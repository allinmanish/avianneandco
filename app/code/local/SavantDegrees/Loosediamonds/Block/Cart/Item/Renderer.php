   
      <?php
 
       
 
      class SavantDegrees_Loosediamonds_Block_Cart_Item_Renderer extends Mage_Checkout_Block_Cart_Item_Renderer
 
      {
 
          public function getLoadedProduct()
 
          {
 
              return $this->getProduct()->load($this->getProduct()->getId());
 
          }
 
          public function getSetupFee()
 
          {
 
              return $this->getLoadedProduct()->getSetupFee();
 
          }
 
    }