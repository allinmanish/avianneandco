<?php

class Mfmc_mfmcallforprice_Block_Rewrite_FrontWishlistShareEmailItems extends Mage_Wishlist_Block_Share_Email_Items
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mfmcallforprice/wishlist/email/items.phtml');
    }
}