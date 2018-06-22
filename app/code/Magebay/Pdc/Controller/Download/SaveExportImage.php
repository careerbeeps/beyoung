<?php
namespace Magebay\Pdc\Controller\Download;

use Magebay\Pdc\Helper\Download as DownloadHelper;
use Magebay\Pdc\Helper\Data as PdcHelper;
class SaveExportImage extends \Magento\Framework\App\Action\Action {
    protected $downloadHelper;
	protected $pdcHelper;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        DownloadHelper $downloadHelper,
		PdcHelper $pdcHelper

    )
    {
        $this->downloadHelper = $downloadHelper;
		$this->pdcHelper = $pdcHelper;
        parent::__construct($context);
    }
    public function execute() {
		 $data = $this->getRequest()->getPost();
        $exportFormat = "png";
        if(isset($data['format'])) {
            $exportFormat = $data['format'];
        }
        $sideName = "_";
        if(isset($data['side_name'])) {
            $sideName = "_" . $data['side_name'] . '_';
        }
        $response = array(
            'status' => 'error',
            'message' => 'Unable to save base 64 image!'
        );
        if(isset($data['base_code_image'])) {
            $baseCode = $data['base_code_image'];
            $thumbnailDir = $this->downloadHelper->exportDir.'download/';
            $thumbnailUrl = $this->pdcHelper->getMediaUrl().'pdp/download/';
			if($exportFormat == "svg")
			{
				$thumbnailDir = $this->downloadHelper->exportDir.'download/';
				$thumbnailUrl = $this->pdcHelper->getMediaUrl().'download/';
			}
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0777);
            }
            if (!file_exists($thumbnailDir)) {
                $this->getResponse()->setBody(json_encode($response));
                return;
            }
            if($exportFormat == "svg") {
                $filename = "Design" . $sideName . uniqid() . '.svg';
                $file = $thumbnailDir . $filename;
                file_put_contents($file, $data['base_code_image']);
                if(file_exists($file)) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Image have been successfully saved!',
                        'filename' => $filename,
                        'thumbnail_path' => $thumbnailUrl . $filename
                    );
                    $this->getResponse()->setBody(json_encode($response));
                }
            } else {
                if($data['format'] === "jpeg") {
                    $data['format'] = "jpg";
                }
                $filename = "Design" . $sideName . uniqid() . '.' . $data['format'];
                $file = $thumbnailDir . $filename;
                if(substr($baseCode,0,4)=='data'){
                    $uri =  substr($baseCode,strpos($baseCode,",")+1);
                    // save to file
                    file_put_contents($file, base64_decode($uri));
                    if(file_exists($file)) {
                        //$thumbnailUrl
                        $response = array(
                            'status' => 'success',
                            'message' => 'Image have been successfully saved!',
                            'filename' => $filename,
                            'thumbnail_path' => $thumbnailUrl . $filename
                        );
                        $this->getResponse()->setBody(json_encode($response));
                    }
                }
            }
        }
    }
}
