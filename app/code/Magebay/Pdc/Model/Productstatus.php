<?php 
/**
* @package    Magebay_Pdc
* @version    2.0
* @author     Magebay Developer Team <magebay99@gmail.com>
* @website    http://www.productsdesignercanvas.com
* @copyright  Copyright (c) 2009-2016 MAGEBAY.COM. (http://www.magebay.com)
*/
namespace Magebay\Pdc\Model;
class Productstatus extends \Magento\Framework\Model\AbstractModel {
    /**
     * @var \Magebay\Pdc\Data\Helper
     */
    protected $pdcHelper;
    /**
     * @var \Magebay\Pdc\Model\Pdpside
     */
    protected $pdpSide;
    /**
     * @var \Magebay\Pdc\Model\Artworkcate
     */
    protected $artworkcateFactory;
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
        \Magebay\Pdc\Model\Pdpside $pdpSide,
        \Magebay\Pdc\Model\ArtworkcateFactory $artworkcateFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        $this->pdpSide = $pdpSide;
        $this->artworkcateFactory = $artworkcateFactory;
        $this->pdcHelper = $pdcHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    /**
     * Initialize resource model
     * @return void
     */
    public function _construct() {
        $this->_init('Magebay\Pdc\Model\ResourceModel\Productstatus');
    }
	public function setProductConfig($data) {
		$id = NULL;
		$collection = $this->getCollection();
		$collection->addFieldToFilter('product_id', $data['product_id']);
		if ($collection->count() > 0) {
			$id = $collection->getFirstItem()->getId();
		}
		$this->setData($data)->setId($id)->save();
        return $this->getData();
	}
	public function getProductStatus($productId) {
		$productConfigs = $this->getProductConfig($productId);
        if(isset($productConfigs['status'])) {
            return $productConfigs['status'];    
        }
        return 2;
	}
	public function getConfigNote($productId) {
		$collection = $this->getCollection();
		$collection->addFieldToFilter('product_id', $productId);
		if ($collection->count() > 0) {
			$data = $collection->getFirstItem()->getData();
			$note = array();
			if ($data['note']) {
				$note['note'] = json_decode($data['note'], true);
			}
			//Check product status more details 
			//--Check product has side to design or not--
            $isPdpEnable = $this->pdcHelper->isModuleEnable();
			$sideModel = $this->pdpSide->getDesignSides($productId);
			if (!$sideModel->count() || !$isPdpEnable) {
				$data['status'] = 0;
			}
			$finalArr = array_merge($data, $note);
			//End check status
			return $finalArr;
		}
		return null;
	}
	public function getProductConfig($productId) {
		$note = null;
		if ($this->getConfigNote($productId)) {
			$note = $this->getConfigNote($productId);
		}
        //Default config
        $defaultConfig = $this->getProductDefaultConfig();
        if(isset($defaultConfig['selected_image']) && $note['selected_image'] == "") { // 
            $note['selected_image'] = $defaultConfig['selected_image'];
        }
		return $note;
	}
    /**
    If product have no config, then show all categories of image by default(show all clipart, frame, background, image, shape, ...)
    Skip step of create PDC product
    **/
    public function getProductDefaultConfig() {
        //Selected Image Categories
        $categories = array();
        $imageCategories = $this->artworkcateFactory->create()->getArtworkCateCollection();
        if($imageCategories->count()) {
            foreach($imageCategories as $_category) {
                $categories[$_category->getId()] = array('position' => $_category->getPosition());
            }   
        }
        return array(
            'selected_image' => json_encode($categories),
        );
    }
}