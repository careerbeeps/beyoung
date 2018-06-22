<?php
namespace Magebay\Pdc\Controller\Save;
use Magebay\Pdc\Helper\Data as PdcHelper;
use Magento\Framework\App\Request\Http;
use Magebay\Pdc\Model\Jsonfile;
use Magebay\Pdc\Model\Customerdesign;
use Magebay\Pdc\Model\Admintemplate;
use Magebay\Pdc\Model\Share;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Savebase64image extends \Magento\Framework\App\Action\Action {
    protected $pdcHelper;
    protected $http;
    protected $jsonFileModel;
    protected $customerSession;
    protected $customerDesignModel;
    protected $adminTemplateModel;
    protected $shareModel;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        PdcHelper $pdcHelper,
        Http $http,
        Jsonfile $jsonFileModel,
        \Magento\Customer\Model\Session $customerSession,
        CustomerDesign $customerDesign,
        Admintemplate $adminTemplate,
        Share $share
    )
    {
        $this->pdcHelper = $pdcHelper;
        $this->http = $http;
        $this->jsonFileModel = $jsonFileModel;
        $this->customerSession = $customerSession;
        $this->customerDesignModel = $customerDesign;
        $this->adminTemplateModel = $adminTemplate;
        $this->shareModel = $share;
        parent::__construct($context);
    }
    public function execute() {
        $data = $this->getRequest()->getPost();
        $previewThumbnailType = "jpg";
        $response = array(
            'status' => 'error',
            'message' => 'Unable to save base 64 image!'
        );
        if(isset($data['base_code_image'])) {
            $baseCode = $data['base_code_image'];
            $thumbnailDir = $this->pdcHelper->getMediaBaseDir() . "images/thumbnail/";
            $thumbnailUrl = $this->pdcHelper->getMediaUrl() . "pdp/images/thumbnail/";
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0777);
            } 
            if($previewThumbnailType == "svg") {
                $filename = "thumbnail_image_" . time() . '.svg';
                $file = $thumbnailDir . $filename;
                file_put_contents($file, $data['base_code_image']);
                if(file_exists($file)) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Image have been successfully saved!',
                        'thumbnail_path' => $thumbnailUrl . $filename,
                        'filename' => $filename
                    );
                    $this->getResponse()->setBody(json_encode($response));
                }
            } else {
                if($data['format'] === "jpeg") {
                    $data['format'] = "jpg";
                }
                $filename = "thumbnail_image_" . time() . '.' . $data['format'];
                $file = $thumbnailDir . $filename;
                if(substr($baseCode,0,4)=='data'){
                    $uri =  substr($baseCode,strpos($baseCode,",")+1);
                    // save to file
                    file_put_contents($file, base64_decode($uri));
                    if(file_exists($file)) {
                        //$thumbnailUrl
                        $response = array(
                            'status' => 'success',
                            'message' => 'Image have been successfully saved!',
                            'thumbnail_path' => $thumbnailUrl . $filename,
                            'filename' => $filename
                        );
                        $this->getResponse()->setBody(json_encode($response));
                    }
                }
            }
        }
    }
}
