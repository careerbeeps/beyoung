<?php
namespace Magebay\Pdc\Block\Adminhtml;
class Font extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Magebay_Pdc';
        $this->_controller = 'adminhtml_font';
        $this->_headerText = __('Manage Font');
        $this->_addButtonLabel = __('Add New Font');
        parent::_construct();
    }
}
