<?php
 
namespace Magebay\Pdc\Controller\Index;
 
use Magento\Framework\App\Action\Context;

require_once(BP . "/lib/instagram/instagram.php");
class InstagramNext extends \Magento\Framework\App\Action\Action
{
	protected $_scopeConfig;
	protected $_storeManager;
	protected $_viewContext;
    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);
    }
	public function execute()
    {
		$url = $this->getRequest()->getParam('url','');
		\InstagramUploader::nextPage($url);
    }
    
}
 