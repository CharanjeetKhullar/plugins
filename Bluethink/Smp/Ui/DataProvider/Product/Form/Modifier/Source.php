<?php

namespace Bluethink\Smp\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Fieldset;
use Bluethink\Smp\Model\CustomProductsFactory;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\DataType\Text;

class Source extends AbstractModifier
{

    const SAMPLE_FIELDSET_NAME = 'custom_fieldset';
    const SAMPLE_FIELD_NAME = 'sample_product_enable';
    const SAMPLE_PRICE = 'sample_price';
    const SAMPLE_TITLE = 'sample_title';
    const SAMPLE_QTY = 'sample_qty';

    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var array
     */
    protected $meta = [];

    protected $_modelCustomtabFactory;

    /**
     * @param LocatorInterface $locator
     * @param ArrayManager $arrayManager
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        UrlInterface $urlBuilder,
        CustomProductsFactory $modelFriendFactory
    ) {
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->urlBuilder = $urlBuilder;
        $this->_modelCustomtabFactory = $modelFriendFactory;
    }

    public function modifyData(array $data)
    {
        return array_replace_recursive(
            $data,
            [
                $this->locator->getProduct()->getId() => [
                    static::DATA_SOURCE_DEFAULT => [
                        static::SAMPLE_FIELD_NAME => $this->locator->getProduct()->getData(static::SAMPLE_FIELD_NAME),
                    ]
                ]
            ]
        );
    }

    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->addFieldset();

        return $this->meta;
    }

    protected function addFieldset()
    {
        $this->meta = array_replace_recursive(
            $this->meta,
            [
                static::SAMPLE_FIELDSET_NAME => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Sample Product'),
                                'componentType' => Fieldset::NAME,
                                'dataScope' => 'data.product',
                                'collapsible' => true,
                                'sortOrder' => 10,
                            ],
                        ],
                    ],
                    'children' => [
                        'header_container' => $this->getHeaderContainerConfig(10),
                        // Add children here
                    ],
                ],
            ]
        );

        return $this;
    }

    /**
     * Get config for header container
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getHeaderContainerConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => null,
                        'formElement' => Container::NAME,
                        'componentType' => Container::NAME,
                        'sortOrder' => $sortOrder,
                        'content' => __('Sample Product.'),
                    ],
                ],
            ],
            'children' => [
                'sample_container' => $this->getSampleContainer(10),
            ],
        ];
    }

    protected function getSampleContainer($sortOrder)
    {    
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => null,
                        'formElement' => Fieldset::NAME,
                        'componentType' => Fieldset::NAME,
                        'sortOrder' => $sortOrder,
                        'additionalClasses' => 'admin__field-wide',
                    ],
                ],
            ],
            'children' => [
                static::SAMPLE_FIELD_NAME => $this->getSampleFieldConfig(10),
                static::SAMPLE_QTY => $this->getSampleQtyConfig(30),
                static::SAMPLE_PRICE => $this->getSamplePriceConfig(40),
                static::SAMPLE_TITLE => $this->getSampleTitleConfig(20)
            ],
        ];
    }

    protected function getSampleFieldConfig($sortOrder)
    {
    
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Enable Sample Product'),
                        'formElement' => Select::NAME,
                        'componentType' => Field::NAME,
                        'dataScope' => static::SAMPLE_FIELD_NAME,
                        'options' => $this->getCustomtaboption(),
                        'value' => $this->getCustomtabSelectedOptions(),
                        'dataType' => Number::NAME,
                        'sortOrder' => 10,
                        'required' => true,
                    ],
                ],
            ],
            'children' => [],
        ];
    }

    protected function getSampleTitleConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Sample Title'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::SAMPLE_TITLE,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'required' => true,
                        'value' => $this->getSampleTitle(),
                    ],
                ],
            ],
            'children' => [],
        ];
    }
  
    protected function getSamplePriceConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Sample Price'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::SAMPLE_PRICE,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'value' => $this->getSamplePrice(),
                        'required' => true,
                    ],
                ],
            ],
            'children' => [],
        ];
    }

    protected function getSampleQtyConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Sample Qty'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::SAMPLE_QTY,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'value' => $this->getSampleQty(),
                        'required' => true,
                    ],
                ],
            ],
            'children' => [],
        ];
    }

    protected function getCustomtaboption() 
    {
        $getChooseOptions = [];
        $getChooseOptions[] = [
            'label' => 'Choose Options',
            'value' => '-1',
        ];
        $getChooseOptions[] = [
            'label' => 'Yes',
            'value' => '1',
        ];
        $getChooseOptions[] = [
            'label' => 'No',
            'value' => '2',
        ];
        return $getChooseOptions;
    }
     
     
    protected function getCustomtabSelectedOptions()
    {
        $currentproduct = $this->locator->getProduct()->getId();
        $CustomtabModel = $this->_modelCustomtabFactory->create();
        $CustomtabModel->load($currentproduct, 'product_id');
        if ($CustomtabModel->getId()) {
            return 1;
        } else {
            return 2;
        }
    }

    protected function getSampleTitle()
    {
       $currentproduct = $this->locator->getProduct()->getId();
       $CustomtabModel = $this->_modelCustomtabFactory->create();
       $CustomtabModel->load($currentproduct, 'product_id');
       if ($CustomtabModel->getId()) {
           return $CustomtabModel->getSampleTitle();
       }
       return;
    }

    protected function getSampleQty()
    {
       $currentproduct = $this->locator->getProduct()->getId();
       $CustomtabModel = $this->_modelCustomtabFactory->create();
       $CustomtabModel->load($currentproduct, 'product_id');
       if ($CustomtabModel->getId()) {
           return $CustomtabModel->getSampleQty();
       }
       return;
    }

    protected function getSamplePrice()
    {
       $currentproduct = $this->locator->getProduct()->getId();
       $CustomtabModel = $this->_modelCustomtabFactory->create();
       $CustomtabModel->load($currentproduct, 'product_id');
       if ($CustomtabModel->getId()) {
           return $CustomtabModel->getSamplePrice();
       }
       return;
    }
}