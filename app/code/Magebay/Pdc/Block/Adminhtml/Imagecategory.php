<?php
namespace Magebay\Pdc\Block\Adminhtml;
class Imagecategory extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Magebay_Pdc';
        $this->_controller = 'adminhtml_imagecategory';
        $this->_headerText = __('Image Categories');
        $this->_addButtonLabel = __('Add New Category');
        parent::_construct();
    }
}
