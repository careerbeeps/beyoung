<?php
namespace Magebay\Pdc\Controller\Download;

use Magebay\Pdc\Helper\Download as DownloadHelper;
class Pdfsvg extends \Magento\Framework\App\Action\Action {
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
        $orderInfo = $data["order_info"];
        $response = array(
            'status' => 'error',
            'message' => 'Unable to save and download pdf file from svg!'
        );
        if(isset($data['svg_string'])) { 
            $result = $this->downloadHelper->createImageFromString($data['svg_string'], $orderInfo, "svg");
            if($result['file_location']) {
                $svgFile = $result['file_location'];
                $filename = $this->downloadHelper->getDownloadFilename($orderInfo, "pdf");
                $response = $this->downloadHelper->createPDFFromSVG($svgFile, $filename);
            }
        }
        $this->getResponse()->setBody(json_encode($response));
    }
}
