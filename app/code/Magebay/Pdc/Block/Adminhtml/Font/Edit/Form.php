<?php
namespace Magebay\Pdc\Block\Adminhtml\Font\Edit;

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
        $this->setId('font_form');
        $this->setTitle(__('Font Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('pdc_font');

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
            $fieldset->addField('font_id', 'hidden', ['name' => 'font_id']);
        }

        $fieldset->addField(
            'dispay_text',
            'text',
            [
                'name' => 'dispay_text', 
                'label' => __('Display Text'), 
                'title' => __('Display Text'), 
                'required' => false,
                'note' => 'Leave empty to use font name',
            ]
        );
        $fieldset->addField(
            'filename',
            'file',
            [
                'title' => __('Font File'),
                'label' => __('Font File'),
                'name' => 'filename',
                'required' => false,
                'note' => 'Allow fonts type: .ttf, .otf, .woff',
            ]
        );
        $fieldset->addField(
            'file_name_old',
            'hidden',
            [
                'name' => 'file_name_old',
                'required' => false,
            ]
        );
        if (!$model->getId()) {
            $model->setData('status', '1');
        }
        $formData = $model->getData();
        if(isset($formData['original_filename']) && isset($formData['original_filename'])) {
            $formData['filename'] = $formData['original_filename'];
			$formData['file_name_old'] = $formData['original_filename'];
        }
        $form->setValues($formData);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
