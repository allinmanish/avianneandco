<?php
/*
 © Copyright Webgility LLC 2012
    ----------------------------------------
 All materials contained in these files are protected by United States copyright
 law and may not be reproduced, distributed, transmitted, displayed, published or
 broadcast without the prior written permission of Webgility LLC. You may not
 alter or remove any trademark, copyright or other notice from copies of the
 content.
 File last updated: 04/01/2012
*/


class Webgility_Shop1_Model_Runmobile
{
    /**
     * Available options
     *
     * @var array
     */
    protected $__ENCODE_RESPONSE = true;
    protected $_options;
    protected $STORE_NAME= 'NOT_FOUND_STORE_NAME';
    protected $STORE_ID =null;
    protected $RequestOrders = array();

    const STATE_NEW        = 'new';
    const STATE_PROCESSING = 'processing';
    const STATE_COMPLETE   = 'complete';
    const STATE_CLOSED     = 'closed';
    const STATE_CANCELED   = 'canceled';
    const STATE_HOLDED     = 'holded';
    const STATE_UNHOLDED   = 'unholded';
	
    protected   $types = array('AE'=>'American Express', 'VI'=>'Visa', 'MC'=>'MasterCard', 'DI'=>'Discover','OT'=>'Other',''=>'');
    protected   $carriers = array('dhl'=>'DHL',
                                  'fedex'=>'FedEx',
                                  'ups'=>'UPS',
                                  'usps'=>'USPS',
                                  'freeshipping'=>"Free Shipping" ,
                                  'flatrate'=>"Flat Rate",
                                  'tablerate'=>"Best Way");
    protected   $carriers_ =array('DHL'=>'dhl',
                                  'FEDEX'=>'fedex',
                                  'UPS'=>'ups',
                                  'USPS'=>'usps',
								  'FEDERAL EXPRESS'=>'fedex',
                                  'UNITED PARCEL SERVICE'=>'ups',
                                  'UNITED STATES POSTAL SERVICE'=>'usps',
                                  "FREE SHIPPING" =>'freeshipping',
                                  'FLAT RATE'=>"flatrate",
                                  "BEST WAY" =>'tablerate');
    protected   $PayMethodsCC = array( 'paypal_express'      ,
                                       'paypal_standard'     ,
                                       'paypal_direct'       ,
                                       'paypaluk_express'    ,
                                       'paypaluk_direct'     ,
                                       'ccsave'              ,
                                       'authorizenet'        ,
                                       'payflow_pro'         );
    protected   $PayMethods = array(   'paypal_express'      => 'PayPal Express',
                                       'paypal_standard'     => 'PayPal Standard',
                                       'paypal_direct'       => 'Paypal Direct',

                                       'paypaluk_express'    => 'PaypalUk Express',
                                       'paypaluk_direct'     => 'PaypalUk Direct',
                                       'ccsave'              => 'Credit Card (saved)',
                                       'checkmo'             => 'Check / Money order',
                                       'free'                => 'No Payment Information Required',
                                       'purchaseorder'       => 'Purchase Order' ,
                                       'authorizenet'        => 'Credit Card (Authorize.net)',
                                       'payflow_pro'         => 'Credit Card (Payflow Pro)'
                                      );

    protected $status_list = array(
                      self::STATE_PROCESSING,
                      self::STATE_COMPLETE,
                      self::STATE_CLOSED,
                      self::STATE_CANCELED,
                      self::STATE_HOLDED,
                      self::STATE_UNHOLDED
                      );

    /**
     * Script arguments


     *
     * @var array
     */
    protected $_orders = null;
    protected $xmlRequest = null;
    protected $xmlResponse = null;
    protected $root = null;
    protected $envelope = null;
    protected $_current_order = null;
    protected $send_email = false;
    protected $Msg = array();
    protected $result = '';
    protected $RequestParams = array();
    protected $filters = array();
    protected $QB_NUMBER_OF_DAYS,$QB_ORDER_START_NUMBER,$QB_PROVIDER;
    protected $_tagName = array(),  $_tagAttributes= array(),$_tagContents=array(),$_tagTags= array();
   
    public function run()
    {}

    public  function CheckUser($username,$password)
	{       
	   try
	   {
	 	   // return true;
		   $user = Mage::getSingleton('admin/user');
		   $userRole= Mage::getSingleton('admin/mysql4_acl_role');
		   //$userRole = Mage::getSingleton('admin/role');
		   if ($user->authenticate($username, $password)) 
		   {
			  $loadRole=$userRole->load($user->getRoles($user));
			  return 0;
		   }
		   else
		   {
		   	   $details =  $user->loadByUsername($username);
			   if($details->user_id >0)
			   return 2;
			   else
			   return 1;
			   exit;
		   }
       }catch (Exception $e) 
	   {
			return 1;				
			exit;
       }
	}

	public  function  GetStoreId()
	{
		return $this->STORE_ID;
	}
    //***************************************************
    //
    //      Update Orders Shipping Status Service
    //
    //***************************************************	
	public  function  UpdateOrdersShippingStatus($orderId,$storeId=1)
	{
		$orders = Mage::getResourceModel('sales/order_collection')
					->addAttributeToSelect('*')
					->addFieldToFilter('increment_id', $orderId)
					->addAttributeToFilter('store_id', $storeId)
					->load();
		return $orders;
	}

    public function _initShipment($current_order,$RequestOrders,$data)
	{
		try
		{
        	$shipment = false;
			if (!$current_order->getId()) 
			{
				$this->Msg[] = 'Error. Order not longer exist.';
				$this->result = 'Failed';
				return false;
            }
            if (!$current_order->canShip())
			{
                  return false;
            }
            // Not Ship
            $convertor  = Mage::getModel('sales/convert_order');
            $_shipment    = $convertor->toShipment($current_order);
			$savedQtys = _getItemQtys($data);
            foreach ($current_order->getAllItems() as $orderItem)
			{
            	if(!$orderItem->getQtyToShip())
				{
                    continue;
                }
                $_item = $convertor->itemToShipmentItem($orderItem);
                if (isset($savedQtys[$orderItem->getId()]))
				{
                    $qty = $savedQtys[$orderItem->getId()];
                }
                else{
                    $qty = $orderItem->getQtyToShip();
                }
                $_item->setQty($qty);
            	$_shipment->addItem($_item);
            }			
			if(is_array($RequestOrders['TRACKINGNUMBER']))
			{
				$t = 0;
				foreach($RequestOrders['TRACKINGNUMBER'] as $trackNumber)
				{
					$trackNumber = $trackNumber;
					if (!empty($trackNumber))
					{
						if (!$CarrierCode =$this->getShippingCode($RequestOrders['SHIPPEDVIA'][$t]))
						{
							$CarrierCode="custom";
							$Title = $RequestOrders['SHIPPEDVIA'][$t];
						}elseif(isset($RequestOrders['SERVICEUSED'][$t])){
							$Title = $RequestOrders['SERVICEUSED'][$t];
						}else{
							$Title = $RequestOrders['SHIPPEDVIA'][$t];
						}
						$_track = Mage::getModel('sales/order_shipment_track')
										->setNumber($trackNumber)
										->setCarrierCode($CarrierCode)
										->setTitle($Title);
						$_shipment->addTrack($_track);
					}
					$t++;
				}
			}else{
				$trackNumber = $RequestOrders['TRACKINGNUMBER'];
				if (!empty($trackNumber))
				{
					if (!$CarrierCode =$this->getShippingCode($RequestOrders['SHIPPEDVIA']))
					{
						$CarrierCode="custom";
						$Title = $RequestOrders['SHIPPEDVIA'];
					}elseif(isset($RequestOrders['SERVICEUSED']))
					{
						$Title = $RequestOrders['SERVICEUSED'];
					}else{
						$Title = $RequestOrders['SHIPPEDVIA'];
					}
					$_track = Mage::getModel('sales/order_shipment_track')
									->setNumber($trackNumber)
									->setCarrierCode($CarrierCode)
									->setTitle($Title);
									$_shipment->addTrack($_track);
				}
			}
			return $_shipment;
		}catch (Exception $e) {
        	$this->Msg[] = "Critical Error _initShipment (Exception e)" ;
        }
    }
	
    public function _saveShipment($shipment)
    {
        $shipment->getOrder()->setIsInProcess(true);
		$transactionSave = Mage::getModel('core/resource_transaction')
								->addObject($shipment)
								->addObject($shipment->getOrder())
								->save();
        return $this;
    }

	public function AddShipmentByOrder($current_order,$RequestOrders,$data)
	{
		try
		{
            if ($shipment = $this->_initShipment($current_order,$RequestOrders,$data))
			{
				$shipment->register();
                $this->Msg[] = 'Create Shipment .';
                $comment = $data['comment_text'];
                $shipment->addComment($comment,true );
                if ($this->send_email)
				{
                    $shipment->setEmailSent(true);
                }
                $this->_saveShipment($shipment);
				if($data['copy_email'] == 1)
				{
					if($data['append_comment'] == 1)
					{	
						$shipment->sendUpdateEmail($this->send_email, $comment);
						$this->send_email;
					}else{
						$shipment->sendUpdateEmail($this->send_email, '');
						$this->send_email;
					}
				}
                return true;
            }else {
                return false;
            }
        }catch(Mage_Core_Exception $e) {
			$this->Msg[] = "Critical Error AddShipment (Mage_Core_Exception e)";
		}
        catch (Exception $e)
		{
			$this->Msg[] = "Critical Error AddShipment (Exception e)" ;
		}
    }
	
