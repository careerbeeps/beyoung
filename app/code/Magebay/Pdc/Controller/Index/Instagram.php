<?php
 
namespace Magebay\Pdc\Controller\Index;
 
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Instagram extends \Magento\Framework\App\Action\Action
{
	protected $_bkHelper;
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
    */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    { 
		$resultPageFactory = $this->resultPageFactory->create();
		return $resultPageFactory;
    }
}
 