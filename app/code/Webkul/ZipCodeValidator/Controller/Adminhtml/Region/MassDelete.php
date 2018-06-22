<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_ZipCodeValidator
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\ZipCodeValidator\Controller\Adminhtml\Region;

use Magento\Backend\App\Action\Context;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var regionCollection
     */
    private $regionCollection;

    /**
     * @var zipcodeCollection
     */

    private $zipcodeCollection;

    /**
     * @param Context $context
     * @param \Webkul\ZipCodeValidator\Model\ResourceModel\Region\CollectionFactory $regionCollection
     * @param \Webkul\ZipCodeValidator\Model\ResourceModel\Zipcode\CollectionFactory $zipcodeCollection
     */
    public function __construct(
        Context $context,
        \Webkul\ZipCodeValidator\Model\ResourceModel\Region\CollectionFactory $regionCollection,
        \Webkul\ZipCodeValidator\Model\ResourceModel\Zipcode\CollectionFactory $zipcodeCollection
    ) {
        $this->regionCollection = $regionCollection;
        $this->_zipcodeCollection = $zipcodeCollection;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_ZipCodeValidator::region');
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $count=0;
            $data=$this->getRequest()->getParams();
            if (isset($data['selected'])) {
                $regionIds=$data['selected'];
                $regionCollection = $this->regionCollection->create()
                    ->addFieldToFilter('id', ['in'=> $regionIds]);
                if ($regionCollection->getSize()) {
                    foreach ($regionCollection as $region) {
                        $count++;
                        $this->removeItem($region);
                    }
                    $zipcodeCollection = $this->_zipcodeCollection->create()
                        ->addFieldToFilter('region_id', ['in'=> $regionIds]);
                    if ($zipcodeCollection->getSize()) {
                        foreach ($zipcodeCollection as $zipcode) {
                            $this->removeItem($zipcode);
                        }
                    }
                    $this->messageManager->addSuccess(__("%1 Region(s) deleted succesfully", $count));
                }
            } else {
                $this->messageManager->addError(__("Region Id(s) Invalid"));
            }
        } catch (\Exception $e) {
                $this->messageManager->addError(__("Something Went Wrong!!!"));
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Remove Item
     *
     * @param object $item
     */
    private function removeItem($item)
    {
        $item->delete();
    }
}
