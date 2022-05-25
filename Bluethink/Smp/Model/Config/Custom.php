<?php

namespace Bluethink\Smp\Model\Config;

use Magento\Framework\Option\ArrayInterface;
 
class Custom implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('General')],
            ['value' => 1, 'label' => __('Wholesale')],
            ['value' => 2, 'label' => __('Retailer')]
           
        ];
    }
}