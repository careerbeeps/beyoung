<?php 
/**
* @package    Magebay_Pdc
* @version    2.0
* @author     Magebay Developer Team <magebay99@gmail.com>
* @website    http://www.productsdesignercanvas.com
* @copyright  Copyright (c) 2009-2016 MAGEBAY.COM. (http://www.magebay.com)
*/
namespace Magebay\Pdc\Helper;
require_once(BP . "/lib/internal/TCPDF/TCPDF.php");
class Download extends \Magento\Framework\App\Helper\AbstractHelper {
    public $exportDir;
    public $exportMediaUrl;
    protected $pdcHelper;
    protected $uploadHelper;
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magebay\Pdc\Helper\Data $pdcHelper,
        \Magebay\Pdc\Helper\Upload $uploadHelper
    ) {
        $this->pdcHelper = $pdcHelper;
        $this->uploadHelper = $uploadHelper;
        $this->exportDir = $this->pdcHelper->getMediaBaseDir() .  "export/" ;
        $this->exportMediaUrl = $this->pdcHelper->getMediaUrl() . "pdp/export/";
        parent::__construct($context);
    }
    //Create image from a string, string might a svg string or base64 string
    public function createImageFromString($imageString, $orderInfo, $imgExt = 'png', $isBackend = 0) {
        $response = array(
            'status' => 'error',
            'message' => 'Unable to create image from string!'
        );
        if($imageString) {
            $this->setDownloadableWhenExport();
            $thumbnailDir = $this->exportDir . $imgExt . '/';
            $thumbnailUrl = $this->exportMediaUrl . $imgExt . "/";
            $filename = $this->getDownloadFilename($orderInfo, $imgExt);
            $file = $thumbnailDir . $filename;
            //If image is svg image, then replace linked image to base64 image
            if($imgExt == "svg") {
                $imageString = $this->convertLinkedToEmbedImage($imageString);
            }
            file_put_contents($file, $imageString);
            if(file_exists($file)) {
                //Add watermark if active, only add watermark to customer design.
                if(!isset($orderInfo['order_id']) && $isBackend == 0) {
                    $this->uploadHelper->addWatermark($file);    
                }
                //$thumbnailUrl
                $response = array(
                    'status' => 'success',
                    'message' => 'Image have been successfully saved!',
                    'thumbnail_path' => $thumbnailUrl . $filename,
                    'file_location' => $file
                );
            }
        }
        return $response;
    }
    protected function setDownloadableWhenExport() {
        $exportFolder = $this->exportDir;
        if (!file_exists($exportFolder)) {
            mkdir($exportFolder, 0777);
        }
        $fileTypes = array("pdf", "png", "svg", "jpg");
        foreach($fileTypes as $type) {
            $thumbnailDir = $this->exportDir . $type . '/';
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0777);
            }
            //Check if htaccess file exists or not, this file for downloadable purpose
            if (!file_exists($thumbnailDir . ".htaccess")) {
                $htaccessInfo = "AddType application/octet-stream .pdf \n";
                $htaccessInfo .= "AddType application/octet-stream .svg \n";
                $htaccessInfo .= "AddType application/octet-stream .png \n";
                $htaccessInfo .= "AddType application/octet-stream .jpg \n";
                file_put_contents($thumbnailDir . ".htaccess", $htaccessInfo);
            }
        }
    }
    public function getDownloadFilename($orderInfo, $fileExt) {
        if(!empty($orderInfo)) {
            $filename = "Design-" . $orderInfo['side_label'] . '-Order-' . $orderInfo['increment_id'] . "-Item-" . $orderInfo['item_id'] . '-' . time() . '.' . $fileExt;
        } else {
            $filename = "Design-" . time() . "." . $fileExt;
        }
        
        return $filename;
    }
    //Replace linked images in svg to embed image
    //Return svg string with image encoded
    protected function convertLinkedToEmbedImage($svgString) {
        $linkedImages = $this->getLinkedImagesFromSvg($svgString);
        //If there is no linked image, return original svg string
        if(empty($linkedImages)) {
            return $svgString;
        }
        $base64Image = array();
        foreach($linkedImages as $url) {
            $base64Image[$url] = $this->convertLinkedImageToBase64Image($url);
        }
        //Replace here
        foreach($base64Image as $linkImg => $baseCodeImg) {
            $temp = str_replace($linkImg, $baseCodeImg, $svgString);
            $svgString = $temp;
        }
        return $svgString;
    }
    protected function getLinkedImagesFromSvg($imageString) {
        $xml = new \SimpleXmlElement($imageString);
        $linkedImages = array();
        foreach($xml as $node) {
            try {
                if($node->image) {
                    $attributes = $node->image->attributes('xlink', true);
                    $linkedImages[] = (string) $attributes->href;
                }
            } catch(\Exception $e) {

            }
        }
        return $linkedImages;
    }
    //Convert linked image to base 64 image, then can open and edit in AI Editor
    public function convertLinkedImageToBase64Image($url) {
        $fileNameArr = explode("/pdp/images/", $url);
        //Replace to blank space, this happen when use import image feature
        $filename = str_replace("%20", " ", end($fileNameArr));
        //Search path of image
        $pdcImageBasePath = $this->pdcHelper->getMediaBaseDir() . "images/";
        $filePath = "";
        $pdpSubImgDirs = array("upload", "artworks", "color-thumbnail", "thumbnail"); // Need to add more folder if needed
		if(file_exists($pdcImageBasePath . $filename)) {
            $filePath = $pdcImageBasePath . $filename;
        } else {
            //Check sub folder
            foreach ($pdpSubImgDirs as $dir) {
                $tempPath = $pdcImageBasePath . $dir . "/" . $filename;
                if(file_exists($tempPath)) {
                    $filePath = $tempPath;
                    break;
                }
            }
        }
        if($filePath != "") {
            try {
                $type = pathinfo($filePath, PATHINFO_EXTENSION);
                $data = file_get_contents($filePath); 
                $imageEncode = base64_encode($data);
                if($imageEncode) {
                    $base64 = 'data:image/' . $type . ';base64,' . $imageEncode;
                    return $base64;
                }
            } catch(\Exception $error) {
            
            }
        }
        //Default will return old url
        return $url;
    }
    public function createPDFFromPng($pngFile, $filename,$downloadZip = false) {
		 $response = array(
            'status' => 'error',
            'message' => 'Unable to create pdf file!'
        );
		if(!file_exists($pngFile)) {
			return;
		}
		$pdfSize = array();//array(floatval($attrs->width), floatval($attrs->height));
        //png size 
        $pngSize = $this->getImageSize($pngFile);
        if(is_array($pngSize)) {
            $pdfSize[0] = $pngSize[0];
            $pdfSize[1] = $pngSize[1];
        }
		$pdf = new \TCPDF_TCPDF("", "mm", $pdfSize, true, 'UTF-8', false, false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetLeftMargin(0);
		$pdf->SetRightMargin(0);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);
		$pdf->setPrintFooter(false);
		$pdf->setPrintHeader(false);
		$pdf->SetAutoPageBreak(TRUE, -$pdf->getBreakMargin());
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->AddPage();
		$pdf->Image($pngFile, $x=0, $y=0, $w=$pdf->getPageWidth(), $h=0, $link='', $align='', $palign='', $border=0, $fitonpage=true);
		$pdf->close();        
        $pdfDir = $this->exportDir . "pdf/";
        $pdfUrl = $this->exportMediaUrl . "pdf/";
		if($downloadZip)
		{
			$pdfDir = $this->exportDir . "download/";
			$pdfUrl = $this->exportMediaUrl . "download/";
		}
        if (!file_exists($pdfDir)) {
            mkdir($pdfDir, 0777);
        } 
        $this->setDownloadableWhenExport();
        $pdfPath = $pdfDir . $filename;
		$pdf->Output($pdfPath, 'F');
        if(file_exists($pdfPath)) {
			$response = array(
                'status' => 'success',
                'message' => 'Pdf have been successfully created!',
                'pdf_url' => $pdfUrl . $filename
            );
		}
        return $response;
	}
    private function getImageSize($pngFile) {
        //Return mm
        $pngSize = $this->uploadHelper->getImageSize($pngFile);
        if(is_array($pngSize)) {
            $pngInMM = array();
            $pngInMM[0] = $this->pixelToMM($pngFile, $pngSize[0]);
            $pngInMM[1] = $this->pixelToMM($pngFile, $pngSize[1]);
            return $pngInMM;
        }
        return false;
    }
    //Get DPI to get exactly size of pdf
    private function getPngDPI($pngFile) {
        //Some code to get dpi here
        return 96;
    }
    private function pixelToMM ($pngFile, $pixel) {
        //mm = (pixels * 25.4) / dpi
        //pixels = (mm * dpi) / 25.4
        //there are 25.4 millimeters in an inch
        //Reference Link: http://www.dallinjones.com/2008/07/how-to-convert-from-pixels-to-millimeters/
        $dpi = $this->getPngDPI($pngFile);
        $mm = ($pixel * 25.4) / $dpi;
        return $mm;
    }
    public function createPDFFromSVG($svgFile, $filename,$downloadZip = false) {
		 $response = array(
            'status' => 'error',
            'message' => 'Unable to create pdf file!'
        );
		if(!file_exists($svgFile)) {
			return;
		}
		$xml = \simplexml_load_file($svgFile);
		$svgFonts = array();
		foreach($xml as $node) {
			try {
				if ($node->text) {
					$textAttr = $node->text->attributes();
					$fontFamily = (string)$textAttr['font-family'];
					if(!in_array($fontFamily, $svgFonts)) {
						$svgFonts[] = $fontFamily;
					}
				}
			} catch(\Exception $e) {
	
			}
		}
		if(!empty($svgFonts)) {
			$svgFonts = $this->filterSvgFont($svgFonts);
		}
		$attrs = $xml->attributes();
		$pdfSize = array(floatval($attrs->width), floatval($attrs->height));
        if($pdfSize[0] > 0 && $pdfSize[1] > 0) {
            $svgSizeInMM = $this->getSVGSizeInMM($pdfSize[0], $pdfSize[1]);
            $pdfSize = $svgSizeInMM;
        }
		$pdf = new \TCPDF_TCPDF("", "mm", $pdfSize, true, 'UTF-8', false, false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetLeftMargin(0);
		$pdf->SetRightMargin(0);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);
		$pdf->setPrintFooter(false);
		$pdf->setPrintHeader(false);
		$pdf->SetAutoPageBreak(TRUE, -$pdf->getBreakMargin());
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		//Set Font
		foreach($svgFonts as $font) {
			$pdf->SetFont($font);
		}
		$pdf->AddPage();
		$pdf->ImageSVG($svgFile, $x=0, $y=0, $w=$pdf->getPageWidth(), $h=0, $link='', $align='', $palign='', $border=0, $fitonpage=true);
		$pdf->close();
		//Close and output PDF document
        $pdfDir = $this->exportDir . "pdf/";
        $pdfUrl = $this->exportMediaUrl  . "pdf/";
		//export to download if create zip
		if($downloadZip)
		{
			$pdfDir = $this->exportDir . "download/";
			$pdfUrl = $this->exportMediaUrl  . "download/";
		}
        if (!file_exists($pdfDir)) {
            mkdir($pdfDir, 0777);
        } 
        $this->setDownloadableWhenExport();
        $pdfPath = $pdfDir . $filename;
		$pdf->Output($pdfPath, 'F');
        if(file_exists($pdfPath)) {
			$response = array(
                'status' => 'success',
                'message' => 'Pdf have been successfully created!',
                'pdf_url' => $pdfUrl . $filename
            );
		}
        return $response;
	}
    private function getSVGSizeInMM($svgWidthInPixel, $svgHeightInPixel) {
        $dpi = $this->getPngDPI(null);
        return array(
            ($svgWidthInPixel * 25.4) / $dpi,
            ($svgHeightInPixel * 25.4) / $dpi
        );
    }
    protected function filterSvgFont($fonts) {
		$validFont = array();
		$tcpdfFontPath = BP . "lib/internal/TCPDF/fonts/";
		$tcpdfFonts = array();
		$directory = $tcpdfFontPath;
		if( is_dir( $directory ) && $handle = opendir( $directory ) )
		{
			while( ( $file = readdir( $handle ) ) !== false )
			{
				$temp = explode(".", $file);
				if(end($temp) == "php") {
					$tcpdfFonts[] = str_replace(".php", "", $file);
				}
			}
		}
		//Compare font
		foreach($fonts as $font) {
			$fontName = trim(strtolower($font)); 
			if(in_array($fontName, $tcpdfFonts)) {
				$validFont[] = $fontName;
			}
		}
		return $validFont;
	}
}