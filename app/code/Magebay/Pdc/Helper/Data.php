<?php 
/**
* @package    Magebay_Pdc
* @version    2.0
* @author     Magebay Developer Team <magebay99@gmail.com>
* @website    http://www.productsdesignercanvas.com
* @copyright  Copyright (c) 2009-2016 MAGEBAY.COM. (http://www.magebay.com)
*/
namespace Magebay\Pdc\Helper;
class Data extends \Magento\Framework\App\Helper\AbstractHelper {
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    protected $scopeConfig;
    protected $fontFactory;
    protected $productStatusFactory;
    protected $pdpSideFactory;
    protected $adminTemplateFactory;
    protected $productFactory;
    protected $priceFormat;
    protected $pdpColorFactory;
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magebay\Pdc\Model\FontsFactory $fontFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        //\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magebay\Pdc\Model\ProductstatusFactory $productStatusFactory,
        \Magebay\Pdc\Model\PdpsideFactory $pdpSideFactory,
        \Magebay\Pdc\Model\PdpcolorFactory $pdpColorFactory,
        \Magebay\Pdc\Model\AdmintemplateFactory $adminTemplateFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Pricing\Helper\Data $priceFormat
    ) {
        $this->_storeManager = $storeManager;
        //$this->scopeConfig = $scopeConfig;
        $this->scopeConfig = $context->getScopeConfig();
        $this->fontFactory = $fontFactory;
        $this->productStatusFactory = $productStatusFactory;
        $this->pdpSideFactory = $pdpSideFactory;
        $this->adminTemplateFactory = $adminTemplateFactory;
        $this->productFactory = $productFactory;
        $this->priceFormat = $priceFormat;
        $this->pdpColorFactory = $pdpColorFactory;
        parent::__construct($context);
    }
    public function isModuleEnable() {
        //Module should enable in system and should be also activated
        $isModuleEnable = $this->getStoreConfigData('pdp/setting/enable');
        if($isModuleEnable == "1") {
            return true;    
        }
        return false;
    }
    public function getBaseUrl() {
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK);
        return $baseUrl;
    }
    public function getMediaUrl() {
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $baseUrl;
    }
    public function getMediaBaseDir() {
        return BP . "/pub/media/pdp/";
    }
    /** Get store config data in system**/
    public function getStoreConfigData($path) {
         $config = $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
         return $config;
    }
    public function isProductDesignAble($productId) {
		$supportDesignProducts = array (
				"simple",
				"configurable",
				"virtual",
                "bundle"
				//"downloadable" 
		);
		$product = $this->productFactory->create()->load($productId);
		if(!in_array($product->getTypeId(), $supportDesignProducts)) {
			return false;
		}
		//If product that "Not Visible Individually" will unable to customize
		if(!$product->isVisibleInSiteVisibility()) {
			return false;
		}
		return true;
	}
	 public function getPdpBaseUrl() {
		//new code for m2
		$url = $this->_storeManager->getStore()->getBaseUrl();
		return $url;
	}
    public function isDesignAble($productId) {
        if(!$this->isProductDesignAble($productId)) {
        	return false;
        }
        $productStatus = $this->productStatusFactory->create()->getProductStatus($productId);
        if ($productStatus != 1) {
            return false;
        }
        return true;
    }
    /****** Unconvert functions *****/
    public function getImageQuality() {
        $quality = 0.7;
        // $qualityInSystem = Mage::getStoreConfig("pdp/setting/image_quality");
        // if($qualityInSystem > 0 && $qualityInSystem <= 1) {
        //    $quality = $qualityInSystem;
        // }
        return $quality;
    }
    //Return a array of all side & color
    public function getProductDesignColors($productId) {
        $sideModel = $this->pdpSideFactory->create();
        $designSides = $sideModel->getDesignSides($productId);
        $defaultSideArr = array();
        foreach ($designSides as $side) {
            $defaultSideArr[$side->getId()] = $side->getData();
        }
        $productColorDataArr = array();
        $productColors = $this->pdpColorFactory->create()->getProductColorCollection($productId);
        foreach($productColors as $_productColor) {
            $productColorDataArr[$_productColor->getId()] = $_productColor->getData();
        }
        return array(
            'default_side' => $defaultSideArr,
            'product_color_sides' => $productColorDataArr
        );
    }
    //Mostly for product same as T-Shirt
    public function isProductColorTabEnable($productColors) {
        //Check all side use background image + mask image. 
        //Has product color item
        //Check default side using background and mask or not
        if(isset($productColors['default_side'])) {
            foreach($productColors['default_side'] as $_productSide) {
                /* if($_productSide["background_type"] != "image" || $_productSide["use_mask"] != 1) {
                    return false;
                } */
				//remove require overlay
				if($_productSide["background_type"] != "image") {
                    return false;
                }
            }
        }
        if(empty($productColors['product_color_sides'])) {
            return false;
        }
        return true;
    }
    public function isLoggedIn() {
        return false;
    }
    public function getSampleJsonFile($productId) {
		$jsonFilename = $this->adminTemplateFactory->create()->getDefaultDesign($productId);
		return $jsonFilename;
	}
    public function getAdminTemplates($productId) {
		$response = "";
		$jsonFilename = $this->getSampleJsonFile($productId);
		if ($jsonFilename != "") {
			$response = $this->getPDPJsonContent($jsonFilename);
		}
		return $response;
	}
    /****** End Unconvert functions *****/
    public function getFonts() {
        $fonts = $this->fontFactory->create()->getFonts();
        return $fonts;
    }
    public function getProductConfig($productId) {
        return $this->productStatusFactory->create()->getProductConfig($productId);
    }
    public function getSidesConfig($productId) {
        $model = $this->pdpSideFactory->create();
		$sides = $model->getActiveDesignSides($productId);
        $sidesConfig = array();
        foreach($sides as $side) {
            $sidesConfig[$side->getId()] = $side->getData();
        }
        if(!empty($sidesConfig)) {
            return json_encode($sidesConfig);
        }
        return false;
    }
    public function formatPrice($price) {
        return $this->formatPrice->currency($price,true,false);
    }
    public function getPDPJsonContent($filename) {
        $jsonBaseDir = $this->getMediaBaseDir() . "json/";
        try {
            $data = file_get_contents($jsonBaseDir . $filename);
            if ($data) {
                return $data;
            }
        } catch (\Exception $e) {

        }
    }
    public function getThumbnailImage($filename) {
		$content = $this->getPDPJsonContent($filename);
		$jsonData = json_decode($content, true);
		$thumbnails = array();
		foreach ($jsonData as $side) {
			if(!isset($side['sideSvg'])) continue;
            $thumbnails[] = array (
                'name' => $side['label'], 
                'image' => isset($side['new_prevew_svg']) ? $side['new_prevew_svg'] : $side['sideSvg'],
                //'inlay' => $side['side_inlay'],
            );
		}
		return $thumbnails;
	}
    public function getPDPExtraPrice($jsonFilename) {
		$extraPrice = 0;
		if ($jsonFilename) {
			$jsonContent = $this->getPDPJsonContent($jsonFilename);
			$jsonDecoded = json_decode($jsonContent, true);
            foreach ($jsonDecoded as $side) {
                if(isset($side['final_price'])) {
                    $extraPrice += floatval($side['final_price']);    
                }
            }
		}
		return $extraPrice;
	}
    public function get_content_id($file,$id){
		$h1tags = preg_match_all("/(<div id=\"{$id}\">)(.*?)(<\/div>)/ismU",$file,$patterns);
		$res = array();
		array_push($res,$patterns[2]);
		array_push($res,count($patterns[2]));
		return $res;
	}
	public function get_div($file,$id){
	    $h1tags = preg_match_all("/(<div.*>)(\w.*)(<\/div>)/ismU",$file,$patterns);
	    $res = array();
	    array_push($res,$patterns[2]);
	    array_push($res,count($patterns[2]));
	    return $res;
	}
    public function get_domain($url)   {   
		//$dev = 'dev';
		$dev = $_SERVER['SERVER_NAME'];
		if ( !preg_match("/^http/", $url) )
			$url = 'http://' . $url;
		if ( $url[strlen($url)-1] != '/' )
			$url .= '/';
		$pieces = parse_url($url);
		$domain = isset($pieces['host']) ? $pieces['host'] : ''; 
		if ( preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs) ) { 
			$res = preg_replace('/^www\./', '', $regs['domain'] );
			return $res;
		}   
		return $dev;
	}
}