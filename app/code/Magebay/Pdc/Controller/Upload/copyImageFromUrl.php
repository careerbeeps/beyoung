<?php
namespace Magebay\Pdc\Controller\Upload;
use Magento\Framework\UrlInterface;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magebay\Pdc\Helper\Data as PdcHelper;

class copyImageFromUrl extends \Magento\Framework\App\Action\Action {

	protected $_urlBuilder;
	protected $_fileSystem;
	protected $_storeManager;
	protected $_pdcHelper;
	protected $_resultJsonFactory;
	protected $_priceHelper;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
		Filesystem $fileSystem,
		StoreManagerInterface $storeManager,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Framework\Pricing\Helper\Data $priceHelper,
		PdcHelper $pdcHelper
    )
    {
		$this->_fileSystem = $fileSystem;
		$this->_storeManager = $storeManager;
		$this->_resultJsonFactory = $resultJsonFactory;
		$this->_priceHelper = $priceHelper;
		$this->_pdcHelper = $pdcHelper;
        parent::__construct($context);
    }
    public function execute() {
		$data = $this->getRequest()->getPost();
        //$data['url'] = "https://scontent.cdninstagram.com/hphotos-ash/t51.2885-15/e15/10593521_932289783466894_1249853250_n.jpg";
		$response = array(
            "status" => "error", 
            "message" => "Can not upload your image to server. Something when wrong!"
        );
        if(isset($data['url']) && $data['url'] != "") {
			$baseDir = $this->_fileSystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath();
			$baseDir .= "pdp/images/upload/";
            if(!file_exists($baseDir)) {
                mkdir($baseDir, 0777);
            }
			$mediaUrl = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . "pdp/images/upload/";
			$arfileTemp = explode('?',$data['url']);
			$fileTemp = $arfileTemp[0];
			$fileTemp = explode('.', $fileTemp);
			$fileExt = end($fileTemp);
			//Fix facebook upload issue
			//http://scontent.xx.fbcdn.net/hphotos-xtp1/v/t1.0-9/12122821_946660232074380_1655781826548584573_n.jpg?oh=672e4355b75b5185b0ddd7f8bce48124&oe=571CD91D
			$filename = "social-image-" . time() . "." . $fileExt;
			if (copy($data['url'], $baseDir . $filename)) {
				//Upload fee if exists
				$productId = $this->getRequest()->getParam('product_id',0);
				$price = 0;
				$priceFormat = __("Free");
				if($productId) {
					$productConfig = $this->_pdcHelper->getProductConfig($productId);
					if(isset($productConfig['clipart_price']) && $productConfig['clipart_price'] > 0) {
						$price = $productConfig['clipart_price'];
						$priceFormat = $this->_priceHelper->currency($price, true, false);
					}
				}
				$response = array(
                    "status" => "success", 
                    "message" => "Image had copied successfully!",
                    "filename" => $mediaUrl . $filename,
					//"original_filename" => $data["url"],
					"price" => $price,
					//"price_format" => $priceFormat
                );
			}
        }
		$resultJson = $this->_resultJsonFactory->create();
        return $resultJson->setData($response);
    }
}
