<?php
namespace Magebay\Pdc\Controller\Designarea;

class Index extends \Magento\Framework\App\Action\Action {
    public function execute() {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        return $resultPage;
    }
}
