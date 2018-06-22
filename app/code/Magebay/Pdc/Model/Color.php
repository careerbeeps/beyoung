<?php 
/**
* @package    Magebay_Pdc
* @version    2.0
* @author     Magebay Developer Team <magebay99@gmail.com>
* @website    http://www.productsdesignercanvas.com
* @copyright  Copyright (c) 2009-2016 MAGEBAY.COM. (http://www.magebay.com)
*/
namespace Magebay\Pdc\Model;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\Db;
use Magento\Framework\Data\Collection\AbstractDb;

class Color extends \Magento\Framework\Model\AbstractModel {
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;
	/**
	* var Magebay\Pdc\Helper\Data
	**/
	protected $_pdcHelper;
	
	public function __construct(
		\Magebay\Pdc\Helper\Data $pdcHelper,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
		$this->_pdcHelper = $pdcHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
		
    }
	 /**
     * Initialize resource model
     * @return void
     */
    public function _construct() {
        $this->_init('Magebay\Pdc\Model\ResourceModel\Color');
    }
    public static $statusesOptions = [
        self::STATUS_ENABLED => 'Enable',
        self::STATUS_DISABLED => 'Disabled',
    ];
   
    public function getOptionArray() {
		$arr_status = array (
				array ('value' => 1, 'label' => $this->_pdcHelper->__ ( 'Enabled' ) ),
				array ('value' => 2, 'label' => $this->_pdcHelper->__ ( 'Disabled' ) ) 
		);
		return $arr_status;
	}
	public function getColors() {
		$collection = $this->getCollection();
		$collection->addFieldToFilter('status', 1);
		$collection->setOrder('position', 'ASC');
		$collection->setOrder('color_name');
		return $collection;
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