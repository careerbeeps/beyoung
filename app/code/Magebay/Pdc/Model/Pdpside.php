<?php 
/**
* @package    Magebay_Pdc
* @version    2.0
* @author     Magebay Developer Team <magebay99@gmail.com>
* @website    http://www.productsdesignercanvas.com
* @copyright  Copyright (c) 2009-2016 MAGEBAY.COM. (http://www.magebay.com)
*/
namespace Magebay\Pdc\Model;
class Pdpside extends \Magento\Framework\Model\AbstractModel {
    /**
     * Initialize resource model
     * @return void
     */
    public function _construct() {
        $this->_init('Magebay\Pdc\Model\ResourceModel\Pdpside');
    }
		public function saveProductSide($data) {
		$id = NULL;
		if (isset($data['id']) && $data['id'] != "" && $data["id"] !== "0") {
			$id = $data['id'];
		}
		$sideInfo = $this->setData($data)->setId($id)->save();
		return $sideInfo->getData();
	}
	public function getDesignSides($productId) {
		$collection = $this->getCollection();
		$collection->addFieldToFilter('product_id', $productId);
		$collection->setOrder('position', 'ASC');
		//$collection->setOrder('label', 'ASC');
		return $collection;
	}
    //If side using color as background
    public function getDesignSideColors($productId) {
		$collection = $this->getCollection();
		$collection->addFieldToFilter('product_id', $productId);
        $collection->addFieldToFilter('background_type', "color");
		$collection->setOrder('position', 'ASC');
		//$collection->setOrder('label', 'ASC');
		return $collection;
	}
	public function getActiveDesignSides($productId) {
		$collection = $this->getDesignSides($productId);
		$collection->addFieldToFilter('status', 1);
		return $collection;
	}
	public function inlineUpdate($params) {
		if ($params['side_id'] != "" && $params['update-info'] != "") {
			$sideInfo = $this->load($params['side_id']);
			$fieldInfo = explode('-', $params['update-info']);
			switch ($fieldInfo[0]) {
				case "status" : 
					$sideInfo->setStatus($fieldInfo[1]);
					break;
				case "position" :
					$sideInfo->setPosition($fieldInfo[1]);
                    break;
                case "price" :
					$sideInfo->setPrice($fieldInfo[1]);
                    break;    
			}
			$sideInfo->save();
		}
	}
}