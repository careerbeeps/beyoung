<?php
namespace Magebay\Pdc\Controller\Download;
use Magebay\Pdc\Helper\Download as DownloadHelper;
class Png extends \Magento\Framework\App\Action\Action {
    protected $downloadHelper;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        DownloadHelper $downloadHelper
    )
    {
        $this->downloadHelper = $downloadHelper;
        parent::__construct($context);
    }
    public function execute() {
        $data = $this->getRequest()->getPost();
        $orderInfo = array();
        $_defaultExt = "png";
        if(isset($data['options']['order_info'])) {
            $orderInfo = $data['options']['order_info'];
        }
        if(isset($data['format']) && $data['format'] != "") {
            $_defaultExt = $data['format'];
        }
        $response = array(
            'status' => 'error',
            'message' => 'Unable to save base 64 image!'
        );
        if(isset($data['base_code_image'])) {
            $baseCode = $data['base_code_image'];
            if(substr($baseCode,0,4)=='data'){
                $uri =  substr($baseCode,strpos($baseCode,",")+1);
                $pngString = base64_decode($uri);
                $isBackend = 0;
                if(isset($data['options']['is_backend'])) {
                    $isBackend = $data['options']['is_backend'];
                }
                
                $response = $this->downloadHelper->createImageFromString($pngString, $orderInfo, $_defaultExt, $isBackend);
            }
        }
        $this->getResponse()->setBody(json_encode($response));
    }
}
