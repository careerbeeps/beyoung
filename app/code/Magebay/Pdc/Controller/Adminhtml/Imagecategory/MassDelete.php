<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magebay\Pdc\Controller\Adminhtml\Imagecategory;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;

/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action
{
    protected $imageCategoryFactory;

    public function __construct(Context $context, \Magebay\Pdc\Model\ImagecategoryFactory $imageCategoryFactory)
    {
        $this->imageCategoryFactory = $imageCategoryFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        if(isset($params['imagecategory_ids']) && $params['imagecategory_ids']) {
            $categoryObj = $this->imageCategoryFactory->create();
            foreach($params['imagecategory_ids'] as $categoryId) {
                $categoryObj->load($categoryId)->delete();
            }
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', count($params['imagecategory_ids'])));   
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
