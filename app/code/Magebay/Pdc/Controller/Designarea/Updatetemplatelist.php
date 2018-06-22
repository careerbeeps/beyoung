<?php
namespace Magebay\Pdc\Controller\Designarea;

class Updatetemplatelist extends \Magento\Framework\App\Action\Action {
    protected $adminTemplateModel;
    protected $pdcHelper;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magebay\Pdc\Model\Admintemplate $adminTemplateModel,
        \Magebay\Pdc\Helper\Data $pdcHelper
    ) {
        $this->adminTemplateModel = $adminTemplateModel;
        $this->pdcHelper = $pdcHelper;
        parent::__construct($context);
    }
    public function execute() {
        try {
            $data = $this->getRequest()->getPostValue();
            //\Zend_Debug::dump($data);die;
            if(isset($data['design-template']) && $data['design-template']) {
                $templateModel = $this->adminTemplateModel;
                foreach($data['design-template'] as $templateData) {
                    $templateData['product_id'] = $data['product_id'];
                    if(isset($data['default_design']) && $data['default_design'] == $templateData['id']) {
                        $templateData['is_default'] = 1;
                    } else {
                        $templateData['is_default'] = 0;
                    }
                    //\Zend_Debug::dump($templateData);
                    $templateModel->updateTemplateData($templateData);
                }   
            }
		} catch (\Exception $e) {
				
		}
        //Design list page
        $url = $this->pdcHelper->getBaseUrl() . "pdc/designarea/index/productid/". $data['product_id'] ."#/templatelist/product/" . $data['product_id'];
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath($url);        
    }
}
