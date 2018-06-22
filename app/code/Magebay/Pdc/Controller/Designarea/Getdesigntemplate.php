<?php
namespace Magebay\Pdc\Controller\Designarea;
class Getdesigntemplate extends \Magento\Framework\App\Action\Action {
    protected $adminTemplateFactory;
    protected $pdcHelper;
    protected $_templateLimitItem = 12;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magebay\Pdc\Model\AdmintemplateFactory $adminTemplateFactory,
        \Magebay\Pdc\Helper\Data $pdcHelper
    ) {
        $this->adminTemplateFactory = $adminTemplateFactory;
        $this->pdcHelper = $pdcHelper;
        parent::__construct($context);
    }
    public function execute() {
        $data = $this->getRequest()->getPostValue();
        $current_page = $data['current_page'];
		$productId = $data['product_id'];
        if(!$productId) {
            return false;
        }
		$collection = $this->pagingTemplateCollection($current_page, $productId);
		if ( count($collection) > 0) {
			$data = array();
			foreach ($collection as $template) {
				$data[] = $template->getData();
			}
			$this->getResponse()->setBody(json_encode($data));
		} else {
			$this->getResponse()->setBody("nomore");
		}
    }
    protected function pagingTemplateCollection($current_page, $productId) {
        $_LIMIT = $this->_templateLimitItem;
		$collection = $this->adminTemplateFactory->create()->getProductTemplates($productId);
		$collection_counter = $this->adminTemplateFactory->create()->getProductTemplates($productId);
		$size = ceil(count($collection_counter) / $_LIMIT);
		if ($current_page <= $size) {
			$collection->setCurPage($current_page);
			$collection->setPageSize($_LIMIT);
			return $collection;
		}
	}
}
