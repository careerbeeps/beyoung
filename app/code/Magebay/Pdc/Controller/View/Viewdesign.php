<?php
namespace Magebay\Pdc\Controller\View;

class Viewdesign extends \Magento\Framework\App\Action\Action {
    public function execute() {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_LAYOUT);
        return $resultPage;
    }
}