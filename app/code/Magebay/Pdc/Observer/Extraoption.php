<?php 
namespace Magebay\Pdc\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
class Extraoption implements ObserverInterface {
    protected $http;
    protected $_productMetadata;
    public function __construct(
        \Magento\Framework\App\Request\Http $http,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata
    ) {
        $this->http = $http;
        $this->_productMetadata = $productMetadata;
    }
    public function execute(\Magento\Framework\Event\Observer $observer) {
        // set the additional options on the product
		$actionName = "";
		try {
            $action = $this->http;
            if($action) {
                $actionName = $this->http->getFullActionName();    
            }
		} catch(\Exception $e) {
			$actionName = "";
		}
        if ($actionName == 'checkout_cart_add' || $actionName == 'checkout_cart_updateItemOptions')
        {
            // assuming you are posting your custom form values in an array called extra_options...
            $extraOption = $this->http->getParam('extra_options');
            if ($extraOption != "")
            {
                $product = $observer->getProduct();
                // add to the additional options array
                $additionalOptions = array();
               /*  if ($additionalOption = $product->getCustomOption('additional_options'))
                {
                    $additionalOptions = (array) unserialize($additionalOption->getValue());
                } */
                $additionalOptions[] = array(
                    'code' => 'pdpinfo',
                    'label' => __('Customize Design'),
                    'value' => '',
                    'json' => $extraOption,
                	'time' => microtime()
                );
                // add the additional options array with the option code additional_options
                $version = $this->_productMetadata->getVersion();
                if(version_compare($version, '2.2.0') >= 0)
                {
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $json = $objectManager->get('Magento\Framework\Serialize\Serializer\Json');
                    $jsonOptions = $json->serialize($additionalOptions);
                    $observer->getProduct()
                        ->addCustomOption('additional_options', $jsonOptions);
                }
                else
                {
                    $observer->getProduct()
                        ->addCustomOption('additional_options', serialize($additionalOptions));
                }

            }
        }
    }
}