<?php
namespace Magebay\Pdc\Controller\Download;

use Magebay\Pdc\Helper\Download as DownloadHelper;
use Magebay\Pdc\Helper\Data as PdcHelper;
class Txt extends \Magento\Framework\App\Action\Action {
    protected $downloadHelper;
    protected $orderModel;
    protected $pdcHelper;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        DownloadHelper $downloadHelper,
        PdcHelper $pdcHelper,
        \Magento\Sales\Model\Order $order
    )
    {
        $this->downloadHelper = $downloadHelper;
        $this->orderModel = $order;
        $this->pdcHelper = $pdcHelper;
        parent::__construct($context);
    }
    public function execute() {
        $data = $this->getRequest()->getParams();
		$exportString = array();
		$response = array(
            'status' => 'error',
            'message' => 'Can not download file'
        );
		//Order Info 
		$bl = "\r\n";
		$tab = "\t";
		$order = $this->orderModel->load($data['order-id']);
        $exportString[] = "Order # " . $order->getRealOrderId();
		$exportString[] = "Created at: " . $order->getData('created_at');
		//Design Info
		$fileContent = $this->pdcHelper->getPDPJsonContent($data['jsonfile']);
		$jsonContent = json_decode($fileContent, true);
		foreach ($jsonContent as $side) {
			$clipartString = array();
			$textString = array();
			//Zend_Debug::dump($side);//image_result
			$exportString[] = "****************************************************************************************************";
			$exportString[] = "Side: " . $side['label'] . $bl;
			if(!isset($side['json']) || $side['json'] == "") continue;
            $jsonDecoded = $side['json'];
			for ($j = 0; $j < count($jsonDecoded['objects']) ; $j++) {
				$itemNum = $j + 1;
				$objectType = $jsonDecoded['objects'][$j]['type'];
				if($objectType == "image") {
					$clipartString[] = "$tab $itemNum. " . $jsonDecoded['objects'][$j]['src'];
				} elseif ($objectType == "path-group") {
					$clipartString[] = "$tab $itemNum. " . $jsonDecoded['objects'][$j]['isrc'];
				} elseif($objectType == "text" || $objectType == "i-text" || $objectType == "curvedText") {
					//Zend_Debug::dump($jsonDecoded['objects'][$j]);
					$textString[] = "$tab ----------------------------------------";
					$textString[] = "$tab + text: " . $jsonDecoded['objects'][$j]['text'];
					$textString[] = "$tab + font-size: " . $jsonDecoded['objects'][$j]['fontSize'];
					$textString[] = "$tab + font-family: " . $jsonDecoded['objects'][$j]['fontFamily'];
					$textString[] = "$tab + color: " . $jsonDecoded['objects'][$j]['fill'];
					$textString[] = "$tab ----------------------------------------";
				}
			}
			$exportString[] = "Cliparts items:";
			$exportString[] = join($bl, $clipartString);
			$exportString[] = $bl . "Text items:";
			$exportString[] = join($bl, $textString);
		}
		$finalTextData = join($bl, $exportString);
		$exportTxtDir = $this->pdcHelper->getMediaBaseDir() .'export/txt/';
		$textFileUrl = $this->pdcHelper->getMediaUrl() . "pdp/export/txt/";
		$rangeTime1 = date('Y-m-d');
		$rangeTime2 = date('H-i-s');
		$rangeTime = $rangeTime1.'-'.$rangeTime2;
		$filename = 'General-Info-'.$rangeTime.'.txt';
		
		if (!file_exists($exportTxtDir)) {
                mkdir($exportTxtDir, 0777);
            } 
		$file = $exportTxtDir . $filename;
		file_put_contents($file, $finalTextData);
		if(file_exists($file)) {
			$response = array(
			'status' => 'success',
			'message' => 'Image have been successfully saved!',
			'file_path' => $textFileUrl . $filename,
			);
		}
		$this->getResponse()->setBody(json_encode($response));
    }
}
