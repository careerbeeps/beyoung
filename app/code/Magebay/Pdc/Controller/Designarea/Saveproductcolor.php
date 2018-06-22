<?php
namespace Magebay\Pdc\Controller\Designarea;

class Saveproductcolor extends \Magento\Framework\App\Action\Action {
    protected $pdpProductColorModel;
    protected $pdpProductColorImageModel;
    protected $uploadModel;
    protected $pdcHelper;
	protected $_resultJsonFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magebay\Pdc\Model\Pdpcolor $pdpColor,
        \Magebay\Pdc\Model\Pdpcolorimage $pdpColorImage,
        \Magebay\Pdc\Model\Upload $upload,
        \Magebay\Pdc\Helper\Data $helper
    ) {
		$this->_resultJsonFactory = $resultJsonFactory;
        $this->pdpProductColorModel = $pdpColor;
        $this->pdpProductColorImageModel = $pdpColorImage;
        $this->uploadModel = $upload;
        $this->pdcHelper = $helper;
        parent::__construct($context);
    }
    public function execute() {
        $response = array(
            'status' => 'error',
            'message' => 'Can not save product color. Something went wrong!'
        );
        try {
            $postData = file_get_contents("php://input");
            $dataDecoded = json_decode($postData, true);
            $productColorId = $this->pdpProductColorModel->saveProductColor($dataDecoded);
            if($productColorId) {
                $productColorImageInfo['product_color_id'] = $productColorId;
                foreach ($dataDecoded['design_sides'] as $sideId) {
					$productColorImageInfo['side_id'] = $sideId;
					$filename = $dataDecoded['color_image_' . $sideId];
                    $overlayFilename = ($dataDecoded['overlay_image_' . $sideId] && $dataDecoded['overlay_image_' . $sideId] != '') ? $dataDecoded['overlay_image_' . $sideId] : '';
                    if($filename != "") {
                        $productColorImageInfo['filename'] = $filename;
                        //Create thumbnail
                        $thumbnail = $this->pdcHelper->getMediaBaseDir() . 'images/resize/' . $filename;
                        if(file_exists($thumbnail)) {
                            $productColorImageInfo['filename_thumbnail'] = 'resize/resize_' . $filename;
                        }
                        //End create thumbnail
                        $productColorImageInfo['overlay'] = $overlayFilename;
				        $this->pdpProductColorImageModel->saveProductColorImage($productColorImageInfo);
                    }
				}
                $response = array(
                    'status' => 'success',
                    'message' => 'Side saved successfully!',
                );
            } 
        } catch(\Exception $error) {
              
        }
       $resultJson = $this->_resultJsonFactory->create();
		return $resultJson->setData($response);
    }
}
