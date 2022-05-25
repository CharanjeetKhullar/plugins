<?php 

namespace Bluethink\Smp\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomProducts extends AbstractDb
{
   public function _construct()
   {
      $this->_init("sample_product_table", "id");
   }
}