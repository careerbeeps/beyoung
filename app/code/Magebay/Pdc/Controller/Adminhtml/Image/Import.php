<?php
/***************************************************
 *    M""""""""`M            dP                    *
 *    Mmmmmm   .M            88                    *
 *    MMMMP  .MMM  dP    dP  88  .dP   .d8888b.    *
 *    MMP  .MMMMM  88    88  88888"    88'  `88    *
 *    M' .MMMMMMM  88.  .88  88  `8b.  88.  .88    *
 *    M         M  `88888P'  dP   `YP  `88888P'    *
 *    MMMMMMMMMMM  [  -*-  Magebay.com  -*-   ]    *
 *                                                 *
 *    * * * * * * * * * * * * * * * * * * * * *    *
 *    * -  Copyright © 2012 - 2016 Magebay  - *    *
 *    *    -  -  All Rights Reserved  -  -    *    *
 *    * * * * * * * * * * * * * * * * * * * * *    *
 * ----------------------------------------------- *
 *
 * @PROJECT    : Product Design Canvas [ Magebay.com ]
 * @AUTHOR     : Zuko
 * @FILE       : Import.php
 * @CREATED    : 11:00 AM , 18/04/2016
 * @DETAIL     :
 * ----------------------------------------------- */

namespace Magebay\Pdc\Controller\Adminhtml\Image;


use Magebay\Pdc\Controller\Adminhtml\Image as ImageBaseController;
use Magebay\Pdc\Helper\ImportHelper;
use Magebay\Pdc\Model\ImageFactory;
//use Magento\Backend\Model\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Import
 * @package Magebay\Pdc\Controller\Adminhtml\Image
 */
class Import extends ImageBaseController
{
    const COLLECTION_REGKEY = 'pdc_current_image_collection';
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magebay\Pdc\Model\ImageFactory
     */
    protected $imageModelFactory;

    /**
     * @var \Magebay\Pdc\Helper\ImportHelper
     */
    public $importHelper;
    
    public function __construct (
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\Registry $coreRegistry,
		\Magebay\Pdc\Model\Upload $uploader,
		ImageFactory $imageFactory,
		ImportHelper $importHelper
	)
    {
        parent::__construct($context, $coreRegistry);
        $this->imageModelFactory = $imageFactory;
		$this->uploaderFactory = $uploader;
        $this->resultPageFactory = $context->getResultFactory();
        $this->importHelper = $importHelper;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute ()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
//        $resultPage = $this->initPage($this->resultPageFactory->create());
        if ($this->getRequest()->isPost())
        {
            $resultPage = $this->handleImportRequest($resultPage);
            return $resultPage;

        }else{
            $this->messageManager->addNotice(
                $this->_objectManager->get('Magento\ImportExport\Helper\Data')->getMaxUploadSizeMessage()
            );

            $resultPage->setActiveMenu('Magebay_Pdc::manage_image');
            $resultPage->getConfig()->getTitle()->prepend(__('Images Manager'));
            $resultPage->getConfig()->getTitle()->prepend(__('Import'));
            $resultPage->addBreadcrumb(__('Import'), __('Import'));
            return $resultPage;
        }
    }

    /**
     * Getting current images data from database
     *
     * @return \Magebay\Pdc\Model\ResourceModel\Image\Collection
     */
    private function getCurrentData ()
    {
        /**
         * @var \Magebay\Pdc\Model\Image $model
         * @var \Magebay\Pdc\Model\ResourceModel\Image\Collection $imageCollection
         */
        $model = $this->imageModelFactory->create();
        $imageCollection = $model->getCollection()->load();
        if (!$this->_coreRegistry->registry(self::COLLECTION_REGKEY))
            $this->_coreRegistry->register(self::COLLECTION_REGKEY, $imageCollection);

        return $this->_coreRegistry->registry(self::COLLECTION_REGKEY);
    }

    /**
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    private function handleImportRequest ($resultPage)
    {
        $cliparts = $this->getCurrentData();
        $oldData = [];
        if (count($cliparts))
        {
            foreach ($cliparts as $clipart)
            {
                $oldData[ $clipart['filename'] ] = $clipart['filename'];
            }
        }
		$imagePath = BP . "/pub/media/pdp/images/upload/";
		$resultUpload = $this->uploaderFactory->uploadFileAndGetName('cvsfile',$imagePath,array(),'',array('csv'));
		$csv_file = '';
		$errorUpload = false;
		if(isset($resultUpload['status']) && $resultUpload['status'] == 'success')
		{
			$csv_file  = $imagePath .$resultUpload['file'];
		}
		else{
			$errorUpload = true;
		}
		if ($errorUpload || ! is_file( $csv_file ) )
        {
			$this->messageManager->addError(__('File not found!'));
            return $this->_redirect('*/*/import');
        }
        if (($handle = fopen( $csv_file, "r")) !== FALSE)
        {
            $data = $this->importHelper->getCsvContents($csv_file);
            if($data){
                /**
                 * @var \Magebay\Pdc\Model\Image
                 */
                $model = $this->imageModelFactory->create();
                $data = $this->importHelper->batchResize($data);
                $newSet = 0;
                foreach ($data as $k => $toImport)
                {

                    if($toImport['import'] != true) continue;
                    //TODO : create model and get id with filter by name

                    if(array_key_exists($toImport['filename'],$oldData))
                    {

                        $toImport['image_id'] = $this->getCurrentData()
                            ->addFieldToFilter('filename',$toImport['filename'])->load()->getFirstItem()->getId();
                    }
                    unset($toImport['import']);
                    $model->setData($toImport)->save();
                    $newSet++;
                }
                try{
                    $model->save();
                    $this->messageManager->addSuccess(__('Import Successful ! . Total '.$newSet.' of '.count($data).' 
                    record(s) was imported.'));
                    return $this->_redirect('*/*/index');

                }catch (LocalizedException $e){
                    $this->messageManager->addError($e->getMessage());
                    return $this->_redirect('*/*/import');
                }
            };
        }
        return $resultPage;
    }
}