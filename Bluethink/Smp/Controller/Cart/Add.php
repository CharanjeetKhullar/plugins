<?php

namespace Bluethink\Smp\Controller\Cart;

use Magento\Framework\Data\Form\FormKey;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Message\ManagerInterface;

class Add extends Action 
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;
 
    protected $formKey;

    public function __construct(
        Context $context,
        Product $product,
        FormKey $formKey,
        Cart $cart,
        ManagerInterface $messageManager
    ) {
       $this->formKey = $formKey;
       $this->cart = $cart;
       $this->product = $product;
       $this->messageManager = $messageManager;
       parent::__construct($context);
    }

    public function execute()
    {
       $samplerequest =  $this->getRequest()->getParams('product');
       $productId = $samplerequest['product'] ;
       $sampleData =  $this->product->load($productId);
       $sampleProductId = $sampleData->getId(); 
       $productPrice = $sampleData->getPrice();
       try {
               $params = array();
               $params['form_key'] = $this->formKey->getFormKey();
               $params['qty'] = '1';
               $params['price'] = $productPrice;
               /*get product id*/
               $pId = $sampleProductId;//productId
               $_product = $this->product->load($pId);
               if ($_product) {
                   $this->cart->addProduct($_product, $params);
                   $this->cart->save();
               }
               $this->messageManager->addSuccess(__('Sample Product Add to cart successfully.'));
           } catch (\Magento\Framework\Exception\LocalizedException $e) {
               $this->messageManager->addException(
                   $e,
                   __('%1', $e->getMessage())
               );
           } catch (\Exception $e) {
               $this->messageManager->addException($e, __('error.'));
           }
           $resultRedirect = $this->resultRedirectFactory->create();
           return $resultRedirect->setPath('checkout/cart/index');
    }
}
            

            


        
