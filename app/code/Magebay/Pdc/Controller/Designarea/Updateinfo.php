<?php
namespace Magebay\Pdc\Controller\Designarea;

class Updateinfo extends \Magento\Framework\App\Action\Action {
    protected $productStatusModel;
	protected $_resultJsonFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magebay\Pdc\Model\Productstatus $productStatus
    ) {
		$this->_resultJsonFactory = $resultJsonFactory;
        $this->productStatusModel = $productStatus;
        parent::__construct($context);
    }
    public function execute() {
        $response = array(
            'status' => 'error',
            'message' => 'Can not save side. Something went worng!'
        );
        try {
            $postData = file_get_contents("php://input");
            $dataDecoded = json_decode($postData, true);
            //Note should in json format
            $noteArray = $dataDecoded['note'];
            $dataDecoded['note'] = json_encode($noteArray);
            $result = $this->productStatusModel->setProductConfig($dataDecoded);
            if($result) {
                $noteDecoded = json_decode($result['note'], true);
                $result['note'] = $noteDecoded;
                $response = array(
                    'status' => 'success',
                    'message' => 'Product info saved successfully!',
                    'data' => $result
                );
            } 
        } catch(\Exception $error) {
              
        }
        $resultJson = $this->_resultJsonFactory->create();
		return $resultJson->setData($response);
    }
}
