<?php
namespace Magebay\Pdc\Helper;

class PdcProduct extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $productFactory;
    protected $dataObjectHelper;
    protected $productRepository;
    protected $_storeManager;
    function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }
    public function getConfigChildProductIds($id){
        $product = array();
        if(is_numeric($id)){
            $product = $this->productRepository->getById($id);
        }else{
            return;
        }

        if(!isset($product)){
            return;
        }

        if ($product->getTypeId() != \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            return [];
        }

        $storeId = $this->_storeManager->getStore()->getId();

        $productTypeInstance = $product->getTypeInstance();
        $productTypeInstance->setStoreFilter($storeId, $product);
        $usedProducts = $productTypeInstance->getUsedProducts($product);
        $childrenList = [];

        foreach ($usedProducts  as $child) {
            $attributes = [];
            $isSaleable = $child->isSaleable();

            //getting in-stock product
            if($isSaleable){
                foreach ($child->getAttributes() as $attribute) {
                    $attrCode = $attribute->getAttributeCode();
                    $value = $child->getDataUsingMethod($attrCode) ?: $child->getData($attrCode);
                    if (null !== $value && $attrCode != 'entity_id') {
                        $attributes[$attrCode] = $value;
                    }
                }

                $attributes['store_id'] = $child->getStoreId();
                $attributes['id'] = $child->getId();
                /** @var \Magento\Catalog\Api\Data\ProductInterface $productDataObject */
                $productDataObject = $this->productFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $productDataObject,
                    $attributes,
                    '\Magento\Catalog\Api\Data\ProductInterface'
                );
                $childrenList[] = $productDataObject;
            }
        }

        $childData = array();
        foreach($childrenList as $child){
            $childData[] = $child;
        }

        return $childData;
    }
}