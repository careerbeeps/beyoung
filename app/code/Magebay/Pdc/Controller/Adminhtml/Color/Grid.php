<?php

namespace Magebay\Pdc\Controller\Adminhtml\Color;

class Grid extends \Magebay\Pdc\Controller\Adminhtml\Color
{
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
