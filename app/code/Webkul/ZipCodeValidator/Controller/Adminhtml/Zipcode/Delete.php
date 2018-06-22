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
namespace Webkul\ZipCodeValidator\Controller\Adminhtml\Zipcode;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Webkul\ZipCodeValidator\Model\ResourceModel\Zipcode\CollectionFactory;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_ZipCodeValidator::zipcode');
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $collection = $this->filter
                ->getCollection($this->collectionFactory->create());
            if ($collection->getSize()) {
                foreach ($collection as $region) {
                    if (!isset($regionId)) {
                        $regionId=$region->getRegionId();
                    }
                    $this->remove($region);
                }
                    $this->messageManager->addSuccess(__('Zipcode(s) deleted successfully'));
            } else {
                $this->messageManager->addError(__('No entity selected.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        if (isset($regionId)) {
             return $resultRedirect->setPath('*/zipcode/', ['region_id' => $regionId]);
        } else {
            return $resultRedirect->setPath('*/region/');
        }
    }
    private function remove($region)
    {
        return $region->delete();
    }
}
