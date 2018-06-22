<?php
namespace Magebay\Pdc\Controller\Designarea;

class Productinfo extends \Magento\Framework\App\Action\Action {
    protected $pdcSideModel;
    protected $productStatusModel;
	protected $_resultJsonFactory;
	protected $_imagecategory;
	protected $_pdpcolor;
	protected $_fonts;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magebay\Pdc\Model\Pdpside $pdpside,
        \Magebay\Pdc\Model\Productstatus $productStatus,
        \Magebay\Pdc\Model\ImagecategoryFactory $imagecategory,
        \Magebay\Pdc\Model\ColorFactory $pdpcolor,
        \Magebay\Pdc\Model\FontsFactory $fonts
    ) {
		$this->_resultJsonFactory = $resultJsonFactory;
        $this->pdcSideModel = $pdpside;
        $this->productStatusModel = $productStatus;
        $this->_imagecategory = $imagecategory;
        $this->_pdpcolor = $pdpcolor;
        $this->_fonts = $fonts;
        parent::__construct($context);
    }
    public function execute() {
        $response = array(
            'status' => 'error',
            'message' => 'Can not get product info. Something went worng!'
        );
        try {
            $responseData = array();
            $productId = $this->getRequest()->getParam("productid");
            //Get product sides
            $responseData['sides'] = array();
            $sides = $this->pdcSideModel->getDesignSides($productId);
            foreach($sides as $side) {
                $responseData['sides'][$side->getId()] = $side->getData();
                    
            }
            //Get product status - config
            $responseData['productinfo'] = array(
                'status' => "2",
                'product_id' => $productId,
                'note' => array(
				    'show_price' => "2",
				    'text_price' => "0",
				    'clipart_price' => "0",
                    'default_color' => '#000',
                    'default_fontsize' => "25",
                    'default_fontheight' => "1",
                    'auto_replace_pattern' => "2",
                    'enable_upload_plugin' => "1",
                    'enable_clipart_plugin' => "1",
                    'enable_background_plugin' => "1",
                    'enable_shape_plugin' => "1",
                    'enable_frame_plugin' => "2",
                    'enable_facebook_plugin' => "2",
                    'enable_instagram_plugin' => "2",
                    'enable_qrcode_plugin' => "2",
                    'enable_colorpicker_plugin' => "1",
                    'enable_curvedtext_plugin' => "2",
                    'enable_image_plugin' => "1",
                    'enable_product_design_tab' => "1",
                    'enable_elements_tab' => "1",
                    'enable_upload_tab' => "1",
                    'enable_text_tab' => "1",
                    'enable_layer_tab' => "1",
                    'enable_info_tab' => "2",
                    'enable_download_btn' => "1",
                    'enable_share_btn' => "2",
                    'enable_reset_btn' => "1",
                    'enable_zoom_btn' => "1"
                )	
            );
            $_productInfoFromDb = $this->productStatusModel->getConfigNote($productId);
            if(isset($_productInfoFromDb['id'])) {
                $responseData['productinfo'] = $_productInfoFromDb; 
            }
			//get image category 
			$imageCatModel = $this->_imagecategory->create();
			$imgCats = array();
			$imgCollection = $imageCatModel->getCollection()
				->addFieldToSelect(array('id','position','title','image_types'))
				->addFieldToFilter('status',1);
			if(count($imgCollection))
			{
				$imgCats = $imgCollection->getData();
			}
			$responseData['imagecats'] = $imgCats;
			//get all color
			$colorModel = $this->_pdpcolor->create();
			$colorCollection = $colorModel->getCollection()
				->addFieldToFilter('status',1);
			$colorItems = array();
			if(count($colorCollection))
			{
				$colorItems = $colorCollection->getData();
			}
			$responseData['coloritems'] = $colorItems;
			//get all fonts
			$fontModel = $this->_fonts->create();
			$fontItems = array();
			$fontCollection =  $fontModel->getCollection()
				->addFieldToFilter('status',1);
			if(count($fontCollection))
			{ 
				foreach($fontCollection as $fontCollect)
				{
					$fontItem = $fontCollect->getData();
					$fontItem['display_text'] = $fontCollect->getDispayText() ? $fontCollect->getDispayText() : $fontCollect->getName();
					$fontItems[] = $fontItem;
				}
			}
			$responseData['fontitems'] = $fontItems;
            if(!empty($responseData)) {
				$responseData['productinfo']['traslate'] = array(
					'pdc_popup_label' => __('Product Designer Canvas - X3'),
					'pdc_popup_gene_info' => __('General Information'),
					'pdc_popup_enable_pdp' => __('Enable Design Tool'),
					'pdc_popup_yes' => __('Yes'),
					'pdc_popup_no' => __('No'),
					'pdc_popup_design_side' => __('Design Sides'),
					'pdc_popup_new_side' => __('Add New Side'),
					'pdc_popup_side_label' => __('Side Label'),
					'pdc_popup_cavans_side' => __('Canvas Size'),
					'pdc_popup_position' => __('Position'),
					'pdc_popup_action' => __('Action'),
					'pdc_popup_edit' => __('Edit'),
					'pdc_popup_delete' => __('Delete'),
					'pdc_popup_temp_lib' => __('Template Library'),
					'pdc_popup_add_product_color' => __('Add Product Color'),
					'pdc_popup_view_product_color' => __('View Product Colors'),
					'pdc_popup_limit_img_color' => __('Limit Images, Colors or Fonts - Developing'),
					'pdc_popup_label_advance_option' => __('Advanced Options'),
					'pdc_popup_show_options' => __('Show Options'),
					'pdc_popup_hide_options' => __('Hide Options'),
					'pdc_popup_disable_tab' => __('Enable/Disable Tab, Buttons'),
					'pdc_popup_product_design' => __('Enable Product Design Tab'),
					'pdc_popup_auto_replace' => __('Auto Replace Pattern'),
					'pdc_popup_enabel_element' => __('Enable Elements Tab'),
					'pdc_popup_upload_tab' => __('Enable Upload Tab'),
					'pdc_popup_text_tab' => __('Enable Text Tab'),
					'pdc_popup_layer_tab' => __('Enable Layer Tab'),
					'pdc_popup_infor_tab' => __('Enable Info Tab'),
					'pdc_popup_download_button' => __('Enable Download Button'),
					'pdc_popup_share_button' => __('Enable Share Button'),
					'pdc_popup_reset_button' => __('Enable Reset Button'),
					'pdc_popup_zoon_button' => __('Enable Zoom Button'),
					'pdc_popup_backgroud_plugin' => __('Enable Background Plugin'),
					'pdc_popup_image_plugin' => __('Enable Image Plugin'),
					'pdc_popup_upload_plugin' => __('Enable Upload Plugin'),
					'pdc_popup_clipart_plugin' => __('Enable Clipart Plugin'),
					'pdc_popup_shape_plugin' => __('Enable Shape Plugin'),
					'pdc_popup_upload_facebook' => __('Enable Upload Facebook Photo'),
					'pdc_popup_upload_instagram' => __('Enable Upload Instagram Plugin'),
					'pdc_popup_mask_plugin' => __('Enable Frame/Mask Plugin'),
					'pdc_popup_qr_plugin' => __('Enable QR-Code Plugin'),
					'pdc_popup_picker_plugin' => __('Enable ColorPicker Plugin'),
					'pdc_popup_en_dis_plugin' => __('Enable/Disable Plugin'),
				);
				$responseData['productinfo']['color_traslate'] = array(
					'side_label' => __('Side Label'),
					'add_color_label' => __('Add Product Color'),
					'upload_bg_image' => __('Upload Background Image'),
					'upload_mask_image' => __('Upload Mask Image'),
					'color_name' => __('Color Name'),
					'color_hex' => __('Color Hexcode'),
					'list_color_label' => __('Product Color Lists'),
					'img_bg_mask' => __('Background Image & Mask Image'),
					'color_position' => __('Position'),
					'color_action' => __('Action')
				);
				$responseData['productinfo']['temp_traslate'] = array(
					'label'=> __('Create New Template'),
					'delete'=> __('Delete'),
					'edit'=> __('Edit'),
					'no'=> __('No'),
					'yes'=> __('Yes'),
					'action'=> __('Action'),
					'is_default'=> __('Is Default'),
					'status'=> __('Status'),
					'position'=> __('Position'),
					'template_name'=> __('Template Name'),
					'template_preview'=> __('Template Preview'),
					'product_color_list'=> __('Product Color List'),
					'template_lib'=> __('Template Library'),
				);
                $response = array(
                    'status' => 'success',
                    'message' => 'Get product info successfully!',
                    'data' => $responseData
                );
            } 
        } catch(\Exception $error) {
              
        }
		$resultJson = $this->_resultJsonFactory->create();
		return $resultJson->setData($response);
    }
}
