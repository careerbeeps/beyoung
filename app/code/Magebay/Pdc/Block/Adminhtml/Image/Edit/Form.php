<?php
namespace Magebay\Pdc\Block\Adminhtml\Image\Edit;

/**
 * Adminhtml cms block edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    protected $imageCategoryFactory; 
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = [],
        \Magebay\Pdc\Model\ImagecategoryFactory $imageCategory
    ) {
        $this->imageCategoryFactory = $imageCategory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('image_form');
        $this->setTitle(__('Image Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('pdc_image');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
        );

        $form->setHtmlIdPrefix('block_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('image_id', 'hidden', ['name' => 'image_id']);
        }

        $fieldset->addField(
            'image_name',
            'text',
            ['name' => 'image_name', 'label' => __('Image Label'), 'title' => __('Image Label'), 'required' => false]
        );
        $fieldset->addField(
            'filename',
            'image',
            [
            'title' => __('Image File'),
            'label' => __('Image File'),
            'name' => 'filename',
            'required' => true,
            'note' => 'Allow image type: jpg, jpeg, gif, png, svg',
            ]
        );
        $imageCategories = $this->imageCategoryFactory->create()->getCategoriesGroup();
        $fieldset->addField(
            'category',
            'select',
            [
                'label' => __('Category'),
                'title' => __('Category'),
                'name' => 'category',
                'required' => true,
                'values' => $imageCategories
            ]
        );
	    $fieldset->addField(
            'price',
            'text',
            [
                'name' => 'price',
                'label' => __('Price'),
                'title' => __('Price'),
                'required' => false,
                'class' => 'validate-number'
            ]
        ); 
        $fieldset->addField(
            'thumbnail',
            'image',
            [
            'title' => __('Thumbnail File'),
            'label' => __('Thumbnail File'),
            'name' => 'thumbnail',
            'required' => false,
            'note' => 'Allow image type: jpg, jpeg, gif, png, svg',
            ]
        );
        $fieldset->addField(
            'position',
            'text',
            [
                'name' => 'position',
                'label' => __('Position'),
                'title' => __('Position'),
                'required' => false,
                'class' => 'validate-number'
            ]
        );
        $fieldset->addField(
            'tag',
            'text',
            [
                'name' => 'tag',
                'label' => __('Tags'),
                'title' => __('Tags'),
                'required' => false,
            ]
        );
        $fieldset->addField(
            'sort_description',
            'textarea',
            [
                'name' => 'sort_description',
                'label' => __('Short Description'),
                'title' => __('Short Description'),
                'required' => false,
            ]
        );
        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'required' => false,
            ]
        );

        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'required' => false,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        if (!$model->getId()) {
            $model->setData('status', '1');
        }
	    //\Zend_Debug::dump($model->getData()); 
        $formData = $model->getData();
        //Image Preview
        $imageFields = array("filename", "thumbnail");
        foreach($imageFields as $imageField) {
            if(isset($formData[$imageField]) && $formData[$imageField]) {
                $imagePrefix = 'pdp/images/artworks/';
                $formData[$imageField] = $imagePrefix . $formData[$imageField];    
            }   
        }
        $form->setValues($formData);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
