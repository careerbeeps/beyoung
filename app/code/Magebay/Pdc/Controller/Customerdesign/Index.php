<?php
 
namespace Magebay\Pdc\Controller\Customerdesign;
 
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;


class Index extends \Magento\Framework\App\Action\Action
{
	protected $_customerSession;	
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
		\Magento\Customer\Model\Session $customerSession
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_customerSession = $customerSession;
    }
    public function execute()
    {
		$resultPageFactory = $this->resultPageFactory->create();
			// Add page title
		$resultPageFactory->getConfig()->getTitle()->set(__('Customize Product'));
		$messageManager = $this->_objectManager->get('Magento\Framework\Message\ManagerInterface');
		if(!$this->_customerSession->isLoggedIn())
		{
			$message = __("You have to login before see your design");
			$messageManager->addError($message);
		}
		return $resultPageFactory;
    }
}
 