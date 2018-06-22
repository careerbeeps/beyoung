<?php
namespace Magebay\Pdc\Plugin;
/**
 * Class ToOrderItemPlugin
 * @package Magebay\Pdc\Plugin
 */
class ToOrderItemPlugin {
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $_json;
    /**
     * ToOrderItemPlugin constructor.
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     */
    function __construct(
        \Magento\Framework\Serialize\Serializer\Json $json
    )
    {
        $this->_json = $json;
    }
    public function aroundConvert(\Magento\Quote\Model\Quote\Item\ToOrderItem $subject, \Closure $procede, $item, $data = []) {
        //Do nothing before
        //Call original method
        $orderItem = $procede($item, $data);
        //Do the custom code after: Check additional info of item
        if ($additionalOptions = $item->getOptionByCode('additional_options')) {
            $options = $orderItem->getProductOptions();
            //$options['additional_options'] = unserialize($additionalOptions->getValue());
            $options['additional_options'] = $this->_json->unserialize($additionalOptions->getValue());
            $orderItem->setProductOptions($options);
        }
        return $orderItem;
    }
}