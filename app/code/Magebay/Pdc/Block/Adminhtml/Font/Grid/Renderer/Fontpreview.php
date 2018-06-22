<?php
namespace Magebay\Pdc\Block\Adminhtml\Font\Grid\Renderer;
use Magento\Store\Model\StoreManagerInterface;
class Fontpreview extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $storeManager;
	protected $pdcHelper;
    protected $_pdcFonts;
    public function __construct(
        \Magento\Backend\Block\Context $context,
        StoreManagerInterface $storeManager,
		\Magebay\Pdc\Helper\Data $pdcHelper,
        \Magebay\Pdc\Helper\Font $pdcFonts,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
		$this->pdcHelper = $pdcHelper;
        $this->_pdcFonts = $pdcFonts;
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        //\Zend_Debug::dump($row->getData());
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);        
        $displayText = $row->getDispayText();
        if($displayText == "") {
            $displayText = $row->getName();
        }
		$correctfont = true;
		if(trim($row->getOriginalFilename()) == '')
		{
			$correctfont = false;
		}
		if($correctfont && $row->getOriginalFilename() != '')
		{
			$fontName = $row->getName();
			$path = $this->pdcHelper->getMediaBaseDir() . "fonts/".$row->getOriginalFilename();
			$dataFonts = $this->_pdcFonts->getPdcFontInfo($path);
			if(isset($dataFonts[1]) && isset($dataFonts[2]))
			{
				$correctFontName = $dataFonts[1];
				if($fontName != $correctFontName)
				{
					$correctfont = false;
				}
			}
		}
		$fileName = $row->getOriginalFilename() != '' ? $row->getOriginalFilename() : $row->getName() . '.' . $row->getExt();
		$out = '<style type="text/css">';
		$out .= '@font-face {';
			$out .= 'font-family: "'. $row->getName() .'"';
			$fontPath = $mediaUrl . 'pdp/fonts/' . $fileName;
			$out .= ';src: url("' . $fontPath .'")';
		
		$out .= '}';
		$out .= '</style>'; 
		if($correctfont)
		{
			$out .= '<span style="font-family: '. $row->getName() .'">'. $displayText .'</span>';
		}
		else
		{
			$out .= '<span style="font-family: '. $row->getName() .'">'. $displayText .'(*)</span>';
		}
        
        return $out;
        
    }
}
