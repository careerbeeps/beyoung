<?php
 
namespace Magebay\Pdc\Controller\Index;
 
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class ReloadPrice extends \Magento\Framework\App\Action\Action
{
	 /**
     * Result page factory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory;
     */
	protected $_resultJsonFactory;
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
    */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
		JsonFactory $resultJsonFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
		$this->_resultJsonFactory = $resultJsonFactory;
    }
    public function execute()
    {
        $resultJson = $this->_resultJsonFactory->create();
		$params = $this->getRequest()->getParams();
		$optionsIds = isset($params['options']) ? $params['options'] : array();
		$productId = $this->getRequest()->getParam('product-id',0);
		$productModel = $this->_objectManager->get('Magento\Catalog\Model\Product');
		$product = $productModel->load($productId);
		$optionsModel = $this->_objectManager->get('Magento\Catalog\Model\Product\Option');
		$options = $optionsModel->getProductOptionCollection($product);
		$optionValueModel = $this->_objectManager->get('Magento\Catalog\Model\product\option\value');
		$finaPrice = $product->getFinalPrice();
		$price = $product->getPrice();
		$status = 'error';
		$pdcPrice = $this->getRequest()->getParam('pdc-price',0);
		$helperPrice = $this->_objectManager->get('Magento\Framework\Pricing\Helper\Data');
		if($product && $product->getId() && $pdcPrice > 0)
		{
			$finaPrice += $pdcPrice;
			$price += $pdcPrice;
			
			foreach($options as $option)
			{
				
				if($option->getType() == 'field' || $option->getType() == 'date' || $option->getType() == 'date_time' || $option->getType() == 'time')
				{
					if(isset($optionsIds[$option->getId()]) && $optionsIds[$option->getId()] != '')
					{
						$finaPrice += $option->getPrice();
						$price += $option->getPrice();
					}
				}
				elseif($option->getType() == 'drop_down' || $option->getType() == 'radio')
				{
					if(isset($optionsIds[$option->getId()]) && $optionsIds[$option->getId()] != '')
					{
						$values = $optionValueModel->getValuesCollection($option);
						if(count($values))
						{
							foreach($values as $value)
							{
								if($value->getId() == $optionsIds[$option->getId()])
								{
									$finaPrice += $value->getPrice();
									$price += $value->getPrice();
								}
							}
						}
					}
				}
				elseif($option->getType() == 'multiple' || $option->getType() == 'checkbox')
				{
					$values = $optionValueModel->getValuesCollection($option);
					$paramValues = isset($optionsIds[$option->getId()]) ? $optionsIds[$option->getId()] : array();
					foreach($values as $value)
					{
						if(in_array($value->getId(),$paramValues))
						{
							$finaPrice += $value->getPrice();
							$price += $value->getPrice();
						}
					}
				}
			}
			$status = 'success';
			$price = $helperPrice->currency($price,true,false);
			$finaPrice = $helperPrice->currency($finaPrice,true,false);
			
		}
		//get array sku exists in side of product
		$response = array('finaPrice'=>$finaPrice,'price'=>$price,'status'=>$status);
		return $resultJson->setData($response);
    }
}
 