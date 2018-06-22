<?php

namespace Magebay\Pdc\Block\Adminhtml\Color\Grid\Renderer;

class Status extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    public function __construct(
        \Magento\Backend\Block\Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        if($row->getStatus() == "1") {
            return 'Enable';
        } else {
            return 'Disabled';
        }
    }
}
