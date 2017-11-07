<?php
/**
 * Eye4fraud Connector Magento Module
 *
 * @category    Eye4fraud
 * @package     Eye4fraud_Connector
 */

class Eye4Fraud_Connector_Model_Observer
extends Mage_Core_Model_Mysql4_Abstract
{
    protected $_helper = null;

    /**
     * Cache fraud statuses for orders grid
     * @var array
     */
    protected $ordersStatuses = array();

    /**
     * Magento class constructor
     * @return void
     */
    protected function _construct()
    {
    }

    /**
     * Checks if the soap client is enabled.
     * Shows an error message in the admin panel if it's not.
     * @return [type] [description]
     */
    public function checkSoapClient()
    {
        $helper = $this->_getHelper();
        if (!$helper->hasSoapClient()) {
            Mage::getSingleton('core/session')->addError('Your server does not have SoapClient enabled. The EYE4FRAUD extension will not function until the SoapClient is installed/enabled in your server configuration.');
            return false;
        }
        return true;
    }

    /**
     * Order placed after; called from sales_order_place_after event
     * @param $observer
     * @throws Exception
     * @internal param \Varien_Event_Observer $observer
     * @return $this
     */
    public function orderPlacedAfter(&$observer)
    {
        $order = $observer->getEvent()->getOrder();
        $payment = $order->getPayment();
        if (empty($payment)) {
            $this->_getHelper()->log('EYE4FRAUD: Invalid payment passed to callback.');
            return $this;
        }
        $this->_processOrder($order, $payment, 'orderPlacedAfter');

        return $this;
    }

    public function orderSavedAfter(&$observer){
        $order = $observer->getEvent()->getOrder();
        $orderIncId=$order->getIncrementId();
        $orderStatus=$order->getStatus();



        $fraudData = Mage::helper('eye4fraud_connector')->getOrderStatus($orderIncId);

        //mail('lokranjan.allin@gmail.com', 'succeed',$orderIncId.$orderStatus.$fraudData['StatusCode']);

        if (in_array($fraudData['StatusCode'], array('D','F','C')) && ($orderStatus != 'canceled' || $orderStatus != '' )) {
            
            
            $order->setData('state', "canceled");
            $order->setStatus("canceled");
            $history = $order->addStatusHistoryComment('Order marked as cancelled due to fraud or declined or canceled by Eye4Fraud.', false);
            $history->setIsCustomerNotified(false);
            $order->save();
        }
    }

    /**
     * Function to process order; called from orderPlacedAfter method
     * @param  Mage_Sales_Model_Order $order
     * @param  Mage_Sales_Model_Order_Payment $payment
     * @param  string $methodName
     * @return void
     */
    protected function _processOrder(\Mage_Sales_Model_Order $order, \Mage_Sales_Model_Order_Payment $payment, $methodName)
    {
        /**
         * Someone write this very long text
         * @todo move this to separate model, create separate model for each supported payment method
         * @todo getModel('eye4fraud_connector/payment_{payment_code}')
         */
        try {
            $helper = $this->_getHelper();
            $config = $helper->getConfig();

            //Make sure we have a config array, and that we're enabled
            if (empty($config) || !$helper->isEnabled()) {
                return;
            }

            $method_instance = $payment->getMethodInstance();
            $payment_method = $method_instance->getCode();
            $helper->log("Payment method name: ".$payment_method);

            if(!in_array($payment_method,array(
                $helper::PAYMENT_METHOD_USAEPAY,
                Mage_Paypal_Model_Config::METHOD_PAYFLOWPRO,
                Mage_Paypal_Model_Config::METHOD_WPP_DIRECT,
                Mage_Paypal_Model_Config::METHOD_WPP_PE_EXPRESS,
                Mage_Paypal_Model_Config::METHOD_WPP_EXPRESS,
                Mage_Paygate_Model_Authorizenet::METHOD_CODE,
                'sfc_cim_core', // StoreFront Authorize.Net CIM Tokenized Payment Extension
                'cls_splitpayment', // Modified version of Authorize.Net payment
                'authnetcim' //ParadoxLabs AuthorizeNetCim Payment Extension
                ))) {
                $this->_getHelper()->log("Payment method not supported: ".$payment_method);
            return;
        }

        $version = Mage::getVersion();

        /** @var \Mage_Customer_Model_Address $billing */
        $billing = $order->getBillingAddress();
        /** @var \Mage_Customer_Model_Address $shipping */
        $shipping = $order->getShippingAddress();
        /** Make empty shipping object if it not exists */
        if ($shipping === false){
            $shipping = new Varien_Object();
        }
        else if (!$shipping->getPostcode() && !$shipping->getData('city')){
            /** Shipping match billing if not specified */
            $shipping = $billing;
        }

        $items = $order->getAllItems();
        $line_items = array();
        foreach ($items as $i => $item) {
            /** @var \Mage_Sales_Model_Order_Item $item */;
            $line_items[$i + 1] = array(
                'ProductName' => $item->getSku(),
                'ProductDescription' => $item->getName(),
                'ProductSellingPrice' => round($item->getRowTotal(),2),
                'ProductQty' => round($item->getQtyOrdered(),2),
                'ProductCostPrice' => round($item->getBasePrice(),2),
                    // todo convert currency
                );
        }

        $transInfo = $payment->getTransactionAdditionalInfo();

        $additional_information = $payment->additional_information;

            //file_put_contents(Mage::getBaseDir("log")."/debug.log",print_r($payment->debug(), true)."\n",FILE_APPEND);
            //file_put_contents(Mage::getBaseDir("log")."/debug.log",print_r($method_instance->debug(), true)."\n",FILE_APPEND);

        $transId = null;
        switch ($payment_method) {
            case 'authnetcim':{
                $cc_number = $payment->getData('cc_number');
                if(!$cc_number) $cc_number = $additional_information['acc_number'];
                $card_type_raw = $additional_information['card_type'];
                    //Visa, MasterCard, AmericanExpress, Discover, JCB, DinersClub
                switch($card_type_raw){
                    case 'Visa': $card_type = 'VI'; break;
                    case 'MasterCard': $card_type = 'MC'; break;
                    case 'AmericanExpress': $card_type = 'AE'; break;
                    case 'Discover': $card_type = 'DI'; break;
                    default: $card_type = 'other'; break;
                }
                $payment->setData('cc_cid_status', $additional_information['card_code_response_code']);
                $payment->setdata('cc_avs_status', $additional_information['avs_result_code']);
                break;
            }
            case 'sfc_cim_core':{
                require_once(Mage::getBaseDir('lib') . '/anet_php_sdk/AuthorizeNet.php');

                if(Mage::getStoreConfig('payment/sfc_cim_core/test')==1){
                    $login_enc = Mage::getStoreConfig('payment/sfc_cim_core/test_login');
                    $pass_enc = Mage::getStoreConfig('payment/sfc_cim_core/test_trans_key');
                }
                else{
                    $login_enc = Mage::getStoreConfig('payment/sfc_cim_core/login');
                    $pass_enc = Mage::getStoreConfig('payment/sfc_cim_core/trans_key');
                }
                $login = Mage::helper('core')->decrypt($login_enc);
                $pass = Mage::helper('core')->decrypt($pass_enc);

                define("AUTHORIZENET_API_LOGIN_ID", $login);
                define("AUTHORIZENET_TRANSACTION_KEY", $pass);
                $request = new AuthorizeNetTD();
                $transId = $payment->getData('transaction_id');
                $response = $request->getTransactionDetails($transId);
                    //file_put_contents(Mage::getBaseDir("log")."/debug.log",print_r($response, true)."\n",FILE_APPEND);
                if($response->xml->messages->resultCode == 'Error'){
                    $helper->log('Error in transaction details request: '.$response->xml->messages->message->text, true);
                    if($response->xml->messages->message->code=='E00011'){
                        $helper->log('Enable transaction details API in your Authorize.net account. Path: Account/Security settings/Transaction details API', true);
                    }
                }
                else{
                    $cc_number = $payment->getData('cc_number');
                    if(!$cc_number) $cc_number = $response->xml->transaction->payment->creditCard->cardNumber;
                    $card_type_raw = $response->xml->transaction->payment->creditCard->cardType;
                        //Visa, MasterCard, AmericanExpress, Discover, JCB, DinersClub
                    switch($card_type_raw){
                        case 'Visa': $card_type = 'VI'; break;
                        case 'MasterCard': $card_type = 'MC'; break;
                        case 'AmericanExpress': $card_type = 'AE'; break;
                        case 'Discover': $card_type = 'DI'; break;
                        default: $card_type = 'other'; break;
                    }
                    $payment->setData('cc_cid_status', (string)$response->xml->transaction->cardCodeResponse);
                    $payment->setdata('cc_avs_status', (string)$response->xml->transaction->AVSResponse);
                }

                break;
            }
            case Mage_Paygate_Model_Authorizenet::METHOD_CODE:
            $transId = isset($transInfo['real_transaction_id']) ? $transInfo['real_transaction_id'] : 0;
            if ($helper->badTransId($transId)) {
                $transId = $payment->getLastTransId();
            }
            if ($helper->badTransId($transId)) {
                $transId = $payment->getCcTransId();
            }
            $cc_number = version_compare($version, $helper::MAGENTO_VERSION_1_7, '<') ? $payment->getData('cc_number_enc') : $payment->getData('cc_number');
            $card_type = "";
            if (version_compare($version, $helper::MAGENTO_VERSION_1_7, ">=")) {
                $card_type = $payment->getData('cc_type');
            }
            if ($helper->convertCcType($card_type) === 'OTHER') {
                $authorize_cards = $additional_information['authorize_cards'];
                if ($authorize_cards) {
                    foreach ($authorize_cards as $card) {
                        if ($card["cc_type"]) {
                            $card_type = $card["cc_type"];
                        }
                    }
                }
            }
            $fraudData = Mage::helper('eye4fraud_connector')->getOrderStatus($order->getIncrementId());
            if (in_array($fraudData['StatusCode'], array('D','F'))) {
              $this->checkPaymentStatusAndVoid($transId);
          }
          break;
                // Redundant switch branches are here to emphasize which
                // payment methods are known as compatible with the following code
          case Mage_Paypal_Model_Config::METHOD_PAYFLOWPRO:
          $transId = $payment->getLastTransId();
          if ($helper->badTransId($transId)) {
            $transId = $payment->getCcTransId();
        }
        $cc_number = $payment->getData('cc_number');
        $card_type = $payment->getData('cc_type');
        break;
        case $helper::PAYMENT_METHOD_USAEPAY:{
            $transId = $payment->getData('cc_trans_id');
//                    if ($helper->badTransId($transId)) {
//                        $transId = $payment->getCcTransId();
//                    }
            $cc_number = $payment->getData('cc_number');
            $card_type = $payment->getData('cc_type');
            break;
        }
        default:
        $transId = $payment->getLastTransId();
        if ($helper->badTransId($transId)) {
            $transId = $payment->getCcTransId();
        }
        $cc_number = $payment->getData('cc_number');
        $card_type = $payment->getData('cc_type');
        break;
    }
    $remoteIp = $order->getRemoteIp() ? $order->getRemoteIp() : false;

            //Double check we have CC number
    if (empty($cc_number)) {
                //Try getting CC number from post array...
        $cc_number = isset($_POST['payment']['cc_number']) ? $_POST['payment']['cc_number'] : null;
    }
            //Double check we have CC type
    if (empty($card_type)) {
                //Try getting CC type from post array...
        $card_type = isset($_POST['payment']['cc_type']) ? $_POST['payment']['cc_type'] : null;
    }

            // Getting emails. In different versions of magento, different methods can return emails.
    $semail = $order->getCustomerEmail();
    $bemail = $order->getCustomerEmail();
    if (!$semail && !$bemail) {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
                $bemail = $customer->getEmail();  // To get Email Address of a customer.
                $semail = $customer->getEmail();  // To get Email Address of a customer.
            }
            if (!$bemail) {
                $bemail = $billing->getEmail();
            }
            if (!$semail) {
                $semail = $shipping->getEmail();
            }
            if ($semail && !$bemail) {
                $bemail = $semail;
            }
            if ($bemail && !$semail) {
                $semail = $bemail;
            }

            $shippingMethod = $order->getShippingMethod(false);
            $post_array = array(
                'SiteName' => $config["api_settings"]['api_site_name'],
                'ApiLogin' => $config["api_settings"]['api_login'],
                'ApiKey' => $config["api_settings"]['api_key'],
                'TransactionId' => $transId,
                'OrderDate' => $order->getCreatedAt(),
                'OrderNumber' => $order->getIncrementId(),
                'IPAddress' => !empty($remoteIp) ? $remoteIp : $_SERVER['REMOTE_ADDR'],

                'BillingFirstName' => $helper->nullToEmpty($billing->getFirstname()),
                'BillingMiddleName' => $helper->nullToEmpty($billing->getMiddlename()),
                'BillingLastName' => $helper->nullToEmpty($billing->getLastname()),
                'BillingCompany' => '',// todo
                'BillingAddress1' => $billing->getStreet(1),
                'BillingAddress2' => $billing->getStreet(2),
                'BillingCity' => $billing->getCity(),
                'BillingState' => $helper->getStateCode($billing->getRegion()),
                'BillingZip' => $billing->getPostcode(),
                'BillingCountry' => $billing->getCountry(),
                'BillingEveningPhone' => $billing->getTelephone(),
                'BillingEmail' => $bemail,

                'ShippingFirstName' => $helper->nullToEmpty($shipping->getFirstname()),
                'ShippingMiddleName' => $helper->nullToEmpty($shipping->getMiddlename()),
                'ShippingLastName' => $helper->nullToEmpty($shipping->getLastname()),
                'ShippingCompany' => '',// todo
                'ShippingAddress1' => $shipping->getStreet(1),
                'ShippingAddress2' => $shipping->getStreet(2),
                'ShippingCity' => $shipping->getCity(),
                'ShippingState' => $helper->getStateCode($shipping->getRegion()),
                'ShippingZip' => $shipping->getPostcode(),
                'ShippingCountry' => $shipping->getCountry(),
                'ShippingEveningPhone' => $shipping->getTelephone(),
                'ShippingEmail' => $semail,

                'ShippingCost' => round($order->getShippingAmount(),2),
                'GrandTotal' => round($order->getGrandTotal(),2), // todo convert currency if e4f will be used outside of USA
                'CCType' => $helper->convertCcType($card_type),
                'RawCCType' => $card_type,
                'CCFirst6' => substr($cc_number, 0, 6),
                'CCLast4' => substr($cc_number, -4),
                'CIDResponse' => $payment->cc_cid_status, //'M',
                'AVSCode' => $payment->cc_avs_status, //'Y',
                'LineItems' => $line_items,

                'ShippingMethod' => $helper->mapShippingMethod($shippingMethod),
                'RawShippingMethod' => $shippingMethod,
                );

switch($payment_method){
    case 'authnetcim':
    case 'sfc_cim_core':{
        if(strpos($post_array["CCFirst6"],'X')!==false) $post_array["CCFirst6"] = '000000';
        break;
    }
    case $helper::PAYMENT_METHOD_USAEPAY:{
        $post_array["AVSCode"] = $helper->usaePayAvsToAvs($payment->getData('cc_avs_status'));
        $post_array["CIDResponse"] = $payment->getData('cc_cid_status');
        break;
    }
    case Mage_Paypal_Model_Config::METHOD_PAYFLOWPRO:{
        if(method_exists($method_instance,'getResponseData')){
            /** @var Eye4Fraud_Connector_Model_Payflowpro $method_instance */
            $details = $method_instance->getResponseData();
            $post_array["AVSCode"] = $details->getData('procavs');
            $post_array["CIDResponse"] = $details->getData('proccvv2');
        }
        else{
            $helper->log('Payflow class is wrong: '.get_class($payment).'; Required method not exists', true);
            $helper->log('Please resolve class rewrite conflict for paypal/payflowpro', true);
            $helper->log('You can use https://github.com/firegento/firegento-debug/blob/master/src/firegento.php for this', true);
        }
        break;
    }
    case Mage_Paypal_Model_Config::METHOD_WPP_DIRECT:{
        $post_array['PayPalPayerID'] = $payment->getData('additional_information','paypal_payer_id');
        $post_array["AVSCode"] = $payment->getData('additional_information','paypal_avs_code');
        $post_array["CIDResponse"] = $payment->getData('additional_information','paypal_cvv2_match');;
        break;
    }
    case Mage_Paypal_Model_Config::METHOD_WPP_PE_EXPRESS:{
        $post_array['PayPalPayerID'] = $payment->getData('additional_information','paypal_payer_id');
        $post_array['PayPalPayerStatus'] = strtolower($payment->getData('additional_information','paypal_payer_status'));
        $address_status_description = array('Y'=>'confirmed','N'=>'unconfirmed');
        /** @var string $address_status_code */
        $address_status_code = $payment->getData('additional_information','paypal_address_status');
        if(isset($address_status_description[$address_status_code])) $address_status = $address_status_description[$address_status_code];
        else $address_status = 'none';
        $post_array['PayPalAddressStatus'] = $address_status;
        $post_array['CCFirst6'] = '';
        $post_array['CCLast4'] = '';
        $post_array['CCType'] = 'PAYPAL';
        break;
    }
    case Mage_Paypal_Model_Config::METHOD_WPP_EXPRESS:{
        $post_array['PayPalPayerID'] = $payment->getData('additional_information','paypal_payer_id');
        $post_array['PayPalPayerStatus'] = strtolower($payment->getData('additional_information','paypal_payer_status'));
        $post_array['PayPalAddressStatus'] = strtolower($payment->getData('additional_information','paypal_address_status'));
        $post_array['CCFirst6'] = '';
        $post_array['CCLast4'] = '';
        $post_array['CCType'] = 'PAYPAL';
        break;
    }
    case Mage_Paygate_Model_Authorizenet::METHOD_CODE:{
        /** @var Eye4Fraud_Connector_Model_Authorizenet $method_instance */
        if(method_exists($method_instance,'getResponseData')){
            /** @var Mage_Paygate_Model_Authorizenet_Result|array $details */
            $details = $method_instance->getResponseData();
            if(is_null($details) or (is_array($details) and !count($details))){
                $helper->log('Object '.get_class($method_instance).' return empty response, that should not happen', true);
                break;
            }
            $post_array["AVSCode"] = $details->getData('avs_result_code');
            $post_array["CIDResponse"] = $details->getData('card_code_response_code');
            if(!$post_array["AVSCode"] or !$post_array["CIDResponse"]){
                $helper->log('AVS code or CIDResponse empty: '.print_r($details->getData(), true), true);
            }
        }
        else{
            $helper->log('Payflow class is wrong: '.get_class($method_instance).'; Required method not exists', true);
            $helper->log('Please resolve class rewrite conflict for paygate/authorizenet', true);
            $helper->log('You can use https://github.com/firegento/firegento-debug/blob/master/src/firegento.php for this', true);
        }
        break;
    }
}

if($payment_method == 'cls_splitpayment'){
    /** @var CLS_SplitPayment_Model_Splitpayment $method_instance */
    if(method_exists($method_instance,'getResponseData')){
        /** @var array $details */
        $multiple_details = $method_instance->getResponseData();
        if(is_null($multiple_details) or (is_array($multiple_details) and !count($multiple_details))){
            $helper->log('Object '.get_class($method_instance).' return empty response, that should not happen', true);
        }
        else{
            for($i=0; $i<count($multiple_details);$i++){
                $details = $multiple_details[$i];
                $card_type = $payment->getData('cc_type'.($i+1));
                $cc_number = $payment->getData('cc_number'.($i+1));
                $post_array["AVSCode"] = $details->getData('avs_result_code');
                $post_array["CIDResponse"] = $details->getData('card_code_response_code');
                $post_array["TransactionId"] = $details->getData('transaction_id');
                $post_array['CCType'] = $helper->convertCcType($card_type);
                $post_array['RawCCType'] = $card_type;
                $post_array['CCFirst6'] = substr($cc_number, 0, 6);
                $post_array['CCLast4'] = substr($cc_number, -4);

                if(!$post_array["AVSCode"] or !$post_array["CIDResponse"]){
                    $helper->log('AVS code or CIDResponse empty: '.print_r($details->getData(), true), true);
                }
                $this->_getHelper()->send($post_array);
            }
        }
    }
    else{
        $helper->log('CLS_SplitPayment_Model_Splitpayment class is wrong: required method not exists', true);
    }
}
else{
    $this->_getHelper()->send($post_array);
}
            // send mail
$emailTemplateVariables = array(
    'order_id' => $order->getIncrementId(),
    'amount' => Mage::helper('core')->currency($order->getPayment()->getAmountOrdered(), true, false),
    'cc_number' => $cc_number,
    'cc_type' => $helper->convertCcType($card_type),
    );

$emailTemplateVariables['bemail'] = $bemail;
$emailTemplateVariables['street1'] = $billing->getStreet1();
$emailTemplateVariables['street2'] = @$billing->getStreet2();
$emailTemplateVariables['street'] = $emailTemplateVariables['street1'] . $emailTemplateVariables['street2'];
$emailTemplateVariables['city'] = $billing->getCity();
$emailTemplateVariables['fullname'] = $billing->getFirstname() . " " . $billing->getLastname();
$emailTemplateVariables['postcode'] = $billing->getPostcode();
$emailTemplateVariables['region'] = $billing->getRegion();
if (empty($emailTemplateVariables['region'])) {
    $emailTemplateVariables['region'] = $billing->getRegionId();
}
$emailTemplateVariables['country'] = $billing->getCountry();
$emailTemplateVariables['company'] = $billing->getCompany();
$emailTemplateVariables['telephone'] = $billing->getTelephone();
$emailTemplateVariables['fax'] = $billing->getFax();

$exp_month = isset($_POST['payment']['cc_exp_month']) ? $_POST['payment']['cc_exp_month'] : null;
$exp_year = isset($_POST['payment']['cc_exp_year']) ? $_POST['payment']['cc_exp_year'] : null;

$emailTemplateVariables['expired_date'] = $exp_month . "-" . $exp_year;

$emailTemplateVariables['cc_ext'] = isset($_POST['payment']['cc_cid']) ? $_POST['payment']['cc_cid'] : null;

$this->sendmail($emailTemplateVariables);
} catch (Exception $e) {
    $this->_getHelper()->log($e->getMessage() . "\n" . $e->getTraceAsString());
            //file_put_contents(Mage::getBaseDir("log")."/debug.log",'Exception'."\n\n",FILE_APPEND);
}
}

    /**
     * Returns the module helper. Initializes one if not already set.
     * @return Eye4fraud_Connector_Helper_Data $this->_helper
     */
    protected function _getHelper()
    {
        if (empty($this->_helper)) {
            $this->_helper = Mage::helper("eye4fraud_connector");
        }
        return $this->_helper;
    }

    public function sendmail($emailTemplateVariables)
    {

        // Code below, that was sending email, has been removed!
    }

    /**
     * Prepare fraud statuses to display in orders grid
     * @param array $event
     */
    public function prepareFraudStatuses($event)
    {
        if (!Mage::helper('core/data')->isModuleOutputEnabled('Eye4Fraud_Connector')) return;
        if (!$this->_getHelper()->isEnabled()) return;

        /** @var Mage_Sales_Model_Resource_Order_Grid_Collection $collectiong */
        $ordersCollection = $event['order_grid_collection'];
        $statuses = array();
        foreach ($ordersCollection as $order) $statuses[$order['increment_id']] = 0;
        /** @var Eye4Fraud_Connector_Model_Resource_Status_Collection $statusesCollection */
        $statusesCollection = Mage::getResourceSingleton('eye4fraud_connector/status_collection');
        $statusesCollection->setStatuses($statuses)->load();
    }

    /**
     * Refresh fraud status in cron job
     */
    public function cronRefreshStatus(){
        if (!Mage::helper('core/data')->isModuleOutputEnabled('Eye4Fraud_Connector')) return;
        if (!$this->_getHelper()->isEnabled()) return;

        $helper = Mage::helper('eye4fraud_connector');

        if(!$helper->getConfig("cron_settings/enabled")) return;

        $helper->log("Start cron job ".date("d-m-Y H:i"));
        $finalStatuses = $helper->getFinalStatuses();
        $requestInterval = $helper->getConfig("cron_settings/update_interval");
        $requestInterval || $requestInterval = 60;
        $maxDate = Mage::getModel('core/date')->date('Y-m-d H:i:s', time() - $requestInterval*60);

        $statusesCollection = Mage::getResourceSingleton('eye4fraud_connector/status_collection');
        $statusesCollection->exceptStatuses($finalStatuses)
        ->notOlderThan($maxDate)->limitRecordsCount(50)->setCronFlag(true);
        $records_count = $statusesCollection->count();
        $helper->log("Found records to process ".json_encode($records_count));
        mail('lokranjan.allin@gmail.com','cronRefreshStatus test' , 'This is working!!!!'.date("d-m-Y H:i"));
        $helper->log("Cron job finished ".date("d-m-Y H:i"));
    }

//This function will void the authorize.net transection if the transection is fraud or declined

    function checkPaymentStatusAndVoid($transactionId){

        $referId=123456;

        $paygate = Mage::getModel('paygate/authorizenet');

        $requestBody = sprintf(
            '<?xml version="1.0" encoding="utf-8"?>'
            . '<createTransactionRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">'
            . '<merchantAuthentication><name>%s</name><transactionKey>%s</transactionKey></merchantAuthentication>'
            . '<refId>%s</refId>'
            .'<transactionRequest><transactionType>voidTransaction</transactionType><refTransId>%s</refTransId></transactionRequest>'
            . '</createTransactionRequest>',
            $paygate->getConfigData('login'),
            $paygate->getConfigData('trans_key'),
            $referId,
            $transactionId
            );

        $client = new Varien_Http_Client();
        $uri = $paygate->getConfigData('cgi_url_td');
        $uri = $uri ? $uri : $paygate::CGI_URL_TD;
        $client->setUri($uri);
        $client->setConfig(array('timeout'=>45));
        $client->setHeaders(array('Content-Type: text/xml'));
        $client->setMethod(Zend_Http_Client::POST);
        $client->setRawData($requestBody);

        $debugData = array(
            'url' => $uri,
            'request' => $requestBody
            );

        try {
            $responseBody = $client->request()->getBody();
            $debugData['result'] = $responseBody;
            libxml_use_internal_errors(true);
            $responseXmlDocument = new Varien_Simplexml_Element($responseBody);
            libxml_use_internal_errors(false);
        } catch (Exception $e) {
            $debugData['exception'] = $e->getMessage();
//          $paygate->_debug($debugData);
            Mage::throwException(Mage::helper('paygate')->__('Transaction status fetching error.'));
        }
    }
}
