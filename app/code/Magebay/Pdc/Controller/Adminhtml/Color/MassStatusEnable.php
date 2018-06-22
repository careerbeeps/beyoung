<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magebay\Pdc\Controller\Adminhtml\Color;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;

/**
 * Class MassStatusEnable
 */
class MassStatusEnable extends \Magento\Backend\App\Action
{
    protected $colorModel;

    public function __construct(Context $context, \Magebay\Pdc\Model\Color $colorModel)
    {
        $this->colorModel = $colorModel;
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
        if(isset($params['color_ids']) && $params['color_ids']) {
            foreach($params['color_ids'] as $categoryId) {
                $this->colorModel->load($categoryId)->setStatus(1)->save();
            }
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been enable.', count($params['color_ids'])));   
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
