<?php
namespace Magebay\Pdc\Controller\Designarea;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Updateproductcolor extends \Magento\Framework\App\Action\Action {
    protected $pdcSideModel;
    protected $productStatusModel;
    protected $pdpProductColorModel;
    protected $pdpProductColorImageModel;
    protected $pdcHelper;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magebay\Pdc\Model\Pdpside $pdpside,
        \Magebay\Pdc\Model\Productstatus $productStatus,
        \Magebay\Pdc\Model\Pdpcolor $pdpColor,
        \Magebay\Pdc\Model\Pdpcolorimage $pdpColorImage,
        \Magebay\Pdc\Helper\Data $pdcHelper
    ) {
        $this->pdcSideModel = $pdpside;
        $this->productStatusModel = $productStatus;
        $this->pdpProductColorModel = $pdpColor;
        $this->pdpProductColorImageModel = $pdpColorImage;
        $this->pdcHelper = $pdcHelper;
        parent::__construct($context);
    }
    public function execute() {
        try {
            $responseData = array();
            $data = $this->getRequest()->getParams();
            //\Zend_Debug::dump($data);die;
            //Update color name or color code if exists
            if(isset($data['default_side'])) {
                //( 'pdp/pdpside' )
                if(isset($data['default_side']['id']) && $data['default_side']['color_name'] && $data['default_side']['color_code']) {
                    foreach($data['default_side']['id'] as $sideId) {
                        $sideModel = $this->pdcSideModel->load($sideId);
                        $sideModel->setColorName($data['default_side']['color_name']);
                        $colorCode = str_replace("#", "",$data['default_side']['color_code']);
                        $sideModel->setColorCode($colorCode);
                        $sideModel->save();    
                    }
                }
            }
            foreach ($data['position'] as $key => $value) {
                $productColor['status'] = $value;
                $productColor['position'] = $data['position'][$key];
                $productColor['color_name'] = $data['color_name'][$key];
                $productColor['color_code'] = $data['color_code'][$key];
                if(isset($data['color_thumbnail']) && isset($data['color_thumbnail'][$key])) {
                    $productColor['color_thumbnail'] = $data['color_thumbnail'][$key];
                }
                $productColor['id'] = $key;
                $this->pdpProductColorModel->saveProductColor($productColor);
            }
        } catch(\Exception $error) {
              
        }
        
        //$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        //Design list page
        $url = $this->pdcHelper->getBaseUrl() . "pdc/designarea/index/productid/". $data['product_id'] ."#/productcolorslist/product/" . $data['product_id'];
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath($url);
    }
}
