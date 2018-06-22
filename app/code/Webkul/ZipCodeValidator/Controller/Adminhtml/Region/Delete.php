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

class Delete extends \Magento\Backend\App\Action
{

    /**
     * @var region
     */

    private $region;

    /**
     * @var zipcodeCollection
     */

    private $zipcodeCollection;

    /**
     * @param Context $context
     * @param \Webkul\ZipCodeValidator\Model\Region $region
     * @param \Webkul\ZipCodeValidator\Model\ResourceModel\Zipcode\CollectionFactory $zipcodeCollection
     */
    public function __construct(
        Context $context,
        \Webkul\ZipCodeValidator\Model\Region $region,
        \Webkul\ZipCodeValidator\Model\ResourceModel\Zipcode\CollectionFactory $zipcodeCollection
    ) {
        $this->region = $region;
        $this->zipcodeCollection = $zipcodeCollection;
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
            $data=$this->getRequest()->getParams();
            if (isset($data['id'])) {
                $id=$data['id'];
                $region = $this->region->load($id);
                if ($region) {
                    $this->removeItem($region);
                    $zipcodeCollection = $this->zipcodeCollection->create()
                        ->addFieldToFilter('region_id', $id);
                    if ($zipcodeCollection->getSize()) {
                        foreach ($zipcodeCollection as $zipcode) {
                            $this->removeItem($zipcode);
                        }
                    }
                    $this->messageManager->addSuccess(__('Region deleted succesfully'));
                }
            } else {
                $this->messageManager->addError(__('Region Id is Invalid'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(__('Something went wrong !!!'));
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
