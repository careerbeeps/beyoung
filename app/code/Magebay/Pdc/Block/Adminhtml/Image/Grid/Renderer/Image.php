<?php
namespace Magebay\Pdc\Block\Adminhtml\Image\Grid\Renderer;
use Magento\Store\Model\StoreManagerInterface;
class Image extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
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
        //\Zend_Debug::dump($row->getData());
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        if($row->getFilename() != "") {
            return '<img width="60" height="60" src="'. $mediaUrl . 'pdp/images/artworks/' . $row->getFilename() .'"/>';
        }
        return '';
    }
}
