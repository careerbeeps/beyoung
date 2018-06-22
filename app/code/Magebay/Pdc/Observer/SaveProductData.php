<?php
namespace Magebay\Pdc\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class SaveProductData implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return $this
     */
	 protected $_request;
	 protected $_backendSeesion;
	 protected $_filesystem;
	 protected $_admintemplate; 
	 public function __construct(
				\Magento\Framework\App\RequestInterface $request,
				\Magento\Backend\Model\Session $backendSeesion,
				\Magento\Framework\Filesystem $filesystem,
				\Magebay\Pdc\Model\AdmintemplateFactory $admintemplate
			)
		{
			
			$this->_request = $request;
			$this->_backendSeesion = $backendSeesion;
			$this->_filesystem = $filesystem;
			$this->_admintemplate = $admintemplate;
			
		}
    public function execute(Observer $observer)
    {
        $_product = $observer->getEvent()->getProduct();
		$id = $this->_request->getParam('id',0);
		$keyProduct = md5($id);
		$keyProduct1 = $this->_backendSeesion->getPdcProductSession();
		$this->_backendSeesion->setPdcProductSession(md5(time()));
		if($keyProduct1 == $keyProduct)
		{
			$currentThumbnail = $this->_backendSeesion->getPdcTemplateThumbnail();
			$admintemplateModel = $this->_admintemplate->create();
			$newestTemplate = $admintemplateModel->getCollection()
				->addFieldToSelect(array('template_thumbnail'))
				->addFieldToFilter('product_id',$id)
				->setOrder('id','DESC')
				->getFirstItem();
			$templateThumbnail = '';
			if($newestTemplate && $newestTemplate->getId())
			{
				$templateThumbnail = $newestTemplate->getTemplateThumbnail();
			}
			if($templateThumbnail != '' && $templateThumbnail != $currentThumbnail)
			{
				$fileSystem = $this->_filesystem;
				$mediaPath = $fileSystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath();
				$newProductThumbnail = $mediaPath.'pdp/images/thumbnail/'.$templateThumbnail;
				$_product->addImageToMediaGallery($newProductThumbnail, array('image', 'small_image', 'thumbnail'), false, false)
						->save();
			}
		}

        return $this;
    }
}
