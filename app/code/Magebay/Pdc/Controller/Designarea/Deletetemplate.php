<?php
namespace Magebay\Pdc\Controller\Designarea;

class Deletetemplate extends \Magento\Framework\App\Action\Action {
    protected $adminTemplateModel;
	protected $_resultJsonFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magebay\Pdc\Model\Admintemplate $adminTemplateModel
    ) {
		$this->_resultJsonFactory = $resultJsonFactory;
        $this->adminTemplateModel = $adminTemplateModel;
        parent::__construct($context);
    }
    public function execute() {
        $response = array(
            'status' => 'error',
            'message' => 'Can not delete this template. Something went wrong!'
        );
        try {
            $id = $this->getRequest()->getParam('id');
            if($id) {
                $result = $this->adminTemplateModel->load($id)->delete();
                if($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Template had deleted successfully!'
                    );
                } 
                  
            }
        } catch(\Exception $error) {
              
        }
		$resultJson = $this->_resultJsonFactory->create();
		return $resultJson->setData($response);
    }
}
