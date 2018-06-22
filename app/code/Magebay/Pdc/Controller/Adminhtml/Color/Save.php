<?php
namespace Magebay\Pdc\Controller\Adminhtml\Color;
class Save extends \Magebay\Pdc\Controller\Adminhtml\Color
{
    protected $resultPageFactory;
    protected $resultForwardFactory;
    protected $resultRedirectFactory;
    protected $pdcHelper;
    protected $colorModel;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magebay\Pdc\Model\Color $colorModel,
        \Magebay\Pdc\Helper\Data $pdcHelper
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->colorModel = $colorModel;
        $this->pdcHelper = $pdcHelper;
        parent::__construct($context, $coreRegistry);
    }
    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $hexcode = $data['color_code'];
        $data['color_code'] = strtoupper($hexcode);
        $data['color_code'] = str_replace("#", '', $data['color_code']);
        
		if ($data) {
            $model = $this->colorModel;
            $model->setData($data)->setId($this->getRequest()->getParam('color_id'));
            try {
                $model->save();
                $this->messageManager->addSuccess(__('Color had been saved successfully!'));
                // clear previously saved data from session
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // save data in session
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                // redirect to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('color_id')]);
            }
        }
        $this->messageManager->addError("Unable to save color. Something might be wrong!");
        return $resultRedirect->setPath('*/*/');
    }
}
