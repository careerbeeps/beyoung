<?php
namespace Magebay\Pdc\Controller\View;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Designtool extends \Magento\Framework\App\Action\Action {
    public function execute() {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_LAYOUT);
        return $resultPage;
    }
}