<?php 

namespace Bluethink\Smp\Model;

use Magento\Framework\Model\AbstractModel;

class CustomProducts extends AbstractModel
{
	public function _construct(){
		$this->_init("Bluethink\Smp\Model\ResourceModel\CustomProducts");
	}
}