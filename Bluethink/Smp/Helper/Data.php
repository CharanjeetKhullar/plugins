<?php
namespace Bluethink\Smp\Helper;

use Magento\Framework\App\Helper\Context;
use Bluethink\Smp\Model\CustomProductsFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * Buynow button title path
     */
    const BUYNOW_BUTTON_TITLE_PATH = 'buynow/general/button_title';

    /**
     * Buynow button title
     */
    const BUYNOW_BUTTON_TITLE = 'Buy Now';

    /**
     * Addtocart button form id path
     */
    const ADDTOCART_FORM_ID_PATH = 'buynow/general/addtocart_id';

    /**
     * Addtocart button form id
     */
    const ADDTOCART_FORM_ID = 'product_addtocart_form';

    /**
     * Keep cart products path
     */
    const KEEP_CART_PRODUCTS_PATH = 'buynow/general/keep_cart_products';

    /**
     * @var Registry
     */
    protected $_registryData;

    /**
     * @var CustomProductsFactory
     */
    protected $_sampleProduct;
 
    public function __construct(
        Registry $registry,
        Context $context,
        CustomProductsFactory $sampleProducts,
        $data = null
    ){
        $this->_registryData = $registry;
        $this->_sampleProduct = $sampleProducts;   
        parent::__construct($context);
    }
 
    /**
     * Get Config Value
     *
     * @param $config
     * @return void
     */
    public function getConfig($config)
    {
        return $this->scopeConfig->getValue(
            $config,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get button title
     * 
     * @return string
     */
    public function getButtonTitle()
    {
        $btnTitle = $this->getConfig(self::BUYNOW_BUTTON_TITLE_PATH);
        return $btnTitle ? $btnTitle : self::BUYNOW_BUTTON_TITLE;
    }

    /**
     * Get addtocart form id
     * 
     * @return string
     */
    public function getAddToCartFormId()
    {
        $addToCartFormId = $this->getConfig(self::ADDTOCART_FORM_ID_PATH);
        return $addToCartFormId ? $addToCartFormId : self::ADDTOCART_FORM_ID;
    }

    /**
     * Check if keep cart products
     * 
     * @return string
     */
    public function keepCartProducts()
    {
        return $this->getConfig(self::KEEP_CART_PRODUCTS_PATH);
    }

    /**
     * Get Current Product
     *
     * @return void
     */
    public function getCurrentProduct()
    {
        return $this->_registryData->registry('current_product');
    }

    /**
     * Get Sample Product
     * 
     * @param mixed $productId
     * 
     * @return array
     */
    public function getSampleProduct($productId)
    {
        $sampleData = $this->_sampleProduct->create()->load($productId, 'product_id');
        $productTitle = $sampleData->getSampleTitle();
        $sampleProductId = $sampleData->getSampleProductId();
        $productPrice = $sampleData->getSamplePrice();
        $data = array();
        $data = ['product_title'=>$productTitle, 'product_id'=>$sampleProductId, 'product_price'=>$productPrice];

        return $data;    
    }

    /**
     * Get is enable button
     * 
     * @param mixed $productId
     * 
     * @return boolean
     */
    public function isEnableButton($productId)
    {
        $sampleData = $this->_sampleProduct->create()->load($productId, 'product_id');
        $sampleId = $sampleData->getSampleProductId();
        if ($sampleId) {
           return true;
        }
        return false;
    }
}
