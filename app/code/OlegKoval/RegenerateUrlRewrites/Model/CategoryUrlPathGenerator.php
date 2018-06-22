<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OlegKoval\RegenerateUrlRewrites\Model;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
class CategoryUrlPathGenerator extends \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator
{
  

    /**
     * Build category URL path
     *
     * @param \Magento\Catalog\Api\Data\CategoryInterface|\Magento\Framework\Model\AbstractModel $category
     * @return string
     */
    public function getUrlPath($category)
    {
        if (in_array($category->getParentId(), [Category::ROOT_CATEGORY_ID, Category::TREE_ROOT_ID])) {
            return '';
        }
        $path = $category->getUrlPath();
        if ($path !== null && !$category->dataHasChangedFor('url_key') && !$category->dataHasChangedFor('parent_id')) {
            return $path;
        }
        $path = $category->getUrlKey();
        if ($path === false) {
            return $category->getUrlPath();
        }
        if ($this->isNeedToGenerateUrlPathForParent($category)) {
            $parentPath = $this->getUrlPath(
                $this->categoryRepository->get($category->getParentId(), $category->getStoreId())
            );
            $path = $parentPath === '' ? $path : '' . $path;
        }
        return $path;
    }

    
}
