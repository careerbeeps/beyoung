<?php
namespace Magebay\Pdc\Controller\Upload;

use Magebay\Pdc\Helper\Upload as UploadHelper;

class CropImage extends \Magento\Framework\App\Action\Action {
	protected $_uploadHelper;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        UploadHelper $uploadHelper
    )
    {
        $this->_uploadHelper = $uploadHelper;
        parent::__construct($context);
    }
    public function execute() {
        $request = $this->getRequest()->getPost();
        $response = array();
        if (isset($request['filename']) && $request['filename'] != "") {
            $imagePath = $this->_uploadHelper->uploadDir . $request['filename'];
            $croppedImage = $this->_uploadHelper->cropImage($imagePath, $request);
            if($croppedImage) {
                $response = $croppedImage;
                //$this->setCustomImageSession($croppedImage['crop_image']);
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "Image not found!";
        }
        $this->getResponse()->setBody(json_encode($response));
    }

}
