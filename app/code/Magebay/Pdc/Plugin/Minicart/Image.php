<?php

namespace Magebay\Pdc\Plugin\Minicart;
use Magento\Store\Model\StoreManagerInterface;

class Image
{
	public function aroundGetItemData($subject, $proceed, $item)
	{
		$result = $proceed($item);
		$buyRequest = $item->getBuyRequest()->getData();
		$pdcImage = isset($buyRequest['temp_pdc_thumbnail']) ? $buyRequest['temp_pdc_thumbnail'] : ''; 
		if($pdcImage != '')
		{
			$result['product_image']['src'] = $pdcImage;
		}
		return $result;
	}
}