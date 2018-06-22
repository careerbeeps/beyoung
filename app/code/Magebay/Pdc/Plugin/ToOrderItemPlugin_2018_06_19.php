<?php 
namespace Magebay\Pdc\Plugin;
class ToOrderItemPlugin {
    public function aroundConvert(\Magento\Quote\Model\Quote\Item\ToOrderItem $subject, \Closure $procede, $item, $data = []) {
        //Do nothing before
        //Call original method
        $orderItem = $procede($item, $data);
        //Do the custom code after: Check additional info of item
        if ($additionalOptions = $item->getOptionByCode('additional_options')) {
            $options = $orderItem->getProductOptions();
            $options['additional_options'] = $additionalOptions->getValue();
            $orderItem->setProductOptions($options);
        }
        return $orderItem;
    }
}