<?php
 
namespace Magebay\Pdc\Controller\Customerdesign;
 
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class updateDesignDetails extends \Magento\Framework\App\Action\Action
{
	protected $_customerSession;
	protected $_customerdesign;
	
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
		\Magento\Customer\Model\Session $customerSession,
		\Magebay\Pdc\Model\CustomerdesignFactory $customerdesign
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_customerSession = $customerSession;
		$this->_customerdesign = $customerdesign;
    }
    public function execute()
    {
		$resultPageFactory = $this->resultPageFactory->create();
			// Add page title
		$messageManager = $this->_objectManager->get('Magento\Framework\Message\ManagerInterface');	
		$resultPageFactory->getConfig()->getTitle()->set(__('Customize Product'));
		if(!$this->_customerSession->isLoggedIn())
		{
			$message = __("You have to login to Save Your Design");
			$messageManager->addError($message);
			$this->_redirect("*/*/index");
		}
		$params = $this->getRequest()->getParams();
        $params['customer_id'] = $this->_customerSession->getId();
        $params['update_time'] = date('Y-m-d H:i:s');
        $params['design_title'] = $params['title'];
        $params['design_note'] = $params['note'];
		$okSave = false;
		$id = isset($params['id']) ? $params['id'] : 0;
		$customerDesignModel = $this->_customerdesign->create();
		if($id > 0)
		{
			$checkCustomerDesign = $model = $customerDesignModel->load($id);
			if($checkCustomerDesign->getId() && $checkCustomerDesign->getCustomerId() == $this->_customerSession->getId())
			{
				$okSave = true;
			}
		}
		if(!$okSave)
		{
			$messageManager->addError(__('You can not save data !'));
			$this->_redirect("*/*/index");
		}
        $model = $customerDesignModel->setData($params)->save();
        $response = array();
        if($model->getId()) {
            $response['status'] = "success";
            $response['message'] = "Your design has been successfully saved!";
            $messageManager->addSuccess(__($response['message']));
            //Redirect to My Design page maybe
            $this->_redirect("*/*/index");
        }
		$this->_redirect("*/*/index");
    }
}
 