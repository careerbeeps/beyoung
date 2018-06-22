<?php
namespace MageWorx\PaymentRestriction\Plugin\Model;
use Magento\Store\Model\StoreManagerInterface;
class Config
{
    protected $_storeManager;

public function __construct(
    StoreManagerInterface $storeManager
) {
    $this->_storeManager = $storeManager;

}

/**
 * Adding custom options and changing labels
 *
 * @param \Magento\Catalog\Model\Config $catalogConfig
 * @param [] $options
 * @return []
 */
public function afterGetAttributeUsedForSortByArray(\Magento\Catalog\Model\Config $catalogConfig, $options)
{
    $store = $this->_storeManager->getStore();
    $currencySymbol = $store->getCurrentCurrency()->getCurrencySymbol();

    //Remove specific default sorting options
    unset($options['position']);
    unset($options['name']);
    unset($options['price']);

    //Changing label
    $customOption['position'] = __('Relevance');

    //New sorting options
    $customOption['price_desc'] = __($currencySymbol.' (High to Low)');
    $customOption['price_asc'] = __($currencySymbol.' (Low to High)');

    //Merge default sorting options with custom options
    $options = array_merge($customOption, $options);

    return $options;
}
}