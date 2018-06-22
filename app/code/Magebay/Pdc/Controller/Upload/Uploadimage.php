<?php
namespace Magebay\Pdc\Controller\Upload;
use Magebay\Pdc\Model\Upload;
use Magento\Store\Model\StoreManagerInterface;
use Magebay\Pdc\Helper\Data as PdcHelper;
use Magebay\Pdc\Helper\Upload as UploadHelper;

class Uploadimage extends \Magento\Framework\App\Action\Action {
    protected $uploadModel;
    protected $storeManager;
    protected $pdcHelper;
    protected $uploadHelper;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        Upload $upload,
        StoreManagerInterface $storeManager,
        PdcHelper $pdcHelper,
        UploadHelper $uploadHelper
    )
    {
        $this->uploadModel = $upload;
        $this->storeManager = $storeManager;
        $this->pdcHelper = $pdcHelper;
        $this->uploadHelper = $uploadHelper;
        parent::__construct($context);
    }
    public function execute() {
        $baseDir = $this->pdcHelper->getMediaBaseDir() . "images/upload/";
        if (!file_exists($baseDir)) {
            mkdir($baseDir, 0777);
        }
        if (file_exists($baseDir)) {
            $mediaUrl = $this->pdcHelper->getMediaUrl() . "pdp/images/upload/";
            $supportImages = array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg+xml', 'svg','ai','eps','psd','pdf');
            $resultUpload = $this->uploadModel->uploadFileAndGetName('filename',$baseDir,array(),'pdp/images/',$supportImages);
            if ($resultUpload['status'] === 'success') {
                $name = $resultUpload['file'];
                $size = $resultUpload["size"];
                $type = $resultUpload["type"]; // could be bogus!!! Users and browsers lie!!!
                //Check upload file types
                $applicationFileTypes = $this->uploadHelper->getApplicationFileTypes();
                if(in_array($type, $applicationFileTypes)) {
                    //Using imagick to convert application file to png file
                    $convertResult = $this->uploadHelper->convertFileToImageUsingPhpExt($baseDir . $name);
                    if(isset($convertResult['status']) && $convertResult['status'] == "success") {
                        $uploadedImage = $mediaUrl . $convertResult['filename'];
                    } else {
                        $this->getResponse()->setBody(json_encode($convertResult))->sendResponse();
                        return false;
                    }
                } else {
                    //Check if image is real, if not, remove file for security reason.
                    if($type == "image/svg+xml") {
                        $uploadedImage = $mediaUrl . $name;
                    } else {
                        $isRealImage = $this->uploadHelper->isRealImage($baseDir . $name);
                        if($isRealImage) {
                            $uploadedImage = $mediaUrl . $name;
                        } else {
                            $response['status'] = 'error';
                            $response['message'] = 'Please upload a valid file!';
                            //unlink($baseDir . $name);
                            $this->getResponse()->setBody(json_encode($response))->sendResponse();
                            return false;
                        }
                    }
                }
            }
            if (isset($uploadedImage)) {
                $uploadThumbnail = "";
                $filenameTemp = explode("/", $uploadedImage);
                //Create thumbnail
                $basePath = $this->pdcHelper->getMediaBaseDir() . "images/upload/" . end($filenameTemp);
                $newPath = $this->pdcHelper->getMediaBaseDir() . "images/upload/resize/";
                $options = array(
                    'media-url' => $this->pdcHelper->getMediaUrl() . 'pdp/images/upload/resize/'
                );
                $thumbnailResult = $this->uploadModel->resizeImage($basePath, $newPath, $options);
                if($thumbnailResult) {
                    $uploadThumbnail = $thumbnailResult;
                }
                //Upload fee if exists
                $productId = $this->getRequest()->getParam('product_id',0);
                $price = 0;
                $priceFormat = __("Free");
                if($productId) {
                    $productConfig = $this->pdcHelper->getProductConfig($productId);
                    if(isset($productConfig['clipart_price']) && $productConfig['clipart_price'] > 0) {
                        $price = $productConfig['clipart_price'];
                        $priceFormat = $this->pdcHelper->formatPrice($price);
                    }
                }
                //Check image dpi, image dimensions
                $checkImage = $this->checkUserImageDPI($basePath);
                $response = array(
                    'status' => 'success',
                    'message' => 'Image had uploaded successfully!',
                    'filename' => $uploadedImage,
                    'original_name' => '',
                    'thumbnail' => $uploadThumbnail,
                    'price' => $price,
                    'price_format' => $priceFormat,
                    'check_status' => $checkImage
                );
                //$this->setCustomImageSession($response);
                //Save image to customer account
                $customerUploadFilename = explode("/pdp/images/", $uploadedImage);
                $customerUploadThumbnail = explode("/pdp/images/", $uploadThumbnail);
                $customerImageData = array(
                    'filename' => end($customerUploadFilename),
                    'original_filename' => '',
                    'thumbnail' => end($customerUploadThumbnail),
                );
            }
            $this->getResponse()->setBody(json_encode($response));
        }

    }
    private function checkUserImageDPI($imagePath) {
        if(!file_exists($imagePath)) return false;
        $dpiRequired = $this->pdcHelper->getStoreConfigData("pdp/custom_upload/upload_min_dpi");
        $response = array(
            'min_dpi_enable' => false,
            'valid_image' => false //Is image high or low resolution
        );
        if($dpiRequired != "" && $dpiRequired != NULL && (int) $dpiRequired > 0 && $this->uploadHelper->isImagickLoaded()) {
            $response['min_dpi_enable'] = true;
            $response['image_dpi'] = $this->getImageDPI($imagePath);
            $response['require_dpi'] = $dpiRequired;
            if($response['image_dpi'] >= $response['require_dpi']) {
                $response['valid_image'] = true;
            }
        }
        //Check image dimensions
        $imgPixelRequireW = $this->pdcHelper->getStoreConfigData("pdp/custom_upload/upload_min_pixel_width");
        $imgPixelRequireH = $this->pdcHelper->getStoreConfigData("pdp/custom_upload/upload_min_pixel_height");
        if($imgPixelRequireW > 0 || $imgPixelRequireH > 0) {
            $imageSize = $this->uploadHelper->getImageSize($imagePath);
            $response['image_dimension']['real_width'] = 0;
            $response['image_dimension']['real_height'] = 0;
            if(is_array($imageSize) && isset($imageSize[0]) && isset($imageSize[1])) {
                $response['image_dimension']['real_width'] = $imageSize[0];
                $response['image_dimension']['real_height'] = $imageSize[1];
            }
            $response['image_dimension']['require_width'] = (int) $imgPixelRequireW;
            $response['image_dimension']['require_height'] = (int) $imgPixelRequireH;
            $response['image_dimension']['valid_image'] = true;
            if($response['image_dimension']['require_width'] > 0
                && $response['image_dimension']['require_width'] > $response['image_dimension']['real_width']) {
                $response['image_dimension']['valid_image'] = false;
            }
            if($response['image_dimension']['require_height'] > 0
                && $response['image_dimension']['require_height'] > $response['image_dimension']['real_height']) {
                $response['image_dimension']['valid_image'] = false;
            }

        }
        return $response;
    }
    private function getImageDPI($imagePath) {
        if($this->uploadHelper->isImagickLoaded()) {
            $_DPI = 72;
            $img = new \Imagick($imagePath);
            $identify = $img->identifyimage();
            if(isset($identify['resolution']['x'])) {
                if(isset($identify['units']) && $identify['units'] == "PixelsPerCentimeter") {
                    $_DPI = ceil($identify['resolution']['x'] * 2.54);
                } elseif(isset($identify['units']) && $identify['units'] == "PixelsPerInch") {
                    $_DPI = $identify['resolution']['x'];
                } else {
                    // units = Undefined
                    $_DPI = 72;
                }
            }
            return $_DPI;
        }
    }
}
