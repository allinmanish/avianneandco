<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Mobile
 * @copyright  Copyright (c) 2010-2011 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */
/**
 * aheadWorks Mobile Data Helper
 */
class AW_Mobile_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * iPhone Response
     */
    const IPHONE_RESPONSE = 'iPhone';

    /**
     * Android Response
     */
    const ANDROID_RESPONSE = 'Android';

    /**
     * BlackBerry Response
     */
    const BLACKBERRY_RESPONSE = 'BlackBerry';

    /**
     * iPhone Response
     */
    const WMOBILE_RESPONSE = 'IEMobile';

    /**
     * iPad Response
     */
    const IPAD_RESPONSE = 'iPad';

    /**
     * Frame Navigation Template path
     */
    const FRAME_NAVIGATION_TEMPLATE = 'catalog/navigation/top.phtml';

    /**
     * Path to Custom Design Config Path
     */
    const XML_PATH_MOBILE_CUSTOM_THEME = 'awmobile/design/custom_design';

    /**
     * Path to Custom Design Config Path
     */
    const XML_PATH_MOBILE_FOOTER_LINKS_BLOCK = 'awmobile/design/footer_links_block';

    /**
     * Path to Copyright Config Path
     */
    const XML_PATH_MOBILE_COPYRIGHT = 'awmobile/design/copyright';

    /**
     * Default package
     */
    const DEFAULT_PACKAGE = 'aw_mobile';

    /**
     * Default theme
     */
    const DEFAULT_THEME = 'iphone';

    /**
     * Target platform
     * @var string
     */
    protected $_target = null;

    /**
     * Is checkout messages prepared
     * @var bool
     */
    protected $_checkoutMessagesPrepared = false;

    /**
     * Retrives is iPhone Flag
     * @return boolean
     */
    public function iPhone()
    {
        try {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::IPHONE_RESPONSE) !== false);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Retrives is Android Flag
     * @return boolean
     */
    public function Android()
    {
        try {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::ANDROID_RESPONSE) !== false);
        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * Retrives is Windows Mobile IE Flag
     * @return boolean
     */
    public function winMobile()
    {
        try {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::WMOBILE_RESPONSE) !== false);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Find version in the http_user_agent
     *
     * @param string $pattern
     * @param string $text
     * @return string
     */
    protected function _findVersion($pattern, $text)
    {
        $regExp = "/({$pattern} (?:(\d+)\.)?(?:(\d+)\.)?(\*|\d+))/";
        $toDelete = "{$pattern} ";

        $matches = array();
        preg_match($regExp, $text, $matches);
        if (count($matches)) {
            return str_replace($toDelete, "", $matches[0]);
        }
    }

    public function getAndroidVersion()
    {
        try {
            return $this->_findVersion(strtolower(self::ANDROID_RESPONSE), strtolower($_SERVER['HTTP_USER_AGENT']));
        } catch (Exception $e) {
            # Do Nothing
        }
    }

    /**
     * Retrives is BlackBerry Flag
     * @return boolean
     */
    public function BlackBerry()
    {
        try {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::BLACKBERRY_RESPONSE) !== false);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Retreive true if iPad device has been detected
     * @return bool
     */
    public function is_iPad()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return (strpos($_SERVER['HTTP_USER_AGENT'], self::IPAD_RESPONSE) !== false);
        }
        return false;
    }

    /**
     * Retrives customer session
     * @return Mage_Customer_Model_Session
     */
    protected function _customerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Retrives Show Desktop flag value
     * @return boolean
     */
    public function getShowDesktop()
    {
        return $this->_customerSession()->getShowDesktop();
    }

    /**
     * Set up State Changed mutex
     * @return AW_Mobile_Helper_Data
     */
    public function setStateChanged()
    {
        $this->_customerSession()->setStateChanged(true);
    }

    /**
     * Retrivces State Changed mutex
     * @return boolean
     */
    public function isStateChanged()
    {
        $state = $this->_customerSession()->getStateChanged();
        $this->_customerSession()->setStateChanged(false);
        return $state;
    }

    /**
     * Retrives Page Cache enabled flag
     * @return boolean
     */
    public function isPageCache()
    {
        return Mage::app()->useCache('full_page');
    }

    /**
     * Set up Forced flag
     * @return AW_Mobile_Helper_Data
     */
    public function setForced()
    {
        $this->_customerSession()->setForced(true);
        return $this;
    }

    /**
     * Retrivces Forced flag
     * @return boolean
     */
    public function isForced()
    {
        return $this->_customerSession()->getForced();
    }

    /**
     * Set up Show Desktop flag
     * @param boolean $value
     */
    public function setShowDesktop($value)
    {
        $this->setStateChanged();
        $this->setForced();
        $this->_customerSession()->setShowDesktop($value);
    }

    /**
     * Retrive config value of Mobile Detect option
     * @return boolen
     */
    public function confMobileDetect()
    {
        return !!Mage::getStoreConfig('awmobile/behaviour/mobile_detect');
    }

    /**
     * Compare param $version with magento version
     * @param string $version
     * @return boolean
     */
    public function checkVersion($version)
    {
        return version_compare(Mage::getVersion(), $version, '>=');
    }

    /**
     * Retrive is Magento Enterprise Edition Flag
     * @return boolean
     */
    public function isEE()
    {
        return $this->checkVersion('1.7.0.0');
    }

    /**
     * Retrives flag that old POST format required for chechout street address
     * @return boolean
     */
    public function isOldStreetFormat()
    {
        if (!$this->isEE()) {
            return !$this->checkVersion('1.4.2.0');
        } else {
            return !$this->checkVersion('1.9.0.0');
        }
    }


    /**
     * Escape html entities
     *
     * @param   mixed $data
     * @param   array $allowedTags
     * @return  mixed
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        if (is_array($data)) {
            $result = array();
            foreach ($data as $item) {
                $result[] = $this->escapeHtml($item);
            }
        } else {
            // process single item
            if (strlen($data)) {
                if (is_array($allowedTags) and !empty($allowedTags)) {
                    $allowed = implode('|', $allowedTags);
                    $result = preg_replace('/<([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)>/si', '##$1$2$3##', $data);
                    $result = htmlspecialchars($result);
                    $result = preg_replace('/##([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)##/si', '<$1$2$3>', $result);
                } else {
                    $result = htmlspecialchars($data);
                }
            } else {
                $result = $data;
            }
        }
        return $result;
    }

    /**
     * Wrapper for standart strip_tags() function with extra functionality for html entities
     *
     * @param string $data
     * @param string $allowableTags
     * @param bool $escape
     * @return string
     */
    public function stripTags($data, $allowableTags = null, $escape = false)
    {
        if (method_exists(Mage::helper('core'), 'stripTags')) {
            return parent::stripTags($data, $allowableTags = null, $escape = false);
        }
        $result = strip_tags($data, $allowableTags);
        return $escape ? $this->escapeHtml($result, $allowableTags) : $result;
    }

    /**
     * Retrives target platform
     * @return string
     */
    public function getTargetPlatform()
    {
        if ($this->getDisabledOutput()) {
            return AW_Mobile_Model_Observer::TARGET_DESKTOP;
        }
        if (!$this->_target) {
            $target = AW_Mobile_Model_Observer::TARGET_DESKTOP;
            if ($this->isForced()) {
                $target = $this->getShowDesktop() ? AW_Mobile_Model_Observer::TARGET_DESKTOP : AW_Mobile_Model_Observer::TARGET_MOBILE;
            } elseif ($this->confMobileDetect() && (
                /* Select a platform */
                $this->iPhone() ||
                    $this->Android() ||
                    $this->BlackBerry() ||
                    $this->winMobile()
            )
            ) {
                $target = AW_Mobile_Model_Observer::TARGET_MOBILE;
            }
            $this->_target = $target;
        }
        return $this->_target;
    }

    /**
     * Retrives disabled output of extension flag
     * @return boolean
     */
    public function getDisabledOutput()
    {
        return !!Mage::getStoreConfig('advanced/modules_disable_output/AW_Mobile');
    }

    /**
     * Retrives additional navigation tools
     *
     * @return boolean
     */
    public function wantNavigationTools()
    {
        if ($this->Android() && version_compare($this->getAndroidVersion(), '1.6', '<=')) {
            return true;
        }
        return (!$this->iPhone() && !$this->Android());
    }

    public function isOldAndroid()
    {
        return ($this->Android() && version_compare($this->getAndroidVersion(), '1.6', '<='));
    }

    protected function _getCustomDesign($key)
    {
        $package = null;
        $theme = null;
        if ($customTheme = Mage::getStoreConfig(self::XML_PATH_MOBILE_CUSTOM_THEME)) {
            list($package, $theme) = explode('/', $customTheme);
            return $$key;
        }
        return null;
    }

    /**
     * Retrives default package
     * @return string
     */
    public function getMobilePackage()
    {
        if ($this->_getCustomDesign('package')) {
            return $this->_getCustomDesign('package');
        }
        return self::DEFAULT_PACKAGE;
    }

    /**
     * Retrives default theme
     * @return string
     */
    public function getMobileTheme()
    {
        if ($this->_getCustomDesign('theme')) {
            return $this->_getCustomDesign('theme');
        }
        return self::DEFAULT_THEME;
    }

    public function getCustomFooterLinksHtml()
    {
        if ($blockId = Mage::getStoreConfig(self::XML_PATH_MOBILE_FOOTER_LINKS_BLOCK)) {
            $block = Mage::app()->getLayout()->createBlock('cms/block');
            if ($block) {
                return $block->setBlockId($blockId)->toHtml();
            }
        }
        return null;
    }

    public function getCopyright()
    {
        return Mage::getStoreConfig(self::XML_PATH_MOBILE_COPYRIGHT);
    }

    public function prepareCartMessages()
    {
        if ($this->_checkoutMessagesPrepared) {
            return;
        } else {
            $this->_checkoutMessagesPrepared = true;
        }
        $cart = Mage::getSingleton('checkout/cart');
        if ($cart->getQuote()->getItemsCount()) {
            $cart->init();
            $cart->save();
            if (!$cart->getQuote()->validateMinimumAmount()) {
                $warning = Mage::getStoreConfig('sales/minimum_order/description');
                $cart->getCheckoutSession()->addNotice($warning);
            }
        }
        $messages = array();
        foreach ($cart->getQuote()->getMessages() as $message) {
            if ($message) {
                $messages[] = $message;
            }
        }
        if (method_exists($cart->getCheckoutSession(), 'addUniqueMessages')) {
            $cart->getCheckoutSession()->addUniqueMessages($messages);
        }
    }
}
