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
class Savejsonfile extends \Magento\Framework\App\Action\Action {
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
        $postData = $this->getRequest()->getPost();
        $response = array(
            'status' => 'error',
            'message' => 'Unable to save json file!'
        );
		$jsonContent = "";
        $_jsonInfo = array();
        if(!isset($postData['json_content'])) {
			//Try get data another way, fix mod_security problem
			$postString = file_get_contents("php://input");
			if($postString != "") {
                $_jsonInfo = json_decode($postString, true);
                if(isset($_jsonInfo['side_config']) && $_jsonInfo['side_config']) {
                    $jsonContent = json_encode($_jsonInfo['side_config']);   
                }
			} else {
				$this->getResponse()->setBody(json_encode($response));
				return;
			}
        } else {
			$jsonContent = $postData['json_content'];
		}
        //Side Info
        $sides = array();
        $jsonBaseDir = $this->pdcHelper->getMediaBaseDir() . "json/";
		$response = array();
		if(!file_exists($jsonBaseDir)) {
			mkdir($jsonBaseDir, 0777, true);
		}
		if (file_exists($jsonBaseDir)) {
			$jsonBaseUrl = $this->pdcHelper->getMediaUrl() . 'pdp/json/';
			$filename = "CustomOption" . time() . '.json';
			try {
				$result = file_put_contents($jsonBaseDir . $filename, $jsonContent);
				if ($result) {
					$jsonFileModel = $this->jsonFileModel;;
					$jsonFileModel->setFilename($filename);
					$jsonFileModel->save();
					if ($jsonFileModel->getId()) {
                        $response['status'] = "success";
						$response['message'] = "Item saved successfully!";
						$response['filename']= $filename;
						$response['id'] = $jsonFileModel->getId();
						$response['full_path'] = $jsonBaseUrl . $filename;
                        //Check request options, this could be save sample, save customer design, save design before share, ...
                        if(!empty($_jsonInfo) && isset($_jsonInfo['options']['action'])) {
                            $_tempData = $_jsonInfo['options']; 
                            $customerSession = $this->customerSession;
                            if ($customerSession->isLoggedIn()) {
                                $_tempData['customer_id'] = $customerSession->getCustomerId();
                            }
                            //if customer logged, guest action => customer action
                            if(isset($_jsonInfo['options']['action']) && $_jsonInfo['options']['action'] == "save_guest_design") {
                                if ($customerSession->isLoggedIn()) {
                                    $_jsonInfo['options']['action'] = 'save_customer_design';
                                }    
                            }
                            switch($_jsonInfo['options']['action']) {
                                case 'save_sample': 
                                    $_tempData['pdp_design'] = $filename;
                                    $_template = $this->adminTemplateModel->saveAdminTemplate($_tempData);
                                    if(!$_template->getId()) {
                                        $response['status'] = "error";
						                $response['message'] = "Can not save default design!";
                                    }
                                    break;
                                case 'save_customer_design':
                                    $_tempData['filename'] = $filename;
                                    $_tempData['product_id'] = $_jsonInfo['options']['product_id'];
                                    $_tempData['title'] = $_jsonInfo['options']['design_title'];
                                    $_tempData['note'] = $_jsonInfo['options']['design_note'];
                                    $_isCustomerDesignSaved = $this->customerDesignModel->saveTemplate($_tempData);
                                    if($_isCustomerDesignSaved === true) {
                                        $response['status'] = 'success';
                                        $response['message'] = "Design saved successfully!";
                                    } elseif($_isCustomerDesignSaved == 'guest') {
                                        $response['status'] = "error";
						                $response['message'] = "guest";
                                    } else {
                                        $response['status'] = "error";
						                $response['message'] = "Can not save template design!";
                                    }
                                    break;
                                case 'save_for_share':
                                    $_jsonInfo['options']['pdpdesign'] = $filename;
                                    $shareId = $this->shareModel->saveShareData($_jsonInfo['options']);
                                    if($shareId) {
                                        $response['status'] = 'success';
                                        $response['message'] = "Design ready to share!";
                                        $response['share_id'] = $shareId;
                                    }
                                    break;
                            }
                        }
					}
				}
			} catch(\Exception $e) {
				$response['message'] = "Can not save json file to database!";
			}
		} else {
			$response['message'] = "Folder not exists!";
		}
        $this->getResponse()->setBody(json_encode($response));
    }
}
