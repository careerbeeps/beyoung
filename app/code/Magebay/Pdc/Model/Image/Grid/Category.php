<?php

namespace Magebay\Pdc\Model\Image\Grid;
class Category implements \Magento\Framework\Option\ArrayInterface {
    protected $imageCategoryFactory;
    public function __construct(
        \Magebay\Pdc\Model\ImagecategoryFactory $imageCategoryFactory
    ) {
        $this->imageCategoryFactory = $imageCategoryFactory;
    }
    public function toOptionArray()
    {
        return $this->imageCategoryFactory->create()->getCategoriesInImageGrid();
    }
}
