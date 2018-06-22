<?php
namespace Magebay\Pdc\Controller\Designarea;

class Deleteproductcolor extends \Magento\Framework\App\Action\Action {
    protected $pdpColorModel;
    protected $_resultJsonFactory;
	
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magebay\Pdc\Model\Pdpcolor $pdpColor
    ) {
		$this->_resultJsonFactory = $resultJsonFactory;
        $this->pdpColorModel = $pdpColor;
        parent::__construct($context);
    }
    public function execute() {
        $response = array(
            'status' => 'error',
            'message' => 'Can not delete this product color. Something went wrong!'
        );
        try {
            $id = $this->getRequest()->getParam('id');
            if($id) {
                $result = $this->pdpColorModel->deleteProductColor($id);
                if($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Product color deleted successfully!'
                    );
                } 
                  
            }
        } catch(\Exception $error) {
              
        }
		$resultJson = $this->_resultJsonFactory->create();
		return $resultJson->setData($response);
    }
}
