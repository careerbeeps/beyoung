<?php

namespace Magebay\Pdc\Model\Color\Grid;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return \Magebay\Pdc\Model\Color::getStatusesOptionArray();
    }
}
