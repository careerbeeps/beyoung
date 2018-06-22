<?php
/**
 * =====================================================
 *                 -:- Z-Programing -:-
 * =====================================================
 * @PROJECT    : Product Design Canvas [ Magebay.com ]
 * @AUTHOR     : Zuko
 * @FILE       : ImportBlock.php
 * @CREATED    : 9:32 AM , 19/Apr/2016
 * @DETAIL     :
 * =====================================================
 * =====================================================
 **/

namespace Magebay\Pdc\Block\Adminhtml\Image;

use Magento\Backend\Block\Widget\Form\Container;

/**
 * Class ImportBlock
 *
 * @package Magebay\Pdc\Block\Adminhtml\Image
 */
class ImportBlock extends Container
{

    public function __construct (\Magento\Backend\Block\Widget\Context $context,
                                 array $data)
    {
        parent::__construct($context, $data);
    }
    protected function _construct ()
    {
        parent::_construct();

        $this->_blockGroup = 'Magebay_Pdc';
        $this->_controller = 'adminhtml_image';
        $this->_mode = 'import';
        $this->buttonList->update('save', 'label', __('Import'));
//        $this->buttonList->remove('back');
        $this->buttonList->remove('reset');
/*        $this->buttonList->update('save', 'label', __('Check Data'));
        $this->buttonList->update('save', 'id', 'upload_button');
        $this->buttonList->update('save', 'onclick', 'varienImport.postToFrame();');
        $this->buttonList->update('save', 'data_attribute', '');*/
    }
    public function getHeaderText ()
    {
        return __('Import Images');
    }
}