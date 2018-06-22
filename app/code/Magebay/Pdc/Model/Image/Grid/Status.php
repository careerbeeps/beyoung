<?php

namespace Magebay\Pdc\Model\Image\Grid;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return \Magebay\Pdc\Model\Image::getStatusesOptionArray();
    }
}
