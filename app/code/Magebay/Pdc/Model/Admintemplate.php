<?php 
/**
* @package    Magebay_Pdc
* @version    2.0
* @author     Magebay Developer Team <magebay99@gmail.com>
* @website    http://www.productsdesignercanvas.com
* @copyright  Copyright (c) 2009-2016 MAGEBAY.COM. (http://www.magebay.com)
*/
namespace Magebay\Pdc\Model;
class Admintemplate extends \Magento\Framework\Model\AbstractModel {
    /**
     * Initialize resource model
     * @return void
     */
    public function _construct() {
        $this->_init('Magebay\Pdc\Model\ResourceModel\Admintemplate');
    }
    public function saveAdminTemplate($data) {
		$collection = $this->getCollection();
        if(!$data['template_id'] || !(isset($data['template_id']))) {
            //Add new design template
            $data['template_id'] = NULL;
            $data['created_time'] = date("Y-m-d H:i:s");;
            $data['update_time'] = $data['created_time'];
        } else {
            //Edit template
            $data['update_time'] = date("Y-m-d H:i:s");;
        }
        $this->setData($data)->setId($data['template_id']);
        $this->save();
        return $this;
	}
    public function _getTemplates($productId) {
        $collection = $this->getCollection();
        $collection->addFieldToFilter("product_id", $productId);
        $collection->setOrder("is_default", "DESC");
        $collection->setOrder("template_position", "ASC");
        $collection->setOrder("id", "DESC");
        return $collection;
    }
    public function getProductTemplates($productId) {
        if($productId) {
            $collection = $this->_getTemplates($productId);
            return $collection;
        }
        return false;
    }
    public function updateTemplateData($data) {
        if(isset($data['id'])) {
            // if(isset($data['is_default'])) {
            //     $this->_resetDefaultTemplate($data['product_id']);
            //     $data['is_default'] = 1;
            // }
            $this->setData($data)->setId($data['id'])->save();
        }
    }
    public function _resetDefaultTemplate($productId) {
        $collection = $this->getProductTemplates($productId);
        $collection->addFieldToFilter("is_default", 1);
        if($collection->count()) {
            foreach($collection as $template) {
                $template->setIsDefault("0")->save();
            }
        }
        return true;
    }
    //Return a string, json filename
    public function getDefaultDesign($productId) {
        $data = $this->getDefaultDesignData($productId);
        if(!empty($data)) {
            return $data['pdp_design'];
        }
        return "";
    }
    //return an array
    public function getDefaultDesignData($productId) {
        $data = array();
        $collection = $this->_getTemplates($productId);
        $collection->addFieldToFilter("is_default", 1);
        if($collection->count()) {
            $data = $collection->getFirstItem()->getData();
        }
        return $data;
    }
}