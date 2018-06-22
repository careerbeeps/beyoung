<?php
namespace Magebay\Pdc\Block\Adminhtml;
class Color extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Magebay_Pdc';
        $this->_controller = 'adminhtml_color';
        $this->_headerText = __('Manage Color');
        $this->_addButtonLabel = __('Add New Color');
        parent::_construct();
    }
}
