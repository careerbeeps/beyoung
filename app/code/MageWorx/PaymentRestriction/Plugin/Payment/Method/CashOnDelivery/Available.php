<?php
/**
 * Copyright © 2016 MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\PaymentRestriction\Plugin\Payment\Method\CashOnDelivery;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Backend\Model\Auth\Session as BackendSession;
use Magento\OfflinePayments\Model\Cashondelivery;

class Available
{

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var BackendSession
     */
    protected $backendSession;

    /**
     * @param CustomerSession $customerSession
     * @param BackendSession $backendSession
     */
    public function __construct(
        CustomerSession $customerSession,
        BackendSession $backendSession
    ) {
		mail("davids@mitash.com","paymentrestra","testing");
        $this->customerSession = $customerSession;
        $this->backendSession = $backendSession;
    }

    /**
     *
     * @param Cashondelivery $subject
     * @param $result
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterIsAvailable(Cashondelivery $subject, $result)
    {
        // Do not remove payment method for admin
        if ($this->backendSession->isLoggedIn()) {
            return $result;
        }
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$shippingaddressCollection = $objectManager->get("\Magento\Checkout\Model\Session")->getQuote()->getShippingAddress()->getData();
		
//$objectManager = MagentoFrameworkAppObjectManager::getInstance();
if($shippingaddressCollection['postcode']!=''){
        return false;
}
      /*  if (!$isLogged) {
            return false;
        }*/
 return false;
      //  return $result;
    }
}