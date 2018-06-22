<?php
namespace Magebay\Pdc\Block\Adminhtml\Imagecategory\Edit;

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
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
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
        $this->setId('imagecategory_form');
        $this->setTitle(__('Category Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('pdc_imagecategory');

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
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
            'title',
            'text',
            ['name' => 'title', 'label' => __('Category Title'), 'title' => __('Category Title'), 'required' => true]
        );
        
        $fieldset->addField(
            'image_types',
            'select',
            [
                'label' => __('Image Types'),
                'title' => __('Image Types'),
                'name' => 'image_types',
                'required' => true,
                'options' => [
                    'uncategorized' => __('Uncategorized'), 
                    'image' => __('Image'),
                    'background' => __('Background'),
                    'frame' => __('Mask'),
                    'clipart' => __('Clipart'),
                    'shape' => __('Shape'),
                ]
            ]
        );
        
        $fieldset->addField(
            'thumbnail',
            'image',
            [
            'title' => __('Image'),
            'label' => __('Image'),
            'name' => 'thumbnail',
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
        if(isset($formData['thumbnail']) && $formData['thumbnail']) {
            $imagePrefix = 'pdp/images/';
            $formData['thumbnail'] = $imagePrefix . $formData['thumbnail'];    
        }
        $form->setValues($formData);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
