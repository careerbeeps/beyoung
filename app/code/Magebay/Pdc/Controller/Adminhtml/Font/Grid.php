<?php

namespace Magebay\Pdc\Controller\Adminhtml\Font;

class Grid extends \Magebay\Pdc\Controller\Adminhtml\Font
{
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
