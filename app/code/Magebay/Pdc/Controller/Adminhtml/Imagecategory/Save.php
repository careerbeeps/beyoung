<?php
namespace Magebay\Pdc\Controller\Adminhtml\Imagecategory;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Save extends \Magebay\Pdc\Controller\Adminhtml\Imagecategory
{
    protected $resultPageFactory;
    protected $resultForwardFactory;
    protected $resultRedirectFactory;
    protected $imageCategory;
    //Upload image field
    protected $adapterFactory;
    protected $uploaderFactory;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magebay\Pdc\Model\ImagecategoryFactory $_imageCategory,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magebay\Pdc\Model\Upload $uploader
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->imageCategory = $_imageCategory;
        $this->adapterFactory = $adapterFactory;
        $this->uploaderFactory = $uploader;
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
        // check if data sent
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('id');
            $model = $this->imageCategory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This category no longer exists.'));
                
                return $resultRedirect->setPath('*/*/');
            }
            // start block upload image
            $imageFieldName = "thumbnail";
            $imagePath = BP . "/pub/media/pdp/images/";
            $uploadResult = $this->uploaderFactory->uploadFileAndGetName($imageFieldName, $imagePath, $data);
            if(isset($uploadResult['status']) && $uploadResult['status'] == 'success')
			{
				$data['thumbnail'] = $uploadResult['file'];
			}
			else
			{
				$errorMessage = 'Something went wrong. Can not upload image to the server';
                if(isset($uploadResult['message'])) {
                    $errorMesasge = $uploadResult['message'];
					$tempFileName = '';
					if(isset($data['thumbnail']['value']))
					{
						$tempFileName = $data['thumbnail']['value'];
						$tempFileName = str_replace('pdp/images/','',$tempFileName);
					}
					$data['thumbnail'] = $tempFileName;
                    // display error message
                    $this->messageManager->addError($errorMesasge);
                    // save data in session
                    $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                    // redirect to edit form
                    return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                }
			}
            // end block upload image
            // init model and set data

            $model->setData($data);
	         
            // try to save it
            try {
                // save the data
                $model->save();
                // display success message
                $this->messageManager->addSuccess(__('You saved the category.'));
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
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
