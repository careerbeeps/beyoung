<?php
namespace Magebay\Pdc\Block\Adminhtml\Color\Grid\Renderer;
use Magento\Store\Model\StoreManagerInterface;
class Colorpreview extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $storeManager;
    public function __construct(
        \Magento\Backend\Block\Context $context,
        StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $val = $row->getColorCode();

        $out = '<center><span style=\'background:#'.$val.'; display: table; padding: 0px; height: 40px; width: 100px; position: relative; border: 0px solid rgb(255, 255, 255);border-radius:100% 0;\'><span style=\'position: absolute; width: 100%; background: rgba(0, 0, 0, 0.2); bottom: 0px; height: 5px;border-radius:100% 0;\'></span></span></center>';

        return $out;
        
    }
}
