<?php
/**
 * Eye4fraud Connector Magento Module
 *
 * @category    Eye4fraud
 * @package     Eye4fraud_Connector
 */

class Eye4Fraud_Connector_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    protected $_config = null;
    protected $_logFile = "eye4fraud_debug.log";

    const PAYMENT_METHOD_USAEPAY = 'usaepay';
    const MAGENTO_VERSION_1_7 = '1.7';						//This is run through version_compare()

    /**
     * List of statuses allowed to save in DB
     * @var array
     */
    protected $finalStatuses = array('A','D','I','C','F','M','INV','ALW');

    /**
     * Returns store config item
     * @param string $str
     * @return string
     */
    public function getConfig($str='')
    {
        if($str) return Mage::getStoreConfig('eye4fraud_connector/'.$str);
    	if (empty($this->_config)){
    		$this->_config = Mage::getStoreConfig('eye4fraud_connector');
    	}
        return $this->_config;
    }

    /**
     * Final fraud statuses, which not require update from remote
     * @return array
     */
    public function getFinalStatuses(){
        return $this->finalStatuses;
    }

    /**
     * Write log on debug
     * @param mixed $txt Item to log
     * @param bool $force Force log write
     */
    public function log($txt, $force = false){
        if (!$this->isDebug() && !$force) return;
        if($this->isDebug()) $force = true;
        Mage::log($txt, null, $this->_logFile, $force);
    }

    /**
     * Checks config to see if module is enabled
     * @return boolean 
     */
    public function isEnabled(){
    	// Check if soap client exists - if not, we cannot enable the module
    	if (!$this->hasSoapClient()){
    		return false;
    	}

    	$config = $this->getConfig();
    	return !isset($config['api_settings']['enabled']) ? false : (bool)$config['api_settings']['enabled'];
    }

    /**
     * Check if we have the Soap Client enabled on this server
     * @return boolean
     */
    public function hasSoapClient(){
    	return class_exists("SoapClient");
    }

    /**
     * Checks config to see if module is debug_mode
     * @return boolean 
     */
    public function isDebug(){
    	$config = $this->getConfig();
    	return !isset($config['api_settings']['debug_mode']) ? false : (bool)$config['api_settings']['debug_mode'];
    }

    /**
     * Replace empty array values with empty string
     * @param  array  $x
     * @return array|string
     */
    public function cleanArray(array $x)
    {
        if(is_array($x))
        {
            $array = array();
            foreach($x as $key => $val)
            {
                $array[$key] = is_array($val) ? $this->cleanArray($val) : $val;
            }

            return $array ? $array : '';
        }

        return trim($x);
    }

    /**
     * Convert objects or arrays of objects into pure array
     * @param  [type] $x [description]
     * @return array|string
     */
    public function makeArray($x)
    {
        $array = array();

        if(is_array($x) || is_object($x))
        {
            foreach((array) $x as $key => $val)
            {
                $array[$key] = $this->makeArray($val);
            }

            return $array;
        }

        return $x;
    }

    /**
     * Runs both makeArray and CleanArray on parameter $x
     * @param  array $x
     * @return array
     */
    public function makeCleanArray($x)
    {
        return $this->cleanArray($this->makeArray($x));
    }

    /**
     * Converts falsey values to empty string
     * @param  mixed $value 
     * @return mixed|string
     */
    public function nullToEmpty($value) {
        return $value ? $value : '';
    }

    /**
     * Returns value from array by key
     * @param  array $map
     * @param  string $key 
     * @return mixed|null
     */
    public function mapGet($map, $key) {
        return array_key_exists($key, $map) ? $map[$key] : null;
    }

    /**
     * Returns false if transaction id is bad
     * @param  mixed $transId 
     * @return bool
     */
    public function badTransId($transId) {
        return $transId == '0' || empty($transId);
    }

    /**
     * Converts credit card type
     * @param  string $type 
     * @return string 
     */
    public function convertCcType($type) {
        switch ($type) {
            case "AE": return "AMEX";
            case "VI": return "VISA";
            case "MC": return "MC";
            case "DI": return "DISC";
        }
        return "OTHER";
    }

    /**
     * Returns the mapper shipping method
     * @param  string $method
     * @return string
     */
    public function mapShippingMethod($method) {
        if (!$method) {
            return '';
        }
        list($carrier, $method) = explode('_', $method, 2);

        $map = array(
            'freeshipping' => array(
                'freeshipping' => 'Other',
            ),
            'ups' => array(
                '1DM' => '1D', // Next Day Air Early AM
                '1DML' => '1D', // Next Day Air Early AM Letter
                '1DA' => '1D', // Next Day Air
                '1DAL' => '1D', // Next Day Air Letter
                '1DAPI' => '1D', // Next Day Air Intra (Puerto Rico)
                '1DP' => '1D', // Next Day Air Saver
                '1DPL' => '1D', // Next Day Air Saver Letter
                '2DM' => '2D', // 2nd Day Air AM
                '2DML' => '2D', // 2nd Day Air AM Letter
                '2DA' => '2D', // 2nd Day Air
                '2DAL' => '2D', // 2nd Day Air Letter
                '3DS' => '3D', // 3 Day Select
                'GND' => 'Other', // Ground
                'GNDCOM' => 'Other', // Ground Commercial
                'GNDRES' => 'Other', // Ground Residential
                'STD' => 'Other', // Canada Standard
                'XPR' => '1D', // Worldwide Express
                'WXS' => '2D', // Worldwide Express Saver
                'XPRL' => '1D', // Worldwide Express Letter
                'XDM' => '1D', // Worldwide Express Plus
                'XDML' => '2D', // Worldwide Express Plus Letter
                'XPD' => '2D', // Worldwide Expedited
            ),
            'usps' => array(
                'FIRST CLASS' => 'Other', // First-Class
                'PRIORITY' => '2D', // Priority Mail
                'EXPRESS' => '1D', // Express Mail
                'BPM' => 'Other', // Bound Printed Matter
                'PARCEL' => 'Other', // Parcel Post
                'MEDIA' => 'Other', // Media Mail
                'LIBRARY' => 'Other', // Library
            ),
            'dhl' => array(
                'IE' => '3D', // International Express
                'E SAT' => '3D', // Express Saturday
                'E 10:30AM' => '1D', // Express 10:30 AM
                'E' => '3D', // Express
                'N' => '1D', // Next Afternoon
                'S' => '2D', // Second Day Service
                'G' => 'Other', // Ground
            ),
            'fedex' => array(
                'EUROPE_FIRST_INTERNATIONAL_PRIORITY' => '1D', // Europe First Priority
                'FEDEX_1_DAY_FREIGHT' => '1D', // 1 Day Freight
                'FEDEX_2_DAY_FREIGHT' => '2D', // 2 Day Freight
                'FEDEX_2_DAY' => '2D', // 2 Day
                'FEDEX_3_DAY_FREIGHT' => '3D', // 3 Day Freight
                'FEDEX_EXPRESS_SAVER' => '3D', // Express Saver
                'FEDEX_GROUND' => 'Other', // Ground
                'FIRST_OVERNIGHT' => '1D', // First Overnight
                'GROUND_HOME_DELIVERY' => 'Other', // Home Delivery
                'INTERNATIONAL_ECONOMY' => 'Other', // International Economy
                'INTERNATIONAL_ECONOMY_FREIGHT' => 'Other', // Intl Economy Freight
                'INTERNATIONAL_FIRST' => '1D', // International First
                'INTERNATIONAL_GROUND' => 'Other', // International Ground
                'INTERNATIONAL_PRIORITY' => '3D', // International Priority
                'INTERNATIONAL_PRIORITY_FREIGHT' => '3D', // Intl Priority Freight
                'PRIORITY_OVERNIGHT' => '1D', // Priority Overnight
                'SMART_POST' => 'Other', // Smart Post
                'STANDARD_OVERNIGHT' => '1D', // Standard Overnight
                'FEDEX_FREIGHT' => 'Other', // Freight
                'FEDEX_NATIONAL_FREIGHT' => 'Other', // National Freight
            )
        );

        $carrier_e4f_codes = $this->mapGet($map, $carrier);
        if (!$carrier_e4f_codes) {
            return 'Other';
        }
        $e4f_method_code = $this->mapGet($carrier_e4f_codes, $method);
        return $e4f_method_code;
    }

    /**
     * Returns state code from State Name
     * @param  string $stateName 
     * @return string
     */
    public function getStateCode($stateName) {
        $stateName = strtolower($stateName);
        $US_STATES = array(
            'AK' => 'Alaska',
            'AL' => 'Alabama',
            'AR' => 'Arkansas',
            'AZ' => 'Arizona',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'IA' => 'Iowa',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'MA' => 'Massachusetts',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MO' => 'Missouri',
            'MS' => 'Mississippi',
            'MT' => 'Montana',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'NE' => 'Nebraska',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NV' => 'Nevada',
            'NY' => 'New York',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WI' => 'Wisconsin',
            'WV' => 'West Virginia',
            'WY' => 'Wyoming',
            //  Armed Forces
            'AA' => 'Armed Forces Americas (except Canada)',
            'AE' => 'Armed Forces Africa,Canada,Europe,Middle East',
            'AP' => 'Armed Forces Pacific',
            //  Commonwealth/Territory: Abbreviation:
            'AS' => 'American Samoa',
            'DC' => 'District of Columbia',
            'FM' => 'Federated States of Micronesia',
            'GU' => 'Guam',
            'MH' => 'Marshall Islands',
            'MP' => 'Northern Mariana Islands',
            'PW' => 'Palau',
            'PR' => 'Puerto Rico',
            'VI' => 'Virgin Islands',
        );
        foreach ($US_STATES as $code => $name) {
            if (strtolower($name) == $stateName) {
                return $code;
            }
        }
        return $stateName; // probably it is not USA so returning unchanged
    }
    /*
        'X' => 'X - Street and 9-digit ZIP match',
        'Y' => 'Y - Street and 5-digit ZIP match',
        'A' => "A - Street matches, 5 & 9-digit ZIP no match",
        'W' => 'W - Street not match, 9-digit ZIP matches',
        'Z' => 'Z - Street not match, 5-digit ZIP matches',
        'N' => "N - Street, 5 & 9-digit ZIP don't match",
        'U' => "U - Address unavailable",
        'R' => "R - Retry. Issuer's System Unavailable",
        'E' => 'E - AVS data is invalid',
        'S' => 'S - U.S. issuing bank does not support AVS',
        'D' => 'D - Street and ZIP match for Intern. Trans.',
        'M' => 'M - Street and ZIP match for Intern. Trans',
        'B' => 'B - Street Match for Intern. Trans. ZIP unverified',
        'P' => 'P - ZIP match for Intern. Trans. Street unverified',
        'C' => 'C - Street, ZIP not verified for Intern. Trans.',
        'I' => 'I - Address not verified by International issuer',
        'G' => 'G - Non-US. Issuer does not participate',
    */

    /**
     * Converts usaePayAvs to Avs
     * @param  string $avs
     * @return string
     */
    public function usaePayAvsToAvs($avs) {
        $avs_code = array(
            '' => 'E', // AVS Data is invalid
            // Domestic Response Codes
            'YYY' => 'Y', // Address and 5-digit Zip Code match
            'YYA' => 'Y',
            'YYD' => 'Y',
            'Y' => 'Y',
            'NYZ' => 'Z', // 5-digit Zip Code matches only
            'Z' => 'Z',
            'YNA' => 'A', // Address matches only
            'YNY' => 'A',
            'A' => 'A',
            'NNN' => 'N', // Neither Address nor Zip Code match<br />
            'NN' => 'N',
            'N' => 'N',
            'YYX' => 'X', // Address and 9-digit Zip Code match
            'X' => 'X',
            'NYW' => 'W', // 9-digit Zip Code matches only
            'W' => 'W',
            'XXW' => '?', // Card Number Not On File
            'XXU' => 'U', // Address info not verified for domestic transaction
            'XXR' => 'R', //Retry / System Unavailable
            'R' => 'R',
            'U' => 'R',
            'E' => 'R',
            'XXS' => 'S', // Service not supported
            'S' => 'S',
            'XXE' => 'E', // Address verification not allowed for card type
            'XXG' => 'G', // Global non-AVS participant
            'G' => 'G',
            'C' => 'G',
            'I' => 'G',
            // International Response Codes
            'YYG' => 'B', // Address: Match & Zip: Not Compatible
            'B' => 'B',
            'M' => 'B',
            'GGG' => 'D', // International Address: Match & Zip: Match<br />
            'YYF' => 'D',
            'D' => 'D',
            'YGG' => 'P', //International Address: No Compatible & Zip: Match
            'NYP' => 'P',
            'P' => 'P',
        );
        return isset($avs_code[$avs]) ? $avs_code[$avs] : 'R';
    }

    /**
     * Send request to eye4fraud servers
     * @param  array $post_array
     * @return string
     */
    public function send($post_array) {

        $response = "";
        //Log $post_array if in debug mode
        $this->log("Sendig post:");
        $this->log($post_array);

        $post_query = http_build_query($post_array);
        $ch = curl_init('https://www.eye4fraud.com/api/');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        //Log $response if in debug mode
        $this->log("Response:");
        $this->log($response);

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        //Log $code for bad response if in debug mode
        if ($response != 'ok') {
            $this->log("=== E4F Observer::send() Error, \$response NOT ok ===");
            $this->log("Code: $code");
        }

        return $response;
    }

    /**
     * @param $orderId
     * @return array
     */
    public function getOrderStatus($orderId){
        $postData = array(
            'ApiLogin' => $this->getConfig('api_settings/api_login'),
            'ApiKey' => $this->getConfig('api_settings/api_key'),
            'Action' => 'getOrderStatus',
            'OrderNumber' => $orderId
        );
        $response = $this->send($postData);
//        $response = '<response>
//    <keyvalue>
//        <key>OrderNumber</key>
//        <value>'.$orderId.'</value>
//    </keyvalue>
//    <keyvalue>
//        <key>StatusCode</key>
//        <value>D</value>
//    </keyvalue>
//    <keyvalue>
//        <key>Description</key>
//        <value>Declined</value>
//    </keyvalue>
//</response>';
        $result = array();
        if($response!==false){
            $dom = new DOMDocument();
            $dom->loadXML($response);
            $nodesList = $dom->getElementsByTagName("keyvalue");
            $result['error'] = true;
            $result['StatusCode'] = 'IER';
            $result['Description'] = 'Unknown Error';
            if($nodesList->length) {
                foreach($nodesList as $node){
                    $item = array();
                    foreach($node->childNodes as $node2){
                        if(in_array($node2->nodeName, array('key','value'))) $item[$node2->nodeName] = $node2->nodeValue;
                    }
                    $result[$item['key']] = $item['value'];
                }
                $result['error'] = false;
                $result['Description'] = trim($result['Description']);
            }
            else{
                $nodesList = $dom->getElementsByTagName("errors");
                if($nodesList->length) {
                    $item = array();
                    foreach ($nodesList as $node) {
                        foreach($node->childNodes as $node2){
                            if(in_array($node2->nodeName, array('error'))) $item[$node2->nodeName] = $node2->nodeValue;
                        }
                    }
                    if($item['error']){
                        $result['StatusCode'] = 'RER';
                        $result['Description'] = $item['error'];
                    }
                }
            }
        }
        return $result;
    }
}
