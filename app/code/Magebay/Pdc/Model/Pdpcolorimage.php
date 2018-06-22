<?php 
/**
* @package    Magebay_Pdc
* @version    2.0
* @author     Magebay Developer Team <magebay99@gmail.com>
* @website    http://www.productsdesignercanvas.com
* @copyright  Copyright (c) 2009-2016 MAGEBAY.COM. (http://www.magebay.com)
*/
namespace Magebay\Pdc\Model;
class Pdpcolorimage extends \Magento\Framework\Model\AbstractModel {
    protected $pdcSideModel;
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magebay\Pdc\Helper\Data $pdcHelper,
        \Magebay\Pdc\Model\Pdpside $pdcSideModel,
        \Magebay\Pdc\Model\ArtworkcateFactory $artworkcateFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        $this->pdcSideModel = $pdcSideModel;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    /**
     * Initialize resource model
     * @return void
     */
    public function _construct() {
        $this->_init('Magebay\Pdc\Model\ResourceModel\Pdpcolorimage');
    }
    public function saveProductColorImage($data) {
		$id = NULL;
		if (isset($data['id']) && $data['id'] != "") {
			$id = $data['id'];
		}
		$result = $this->setData($data)->setId($id)->save();
		return $result->getId();
	}
	public function getProductColorImage($productId, $productColorId) {
		$sideCollection = $this->pdcSideModel->getCollection();
		$sideCollection->addFieldToFilter('product_id', $productId);
		$collection = $this->getCollection();
		$collection->addFieldToFilter('product_color_id', $productColorId);
		
		$collection->getSelect()->join(
			array ('t2' => $sideCollection->getMainTable()),
			'main_table.side_id = t2.id AND t2.status = 1',
			't2.label'	
		);
		return $collection;
	}
}