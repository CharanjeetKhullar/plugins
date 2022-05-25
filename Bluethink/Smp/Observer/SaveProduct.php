<?php

namespace Bluethink\Smp\Observer;

use Magento\Framework\Event\Observer;
use Bluethink\Smp\Model\CustomProductsFactory;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Message\ManagerInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ProductRepository;


class SaveProduct implements ObserverInterface
{

    /**
     * @var CustomProductsFactory
     */
    protected $_customProductsFactory;

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var ProductFactory
     */
    protected $_productFactory;

    /**
     * @var 
     */
    protected $_product;
    
    /**
     * @var 
     */
    protected $_isProcessed;
  
    /**
     * @param CustomProductsFactory $customProduct
     * @param Http $request
     * @param ManagerInterface $messageManager
     * @param ProductFactory $productFactory
     * @param ProductRepository $productRepository
     * @param array $data
     */
    public function __construct(
        CustomProductsFactory $customProduct,
        Http $request,
        ManagerInterface $messageManager,
        ProductFactory $productFactory,
        ProductRepository $productRepository,
        array $data = []
    ) {
        $this->_customProductsFactory = $customProduct;
        $this->request = $request;
        $this->_messageManager = $messageManager;
        $this->_productFactory = $productFactory;
        $this->_productRepository = $productRepository;
        $this->_isProcessed = true;
      }    
    
    /**
     *
     *  @param Observer $observer
     */
    public function execute(Observer $observer)
    { 
       $_product = $this->_productFactory->create();
       $productSku =  $observer->getProduct()->getSku();
       $title = $observer->getProduct()->getSampleTitle();
       $price = $observer->getProduct()->getSamplePrice();
       $qty = $observer->getProduct()->getSampleQty();
       $productId = $observer->getProduct()->getId();
       $productName =  $observer->getProduct()->getName();
       $is_EnableProdcut = $observer->getProduct()->getSampleProductEnable();
        
        if ($title != '' && $qty != '' && $price != '' && $is_EnableProdcut != 2) {

            $getIsExist = $this->_customProductsFactory->create()->load($productId, 'product_id');
            $sampleProdcutId = $getIsExist->getId();
            
            if ($sampleProdcutId) { 
                $getIsExist->setData('sample_title', $title);
                $getIsExist->setData('sample_price', $price);
                $getIsExist->setData('sample_qty', $qty);

                if ($this->_isProcessed) { 
                    $this->_isProcessed = false;$getIsExist->save();
                }

                if ($getIsExist->save()) {
                    $this->_messageManager->addSuccess(__("Sample Prodcut Updated Successfully"));
                }

            } else {
                /** create sample Prodcut code  */
                $sku = $productSku.'-sample';
                $_product->setSku($sku);
                $_product->setName($title);
                $_product->setAttributeSetId(4); 
                $_product->setStatus(1); 
                $_product->setWeight(1); 
                $_product->setWebsiteIds(array(1)); 
                $_product->setVisibility(1); 
                $_product->setTypeId('simple');
                $_product->setPrice($price); 
                $_product->setStockData(
                    array(
                    'use_config_manage_stock' => 0,
                    'manage_stock' => 1,
                    'is_in_stock' => 1,
                    'max_sale_qty' => 1,
                    'min_sale_qty' =>1,
                    'qty' => $qty
                    )
                    );
                /** Add this code for avoid recursion process */         
                if ($this->_isProcessed) {
                    $this->_isProcessed = false; 
                    $_product->save();
                }
                    
                /**Add this code to save sample prodcut details in sample prodcut table  */
                $sampleProdcutId =$_product->getId();
                if ($sampleProdcutId) {
                    $model = $this->_customProductsFactory->create();
                    $data = [];
                    $data=([
                        "sample_title" => $title,
                        "sample_price" => $price,
                        "product_id" => $productId,
                        "product_sku" => $productSku,
                        "sample_qty" => $qty,
                        "sample_product_id" => $sampleProdcutId
                        ]);
                    $saveData = $model->setData($data)->save();

                    if ($saveData) {
                        $this->_messageManager->addSuccess(__("Sample Prodcut Saved  Successfully"));
                    }
                }
            }
        }		
	}
}
    

    

