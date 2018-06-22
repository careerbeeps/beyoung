<?php 
namespace Magebay\Pdc\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
class Customprice implements ObserverInterface {
    protected $pdcHelper;
    public function __construct(
        \Magebay\Pdc\Helper\Data $pdcHelper
    ) {
        $this->pdcHelper = $pdcHelper;
    }
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $item = $observer->getEvent()->getData('quote_item');
        // $product = $observer->getEvent()->getData('product');
		$item = ( $item->getParentItem() ? $item->getParentItem() : $item );
		$product = $item->getProduct();
        
        $buyInfo = $item->getBuyRequest();
		$options = $buyInfo->getData();
    	$extraPrice = 0;
    	 if (isset($options['extra_options'])) {
    		$extraPrice = $this->pdcHelper->getPDPExtraPrice($options['extra_options']);
    		if ($extraPrice != 0) {
    			// //Cal final price of product include special price, tier price, group price and custom option price
    			// //Group price, special price will same as final price, but tier price is different
    			$itemQty = (int) $item->getBuyRequest()->getData('original_qty');
    			$finalPrice = $product->getFinalPrice();
                $tierPrice = $product->getTierPrice($itemQty);
                /* if($tierPrice < $finalPrice) {
                    $finalPrice = $tierPrice;
                } */
				if ($itemQty > 0 && $product->getTierPriceCount() > 0) {
    				$tierPrices = $product->getTierPrice();
    				foreach ($tierPrices as $_tierPrice) {
    					//Make sure item has enought qty to cal tier price
    					if($itemQty >= (int) $_tierPrice['price_qty']) {
    						$tierPrices = $product->getTierPrice($itemQty);
    						break;
    					}
    				}
    				if (!is_array($tierPrices) && $tierPrices < $finalPrice) {
    					$customOptionPrice = $this->getOptionPrice($product, $itemQty, $options);
    					$finalPrice = $tierPrices + $customOptionPrice;
    				}
    			}
				$finalPrice += $extraPrice;
    			$item->setCustomPrice($finalPrice);
                $item->setOriginalCustomPrice($finalPrice);
                // Enable super mode on the product.
                $item->getProduct()->setIsSuperMode(true);
    		}
    	} 
    }
	public function getOptionPrice($_product, $itemQty, $options) {
    	$customOptionsPrice = 0;
    	$tierPrices = $_product->getTierPrice();
    	$finalPrice = $_product->getFinalPrice();
    	foreach ($tierPrices as $_tierPrice) {
    		//Make sure item has enought qty to cal tier price
    		if($itemQty >= (int) $_tierPrice['price_qty']) {
    			$tierPrices = $_product->getTierPrice($itemQty);
    			break;
    		}
    	}
    	if (!is_array($tierPrices) && $tierPrices < $finalPrice) {
    		$basePricesForPercent = array();
    		$basePricesForPercent[] = floatval($_product->getPrice());
    		if ($_product->getGroupPrice()) {
    			$basePricesForPercent[] = floatval($_product->getGroupPrice());
    		}
    		if ($_product->getSpecialPrice()) {
    			$basePricesForPercent[] = floatval($_product->getSpecialPrice());
    		}
    		$_finalPriceForPercent = min($basePricesForPercent);
    		//Tier price not include custom option price, so, need to add custom option price
    		if (isset($options['super_attribute'])) {
    			$_attributes = $_product->getTypeInstance(true)->getConfigurableAttributes($_product);
    			foreach($_attributes as $_attribute){
    				if (isset($_attribute['prices'])) {
    					foreach($_attribute['prices'] as $_priceOption) {
    						if (in_array($_priceOption['value_index'], $options['super_attribute'])) {
    							if ($_priceOption['pricing_value']) {
    								if ($_priceOption['is_percent'] == 0) {
    									$customOptionsPrice += floatval($_priceOption['pricing_value']);
    								} else {
    									$realPrice = ($_priceOption['pricing_value'] * $_finalPriceForPercent) / 100;
    									$customOptionsPrice += floatval($realPrice);
    								}
    							}
    						}
    					}
    				}
    			}
    		} elseif (isset($options['options'])) {
    			if ($_product->hasCustomOptions()) {
    				$customOptions = $_product->getOptions();
    				foreach($customOptions as $_option) {
    					foreach ($_option->getValues() as $option) {
    						if (in_array($option['option_type_id'], $options['options'])) {
    							if ($option['price_type'] == "fixed") {
    								$customOptionsPrice += floatval($option['price']);
    							} else {
    								$realPrice = ($option['price'] * $_finalPriceForPercent) / 100;
    								$customOptionsPrice += floatval($realPrice);
    							}
    						}
    					}
    				}
    			}
    			$customOptionsPrice;
    		}
    	}
    	return $customOptionsPrice;
    }
}