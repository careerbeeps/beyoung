<?php 
/**
* @package    Magebay_Pdc
* @version    2.0
* @author     Magebay Developer Team <magebay99@gmail.com>
* @website    http://www.productsdesignercanvas.com
* @copyright  Copyright (c) 2009-2016 MAGEBAY.COM. (http://www.magebay.com)
*/
namespace Magebay\Pdc\Model;
class Customerdesign extends \Magento\Framework\Model\AbstractModel {
    /**
     * Initialize resource model
     * @return void
     */
    public function _construct() {
        $this->_init('Magebay\Pdc\Model\ResourceModel\Customerdesign');
    }
    public function saveTemplate($data) {
        //Check if customer login
        if(isset($data['customer_id']) && $data['customer_id'] != "") {
            $data['created_time'] = date("Y-m-d H:i:s");
            $this->setData($data)->save();
            return true;
        } else {
            //It might session timeout 
            return 'guest';
        }
		return false;
	}
    public function updateDesignDetails($data) {
        if(isset($data['id']) && $data['id'] != "") {
            $data['update_time'] = now();
            $this->load($data['id'])->setData($data)->save();
            return $this->getId();
        }
        return false;
    }
}