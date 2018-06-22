<?php
/**
 * =====================================================
 *                  -:- Z-Programing -:-
 * =====================================================
 * @PROJECT    : Product Design Canvas [ Magebay.com ]
 * @AUTHOR     : Zuko
 * @FILE       : Form.php
 * @CREATED    : 2:01 PM , 19/Apr/2016
 * @DETAIL     :
 * =====================================================
 * =====================================================
 **/

namespace Magebay\Pdc\Block\Adminhtml\Image\Import;

use Magento\Backend\Block\Widget\Form\Generic;


/**
 * Class Form
 * @package Magebay\Pdc\Block\Adminhtml\Image\Import
 */
class Form extends Generic
{
    public function __construct (\Magento\Backend\Block\Template\Context $context,
                                 \Magento\Framework\Registry $registry,
                                 \Magento\Framework\Data\FormFactory $formFactory,
                                 array $data)
    {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _construct ()
    {
        parent::_construct();
        $this->setId('block_form');
//        $this->setTitle(__('Block Information'));
    }

    public function _prepareForm ()
    {
        /**
         * @var \Magento\Framework\Data\Form $form
         * @var \Magento\Framework\Data\Form\Element\AbstractElement $fieldset
         */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form',
                        'action' => $this->getUrl('pdc/image/import'),
                        'method' => 'post',
                        'enctype' => 'multipart/form-data']
            ]
        );
        $form->setHtmlIdPrefix('pdc_');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Import Images'), 'class' => 'fieldset-wide']
        );
        $fieldset->addField('cvsfile',
                            'file',
                            [
                                'name' => 'cvsfile',
                                'label' => __('Select File to Import'),
                                'title' => __('Select File to Import'),
                                'required' => true,
                                'class' => 'input-file',
                                'note' => __(
                                    'NOTE:Please upload all images to pdc images folder : media/pdp/images/artworks/* 
                                    before submit this form'
                                ),
                            ]);
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}