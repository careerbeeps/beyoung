<?php
namespace Magebay\Pdc\Block\Adminhtml;
class Image extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Magebay_Pdc';
        $this->_controller = 'adminhtml_image';
        $this->_headerText = __('Manage Image');
        $this->_addButtonLabel = __('Add New Image');
        // Add Import button to Magento Backend toolbar
        $this->buttonList->add(
            'import',
            [
                'label' => __('Import Images'),
                'class' => 'add primary',
                'onclick' => "setLocation('{$this->getUrl('*/*/import')}')",
            ],
            10
        );
        parent::_construct();
    }
}
