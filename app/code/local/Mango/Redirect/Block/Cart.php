<?php

class Mango_Redirect_Block_Cart extends  Mage_Checkout_Block_Cart {

    public function __construct(){
        parent::__construct();

    }

    public function getContinueShoppingUrl()
    {
        $url = parent::getContinueShoppingUrl();
        $url = str_replace("&ajax=1", "", $url);
        $url = str_replace("ajax=1&", "", $url);
       return $url;
    }

}