<?php

namespace Beyoung\Contactlink\Model;

class Sitemap extends \Magento\Sitemap\Model\Sitemap
{
    protected function _initSitemapItems()
    {
        parent::_initSitemapItems();

        $newLine = [];
        $object = new \Magento\Framework\DataObject();
        $object->setId(['contact']);
        $object->setUrl('contact');
        $object->setUpdatedAt(date("Y-m-d h:i:s"));
        $newLine['contact'] = $object;

        $this->_sitemapItems[] = new \Magento\Framework\DataObject(
            [
                'changefreq' => 'weekly',
                'priority' => '0.25',
                'collection' => $newLine
            ]
        );
    }
}