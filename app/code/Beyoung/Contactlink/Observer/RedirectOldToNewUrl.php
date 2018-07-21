<?php

namespace Beyoung\Contactlink\Observer;

class RedirectOldToNewUrl implements \Magento\Framework\Event\ObserverInterface{


/**
    * @var \Magento\Catalog\Model\ProductRepository
    */
    protected $_productRepository;

    /**
    * @var \Magento\Framework\App\ResponseFactory
    */
    protected $_responseFactory;

    /**
    * @var \Magento\Framework\UrlInterface
    */
    protected $_url;

    /**
    * @var \Magento\Store\Model\StoreManagerInterface
    */
    protected $_storeManager;



    public function __construct
    (
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Magento\Catalog\Model\ProductRepository $productRepository

    ){
        $this->_storeManager = $storeManagerInterface;
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_productRepository = $productRepository;
    }


    /* Check here Request query string */
    protected function _getQueryString()
    {
        
        if($_SERVER['REQUEST_URI'] == '/home' || $_SERVER['REQUEST_URI'] == '/home/'){
            
            return true;
        }

        return false;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        
        if($this->_getQueryString()){

            $storeObj = $this->_storeManager->getStore(1);
            $storeId = $storeObj->getId();
            $BaseURL = $storeObj->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
            $this->_responseFactory->create()->setRedirect($BaseURL , 301)->sendResponse();
        }

    }

    


}