<?php
namespace Magebay\Pdc\Controller\Designarea;
class Getdesigncontent extends \Magento\Framework\App\Action\Action {
    protected $pdcHelper;
	 /**
     * Result page factory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory;
     */
	protected $_resultJsonFactory;
	
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magebay\Pdc\Helper\Data $pdcHelper,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->pdcHelper = $pdcHelper;
		$this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }
    public function execute() {
		$resultJson = $this->_resultJsonFactory->create();
        $params = $this->getRequest()->getPostValue();
        if(isset($params['json_filename']) && $params['json_filename']) {
            $designContent = $this->pdcHelper->getPDPJsonContent($params['json_filename']);
            if($designContent) {
                $response = array($designContent);
				return $resultJson->setData($response);
            }
        }
    }
}
