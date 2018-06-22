<?php
namespace Magebay\Pdc\Controller\Designarea;
class Templatelist extends \Magento\Framework\App\Action\Action {
    protected $adminTemplateModel;
    protected $pdcHelper;
	protected $_resultJsonFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magebay\Pdc\Model\Admintemplate $adminTemplateModel,
        \Magebay\Pdc\Helper\Data $pdcHelper
    ) {
		$this->_resultJsonFactory = $resultJsonFactory;
        $this->adminTemplateModel = $adminTemplateModel;
        $this->pdcHelper = $pdcHelper;
        parent::__construct($context);
    }
    public function execute() {
		 $resultJson = $this->_resultJsonFactory->create();
        $response = array(
            'status' => 'error',
            'message' => 'Can not get template list. Something went worng!',
            'media_url' => $this->pdcHelper->getMediaUrl() . "pdp/images/",
            'base_url' => $this->pdcHelper->getBaseUrl()
        );
        try {
            $responseData = array();
            $productId = $this->getRequest()->getParam("productid");
            $templates = $this->adminTemplateModel->getProductTemplates($productId);
            if (!$templates->count()) {
                $response['status'] = 'error';
                $response['message'] = 'There is no template found. Please create new template for this product';
                return $resultJson->setData($response);
            } else {
                $responseData['templates'] = array();
                foreach($templates as $template) {
                    $responseData['templates'][$template->getId()] = $template->getData();
                }
            }
            if(!empty($responseData)) {
                $response['status'] = 'success';
                $response['message'] = 'Get tempate list successfully!';
                $response['data'] = $responseData;
            } 
        } catch(\Exception $error) {
              
        }
       
		return $resultJson->setData($response);
    }
    //Return a array of all side & color
    protected function getProductDesignColors($productId) {
        $sideModel = $this->pdcSideModel;
        $designSides = $sideModel->getDesignSides($productId);
        $defaultSideArr = array();
        foreach ($designSides as $side) {
            $defaultSideArr[$side->getId()] = $side->getData();
        }
        $productColorDataArr = array();
        $productColors = $this->pdpProductColorModel->getProductColorCollection($productId);
        foreach($productColors as $_productColor) {
            $productColorDataArr[$_productColor->getId()] = $_productColor->getData();
        }
        return array(
            'default_side' => $defaultSideArr,
            'product_color_sides' => $productColorDataArr
        );
    }
    //Mostly for product same as T-Shirt
    protected function isProductColorTabEnable($productColors) {
        //Check all side use background image + mask image. 
        //Has product color item
        //Check default side using background and mask or not
        if(isset($productColors['default_side'])) {
            foreach($productColors['default_side'] as $_productSide) {
                if($_productSide["background_type"] != "image" || $_productSide["use_mask"] != 1) {
                    return false;
                }
            }
        }
        if(empty($productColors['product_color_sides'])) {
            return false;
        }
        return true;
    }
}
