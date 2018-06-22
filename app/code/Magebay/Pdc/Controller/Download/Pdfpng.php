<?php
namespace Magebay\Pdc\Controller\Download;
use Magebay\Pdc\Helper\Download as DownloadHelper;
class Pdfpng extends \Magento\Framework\App\Action\Action {
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
        if(isset($data['order_info'])) {
            $orderInfo = $data['order_info'];
        }
        $response = array(
            'status' => 'error',
            'message' => 'Unable to save and download pdf file from png!'
        );
        if(isset($data['png_string'])) { 
            $baseCode = $data['png_string'];
            if(substr($baseCode,0,4)=='data'){
                $uri =  substr($baseCode,strpos($baseCode,",")+1);
                $pngString = base64_decode($uri);
                $isBackend = 0;
                if(isset($data['options']['is_backend'])) {
                    $isBackend = $data['options']['is_backend'];
                }
                $result = $this->downloadHelper->createImageFromString($pngString, $orderInfo, "png", $isBackend);
                if($result['file_location']) {
                    $pngFile = $result['file_location'];
                    $filename = $this->downloadHelper->getDownloadFilename($orderInfo, "pdf");
                    $response = $this->downloadHelper->createPDFFromPng($pngFile, $filename);
                }
            }
        }
        $this->getResponse()->setBody(json_encode($response));
    }
}
