<?php
namespace Magebay\Pdc\Block\Adminhtml\Color\Edit;

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
        $this->setId('color_form');
        $this->setTitle(__('Color Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('pdc_color');

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
            $fieldset->addField('color_id', 'hidden', ['name' => 'color_id']);
        }

        $fieldset->addField(
            'color_name',
            'text',
            [
                'name' => 'color_name', 
                'label' => __('Color Name'), 
                'title' => __('Color Name'), 
                'required' => false,
            ]
        );
        $fieldset->addField(
            'color_code',
            'text',
            [
                'name' => 'color_code', 
                'label' => __('Color Hexcode'), 
                'title' => __('Color Hexcode'), 
                'required' => true,
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
            ]
        );
        if (!$model->getId()) {
            $model->setData('status', '1');
        }
        $formData = $model->getData();
        $form->setValues($formData);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
