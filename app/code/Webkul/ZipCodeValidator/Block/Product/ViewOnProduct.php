<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_ZipCodeValidator
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\ZipCodeValidator\Block\Product;

class ViewOnProduct extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var Magento\Customer\Model\Address
     */
    private $address;

    /**
     * @var Magento\Catalog\Model\Product
     */
    private $product;

    /**
     * @var Magento\CatalogInventory\Model\Stock\StockItemRepository
     */
    private $stockItemRepository;

    /**
     * @param \Magento\Backend\Block\Template\Context                   $context
     * @param \Magento\Customer\Model\Session                           $customerSession
     * @param \Magento\Customer\Model\Address                           $address
     * @param \Magento\Catalog\Model\Product                            $product
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     * @param array                                                     $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Address $address,
        \Magento\Catalog\Model\Product $product,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->address = $address;
        $this->product = $product;
        $this->stockItemRepository = $stockItemRepository;
        parent::__construct($context, $data);
    }

    /**
     * Get Customer Zipcode
     *
     * @return string
     */
    public function getCustomerZipcode()
    {
        if ($this->customerSession->getCustomerId()) {
            $customerAddressId = $this->customerSession->getCustomer()->getDefaultShipping();
            $postcode = $this->address->load($customerAddressId)->getPostcode();
            return $postcode;
        }
        return '';
    }

    /**
     * get Product
     *
     * @return Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        $id = $this->getRequest()->getParam('id');
        return $this->product->load($id);
    }
    /**
     * get Stock status
     *
     * @return boolean
     */
    public function getStockValue()
    {
        return $this->stockItemRepository
            ->get($this->getRequest()->getParam('id'))
            ->getIsInStock();
    }
}
