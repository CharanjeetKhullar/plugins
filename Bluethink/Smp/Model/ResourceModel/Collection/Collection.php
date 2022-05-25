<?php 

namespace Bluethink\Smp\Model\ResourceModel\Collection;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	public function _construct()
    {
		$this->_init("Bluethink\Smp\Model\CustomProducts","Bluethink\Smp\Model\ResourceModel\CustomProducts");
	}
}