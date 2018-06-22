<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magebay\Pdc\Controller\Adminhtml\Font;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;

/**
 * Class MassStatusDisable
 */
class MassStatusDisable extends \Magento\Backend\App\Action
{
    protected $fontsModel;

    public function __construct(Context $context, \Magebay\Pdc\Model\Fonts $fontsModel)
    {
        $this->fontsModel = $fontsModel;
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
        if(isset($params['font_ids']) && $params['font_ids']) {
            foreach($params['font_ids'] as $categoryId) {
                $this->fontsModel->load($categoryId)->setStatus(2)->save();
            }
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been changed.', count($params['font_ids'])));   
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
