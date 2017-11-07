<?php          
	 class SavantDegrees_Loosediamonds_Model_Quote_Item extends Mage_Sales_Model_Quote_Item
	 {
		 public function calcRowTotal()
		  {
			   parent::calcRowTotal();
			   $product = $this->getProduct();
			   $item = $this->getProduct();
			   
			    $product->load($product->getId());
				$options=$product->getOptions();
				
				//echo "hello world";
				
				$fee=0;
				$loose_id=array(6344,6343,6342,6341,6340,6339,6338,6337,6336,6335);
				
				if (in_array($product->getId(),$loose_id)) {
				$option_id=0;
		
				foreach ($options as $op)
				{
				  $option_id=$op->getOptionId();
				  break;
				}
					$val=$this->getOptionByCode('option_' . $option_id);
					$lot=$val->getValue();
					
					if (isset($lot)) {
					$resource=Mage::getSingleton('core/resource');
                    $read=$resource->getConnection('core_read');           
                    $sql="SELECT *
FROM `dev_products` WHERE lot='$lot'";
                       
                    $fetch=$read->fetchRow($sql);
					
					$fee=round($fetch['price'])*$this->getQty();
					}
			   }  							
				
				if ($product->getId()=="6316")
				{
				  $fee=$this->getOwnringPrice($product,16573);
				  $fee=$fee*$this->getQty();
				}
				
				elseif ($product->getId()=="6386") {
				  $fee=$this->getOwnringPrice($product,16627);
				  $fee=$fee*$this->getQty();
				}
				elseif ($product->getId()=="6396") {
				  $fee=$this->getOwnringPrice($product,16631);
				  $fee=$fee*$this->getQty();
				}
				
				
				
				// This is where I add the Setup Fee, more than one fee can be added
	
			  // here if necessary
	
			     $baseTotal = $this->getBaseRowTotal() + $fee;
	
	   
	
			     $total = $this->getStore()->convertPrice($baseTotal);
	
			     $this->setRowTotal($this->getStore()->roundPrice($total));
				 $this->setBaseRowTotal($this->getStore()->roundPrice($baseTotal));
				return $this;
		}
		function getProductOptions(&$item)
		{
		  $options = array();
		  if ($optionIds = $item->getOptionByCode('option_ids')) {
			  $options = array();
			  foreach (explode(',', $optionIds->getValue()) as $optionId) {
				  if ($option = $item->getProduct()->getOptionById($optionId)) {

					  $quoteItemOption = $item->getOptionByCode('option_' . $option->getId());

					  $group = $option->groupFactory($option->getType())
						  ->setOption($option)
						  ->setQuoteItemOption($quoteItemOption);

					  $options[] = array(
						  'label' => $option->getTitle(),
						  'value' => $group->getFormattedOptionValue($quoteItemOption->getValue()),
						  'print_value' => $group->getPrintableOptionValue($quoteItemOption->getValue()),
						  'option_id' => $option->getId(),
						  'option_type' => $option->getType(),
						  'custom_view' => $group->isCustomizedView()
					  );
				  }
			  }
		  }
		  if ($addOptions = $item->getOptionByCode('additional_options')) {
			  $options = array_merge($options, unserialize($addOptions->getValue()));
		  }
			return $options;
		} 
		
		
		public function getOwnringPrice($product,$option)
	    {
		 	$options=$product->getOptions();	
			$fee=0;	
			$option_id=$option;
			$val=$this->getOptionByCode('option_' . $option_id);
			return $lot=$val->getValue();
												 
	    }
	}