	public function sendOrderUpdateEmail($notifyCustomer=true, $comment='')
    {
        $bcc = $this->_getEmails(self::XML_PATH_UPDATE_EMAIL_COPY_TO);
        if (!$notifyCustomer && !$bcc)
		{
            return $this;
        }
        $mailTemplate = Mage::getModel('core/email_template');
        if ($notifyCustomer)
		{
            $customerEmail = $this->getCustomerEmail();
            $mailTemplate->addBcc($bcc);
        }else{
            $customerEmail = $bcc;
        }
        if ($this->getCustomerIsGuest())
		{
            $template = Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_GUEST_TEMPLATE, $this->getStoreId());
            $customerName = $this->getBillingAddress()->getName();
        }else{
            $template = Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_TEMPLATE, $this->getStoreId());
            $customerName = $this->getCustomerName();
        }
        $mailTemplate->setDesignConfig(array('area'=>'frontend', 'store' => $this->getStoreId()))
            ->sendTransactional(
                $template,
                Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_IDENTITY, $this->getStoreId()),
                $customerEmail,
                $customerName,
                array(
                    'order'     => $this,
                    'billing'   => $this->getBillingAddress(),
                    'comment'   => $comment
                )
        	);
        return $this;
    }

	public function getorderitems($Id,$incrementID)
	{
		#config option
		$download_option_as_item =false;
		if($download_option_as_item==true)
		{
			$collection =Mage::getModel('sales/order_item')->getCollection()
			->setOrderFilter($Id);			
		}else{
			$collection =Mage::getModel('sales/order_item')->getCollection()
			->setOrderFilter($Id)			
			->addFieldToFilter('parent_item_id', array('null' => true))
			->setRandomOrder();		
		} 	
		$products = array();
		foreach ($collection as $item)
		{
				$products[] = $item->getProductId();
				$products[] = $item->toArray();
		}
		$productsCollection = Mage::getModel('catalog/product')
									->getCollection()
									->addAttributeToSelect('*')
									->addIdFilter($products)
									->load();
		foreach ($collection as $item)
		{
			$item->setProduct($productsCollection->getItemById($item->getProductId()));
		}
		$collection = $collection->toArray();
		$productsCollection = $productsCollection->toArray();
		return $collection;
	}

	public function addproduct($storeId=1)
	{
		$Product  = Mage::getModel('catalog/product')->setStoreId($storeId);
		$Product->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE);
		$Product->setWebsiteIds(array(Mage::app()->getStore(true)->getWebsite()->getId()));			
		return $Product;
	}

	public function editproduct($storeId=1,$productId)
	{
		$Product = Mage::getModel('catalog/product')
						->setStoreId($storeId);
		$Product->load($productId);
		return $Product;
	}
	
	public function getProduct($storeId=1,$start_no=0,$limit,$datefrom )
	{
		
		
		/*if($start_item_no > 0)
		{
			if($start_item_no>$limit)
			{
				$start_no=intval($start_item_no/$limit)+1;
			}else{
				$start_no=intval($limit/$start_item_no)+1;
			}
		}else{
			$start_no = 0;
		}*/
		
		
		$datefrom = $datefrom? $datefrom:'0';
		$productsCollection = Mage::getResourceModel('catalog/product_collection')
									->addAttributeToSelect('*')
									->addStoreFilter($storeId)
									//->addAttributeToFilter('updated_at', array('gt' => $datefrom,'datetime' => true))	
									->addAttributeToFilter('type_id', array('in' => array('simple','downloadable','virtual','configurable')))	
									->addAttributeToFilter('entity_id', array('gt' => $start_no))
									//->addAttributeToFilter('type_id', array('in' => array('simple','downloadable','virtual','configurable','bundle','grouped')))		
									->addAttributeToSort('entity_id', 'asc')
									->setPageSize($limit)->load();			
		return $productsCollection;
	}

	public function getProductByName($storeId=1,$start_no=0,$limit=20,$itemname)
	{
		/*if($start_item_no > 0)
		{
			if($start_item_no>$limit)
			{
				$start_no=intval($start_item_no/$limit)+1;
			}else{
				$start_no=intval($limit/$start_item_no)+1;
			}
		}else{
			$start_no = 0;
		}*/
		$productsCollection = Mage::getResourceModel('catalog/product_collection')
								->addAttributeToSelect('*')
								->addStoreFilter($storeId)
								->addFieldToFilter(array(array('attribute'=>'name', 'like'=>"%$itemname%")))
								->addAttributeToSort('entity_id', 'asc');
								//->setPageSize($limit)
								//->setCurPage($start_no);			
		return $productsCollection;
	}
	
	
	public function getProductBySku($storeId=1,$start_item_no=0,$items)
	{
		$sku_str_array	=	explode(",", $items);
		
		if($start_item_no > 0)
		{
			if($start_item_no>$limit)
			{
				$start_no=intval($start_item_no/$limit)+1;
			}else{
				$start_no=intval($limit/$start_item_no)+1;
			}
		}else{
			$start_no = 0;
		}
		$productsCollection = Mage::getResourceModel('catalog/product_collection')
								->addAttributeToSelect('*')
								->addStoreFilter($storeId)
								->addFieldToFilter('sku',array("in"=>$sku_str_array))
								//->addFieldToFilter('sku',array("in"=>array('CHOCO40844S','1234','BLACK408237')))
								//->addFieldToFilter('sku','CHOCO40844S')
								//->addFieldToFilter('sku',array(array('eq'=>'1234'),array('eq'=>'CHOCO40844S')))
								->addAttributeToSort('entity_id', 'asc')
								->setPageSize($limit)
								->setCurPage($start_no);			
		return $productsCollection;
	}
	
	public function getProductById($storeId=1,$start_item_no=0,$items)
	{
		if($start_item_no > 0)
		{
			if($start_item_no>$limit)
			{
				$start_no=intval($start_item_no/$limit)+1;
			}else{
				$start_no=intval($limit/$start_item_no)+1;
			}
		}else{
			$start_no = 0;
		}
		$productsCollection = Mage::getResourceModel('catalog/product_collection')
								->addAttributeToSelect('*')
								->addStoreFilter($storeId)
								->addAttributeToFilter('entity_id', $start_item_no)
								->addAttributeToSort('entity_id', 'asc')
								->setPageSize($limit)
								->setCurPage($start_no);			
								
								//echo 'test'.$start_item_no;
								
		return $productsCollection;
	}

	public function getduplicaterecord($productname,$productcode)
	{
		$productsCollection = Mage::getModel('catalog/product')
									->getCollection()
									->addAttributeToSelect('*')
									->addAttributeToFilter('sku', $productcode)
									->load();
		$productsCollection = $productsCollection->toArray();
		if(count($productsCollection)>0)
		{
			return "1";
		}else{
			return "0";
			$productsCollection = Mage::getModel('catalog/product')
										->getCollection()
										->addAttributeToSelect('*')
										->addAttributeToFilter('name', $productname)
										->load();
			$productsCollection = $productsCollection->toArray();
			if(count($productsCollection)>0)
			{
				return "1";
			}
			else
			{
				return "0";
			}
		}
	}	
	
	public function getoptions($product)
	{
		$collection = $product->getOptionInstance()->getProductOptionCollection($product);
		$lastvalues = array();
		$j=0;
		$collection = $collection->toArray();
		if(count($collection['items'])>0)
		{
			foreach($collection['items'] as $items)
			{
				$values = Mage::getModel('catalog/product_option_value')
								->getCollection()
								->addTitleToResult(1)
								->addPriceToResult(1)
								->addOptionToFilter(array($items['option_id']))
								->setOrder('sort_order', 'asc')
								->setOrder('title', 'asc');
				$values = $values->toArray();
				
				for($i=0;$i<(count($values['items']));$i++)
				{
					$values['items'][$i]['option_title']= $items['default_title'];
					$lastvalues[$j] = $values['items'][$i];
					$j++;
				}
			}
			return $lastvalues;
		}
		
	}
	
	public  function  GetOrdersRemained($datefrom,$start_order_no=0,$order_status_list='',$storeId=1)
	{	   
		$order_status_list = str_replace("'","",$order_status_list);
		$order_status_list = explode(",",$order_status_list);		
		$order_status = orderStatustofetch($order_status_list);		   	
		try{
			$datetime1 = explode("-",$datefrom);			
			$datefrom = $datetime1[2]."-".$datetime1[0]."-".$datetime1[1];			
			$datefrom .=" 00:00:00"; 
			$this->_orders = Mage::getResourceModel('sales/order_collection')
									->addAttributeToSelect('*')
									->joinAttribute('billing_firstname', 'order_address/firstname', 'billing_address_id', null, 'left')
									->joinAttribute('billing_lastname', 'order_address/lastname', 'billing_address_id', null, 'left')
									->joinAttribute('billing_street', 'order_address/street', 'billing_address_id', null, 'left')
									->joinAttribute('billing_company', 'order_address/company', 'billing_address_id', null, 'left')
									->joinAttribute('billing_city', 'order_address/city', 'billing_address_id', null, 'left')
									->joinAttribute('billing_region', 'order_address/region', 'billing_address_id', null, 'left')
									->joinAttribute('billing_country', 'order_address/country_id', 'billing_address_id', null, 'left')
									->joinAttribute('billing_postcode', 'order_address/postcode', 'billing_address_id', null, 'left')
									->joinAttribute('billing_telephone', 'order_address/telephone', 'billing_address_id', null, 'left')
									->joinAttribute('billing_fax', 'order_address/fax', 'billing_address_id', null, 'left')
									->joinAttribute('shipping_firstname', 'order_address/firstname', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_lastname', 'order_address/lastname', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_street', 'order_address/street', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_company', 'order_address/company', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_city', 'order_address/city', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_region', 'order_address/region', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_country', 'order_address/country_id', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_postcode', 'order_address/postcode', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_telephone', 'order_address/telephone', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_fax', 'order_address/fax', 'shipping_address_id', null, 'left')
									->addAttributeToFilter('created_at', array('from' => $datefrom,'datetime' => true))				
									->addAttributeToFilter('store_id', $storeId)
									->addAttributeToFilter('entity_id', array('gt' => $start_order_no))
									->addAttributeToFilter('status', array('in' => $order_status))
									->addAttributeToSort('entity_id', 'asc')
									->load();
			return $this->_orders;			
		 	if (count($this->_orders)==0){
		   		exit;
		 	}
		}catch (Exception $e) {
			Mage::printException($e);
		}
	}
    //***************************************************
    //
    //      update  Orders Service
    //
    //***************************************************
	public function getCustomer($datefrom,$customerid,$storeId,$limit)
	{
		$collection = Mage::getResourceModel('customer/customer_collection')
							->addNameToSelect()
							->addAttributeToSelect('email')
							->addAttributeToSelect('created_at')
							->addAttributeToSelect('group_id')	
							//->addAttributeToFilter('updated_at', array('gt' => $datefrom,'datetime' => true))	
							->addAttributeToFilter('store_id', array('in' => array('0',$storeId)))
							->joinAttribute('billing_street', 'customer_address/street', 'default_billing', null, 'left')
							->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
							->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
							->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
							->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
							->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left')
							//->addAttributeToSort('updated_at', 'asc')
							->addAttributeToFilter('entity_id', array('gt' => $customerid))
							->addAttributeToSort('entity_id', 'asc')
							->setPageSize($limit)->load();		
		return $collection;
	}
	
	public function getCustomerById($datefrom,$customerid,$storeId,$limit)
	{
		$collection = Mage::getResourceModel('customer/customer_collection')
							->addNameToSelect()
							->addAttributeToSelect('email')
							->addAttributeToSelect('created_at')
							->addAttributeToSelect('group_id')	
							->addAttributeToFilter('updated_at', array('gt' => $datefrom,'datetime' => true))	
							->addAttributeToFilter('store_id', array('in' => array('0',$storeId)))
							->addAttributeToFilter('entity_id', $customerid)
							->joinAttribute('billing_street', 'customer_address/street', 'default_billing', null, 'left')
							->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
							->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
							->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
							->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
							->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left')
							->addAttributeToSort('updated_at', 'asc')
							->addAttributeToSort('entity_id', 'asc')
							->setPageSize($limit)->load();		
		return $collection;
	}	
	
	public  function  GetOrders($datefrom,$start_order_no=0,$order_status_list='',$storeId=1,$no_of_orders=20,$by_updated_date='')
	{
		if(strtolower($order_status_list)=='all' || strtolower($order_status_list)=="'all'")
		{
			$objNew = new Webgility_Shop1_Model_Runmobile();
			$order_status = array();
			$orderStatus1 = $objNew->_getorderstatuses($storeId);		  			   			
			foreach ($orderStatus1 as $sk=>$sv)
			{
				$order_status[]= $sk;
			}
		}else{
			$order_status_list = str_replace("'","",$order_status_list);
			$order_status_list = explode(",",$order_status_list);	   
			$order_status = orderStatustofetch($order_status_list);		   	
		}
		try
		{
			$this->_orders = Mage::getResourceModel('sales/order_collection')
									->addAttributeToSelect('*')
									->joinAttribute('billing_firstname', 'order_address/firstname', 'billing_address_id', null, 'left')
									->joinAttribute('billing_lastname', 'order_address/lastname', 'billing_address_id', null, 'left')
									->joinAttribute('billing_street', 'order_address/street', 'billing_address_id', null, 'left')
									->joinAttribute('billing_company', 'order_address/company', 'billing_address_id', null, 'left')
									->joinAttribute('billing_city', 'order_address/city', 'billing_address_id', null, 'left')
									->joinAttribute('billing_region', 'order_address/region', 'billing_address_id', null, 'left')
									->joinAttribute('billing_country', 'order_address/country_id', 'billing_address_id', null, 'left')
									->joinAttribute('billing_postcode', 'order_address/postcode', 'billing_address_id', null, 'left')
									->joinAttribute('billing_telephone', 'order_address/telephone', 'billing_address_id', null, 'left')
									->joinAttribute('billing_fax', 'order_address/fax', 'billing_address_id', null, 'left')
									->joinAttribute('shipping_firstname', 'order_address/firstname', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_lastname', 'order_address/lastname', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_street', 'order_address/street', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_company', 'order_address/company', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_city', 'order_address/city', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_region', 'order_address/region', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_country', 'order_address/country_id', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_postcode', 'order_address/postcode', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_telephone', 'order_address/telephone', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_fax', 'order_address/fax', 'shipping_address_id', null, 'left')
									->addAttributeToFilter($by_updated_date?$by_updated_date:'created_at', array('from' => $datefrom,'datetime' => true))
									->addAttributeToFilter('store_id', $storeId)
									->addAttributeToFilter('entity_id', array('gt' => $start_order_no))
									->addAttributeToFilter('status', array('in' => $order_status))			 
									->addAttributeToSort('entity_id', 'asc')
									->setPageSize($no_of_orders)
									->load();
			return $this->_orders;			
			if (count($this->_orders)==0){
				exit;
			}
		}catch (Exception $e) {
			Mage::printException($e);
		}
	}
	
	public  function  GetOrdersById($datefrom,$start_order_no=0,$order_status_list='',$storeId=1,$no_of_orders=20,$by_updated_date='')
	{
		if(strtolower($order_status_list)=='all' || strtolower($order_status_list)=="'all'")
		{
			$objNew = new Webgility_Shop1_Model_Runmobile();
			$order_status = array();
			$orderStatus1 = $objNew->_getorderstatuses($storeId);		  			   			
			foreach ($orderStatus1 as $sk=>$sv)
			{
				$order_status[]= $sk;
			}
		}else{
			$order_status_list = str_replace("'","",$order_status_list);
			$order_status_list = explode(",",$order_status_list);	   
			$order_status = orderStatustofetch($order_status_list);		   	
		}
		try
		{
			$this->_orders = Mage::getResourceModel('sales/order_collection')
									->addAttributeToSelect('*')
									->joinAttribute('billing_firstname', 'order_address/firstname', 'billing_address_id', null, 'left')
									->joinAttribute('billing_lastname', 'order_address/lastname', 'billing_address_id', null, 'left')
									->joinAttribute('billing_street', 'order_address/street', 'billing_address_id', null, 'left')
									->joinAttribute('billing_company', 'order_address/company', 'billing_address_id', null, 'left')
									->joinAttribute('billing_city', 'order_address/city', 'billing_address_id', null, 'left')
									->joinAttribute('billing_region', 'order_address/region', 'billing_address_id', null, 'left')
									->joinAttribute('billing_country', 'order_address/country_id', 'billing_address_id', null, 'left')
									->joinAttribute('billing_postcode', 'order_address/postcode', 'billing_address_id', null, 'left')
									->joinAttribute('billing_telephone', 'order_address/telephone', 'billing_address_id', null, 'left')
									->joinAttribute('billing_fax', 'order_address/fax', 'billing_address_id', null, 'left')
									->joinAttribute('shipping_firstname', 'order_address/firstname', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_lastname', 'order_address/lastname', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_street', 'order_address/street', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_company', 'order_address/company', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_city', 'order_address/city', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_region', 'order_address/region', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_country', 'order_address/country_id', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_postcode', 'order_address/postcode', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_telephone', 'order_address/telephone', 'shipping_address_id', null, 'left')
									->joinAttribute('shipping_fax', 'order_address/fax', 'shipping_address_id', null, 'left')
									->addAttributeToFilter($by_updated_date?$by_updated_date:'created_at', array('from' => $datefrom,'datetime' => true))
									->addAttributeToFilter('store_id', $storeId)
									->addAttributeToFilter('entity_id', $start_order_no)
									->addAttributeToFilter('status', array('in' => $order_status))			 
									->addAttributeToSort('entity_id', 'asc')
									->setPageSize($no_of_orders)
									->load();
			return $this->_orders;			
			if (count($this->_orders)==0){
				exit;
			}
		}catch (Exception $e) {
			Mage::printException($e);
		}
	}
	  
	public function getorderstatus($incrementId,$storeId,$entityId)
	{
		$orders = Mage::getResourceModel('sales/order_collection')
						->addAttributeToSelect('status')
						->addFieldToFilter('increment_id',$incrementId)
						->addAttributeToFilter('store_id', $storeId)
						->load();
		$orders_array=$orders->toArray();
		return $orders_array[$entityId]['status'];
	}
	/**
     * Init installation
     *
     * @param Mage_Core_Model_App $app
     * @return boolean
     */
    public function init(Mage_Core_Model_App $app)
    {
        return true;
    }

    public function getCcTypeName($ccType)
    {
        return isset($this->types[$ccType]) ? $this->types[$ccType] : false;
    }

    public function getShippingCode($shipp)
	{
       $shipp = strtoupper($shipp);
       if (array_key_exists($shipp, $this->carriers_)){
          return $this->carriers_[$shipp];
       }
       return false;
    }	
	## start code 
	
	public function parseRequest()
	{
		$request ='';
		/*if($_SERVER['REQUEST_METHOD']=='GET')
		{
			$request = $_GET['request'];
		}elseif($_SERVER['REQUEST_METHOD']=='POST')
		{
			$request = $_POST['request'];
		}
		$fp = fopen('ecctest.txt', 'a+');
		fwrite($fp, "\n Request Mobilr : ".$request);
		fclose($fp);*/
		
		if($_SERVER['REQUEST_METHOD']=='GET') {
			$request = stripslashes($_GET['request']);
		} elseif($_SERVER['REQUEST_METHOD']=='POST') {
			$request = 	trim($this->getRequestData(str_replace(' ', '+', stripslashes($_POST['request']))));
			$request = substr($request,strpos($request, "{"),strrpos($request, "}")+1);
		}
		
		
		
		//echo $request;die();
		if($request)
		{
			$request = json_decode($request);
			foreach($request as $k=>$v)
			{
				$k = strtolower($k);
				$$k = $v;
			}
		}
		if(!empty($method))
		{
			switch ($method)
			{
				case 'checkAccessInfo':
				echo $str = $method($username,$password);  
				break;  
				

				case 'getItems':
				echo $str =$method($username,$password,$start,$limit,$datefrom,$storeid);
				break;  
				
				case 'getItemsByName':
				echo $str =$method($username,$password,$start,$limit,$itemname);
				break; 

				case 'getPriceQtyBySku':
				echo $str =$method($username,$password,$start,$storeid, $items);
				break;
				
				case 'getCompanyInfo':
				echo $str = $method($username,$password);
				break;
				
				case 'getPaymentMethods':
				echo $str = $method($username,$password);
				break;

				case 'getShippingMethods':
				echo $str = $method($username,$password);
				break;
				
				case 'getOrderStatus':
				echo $str = $method($username,$password);
				break;
				
				case 'getOrderStatusForOrder':
				echo $str = $method($username,$password);
				break;
				
				case 'getCategory':
				echo $str = $method($username,$password);
				break;
				
				case 'getTaxes':
				echo $str = $method($username,$password);
				break;
				
				case 'getManufacturers':
				echo $str = $method($username,$password);
				break;
				
				case 'getOrders':
				echo $str = $method($username,$password,$datefrom,$start_order_no,$ecc_excl_list,$order_per_response,$storeid,$devicetokenid);
				break;
				
				case 'synchronizeItems':
				echo $str = $method($username,$password,$item_json_array,$storeid=1);
				break;
				
				case 'ItemUpdatePriceQty':
				echo $str = $method($username,$password,$itemid,$qty,$price,$cost,$weight,$storeid);
				break;
				
				case 'UpdateOrdersShippingStatus':
				echo $str = $method($username,$password,$orders_json_array,$emailAlert='N',$statustype);
				break;
				
				case 'UpdateOrdersStatusAcknowledge':
				echo $str = $method($username,$password,$Orders_json_array) ;
				break;
				
				case 'OrderUpdateStatus':
				echo $str = $method($username,$password,$orderid,$current_order_status,$order_status,$order_notes,$storeid=1);
				break;
				
				case 'addProduct':
				echo $str = $method($username,$password,$item_json_array) ;
				break;
				
				case 'getAttributesets':
				echo $str = $method($username,$password);
				break;
				
				case 'getVisibilityStatus':
				echo $str = $method($username,$password);
				break;
				
				case 'getStores':
				echo $str = $method($username,$password,$devicetokenid);
				break;
				
				case 'getItemsQuantity':
				echo $str = $method($username,$password);
				break;
				
				case 'isAuthorized':
				echo $str = $method($username,$password);
				break;
				
				case 'getCustomers':
				echo $str = $method($username,$password,$datefrom,$customerid,$limit,$storeid);
				break;
				
				######### New added cases ###########
				
				case 'getStoreCustomerByIdForEcc':
				echo $str = $method($username,$password,$datefrom,$customerid,$limit,$storeid=1,$others);
				break;
				
				case 'getStoreItemByIdForEcc':
				echo $str =$method($username,$password,$datefrom,$start,$limit,$storeid=1);
				break;
				
				case 'getStoreOrderByIdForEcc':
				echo $str = $method($username,$password,$datefrom,$start_order_no,$ecc_excl_list,$order_per_response,$storeid,$others);
				break;
				
				case 'addItemImage':
				echo $str =$method($username,$password,$itemid,$image,$storeid=1);
				break;
				
				case 'runApns':
				echo $str =$method($username,$password);
				break;
				
				case 'deleteStore':
				echo $str =$method($username,$password,$devicetokenid);
				break;
				######################################
			}
		}
	}
	
	function getRequestData($s1)
	{
		//return $s1;
		$cipher_alg = MCRYPT_RIJNDAEL_128;
		$key = "d994e5503a58e025";
		$hexiv="d994e5503a58e02525a8fc5eef45223e";
		return $enc_string = mcrypt_decrypt($cipher_alg, $key, base64_decode($s1), MCRYPT_MODE_CBC, '');
	}
	
	
	function getstores()
	{
		$websites = Mage::getModel('core/website')
				->getResourceCollection()				
				->setLoadDefault(true)
				->load();					
		$websites1 =  $websites->toArray();
		unset($websites);
		$stores = Mage::getModel('core/store_group')
				->getResourceCollection()				
				->setLoadDefault(true)
				->load();					
		$stores = $stores->toArray();		
		for($i=0;$i<count($websites1['items']);$i++)
		{

			if($websites1['items'][$i]['website_id']>0)
			{
				$websites[$websites1['items'][$i]['website_id']] = $websites1['items'][$i]['name'];
			}
		}
		for($i=0;$i<count($stores['items']);$i++)
		{
			if($stores['items'][$i]['group_id']>0)
			{	
				$stores['items'][$i]['website_name'] = $websites[$stores['items'][$i]['website_id']];
			}
		}
		return $stores;
	}
	
	public function getmanufacturers()
	{
		$optionCollection = array();
		$attributesInfo = Mage::getResourceModel('eav/entity_attribute_collection')
								->setCodeFilter('manufacturer')
								->addSetInfo()
								->getData();
		$attributes = array();
		if(count($attributesInfo)>0)
		{
			$optionCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
										->setAttributeFilter($attributesInfo[0]['attribute_id'])
										->setPositionOrder('desc', true)
										->load();
			$optionCollection = $optionCollection->toArray();
			return $optionCollection;
		}else{
			return $optionCollection;
		}
 	}
	
	function getStoreDetails()
	{
		$config = array();		
		$store = Mage::getSingleton('adminhtml/system_store');		
		$websites = Mage::app()->getStore()->getWebsiteId();		
		$data = $store->getStoreNameWithWebsite($websites);				
		$configDataCollection = Mage::getModel('core/config_data')->getCollection();
		foreach ($configDataCollection as $data)
		{			
			list($base,$field,$value) = explode("/",$data->getPath());
			$config[$base][$field][$value] = $data->getValue();
		}
		return $config;
	}
	
	public function _getPaymentMethods($store=null)
	{
		$method = Mage::getSingleton('payment/config')->getActiveMethods();					
		if(is_array($method))
		{
			return $method;
		}
	}
	
    public function getPaymentArray($store=null)
    {
		$methods = array(array('value'=>'', 'label'=>''));
        foreach ($this->_getPaymentMethods() as $paymentCode=>$paymentModel)
		{
            $paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
            $methods[$paymentCode] = array(
                'label'   => $paymentTitle,
                'value' => $paymentCode,
            );
        }
        return $methods;
    }
	
	public function getPaymentlabel($paymethod='')
    {
        $method = "";
        foreach ($this->_getPaymentMethods() as $paymentCode=>$paymentModel) 
		{
            $paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
			if($paymentCode==$paymethod)
			{
				return $paymentTitle;
				break;
			}
        }
		return $method;
    }
	
	public function _getorderstatuses($storeId=1)
	{			
		$statuses = Mage::getSingleton('sales/order_config')->getStatuses();								
		return $statuses;
	}
	
	public function _getcategory($storeId=1)
	{
		$store = Mage::app()->getStore($storeId);
		$rootId[] = $store->getRootCategoryId();     
		$collection = Mage::getModel('catalog/category')
							->getCollection()
							->setStoreId($storeId)			
							->addAttributeToSelect('name')
							->addAttributeToSelect('is_active');	
		foreach ($collection as $category) 
		{		
			if(in_array($category->getParentId(),$rootId) || in_array($category->getId(),$rootId))
			{
				$rootId[] = $category->getId();
				$result[] = array(
					'category_id' => $category->getId(),
					'parent_id'   => $category->getParentId(),
					'name'        => $category->getName(),
					'is_active'   => $category->getIsActive(),
					'position'    => $category->getPosition(),
					'level'       => $category->getLevel()
				);

			}
        }			
        return $result;
	}	
	
	public function _gettaxes($storeId=1)
	{
		$statuses = Mage::getSingleton('sales/order_config')->getStatuses();
		$rateRequest = Mage::getModel('tax/class')->getCollection()->setClassTypeFilter('PRODUCT')->toOptionArray();		
		return $rateRequest;
    }
	
	public function _getshippingMethods($storeid=1,$isActiveOnlyFlag=false)
	{
		$methods = array(array('value'=>'', 'label'=>''));
		$carriers = Mage::getSingleton('shipping/config')->getAllCarriers($storeid);
		foreach ($carriers as $carrierCode=>$carrierModel) 
		{
			if (!$carrierModel->isActive() && (bool)$isActiveOnlyFlag === true)
			{
				continue;
			}
			$carrierMethods = $carrierModel->getAllowedMethods();
			if (!$carrierMethods)
			{
				continue;
			}
			$carrierTitle = Mage::getStoreConfig('carriers/'.$carrierCode.'/title');
			$methods[$carrierCode] = array(
				'label'   => $carrierTitle,
				'value' => array(),
			);
			if(trim(strtolower($carrierCode))=='ups')	
			{
				$orShipArr = Mage::getSingleton('usa/shipping_carrier_ups')->getCode('originShipment');				
				$stroredOriginShipment = Mage::getStoreConfig('carriers/ups/origin_shipment',$storeCode);
			}
			foreach ($carrierMethods as $methodCode=>$methodTitle)
			{
				$methodTitle = $methodTitle ? $methodTitle : $orShipArr[$stroredOriginShipment]["$methodCode"];
				$methods[$carrierCode]['value'][] = array(
					'value' => $carrierCode.'_'.$methodCode,
					'label' => $methodTitle,
				);
			}
			unset($orShipArr,$stroredOriginShipment);	
		}
		return  $methods;
	}

	public function _getvisibilitystatus()
	{
		$_visibilitystatus = Mage::getModel('Mage_Catalog_Model_Product_Visibility')->getOptionArray();
        return $_visibilitystatus;
	}
}

#########################################################################################
#
# registered function defination
#	

	function getDefaultStore($storeId)
	{
		if(isset($storeId) && $storeId!="")
		{
			$stores = Mage::getModel('core/store')
							->getResourceCollection()
							->setLoadDefault(true)
							->addIdFilter($storeId)
							->load();
			$stores = $stores->toArray();
			$store_Id = $stores['items'][0]['store_id'];					
			return $store_Id;
		}
		if(!defined("__STORE_ID"))
		{
			$name = Mage::app()->getDefaultStoreView();	
			$name = $name->toArray();
			return $name['store_id'];
			define("__STORE_ID",$name['store_id']);
		}elseif(__STORE_ID!=''){
			return __STORE_ID;
		}else{
			return 1;
		}
	}

	function getVersion()
	{
		if(Mage::getVersion()!="")
		{
			return Mage::getVersion();
		}else{
			return "0";
		}
	
	}

	function checkAccessInfo($username,$password)
	{	
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		$Cartsupportversion = array("1.2.1","1.2.1.1","1.2.1.2","1.3.1.1","1.3.2.1","1.3.2.2","1.3.2.3","1.3.1","1.3.0","1.3.2.4","1.4.0.0","1.4.0.1","1.4.1.0","1.4.1.1","1.7.1.0","1.9.0.0");
		$version = getVersion();
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$code = "0";
			$message = "Successfully connected to your online store.";
			$responseArray['StatusCode'] = $code;
			if($version!==0)
			{
				if(!in_array($version,$Cartsupportversion))
				{
					$responseArray['StatusMessage'] = $message ." However, your store version is " . $version ." which hasn't been fully tested with eCC. If you'd still like to continue, click OK to continue or contact Webgility to confirm compatibility.";
				}else{
					$responseArray['StatusMessage'] = $message;
				}	
			}else{
				$responseArray['StatusMessage'] = $message." However, eCC is unable to detect your store version. If you'd still like to continue, click OK to continue or contact Webgility to confirm compatibility.";
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

	function isAuthorized($username,$password)
	{
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0")
		{ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
			$response = json_encode($responseArray);
			return responce($response);
			exit;
		}
	
	}

	function getCompanyInfo($username,$password,$storeid=1)
	{
		$storeId=getDefaultStore($storeid);		
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}
		$config = $objNew->getStoreDetails();	
		if(isset($config['shipping']['origin']['region_id']))
		{
			$origRegionCode = Mage::getModel('directory/region')->load($config['shipping']['origin']['region_id'])->getCode();
		}
		else
		{
			$origRegionCode ="";
		}
		if(isset($config['shipping']['origin']['country_id']))
		{
			$country = Mage::getModel('directory/country')->load($config['shipping']['origin']['country_id'])->getIso2Code();
		}
		else
		{
			$country ="";
		}
		$responseArray['StatusCode'] = '0';
		$responseArray['StatusMessage'] = 'All Ok';
		$responseArray['Company']['StoreID'] = '1';
		$responseArray['Company']['StoreName'] =  $config['storeName'];
		$responseArray['Company']['Address'] = htmlspecialchars('', ENT_NOQUOTES);
		if(isset($config['shipping']['origin']['city']))
		{
			$responseArray['Company']['city'] = htmlspecialchars($config['shipping']['origin']['city'], ENT_NOQUOTES);
		}
		else
		{
			$responseArray['Company']['city'] = '';
		}
		$responseArray['Company']['State'] = htmlspecialchars($origRegionCode, ENT_NOQUOTES);
		$responseArray['Company']['Country'] = $country;
		if(isset($config['shipping']['origin']['postcode']))
		{
			$responseArray['Company']['Zipcode'] = htmlspecialchars($config['shipping']['origin']['postcode'], ENT_NOQUOTES);
		}
		else
		{
			$responseArray['Company']['Zipcode'] ='';
		}
		$responseArray['Company']['Phone'] = htmlspecialchars('', ENT_NOQUOTES);
		$responseArray['Company']['Fax'] = htmlspecialchars('', ENT_NOQUOTES);
		$responseArray['Company']['Website'] = htmlspecialchars(Mage::getStoreConfig('web/unsecure/base_url'), ENT_NOQUOTES);
		$response = json_encode($responseArray);
		return responce($response);
	}
	#
	# function to return the store Manufacturer list so synch with QB inventory
	#
	function getManufacturers($username,$password,$storeid=1)
	{
		$storeId = getDefaultStore($storeid);
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$man = $objNew->getmanufacturers();	
			if(count($man['items'])>0)
			{
				for($i=0;$i < count($man['items']);$i++)
				{
					$responseArray['Manufacturers'][$i]['ManufacturerId'] = $man['items'][$i]['option_id'];
					$responseArray['Manufacturers'][$i]['Name'] = htmlentities($man['items'][$i]['value']);
				}
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

	function getAttributesets($username,$password,$storeid=1)
	{
		$storeId = getDefaultStore($storeid);
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();

		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$entityTypeId = Mage::getModel('eav/entity')
									->setType('catalog_product')
									->getTypeId();
					$attributeSet = Mage::getResourceModel('eav/entity_attribute_set_collection')->setEntityTypeFilter($entityTypeId);
					$attributeSet = $attributeSet->toArray();
			if(count($attributeSet['totalRecords'])>0)
			{
				$i =0;
				foreach($attributeSet['items'] as $aSet_value)
				{
					$responseArray['Attributesets'][$i]['AttributesetID'] = $aSet_value['attribute_set_id'];
					$responseArray['Attributesets'][$i]['AttributesetName'] = htmlentities($aSet_value['attribute_set_name']);
					$i++;
				}
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

	function getPaymentMethods($username,$password,$storeid=1)
	{
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$config = $objNew->getPaymentArray(1);
			$i=1;
			$j =0;
			foreach($config as $k=>$v)
			{
				if($config[$k]['value']!='' && $config[$k]['label']!='')
				{
					$responseArray['PaymentMethods'][$j]['MethodId'] = $i;
					$responseArray['PaymentMethods'][$j]['Method'] = $config[$k]['label'];
					$responseArray['PaymentMethods'][$j]['Detail'] = $config[$k]['value'];
					$j++;
				}
				$i++;				
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

	function getShippingMethods($username,$password,$storeid=1)
	{
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);	
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}
		else
		{
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$carriers = $objNew->_getshippingMethods($storeId);	
			if(is_array($carriers)) 
			{
				$j=0;
				foreach($carriers as $k=>$v)
				{
					if($carriers[$k]['value']!="")
					{
						$responseArray['ShippingMethods'][$j]['Carrier'] = $carriers[$k]['label'];
						for($i=0;$i < count($carriers[$k]['value']);$i++)
						{
							$responseArray['ShippingMethods'][$j]['Methods'][$i] = $carriers[$k]['value'][$i]['label'];
						}
						$j++;
					}
				}
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

	function getTaxes($username,$password,$storeid=1)
	{
		$responseArray = array();
		$storeId=getDefaultStore($storeid);
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$taxes = $objNew->_gettaxes($storeId);	
			if($taxes)
			{
				for($i=0;$i< count($taxes);$i++) 
				{
					$responseArray['Taxes'][$i]['TaxID'] = $taxes[$i]['value'];
					$responseArray['Taxes'][$i]['TaxName'] = htmlentities($taxes[$i]['label'], ENT_QUOTES);
				}
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

	function getOrderStatusForOrder($username,$password,$storeid=1)
	{
		$storeId=getDefaultStore($storeid);
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$orderStatus = $objNew->_getorderstatuses($storeId);	
			$invoiceflag = 0;
			$i=0;
			foreach($orderStatus as $id=>$val)
			{	
				$responseArray['OrderStatus'][$i]['StatusId'] = $id;
				$responseArray['OrderStatus'][$i]['StatusName'] = $val;
				if($id == 'invoice')
					$invoiceflag = 1;
				$i++;
			}	
			if($invoiceflag != 1){
				$responseArray['OrderStatus'][$i]['StatusId'] = 'Invoice';
				$responseArray['OrderStatus'][$i]['StatusName'] = 'Invoice';
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

function saveShipment($username,$password,$xmlShipmentItem,$storeid=1)
{
	$storeId=getDefaultStore($storeid);	
	$data =array();
	$items =array();
	
	$xmlResponse = new xml_doc();
	$xmlResponse->version='1.0';
	$xmlResponse->encoding='UTF-8';	
	$root = $xmlResponse->createTag("RESPONSE", array());	
	$objNew = new Webgility_Shop1_Model_Runmobile();
	$status =  $objNew->CheckUser($username,$password);
	if($status!="0"){ //login name invalid
		if($status=="1"){
		$xmlResponse->createTag("StatusCode", array(), "1", $root, __ENCODE_RESPONSE);
		$xmlResponse->createTag("StatusMessage", array(), "Invalid login. Authorization failed", $root, __ENCODE_RESPONSE);
		return $xmlResponse->generate();
		}
		if($status=="2"){ //password invalid
		$xmlResponse->createTag("StatusCode", array(), "2", $root, __ENCODE_RESPONSE);
		$xmlResponse->createTag("StatusMessage", array(), "Invalid password. Authorization failed", $root, __ENCODE_RESPONSE);
		return $xmlResponse->generate();
		}
	}
	
	$xmlRequest = new xml_doc($xmlShipmentItem);
	$xmlRequest->parse();
	$xmlRequest->getTag(0, $_tagName, $_tagAttributes, $_tagContents, $_tagTags, __ENCODE_RESPONSE);
	
	if (strtoupper(trim($_tagName)) != 'REQUEST') {
		$xmlResponse->createTag("StatusCode", array(), "9997", $root, __ENCODE_RESPONSE);
		$xmlResponse->createTag("StatusMessage", array(), "Unknown request or request not in proper format", $root, __ENCODE_RESPONSE);
		return $xmlResponse->generate();
	}
	
	if (count($_tagTags) == 0) {
		$xmlResponse->createTag("StatusCode", array(), "9996", $root, __ENCODE_RESPONSE);
		$xmlResponse->createTag("StatusMessage", array(), "REQUEST tag(s) doesnt have correct input format", $root, __ENCODE_RESPONSE);
		return $xmlResponse->generate();
	}
	
	$ShipmentTag = $xmlRequest->getChildByName(0, "SHIPMENT");
	$xmlRequest->getTag($ShipmentTag, $_tagName, $_tagAttributes, $_tagContents, $_tagTags, __ENCODE_RESPONSE);
	foreach($_tagTags as $k=>$v){	
		$xmlRequest->getTag($v, $_tagName, $_tagAttributes, $_tagContents, $_contentTags, __ENCODE_RESPONSE);
		if($_tagContents !=''){
			$data[$_tagName] = $_tagContents;
		}		
		$i =0;
		foreach($_contentTags as $k1=>$v1){
			$xmlRequest->getTag($v1, $_tagName, $_tagAttributes, $_tagContents, $_itemsTags, __ENCODE_RESPONSE);
			if($_tagName == 'ITEM'){			
				foreach($_itemsTags as $k2=>$v2){
					$xmlRequest->getTag($v2, $_tagName, $_tagAttributes, $_tagContents, $_itemsTags, __ENCODE_RESPONSE);
					$items[$i][$_tagName] = $_tagContents;									
				}	
			}
			if($_tagName == 'SHIPPING'){
				foreach($_itemsTags as $k2=>$v2){
					$xmlRequest->getTag($v2, $_tagName, $_tagAttributes, $_tagContents, $_itemsTags, __ENCODE_RESPONSE);
					$data['SHIPPING'][$i][$_tagName] = $_tagContents;					
				}	
			}
			$i++;
		}
	}
	
	$orders = $objNew->UpdateOrdersShippingStatus($data['ORDERID'],$storeId);		
	$orders_array=$orders->toArray();	
	unset($orders);	
	if(array_key_exists('items',$orders_array))
		$orders_array_w =$orders_array['items'];
	else
		$orders_array_w =$orders_array;	
		
	foreach($orders_array_w as $orders_el){	
		$current_order = Mage::getModel('sales/order')
						   ->load($orders_el['entity_id']);	
		$i=0;
		$qtyCount = 0;
		$totalQty = 0;
		$ItemCount = count($current_order->getAllItems());
		foreach ($current_order->getAllItems() as $item_o) {
			if(!empty($items[$i]['QTY']) || $items[$i]['QTY'] !=0){
				$item_o->setQtyInvoiced($item_o->getQtyToShip());				
				$itemData['items'][$item_o->getId()] = $items[$i]['QTY'];		
				$totalQty = $totalQty +	$items[$i]['QTY'];
				$i++;
			}else{
				$qtyCount++;
			}			
		}			
		$itemData['comment_text'] = $data['SHIPMENTCOMMENT'];
		
		if(array_key_exists('APPENDCOMMENT',$data))
			$itemData['append_comment'] = $data[APPENDCOMMENT];
		else
			$itemData['append_comment'] = 0;
			
		if(array_key_exists('EMAILCOPY',$data))
			$itemData['copy_email'] = $data[EMAILCOPY];
		else
			$itemData['copy_email'] = 0;
	}
	
	if($qtyCount == $ItemCount)
	{
		$xmlResponse->createTag("StatusCode", array(), "001", $root, __ENCODE_RESPONSE);
		$xmlResponse->createTag("StatusMessage", array(), "Item not found for shipment", $root, __ENCODE_RESPONSE);
		return $xmlResponse->generate();
	}
	$k =0;
	foreach($data['SHIPPING'] as $shippingcontent)
	{
		$shipingNo[$k] = $shippingcontent['NUMBER'];
		$trackingCarrier[$k] = $shippingcontent['TRACKINGCARRIER'];
		$k++;
	}
	$RequestOrders = array("TRACKINGNUMBER"=>$shipingNo,"SHIPPEDVIA"=>$trackingCarrier,"SERVICEUSED"=>$trackingCarrier);	
	if($shipment = $objNew->AddShipmentByOrder($current_order,$RequestOrders,$itemData))
	{
		$msg = 'The shipment has been created.Item quantity is'.$totalQty;
		$xmlResponse->createTag("StatusCode", array(), "002", $root, __ENCODE_RESPONSE);
		$xmlResponse->createTag("StatusMessage", array(), $msg, $root, __ENCODE_RESPONSE);
		return $xmlResponse->generate();
	}else{
		$xmlResponse->createTag("StatusCode", array(), "003", $root, __ENCODE_RESPONSE);
		$xmlResponse->createTag("StatusMessage", array(), "The shipment has been failed", $root, __ENCODE_RESPONSE);
		return $xmlResponse->generate();
	}
}

	function getVisibilityStatus($username,$password,$storeid=1)
	{
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$visibilitystatus = $objNew->_getvisibilitystatus();	
			if(is_array($visibilitystatus)) 
			{
				$i =0;
				foreach($visibilitystatus as $vstatusKey=>$vstatusVal)
				{
					$responseArray['VisibilityStatus'][$i]['StatusId'] = $vstatusKey;
					$responseArray['VisibilityStatus'][$i]['StatusName'] = $vstatusVal;
					$i++;
				}
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}
	
	function getOrderStatus($username,$password,$storeid=1)
	{
		$storeId=getDefaultStore($storeid);
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$orderStatus = $objNew->_getorderstatuses($storeId);	
			$invoiceflag = 0;
			$i=0;
			foreach($orderStatus as $id=>$val)
			{	
				$responseArray['OrderStatus'][$i]['StatusId'] = $id;
				$responseArray['OrderStatus'][$i]['StatusName'] = $val;
				if($id == 'invoice')
					$invoiceflag = 1;
				$i++;
			}	
			if($invoiceflag != 1){
				$responseArray['OrderStatus'][$i]['StatusId'] = 'Invoice';
				$responseArray['OrderStatus'][$i]['StatusName'] = 'Invoice';
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

	function addProduct($username,$password,$item_json_array,$storeid=1)
	{
		$responseArray = array();
		$requestArray = array();
		global $sql_tbl,$config; 	
		$version = getVersion();	
		if($version != '1.2.1.2')
		{
			$storeId=getDefaultStore($storeid);
		}	
		$stores = Mage::getModel('core/store')
						->getResourceCollection()
						->setLoadDefault(true)
						->addGroupFilter($storeId)
						->load();
		$stores = $stores->toArray();
		$website_id = $stores['items'][0]['website_id'];
		unset($stores);
	
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
	
		if($status!="0"){ //login name invalid
			if($status=="1"){
					$responseArray['StatusCode'] = '1';
					$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
				}
				if($status=="2"){ //password invalid
					$responseArray['StatusCode'] = '2';
					$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
				}
		}
		else
		{
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$requestArray = json_decode($item_json_array, true);
			 if (!is_array($requestArray['REQUEST'])) {
				$responseArray['StatusCode']='9997';
				$responseArray['StatusMessage']='Unknown request or request not in proper format';
				return json_encode($responseArray);
			 }
			 
			if (count($requestArray) == 0) {
				$responseArray['StatusCode']='9996';
				$responseArray['StatusMessage']='REQUEST tag(s) doesnt have correct input format';
				return json_encode($responseArray);
			}
			foreach($requestArray as $k=>$v)//request
			{
				foreach($v as $k1=>$vItems)//Items
				{
					$itemsProcessed=0;
					foreach($vItems as $k2=>$vItem)//Item
					{
						$productcode=$vItem['ItemCode'];
						$product=$vItem['ItemName'];
						$descr=$vItem['ItemDesc'];
						$free_shipping=$vItem['FreeShipping'];  
						$free_tax=$vItem['TaxExempt'];  
						$tax_id=$vItem['TaxID']; 
						$item_match=$vItem['ItemMatchBy'];
						$manufacturerid=$vItem['ManufacturerID'];
						$avail_qty=$vItem['Quantity'];
						$price=$vItem['UnitPrice'];			
						$weight=$vItem['Weight'];
						$visibility=$vItem['Visibility'];
						$attributesetid=$vItem['Attributesetid'];
						
						if(is_array($vItem['ItemVariants']))
						{
							$arrayVarients=$vItem['ItemVariants'];
							$v=0;
							foreach($arrayVarients as $k3=>$vVarients)//varients
							{ 
								$b=0;
								foreach($vVarients as $k4=>$vVariant)//variant
								{		
									$variantid=$vVariant['variantid'];
									if(isset($variantid)&& $variantid!='')
									{ 
										$variant_data[$b]['variantid'] = $variantid;				
									}
									$variantqty=$vVariant['variantqty'];
									if(isset($variantqty)&& $variantqty!='')
									{ 
										$variant_data[$b]['variantqty'] = $variantqty;				
									}
									$variantUnitprice=$vVariant['variantUnitprice'];
									if(isset($variantUnitprice)&& $variantUnitprice!='')
									{ 
										$variant_data[$b]['variantUnitprice'] = $variantUnitprice;		
									}
									$b++;
								}
								$V++;
							}
						}
						
						if(is_array($vItem['ItemOptions']))
						{    
							$arrayOptions=$vItem['ItemOptions']; 
							$o=0;
							$all_options = '';
							foreach($arrayOptions as $k3=>$vOptions)//Options
							{ 
								foreach($vOptions as $k4=>$vOption)//Option
								{
									$optionname=$vOption['optionname'];
									if(isset($optionname)&& $optionname!='')
									{ 
										$all_options[$br]['optionname'] =  $optionname;				
										if(!in_array($optionname,$uniq_options))
										{
											$uniq_options[$bk] = $optionname;	
											$bk++;						
										}		
									}		
									$optionvalue=$vOption['optionvalue'];
									if(isset($optionvalue)&& $optionvalue!='')
									{ 
										$all_options[$br]['optionvalue'] =  $optionvalue;
										if(!in_array($optionvalue,$uniq_options_vals[$optionname]))
										{
											$uniq_options_vals[$optionname][]= $optionvalue;
										}
									}
									$optionprz=$vOption['optionprz'];
									if(isset($optionprz)&& $optionprz!='')
									{ 
										$all_options[$br]['optionprz'] =  $optionprz;	
										$uniq_options_vals1[$optionname][$optionvalue] = $optionprz;			
									}
									$br++;	
								}
								$o++;
							}
						}
						
						if(is_array($vItem['Categories']))
						{
							$arrayCategories=$vItem['Categories'];
							$cateId = array();	  
							foreach($arrayCategories as $k3=>$vCategories)//Categories
							{ 
								$categoryid .= $vCategories['CategoryId'].",";
							}
							$categoryid = strrev(substr($categoryid,0,-1));
							$categoryid = strrev($categoryid);
						}
						
						$forsale = "Y";
						$provider = "master";
						$list_price = $price;
						$fulldescr =$descr;
						$min_amount = "1";
						if(isset($attributesetid)&& $attributesetid!='')
						{
							$attributeSet['attribute_set_id'] = $attributesetid;
						}else 
						{
							$entityTypeId = Mage::getModel('eav/entity')
													->setType('catalog_product')
													->getTypeId();
							
							$attributeSet = Mage::getResourceModel('eav/entity_attribute_set_collection')
													->setEntityTypeFilter($entityTypeId)
													->addFilter('attribute_set_name', 'Default')
													->getLastItem();
							$attributeSet = $attributeSet->toArray();	
						}
						if ($objNew->getduplicaterecord($product,$productcode)==0)
						{
							$data = array();
							if(isset($variant_data) && $variant_data!="" && is_array($variant_data))
							{
								$controlerData = array();
								foreach($uniq_options_vals as $atk=>$atv)
								{
									$attributeCode  = $atk;
									$attribute = Mage::getModel('catalog/resource_eav_attribute')
									->loadByCode(4, $attributeCode);
															
									if ($attribute->getId() && !$attributeId) {
										$eavSetId = Mage::getSingleton('core/resource')->getConnection('core_write');
										$SetIds=$eavSetId->query("SELECT * FROM `eav_entity_attribute` where `entity_type_id` = 4 AND `attribute_set_id` = '".$attributeSet['attribute_set_id']."' AND `attribute_id` = '".$attribute->getId()."'");
										$SetId = '';
										$attid = $attribute->getId();
										while ($row = $SetIds->fetch() ) {
											$SetId = $row['attribute_set_id'];
											$attid = $row['attribute_id'];
										}
											$attrType = $attribute->getFrontendInput();
											if($attrType =='select'){
												$attrVals = $attribute->getSource()->getAllOptions(false);
												$counter = 0;
												foreach($attrVals as $attrVal)
												{
													if(strtoupper($attrVal['label']) != strtoupper($atv[0])){
														$counter++;
													}									
												}
												$attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
												->setCodeFilter($attributeCode)
												->getFirstItem();
												$options = $attributeInfo->getSource()->getAllOptions(false);
												if(count($attrVals) == $counter){
																					
													$_optionArr = array('value'=>array(), 'order'=>array(), 'delete'=>array());
													foreach ($options as $option)
													$_optionArr['value'][$option['value']] = array($option['label']);
													$_optionArr['value']['option_1'] = array($atv[0]);
													$attribute->setOption($_optionArr);
													$attribute->save();
													$getattributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
													->setCodeFilter($attributeCode)
													->getFirstItem();
													$getoptions = $getattributeInfo->getSource()->getAllOptions(false);
													for ($op = 0;$op<count($getoptions);$op++)
														$atv[0] = $getoptions[$op]['value'];
													unset($getoptions);
													unset($getattributeInfo);
													
												}else{
													foreach ($options as $option){
														if(strtoupper($option['label']) == strtoupper($atv[0]))
															$atv[0] = $option['value'];
													}	
												}
											}
											
											if($SetId != $attributeSet['attribute_set_id'])
											{								
												$readresult=$eavSetId->query("SELECT * FROM `eav_attribute_group` WHERE `attribute_set_id` = '".$attributeSet['attribute_set_id']."' ORDER BY `attribute_group_id`  ASC limit 1");
												while ($row = $readresult->fetch() ) {
													$group = $row['attribute_group_id'];
												}
												
												$readresult2=$eavSetId->query("SELECT entity_attribute_id FROM `eav_entity_attribute` order by entity_attribute_id desc limit 1");
												while ($row2 = $readresult2->fetch() ) {
													$entity_attribute_id = $row2['entity_attribute_id']+1;
												}
												
												$eavSetId->query("insert into eav_entity_attribute(`entity_attribute_id` ,`entity_type_id` ,`attribute_set_id` ,`attribute_group_id` ,`attribute_id` ,`sort_order`) values ('".$entity_attribute_id."','4','".$attributeSet['attribute_set_id']."','".$group."','".$attid."','100')");									
											}
										$data[$atk] = $atv[0];	
									}else{
										$write = Mage::getSingleton('core/resource')->getConnection('core_write');
										$readresult=$write->query("SELECT * FROM `eav_attribute_group` WHERE `attribute_set_id` = '".$attributeSet['attribute_set_id']."' ORDER BY `attribute_group_id`  ASC limit 1");
										while ($row = $readresult->fetch() ) {
											$group = $row['attribute_group_id'];
										}
										
										$controlerData['attribute_code'] = $atk;
										$controlerData['is_global'] = 1;
										$controlerData['frontend_input'] = 'select';
										$controlerData['default_value_text'] = '';
										$controlerData['default_value_yesno'] = 0;
										$controlerData['default_value_date'] = '';
										$controlerData['default_value_textarea'] = '';
										$controlerData['is_unique'] = 0;
										$controlerData['is_required'] = 0;
										$controlerData['is_configurable'] = 0;
										$controlerData['is_searchable'] = 0;
										$controlerData['is_visible_in_advanced_search'] = 0;
										$controlerData['is_comparable'] = 0;
										$controlerData['is_used_for_promo_rules'] = 0;
										$controlerData['is_html_allowed_on_front'] = 1;
										$controlerData['is_visible_on_front'] = 1;
										$controlerData['used_in_product_listing'] = 0;
										$controlerData['used_for_sort_by'] = 0;
										$controlerData['frontend_label'][0] = $atk;	
										$controlerData['frontend_label'][1] = $atk;		
										$controlerData['option']['value']['option_0'][] = $atv[0];
										$controlerData['option']['order']['option_0'] = 1;
										$controlerData['option']['delete']['option_0'] = '';
										$controlerData['is_filterable'] = 0;
										$controlerData['is_filterable_in_search'] = 0;
										$controlerData['backend_type'] = 'int';
										$controlerData['default_value'] = '';
										$controlerData['apply_to'][0] = 'simple';
																		
										
										$model = Mage::getModel('catalog/resource_eav_attribute');
										$model->addData($controlerData);
										$model->setEntityTypeId(4);
										$model->setIsUserDefined(1);						
										$model->setAttributeSetId($attributeSet['attribute_set_id']);
										$model->setAttributeGroupId($group);
										$model->save();
										unset($model);
										unset($controlerData);
										$attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
										->setCodeFilter($attributeCode)
										->getFirstItem();
										$options = $attributeInfo->getSource()->getAllOptions(false);
										foreach ($options as $option){
											$atv[0] = $option['value'];
										}
										unset($attributeInfo);
										$data[$atk] = $atv[0];
									}
								}
							}
							$Product = $objNew->addproduct($storeId);
							$Product->setCategoryIds($categoryid);
							
							$data['name'] = trim($product);//'testp';
							$data['sku'] = trim($productcode);//'testp114512';
							
							$data['manufacturer'] = $manufacturerid;//'''122';		
							$data['description'] = $descr;//'test';
							$data['short_description'] = $descr;//'test';				
							$data['qty'] = $avail_qty;//'test';
							//$data['stock_data']['qty'] = $avail_qty;//'58';				
							
							$data['attribute_set_id']=$attributeSet['attribute_set_id'];				
							$data['price'] = $price;//'100';
							if($tax_id)
								$data['tax_class_id'] = $tax_id;// '1';
							else
								$data['tax_class_id'] = 0;
							$data['weight'] = $weight;//'1';				
							$data['stock_data']['use_config_manage_stock'] = 1;
							$data['stock_data']['is_in_stock'] = 1;
							$data['status'] = '1';				
							$data['website_id'] = $website_id;
							if($visibility)
								$data['visibility'] = $visibility;
							else
								$data['visibility'] = '1';
							$Product->addData($data);				
							$Product->save();
							
							$productId = $Product->getId();
							$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
							$stockItem->addQty($avail_qty);				
							$stockItem->setIsInStock(true);
							$stockItem->save();
							
							#
							# Insert new product into the database and get its productid
							#
							$responseArray['Items'][$itemsProcessed]['Status'] = 'Success';
							$responseArray['Items'][$itemsProcessed]['ProductID'] = $productId;
							$responseArray['Items'][$itemsProcessed]['Sku'] = htmlentities($productcode);
							$responseArray['Items'][$itemsProcessed]['ProductName'] = htmlentities($product);	
						}
						else
						{
							$responseArray['Items'][$itemsProcessed]['Status'] = 'Duplicate product code exists';
							$responseArray['Items'][$itemsProcessed]['ProductID'] = '';
							$responseArray['Items'][$itemsProcessed]['Sku'] = htmlentities($productcode);
							$responseArray['Items'][$itemsProcessed]['ProductName'] = htmlentities($product);	
						}
						$itemsProcessed++;
					}
				}
				unset($attributes,$n_id,$attribute_option,$attribute_option1);
			}
			}
		$response = json_encode($responseArray);
		return responce($response);
	}

	function synchronizeItems($username,$password,$item_json_array,$storeid=1)
	{
		$responseArray = array();
		$requestArray = array();
		$storeId=getDefaultStore($storeid);
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$requestArray=json_decode($item_json_array, true);
			if (!is_array($requestArray['REQUEST']))
			{
				$responseArray['StatusCode']='9997';
				$responseArray['StatusMessage']='Unknown request or request not in proper format';
				return json_encode($responseArray);
			}
				 
			if (count($requestArray) == 0)
			{
				$responseArray['StatusCode']='9996';
				$responseArray['StatusMessage']='REQUEST array(s) doesnt have correct input format';
				return json_encode($responseArray);	
			}			
			$itemsProcessed = 0;
			$i=0;
			$version = getVersion();	
			foreach($requestArray as $k=>$v)//request
			{	
				foreach($v as $k1=>$vItems)//items
				{
					foreach($vItems as $k2=>$vItem)//item
					{
						foreach($vItem as $k3=>$vVarients)//varients
						{ 
							$updated_attrib=0;
							foreach($vVarients as $k4=>$v4)//variant
							{
								$status ="Success";	
								$productID = $v4['ID'];
								if ($v4['Qty']!="")
								{
									$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productID);
									$stockItem->setQty($v4['Qty']);
									if($v4['Qty']>0)
									{
										$stockItem->setIs_in_stock(1);
									}						
									$stockItem->save();								
									if($stockItem->getQty() != $v4['Qty'])
									{
										$stockItem = Mage::getModel('cataloginventory/stock_item');
										$stockItem->load($productID);
										$stockItem->setQty($v4['Qty']);
										if($v4['Qty']>0)
										{
											$stockItem->setIs_in_stock(1);
										}
										$stockItem->save();
									} 	
								}
								
								if ($v4['Price']!="")
								{	
									Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 					
									$p = new Mage_Catalog_Model_Product();
									$p->load($productID);
									$p->price = $v4['Price'];
									$p->save();	
									
									if($p->getPrice() != $v4['Price'])
									{
										$Product = $objNew->editproduct($storeId,$productID);
										$Product->setPrice($v4['Price']);
										$Product->save();					
									}
								}	
								$status ="Success";							
								$responce_array['Items'][$item_counter]['Varients'][$updated_attrib]['VarientID']=$v4['ID'];						
								$responce_array['Items'][$item_counter]['Varients'][$updated_attrib]['Sku']=$v4['Sku'];						
								$responce_array['Items'][$item_counter]['Varients'][$updated_attrib]['ItemUpdateStatus']=$status;						
								$updated_attrib++;
							}
						}
					}
				}
			}
		}
		
		$response = json_encode($responseArray);
		return responce($response);
	}


	function ItemUpdatePriceQty($username,$password,$itemid,$qty,$price,$cost,$weight,$storeid=1)
	{
	
		//die($username.','.$password.','.$itemId.','.$qty.','.$price.','.$storeid);
		
		$responseArray = array();
		$requestArray = array();
		$storeId=getDefaultStore($storeid);
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'Item successfully updated';
			$itemsProcessed = 0;
			$i=0;
			$version = getVersion();	
			
			$status ="Success";	
			if ($qty!="")
			{
				$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($itemid);
				$stockItem->setQty($qty);
				if($qty>0)
				{
					$stockItem->setIs_in_stock(1);
				}						
				$stockItem->save();								
				if($stockItem->getQty() != $qty)
				{
					$stockItem = Mage::getModel('cataloginventory/stock_item');
					$stockItem->load($itemid);
					$stockItem->setQty($qty);
					if($qty>0)
					{
						$stockItem->setIs_in_stock(1);
					}
					$stockItem->save();
				} 	
			}
			
			if ($price!="")
			{	
				Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 					
				$p = new Mage_Catalog_Model_Product();
				$p->load($itemid);
				$p->price = $price;
				$p->cost = $cost;
				
				$p->save();	
				
				if($p->getPrice() != $price)
				{
					$Product = $objNew->editproduct($storeId,$itemid);
					$Product->setPrice($price);	
					$Product->save();					
				}
				
				if($p->getWeight() != $weight)
				{
					$Product = $objNew->editproduct($storeId,$itemid);
					$Product->setWeight($weight);	
					$Product->save();					
				}
				
			}	
			$status ="Success";							
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

	function getStoreItemByIdForEcc($username,$password,$datefrom,$start,$limit,$storeid,$others) {
	
		$responseArray = array();
		$storeId=getDefaultStore($storeId);	
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);	
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$items_query_product = $objNew->getProductById($storeId,$start,$limit);	 	
			$count_query_product = $items_query_product->getSize();			
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$responseArray['TotalRecordFound'] = $count_query_product?$count_query_product:'0';
			$responseArray['TotalRecordSent'] = count($items_query_product->getItems())?count($items_query_product->getItems()):'0';	
			if(count($items_query_product)>0)
			{	
				#get the manufacturer
				$manufacturer = $objNew->getmanufacturers();
				if($manufacturer['totalRecords']>0)
				{
					foreach($manufacturer['items'] as $manufacturer1)
					{
						$manufacturer2[$manufacturer1['option_id']] = $manufacturer1['value'];
					}
				}	
				unset($manufacturer,$manufacturer1);	
				$itemI = 0;
				foreach ($items_query_product->getItems() as $iInfo11) 
				{
					//echo $iInfo11->getId();die('reached');
				
					$iInfo['category_ids'] = $iInfo11->getCategoryIds();
					$options = $objNew->getoptions($iInfo11);
					
					$iInfo = $iInfo11->toArray();//print_r($iInfo);
					if($iInfo['type_id']=='simple' || $iInfo['type_id']=='virtual' || $iInfo['type_id']=='downloadable')				
					{
						$desc=addslashes(htmlspecialchars(substr($iInfo['description'],0,4000),ENT_QUOTES));
						$stockItem =  Mage::getModel('cataloginventory/stock_item')->loadByProduct($iInfo['entity_id']);
						
						
						
						$obj = Mage::getModel('catalog/product');
						$_product_for_image_url = $obj->load($iInfo['entity_id']); // Enter your Product Id in $product_id
						
						
						$stockItem=$stockItem->toArray();
						$responseArray['Items'][$itemI]['ItemID'] = $iInfo['entity_id'];
						$responseArray['Items'][$itemI]['ItemCode'] = htmlspecialchars($iInfo['sku'],ENT_QUOTES);
						$responseArray['Items'][$itemI]['ItemDescription'] = htmlspecialchars($iInfo['name'],ENT_QUOTES);
						$responseArray['Items'][$itemI]['ItemShortDescr'] = addslashes(substr($desc,0,300));
						/*$responseArray['Items'][$itemI]['ItemImageUrl'] = $_product_for_image_url->getImageUrl();
						$responseArray['Items'][$itemI]['ItemSmallImageUrl'] = $_product_for_image_url->getSmallImageUrl();*/
						
						
						/*Start code fetch images array*/
						/*$obj = Mage::getModel('catalog/product');
						$_product_for_image_url = $obj->load($iInfo['entity_id']); // Enter your Product Id in $product_id
						$product_images_array	=	$_product_for_image_url->getMediaGalleryImages();
						if(count($product_images_array)>0)
						{
							$product_images_count = 0;
							foreach($product_images_array as $product_images)
							{
								$responseArray['Items'][$itemI]['ItemImages'][$product_images_count]['ItemID'] = $iInfo['entity_id'];
								$responseArray['Items'][$itemI]['ItemImages'][$product_images_count]['ItemImageID'] = $product_images->getId();
								$responseArray['Items'][$itemI]['ItemImages'][$product_images_count]['ItemImageFileName'] = $product_images->getFile();
								$responseArray['Items'][$itemI]['ItemImages'][$product_images_count]['ItemImageUrl'] = $product_images->getUrl();
								
								$responseArray['Items'][$itemI]['ItemImages'][$product_images_count]['ItemImageThumbnail'] = $product_images_array->helper('catalog/image')->init($iInfo11, 'thumbnail', $product_images->getFile())->resize(200, 130);
																
								$product_images_count++;
							}
						}*/
						
						$_images = Mage::getModel('catalog/product')->load($iInfo11->getId())->getMediaGalleryImages();
						
						/*?><pre><?php print_r($_images->toArray());die();?></pre><?php*/
						
						if($_images){			
						   $i=0; foreach($_images as $_image){ 
						   		
								$fileNameStr	=	$_image->getFile();
								//echo strrpos($fileNameStr,'/').'<br/>'.strlen($fileNameStr).'<br/>';
								$fileName		=	substr($fileNameStr, strrpos($fileNameStr,'/')+1, strlen($fileNameStr));
								//die();
								$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemID']				=	$iInfo['entity_id'];
								$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemImageID']		=	$_image->getId();
								$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemImageFileName']	=	$fileName;
								$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemImageUrl']		=	$_image->getUrl();
								//$_thumbnail_url	=	Mage::helper('catalog/image')->init($iInfo11, 'thumbnail', $_image->getFile())->resize(100, 65);
								//$_thumbnail_url	=	Mage::helper('catalog/image')->init($iInfo11, 'small_image')->resize(100, 100);
								//echo '<br/>'.$_thumbnail_url;die('reachecd');
								//$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemImageThumbnail']	=	$_thumbnail_url;	
								$i++;			
						   }
						}
						/*?><pre><?php print_r($responseArray['Items']);die('reached');?></pre><?php*/
						
						/*End code fetch images array*/
						

						if(is_array($iInfo['category_ids']))
						{
							$categoriesI = 0;
							foreach ($iInfo['category_ids'] as $category) 
							{		
								$responseArray['Items'][$itemI]['Categories'][$categoriesI]['Category'] = '';
								$responseArray['Items'][$itemI]['Categories'][$categoriesI]['CategoryId'] = $category;
								$categoriesI++;
							}
						}
						$iInfo['manufacturer'] = $iInfo['manufacturer']?$manufacturer2[$iInfo['manufacturer']]:$iInfo['manufacturer'];			
						$responseArray['Items'][$itemI]['manufacturer'] = $iInfo['manufacturer'];
						$responseArray['Items'][$itemI]['Quantity'] = $stockItem['qty'];
						$responseArray['Items'][$itemI]['UnitPrice'] = $iInfo11->getPrice();// UnitPrice = price
						$responseArray['Items'][$itemI]['ListPrice'] = $iInfo[cost];//ListPrice = cost
						$responseArray['Items'][$itemI]['Weight'] = $iInfo11->getWeight();
						$responseArray['Items'][$itemI]['LowQtyLimit'] = $stockItem['min_qty'];
						$responseArray['Items'][$itemI]['FreeShipping'] = 'N';
						$responseArray['Items'][$itemI]['Discounted'] = '';
						$responseArray['Items'][$itemI]['shippingFreight'] = '';
						$responseArray['Items'][$itemI]['Weight_Symbol'] = "lbs";
						$responseArray['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";
						$responseArray['Items'][$itemI]['TaxExempt'] = 'N';
						$responseArray['Items'][$itemI]['UpdatedAt'] = $iInfo["updated_at"];
						$responseArray['Items'][$itemI]['ItemVariants'] = '';
						if(is_array($options) && count($options)>0)
						{
							$optionI = 0;
							foreach($options as $ioInfo)
							{
								$ioInfo = parseSpecCharsA($ioInfo);
								$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['ItemOptionID'] = $ioInfo['option_type_id'];
								$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['ItemID'] = $iInfo['entity_id'];
								$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['Value'] = htmlspecialchars($ioInfo['title'],ENT_QUOTES);
								$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['Name'] = htmlspecialchars($ioInfo['option_title'],ENT_QUOTES);
								$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['Price'] = $ioInfo['price'];
								$optionI++;
							}
						}
					}
					$itemI++;
				} // end items
			}
		}
		
		
		/*?><pre><?php print_r($responseArray);?></pre><?php*/
		
		$response = json_encode($responseArray);
		return responce($response);
	
	}

	function getItems($username,$password,$start=0,$limit,$datefrom,$storeId=1)
	{
	
		$datefrom = 0;
		$responseArray = array();
		$storeId=getDefaultStore($storeId);	
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);	
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$items_query_product = $objNew->getProduct($storeId,$start,$limit,$datefrom );	 	
			$count_query_product = $items_query_product->getSize();			
			
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			/*$responseArray['TotalRecordFound'] = $count_query_product?$count_query_product:'0';
			$responseArray['TotalRecordSent'] = count($items_query_product->getItems())?count($items_query_product->getItems()):'0';*/	
			if(count($items_query_product)>0)
			{	
				#get the manufacturer
				$manufacturer = $objNew->getmanufacturers();
				if($manufacturer['totalRecords']>0)
				{
					foreach($manufacturer['items'] as $manufacturer1)
					{
						$manufacturer2[$manufacturer1['option_id']] = $manufacturer1['value'];
					}
				}	
				unset($manufacturer,$manufacturer1);	
				$itemI = 0;
				foreach ($items_query_product->getItems() as $iInfo11) 
				{
					//echo $iInfo11->getId();die('reached');
				
					$iInfo['category_ids'] = $iInfo11->getCategoryIds();
					$options = $objNew->getoptions($iInfo11);
					
					$iInfo = $iInfo11->toArray();//print_r($iInfo);
					
					if(strlen($iInfo['sku']) > 0 && $iInfo['sku'] != '') {
					
						if($iInfo['type_id']=='simple' || $iInfo['type_id']=='virtual' || $iInfo['type_id']=='downloadable' || $iInfo['type_id']== 'configurable')				
						{
							$desc=addslashes(htmlspecialchars(substr($iInfo['description'],0,4000),ENT_QUOTES));
							$stockItem =  Mage::getModel('cataloginventory/stock_item')->loadByProduct($iInfo['entity_id']);
							
							
							
							$obj = Mage::getModel('catalog/product');
							$_product_for_image_url = $obj->load($iInfo['entity_id']); // Enter your Product Id in $product_id
							
							
							$stockItem=$stockItem->toArray();
							$responseArray['Items'][$itemI]['ItemID'] = $iInfo['entity_id'];
							$responseArray['Items'][$itemI]['ItemCode'] = htmlspecialchars($iInfo['sku'],ENT_QUOTES);
							$responseArray['Items'][$itemI]['ItemDescription'] = htmlspecialchars($iInfo['name'],ENT_QUOTES);
							$responseArray['Items'][$itemI]['ItemShortDescr'] = addslashes(substr($desc,0,300));
							/*$responseArray['Items'][$itemI]['ItemImageUrl'] = $_product_for_image_url->getImageUrl();
							$responseArray['Items'][$itemI]['ItemSmallImageUrl'] = $_product_for_image_url->getSmallImageUrl();*/
							
							
							/*Start code fetch images array*/
							/*$obj = Mage::getModel('catalog/product');
							$_product_for_image_url = $obj->load($iInfo['entity_id']); // Enter your Product Id in $product_id
							$product_images_array	=	$_product_for_image_url->getMediaGalleryImages();
							if(count($product_images_array)>0)
							{
								$product_images_count = 0;
								foreach($product_images_array as $product_images)
								{
									$responseArray['Items'][$itemI]['ItemImages'][$product_images_count]['ItemID'] = $iInfo['entity_id'];
									$responseArray['Items'][$itemI]['ItemImages'][$product_images_count]['ItemImageID'] = $product_images->getId();
									$responseArray['Items'][$itemI]['ItemImages'][$product_images_count]['ItemImageFileName'] = $product_images->getFile();
									$responseArray['Items'][$itemI]['ItemImages'][$product_images_count]['ItemImageUrl'] = $product_images->getUrl();
									
									$responseArray['Items'][$itemI]['ItemImages'][$product_images_count]['ItemImageThumbnail'] = $product_images_array->helper('catalog/image')->init($iInfo11, 'thumbnail', $product_images->getFile())->resize(200, 130);
																	
									$product_images_count++;
								}
							}*/
							
							$_images = Mage::getModel('catalog/product')->load($iInfo11->getId())->getMediaGalleryImages();
							
							/*?><pre><?php print_r($_images->toArray());die();?></pre><?php*/
							
							if($_images){			
							   $i=0; foreach($_images as $_image){ 
									
									$fileNameStr	=	$_image->getFile();
									//echo strrpos($fileNameStr,'/').'<br/>'.strlen($fileNameStr).'<br/>';
									$fileName		=	substr($fileNameStr, strrpos($fileNameStr,'/')+1, strlen($fileNameStr));
									//die();
									$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemID']				=	$iInfo['entity_id'];
									$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemImageID']		=	$_image->getId();
									$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemImageFileName']	=	$fileName;
									$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemImageUrl']		=	$_image->getUrl();
									//$_thumbnail_url	=	Mage::helper('catalog/image')->init($iInfo11, 'thumbnail', $_image->getFile())->resize(100, 65);
									//$_thumbnail_url	=	Mage::helper('catalog/image')->init($iInfo11, 'small_image')->resize(100, 100);
									//echo '<br/>'.$_thumbnail_url;die('reachecd');
									//$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemImageThumbnail']	=	$_thumbnail_url;	
									$i++;			
							   }
							}
							/*?><pre><?php print_r($responseArray['Items']);die('reached');?></pre><?php*/
							
							/*End code fetch images array*/
							
	
							if(is_array($iInfo['category_ids']))
							{
								$categoriesI = 0;
								foreach ($iInfo['category_ids'] as $category) 
								{		
									$responseArray['Items'][$itemI]['Categories'][$categoriesI]['Category'] = '';
									$responseArray['Items'][$itemI]['Categories'][$categoriesI]['CategoryId'] = $category;
									$categoriesI++;
								}
							}
							$iInfo['manufacturer'] = $iInfo['manufacturer']?$manufacturer2[$iInfo['manufacturer']]:$iInfo['manufacturer'];			
							$responseArray['Items'][$itemI]['manufacturer'] = $iInfo['manufacturer'];
							$responseArray['Items'][$itemI]['Quantity'] = $stockItem['qty'];
							$responseArray['Items'][$itemI]['UnitPrice'] = $iInfo11->getPrice();// UnitPrice = price
							$responseArray['Items'][$itemI]['ListPrice'] = $iInfo[cost];//ListPrice = cost
							$responseArray['Items'][$itemI]['Weight'] = $iInfo11->getWeight();
							$responseArray['Items'][$itemI]['LowQtyLimit'] = $stockItem['min_qty'];
							$responseArray['Items'][$itemI]['FreeShipping'] = 'N';
							$responseArray['Items'][$itemI]['Discounted'] = '';
							$responseArray['Items'][$itemI]['shippingFreight'] = '';
							$responseArray['Items'][$itemI]['Weight_Symbol'] = "lbs";
							$responseArray['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";
							$responseArray['Items'][$itemI]['TaxExempt'] = 'N';
							$responseArray['Items'][$itemI]['UpdatedAt'] = $iInfo["updated_at"];
							$responseArray['Items'][$itemI]['ItemVariants'] = '';
							if(is_array($options) && count($options)>0)
							{
								$optionI = 0;
								foreach($options as $ioInfo)
								{
									$ioInfo = parseSpecCharsA($ioInfo);
									$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['ItemOptionID'] = $ioInfo['option_type_id'];
									$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['ItemID'] = $iInfo['entity_id'];
									$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['Value'] = htmlspecialchars($ioInfo['title'],ENT_QUOTES);
									$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['Name'] = htmlspecialchars($ioInfo['option_title'],ENT_QUOTES);
									$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['Price'] = $ioInfo['price'];
									$optionI++;
								}
							}
						}
						$itemI++;
					
					} else {$count_query_product = $count_query_product - 1;}
					
					
				} // end items
			}
			
			$responseArray['TotalRecordFound'] = $count_query_product?$count_query_product:'0';
			$responseArray['TotalRecordSent'] = $itemI;
			
		}
		
		
		/*?><pre><?php print_r($responseArray);?></pre><?php*/
		
		$response = json_encode($responseArray);
		return responce($response);
	} 


	function getImageResponseArray($start, $fileNameParam, $storeid) {
	
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$responseArray = array();
		
		$responseArray['StatusCode'] = '0';
		$responseArray['StatusMessage'] = 'All Ok';
		$responseArray['ItemImageFlag']	=	'1';
		
		$items_query_product = $objNew->getProductById($storeid,$start,1);	 	
		$count_query_product = $items_query_product->getSize();			
		if(count($items_query_product)>0)
		{	
				
			$itemI = 0;
			foreach ($items_query_product->getItems() as $iInfo11) 
			{
				$iInfo = $iInfo11->toArray();//print_r($iInfo);
				if($iInfo['type_id']=='simple' || $iInfo['type_id']=='virtual' || $iInfo['type_id']=='downloadable')				
				{
					
					$obj = Mage::getModel('catalog/product');
					$_product_for_image_url = $obj->load($iInfo['entity_id']); // Enter your Product Id in $product_id
					
					$_images = Mage::getModel('catalog/product')->load($iInfo11->getId())->getMediaGalleryImages();
					
					/*?><pre><?php print_r($_images->toArray());die();?></pre><?php*/
					
					if($_images){			
					   $i=0; 
					   foreach($_images as $_image){ 
							
							$fileNameStr	=	$_image->getFile();
							//echo strrpos($fileNameStr,'/').'<br/>'.strlen($fileNameStr).'<br/>';
							$fileName		=	substr($fileNameStr, strrpos($fileNameStr,'/')+1, strlen($fileNameStr));
							if($fileNameParam == $fileName) {
								$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemID']				=	$iInfo['entity_id'];
								$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemImageID']		=	$_image->getId();
								$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemImageFileName']	=	$fileName;
								$responseArray['Items'][$itemI]['ItemImages'][$i]['ItemImageUrl']		=	$_image->getUrl();
								$i++;
								break;	
							}		
					   }
					}
				}
				$itemI++;
			} // end items
		}
		return $responseArray;
	}

	
	function addItemImage($username,$password,$itemid,$image,$storeid=1) {
		//echo $username.','.$password.','.$itemid;die();
		$responseArray = array();
		
		$fileName = time().'.jpg';
		//Base 64 encoded string $image
		$ImageData	=	base64_decode($image);
		
		/*$responseArray['StatusCode'] = '0';
		$responseArray['StatusMessage'] = 'All Ok';
		$responseArray['ItemImageFlag']	=	'1';*/
		
		$imageUrl	=	saveImage($itemid, $fileName, $ImageData);
		
		
		$responseArray	=	getImageResponseArray($itemid, $fileName, $storeid);
		
		/*$responseArray['Items'][0]['ItemImages'][0]['ItemID']				=	$itemid;
		$responseArray['Items'][0]['ItemImages'][0]['ItemImageID']		=	$itemid;
		$responseArray['Items'][0]['ItemImages'][0]['ItemImageFileName']	=	$fileName;
		$responseArray['Items'][0]['ItemImages'][0]['ItemImageUrl']		=	$imageUrl;*/
		
		
		$response = json_encode($responseArray);
		return responce($response);
	}

	
	function _createDestinationFolder($destinationFolder)
	{
		if( !$destinationFolder ) {
			return $this;
		}
	
		if (substr($destinationFolder, -1) == DIRECTORY_SEPARATOR) {
			$destinationFolder = substr($destinationFolder, 0, -1);
		}
	
		if (!(@is_dir($destinationFolder) || @mkdir($destinationFolder, 0777, true))) {
			throw new Exception("Unable to create directory '{$destinationFolder}'.");
		}
		return $this;
	
		$destinationFolder = str_replace('/', DIRECTORY_SEPARATOR, $destinationFolder);
		$path = explode(DIRECTORY_SEPARATOR, $destinationFolder);
		$newPath = null;
		$oldPath = null;
		foreach( $path as $key => $directory ) {
			if (trim($directory)=='') {
				continue;
			}
			if (strlen($directory)===2 && $directory{1}===':') {
				$newPath = $directory;
				continue;
			}
			$newPath.= ( $newPath != DIRECTORY_SEPARATOR ) ? DIRECTORY_SEPARATOR . $directory : $directory;
			if( is_dir($newPath) ) {
				$oldPath = $newPath;
				continue;
			} else {
				if( is_writable($oldPath) ) {
					mkdir($newPath, 0777);
				} else {
					throw new Exception("Unable to create directory '{$newPath}'. Access forbidden.");
				}
			}
			$oldPath = $newPath;
		}
		return $this;
	}
	
	function _addDirSeparator($dir)
	{
		if (substr($dir,-1) != DIRECTORY_SEPARATOR) {
			$dir.= DIRECTORY_SEPARATOR;
		}
		return $dir;
	}
	
	function getDispretionPath($fileName)
	{
		$char = 0;
		$dispretionPath = '';
		while( ($char < 2) && ($char < strlen($fileName)) ) {
			if (empty($dispretionPath)) {
				$dispretionPath = DIRECTORY_SEPARATOR.('.' == $fileName[$char] ? '_' : $fileName[$char]);
			}
			else {
				$dispretionPath = _addDirSeparator($dispretionPath) . ('.' == $fileName[$char] ? '_' : $fileName[$char]);
			}
			$char ++;
		}
		return $dispretionPath;
	}



	function saveImage($productId, $fileName, $ImageData)
	{
		_createDestinationFolder(Mage::getSingleton('catalog/product_media_config')->getBaseMediaPath());
		$destFile = Mage::getSingleton('catalog/product_media_config')->getBaseMediaPath();
		$destFile.= getDispretionPath($fileName);
		_createDestinationFolder($destFile);
		$destFile = _addDirSeparator($destFile) . $fileName;	
		$file = fopen($destFile, 'w+');
		fwrite($file, $ImageData);
		fclose($file);
		$product = Mage::getModel('catalog/product');
		$product->load($productId);
		$mediaAttribute = array (
					'thumbnail',
					'small_image',
					'image'
			);
		$product->addImageToMediaGallery($destFile, $mediaAttribute, true, false);
		$product->save();
		return $destFile;
	}


	function getItemsByName($username,$password,$start_item_no=0,$limit=500,$itemname ,$storeId=1)
	{
		$responseArray = array();
		$storeId=getDefaultStore($storeId);	
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);	
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$items_query_product = $objNew->getProductByName($storeId,$start_item_no,$limit,$datefrom, $itemname);	 	
			$count_query_product = $items_query_product->getSize();			
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$responseArray['TotalRecordFound'] = $count_query_product?$count_query_product:'0';
			$responseArray['TotalRecordSent'] = count($items_query_product->getItems())?count($items_query_product->getItems()):'0';	
			if(count($items_query_product)>0)
			{	
				#get the manufacturer
				$manufacturer = $objNew->getmanufacturers();
				if($manufacturer['totalRecords']>0)
				{
					foreach($manufacturer['items'] as $manufacturer1)
					{
						$manufacturer2[$manufacturer1['option_id']] = $manufacturer1['value'];
					}
				}	
				unset($manufacturer,$manufacturer1);	
				$itemI = 0;
				foreach ($items_query_product->getItems() as $iInfo11) 
				{
					$iInfo['category_ids'] = $iInfo11->getCategoryIds();
					$options = $objNew->getoptions($iInfo11);
					$iInfo = $iInfo11->toArray();
					if($iInfo['type_id']=='simple' || $iInfo['type_id']=='virtual' || $iInfo['type_id']=='downloadable')				
					{
						$desc=addslashes(htmlspecialchars(substr($iInfo['description'],0,4000),ENT_QUOTES));
						$stockItem =  Mage::getModel('cataloginventory/stock_item')->loadByProduct($iInfo['entity_id']);
						$stockItem=$stockItem->toArray();
						$responseArray['Items'][$itemI]['ItemID'] = $iInfo['entity_id'];
						$responseArray['Items'][$itemI]['ItemCode'] = htmlspecialchars($iInfo['sku'],ENT_QUOTES);
						$responseArray['Items'][$itemI]['ItemDescription'] = htmlspecialchars($iInfo['name'],ENT_QUOTES);
						$responseArray['Items'][$itemI]['ItemShortDescr'] = addslashes(substr($desc,0,300));
						if(is_array($iInfo['category_ids']))
						{
							$categoriesI = 0;
							foreach ($iInfo['category_ids'] as $category) 
							{		
								$responseArray['Items'][$itemI]['Categories'][$categoriesI]['Category'] = '';
								$responseArray['Items'][$itemI]['Categories'][$categoriesI]['CategoryId'] = $category;
								$categoriesI++;
							}
						}
						$iInfo['manufacturer'] = $iInfo['manufacturer']?$manufacturer2[$iInfo['manufacturer']]:$iInfo['manufacturer'];			
						$responseArray['Items'][$itemI]['manufacturer'] = $iInfo['manufacturer'];
						$responseArray['Items'][$itemI]['Quantity'] = $stockItem['qty'];
						$responseArray['Items'][$itemI]['UnitPrice'] = $iInfo11->getPrice();
						$responseArray['Items'][$itemI]['ListPrice'] = $iInfo[cost];
						$responseArray['Items'][$itemI]['Weight'] = $iInfo11->getWeight();
						$responseArray['Items'][$itemI]['LowQtyLimit'] = $stockItem['min_qty'];
						$responseArray['Items'][$itemI]['FreeShipping'] = 'N';
						$responseArray['Items'][$itemI]['Discounted'] = '';
						$responseArray['Items'][$itemI]['shippingFreight'] = '';
						$responseArray['Items'][$itemI]['Weight_Symbol'] = "lbs";
						$responseArray['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";
						$responseArray['Items'][$itemI]['TaxExempt'] = 'N';
						$responseArray['Items'][$itemI]['UpdatedAt'] = $iInfo["updated_at"];
						$responseArray['Items'][$itemI]['ItemVariants'] = '';
						if(is_array($options) && count($options)>0)
						{
							$optionI = 0;
							foreach($options as $ioInfo)
							{
								$ioInfo = parseSpecCharsA($ioInfo);
								$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['ItemOption']['ID'] = $ioInfo['option_type_id'];
								$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['ItemOption']['Value'] = htmlspecialchars($ioInfo['title'],ENT_QUOTES);
								$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['ItemOption']['Name'] = htmlspecialchars($ioInfo['option_title'],ENT_QUOTES);
								$optionI++;
							}
						}
					}
					$itemI++;
				} // end items
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}
	
	function getPriceQtyBySku($username,$password,$start_item_no=0,$storeId=1, $items)
	{
		
		//die($sku_str);
		$responseArray = array();
		$storeId=getDefaultStore($storeId);	
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);	
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$items_query_product = $objNew->getProductBySku($storeId,$start_item_no,$items);	 	
			$count_query_product = $items_query_product->getSize();			
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$responseArray['TotalRecordFound'] = $count_query_product?$count_query_product:'0';
			$responseArray['TotalRecordSent'] = count($items_query_product->getItems())?count($items_query_product->getItems()):'0';	
			if(count($items_query_product)>0)
			{	
				#get the manufacturer
				$manufacturer = $objNew->getmanufacturers();
				if($manufacturer['totalRecords']>0)
				{
					foreach($manufacturer['items'] as $manufacturer1)
					{
						$manufacturer2[$manufacturer1['option_id']] = $manufacturer1['value'];
					}
				}	
				unset($manufacturer,$manufacturer1);	
				$itemI = 0;
				foreach ($items_query_product->getItems() as $iInfo11) 
				{
					$iInfo['category_ids'] = $iInfo11->getCategoryIds();
					$options = $objNew->getoptions($iInfo11);
					$iInfo = $iInfo11->toArray();
					if($iInfo['type_id']=='simple' || $iInfo['type_id']=='virtual' || $iInfo['type_id']=='downloadable')				
					{
						$desc=addslashes(htmlspecialchars(substr($iInfo['description'],0,4000),ENT_QUOTES));
						$stockItem =  Mage::getModel('cataloginventory/stock_item')->loadByProduct($iInfo['entity_id']);
						$stockItem=$stockItem->toArray();
						$responseArray['Items'][$itemI]['ItemID'] = $iInfo['entity_id'];
						$responseArray['Items'][$itemI]['ItemCode'] = htmlspecialchars($iInfo['sku'],ENT_QUOTES);
						//$responseArray['Items'][$itemI]['ItemDescription'] = htmlspecialchars($iInfo['name'],ENT_QUOTES);
						//$responseArray['Items'][$itemI]['ItemShortDescr'] = addslashes(substr($desc,0,300));
						/*if(is_array($iInfo['category_ids']))
						{
							$categoriesI = 0;
							foreach ($iInfo['category_ids'] as $category) 
							{		
								$responseArray['Items'][$itemI]['Categories'][$categoriesI]['Category'] = '';
								$responseArray['Items'][$itemI]['Categories'][$categoriesI]['CategoryId'] = $category;
								$categoriesI++;
							}
						}*/
						/*$iInfo['manufacturer'] = $iInfo['manufacturer']?$manufacturer2[$iInfo['manufacturer']]:$iInfo['manufacturer'];			
						$responseArray['Items'][$itemI]['manufacturer'] = $iInfo['manufacturer'];*/
						$responseArray['Items'][$itemI]['Quantity'] = number_format ($stockItem['qty'] ,2);
						$responseArray['Items'][$itemI]['UnitPrice'] = $iInfo11->getPrice();
						$responseArray['Items'][$itemI]['ListPrice'] = $iInfo[cost];
						$responseArray['Items'][$itemI]['Weight'] = $iInfo11->getWeight();
						/*$responseArray['Items'][$itemI]['LowQtyLimit'] = $stockItem['min_qty'];
						$responseArray['Items'][$itemI]['FreeShipping'] = 'N';
						$responseArray['Items'][$itemI]['Discounted'] = '';
						$responseArray['Items'][$itemI]['shippingFreight'] = '';
						$responseArray['Items'][$itemI]['Weight_Symbol'] = "lbs";
						$responseArray['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";
						$responseArray['Items'][$itemI]['TaxExempt'] = 'N';
						$responseArray['Items'][$itemI]['UpdatedAt'] = $iInfo["updated_at"];
						$responseArray['Items'][$itemI]['ItemVariants'] = '';*/
						/*if(is_array($options) && count($options)>0)
						{
							$optionI = 0;
							foreach($options as $ioInfo)
							{
								$ioInfo = parseSpecCharsA($ioInfo);
								$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['ItemOption']['ID'] = $ioInfo['option_type_id'];
								$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['ItemOption']['Value'] = htmlspecialchars($ioInfo['title'],ENT_QUOTES);
								$responseArray['Items'][$itemI]['ItemOptions'][$optionI]['ItemOption']['Name'] = htmlspecialchars($ioInfo['option_title'],ENT_QUOTES);
								$optionI++;
							}
						}*/
					}
					$itemI++;
				} // end items
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}
								
	function OrderUpdateStatus($username,$password,$orderid,$current_order_status,$order_status,$order_notes,$storeid=1,$emailAlert='N')
	{
		
		
		$OrderId			=	trim($orderid); 
		$CurrentOrderStatus	=	trim($current_order_status);
		$OrderStatus		=	trim($order_status); 
		$OrderNotes			=	trim($order_notes);

		$storeId=getDefaultStore($storeId);	
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);	
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			
			if(!isset($OrderId)) {
				$responseArray['StatusCode'] = '9997';
				$responseArray['StatusMessage'] = 'Unknown request or request not in proper format';
				return $this->response($Orders->getOrders());
			}
			
			$update_notes_flag	=	false;
			$update_status_flag	=	false;
			
			$responseArray['StatusCode'] = '0';
			
			      
			$orderStatus = $objNew->_getorderstatuses($storeId);		  			
			$status = $OrderStatus;	
			$order_status_list = array(0=>$status);
			$status_w = orderStatustofetch($order_status_list);
			$OrderStatus = $status_w[0];						
	
			$info = "";			
			
			if ($OrderNotes!="")			
			$info .=" \n".$OrderNotes;
		
			$orders = $objNew->UpdateOrdersShippingStatus($OrderId,$storeId);		
			$orders_array=$orders->toArray();	
			unset($orders);	
			
			//print_r($orders_array);
			
			
			// Updated for 1.4.1.0
			if(array_key_exists('items',$orders_array))
				$orders_array_w =$orders_array['items'];
			else
				$orders_array_w =$orders_array;	
			foreach($orders_array_w as $orders_el){	
			
				$current_order = Mage::getModel('sales/order')
				->load($orders_el['entity_id']);
				
					if(strtolower($OrderStatus)=='canceled') {
						if($current_order->getState()== strtolower($OrderStatus)) {
							//$result ="Success: Order is already Canceled";			
							$StatusMessage	=	"Order is already Canceled";
							$emailAlert = "N";	
														
						} else {
							
														
							$current_order->setState($OrderStatus,true);								
							$invoiceNotifies = false;								
							if($emailAlert=='Y') {$invoiceNotified = true;}
								
							$update_status_flag = true;	
							
							if ($OrderNotes!="") {	
									
								$current_order->addStatusToHistory($OrderStatus, '', $invoiceNotified);
								$current_order->save();								
								$result ="";								
								Mage::unregister('sales_order');
								Mage::unregister('current_order');
								$update_notes_flag = true;
							}											
							
						}
					
					} elseif(strtolower($OrderStatus)=='holded') {
					
						$result = holdAction($orders_el['entity_id']);
						if(trim($result)!="" && $result==1) { $update_status_flag = true; } else { $emailAlert = "N";}
						Mage::unregister('sales_order');
						Mage::unregister('current_order');	
							
					
					} elseif(strtolower($OrderStatus)=='unholded') {
					
						$result = unholdAction($orders_el['entity_id']);
						if(trim($result)!="" && $result==1) { $update_status_flag = true; } else { $emailAlert = "N";}
						Mage::unregister('sales_order');
						Mage::unregister('current_order');
							
					
					} elseif (strtolower($OrderStatus) == "complete") { 
					
						if($current_order->getState()== strtolower($OrderStatus)) {
							//$result = "Success: Order has already been completed";
							$StatusMessage	=	"Order has been already completed";
							$emailAlert = "N";	
						} 
						elseif(strtolower($current_order->getState())=="processing" || strtolower($current_order->getState()) =="pending" || strtolower($current_order->getState()) =="new") 
						{
							$current_order->setTotal_paid($orders_el['grand_total']);
							$current_order->setBase_total_paid($orders_el['base_grand_total']);
							
							$current_order->setTotal_invoiced($orders_el['grand_total']);
							$current_order->setBase_total_invoiced($orders_el['base_grand_total']);
							
							$current_order->setDiscount_invoiced($orders_el['discount_amount']);
							$current_order->setBase_discount_invoiced($orders_el['base_discount_amount']);
							
							$current_order->setSubtotal_invoiced($orders_el['subtotal']);
							$current_order->setTax_invoiced($orders_el['tax_amount']);
							
							$current_order->setShipping_invoiced($orders_el['shipping_amount']);		   
							$current_order->setBase_subtotal_invoiced($orders_el['base_subtotal']);
							$current_order->setBase_tax_invoiced($orders_el['base_tax_amount']);			   
							$current_order->setBase_shipping_invoiced($orders_el['base_shipping_amount']);
							
							foreach ($current_order->getAllItems() as $item_o) 
							{					
								$item_o->setQtyInvoiced($item_o->getQtyToShip());										
								$data['items'][$item_o->getId()] = $item_o->getQtyToShip();
							}				
							$data['comment_text'] = $OrderNotes;
							
							unset($data['send_email'],$data['comment_customer_notify']);
							
							if($OrderNotes != '') {
								saveInvoice($data,$orders_el['entity_id']);
								$update_notes_flag = true;
							}
							
							
							if($current_order->getState()!=$OrderStatus)
							{				
								$state = $OrderStatus;
								$current_order->setData('state', $state);	
								// add status history
								if ($status) {
									if ($status === true) {
										$status = $current_order->getConfig()->getStateDefaultStatus($state);
									}
									$current_order->setStatus($status);
									
								}
							}
							$invoiceNotifies = false;
							if($emailAlert=='Y')
								$invoiceNotified = true;
							$current_order->addStatusToHistory($OrderStatus, $info, $invoiceNotified);								
							$current_order->save();
							
							$update_status_flag = true;	
							
							Mage::unregister('sales_order');
							Mage::unregister('current_order');
							Mage::unregister('current_invoice');
							
						}else 
						{
							//$result = "Error: Order cannot be completed. Please review manually";	
							$StatusMessage	=	"Order cannot be completed.";
							$emailAlert = "N";						
						}
					} elseif ($OrderNotes!="") {	
						
						if($emailAlert=='Y') {$invoiceNotified = true;}
									
						$current_order->addStatusToHistory($orders_el['status'], $info, $invoiceNotified);
						$current_order->save();								
						$result ="";								
						Mage::unregister('sales_order');
						Mage::unregister('current_order');
						$update_notes_flag = true;
						
					} else {
					
						//$result = 'Error : Order cannot be '.$current_order->getState()." . Please review manually";	
						$StatusMessage	=	"Order cannot be ".$current_order->getState();
						$emailAlert = "N";				
					}
					
					if($emailAlert=='Y') {
						$_SERVER['SCRIPT_FILENAME'] = str_replace("ecc-magento","index",$_SERVER['SCRIPT_FILENAME']);
						$_SERVER['REQUEST_URI'] = str_replace("ecc-magento","index",$_SERVER['REQUEST_URI']);
						$_SERVER['PHP_SELF'] = str_replace("ecc-magento","index",$_SERVER['PHP_SELF']);
						$_SERVER['SCRIPT_NAME'] = str_replace("ecc-magento","index",$_SERVER['SCRIPT_NAME']);
						$current_order->sendOrderUpdateEmail(true,$info);
						unset($info);
					}
				}
				
				if($update_notes_flag && $update_status_flag) {
					$StatusMessage	=	"Order updated successfully";	
				} elseif($update_notes_flag) {
					$StatusMessage	=	"Order notes updated successfully";	
				} elseif($update_status_flag) {
					$StatusMessage	=	"Order status updated successfully";	
				}/* else {
					$StatusMessage	=	"Error in update order";
				}*/
				$responseArray['StatusMessage'] = $StatusMessage;	
		}		
		$response = json_encode($responseArray);
		return responce($response);
	
	}

	function UpdateOrdersShippingStatus($username,$password,$Orders_json_array,$emailAlert='N',$statustype,$storeid=1)
	{
		$responseArray = array();
		$requestArray = array();
		$storeId=getDefaultStore($storeid);		
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);	
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$requestArray=json_decode($Orders_json_array, true);
			if (!is_array($requestArray['REQUEST']))
			{
				$responseArray['StatusCode']='9997';
				$responseArray['StatusMessage']='Unknown request or request not in proper format';
				return json_encode($responseArray);
			}			 
			if (count($requestArray) == 0)
			{
				$responseArray['StatusCode']='9996';
				$responseArray['StatusMessage']='REQUEST array(s) doesnt have correct input format';
				return json_encode($responseArray);	
			}
			if(count($requestArray) == 0) {	
				$no_orders = true; 	
			}else {	
				$no_orders = false;	
			}	
			$responseArray['StatusCode']=$no_orders?"1000":"0";
			$responseArray['StatusMessage']=$no_orders?"No new orders.":"All Ok";
			if ($no_orders)
			{
				return json_encode($responce_array);
			}
			$i=0;	
			foreach($requestArray as $k=>$v)//request
			{	
				foreach($v as $k1=>$vOrders)//Orders
				{		  
					$Order_counter=0;
					foreach($vOrders as $k2=>$order)//Order
					{      
						$orderStatus = $objNew->_getorderstatuses($storeId);		  			
						$status = $order['OrderStatus'];	
						$order_status_list = array(0=>$status);
						$status_w = orderStatustofetch($order_status_list);
						$order['OrderStatus'] = $status_w[0];						
				
						$info = "\nOrder shipped ";			
						if ($order['ShippedOn']!="")
						$info .= " on ". substr($order['ShippedOn'],0,10);
						
						if ($order['ShippedVia']!="" || $order['ServiceUsed']!="" )
						$info .= " via ".$order['ShippedVia']." ".$order['ServiceUsed'];
						
						if ($order['TrackingNumber']!="")
						$info .= " with Tracking Number ".$order['TrackingNumber'].".";
						
						if ($order['OrderNotes']!="")			
						$info .=" \n".$order['OrderNotes'];
					
						$orders = $objNew->UpdateOrdersShippingStatus($order['OrderID'],$storeId);		
						$orders_array=$orders->toArray();	
						unset($orders);	
						// Updated for 1.4.1.0
						if(array_key_exists('items',$orders_array))
							$orders_array_w =$orders_array['items'];
						else
							$orders_array_w =$orders_array;	
						foreach($orders_array_w as $orders_el){	
							$current_order = Mage::getModel('sales/order')
							->load($orders_el['entity_id']);
								if(strtolower($order['OrderStatus'])=='canceled'  || strtolower($statustype) == strtolower('Cancel'))
								{
									if($current_order->getState()== strtolower($order['ORDERSTATUS']))
									{
										$result ="Success: Order is already Canceled";			
										$emailAlert = "N";								
									}
									else
									{								
										$current_order->setState($order['OrderStatus'],true);								
										$invoiceNotifies = false;								
										if($emailAlert=='Y')
											$invoiceNotified = true;									
										$current_order->addStatusToHistory($order['OrderStatus'], '', $invoiceNotified);
										$current_order->save();								
										$result ="";								
										Mage::unregister('sales_order');
										Mage::unregister('current_order');
									}
								
								}elseif(strtolower($order['OrderStatus'])=='holded')
								{
									$result = holdAction($orders_el['entity_id']);
									if(trim($result)!="" && $result==1)
									{
										$result ="";				
									}else
									{
										$emailAlert = "N";							
									}
									Mage::unregister('sales_order');
									Mage::unregister('current_order');		
								
								}elseif(strtolower($order['OrderStatus'])=='unholded')
								{
									$result = unholdAction($orders_el['entity_id']);
									if(trim($result)!="" && $result==1)
									{
										$result ="";
									}else
									{
										$emailAlert = "N";							
									}
									Mage::unregister('sales_order');
									Mage::unregister('current_order');
								
								}elseif (strtolower($order['OrderStatus']) == "complete")				
								{
								
									if($current_order->getState()== strtolower($order['OrderStatus']))
									{
										$result = "Success: Order has already been completed";
										$emailAlert = "N";							
									}
									elseif(strtolower($current_order->getState())=="processing" || strtolower($current_order->getState()) =="pending" || strtolower($current_order->getState()) =="new")
									{
										$current_order->setTotal_paid($orders_el['grand_total']);
										$current_order->setBase_total_paid($orders_el['base_grand_total']);
										
										$current_order->setTotal_invoiced($orders_el['grand_total']);
										$current_order->setBase_total_invoiced($orders_el['base_grand_total']);
										
										$current_order->setDiscount_invoiced($orders_el['discount_amount']);
										$current_order->setBase_discount_invoiced($orders_el['base_discount_amount']);
										
										$current_order->setSubtotal_invoiced($orders_el['subtotal']);
										$current_order->setTax_invoiced($orders_el['tax_amount']);
										
										$current_order->setShipping_invoiced($orders_el['shipping_amount']);		   
										$current_order->setBase_subtotal_invoiced($orders_el['base_subtotal']);
										$current_order->setBase_tax_invoiced($orders_el['base_tax_amount']);			   
										$current_order->setBase_shipping_invoiced($orders_el['base_shipping_amount']);
										
										foreach ($current_order->getAllItems() as $item_o) 
										{					
											$item_o->setQtyInvoiced($item_o->getQtyToShip());										
											$data['items'][$item_o->getId()] = $item_o->getQtyToShip();
										}				
										$data['comment_text'] = $order['OrderNotes'];
										
										unset($data['send_email'],$data['comment_customer_notify']);
										saveInvoice($data,$orders_el['entity_id']);
										$RequestOrders = array("TRACKINGNUMBER"=>$order['TrackingNumber'],"SHIPPEDVIA"=>$order['ShippedVia'],"SERVICEUSED"=>$order['ServiceUsed']);
										if($shipment = $objNew->_initShipment($current_order,$RequestOrders,$data))
										{		//die("2");			  
										  $shipment->register();
										  $shipment->addComment($info,false);
										  $shipment_arr = $objNew->_saveShipment($shipment);
										}
										if($current_order->getState()!=$order['OrderStatus'])
										{				
											$state = $order['ORDERSTATUS'];
											$current_order->setData('state', $state);	
											// add status history
											if ($status) {
											if ($status === true) {
											$status = $current_order->getConfig()->getStateDefaultStatus($state);
											}
											$current_order->setStatus($status);
										}
										}
										$invoiceNotifies = false;
										if($emailAlert=='Y')
											$invoiceNotified = true;
										$current_order->addStatusToHistory($order['OrderStatus'], $info, $invoiceNotified);								
										$current_order->save();
										$result = "Success: Order has been completed";	
										Mage::unregister('sales_order');
										Mage::unregister('current_order');
										Mage::unregister('current_invoice');
									}else 
									{
										$result = "Error: Order cannot be completed. Please review manually";	
										$emailAlert = "N";						
									}
								}else
								{
									$result = 'Error : Order cannot be '.$current_order->getState()." . Please review manually";	
									$emailAlert = "N";				
								}
								if($emailAlert=='Y')
								{
									$_SERVER['SCRIPT_FILENAME'] = str_replace("ecc-magento","index",$_SERVER['SCRIPT_FILENAME']);
									$_SERVER['REQUEST_URI'] = str_replace("ecc-magento","index",$_SERVER['REQUEST_URI']);
									$_SERVER['PHP_SELF'] = str_replace("ecc-magento","index",$_SERVER['PHP_SELF']);
									$_SERVER['SCRIPT_NAME'] = str_replace("ecc-magento","index",$_SERVER['SCRIPT_NAME']);
									$current_order->sendOrderUpdateEmail(true,$info);
									unset($info);
								}
							}
							$result = $result?$result:'Success: Order has been '.ucfirst($order['OrderStatus']);				
							$responseArray['Orders'][$Order_counter]['OrderID']=$order['OrderID'];				
							$responseArray['Orders'][$Order_counter]['Status']=$order['Status']=$result;				
							$Order_counter++;	  
					}
				}
			}
		}		
		$response = json_encode($responseArray);
		return responce($response);
	}

	function getStoreOrderByIdForEcc($username,$password,$datefrom,$start_order_no,$ecc_excl_list,$order_per_response="25",$storeid=1,$devicetokenid) {
		$responseArray = array();
		if(!$start_order_no==0)
		{
			$my_orders = Mage::getModel('sales/order')->loadByIncrementId($start_order_no);			
			$my_orders1 = $my_orders->toArray();
			$start_order_no = $my_orders1['entity_id'];									
			if(!isset($start_order_no) || $start_order_no=='')
			{
				$start_order_no=0;						
			}			
		}
		$storeId=getDefaultStore($storeid);	
		if(!isset($datefrom) or empty($datefrom)) $datefrom='2000-01-01';
		if(!isset($dateto) or empty($dateto)) $dateto=date('m-d-Y');
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			if($others == 'by_updated_at')
			{
				$by_updated_date = 'updated_at';	
			}
			$_countorders = $_orders = $objNew->GetOrdersById($datefrom,$start_order_no,$ecc_excl_list,$storeId,$order_per_response,$by_updated_date);	
			$countorders_array = $_countorders->toArray();	
			$country = array();
			$country_data = Mage::getResourceModel('directory/country_collection')->load()->toOptionArray();
			foreach($country_data as $ck=>$cv)
			{	
				if($cv['value']!='')
				$country[$cv['value']] = trim($cv['label']);
			}	
			unset($country_data);	
			if(array_key_exists('items',$countorders_array))
				$countorders_array = $countorders_array['items'];
			if(count($countorders_array)>0)
			{
				$orders_remained = count($countorders_array);
			}else{
				$orders_remained = 0;
			}
			$orders_array=$_orders->toArray();
			$no_orders = false;
			if($orders_remained<1)
			{
				$no_orders = true;
			}
			$responseArray['StatusCode'] = $no_orders?"9999":"0";
			$responseArray['StatusMessage'] = $no_orders?"No Orders returned":"Total Orders:".$orders_remained;
			$responseArray['TotalRecordFound'] = $_orders->getSize()?$_orders->getSize():'0';
			$responseArray['TotalRecordSent'] = count($countorders_array)?count($countorders_array):'0';
			if ($no_orders)
			{	
						$response = json_encode($responseArray);
						return responce($response);
						exit();
			}
			$obj = new Mage_Sales_Model_Order();
			$ord = 0;
			$last_order_downloaded_by_app = 0;
			foreach ($_orders as $_order)
			{
				$customer_comment = "";
				if($_order->getBiebersdorfCustomerordercomment())
				{
					$customer_comment = $_order->getBiebersdorfCustomerordercomment();
				}
				foreach ($_order->getStatusHistoryCollection(true) as $_comment)
				{				
					if($_comment->getComment())
					{
						$customer_comment = $customer_comment." \r\n ".$_comment->getComment();
					}
				}	
				$shipments = $_order->getShipmentsCollection();
				$shipTrack = "";
				foreach ($shipments as $shipment)
				{
					$increment_id = $shipment->getIncrementId();
					$tracks = $shipment->getTracksCollection();
					$trackingInfos=array();
					foreach ($tracks as $track)
					{
						$track = $track->toArray();
					}
					if(isset($track['number']))
					{
						$shipTrack = $track['number'];
					}
		
				}
				$orders=$_order->toArray();
				$_payment=$_order->getPayment();
				$payment=$_payment->toArray();
				# Latest code modififed date for  all country
				$fdate = date("Y-m-d | h:i:s A",strtotime(Mage::app()->getLocale()->date($orders["created_at"], Varien_Date::DATETIME_INTERNAL_FORMAT)));			
				$fdate = explode("|",$fdate);
				$dateCreateOrder= trim($fdate[0]);			
				$timeCreateOrder= trim($fdate[1]);
				#changed on request of nilesh sir 	
				if(!array_key_exists('billing_firstname',$orders) && !array_key_exists('billing_lastname',$orders) )
				{
					$billingAddressArray = $_order->getBillingAddress()->toArray();
					$orders["billing_firstname"]=	$billingAddressArray["firstname"];
					$orders["billing_lastname"]	=	$billingAddressArray["lastname"];
					$orders["billing_company"]	=	$billingAddressArray["company"];
					$orders["billing_street"]	=	$billingAddressArray["street"];
					$orders["billing_city"]		=	$billingAddressArray["city"];
					$orders["billing_region"]	=	$billingAddressArray["region"];
					$orders["billing_postcode"]	=	$billingAddressArray["postcode"];
					$orders["billing_country"]	=	$billingAddressArray["country_id"];
					$orders["customer_email"]	=	$billingAddressArray["customer_email"]?$billingAddressArray["customer_email"]:$orders["customer_email"];
					$orders["billing_telephone"]=	$billingAddressArray["telephone"];
				}
				$responseArray['Orders'][$ord]['OrderInfo'][0]['OrderId'] = $orders['increment_id'];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Title'] = '';
				$responseArray['Orders'][$ord]['OrderInfo'][0]['FirstName'] = $orders["billing_firstname"];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['LastName'] = $orders["billing_lastname"];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Date'] = $dateCreateOrder;
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Time'] = $timeCreateOrder;
				$responseArray['Orders'][$ord]['OrderInfo'][0]['StoreID'] = $orders['store_id'];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['StoreName'] = '';
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Currency'] = $orders['order_currency_code'];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Weight_Symbol'] = "lbs";
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Weight_Symbol_Grams'] = "453.6";
				$responseArray['Orders'][$ord]['OrderInfo'][0]['CustomerId'] = $_order['customer_id'];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Comment'] = $customer_comment;
				$orderStatus = $objNew->_getorderstatuses($storeId);						
				if(array_key_exists($orders['status'],$orderStatus ))	
					$responseArray['Orders'][$ord]['OrderInfo'][0]['Status'] = $orderStatus[$orders['status']];
				else
					$responseArray['Orders'][$ord]['OrderInfo'][0]['Status'] = $orders['status'];
				if($payment['method']=='purchaseorder')
				{ 
					$orders['customer_note'] = $orders['customer_note'] ." Purchase Order Number: ".$payment['po_number'];
				}			
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Notes'] = $orders['customer_note'];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Fax'] = '';
				$item_array = $objNew->getorderitems($orders["entity_id"],$orders["increment_id"]); 
				$item_array = $item_array['items'];							   
				$onlineInfo = array();
				$itemI = 0;
				foreach($item_array as $iInfo) 
				{	
					if(is_object($iInfo['product']))
						$onlineInfo =  $iInfo['product']->toArray();
									
					if(intval($iInfo["qty_ordered"])>0 && is_numeric($iInfo["price"]))
					{			
						unset($productoptions);
						$productoptions = array();
						$productoptions = unserialize($iInfo['product_options']);	
						if(is_array($productoptions['options']) && isset($productoptions['options']))
						{
							if($productoptions['options'])
							{
								if(is_array($productoptions['options']) && !empty($productoptions['options']))
								{
									if(is_array($productoptions['attributes_info']))
									{
										$productoptions['attributes_info']     =    array_merge($productoptions['attributes_info'],$productoptions['options']);
									}else{
										$productoptions['attributes_info']     =    $productoptions['options'];
									}
								}
								unset($productoptions['options'],$productoptions['bundle_options']);
							}           
						}
						if(!empty($productoptions['bundle_options']) && is_array($productoptions['bundle_options']))
						{	
							if(is_array($productoptions['attributes_info']))
							{
								$productoptions['attributes_info'] = array_merge($productoptions['attributes_info'],$productoptions['bundle_options']);
							}else{
								$productoptions['attributes_info'] = $productoptions['bundle_options'];
							}
							unset($productoptions['options'],$productoptions['bundle_options']);	  
						}
						if(isset($iInfo['product']))
						{				
							$product = $iInfo;
							$product['type_id'] = $iInfo['product_type'];
							$product_base = $iInfo['product']->toArray();
							$product['tax_class_id'] = $product_base['tax_class_id'];	
						}else{
							$product = $iInfo;
							$product['type_id'] = $iInfo['product_type'];
							$product['tax_class_id'] = 'no';	
							$productoptions['simple_sku'] = $iInfo['sku'];
						}		
						if($product['type_id']=='bundle')
						{
							$download_option_as_item =true;			
							if($download_option_as_item  == true)
							{
								$iInfo["qty_ordered"] =0;
							}
						}
						if($product['type_id']!='configurable')
						{
							$responseArray['Orders'][$ord]['Items'][$itemI]['ItemCode'] = htmlentities($product['sku'],ENT_QUOTES);
						}else{
							$responseArray['Orders'][$ord]['Items'][$itemI]['ItemCode'] = htmlentities($productoptions['simple_sku'],ENT_QUOTES);
						}
						$responseArray['Orders'][$ord]['Items'][$itemI]['ItemDescription'] = htmlentities(substr($product['name'],0,50),ENT_QUOTES);
						$responseArray['Orders'][$ord]['Items'][$itemI]['ItemShortDescr'] = empty($onlineInfo['description'])?htmlentities(substr($product['description'],0,2000),ENT_QUOTES):htmlentities(substr($onlineInfo['description'],0,2000),ENT_QUOTES);
						$responseArray['Orders'][$ord]['Items'][$itemI]['Quantity'] = intval($iInfo["qty_ordered"]);
						$responseArray['Orders'][$ord]['Items'][$itemI]['ShippedQuantity'] = intval($iInfo["qty_shipped"]);
						$responseArray['Orders'][$ord]['Items'][$itemI]['UnitPrice'] = $iInfo["price"];
						$responseArray['Orders'][$ord]['Items'][$itemI]['Weight'] = $iInfo["weight"];
						$responseArray['Orders'][$ord]['Items'][$itemI]['FreeShipping'] = "N";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "N";
						$responseArray['Orders'][$ord]['Items'][$itemI]['shippingFreight'] = "0.00";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol'] = "lbs";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";	
						if($product['tax_class_id']<=0 || $product['tax_class_id']="")
						{
							$responseArray['Orders'][$ord]['Items'][$itemI]['TaxExempt'] = 'Y';						
						}else{
							$responseArray['Orders'][$ord]['Items'][$itemI]['TaxExempt'] = 'N';		
						}			
						$iInfo['onetime_charges']="0.00";
						$responseArray['Orders'][$ord]['Items'][$itemI]['OneTimeCharge'] = number_format($iInfo['onetime_charges'],2,'.','');
						$responseArray['Orders'][$ord]['Items'][$itemI]['ItemTaxAmount'] = '';
						if(array_key_exists("attributes_info",$productoptions))
						{
							$optionI = 0;
							foreach($productoptions['attributes_info'] as $item_option12)  
							{
								if(is_array($item_option12['value']))
								{
									foreach($item_option12['value'] as $item_option123)
									{
										$item_option1234 .= $item_option123[qty]."x ".$item_option123['title']." $".$item_option123['price'];
									}
									$responseArray['Orders'][$ord]['Items'][$itemI]['ItemOptions'][$optionI]['Name'] = htmlentities($item_option12['label']);
									$responseArray['Orders'][$ord]['Items'][$itemI]['ItemOptions'][$optionI]['Value'] = htmlentities($item_option1234);
									unset($item_option1234);
								}else{
									$responseArray['Orders'][$ord]['Items'][$itemI]['ItemOptions'][$optionI]['Name'] = htmlentities($item_option12['label']);
									$responseArray['Orders'][$ord]['Items'][$itemI]['ItemOptions'][$optionI]['Value'] = htmlentities($item_option12['value']);
								}
								$optionI++;
							}
						}
					}
					$itemI++;
				}		
				#Discount Coupon as line item
				if($orders['coupon_code']!='' || $orders['discount_description']!='')
				{
					$orders["discount_amount"] = $orders["discount_amount"]?$orders["discount_amount"]:$orders["base_discount_amount"];								
					$DESCR1 = $orders['coupon_code']?$orders['coupon_code']:$orders['discount_description'];
					$itemI++;
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemCode'] = htmlentities("Discount Coupon",ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemDescription'] = htmlentities(substr($DESCR1,0,50),ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemShortDescr'] = "Coupon code ".htmlentities(substr($DESCR1,0,50),ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['Quantity'] = intval(1);
					$responseArray['Orders'][$ord]['Items'][$itemI]['UnitPrice'] = "-".abs($orders["discount_amount"]);
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight'] = '';
					$responseArray['Orders'][$ord]['Items'][$itemI]['FreeShipping'] = "N";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "N";
					$responseArray['Orders'][$ord]['Items'][$itemI]['shippingFreight'] = "0.00";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol'] = "lbs";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "Y";
					$orders["discount_amount"] =0;					
				}	
				#Reward Points as line item
				if($orders["reward_points_balance"])
				{
					$itemI++;
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemCode'] = htmlentities('RewardsPoints',ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemDescription'] = htmlentities($orders["reward_points_balance"].'reward points',ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemShortDescr'] =  htmlentities($orders["reward_points_balance"].'reward points',ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['Quantity'] = intval(1);
					$responseArray['Orders'][$ord]['Items'][$itemI]['UnitPrice'] = "-".abs($orders["base_reward_currency_amount"]);
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight'] = '';
					$responseArray['Orders'][$ord]['Items'][$itemI]['FreeShipping'] = "N";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "N";
					$responseArray['Orders'][$ord]['Items'][$itemI]['shippingFreight'] = "0.00";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol'] = "lbs";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "Y";				
				}
					
				/*
				if($order["customer_balance_amount"])
				{				
					$itemI++;
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemCode'] = htmlentities("Store Credit",ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemDescription'] = htmlentities(substr("Store Credit",0,50),ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemShortDescr'] =  htmlentities(substr("Store Credit",0,50),ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['Quantity'] = intval(1);
					$responseArray['Orders'][$ord]['Items'][$itemI]['UnitPrice'] = "-".$order["customer_balance_amount"];
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight'] = '';
					$responseArray['Orders'][$ord]['Items'][$itemI]['FreeShipping'] = "N";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "N";
					$responseArray['Orders'][$ord]['Items'][$itemI]['shippingFreight'] = "0.00";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol'] = "lbs";
					$respSonseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "Y";						
				
				}
				*/
					
				if($orders["gift_cards"])
				{
					$gift_cards = unserialize($orders["gift_cards"]);
					foreach($gift_cards as $gift_card)
					{				
						$itemI++;
						$responseArray['Orders'][$ord]['Items'][$itemI]['ItemCode'] = htmlentities( "GiftCard" ,ENT_QUOTES);
						$responseArray['Orders'][$ord]['Items'][$itemI]['ItemDescription'] = htmlentities(substr("GiftCard #.".$gift_card[c],0,50));
						$responseArray['Orders'][$ord]['Items'][$itemI]['ItemShortDescr'] =  htmlentities(substr($gift_card[c],0,50),ENT_QUOTES);
						$responseArray['Orders'][$ord]['Items'][$itemI]['Quantity'] = intval(1);
						$responseArray['Orders'][$ord]['Items'][$itemI]['UnitPrice'] = "-".abs($gift_card[a]);
						$responseArray['Orders'][$ord]['Items'][$itemI]['Weight'] = '';
						$responseArray['Orders'][$ord]['Items'][$itemI]['FreeShipping'] = "N";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "N";
						$responseArray['Orders'][$ord]['Items'][$itemI]['shippingFreight'] = "0.00";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol'] = "lbs";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "Y";
					}
				}		
				/////////////////////////////////////
				//   billing info
				/////////////////////////////////////
				$PayStatus = "Cleared";
				if ($payment['cc_type']!="")
				{
					$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['CreditCardType'] = $objNew->getCcTypeName($payment['cc_type']);
					if (isset($payment['amount_paid']))
					{
						$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['CreditCardCharge'] = $payment['amount_paid'];
					}else{
						$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['CreditCardCharge'] = '';
					}
					if (isset($payment['cc_exp_month'])&&isset($payment['cc_exp_year'])){
						$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['ExpirationDate'] = sprintf('%02d',$payment['cc_exp_month']).substr($payment['cc_exp_year'],-2,2);
					}else{
						$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['ExpirationDate'] = '';
					}
					$payment['cc_number_enc'] = Mage::helper('core')->decrypt($payment['cc_number_enc']);
					$CreditCardName = (isset($payment['cc_owner'])?($payment['cc_owner']):"");
					$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['CreditCardName'] = $CreditCardName;
					$CreditCardNumber = ((isset($payment['cc_number_enc']) && $payment['cc_number_enc']!='')?$payment['cc_number_enc']:$payment['cc_last4']);
					$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['CreditCardNumber'] = $CreditCardNumber;
					$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['CVV2'] = '';
					$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['AdvanceInfo'] = '';
					$transcationId ="";
					$transcationId = (isset($payment['cc_trans_id'])?($payment['cc_trans_id']):"");
					$transcationId  = $transcationId ? $transcationId : $payment['last_trans_id'];	   			   
					$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['TransactionId'] = $transcationId;
				}
				if (isset($payment['amount_ordered'])&&isset($payment['amount_paid']))
				{
					if (($payment['amount_paid']==$payment['amount_ordered']))
						$PayStatus = "Pending";
				}
				# for version 1.4.1.0
				$responseArray['Orders'][$ord]['Bill'][0]['PayMethod'] = $objNew->getPaymentlabel($payment['method']);
				$responseArray['Orders'][$ord]['Bill'][0]['Title'] = '';
				$responseArray['Orders'][$ord]['Bill'][0]['FirstName'] = $orders["billing_firstname"];
				$responseArray['Orders'][$ord]['Bill'][0]['LastName'] = $orders["billing_lastname"];
				if (!empty($orders["billing_company"]))
				{
					$responseArray['Orders'][$ord]['Bill'][0]['CompanyName'] = $orders["billing_company"];
				}else{
					$responseArray['Orders'][$ord]['Bill'][0]['CompanyName'] = '';
				}
				$orders["billing_street"] = explode("\n",$orders["billing_street"]);			
				$responseArray['Orders'][$ord]['Bill'][0]['Address1'] = $orders["billing_street"][0];
				$responseArray['Orders'][$ord]['Bill'][0]['Address2'] = $orders["billing_street"][1];
				$responseArray['Orders'][$ord]['Bill'][0]['City'] = $orders["billing_city"];
				$responseArray['Orders'][$ord]['Bill'][0]['State'] = $orders["billing_region"];
				$responseArray['Orders'][$ord]['Bill'][0]['Zip'] = $orders["billing_postcode"];
				$responseArray['Orders'][$ord]['Bill'][0]['Country'] = trim($country[$orders["billing_country"]]);
				$responseArray['Orders'][$ord]['Bill'][0]['Email'] = $orders["customer_email"];
				$responseArray['Orders'][$ord]['Bill'][0]['Phone'] = $orders["billing_telephone"];
				$responseArray['Orders'][$ord]['Bill'][0]['PONumber'] = $payment['po_number'];
				/////////////////////////////////////
				//   CreditCard info
				/////////////////////////////////////
				
				$ship_career = explode("-",$orders["shipping_description"]);		   
				$responseArray['Orders'][$ord]['Ship'][0]['ShipMethod'] = $ship_career[1];
				$responseArray['Orders'][$ord]['Ship'][0]['Carrier'] = $ship_career[0];
				$responseArray['Orders'][$ord]['Ship'][0]['TrackingNumber'] = $shipTrack;
				$responseArray['Orders'][$ord]['Ship'][0]['Title'] = '' ;
				if(!array_key_exists('shipping_firstname',$orders) && !array_key_exists('shipping_lastname',$orders) )
				{
					$shippingAddressArray = $_order->getShippingAddress();
					if(is_array($shippingAddressArray))		   	
					$shippingAddressArray = $shippingAddressArray->toArray();
					$orders["shipping_firstname"]=$shippingAddressArray["firstname"];
					$orders["shipping_lastname"]=$shippingAddressArray["lastname"];
					$orders["shipping_company"]=$shippingAddressArray["company"];
					$orders["shipping_street"]=$shippingAddressArray["street"];
					$orders["shipping_city"]=$shippingAddressArray["city"];
					$orders["shipping_region"]=$shippingAddressArray["region"];
					$orders["shipping_postcode"]=$shippingAddressArray["postcode"];
					$orders["shipping_country"]=$shippingAddressArray["country_id"];
					$orders["customer_email"]=$shippingAddressArray["customer_email"]?$shippingAddressArray["customer_email"]:$orders["customer_email"];
					$orders["shipping_telephone"]=$shippingAddressArray["telephone"];
				}
				$responseArray['Orders'][$ord]['Ship'][0]['FirstName'] = $orders["shipping_firstname"];
				$responseArray['Orders'][$ord]['Ship'][0]['LastName'] = $orders["shipping_lastname"];
				if (!empty($orders["shipping_company"]))
				{
					$responseArray['Orders'][$ord]['Ship'][0]['CompanyName'] = $orders["shipping_company"];
				}else{
					$responseArray['Orders'][$ord]['Ship'][0]['CompanyName'] = '';
				}
				$orders["shipping_street"] = explode("\n",$orders["shipping_street"]);		
				$responseArray['Orders'][$ord]['Ship'][0]['Address1'] = $orders["shipping_street"][0];
				$responseArray['Orders'][$ord]['Ship'][0]['Address2'] = $orders["shipping_street"][1];
				$responseArray['Orders'][$ord]['Ship'][0]['City'] = $orders["shipping_city"];
				$responseArray['Orders'][$ord]['Ship'][0]['State'] = $orders["shipping_region"];
				$responseArray['Orders'][$ord]['Ship'][0]['Zip'] = $orders["shipping_postcode"];
				$responseArray['Orders'][$ord]['Ship'][0]['Country'] = trim($country[$orders["shipping_country"]]);
				$responseArray['Orders'][$ord]['Ship'][0]['Email'] = $orders["customer_email"];
				$responseArray['Orders'][$ord]['Ship'][0]['Phone'] = $orders["shipping_telephone"];
				/////////////////////////////////////
			   //   Charges info
			  /////////////////////////////////////
				$responseArray['Orders'][$ord]['Charges'][0]['Discount'] = abs($orders["discount_amount"]);
				#added for 2.6.1	
				$responseArray['Orders'][$ord]['Charges'][0]['StoreCredit'] = $orders["customer_balance_amount"];
				$responseArray['Orders'][$ord]['Charges'][0]['Tax'] = $orders["tax_amount"];
				$responseArray['Orders'][$ord]['Charges'][0]['Shipping'] = $orders["shipping_amount"];
				$responseArray['Orders'][$ord]['Charges'][0]['Total'] = $orders["grand_total"];
				$ord++; 
				#Set last order id for apns alert
				$last_order_downloaded_by_app = $orders['increment_id'];
				
			}
			
			#Update apns-config.txt for apns alert
			modifyApnsConfigFile($last_order_downloaded_by_app, 'get_order', $devicetokenid);
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

					  
	function getOrders($username,$password,$datefrom,$start_order_no,$ecc_excl_list,$order_per_response="25",$storeid=1,$devicetokenid)
	{
		$responseArray = array();
		if(!$start_order_no==0)
		{
			$my_orders = Mage::getModel('sales/order')->loadByIncrementId($start_order_no);			
			$my_orders1 = $my_orders->toArray();
			$start_order_no = $my_orders1['entity_id'];									
			if(!isset($start_order_no) || $start_order_no=='')
			{
				$start_order_no=0;						
			}			
		}
		$storeId=getDefaultStore($storeid);	
		if(!isset($datefrom) or empty($datefrom)) $datefrom='2000-01-01';
		if(!isset($dateto) or empty($dateto)) $dateto=date('m-d-Y');
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			if($others == 'by_updated_at')
			{
				$by_updated_date = 'updated_at';	
			}
			$_countorders = $_orders = $objNew->GetOrders($datefrom,$start_order_no,$ecc_excl_list,$storeId,$order_per_response,$by_updated_date);	
			$countorders_array = $_countorders->toArray();	
			$country = array();
			$country_data = Mage::getResourceModel('directory/country_collection')->load()->toOptionArray();
			foreach($country_data as $ck=>$cv)
			{	
				if($cv['value']!='')
				$country[$cv['value']] = trim($cv['label']);
			}	
			unset($country_data);	
			if(array_key_exists('items',$countorders_array))
				$countorders_array = $countorders_array['items'];
			if(count($countorders_array)>0)
			{
				$orders_remained = count($countorders_array);
			}else{
				$orders_remained = 0;
			}
			$orders_array=$_orders->toArray();
			$no_orders = false;
			if($orders_remained<1)
			{
				$no_orders = true;
			}
			$responseArray['StatusCode'] = $no_orders?"9999":"0";
			$responseArray['StatusMessage'] = $no_orders?"No Orders returned":"Total Orders:".$orders_remained;
			$responseArray['TotalRecordFound'] = $_orders->getSize()?$_orders->getSize():'0';
			$responseArray['TotalRecordSent'] = count($countorders_array)?count($countorders_array):'0';
			if ($no_orders)
			{	
						$response = json_encode($responseArray);
						return responce($response);
						exit();
			}
			$obj = new Mage_Sales_Model_Order();
			$ord = 0;
			$last_order_downloaded_by_app = 0;
			foreach ($_orders as $_order)
			{
				$customer_comment = "";
				if($_order->getBiebersdorfCustomerordercomment())
				{
					$customer_comment = $_order->getBiebersdorfCustomerordercomment();
				}
				foreach ($_order->getStatusHistoryCollection(true) as $_comment)
				{				
					if($_comment->getComment())
					{
						$customer_comment = $customer_comment." \r\n ".$_comment->getComment();
					}
				}	
				$shipments = $_order->getShipmentsCollection();
				$shipTrack = "";
				foreach ($shipments as $shipment)
				{
					$increment_id = $shipment->getIncrementId();
					$tracks = $shipment->getTracksCollection();
					$trackingInfos=array();
					foreach ($tracks as $track)
					{
						$track = $track->toArray();
					}
					if(isset($track['number']))
					{
						$shipTrack = $track['number'];
					}
		
				}
				$orders=$_order->toArray();
				$_payment=$_order->getPayment();
				$payment=$_payment->toArray();
				# Latest code modififed date for  all country
				$fdate = date("Y-m-d | h:i:s A",strtotime(Mage::app()->getLocale()->date($orders["created_at"], Varien_Date::DATETIME_INTERNAL_FORMAT)));			
				$fdate = explode("|",$fdate);
				$dateCreateOrder= trim($fdate[0]);			
				$timeCreateOrder= trim($fdate[1]);
				#changed on request of nilesh sir 	
				if(!array_key_exists('billing_firstname',$orders) && !array_key_exists('billing_lastname',$orders) )
				{
					$billingAddressArray = $_order->getBillingAddress()->toArray();
					$orders["billing_firstname"]=	$billingAddressArray["firstname"];
					$orders["billing_lastname"]	=	$billingAddressArray["lastname"];
					$orders["billing_company"]	=	$billingAddressArray["company"];
					$orders["billing_street"]	=	$billingAddressArray["street"];
					$orders["billing_city"]		=	$billingAddressArray["city"];
					$orders["billing_region"]	=	$billingAddressArray["region"];
					$orders["billing_postcode"]	=	$billingAddressArray["postcode"];
					$orders["billing_country"]	=	$billingAddressArray["country_id"];
					$orders["customer_email"]	=	$billingAddressArray["customer_email"]?$billingAddressArray["customer_email"]:$orders["customer_email"];
					$orders["billing_telephone"]=	$billingAddressArray["telephone"];
				}
				$responseArray['Orders'][$ord]['OrderInfo'][0]['OrderId'] = $orders['increment_id'];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Title'] = '';
				$responseArray['Orders'][$ord]['OrderInfo'][0]['FirstName'] = $orders["billing_firstname"];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['LastName'] = $orders["billing_lastname"];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Date'] = $dateCreateOrder;
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Time'] = $timeCreateOrder;
				$responseArray['Orders'][$ord]['OrderInfo'][0]['StoreID'] = $orders['store_id'];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['StoreName'] = '';
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Currency'] = $orders['order_currency_code'];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Weight_Symbol'] = "lbs";
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Weight_Symbol_Grams'] = "453.6";
				$responseArray['Orders'][$ord]['OrderInfo'][0]['CustomerId'] = $_order['customer_id'];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Comment'] = $customer_comment;
				$orderStatus = $objNew->_getorderstatuses($storeId);						
				if(array_key_exists($orders['status'],$orderStatus ))	
					$responseArray['Orders'][$ord]['OrderInfo'][0]['Status'] = $orderStatus[$orders['status']];
				else
					$responseArray['Orders'][$ord]['OrderInfo'][0]['Status'] = $orders['status'];
				if($payment['method']=='purchaseorder')
				{ 
					$orders['customer_note'] = $orders['customer_note'] ." Purchase Order Number: ".$payment['po_number'];
				}			
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Notes'] = $orders['customer_note'];
				$responseArray['Orders'][$ord]['OrderInfo'][0]['Fax'] = '';
				$item_array = $objNew->getorderitems($orders["entity_id"],$orders["increment_id"]); 
				$item_array = $item_array['items'];							   
				$onlineInfo = array();
				$itemI = 0;
				foreach($item_array as $iInfo) 
				{	
					if(is_object($iInfo['product']))
						$onlineInfo =  $iInfo['product']->toArray();
									
					if(intval($iInfo["qty_ordered"])>0 && is_numeric($iInfo["price"]))
					{			
						unset($productoptions);
						$productoptions = array();
						$productoptions = unserialize($iInfo['product_options']);	
						if(is_array($productoptions['options']) && isset($productoptions['options']))
						{
							if($productoptions['options'])
							{
								if(is_array($productoptions['options']) && !empty($productoptions['options']))
								{
									if(is_array($productoptions['attributes_info']))
									{
										$productoptions['attributes_info']     =    array_merge($productoptions['attributes_info'],$productoptions['options']);
									}else{
										$productoptions['attributes_info']     =    $productoptions['options'];
									}
								}
								unset($productoptions['options'],$productoptions['bundle_options']);
							}           
						}
						if(!empty($productoptions['bundle_options']) && is_array($productoptions['bundle_options']))
						{	
							if(is_array($productoptions['attributes_info']))
							{
								$productoptions['attributes_info'] = array_merge($productoptions['attributes_info'],$productoptions['bundle_options']);
							}else{
								$productoptions['attributes_info'] = $productoptions['bundle_options'];
							}
							unset($productoptions['options'],$productoptions['bundle_options']);	  
						}
						if(isset($iInfo['product']))
						{				
							$product = $iInfo;
							$product['type_id'] = $iInfo['product_type'];
							$product_base = $iInfo['product']->toArray();
							$product['tax_class_id'] = $product_base['tax_class_id'];	
						}else{
							$product = $iInfo;
							$product['type_id'] = $iInfo['product_type'];
							$product['tax_class_id'] = 'no';	
							$productoptions['simple_sku'] = $iInfo['sku'];
						}		
						if($product['type_id']=='bundle')
						{
							$download_option_as_item =true;			
							if($download_option_as_item  == true)
							{
								$iInfo["qty_ordered"] =0;
							}
						}
						if($product['type_id']!='configurable')
						{
							$responseArray['Orders'][$ord]['Items'][$itemI]['ItemCode'] = htmlentities($product['sku'],ENT_QUOTES);
						}else{
							$responseArray['Orders'][$ord]['Items'][$itemI]['ItemCode'] = htmlentities($productoptions['simple_sku'],ENT_QUOTES);
						}
						$responseArray['Orders'][$ord]['Items'][$itemI]['ItemDescription'] = htmlentities(substr($product['name'],0,50),ENT_QUOTES);
						$responseArray['Orders'][$ord]['Items'][$itemI]['ItemShortDescr'] = empty($onlineInfo['description'])?htmlentities(substr($product['description'],0,2000),ENT_QUOTES):htmlentities(substr($onlineInfo['description'],0,2000),ENT_QUOTES);
						$responseArray['Orders'][$ord]['Items'][$itemI]['Quantity'] = intval($iInfo["qty_ordered"]);
						$responseArray['Orders'][$ord]['Items'][$itemI]['ShippedQuantity'] = intval($iInfo["qty_shipped"]);
						$responseArray['Orders'][$ord]['Items'][$itemI]['UnitPrice'] = $iInfo["price"];
						$responseArray['Orders'][$ord]['Items'][$itemI]['Weight'] = $iInfo["weight"];
						$responseArray['Orders'][$ord]['Items'][$itemI]['FreeShipping'] = "N";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "N";
						$responseArray['Orders'][$ord]['Items'][$itemI]['shippingFreight'] = "0.00";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol'] = "lbs";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";	
						if($product['tax_class_id']<=0 || $product['tax_class_id']="")
						{
							$responseArray['Orders'][$ord]['Items'][$itemI]['TaxExempt'] = 'Y';						
						}else{
							$responseArray['Orders'][$ord]['Items'][$itemI]['TaxExempt'] = 'N';		
						}			
						$iInfo['onetime_charges']="0.00";
						$responseArray['Orders'][$ord]['Items'][$itemI]['OneTimeCharge'] = number_format($iInfo['onetime_charges'],2,'.','');
						$responseArray['Orders'][$ord]['Items'][$itemI]['ItemTaxAmount'] = '';
						if(array_key_exists("attributes_info",$productoptions))
						{
							$optionI = 0;
							foreach($productoptions['attributes_info'] as $item_option12)  
							{
								if(is_array($item_option12['value']))
								{
									foreach($item_option12['value'] as $item_option123)
									{
										$item_option1234 .= $item_option123[qty]."x ".$item_option123['title']." $".$item_option123['price'];
									}
									$responseArray['Orders'][$ord]['Items'][$itemI]['ItemOptions'][$optionI]['Name'] = htmlentities($item_option12['label']);
									$responseArray['Orders'][$ord]['Items'][$itemI]['ItemOptions'][$optionI]['Value'] = htmlentities($item_option1234);
									unset($item_option1234);
								}else{
									$responseArray['Orders'][$ord]['Items'][$itemI]['ItemOptions'][$optionI]['Name'] = htmlentities($item_option12['label']);
									$responseArray['Orders'][$ord]['Items'][$itemI]['ItemOptions'][$optionI]['Value'] = htmlentities($item_option12['value']);
								}
								$optionI++;
							}
						}
					}
					$itemI++;
				}		
				#Discount Coupon as line item
				if($orders['coupon_code']!='' || $orders['discount_description']!='')
				{
					$orders["discount_amount"] = $orders["discount_amount"]?$orders["discount_amount"]:$orders["base_discount_amount"];								
					$DESCR1 = $orders['coupon_code']?$orders['coupon_code']:$orders['discount_description'];
					$itemI++;
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemCode'] = htmlentities("Discount Coupon",ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemDescription'] = htmlentities(substr($DESCR1,0,50),ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemShortDescr'] = "Coupon code ".htmlentities(substr($DESCR1,0,50),ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['Quantity'] = intval(1);
					$responseArray['Orders'][$ord]['Items'][$itemI]['UnitPrice'] = "-".abs($orders["discount_amount"]);
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight'] = '';
					$responseArray['Orders'][$ord]['Items'][$itemI]['FreeShipping'] = "N";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "N";
					$responseArray['Orders'][$ord]['Items'][$itemI]['shippingFreight'] = "0.00";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol'] = "lbs";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "Y";
					$orders["discount_amount"] =0;					
				}	
				#Reward Points as line item
				if($orders["reward_points_balance"])
				{
					$itemI++;
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemCode'] = htmlentities('RewardsPoints',ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemDescription'] = htmlentities($orders["reward_points_balance"].'reward points',ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemShortDescr'] =  htmlentities($orders["reward_points_balance"].'reward points',ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['Quantity'] = intval(1);
					$responseArray['Orders'][$ord]['Items'][$itemI]['UnitPrice'] = "-".abs($orders["base_reward_currency_amount"]);
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight'] = '';
					$responseArray['Orders'][$ord]['Items'][$itemI]['FreeShipping'] = "N";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "N";
					$responseArray['Orders'][$ord]['Items'][$itemI]['shippingFreight'] = "0.00";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol'] = "lbs";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "Y";				
				}
					
				/*
				if($order["customer_balance_amount"])
				{				
					$itemI++;
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemCode'] = htmlentities("Store Credit",ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemDescription'] = htmlentities(substr("Store Credit",0,50),ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['ItemShortDescr'] =  htmlentities(substr("Store Credit",0,50),ENT_QUOTES);
					$responseArray['Orders'][$ord]['Items'][$itemI]['Quantity'] = intval(1);
					$responseArray['Orders'][$ord]['Items'][$itemI]['UnitPrice'] = "-".$order["customer_balance_amount"];
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight'] = '';
					$responseArray['Orders'][$ord]['Items'][$itemI]['FreeShipping'] = "N";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "N";
					$responseArray['Orders'][$ord]['Items'][$itemI]['shippingFreight'] = "0.00";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol'] = "lbs";
					$respSonseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";
					$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "Y";						
				
				}
				*/
					
				if($orders["gift_cards"])
				{
					$gift_cards = unserialize($orders["gift_cards"]);
					foreach($gift_cards as $gift_card)
					{				
						$itemI++;
						$responseArray['Orders'][$ord]['Items'][$itemI]['ItemCode'] = htmlentities( "GiftCard" ,ENT_QUOTES);
						$responseArray['Orders'][$ord]['Items'][$itemI]['ItemDescription'] = htmlentities(substr("GiftCard #.".$gift_card[c],0,50));
						$responseArray['Orders'][$ord]['Items'][$itemI]['ItemShortDescr'] =  htmlentities(substr($gift_card[c],0,50),ENT_QUOTES);
						$responseArray['Orders'][$ord]['Items'][$itemI]['Quantity'] = intval(1);
						$responseArray['Orders'][$ord]['Items'][$itemI]['UnitPrice'] = "-".abs($gift_card[a]);
						$responseArray['Orders'][$ord]['Items'][$itemI]['Weight'] = '';
						$responseArray['Orders'][$ord]['Items'][$itemI]['FreeShipping'] = "N";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "N";
						$responseArray['Orders'][$ord]['Items'][$itemI]['shippingFreight'] = "0.00";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol'] = "lbs";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Weight_Symbol_Grams'] = "453.6";
						$responseArray['Orders'][$ord]['Items'][$itemI]['Discounted'] = "Y";
					}
				}		
				/////////////////////////////////////
				//   billing info
				/////////////////////////////////////
				$PayStatus = "Cleared";
				if ($payment['cc_type']!="")
				{
					$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['CreditCardType'] = $objNew->getCcTypeName($payment['cc_type']);
					if (isset($payment['amount_paid']))
					{
						$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['CreditCardCharge'] = $payment['amount_paid'];
					}else{
						$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['CreditCardCharge'] = '';
					}
					if (isset($payment['cc_exp_month'])&&isset($payment['cc_exp_year'])){
						$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['ExpirationDate'] = sprintf('%02d',$payment['cc_exp_month']).substr($payment['cc_exp_year'],-2,2);
					}else{
						$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['ExpirationDate'] = '';
					}
					$payment['cc_number_enc'] = Mage::helper('core')->decrypt($payment['cc_number_enc']);
					$CreditCardName = (isset($payment['cc_owner'])?($payment['cc_owner']):"");
					$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['CreditCardName'] = $CreditCardName;
					$CreditCardNumber = ((isset($payment['cc_number_enc']) && $payment['cc_number_enc']!='')?$payment['cc_number_enc']:$payment['cc_last4']);
					$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['CreditCardNumber'] = $CreditCardNumber;
					$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['CVV2'] = '';
					$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['AdvanceInfo'] = '';
					$transcationId ="";
					$transcationId = (isset($payment['cc_trans_id'])?($payment['cc_trans_id']):"");
					$transcationId  = $transcationId ? $transcationId : $payment['last_trans_id'];	   			   
					$responseArray['Orders'][$ord]['Bill'][0]['CreditCard']['TransactionId'] = $transcationId;
				}
				if (isset($payment['amount_ordered'])&&isset($payment['amount_paid']))
				{
					if (($payment['amount_paid']==$payment['amount_ordered']))
						$PayStatus = "Pending";
				}
				# for version 1.4.1.0
				$responseArray['Orders'][$ord]['Bill'][0]['PayMethod'] = $objNew->getPaymentlabel($payment['method']);
				$responseArray['Orders'][$ord]['Bill'][0]['Title'] = '';
				$responseArray['Orders'][$ord]['Bill'][0]['FirstName'] = $orders["billing_firstname"];
				$responseArray['Orders'][$ord]['Bill'][0]['LastName'] = $orders["billing_lastname"];
				if (!empty($orders["billing_company"]))
				{
					$responseArray['Orders'][$ord]['Bill'][0]['CompanyName'] = $orders["billing_company"];
				}else{
					$responseArray['Orders'][$ord]['Bill'][0]['CompanyName'] = '';
				}
				$orders["billing_street"] = explode("\n",$orders["billing_street"]);			
				$responseArray['Orders'][$ord]['Bill'][0]['Address1'] = $orders["billing_street"][0];
				$responseArray['Orders'][$ord]['Bill'][0]['Address2'] = $orders["billing_street"][1];
				$responseArray['Orders'][$ord]['Bill'][0]['City'] = $orders["billing_city"];
				$responseArray['Orders'][$ord]['Bill'][0]['State'] = $orders["billing_region"];
				$responseArray['Orders'][$ord]['Bill'][0]['Zip'] = $orders["billing_postcode"];
				$responseArray['Orders'][$ord]['Bill'][0]['Country'] = trim($country[$orders["billing_country"]]);
				$responseArray['Orders'][$ord]['Bill'][0]['Email'] = $orders["customer_email"];
				$responseArray['Orders'][$ord]['Bill'][0]['Phone'] = $orders["billing_telephone"];
				$responseArray['Orders'][$ord]['Bill'][0]['PONumber'] = $payment['po_number'];
				/////////////////////////////////////
				//   CreditCard info
				/////////////////////////////////////
				
				$ship_career = explode("-",$orders["shipping_description"]);		   
				$responseArray['Orders'][$ord]['Ship'][0]['ShipMethod'] = $ship_career[1];
				$responseArray['Orders'][$ord]['Ship'][0]['Carrier'] = $ship_career[0];
				$responseArray['Orders'][$ord]['Ship'][0]['TrackingNumber'] = $shipTrack;
				$responseArray['Orders'][$ord]['Ship'][0]['Title'] = '' ;
				if(!array_key_exists('shipping_firstname',$orders) && !array_key_exists('shipping_lastname',$orders) )
				{
					$shippingAddressArray = $_order->getShippingAddress();
					if(is_array($shippingAddressArray))		   	
					$shippingAddressArray = $shippingAddressArray->toArray();
					$orders["shipping_firstname"]=$shippingAddressArray["firstname"];
					$orders["shipping_lastname"]=$shippingAddressArray["lastname"];
					$orders["shipping_company"]=$shippingAddressArray["company"];
					$orders["shipping_street"]=$shippingAddressArray["street"];
					$orders["shipping_city"]=$shippingAddressArray["city"];
					$orders["shipping_region"]=$shippingAddressArray["region"];
					$orders["shipping_postcode"]=$shippingAddressArray["postcode"];
					$orders["shipping_country"]=$shippingAddressArray["country_id"];
					$orders["customer_email"]=$shippingAddressArray["customer_email"]?$shippingAddressArray["customer_email"]:$orders["customer_email"];
					$orders["shipping_telephone"]=$shippingAddressArray["telephone"];
				}
				$responseArray['Orders'][$ord]['Ship'][0]['FirstName'] = $orders["shipping_firstname"];
				$responseArray['Orders'][$ord]['Ship'][0]['LastName'] = $orders["shipping_lastname"];
				if (!empty($orders["shipping_company"]))
				{
					$responseArray['Orders'][$ord]['Ship'][0]['CompanyName'] = $orders["shipping_company"];
				}else{
					$responseArray['Orders'][$ord]['Ship'][0]['CompanyName'] = '';
				}
				$orders["shipping_street"] = explode("\n",$orders["shipping_street"]);		
				$responseArray['Orders'][$ord]['Ship'][0]['Address1'] = $orders["shipping_street"][0];
				$responseArray['Orders'][$ord]['Ship'][0]['Address2'] = $orders["shipping_street"][1];
				$responseArray['Orders'][$ord]['Ship'][0]['City'] = $orders["shipping_city"];
				$responseArray['Orders'][$ord]['Ship'][0]['State'] = $orders["shipping_region"];
				$responseArray['Orders'][$ord]['Ship'][0]['Zip'] = $orders["shipping_postcode"];
				$responseArray['Orders'][$ord]['Ship'][0]['Country'] = trim($country[$orders["shipping_country"]]);
				$responseArray['Orders'][$ord]['Ship'][0]['Email'] = $orders["customer_email"];
				$responseArray['Orders'][$ord]['Ship'][0]['Phone'] = $orders["shipping_telephone"];
				/////////////////////////////////////
			   //   Charges info
			  /////////////////////////////////////
				$responseArray['Orders'][$ord]['Charges'][0]['Discount'] = abs($orders["discount_amount"]);
				#added for 2.6.1	
				$responseArray['Orders'][$ord]['Charges'][0]['StoreCredit'] = $orders["customer_balance_amount"];
				$responseArray['Orders'][$ord]['Charges'][0]['Tax'] = $orders["tax_amount"];
				$responseArray['Orders'][$ord]['Charges'][0]['Shipping'] = $orders["shipping_amount"];
				$responseArray['Orders'][$ord]['Charges'][0]['Total'] = $orders["grand_total"];
				$ord++;  
				#Set last order id for apns alert
				$last_order_downloaded_by_app = $orders['increment_id'];
				
			}
			
			#Update apns-config.txt for apns alert
			modifyApnsConfigFile($last_order_downloaded_by_app, 'get_order', $devicetokenid);
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

	function getStores($username,$password,$devicetokenid)
	{
		##############		Code to create and modify apns-config.txt	###################
		$devicetokenid = str_replace(" ","", $devicetokenid);
		$devicetokenid = substr($devicetokenid,1,strlen($devicetokenid)-2);
		
		$device_token_array	=	array();
		$alert_flag_array	=	array();
		$ecc_base_path = Mage::getBaseDir('base').'/ecc';
		$apns_config_file	=	$ecc_base_path.'/apns-config.txt';
		//$apns_config_file	=	$_SERVER['DOCUMENT_ROOT'].'/magento_1_6_1_0/ecc/apns-config.txt';
		
		
		$config_str			=	'';
		$config_array		=	array();
		$config_array_count	=	0;
		$is_device_not_exist	=	true;
		if(file_exists($apns_config_file)) {
		
			//Code to read DeviceToken
			//echo $_SERVER['DOCUMENT_ROOT'].'/magento_1_6_1_0/ecc/apns-config.txt';
			$file = fopen($apns_config_file, 'r') or exit('Unable to open file for read!');
			//$file = fopen('apns-config.txt', 'r') or exit('Unable to open file!');
			
			//Output a line of the file until the end is reached
			while(!feof($file)) {
			  
			  $line	=	fgets($file);
			  $line_array	=	explode('::', $line); //print_r($line_array);
			  $config_str	=	$line_array[0];
			  $config_str	=	json_decode($config_str);
			  #print_r($config_str);die('reached');
			  /*foreach($config_str as $k=>$v) {
				$config_array[$k] = object_2_array($v);
				$is_device_exist	=	true;
				if($config_array['devices'][0]['id'] == $devicetokenid) {
					  $is_device_not_exist	=	false;
					  $config_array['devices'][0]['newOrder']	=	"";
				  }
				$config_array_count++;
			  }*/
			  
			  
			  foreach($config_str as $k=>$v) {
				$config_array[$k] = object_2_array($v);
				#print_r($config_array_new);
				foreach($config_array['devices'] as $k1 => $v1) {
					#print_r($v1);
					if($devicetokenid == $v1['id']) {
						#$config_array[$k][] = $v1;
						$is_device_not_exist	=	false;
						$config_array['devices'][$config_array_count]['newOrder']	=	'';
					}
					$config_array_count++;
				}
			  }
			  
			  
			}
			fclose($file);
			
			
		}
		
		if($is_device_not_exist) {
			$config_array_count	=	count($config_array['devices']);
			$config_array['devices'][$config_array_count] = array('id' => $devicetokenid, 'newOrder' =>"", 'newUser' =>"");
			#print_r($config_array['devices']);die();
		}

		$fh = fopen($apns_config_file, 'w') or die("can't open file");

		$stringData = json_encode($config_array);
		//die($stringData);
		fwrite($fh, $stringData);
		fclose($fh);
		
		$apns_config_file	=	$ecc_base_path.'/apns-config.txt';
		chmod($apns_config_file, 0777);
		########################################################################
		
		
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{	
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$stores = $objNew->getstores();
			if(count($stores)>0)
			{
			$s=0;
				for($i=0;$i<count($stores['items']);$i++)
				{
					if($stores['items'][$i]['group_id']>0)
					{
						$views = Mage::getModel('core/store')
										->getCollection()    
										->addGroupFilter($stores['items'][$i]['group_id'])      
										->load();
						$views = $views->toArray();
						foreach($views['items'] as $view)
						{

							$responseArray['Stores'][$s]['StoreID'] = htmlentities($view['store_id']);
							$responseArray['Stores'][$s]['StoreName'] = htmlentities($stores['items'][$i]['name'], ENT_QUOTES);
							$responseArray['Stores'][$s]['StoreWebsiteId'] = htmlentities($stores['items'][$i]['website_id'], ENT_QUOTES);
							$responseArray['Stores'][$s]['StoreWebsiteName'] = htmlentities($stores['items'][$i]['website_name'], ENT_QUOTES);
							$responseArray['Stores'][$s]['StoreRootCategoryId'] = htmlentities($stores['items'][$i]['root_category_id'], ENT_QUOTES);
							$responseArray['Stores'][$s]['StoreDefaultStoreId'] = htmlentities($stores['items'][$i]['default_store_id'], ENT_QUOTES);
							$responseArray['Stores'][$s]['StoreType'] = htmlentities('magento');
						}
						$s++;
					}
				}
			}
		}			
		$response = json_encode($responseArray);
		return responce($response);
	}
	
	
	#apns alert code start from here
	function object_2_array($data) 
	{
		if(is_array($data) || is_object($data))
		{
			$result = array(); 
			foreach ($data as $key => $value)
			{ 
				$result[$key] = object_2_array($value); 
			}
			return $result;
		}
		return $data;
	}
	
	
	
	function runApns($username,$password,$storeId=1) {
		
		
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		
		if($status!="0"){ //login name invalid
		
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
			
		}else{
			
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			
			#modifyApnsConfigFile('100000026', 'get_order', '51370ecb8c3f78a3ee14fa77617deb45ea2584841b9b6c99dec35bc0d8fb7402');die();
		
			$stores = $objNew->getstores();
			$StoreName = 'Magento Store';
			if(count($stores)>0) {
				$s=0;
				for($i=0;$i<count($stores['items']);$i++) {
					if($stores['items'][$i]['group_id']>0) {
						$views = Mage::getModel('core/store')
										->getCollection()    
										->addGroupFilter($stores['items'][$i]['group_id'])      
										->load();
						$views = $views->toArray();
						foreach($views['items'] as $view) {
	
							if($view['store_id'] == $storeId) {
								$StoreName = htmlentities($stores['items'][$i]['name'], ENT_QUOTES);
							}
						}
						$s++;
					}
				}
			}
			
			$device_token_array	=	array();
			$alert_flag_array	=	array();
			$ecc_base_path = Mage::getBaseDir('base').'/ecc';
			$apns_config_file	=	$ecc_base_path.'/apns-config.txt';
			//$apns_config_file = $_SERVER['DOCUMENT_ROOT'].'/magento_1_6_1_0/ecc/apns-config.txt';
			
			$config_str			=	'';
			$config_array		=	array();
			$config_array_count	=	0;
			if(file_exists($apns_config_file)) {
			
				//Code to read DeviceToken
				//echo $_SERVER['DOCUMENT_ROOT'].'/magento/ecc/apns-config.txt';
				$file = fopen($apns_config_file, 'r') or exit('Unable to open file for read!');
				//$file = fopen('apns-config.txt', 'r') or exit('Unable to open file!');
				
				//Output a line of the file until the end is reached
				while(!feof($file)) {
				  
				  $line	=	fgets($file);
				  $line_array	=	explode('::', $line); //print_r($line_array);
				  $config_str	=	$line_array[0];
				  $config_str	=	json_decode($config_str);
				  #print_r($config_str);die('reached');
				  foreach($config_str as $k=>$v) {
					$config_array[$k] = object_2_array($v);
					$config_array_count++;
				  }
				}
				fclose($file);
			}
			
			
			#print_r($config_array['devices']);die('reached');
			
			foreach($config_array['devices'] as $device_array) {
			
				if(isset($device_array['newOrder'])) {
					$last_order_id_in_app	=	isset($device_array['newOrder']) ? $device_array['newOrder'] : 0;
					//Code to check new order
					$orders	=	Mage::getResourceModel('sales/order_collection')
										->addAttributeToFilter('store_id', $storeId)
										->addAttributeToFilter('increment_id', array('gt' => $last_order_id_in_app))
										->addAttributeToSort('entity_id', 'asc')
										 ->setOrder('created_at', 'desc')
										->load();
					$orders_array=$orders->toArray();
					unset($orders);	
					//echo $orders_array['totalRecords'];die();
					//print_r($orders_array);die();
					//echo $orders_array['totalRecords'];
					if($orders_array['totalRecords'] > 0) {
						//echo $device_token;
						if($orders_array['totalRecords'] > 1) {
							$message	=	$orders_array['totalRecords'].' new orders received on '.$StoreName.'.';
						} else {
							$message	=	$orders_array['totalRecords'].' new order received on '.$StoreName.'.';
						}
						sendApnsAlert($device_array['id'], $message);
						#sendApnsAlert('51370ecb8c3f78a3ee14fa77617deb45ea2584841b9b6c99dec35bc0d8fb7402', $message);die();
					}
				}
			}
		}
	}
	
	function sendApnsAlert($device_token, $message) {
		
		#!/usr/bin/env php
		 //$device_token = '1f52588dad33b4a62a027ff490121dc9dbbeb51799b8804d3dea7eeba0412951'; // masked for security reason
		  // Passphrase for the private key (ck.pem file)
		  // $pass = '';
		
		  // Get the parameters from http get or from command line
		  //$message = $_GET['message'] or $message = $argv[1] or $message = 'Message received from javacom';
//		  $badge = (int)$_GET['badge'] or $badge = (int)$argv[2];
//		  $sound = $_GET['sound'] or $sound = $argv[3];
		  
		  $ecc_base_path = Mage::getBaseDir('base').'/ecc';
		  
		  $message = isset($message) ? $message : 'Message received from webgility ecc mobile apns test';
		  $badge = (int)1;
		  $sound = 'received5.caf';
		  
		  
		
		  // Construct the notification payload
		  $body = array();
		  $body['aps'] = array('alert' => $message);
		  if ($badge)
		  $body['aps']['badge'] = $badge;
		  if ($sound)
		  $body['aps']['sound'] = $sound;
		
		
		  // End of Configurable Items 
		
		  $ctx = stream_context_create();
		  stream_context_set_option($ctx, 'ssl', 'local_cert', $ecc_base_path.'/apns.pem');
		  //stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns.pem');
		  // assume the private key passphase was removed.
		  // stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
		
		  #$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
		  $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
		  // for production change the server to ssl://gateway.push.apple.com:2195
		  if (!$fp) {
			  print "Failed to connect $err $errstr\n";
			  return;
		  } else {
		  		print "Connection OK\n";
		  }
		
		  $payload = json_encode($body);
		  $msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $device_token)) . pack("n",strlen($payload)) . $payload;
		  #print "Device Token: ".$device_token.", sending message :" . $payload . "\n";
		 echo 'Push notifications enabled successfully. You are ready to send notifications.';
		  fwrite($fp, $msg);
		  fclose($fp);
		
	}	
	
	
	function modifyApnsConfigFile($action_entity_id, $action_type, $devicetokenid) {
		
		##############		Code to create and modify apns-config.txt	###################
		$devicetokenid = str_replace(" ","", $devicetokenid);
		$devicetokenid = substr($devicetokenid,1,strlen($devicetokenid)-2);
		
		$device_token_array	=	array();
		$alert_flag_array	=	array();
		$ecc_base_path = Mage::getBaseDir('base').'/ecc';
		
		$apns_config_file	=	$ecc_base_path.'/apns-config.txt';
		//$apns_config_file	=	$_SERVER['DOCUMENT_ROOT'].'/magento_1_6_1_0/ecc/apns-config.txt';
		$config_str			=	'';
		$config_array		=	array();
		$config_array_count	=	0;
		if(file_exists($apns_config_file)) {
		
			//Code to read DeviceToken
			//echo $_SERVER['DOCUMENT_ROOT'].'/magento_1_6_1_0/ecc/apns-config.txt';
			$file = fopen($apns_config_file, 'r') or exit('Unable to open file for read!');
			//$file = fopen('apns-config.txt', 'r') or exit('Unable to open file!');
			
			//Output a line of the file until the end is reached
			while(!feof($file)) {
			  
			  $line	=	fgets($file);
			  $line_array	=	explode('::', $line); //print_r($line_array);
			  $config_str	=	$line_array[0];
			  $config_str	=	json_decode($config_str);
			  #print_r($config_str);die('reached');
			  /*foreach($config_str as $k=>$v) {
				$config_array[$k] = object_2_array($v);
				
				if($config_array['devices'][0]['id'] == $devicetokenid) {
					$config_array['devices'][0]['newOrder']	=	$action_entity_id;
				}
				
				$config_array_count++;
			  }*/
			  
			  foreach($config_str as $k=>$v) {
				$config_array[$k] = object_2_array($v);
				#print_r($config_array_new);
				foreach($config_array['devices'] as $k1 => $v1) {
					#print_r($v1);
					if($devicetokenid == $v1['id']) {
						#$config_array[$k][] = $v1;
						$config_array['devices'][$config_array_count]['newOrder']	=	$action_entity_id;
					}
					$config_array_count++;
				}
			  }
			  
			}
			fclose($file);
			
			
		}
		/*$last_order_id	=	'';
		$last_customer_id	=	'';
		if($action_type == 'get_order') {
			$last_order_id	=	$action_entity_id;
		} else if($action_type == 'get_customer') {
			$last_customer_id	=	$action_entity_id;
		}
		
		$config_array_count	=	count($config_array['devices']);
		$config_array['devices'][$config_array_count] = array('id' => $devicetokenid, 'newOrder' =>$last_order_id, 'newUser' =>$last_customer_id);*/
		#print_r($config_array['devices']);die();

		$fh = fopen($apns_config_file, 'w') or die("can't open file");

		$stringData = json_encode($config_array);
		//die($stringData);
		fwrite($fh, $stringData);
		fclose($fh);
		
		$apns_config_file	=	$ecc_base_path.'/apns-config.txt';
		chmod($apns_config_file, 0777);
		########################################################################
	}
	
	
	function deleteStore($username,$password,$devicetokenid) {
		#@mail('ziyar@webgility.com','Device token id from deleteStore',$devicetokenid);
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		
		if($status!="0"){ //login name invalid
		
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
			
		}else{	
		
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			
			##############		Code to create and modify apns-config.txt	###################
			$devicetokenid = str_replace(" ","", $devicetokenid);
			$devicetokenid = substr($devicetokenid,1,strlen($devicetokenid)-2);
			
			$device_token_array	=	array();
			$alert_flag_array	=	array();
			$ecc_base_path = Mage::getBaseDir('base').'/ecc';
			$apns_config_file	=	$ecc_base_path.'/apns-config.txt';
			//$apns_config_file	=	$_SERVER['DOCUMENT_ROOT'].'/magento_1_6_1_0/ecc/apns-config.txt';
			$config_str			=	'';
			$config_array		=	array();
			$config_array_new	=	array();
			$config_array_count	=	0;
			if(file_exists($apns_config_file)) {
			
				//Code to read DeviceToken
				//echo $_SERVER['DOCUMENT_ROOT'].'/magento_1_6_1_0/ecc/apns-config.txt';
				$file = fopen($apns_config_file, 'r') or exit('Unable to open file for read!');
				//$file = fopen('apns-config.txt', 'r') or exit('Unable to open file!');
				
				//Output a line of the file until the end is reached
				while(!feof($file)) {
				  
				  $line	=	fgets($file);
				  $line_array	=	explode('::', $line); //print_r($line_array);
				  $config_str	=	$line_array[0];
				  $config_str	=	json_decode($config_str);
				  #print_r($config_str);echo '<br/>';
				  foreach($config_str as $k=>$v) {
					$config_array_new[$k] = object_2_array($v);
					#print_r($config_array_new);
					foreach($config_array_new['devices'] as $k1 => $v1) {
						#print_r($v1);
						if($devicetokenid != $v1['id']) {
							$config_array[$k][] = $v1;
						}
						$config_array_count++;
					}
				  }
				}
				fclose($file);
			}
			#print_r($config_array);die('reached');
			if(count($config_array) > 0) {
	
				$fh = fopen($apns_config_file, 'w') or die("can't open file");
		
				$stringData = json_encode($config_array);
				//die($stringData);
				fwrite($fh, $stringData);
				fclose($fh);
				$apns_config_file	=	$ecc_base_path.'/apns-config.txt';
				chmod($apns_config_file, 0777);
			}
			########################################################################
			
		}
		
		$response = json_encode($responseArray);
		return responce($response);
	}
	
	#apns alert code end here
	
	function getCategory($username,$password,$storeid=1)
	{
		$storeid = getDefaultStore($storeid);				
		$responseArray = array();
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$categories = $objNew->_getcategory($storeid);		
			if($categories)
			{
				
				for($i=0;$i <count ($categories);$i++) 
				{		
					if($categories[$i]['category_id']=='' || $categories[$i]['name']=='')
					{
					}else{
						$responseArray['Categories'][$i]['CategoryID'] = $categories[$i]['category_id'];
						$responseArray['Categories'][$i]['CategoryName'] = htmlentities($categories[$i]['name'], ENT_QUOTES);
						$responseArray['Categories'][$i]['ParentID'] = htmlentities($categories[$i]['parent_id'], ENT_QUOTES);
					}
				}
			}	
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

	function getCustomers($username,$password,$datefrom,$customerid,$limit,$storeid=1)
	{
		$responseArray = array();
		$datefrom =$datefrom ?$datefrom:0;
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		$storeId=getDefaultStore($storeid);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$customersObj = $objNew->getCustomer($datefrom,$customerid,$storeId,$limit);
			$customersArray = $customersObj->toarray();
			if(count($customersArray)<0)
			{
				$no_customer = true;
			}
			$responseArray['StatusCode'] = $no_customer?"9999":"0";
			$responseArray['StatusMessage'] = $no_customer?"No Customer returned":"Total Customer:".count($customersArray);
			$responseArray['TotalRecordFound'] = $customersObj->getSize()?$customersObj->getSize():'0';
			$responseArray['TotalRecordSent'] = count($customersArray)?count($customersArray):'0';
			$i = 0;
			foreach($customersArray as $customer)
			{
				$responseArray['Customers'][$i]['CustomerId'] = $customer["entity_id"];
				$responseArray['Customers'][$i]['FirstName'] = $customer["firstname"];
				$responseArray['Customers'][$i]['MiddleName'] = $customer["middlename"];
				$responseArray['Customers'][$i]['LastName'] = $customer["lastname"];
				$responseArray['Customers'][$i]['CustomerGroup'] = $customer["group_id"];
				$responseArray['Customers'][$i]['email'] = $customer["email"];
				$responseArray['Customers'][$i]['Address1'] = $customer["billing_street"];
				$responseArray['Customers'][$i]['Address2'] = '';
				$responseArray['Customers'][$i]['City'] = $customer["billing_city"];
				$responseArray['Customers'][$i]['State'] = $customer["billing_region"];
				$responseArray['Customers'][$i]['Zip'] = $customer["billing_postcode"];
				$responseArray['Customers'][$i]['Country'] = $customer["billing_country_id"];
				$responseArray['Customers'][$i]['Phone'] = $customer["billing_telephone"];
				$responseArray['Customers'][$i]['CreatedAt'] = $customer["created_at"];
				$responseArray['Customers'][$i]['UpdatedAt'] = $customer["updated_at"];
				$isFilter = $storeId || $customer['group_id'];
				$collection = Mage::getResourceModel('reports/order_collection')->calculateSales($isFilter);
					$collection->addAttributeToFilter('entity_id', $customer["entity_id"]);
				if ($storeId) {
					$collection->addAttributeToFilter('store_id', $storeId);
				}else if ($customer['group_id']){
					$storeIds = Mage::app()->getGroup($storeId)->getStoreIds();
					$collection->addAttributeToFilter('store_id', array('in' => $storeIds));
				}
				$collection->load();
				$collectionArray = $collection->toArray();
				$sales = array_pop($collectionArray);
				$responseArray['Customers'][$i]['SalesStatistics']['LifeTimeSale'] = $sales["lifetime"];
				$responseArray['Customers'][$i]['SalesStatistics']['AverageSale'] = $sales["average"];
				$i++;
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}
	
	function getStoreCustomerByIdForEcc($username,$password,$datefrom,$customerid,$limit,$storeid=1,$others) {
		$responseArray = array();
		$datefrom =$datefrom ?$datefrom:0;
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		$storeId=getDefaultStore($storeid);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$customersObj = $objNew->getCustomerById($datefrom,$customerid,$storeId,$limit);
			$customersArray = $customersObj->toarray();
			if(count($customersArray)<0)
			{
				$no_customer = true;
			}
			$responseArray['StatusCode'] = $no_customer?"9999":"0";
			$responseArray['StatusMessage'] = $no_customer?"No Customer returned":"Total Customer:".count($customersArray);
			$responseArray['TotalRecordFound'] = $customersObj->getSize()?$customersObj->getSize():'0';
			$responseArray['TotalRecordSent'] = count($customersArray)?count($customersArray):'0';
			$i = 0;
			foreach($customersArray as $customer)
			{
				$responseArray['Customers'][$i]['CustomerId'] = $customer["entity_id"];
				$responseArray['Customers'][$i]['FirstName'] = $customer["firstname"];
				$responseArray['Customers'][$i]['MiddleName'] = $customer["middlename"];
				$responseArray['Customers'][$i]['LastName'] = $customer["lastname"];
				$responseArray['Customers'][$i]['CustomerGroup'] = $customer["group_id"];
				$responseArray['Customers'][$i]['email'] = $customer["email"];
				$responseArray['Customers'][$i]['Address1'] = $customer["billing_street"];
				$responseArray['Customers'][$i]['Address2'] = '';
				$responseArray['Customers'][$i]['City'] = $customer["billing_city"];
				$responseArray['Customers'][$i]['State'] = $customer["billing_region"];
				$responseArray['Customers'][$i]['Zip'] = $customer["billing_postcode"];
				$responseArray['Customers'][$i]['Country'] = $customer["billing_country_id"];
				$responseArray['Customers'][$i]['Phone'] = $customer["billing_telephone"];
				$responseArray['Customers'][$i]['CreatedAt'] = $customer["created_at"];
				$responseArray['Customers'][$i]['UpdatedAt'] = $customer["updated_at"];
				$isFilter = $storeId || $customer['group_id'];
				$collection = Mage::getResourceModel('reports/order_collection')->calculateSales($isFilter);
					$collection->addAttributeToFilter('entity_id', $customer["entity_id"]);
				if ($storeId) {
					$collection->addAttributeToFilter('store_id', $storeId);
				}else if ($customer['group_id']){
					$storeIds = Mage::app()->getGroup($storeId)->getStoreIds();
					$collection->addAttributeToFilter('store_id', array('in' => $storeIds));
				}
				$collection->load();
				$collectionArray = $collection->toArray();
				$sales = array_pop($collectionArray);
				$responseArray['Customers'][$i]['SalesStatistics']['LifeTimeSale'] = $sales["lifetime"];
				$responseArray['Customers'][$i]['SalesStatistics']['AverageSale'] = $sales["average"];
				$i++;
			}
		}
		$response = json_encode($responseArray);
		return responce($response);
	}

	function getItemsQuantity($username,$password,$storeid=1)
	{
		$responseArray = array();
		$storeId=getDefaultStore($storeid);	
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$status =  $objNew->CheckUser($username,$password);
		if($status!="0"){ //login name invalid
			if($status=="1"){
				$responseArray['StatusCode'] = '1';
				$responseArray['StatusMessage'] = 'Invalid login. Authorization failed';
			}
			if($status=="2"){ //password invalid
				$responseArray['StatusCode'] = '2';
				$responseArray['StatusMessage'] = 'Invalid password. Authorization failed';
			}
		}else{
			$product = Mage::getModel('catalog/product');
			$stockItemObj = $product->getCollection()
							->addAttributeToSelect('name', true)
							->addAttributeToSelect('sku', true)
							->addAttributeToSelect('price', true)
							->addAttributeToSelect('cost', true)
							->addAttributeToSelect('updated_at', true)			
							->joinTable('cataloginventory/stock_item', 'product_id=entity_id', array('qty'=>'qty', 'notify_stock_qty'=>'notify_stock_qty', 'use_config' => 'use_config_notify_stock_qty','low_stock_date' => 'low_stock_date'))->load();
			$stockItem = $stockItemObj->toArray();		
			$responseArray['StatusCode'] = '0';
			$responseArray['StatusMessage'] = 'All Ok';
			$responseArray['TotalRecordFound'] = $stockItemObj->getSize();
			$itemI = 0;
			foreach($stockItem as $item)
			{
				$responseArray['TotalRecordSent']['Items'][$itemI]['ItemID'] = $item['entity_id'];
				$responseArray['TotalRecordSent']['Items'][$itemI]['Quantity'] = $item['qty'];
				$responseArray['TotalRecordSent']['Items'][$itemI]['UnitPrice'] = $item['price'];
				$responseArray['TotalRecordSent']['Items'][$itemI]['ListPrice'] = $item['cost'];
				$responseArray['TotalRecordSent']['Items'][$itemI]['UpdatedAt'] = $item['updated_at'];
				$itemI++;
			}	
		}
		$response = json_encode($responseArray);
		return responce($response);
	}
	
	function parseSpecCharsA($arr)
	{
		foreach($arr as $k=>$v)
		{
			if(is_array($k))
			{
				foreach($k as $l=>$m)
				{
					$arr[$l] = addslashes(htmlentities($m, ENT_QUOTES));
				}
			}else{
				$arr[$k] = addslashes(htmlentities($v, ENT_QUOTES));
			}
		}
		return $arr;
	}

	function checkVersion()
	{
		global $config;
		$str="";
		if($config['version'])
		{
			return $config['version'];
		}
		else
		{
			return "0";
		}
	}
	#version 2.2 invoice generayion and update shipping status
	/**
     * Save invoice
     * We can save only new invoice. Existing invoices are not editable
     */
    function saveInvoice($data,$orderId)
    {
		try
		{
			if ($invoice = _initInvoice($orderId,$data,false))
			{
				if (!empty($data['capture_case']))
				{
                    $invoice->setRequestedCaptureCase($data['capture_case']);
                }
                if (!empty($data['comment_text'])) {
                    $invoice->addComment($data['comment_text'], isset($data['comment_customer_notify']));
                }
                $invoice->register();
                if (!empty($data['send_email'])) {
                    $invoice->setEmailSent(true);
                }
                $invoice->getOrder()->setIsInProcess(true);
                $transactionSave = Mage::getModel('core/resource_transaction')
										->addObject($invoice)
										->addObject($invoice->getOrder());
                $shipment = false;
                if (!empty($data['do_shipment']))
				{
                    $shipment = $this->_prepareShipment($invoice);
                    if ($shipment)
					{
                        $shipment->setEmailSent($invoice->getEmailSent());
                        $transactionSave->addObject($shipment);
                    }
                }
                $transactionSave->save();
                /**
                 * Sending emails
                 */
                $comment = '';
                if (isset($data['comment_customer_notify']))
				{
                    $comment = $data['comment_text'];
                }
                $invoice->sendEmail(!empty($data['send_email']), $comment);
                if ($shipment)
				{
                    $shipment->sendEmail(!empty($data['send_email']));
                }     
			}
        }catch (Mage_Core_Exception $e){
            $this->_getSession()->addError($e->getMessage());
        }catch (Exception $e){
            $this->_getSession()->addError($this->__('Can not save invoice'));
        }
    }
    /**
     * Initialize invoice model instance
     *
     * @return Mage_Sales_Model_Order_Invoice
     */
    function _initInvoice($orderId,$data,$update = false)
    {
		$invoice = false;
        $order = Mage::getModel('sales/order')->load($orderId);
		/**
		 * Check order existing
		 */

		/**
		 * Check invoice create availability
		 */
		if (!$order->canInvoice())
		{
        	return false;
		}
	    $convertor  = Mage::getModel('sales/convert_order');
		$invoice    = $convertor->toInvoice($order);
		$savedQtys = _getItemQtys($data);
        foreach ($order->getAllItems() as $orderItem)
		{	              
			$item = $convertor->itemToInvoiceItem($orderItem);
		    if (isset($savedQtys[$orderItem->getId()]))
			{
            	$qty = $savedQtys[$orderItem->getId()];
			}else{
				if ($orderItem->isDummy())
				{
                	$qty = 1;
				}else{
					$qty = $orderItem->getQtyToInvoice();
				}
			}
            $item->setQty($qty);				
            $invoice->addItem($item);
		}
		$invoice->collectTotals();
        Mage::register('current_invoice', $invoice);
		return $invoice;
    }
	
	function _getItemQtys($data)
    {
     	if (isset($data['items']))
		{
        	$qtys = $data['items'];
        }else{
            $qtys = array();
        }
        return $qtys;
    }
	
	    /**
     * Initialize order model instance
     *
     * @return Mage_Sales_Model_Order || false
     */
    function _initOrder($id)
    {
        $order = Mage::getModel('sales/order')->load($id);
		if (!$order->getId())
		{
		    return "Error : Order not present";            
        }
        Mage::register('sales_order', $order);
        Mage::register('current_order', $order);
        return $order;
    }	
    /**
     * Cancel order
     */
    function cancelAction($id)
    { 
        if ($order = _initOrder($id))
		{
            try
			{			
				if($order->canCancel())
				{
               		$order->cancel()->save();
			   		return true;
			 	}else{
			 		return "Error: Order cannot be Cancelled. Please cancel it manually";
			 	}
            }catch(Mage_Core_Exception $e){
                return "Error: Order cannot be Cancelled. Please cancel it manually";
            }catch(Exception $e){
                return "Error: Order cannot be Cancelled. Please cancel it manually";
            }
        } 
    }
    /**
     * Hold order
     */
    function holdAction($id)
    {
        if ($order = _initOrder($id))
		{
            try
			{
				if($order->canHold())
				{
               		$order->hold()->save();					
                	return true;
				}else{
					return "Error: Order cannot be Holded. Please review it manually";
				}	
            }catch(Mage_Core_Exception $e){
                return "Error: Order cannot be Holded. Please review it manually";
            }catch (Exception $e){
                return "Error: Order cannot be Holded. Please review it manually";
            }
        }
    }
    /**
     * Unhold order
     */
    function unholdAction($id)
    {
        if ($order = _initOrder($id))
		{
            try
			{
				if($order->canUnhold())
				{
               		$order->unhold()->save();
                	return true;
				}else{
					return "Error: Order cannot be Un Holded. Please review it manually";
				}	
            }catch (Mage_Core_Exception $e){
                return "Error: Order cannot be Un Holded. Please review it manually";
            }catch (Exception $e){
                return "Error: Order cannot be Un Holded. Please review it manually";
            }            
        }
    }

	function orderStatustofetch($order_status_list)
	{
		$objNew = new Webgility_Shop1_Model_Runmobile();
		$orderStatus = $objNew->_getorderstatuses($storeId);		  	
		$order_status = array();	
		foreach ($orderStatus as $sk=>$sv)
		{
			if(in_array($sv,$order_status_list))
			{
				$order_status[] =$sk;
			}
		}
		return $order_status;
	}

	function responce($str)
	{
		$fp = fopen('ecctest.txt', 'a+');
		fwrite($fp, "\n Response Mobile : ".$str);
		fclose($fp);
		return $str;		
		$cipher_alg = MCRYPT_RIJNDAEL_128;
		$key = "d994e5503a58e025";
		$hexiv="d994e5503a58e02525a8fc5eef45223e";
		$enc_string = mcrypt_encrypt($cipher_alg, $key,$str , MCRYPT_MODE_CBC, trim(hexToString(trim($hexiv))));
		return gzcompress(stringToHex($enc_string)); 
	}

/**
 * Converts Strings to HEX representation
 * @param string Input string
 * @return string Hex representation
 */
	function stringToHex($str)
	{
		$hex="";
		$zeros = "";
		$len = 2 * strlen($str);
		for ($i = 0; $i < strlen($str); $i++)
		{
			$val = dechex(ord($str{$i}));
			if( strlen($val)< 2 ) $val="0".$val;
			$hex.=$val;
		}
		for ($i = 0; $i < $len - strlen($hex); $i++)
		{
			$zeros .= '0';
		}
		return $hex.$zeros;
	}
/**
 * Converts HEX values into strings
 * @param string HEX value in string repreentation
 * @return string
 */
	 function hexToString($hex) 
	 {
		$str="";
		for($i=0; $i<strlen($hex); $i=$i+2 ) 
		{
			$temp = hexdec(substr($hex, $i, 2));
			if (!$temp) continue;
			$str .= chr($temp);
		}
		return $str;
	}
?>