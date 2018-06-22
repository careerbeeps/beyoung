<?php

namespace Magebay\Pdc\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;

class SaveOrderAfter implements ObserverInterface
{
	protected $inlineTranslation;
	protected $_transportBuilder;
	protected $_scopeConfigInterface;
	protected $_storeManager;
	
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
		\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
		\Magento\Store\Model\StoreManagerInterface $storeManager 
	)
    {
		$this->inlineTranslation = $inlineTranslation;
		$this->_transportBuilder = $transportBuilder;
		$this->_scopeConfigInterface = $scopeConfigInterface;
		$this->_storeManager = $storeManager;
    }
	public function execute(EventObserver $observer)
    {
		$order = $observer->getOrder();
		$incrementId = $order->getIncrementId();
		$items = $order->getAllVisibleItems();
		$pdcItems = array();
		$customerEmail = '';
		$email = $this->_scopeConfigInterface->getValue('pdp/setting/adminemail',ScopeInterface::SCOPE_STORE);
		if($order->getShippingAddress())
		{
			$shiipingAddress = $order->getShippingAddress();
			$customerEmail = $shiipingAddress->getEmail() ? $shiipingAddress->getEmail() : '';
		}
		elseif($order->getBillingAddress())
		{
			$bildingAddress = $order->getBillingAddress();
			$customerEmail = $bildingAddress->getEmail() ? $bildingAddress->getEmail() : '';
		}
		if(count($items))
		{
			foreach($items as $item)
			{
				$_product = $item->getProduct();
				$requestOptions = $item->getProductOptionByCode('info_buyRequest');
				if(isset($requestOptions['extra_options']) && $requestOptions['extra_options'] != '')
				{
					$pdcItems[] = array(
						'product_name'=>$_product->getName(),
						'product_sku'=>$_product->getSku(),
						'file_name'=>$requestOptions['extra_options']
					);
				}
				
			}
			if(count($pdcItems))
			{
				$storeId = $this->_storeManager->getStore()->getId();
				$this->inlineTranslation->suspend();
				try {
					$templateOptions = array('area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->_storeManager->getStore()->getId());
						$templateVars = array(
											'store' => $this->_storeManager->getStore(),
											'order_id' => $incrementId,
											'orderItems'=>$pdcItems,
											'adminemail'=>$email
										);
						$from = array('email' => $email, 'name' => 'Pdc Templates');
						$this->inlineTranslation->suspend();
						$to = array($customerEmail);
						$transport = $this->_transportBuilder->setTemplateIdentifier('pdc_template')
										->setTemplateOptions($templateOptions)
										->setTemplateVars($templateVars)
										->setFrom($from)
										->addTo($to)
										->getTransport();
						$transport->sendMessage();
						$this->inlineTranslation->resume();
				} catch (\Magento\Framework\Exception\MailException $ex) {

				}
			}
		}
	}
}
