<?php
namespace Magebay\Pdc\Controller\Upload;
use Magebay\Pdc\Model\Upload;
use Magento\Store\Model\StoreManagerInterface;
use Magebay\Pdc\Helper\Data as PdcHelper;
use Magebay\Pdc\Helper\Upload as UploadHelper;

class Createqrcode extends \Magento\Framework\App\Action\Action {
    protected $storeManager;
    protected $pdcHelper;
    protected $baseDir;
    protected $mediaUrl;
	protected $_resultJsonFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        StoreManagerInterface $storeManager,
        PdcHelper $pdcHelper
    )
    {
        $this->storeManager = $storeManager;
		$this->_resultJsonFactory = $resultJsonFactory;
        $this->pdcHelper = $pdcHelper;
        $this->baseDir = $this->pdcHelper->getMediaBaseDir() . "images/upload/";
        $this->mediaUrl = $this->pdcHelper->getMediaUrl() . "pdp/images/upload/";
        parent::__construct($context);
    }
    public function execute() {
        $data = $this->getRequest()->getPostValue();
        $response = array(
            "status" => "error", 
            "message" => "Can not create qrcode. Something when wrong!"
        );
        if(isset($data['content']) && $data['content'] != "") {
            $qrcodeUrl = $this->createQRCodeUsingCurl($data['content']);
            if($qrcodeUrl) {
                $response = array(
                    "status" => "success", 
                    "message" => "QRcode had created successfully!",
                    "filename" => $qrcodeUrl
                );
            }
        }
		$resultJson = $this->_resultJsonFactory->create();
		return $resultJson->setData($response); 
    }
    //http://create.stephan-brumme.com/qr-code/
    protected function createQRCode($url, $width = 150, $height = 150, $border = 1,
            $error = "L", $https = false, $loadBalance = false) {
        // build Google Charts URL:
        // secure connection ?
        $protocol = $https ? "https" : "http";
        // load balancing
        $host   = "chart.googleapis.com";
        if ($loadBalance)
          $host = abs(crc32($parameters) % 10).".chart.apis.google.com";
        // safe URL
        $url    = urlencode($url);
        // put everything together
        $qrUrl  = "$protocol://$host/chart?chs={$width}x{$height}&cht=qr&chld=$error|$border&chl=$url";
        // get QR code from Google's servers
        $qr     = file_get_contents($qrUrl);
        // optimize PNG and save locally
        $baseDir = $this->baseDir;
        $filename = "qrcode-" . time() . ".png";
        $path = $baseDir . $filename;
        $imgIn  = imagecreatefromstring($qr);
        $imgOut = imagecreate($width, $height);
        imagecopy($imgOut, $imgIn, 0,0, 0,0, $width,$height);
        imagepng($imgOut, $path, 9, PNG_ALL_FILTERS);
        imagedestroy($imgIn);
        imagedestroy($imgOut);
        if(file_exists($path)) {
            return $this->mediaUrl . $filename;
        }
    }
    //This way much better
    protected function createQRCodeUsingCurl($url, $width = 150, $height = 150, $border = 1,
            $error = "L", $https = false, $loadBalance = false) {
		
		// build Google Charts URL:
        // secure connection ?
        $protocol = $https ? "https" : "http";
        // load balancing
        $host   = "chart.googleapis.com";
        if ($loadBalance)
          $host = abs(crc32($parameters) % 10).".chart.apis.google.com";
        // safe URL
        $url    = urlencode($url);
        // put everything together
        $qrUrl  = "$protocol://$host/chart?chs={$width}x{$height}&cht=qr&chld=$error|$border&chl=$url";
		$baseDir = $this->baseDir;
        $filename = "qrcode-" . time() . ".png";
		$path = $baseDir . $filename;
		$ch = curl_init($qrUrl);
		$fp = fopen($path, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		if(file_exists($path)) {
            return $this->mediaUrl . $filename;
        }
	}
}
