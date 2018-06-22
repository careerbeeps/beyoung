<?php 
/**
* @package    Magebay_Pdc
* @version    2.0
* @author     Magebay Developer Team <magebay99@gmail.com>
* @website    http://www.productsdesignercanvas.com
* @copyright  Copyright (c) 2009-2016 MAGEBAY.COM. (http://www.magebay.com)
*/
namespace Magebay\Pdc\Model;
class Imagecategory extends \Magento\Framework\Model\AbstractModel {
    
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
        $this->_init('Magebay\Pdc\Model\ResourceModel\Imagecategory');
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
		$status = $this->getStatus() == 1 ? $this->getStatus() : 2;
        return self::$statusesOptions[$status];
    }

    public function getImagetypeAsLabel()
    {
        return self::$imageTypesOptions[$this->getImageTypes()];
    } 
    /*
	* add array images type
	* @return array $imageTypes
	*/
	public function getImagesTypes() {
		return array( 
            'uncategorized' => 'Uncategorized',
            'background' => "Background & Pattern",
            'frame' => "Mask",
            'image' => "Image",
            'clipart' => "Clipart",
            'shape' => "Shape" 
        );
	}
	/* 
	* get array category in image type
	* @return array $categories
	*/
	public function getCategoriesGroup() {
		$imageTypes = $this->getImagesTypes();
		$categories = $this->getCollection()
				->addFieldToFilter('status',1);
		$arrayOptions = array();
		$i = 1;
		foreach($imageTypes as $key => $imageType)
		{
			if($key == 'uncategorized')
			{
				$arrayOptions[0] = 'Uncategorized';
				continue;
			}
			$arrayValue = array();
			foreach($categories as $category)
			{
				if($category->getImageTypes() == $key)
				{
					
					$arrayValue[] = array('value'=>$category->getId(),'label'=>$category->getTitle());
				}
			}
			$arrayOptions[$i]['value'] = $arrayValue;
			$arrayOptions[$i]['label'] = $imageType;
			$i++;
		}
		return $arrayOptions;
	}
    public function getCategoriesInImageGrid() {
        $categories = $this->getCollection()
				->addFieldToFilter('status',1);
        $options = [];
        foreach($categories as $category) {
            $options[] = ['label' => $category->getTitle(), 'value' => $category->getId()];
        }
        return $options;
    }
}