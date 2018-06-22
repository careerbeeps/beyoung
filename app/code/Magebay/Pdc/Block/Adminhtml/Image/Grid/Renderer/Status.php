<?php

namespace Magebay\Pdc\Block\Adminhtml\Image\Grid\Renderer;

class Status extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $imagecategoryFactory;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magebay\Pdc\Model\ImageFactory $imagecategoryFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->imagecategoryFactory = $imagecategoryFactory;
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $category = $this->imagecategoryFactory->create()->load($row->getId());

        if ($category && $category->getId()) {
            return $category->getStatusAsLabel();
        }

        return '';
    }
}
