<?php
namespace Magebay\Pdc\Controller\Adminhtml\Font;
class Save extends \Magebay\Pdc\Controller\Adminhtml\Font
{
    protected $resultPageFactory;
    protected $resultForwardFactory;
    protected $resultRedirectFactory;
    protected $fontsModel;
    //Upload image field
    protected $adapterFactory;
    protected $pdcHelper;
    protected $_pdcPploader;
    protected $_pdcFonts;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magebay\Pdc\Model\Fonts $fontsModel,
        \Magebay\Pdc\Model\Upload $uploaderFactory,
        \Magebay\Pdc\Helper\Data $pdcHelper,
        \Magebay\Pdc\Helper\Font $pdcFonts
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->fontsModel = $fontsModel;
        $this->pdcHelper = $pdcHelper;
        $this->_pdcPploader = $uploaderFactory;
        $this->_pdcFonts = $pdcFonts;
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
		$currentId = $this->getRequest()->getParam('font_id',0);
		$path = $this->pdcHelper->getMediaBaseDir() . "fonts";
		$errorConverName = false;
        if ($data) {
			try
                {
					//if add new 
					$allowFonts = array("ttf", "otf", "fnt", "fon", "woff", "dfont");
					$resultUpload = $this->_pdcPploader->uploadFileAndGetName('filename',$path,array(),'',$allowFonts,false);
					$fileName = '';
					if(isset($resultUpload['status']) && $resultUpload['status'] == 'success')
					{
						$fileName = $resultUpload['file'];
					}
					if($fileName != '')
					{
						$fullPath = $path. '/'.$fileName;
						$dataFonts = $this->_pdcFonts->getPdcFontInfo($fullPath);
						$fnameArr = explode(".",$fileName);
						$data['ext'] = $fnameArr[1];
						$data['name'] = $fnameArr[0]; 
						if(isset($dataFonts[1]))
						{
							$tempDataName = $dataFonts[1]. ' '.$dataFonts[2];
							$arTempDataNames = explode(' ',$tempDataName);
							foreach($arTempDataNames as $arTempDataName)
							{
								if((float)$arTempDataName > 0)
								{
									$errorConverName = true;
									break;
								}
							}
							if($errorConverName || strlen($dataFonts[1]. ' '.$dataFonts[2]) > 30)
							{
								$errorConverName = true;
							}
							else
							{
								$data['name'] = $dataFonts[1]; 
								if(isset($data['dispay_text']) && $data['dispay_text'] != '') {
									
								}
								else
								{
									$data['dispay_text'] = $dataFonts[1];
								}
							}
							
						}
						$data['original_filename'] = $fileName;
						if($data['dispay_text'] == '')
						{
							$tempFontName = explode('.', $data['original_filename']);
							$data['dispay_text'] = $tempFontName[0];
						}
					}
					else
					{
						$this->messageManager->addError(__('Please upload collect font!'));
						// save data in session
						$this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
						// redirect to edit form
						return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('font_id')]);
					}
                }
                catch (\Exception $e)
                {
					// display error message
                    $this->messageManager->addError($e->getMessage());
                    // save data in session
                    $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                    // redirect to edit form
                    return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('font_id')]);
                }
            $model = $this->fontsModel;
			if($currentId > 0 && !isset($data['ext']))
			{
				$oldData = $model->load($currentId)->getData();
				$fullPath = $path. '/'.$oldData['name'] .'.'.$oldData['ext'];
				if(trim($oldData['original_filename']) != '')
				{
					$fullPath = $path. '/'.$oldData['original_filename'];
				}
				$dataFonts = $this->_pdcFonts->getPdcFontInfo($fullPath);
				$data['name'] =  $oldData['name'];
				if(isset($data['file_name_old']) && $data['file_name_old'] != '')
				{
					$tempFontName = explode('.', $data['file_name_old']);
					$data['name'] = $tempFontName[0];
					echo $data['name'];
				}
				if(isset($dataFonts[1]) && isset($dataFonts[2]))
				{
					$tempDataName = $dataFonts[1]. ' '.$dataFonts[2];
					$arTempDataNames = explode(' ',$tempDataName);
					foreach($arTempDataNames as $arTempDataName)
					{
						if((float)$arTempDataName > 0)
						{
							$errorConverName = true;
							break;
						}
					}
					if($errorConverName || strlen($dataFonts[1]. ' '.$dataFonts[2]) > 30)
					{
						$errorConverName = true;
					}
					else
					{
						$data['name'] = $dataFonts[1]; 
						if(isset($data['dispay_text']) && $data['dispay_text'] != '') {
						
						}
						else
						{
							$data['dispay_text'] = $dataFonts[1];
						}
					}
					
				}
				$data['ext'] = $oldData['ext'];
				$data['original_filename'] = $oldData['original_filename'];
				//fix for old data
				if(trim($data['original_filename']) == '')
				{
					$data['original_filename'] = $oldData['name'].'.'.$data['ext'];
				}
				if(!isset($data['dispay_text']) || (isset($data['dispay_text']) && $data['dispay_text'] == ''))
				{
					$tempFontName = explode('.', $data['original_filename']);
					$data['dispay_text'] = $tempFontName[0];
				}
				
			}
            if(isset($data['name']) && $data['name'] != "") {
                $model->setData($data)->setId($this->getRequest()->getParam('font_id'));    
            } else {                
                $this->messageManager->addError("Please choose font to upload.");
                // save data in session
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                // redirect to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('font_id')]);
            }
            try {
                // save the data
                $model->save();
                // display success message
				if($errorConverName)
				{
					$this->messageManager->addSuccess(__('Font had been saved successfully! But system can not convert font to be compatible with font in computer'));
				}
				else
				{
					$this->messageManager->addSuccess(__('Font had been saved successfully!'));
				}
                
                // clear previously saved data from session
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                // save data in session
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                // redirect to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('font_id')]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
