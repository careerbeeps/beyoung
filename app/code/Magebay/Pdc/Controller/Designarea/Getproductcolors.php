<?php
namespace Magebay\Pdc\Controller\Designarea;
class Getproductcolors extends \Magento\Framework\App\Action\Action {
    protected $pdcSideModel;
    protected $productStatusModel;
    protected $pdpProductColorModel;
    protected $pdpProductColorImageModel;
    protected $pdcHelper;
	protected $_resultJsonFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magebay\Pdc\Model\Pdpside $pdpside,
        \Magebay\Pdc\Model\Productstatus $productStatus,
        \Magebay\Pdc\Model\Pdpcolor $pdpColor,
        \Magebay\Pdc\Model\Pdpcolorimage $pdpColorImage,
        \Magebay\Pdc\Helper\Data $pdcHelper
    ) {
		$this->_resultJsonFactory = $resultJsonFactory;
        $this->pdcSideModel = $pdpside;
        $this->productStatusModel = $productStatus;
        $this->pdpProductColorModel = $pdpColor;
        $this->pdpProductColorImageModel = $pdpColorImage;
        $this->pdcHelper = $pdcHelper;
        parent::__construct($context);
    }
    public function execute() {
		$resultJson = $this->_resultJsonFactory->create();
        $response = array(
            'status' => 'error',
            'message' => 'Can not get product color. Something went worng!'
        );
        try {
            $responseData = array();
            $productId = $this->getRequest()->getParam("productid");
            $productColors = $this->pdpProductColorModel->getProductColorCollection($productId);
            if (!$productColors->count()) {
                $response = array(
                    'status' => 'error',
                    'message' => 'No item found. Please add product color'
                );
				return $resultJson->setData($response);
                return false;
            }
            $_productDesignColor = $this->pdcHelper->getProductDesignColors($productId);
            if(!$this->pdcHelper->isProductColorTabEnable($_productDesignColor)) {
                $response = array(
                    'status' => 'error',
                    'message' => 'NOTE: This feature required all design sides must use background image and mask/overlay image. Please edit all side and try again.'
                );
                return $resultJson->setData($response);
                // return false;
            }
            if(isset($_productDesignColor['product_color_sides']) && !empty($_productDesignColor['product_color_sides'])) {
                //get side image for each color
                foreach($_productDesignColor['product_color_sides'] as $colorItem) {
                    $sidesImages = array();
                    $images = $this->pdpProductColorImageModel->getProductColorImage($colorItem['product_id'], $colorItem['id']);
                    foreach($images as $image) {
                        $sidesImages[]= $image->getData();
                    } 
                    $_productDesignColor['product_color_sides'][$colorItem['id']]['images'] = $sidesImages;
                }
            }
            $_productDesignColor['media_url'] = $this->pdcHelper->getMediaUrl() . "pdp/images/";
            $_productDesignColor['base_url'] = $this->pdcHelper->getBaseUrl();
            if(!empty($_productDesignColor)) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Get product color successfully!',
                    'data' => $_productDesignColor
                );
            } 
        } catch(\Exception $error) {
              
        }		
		return $resultJson->setData($response);
    }

}
