<?php
/**
 * Copyright � 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magebay\Pdc\Block\Adminhtml\Product\Edit;
use Magento\Backend\Model\Auth\Session as BackendSession;
class Tab extends \Magento\Backend\Block\Widget\Tab
{
	/**
     * @param \Magento\Backend\Model\Auth\Session
     * 
     */
	protected $_backendSession;
    /**
     * @param \Magebay\Pdc\Helper\Data
     * 
     */
	public $_pdcHelper;
    /**
     * @param \Magebay\Pdc\Model\Productstatus
     * 
     */
    protected $actModelFactory;
	protected $productStatusFactory;
	protected $coreRegistry;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
		\Magento\Framework\Registry $coreRegistry,
		BackendSession $backendSession,
        \Magebay\Pdc\Helper\Data $_pdcHelper,
        \Magebay\Pdc\Model\ProductstatusFactory $productStatus,
        \Magebay\Pdc\Model\ActFactory $actModelFactory,
        array $data = []
    ) {
        $this->setTemplate ( 'pdp/product/pdpdesign.phtml' );
        parent::__construct($context, $data);
		
		$this->coreRegistry = $coreRegistry;
		$this->_backendSession = $backendSession;
        $this->_pdcHelper = $_pdcHelper;
        $this->productStatusFactory = $productStatus;
        $this->actModelFactory = $actModelFactory;
        $this->setCanShow($this->isShowTab());
    }
    protected function isShowTab() {
        $main_domain = $this->_pdcHelper->get_domain( $_SERVER['SERVER_NAME'] );
		if ( $main_domain != 'dev' ) {
            $rakes = $this->actModelFactory->create()->getCollection();
            $rakes->addFieldToFilter('path', 'pdp/act/key' );
            $valid = false;
            if ( count($rakes) > 0 ) {
                foreach ( $rakes as $rake )  {
                    if ( $rake->getExtensionCode() == md5($main_domain.trim($this->_pdcHelper->getStoreConfigData('pdp/act/key')) ) ) {
                        $valid = true;	
                    }
                }
            }		
            if ( $valid == true ) {
                //Check if module enable or not
                if($this->_pdcHelper->isModuleEnable()) {
                    //Check product type, check current action
                    $supportDesignProducts = array (
                        "simple",
                        "configurable",
                        "virtual",
                        "bundle"
                        //"downloadable" 
                    );
                    $product = $this->getCurrentProduct();
                    $productType = $product->getTypeId();
                    $currentAction = $this->getRequest()->getActionName();
                    if(!$product->isVisibleInSiteVisibility()) {
                        return false;
                    }
                    if(in_array($productType, $supportDesignProducts)) {
                        //show tab in edit action only
                        if($currentAction == "edit") {
                            return true;
                        } 
                    }
                }  
            }
		}
        return false;
    }
    public function getCurrentProduct() {
        return $this->coreRegistry->registry('product');
    }
    public function getProductStatus() {
        $productId = $this->getCurrentProduct()->getId();
        $status = $this->productStatusFactory->create()->getProductStatus($productId);
        return $status;
    }
}
