<?php

namespace Magebay\Pdc\Controller\Adminhtml\Image;

class Grid extends \Magebay\Pdc\Controller\Adminhtml\Image
{
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
