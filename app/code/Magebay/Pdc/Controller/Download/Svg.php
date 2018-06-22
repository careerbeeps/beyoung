<?php
namespace Magebay\Pdc\Controller\Download;
use Magebay\Pdc\Helper\Download as DownloadHelper;
class Svg extends \Magento\Framework\App\Action\Action {
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
        $orderInfo = $data['order_info'];
        $response = array(
            'status' => 'error',
            'message' => 'Unable to save and download svg file!'
        );
        if(isset($data['svg_string'])) { 
            $response = $this->downloadHelper->createImageFromString($data['svg_string'], $orderInfo, "svg");
        }
        $this->getResponse()->setBody(json_encode($response));
    }
}
