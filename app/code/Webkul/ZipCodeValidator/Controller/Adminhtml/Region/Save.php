<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_ZipcodeValidator
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\ZipCodeValidator\Controller\Adminhtml\Region;

use Magento\Backend\App\Action;
use Magento\Framework\Filesystem\Driver\File;

class Save extends Action
{
    /**
     * @var \Webkul\ZipCodeValidator\Model\RegionFactory
     */
    private $region;

    /**
     * @var \Webkul\ZipCodeValidator\Model\ZipcodeFactory
     */
    private $zipcode;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $fileUploader;

    /**
     * @var time()
     */
    private $time;
   // private $time = time();
    /**
     * @var success
     */
    private $success =false;
    /**
     * Filesystem driver to allow reading of module.xml files which live outside of app/code
     *
     * @var DriverInterface
     */
    private $filesystemDriver;

    /**
     * @param Action\Context                                  $context
     * @param Webkul\ZipCodeValidator\Model\RegionFactory   $region
     * @param Webkul\ZipCodeValidator\Model\ZipcodeFactory  $zipcode
     * @param Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        Action\Context $context,
        \Webkul\ZipCodeValidator\Model\RegionFactory $region,
        \Webkul\ZipCodeValidator\Model\ZipcodeFactory $zipcode,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        File $filesystemDriver
    ) {
		$this->time = time();
        $this->region = $region;
        $this->zipcode = $zipcode;
        $this->fileUploader = $fileUploaderFactory;
        $this->filesystemDriver = $filesystemDriver;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_ZipcodeValidator::region');
    }

    /**
     * Save action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        if (isset($data['region_id'])) {
            $msg = $this->updateRegion($data);
            if ($msg) {
                foreach ($msg as $err) {
                    $this->messageManager->addError(__($err));
                }
                return $this->resultRedirectFactory->create()
                    ->setPath('*/*/edit', ['id' => $data['region_id']]);
            }
        } else {
            $regionId = 0;
            $collection = $this->region
                ->create()
                ->getCollection();
            foreach ($collection as $key => $value) {
                if (strcasecmp($data['region_name'], $value->getRegionName()) == 0) {
                    $regionId = $value->getId();
                }
            }
            if (!$regionId) {
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $region = $this->region->create()->setData($data)->save();
                $regionId = $region->getId();
                $this->success=__("Region Added Successfully");
            } else {
                $this->messageManager
                    ->addError(__('Region "%1" already exist.', $data['region_name']));
                return $this->resultRedirectFactory->create()->setPath('*/*/index');
            }
            $msg = $this->processCsvData($regionId);
            if ($msg) {
                foreach ($msg as $err) {
                    $this->messageManager->addError(__($err));
                }
                if ($regionId) {
                    return $this->resultRedirectFactory->create()
                    ->setPath('*/*/edit', ['id' => $regionId]);
                }
            }
        }
        $this->messageManager->addSuccess(__($this->success));
        return $this->resultRedirectFactory->create()->setPath('*/*/index');
    }
    /**
     * Update Region Details
     * @return array
     */
    private function updateRegion($params)
    {
        $msg = $this->processCsvData($params['region_id']);
        if ($msg) {
            return $msg;
        }
        $this->region->create()
            ->load($params['region_id'])
            ->setRegionName($params['region_name'])
            ->setStatus($params['status'])
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->save();
        $this->success=__("Region Updated Successfully");
        return $msg;
    }
    /**
     * process csv data
     * @param integer $regionId
     * @return array
     */
    private function processCsvData($regionId)
    {
        $msg = [];
        $data = $this->getRequest()->getParams();
        $time = $this->time;
        // Checking csv file
        try {
            $csvUploader = $this->fileUploader->create(['fileId' => 'zipcodes-csv']);
            $csvUploader->setAllowedExtensions(['csv']);
            $validateData = $csvUploader->validateFile();
            $csvFilePath = $validateData['tmp_name'];
            $csvFile = $validateData['name'];
            $csvExt = explode('.', $csvFile);
            $csvFile = $time.'.'.$csvExt[1];
        } catch (\Exception $e) {
            $msg[]=$e->getMessage();
        }
        $csvData = [];
        if (isset($csvFilePath)) {
            // Reading csv file
            $file = $this->filesystemDriver->fileOpen($csvFilePath, 'r');
            while (!$this->filesystemDriver->endOfFile($file)) {
                $csvData[] = $this->filesystemDriver->fileGetCsv($file, 1024);
            }
            $this->filesystemDriver->fileClose($file);
        }
        if ($csvData) {
            $msg = $this->saveCsvData($csvData, $regionId);
        } elseif (!isset($data['region_id'])) {
            $check = $this->checkZipcode($data['region_name'], $regionId);
            if (!$check) {
                $this->zipcode->create()
                    ->setRegionZipcode($data['region_name'])
                    ->setRegionId($regionId)
                    ->setCreatedAt(date('Y-m-d H:i:s'))
                    ->setUpdatedAt(date('Y-m-d H:i:s'))
                    ->save();
            }
        }
        return $msg;
    }
    /**
     * save csv zipcodes
     * @param array $csvData
     * @param integet $regionId
     * @return array
     */
    private function saveCsvData($csvData, $regionId)
    {
        $msg = [];
        foreach ($csvData as $key => $zipcode) {
			if($zipcode!='')
			{	
				$row = $key+1;
				$check = $this->checkZipcode($zipcode, $regionId);
				if ($check) {
					$msg[$key] = __("Skipped row %1.<br>%2 already exists.", $row, $zipcode[0]);
				} elseif ($zipcode[0]) {
					$zipData['region_id'] = $regionId;
					$zipData['region_zipcode'] = $zipcode[0];
					$zipData['created_at'] = date('Y-m-d H:i:s');
					$zipData['updated_at'] = date('Y-m-d H:i:s');
					$this->saveZipcode($zipData);
				}
			}	
        }
        return $msg;
    }
    /**
     * check zipcode already saved or not
     * @param string $zipcode
     * @param integer $regionId
     * @return integer
     */
    private function checkZipcode($zipcode, $regionId)
    {
        $collection = $this->zipcode->create()
            ->getCollection()
            ->AddFieldToFilter('region_id', $regionId)
            ->AddFieldToFilter('region_zipcode', $zipcode);
        return count($collection);
    }
    /**
     * save zipcodes
     * @return void
     */
    private function saveZipcode($data)
    {
        $this->zipcode->create()->setData($data)->save();
    }
}
