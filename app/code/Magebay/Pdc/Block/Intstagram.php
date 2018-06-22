<?php 
namespace Magebay\Pdc\Block;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Store\Model\ScopeInterface;
require_once(BP . "/lib/instagram/instagram.php");
class Intstagram extends \Magento\Framework\View\Element\Template
{
	protected $_scopeConfig;
	protected $_storeManager;
	protected $_viewContext;
	 public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
		\Magento\Framework\View\Element\Template\Context $viewContext,
        array $data = []
    ){
		$this->_viewContext = $viewContext;
        return parent::__construct($context, $data);
    } 
	function getX3JsUlr()
	{
		$asset_repository = $this->_viewContext->getAssetRepository();
        $asset  = $asset_repository->createAsset('Magebay_Pdc::pdc/');
		return $asset->getUrl();
	}
	/**
	* get pdc setting
	**/
	function getFieldSetting($field)
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$scopeConfig = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
		$filedSetting = $scopeConfig->getValue($field,ScopeInterface::SCOPE_STORE); 
		return $filedSetting;
	}
}
