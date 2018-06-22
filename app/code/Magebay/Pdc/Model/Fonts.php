<?php 
/**
* @package    Magebay_Pdc
* @version    2.0
* @author     Magebay Developer Team <magebay99@gmail.com>
* @website    http://www.productsdesignercanvas.com
* @copyright  Copyright (c) 2009-2016 MAGEBAY.COM. (http://www.magebay.com)
*/
namespace Magebay\Pdc\Model;
class Fonts extends \Magento\Framework\Model\AbstractModel {
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    public static $statusesOptions = [
        self::STATUS_ENABLED => 'Enable',
        self::STATUS_DISABLED => 'Disabled',
    ];
    /**
     * Initialize resource model
     * @return void
     */
    public function _construct() {
        $this->_init('Magebay\Pdc\Model\ResourceModel\Fonts');
    }
    public function getFonts() {
        $fonts = $this->getCollection()->setOrder('name', 'ASC');
		return $fonts;
    }
    public static function getStatusesOptionArray()
    {
        $result = [];

        foreach (self::$statusesOptions as $value => $label) {
            $result[] = [
                'label' => $label,
                'value' => $value
            ];
        }

        return $result;
    }
}