<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_ZipcodeValidator
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\ZipCodeValidator\Controller\Zipcode;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class Result extends Action
{
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $zipcode;

    /**
     * CookieManager
     *
     * @var CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var \Magento\Customer\Model\Url
     */
    private $customerUrl;

    /**
     * @var Magento\Catalog\Model\Product
     */
    private $product;

    private $regionCollection;

    /**
     * @param Context                                                                 $context
     * @param CookieManagerInterface                                                  $cookieManager
     * @param \Magento\Customer\Model\Url                                             $customerUrl
     * @param Magento\Customer\Model\Session                                          $session
     * @param Magento\Catalog\Model\Product                                           $product
     * @param Webkul\ZipCodeValidator\Model\ResourceModel\Zipcode\CollectionFactory $zipcode
     */
    public function __construct(
        Context $context,
        CookieManagerInterface $cookieManager,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Customer\Model\Session $session,
        \Magento\Catalog\Model\Product $product,
        \Webkul\ZipCodeValidator\Model\ResourceModel\Zipcode\CollectionFactory $zipcode,
        \Webkul\ZipCodeValidator\Model\ResourceModel\Region\CollectionFactory $regionCollection
    ) {
        $this->zipcode = $zipcode;
        $this->regionCollection = $regionCollection;
        $this->customerUrl = $customerUrl;
        $this->cookieManager = $cookieManager;
        $this->_session = $session;
        $this->product = $product;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = [];
        $data['addesses'] = '';
        $data['url'] = '';
        $address = $this->getAllAddressOfCustomer();
        if ($address) {
            $data['addesses'] = $address;
        } elseif (!$this->_session->getCustomerId()) {
            $data['url'] = $this->customerUrl->getLoginUrl();
        }
        $zip = $this->getRequest()->getParam('zip');
        $productId = $this->getRequest()->getParam('productId');
        $cookie = $this->cookieManager->getCookie('mpzip');
        $regionIds = explode(',', $this->product->load($productId)->getAvailableRegion());
        $enabledRegionIds = $this->regionCollection->create()
                              ->addFieldToFilter('id', ['in'=> $regionIds])
                              ->addFieldToFilter('status', 1)
                              ->addFieldToSelect('id');
        $zipcodeModel = $this->zipcode->create()
            ->addFieldToFilter('region_id', ['in'=>$enabledRegionIds->getColumnValues('id')])
            ->addFieldToFilter('region_zipcode', $zip);
            
        if ($zipcodeModel->getSize()) {
            $data['productzipcode'] = $zip;
            $data['product_zipcode'] = $zip;
            $data['product_id'] = $productId;
        } else {
            $regionModel = $this->regionCollection->create()
                ->addFieldToFilter('id', ['in'=>$enabledRegionIds->getColumnValues('id')])
                ->addFieldToFilter('region_name', $zip);
            if ($regionModel->getSize()) {
                $data['productzipcode'] = $zip;
                $data['product_zipcode'] = $zip;
                $data['product_id'] = $productId;
            }
        }
        if ($cookie) {
            $data['cookieZip'] = $cookie;
            $cookiezip = trim($zip).','.$data['cookieZip'];
        } else {
            $cookiezip = trim($zip);
        }
        $this->cookieManager->setPublicCookie('mpzip', $cookiezip);
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($data);
        return $resultJson;
    }
    /**
     * Get all address of logged Customer
     *
     * @return array
     */
    private function getAllAddressOfCustomer()
    {
        $customerAddress = [];
        if ($this->_session->getCustomerId()) {
            $customer = $this->_session->getCustomer();
            foreach ($customer->getAddresses() as $address) {
                $addr = $address->toArray();
                $postcode = $addr['postcode'];
                $street = $addr['street'];
                $city = $addr['city'];
                if ($street && $city && $postcode) {
                    $custAddr = $postcode.' '.$street.' '.$city;
                }
                $customerAddress[] = substr($custAddr, 0, 20).'...';
            }
        }
        return $customerAddress;
    }
}
