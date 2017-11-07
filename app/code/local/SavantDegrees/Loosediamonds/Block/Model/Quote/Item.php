   
 <?php
          
	 class SavantDegrees_Loosediamonds_Model_Quote_Item extends Mage_Sales_Model_Quote_Item
	 {
		 public function calcRowTotal()
		  {
			   parent::calcRowTotal();
			   $product = $this->getProduct();
	
			    $product->load($product->getId());
	
	   
				// This is where I add the Setup Fee, more than one fee can be added
	
			  // here if necessary
	
			     $baseTotal = $this->getBaseRowTotal() + 10;
	
	   
	
			     $total = $this->getStore()->convertPrice($baseTotal);
	
			     $this->setRowTotal($this->getStore()->roundPrice($total));
				 $this->setBaseRowTotal($this->getStore()->roundPrice($baseTotal));
				return $this;
			}
	}