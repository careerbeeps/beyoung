<?php

namespace Magebay\Pdc\Controller\Adminhtml\Imagecategory;

class Grid extends \Magebay\Pdc\Controller\Adminhtml\Imagecategory
{
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
