<?php 
/**
* @package    Magebay_Pdc
* @version    2.0
* @author     Magebay Developer Team <magebay99@gmail.com>
* @website    http://www.productsdesignercanvas.com
* @copyright  Copyright (c) 2009-2016 MAGEBAY.COM. (http://www.magebay.com)
*/
namespace Magebay\Pdc\Model;
class Pdpcolor extends \Magento\Framework\Model\AbstractModel {
    protected $pdpColorimageModel;    
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
        \Magebay\Pdc\Model\Pdpcolorimage $pdpColorimageModel,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        $this->pdpColorimageModel = $pdpColorimageModel;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    /**
     * Initialize resource model
     * @return void
     */
    public function _construct() {
        $this->_init('Magebay\Pdc\Model\ResourceModel\Pdpcolor');
    }
    public function saveProductColor($data) {
		$id = NULL;
		if (isset($data['id']) && $data['id'] != "") {
			$id = $data['id'];
		}
		$result = $this->setData($data)->setId($id)->save();
		return $result->getId();
	}
	public function getProductColors ($productId) {
		$collection = $this->getCollection();
		$collection->addFieldToFilter('product_id', $productId);
		$collection->setOrder('position', 'ASC');
		return $collection;
	}
	public function getProductColorCollection($productId) {
		$productColors = $this->getProductColors($productId);
		/*$productColors->getSelect()->join(
				array ('t2' => $colors->getMainTable()),
				'main_table.color_id = t2.color_id',
				array ('t2.color_name', 't2.color_code')
		);*/
		return $productColors;
	}
	public function deleteProductColor($id) {
		$productColor = $this->load($id);
		try {
			//Remove image file in pdpcolorimage table
			$colorImageModel = $this->pdpColorimageModel;
			$colorImageCollection = $colorImageModel->getCollection();
			$colorImageCollection->addFieldToFilter('product_color_id', $id);
			foreach ($colorImageCollection as $colorImage) {
				$_colorImgInfo = $colorImageModel->load($colorImage->getId());
				$_colorImgInfo->delete();
				
			}
			$productColor->delete();
		} catch (\Exception $e) {
			//\Zend_Debug::dump($e);
            return false;
		}
        return true;
	}
}