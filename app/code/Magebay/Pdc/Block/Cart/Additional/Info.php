<?php 
namespace Magebay\Pdc\Block\Cart\Additional;
use Magento\Checkout\Block\Cart\Additional\Info as CartInfo;
class Info extends CartInfo {
    public $pdcHelper;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context, 
        \Magebay\Pdc\Helper\Data $pdcHelper,
        array $data = []
    )
    {
        $this->pdcHelper = $pdcHelper;
        return parent::__construct($context, $data);
    }
}