<?php
namespace Magebay\Pdc\Controller\Download;

use Magebay\Pdc\Helper\Download as DownloadHelper;
use Magebay\Pdc\Helper\Data as PdcHelper;
class DownloadAll extends \Magento\Framework\App\Action\Action {
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
        $request = $this->getRequest()->getPost();
        $response = array(
            'status' => 'error',
            'message' => 'Unable to create zip and download exported files!'
        );
        if(isset($request['images']) && $request['images'] != "") {
            $images = explode(",", $request['images']);
            $finalFiles = $images;
            //Create pdf using both png and svg format
            $baseDir = $this->downloadHelper->exportDir.'download/';
			
            foreach($images as $filename) {
				
                $temp = explode(".", $filename);
				$result = '';
                if(end($temp) == "svg") {
                    $pdfFilename = $temp[0] . "_svg.pdf";
					$pdfSvgFiles = $this->downloadHelper->createPDFFromSVG($baseDir . $filename,$pdfFilename,true);
					if(isset($pdfSvgFiles['status']) == 'success')
					{
						$result = $pdfFilename;
					}
                } else {
                    $pdfFilename = $temp[0] . "_png.pdf";
					$pdfPngFiles = $this->downloadHelper->createPDFFromPng($baseDir . $filename,$pdfFilename,true);
					if(isset($pdfPngFiles['status']) == 'success')
					{
						$result = $pdfFilename;
					}
                }
                if($result && $result != '') {
                    $finalFiles[] = $result;
                }
            }
            $orderInfo = "";
            if(isset($request['order_info'])) {
                $orderInfo = $request['order_info'];
            }
            $zipResult = $this->createZipToDownload($finalFiles, $orderInfo);
            if($zipResult['status'] == "success") {
                $response = $zipResult;
            }
        }
        $this->getResponse()->setBody(json_encode($response));
    }
	protected function createZipToDownload($files, $orderInfo) {
        //Create zip folder
        $downloadAllFolder =  $this->downloadHelper->exportDir ."download/";
        if (!file_exists($downloadAllFolder)) {
            mkdir($downloadAllFolder, 0777);
        }
        if (file_exists($downloadAllFolder)) {
            $zipFilename = $this->getDownloadFilename($orderInfo);
            $fileBaseDir = $downloadAllFolder;
            $zipUrl = $this->pdcHelper->getMediaUrl(). '/pdp/export/download/' . $zipFilename;
            
            $zip = new \ZipArchive();
            $_zipPath = $downloadAllFolder . $zipFilename;
            $zip->open( $_zipPath, \ZipArchive::CREATE );
            //Read order folder
            $directory = $fileBaseDir;
            if( is_dir( $directory ) && $handle = opendir( $directory ) )
            {
                while( ( $file = readdir( $handle ) ) !== false )
                {
                    if(in_array($file, $files)) {
                        $zip->addFile($directory . "/" . $file, $file);
                    }
                }
            }
            $zip->close();
            if(file_exists($_zipPath)) {
                $response = array(
                    "status" => "success",
                    "message" => "Zip file created successfully!",
                    "zip_url" => $zipUrl
                );
            } else {
                $response = array(
                    "status" => "error",
                    "message" => "Zip file not found!"
                );
            }
            return $response;
        }
    }
	protected function getDownloadFilename($orderInfo) {
        if(!empty($orderInfo)) {
            $filename = 'Order-' . $orderInfo['increment_id'] . "-Item-" . $orderInfo['item_id'] . '-' . time() . '.zip';
        } else {
            $filename = "Al-Exported-Files-". time() .".zip";
        }
        return $filename;
    }
}
