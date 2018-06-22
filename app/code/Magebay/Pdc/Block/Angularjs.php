<?php 
namespace Magebay\Pdc\Block;
use Magento\Store\Model\ScopeInterface;
use Magebay\Pdc\Helper\Data as PdcHelper;
class Angularjs extends \Magento\Framework\View\Element\Template {
    public $assetRepository;
    public $pdcHelper;
	protected $_pdcScopeConfig;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context, 
        PdcHelper $pdcHelper,
        array $data = []
    )
    {
        $this->assetRepository = $context->getAssetRepository();
        $this->pdcHelper = $pdcHelper;
        $this->_pdcScopeConfig = $context->getScopeConfig();
        return parent::__construct($context, $data);
    } 
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