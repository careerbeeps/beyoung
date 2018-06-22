<?php
namespace Magebay\Pdc\Controller\Index;
use Magebay\Pdc\Model\Upload;
use Magento\Store\Model\StoreManagerInterface;
use Magebay\Pdc\Helper\Data as PdcHelper;
class Loadmoreimage extends \Magento\Framework\App\Action\Action {
    protected $uploadModel;
    protected $storeManager;
    protected $pdcHelper;
    protected $imageFactory;
    protected $pricing;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        Upload $upload,
        StoreManagerInterface $storeManager,
        PdcHelper $pdcHelper,
        \Magebay\Pdc\Model\ImageFactory $imageFactory,
        \Magento\Framework\Pricing\Helper\Data $pricing
    )
    {
        $this->uploadModel = $upload;
        $this->storeManager = $storeManager;
        $this->pdcHelper = $pdcHelper;
        $this->imageFactory = $imageFactory;
        $this->pricing = $pricing;
        parent::__construct($context);
    }
    public function execute() {
		$post = $this->getRequest()->getPost();
        $current_page = $post['current_page'];
		$category = $post['category'];
		$pageSize = $post['page_size'];
		$imageType = '';
		
		$collection = $this->pagingCollection($current_page, $category, $pageSize, $imageType);
		if ( count($collection) > 0) {
			$data = array();
			foreach ($collection as $image) {
				//$colorImg = $pdpObject->getColorImageFrontend($image->getImageId());
				//$image->setColorImg($colorImg);
                $imageData = $image->getData();
                //Formated price for clipart
                $imageData['price_format'] = __("Free");
                if($imageData['price'] > 0) {
                    $formattedPrice = $this->pricing->currency($imageData['price'],true,false);
                    $imageData['price_format'] = $formattedPrice;
                }
				$data[] = $imageData;
			}
			$this->getResponse()->setBody(json_encode($data));
		} else {
			$this->getResponse()->setBody("nomore");
		}
    }
    protected function pagingCollection($current_page, $category, $page_size, $imageType) {
		$collection = $this->imageFactory->create()->getImageCollectionByCategory($category, $imageType);
		$collection_counter = $this->imageFactory->create()->getImageCollectionByCategory($category, $imageType);
		$size = ceil(count($collection_counter) / $page_size);
		if ($current_page <= $size) {
			$collection->setCurPage($current_page);
			$collection->setPageSize($page_size);
			return $collection;
		}
	}
}
