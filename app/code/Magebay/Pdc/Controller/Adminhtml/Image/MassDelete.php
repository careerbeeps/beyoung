<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magebay\Pdc\Controller\Adminhtml\Image;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;

/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action
{
    protected $imageModel;

    public function __construct(Context $context, \Magebay\Pdc\Model\Image $imageModel)
    {
        $this->imageModel = $imageModel;
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
        if(isset($params['image_ids']) && $params['image_ids']) {
            foreach($params['image_ids'] as $categoryId) {
                $this->imageModel->load($categoryId)->delete();
            }
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', count($params['image_ids'])));   
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
