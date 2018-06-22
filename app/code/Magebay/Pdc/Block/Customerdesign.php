<?php 
namespace Magebay\Pdc\Block;

class Customerdesign extends \Magento\Framework\View\Element\Template {

	protected $_customerSession;
	protected $_product;
	protected $_customerdesign;
	protected $_pdcHelper;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Catalog\Model\Product $product,
		\Magebay\Pdc\Model\CustomerdesignFactory $customerdesign,
		\Magebay\Pdc\Helper\Data $pdcHelper,
        array $data = []
    )
    {
		$this->_customerSession = $customerSession;
		$this->_product = $product;
		$this->_customerdesign = $customerdesign;
		$this->_pdcHelper = $pdcHelper;
        return parent::__construct($context, $data);
    } 
	/**
	* get Customerdesign
	* @param int $customerId
	* @return $items
	**/
    function getCustomerdesign()
	{
		$collection = array();
		$customerId = 0;
		if($this->_customerSession->isLoggedIn())
		{
			$litmit = $this->getRequest()->getParam('limit',5);
			$curPage = $this->getRequest()->getParam('p',1);
			$customerId = $this->_customerSession->getId();
			$customerdesignModle = $this->_customerdesign->create();
			$collection = $customerdesignModle->getCollection()
					->addFieldToFilter('customer_id',$customerId)
					->setOrder('id','DESC');
			$collection->setPageSize($litmit);
			$collection->setCurPage($curPage);
		}
		return $collection;
	}
	protected function _prepareLayout()
    {
        $collection = $this->getCustomerdesign();
        parent::_prepareLayout();
        if ($collection) {
            // create pager block for collection
            $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager','my.custom.pager');
            $pager->setAvailableLimit(array(5=>5,10=>10,20=>20,'all'=>'all')); 
            $pager->setCollection($collection);
            $this->setChild('pager', $pager);
            $collection->load();
        }
        return $this;
    }
    /**
     * @return method for get pager html
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }   
	function getPdcHelper()
	{
		return $this->_pdcHelper;
	}
	public function getDesignLink($jsonFilenameId, $productId) {
		$product = $this->_product->load($productId);
		$productUrl = $product->getProductUrl();
		if($productUrl != "") {
			return $productUrl . "?redesign=" . $jsonFilenameId; 
		}
		return "";
	}
}