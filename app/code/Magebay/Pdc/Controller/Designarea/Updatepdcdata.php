<?php
namespace Magebay\Pdc\Controller\Designarea;

class Updatepdcdata extends \Magento\Framework\App\Action\Action {
    protected $productStatusModel;
	protected $_resultJsonFactory;
	protected $_imagecategory;
	protected $_pdpcolor;
	protected $_fonts;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magebay\Pdc\Model\Productstatus $productStatus,
		\Magebay\Pdc\Model\Imagecategory $imagecategory,
		\Magebay\Pdc\Model\Color $pdpcolor,
        \Magebay\Pdc\Model\Fonts $fonts
    ) {
		$this->_resultJsonFactory = $resultJsonFactory;
        $this->productStatusModel = $productStatus;
		$this->_imagecategory = $imagecategory;
		$this->_pdpcolor = $pdpcolor;
        $this->_fonts = $fonts;
        parent::__construct($context);
    }
    public function execute() {
        $response = array(
            'status' => 'error',
            'message' => 'Can not save side. Something went worng!'
        );
        try {
            $postData = file_get_contents("php://input");
            $dataDecoded = json_decode($postData, true);
			$type = isset($dataDecoded['type']) ? $dataDecoded['type'] : 1;
			$itemIds = isset($dataDecoded['item_ids']) ? $dataDecoded['item_ids'] : array();
			$pdcProductId = isset($dataDecoded['pdc_product_id']) ? (int)$dataDecoded['pdc_product_id'] : 0;
			$jsonData = '';
			$okSave = false;
			if($pdcProductId > 0 && $type == 2)
			{
				if(count($itemIds))
				{
					$arDataImgCats = array();
					foreach($itemIds as $itemId)
					{
						if($itemId == 0)
						{
							continue;
						}
						//get position
						$imgCat = $this->_imagecategory->load($itemId);
						if(!$imgCat || ($imgCat && !$imgCat->getId()))
						{
							continue;
						}
						$arDataImgCats[$itemId] = array('position'=>$imgCat->getPosition());
					}
					$jsonData = json_encode($arDataImgCats);
				}
				$this->productStatusModel->setSelectedImage($jsonData)->setId($pdcProductId)->save();
				$okSave = true;
				$response = array(
                    'status' => 'success',
                    'message' => 'Update data success',
                );
			}
			else if($pdcProductId > 0 && $type == 3)
			{
				//save color
				if(count($itemIds))
				{
					$arDataImgCats = array();
					foreach($itemIds as $itemId)
					{
						if($itemId == 0)
						{
							continue;
						}
						//get position
						$color = $this->_pdpcolor->load($itemId);
						if(!$color || ($color && !$color->getId()))
						{
							continue;
						}
						$arDatacolor[$itemId] = array('position'=>$color->getPosition());
					}
					$jsonData = json_encode($arDatacolor);
				}
				$this->productStatusModel->setSelectedColor($jsonData)->setId($pdcProductId)->save();
				$okSave = true;
				$response = array(
                    'status' => 'success',
                    'message' => 'Update data success',
                );
			}
			else if($pdcProductId > 0 && $type == 4)
			{
				//save color
				if(count($itemIds))
				{
					$arDataImgCats = array();
					foreach($itemIds as $itemId)
					{
						if($itemId == 0)
						{
							continue;
						}
						//get position
						$font = $this->_fonts->load($itemId);
						if(!$font || ($font && !$font->getId()))
						{
							continue;
						}
						$arDataFont[$itemId] = array('position'=>$font->getFontPosition());
					}
					$jsonData = json_encode($arDataFont);
				}
				$this->productStatusModel->setSelectedFont($jsonData)->setId($pdcProductId)->save();
				$okSave = true;
				$response = array(
                    'status' => 'success',
                    'message' => 'Update data success',
                );
			}
            //Note should in json format
           /*  $noteArray = $dataDecoded['note'];
            $dataDecoded['note'] = json_encode($noteArray);
            $result = $this->productStatusModel->setProductConfig($dataDecoded);
            if($result) {
                $noteDecoded = json_decode($result['note'], true);
                $result['note'] = $noteDecoded;
                $response = array(
                    'status' => 'success',
                    'message' => 'Product info saved successfully!',
                    'data' => $result
                );
            }  */
        } catch(\Exception $error) {
              
        }
        $resultJson = $this->_resultJsonFactory->create();
		return $resultJson->setData($response);
    }
}
