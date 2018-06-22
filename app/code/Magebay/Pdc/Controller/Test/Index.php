<?php
namespace Magebay\Pdc\Controller\Test;
use Magebay\Pdc\Model\Upload;
use Magento\Store\Model\StoreManagerInterface;
use Magebay\Pdc\Helper\Data as PdcHelper;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Index extends \Magento\Framework\App\Action\Action {
    protected  $_product;
    protected $_configurableProTypeModel;
    protected $productFactory;
    protected $dataObjectHelper;
    protected $productRepository;
    protected $_storeManager;
	public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Product $product,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProTypeModel,
        \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
	) {
	    $this->_product = $product;
	    $this->_configurableProTypeModel = $configurableProTypeModel;
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->_storeManager = $storeManager;
	    parent::__construct($context);

	}

	/**
	* Post user question
	*
	* @return void
	* @throws \Exception
	*/
	public function execute()
	{
	    //$a = $this->_eventManager;
	   // echo get_class($a);
        
    }
}
