<?php
namespace Magebay\Pdc\Controller\Templates;
class Side extends \Magento\Framework\App\Action\Action {
    public function execute() {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        return $resultPage;
    }
}