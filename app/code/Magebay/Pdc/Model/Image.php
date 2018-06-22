<?php 
/**
* @package    Magebay_Pdc
* @version    2.0
* @author     Magebay Developer Team <magebay99@gmail.com>
* @website    http://www.productsdesignercanvas.com
* @copyright  Copyright (c) 2009-2016 MAGEBAY.COM. (http://www.magebay.com)
*/
namespace Magebay\Pdc\Model;
class Image extends \Magento\Framework\Model\AbstractModel {
    
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    public static $statusesOptions = [
        self::STATUS_ENABLED => 'Enable',
        self::STATUS_DISABLED => 'Disabled',
    ];

    public static $imageTypesOptions = [
        'uncategorized' => 'Uncategorized',
        'background' => 'Background',
        'frame' => 'Mask',
        'image' => 'Image',
        'clipart' => 'Clipart',
        'shape' => 'Shape',
    ];

    /**
     * Initialize resource model
     * @return void
     */
    public function _construct() {
        $this->_init('Magebay\Pdc\Model\ResourceModel\Image');
    }

    public static function getImagetypesOptionArray()
    {
        $result = [];

        foreach (self::$imageTypesOptions as $value => $label) {
            $result[] = [
                'label' => $label,
                'value' => $value
            ];
        }

        return $result;
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

    public function getStatusAsLabel()
    {
        return self::$statusesOptions[$this->getStatus()];
    }

    public function getImagetypeAsLabel()
    {
        return self::$imageTypesOptions[$this->getImageTypes()];
    } 
    public function getImageCollectionByCategory ($category, $imageType)
	{
		if ($category === "0") {
			$images = $this->getCollection()
			//->addFieldToFilter('image_types', $imageType)
			->setOrder('position', 'DESC')
			->setOrder('image_id', 'DESC');
            $images->addFieldToFilter("status", 1);
		} else {
			$images = $this->getCollection()
			//->addFieldToFilter('image_types', $imageType)
			->addFieldToFilter('category', $category)
			->setOrder('position', 'DESC')
			->setOrder('image_id', 'DESC');
            $images->addFieldToFilter("status", 1);
		}
		
		return $images;
	}
}