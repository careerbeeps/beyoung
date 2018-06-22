<?php 
namespace Magebay\Pdc\Block;

use Magebay\Pdc\Helper\Data as PdcHelper;
use Magebay\Pdc\Helper\Upload as UploadHelper;
use Magento\Framework\App\Request\Http;
use Magento\Store\Model\ScopeInterface;

class X3 extends \Magento\Framework\View\Element\Template {
    public $assetRepository;
    public $pdcHelper;
    public $uploadHelper;
    public $_templateLimitItem = 12;
    public $_default_page_size = 12;
    public $request;
    public $_params;
	protected $_pdcScopeConfig;
    public $productFactory;
    public $pdpColorImageFactory;
    protected $artworkcateFactory;
    protected $adminTemplateFactory;
    protected $colorFactory;
    protected $customerSession;
    protected $orderModel;
    protected $_pdpside;
	protected $_pdpImage;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
		Http $request,
		\Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order $order,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magebay\Pdc\Model\ArtworkcateFactory $artworkcateFactory,
        \Magebay\Pdc\Model\AdmintemplateFactory $adminTemplateFactory,
        \Magebay\Pdc\Model\PdpcolorimageFactory $pdpColorImageFactory,
        \Magebay\Pdc\Model\PdpsideFactory $pdpside,
        \Magebay\Pdc\Model\ColorFactory $colorFactory,
        \Magebay\Pdc\Model\ImageFactory $pdpImage,
        PdcHelper $pdcHelper,
        UploadHelper $uploadHelper,
        array $data = []
    )
    {
        $this->assetRepository = $context->getAssetRepository();
        $this->pdcHelper = $pdcHelper;
        $this->request = $request;
        $this->_params = $this->request->getParams();
        $this->productFactory = $productFactory;
        $this->artworkcateFactory = $artworkcateFactory;
        $this->adminTemplateFactory = $adminTemplateFactory;
        $this->colorFactory = $colorFactory;
        $this->uploadHelper = $uploadHelper;
        $this->customerSession = $customerSession;
        $this->orderModel = $order;
        $this->pdpColorImageFactory = $pdpColorImageFactory;
		$this->_pdpside = $pdpside;
		$this->_pdpImage = $pdpImage;
		$this->_pdcScopeConfig = $context->getScopeConfig();
        return parent::__construct($context, $data);
    } 
    public function getX3JsUrl() {
        return $this->getPdcJsUrl() . '/x3/'; 
    }
    public function getPdcJsUrl() {
        $asset_repository = $this->assetRepository;
        $asset  = $asset_repository->createAsset('Magebay_Pdc::pdc/');
        return $asset->getUrl();
    }
    public function getCurrentDesignJson() {
        $viewMode = $this->getViewMode();
        $productId = $this->getCurrentProductId();
        $jsonString = $this->getJsonContentFromParam();
        if ($jsonString == "") {
            if ($viewMode == "backend") {
                $jsonString = $this->pdcHelper->getAdminTemplates($productId);
            } else {
                if($this->getShareId() != null) {
                    $jsonString = $this->pdcHelper->getShareJsonString($this->_shareId);
                } else {
                    $jsonString = $this->pdcHelper->getAdminTemplates($productId);
                }
            }	
        }
        return $jsonString;
    }
    public function getSidesConfig() {
        return $this->pdcHelper->getSidesConfig($this->getCurrentProductId());
    }
    public function getAllImageCategories() {
        $categories = $this->artworkcateFactory->create()->getArtworkCateCollection();
        $categoryArr = array();
        if($categories->count()) {
            foreach($categories as $_category) {
                $categoryArr[] = $_category->getData();
            }  
        }
        if(!empty($categoryArr)) {
            return json_encode($categoryArr);   
        }
        return '';
    }
    public function getImageBackgroundCategories() {
        $list = $this->artworkcateFactory->create()->getImageBackgroundCategories();
        if($list->count()) {
            return $list;   
        }
        return array();
    }
    public function getProductIncludeColors() {
        $productId = $this->getCurrentProductId();
        $productConfigs = $this->pdcHelper->getProductConfig($productId);
        //Filter color if admin selected
        if($productConfigs['selected_color'] != "") {
            $selectedColors = json_decode($productConfigs['selected_color'], true);
            $_includeColors = array();
            foreach($selectedColors as $colorId => $position) {
                $_includeColors[] = $colorId;
            }
            return $_includeColors;
        }
        return false;
    }
    public function getProductIncludeFonts() {
        $productId = $this->getCurrentProductId();
        $productConfigs = $this->pdcHelper->getProductConfig($productId);
        //Filter font if admin selected
        if($productConfigs['selected_font'] != "") {
            $selectedFonts = json_decode($productConfigs['selected_font'], true);
            $_includeFonts = array();
            foreach($selectedFonts as $fontId => $position) {
                $_includeFonts[] = $fontId;
            }
            return $_includeFonts;
        }
        return false;
    }
    /**Unconvert function***/
    public function isShowTemplateTab() {
        $templates = $productTemplates = $this->adminTemplateFactory->create()->getProductTemplates($this->getCurrentProductId());
        if($templates->count() >= 1) {
            return true;
        } else {
            //if has 1 template only and don't set to default design, then show template tab
			//$adminTemplateModel = $this->adminTemplateFactory->create();
            /*$defaultTemplateData = $adminTemplateModel->getDefaultDesignData($this->getCurrentProductId());
            if(empty($defaultTemplateData) && $templates->count() == 1) {
                return true;
            }*/
        }
        return false;
    }
    public function pagingTemplateCollection($current_page, $productId) {
        $_LIMIT = $this->_templateLimitItem;
		$collection = $this->adminTemplateFactory->create()->getProductTemplates($productId);
		$collection_counter = $this->adminTemplateFactory->create()->getProductTemplates($productId);
		$size = ceil(count($collection_counter) / $_LIMIT);
		if ($current_page <= $size) {
			$collection->setCurPage($current_page);
			$collection->setPageSize($_LIMIT);
			return $collection;
		}
	}
	public function pagingCollection($current_page, $category, $page_size, $imageType) {
		$pdpImageModel = $this->_pdpImage->create();
		$collection = $pdpImageModel->getImageCollectionByCategory($category, $imageType);
		$collection_counter = $pdpImageModel->getImageCollectionByCategory($category, $imageType);
		$size = ceil(count($collection_counter) / $page_size);
		if ($current_page <= $size) {
			$collection->setCurPage($current_page);
			$collection->setPageSize($page_size);
			return $collection;
		}
	}
	public function getCurrentProductId() {
		if (isset($this->_params['product-id'])) {
            return $this->_params['product-id'];
        }
		return null;
	}
	public function getProductInfo() {
		return $this->productFactory->create()->load($this->getCurrentProductId())->getData();
	}
	public function getFacebookInfo () {
		return $this->pdcHelper->getFacebookSetting();
	}
	public function getArtworkCategories() {
		$artworkcateModel = $this->artworkcateFactory->create();
		return $artworkcateModel->getCategoryOptions();
	}
	public function getFonts() {
		return $this->pdcHelper->getFonts();
	}
	public function getProductColorCollection() {
		$colorImageModel = $this->pdpColorImageFactory->create();
		$colors = array();
		$productColors = $colorImageModel->getProductColorCollection($this->getCurrentProductId());
        $sideModel = $this->_pdpside->create();
        foreach ($productColors as $productColor) {
			$images = $colorImageModel->getProductColorImage($productColor->getProductId(), $productColor->getId());
			$imageArr = array();
			foreach ($images as $image) {
                //Get inlay info for each side color, the main purpose is to set canvas width and height
                $sideInfo = $sideModel->load($image->getSideId());
				$imageArr[] = array(
					'filename' => $image->getFilename(),
                    'overlay' => $image->getOverlay(),
					'side_id' => $image->getSideId(),
                    'inlay_w' => $sideInfo->getInlayW(),
                    'inlay_h' => $sideInfo->getInlayH(),
                    'inlay_t' => $sideInfo->getInlayT(),
                    'inlay_l' => $sideInfo->getInlayL(),
                    'color_code' => $sideInfo->getColorCode()
				);
			}
			$itemData = $productColor->getData();
			$itemData['images'] = $imageArr;
			$colors[] = $itemData;
		}
		return $colors;
	}
    public function getDefaultSideColor() {
        $productId = $this->getCurrentProductId();
        $sideModel =  $this->_pdpside->create();
        $designSides = $sideModel->getDesignSides($productId);
        $sideArr = array();
        foreach ($designSides as $side) {
            $sideArr[] = $side->getData();
        }
        return $sideArr;
    }
	public function getViewMode () {
		$params = $this->_params;
		$viewMode = "product";
		if (isset($params['area']) && ($params['area'] == "backend" || $params['area'] == "customize")) {
			$viewMode = "backend";
		}
		return $viewMode;
	}
	public function getShareId() {
		$params = $this->_params;
		$this->_shareId = null;
		if (isset($params['share-id']) && $params['share-id'] != "") {
			$this->_shareId = $params['share-id'];
		}
		return $this->_shareId;
	}
	public function getOrderInfo() {
		$params = $this->_params;
		$orderId = $itemId = "";
		if (isset($params['order-id']) && $params['order-id'] != "") {
			$orderId = $params['order-id'];
		}
		if (isset($params['item-id']) && $params['item-id'] != "") {
			$itemId = $params['item-id'];
		}
		if ($orderId != "" && $itemId != "") {
			return array(
				'order-id' => $orderId,
				'item-id' => $itemId
			);
		}
		return null;
	}
	public function getProductConfig() {
		return $this->pdcHelper->getProductConfig($this->getCurrentProductId());
	}
	public function getJsonContentFromParam() {
		$jsonContent = "";
		if (isset($this->_params['json']) && $this->_params['json'] != "") {
			$jsonContent = $this->pdcHelper->getPDPJsonContent($this->_params['json']);
		}
		return $jsonContent;
	}
	public function isAdminUser() {
		$isAdmin = false;
		if(isset($this->_params['area']) && isset($this->_params['key'])) {
			$isAdmin = true;
		}
		return $isAdmin;
	}
    public function getColors() {
        $colors = $this->colorFactory->create()->getColors();
        return $colors;
    }
    public function isLoggedIn() {
        $customerSession = $this->customerSession;
        if ($customerSession->isLoggedIn()) {
            return 'Yes';
        }
        return '';
    }
    public function getOrderItemString($orderId, $itemId) {
		$order = $this->orderModel->load($orderId); 
		$item = $order->getItemById($itemId);
		$buyRequest = $item->getBuyRequest()->getData();
		if (isset($buyRequest['extra_options'])) {
			return $buyRequest['extra_options'];
		}
		return null;
	}
    public function getOrderModel() {
        return $this->orderModel;
    }
	/* 
	* get Pdc Configuaration 
	*/
	/* 
	* get Pdc Configuaration 
	*/
	 function getFieldSetting($field,$bkStore = true)
	{
		$scopeConfig = $this->_pdcScopeConfig;
		$filedSetting = $scopeConfig->getValue($field,ScopeInterface::SCOPE_STORE); 
		return $filedSetting;
	} 
}