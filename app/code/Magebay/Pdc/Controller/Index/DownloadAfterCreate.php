<?php
namespace Magebay\Pdc\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;

class DownloadAfterCreate extends \Magento\Framework\App\Action\Action
{

	protected $resultRawFactory;
	protected $fileFactory;
	public function __construct(
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->resultRawFactory      = $resultRawFactory;
        $this->fileFactory           = $fileFactory;
        parent::__construct($context);
    }
    public function execute()
    {
		$fileName = $this->getRequest()->getParam('file-name','');
		$type = $this->getRequest()->getParam('type','');
		if($fileName == '' || $type == '')
		{
			return false;
		}
		$content['type'] = 'filename';
		$content['value'] = '/pdp/export/'.$type.'/'.$fileName;
        $this->fileFactory->create(
            $fileName,
            $content, //content here. it can be null and set later 
            DirectoryList::MEDIA,
            'application/octet-stream'
        );
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw;
    }
}