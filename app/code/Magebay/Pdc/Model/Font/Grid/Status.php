<?php

namespace Magebay\Pdc\Model\Font\Grid;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return \Magebay\Pdc\Model\Fonts::getStatusesOptionArray();
    }
}
