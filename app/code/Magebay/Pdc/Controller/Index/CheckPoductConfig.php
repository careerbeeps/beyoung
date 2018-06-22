<?php
namespace Magebay\Pdc\Controller\Index;

class CheckPoductConfig extends \Magento\Framework\App\Action\Action {
    protected  $_product;
    protected $_configurableProTypeModel;
    protected $productFactory;
    protected $dataObjectHelper;
    protected $productRepository;
    protected  $_pdcHelper;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Product $product,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProTypeModel,
        \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magebay\Pdc\Helper\Data $pdcHelper
    ) {
        $this->_product = $product;
        $this->_configurableProTypeModel = $configurableProTypeModel;
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->_pdcHelper = $pdcHelper;
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

        $params = $this->getRequest()->getParams();
        $productId = isset($params['product']) ? $params['product'] : 0;
        $response = array('status'=>'error','produc_id'=>0,'json_file'=>'');
        if($productId > 0)
        {
            $product = $this->_product->load($productId);
            if($product->getTypeId() == 'configurable')
            {
                $optionsData = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
                $attributeValues = isset($params['super_attribute']) ? $params['super_attribute'] : array();
                if(count($attributeValues))
                {
                    $assPro = $this->_configurableProTypeModel->getProductByAttributes($attributeValues, $product);
                    if($assPro)
                    {
                        $assocateProId = $assPro->getEntityId();
                        $sampleDesignJsonFile = $this->_pdcHelper->getSampleJsonFile($assocateProId);
                        $response['product_id'] = $assocateProId;
                        $response['json_file'] = $sampleDesignJsonFile;
                        $response['status'] = 'success';
                    }
                }
            }
        }

        $this->getResponse()->setBody(json_encode($response));
    }


}
