<?php

namespace Magebay\Pdc\Model\Imagecategory\Grid;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return \Magebay\Pdc\Model\Imagecategory::getStatusesOptionArray();
    }
}
