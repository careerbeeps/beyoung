<?php
/**
 * =====================================================
 *                  -:- Z-Programing -:-
 * =====================================================
 * @PROJECT    : Product Design Canvas [ Magebay.com ]
 * @AUTHOR     : Zuko
 * @FILE       : ImportHelper.php
 * @CREATED    : 8:53 AM , 21/Apr/2016
 * @DETAIL     : Helper for import feature in backend
 * =====================================================
 * =====================================================
 **/

namespace Magebay\Pdc\Helper;

use Magebay\Pdc\Model\Upload as UploadModel;
use Magento\Framework\App\Helper\AbstractHelper;

if(defined('DS') != DIRECTORY_SEPARATOR)
    define('DS',DIRECTORY_SEPARATOR);
/**
 * Class ImportHelper
 * @package Magebay\Pdc\Helper
 */
class ImportHelper extends AbstractHelper
{
    const ORG_UPLOAD_PATH = 'images' . DS . 'artworks' . DS;
    const THUMB_PATH = self::ORG_UPLOAD_PATH . 'resize' . DS;
    /**
     * @var \Magebay\Pdc\Model\Upload
     */
    protected $pdcUploadModel;
    /**
     * @var \Magebay\Pdc\Helper\Data
     */
    public $pdcHelper;

    public function __construct (\Magento\Framework\App\Helper\Context $context,
                                 UploadModel $pdcUploadModel,
                                 Data $pdcHelper)
    { 
        parent::__construct($context); 
        $this->pdcUploadModel = $pdcUploadModel;
        $this->pdcHelper = $pdcHelper;
    }

    /**
     * Getting Contents from local , uploaded csv file to arr with named key
     * @param string $filename
     *
     * @return array|bool
     */
    public function getCsvContents ($filename)
    {
        $csv = array_map('str_getcsv', file($filename));
        array_walk($csv, function(&$a) use ($csv)
        {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);
        if (count($csv) > 0) return $csv;

        return false;
    }
    public function batchResize(array $toResizeArr)
    {
        foreach ($toResizeArr as $k => $item)
        {
            if($item['thumbnail'] == '')
            {
                $pdcMediaPath = $this->pdcHelper->getMediaBaseDir();
                $orgFilePath = $pdcMediaPath . self::ORG_UPLOAD_PATH . $item['filename'];
                $newFilePath = $pdcMediaPath . self::THUMB_PATH;
                $newThumb = $this->pdcUploadModel->resizeImage($orgFilePath,$newFilePath,['media-url' =>'resize'.DS]);
                if($newThumb != false)
                {
                    $toResizeArr[$k]['thumbnail'] = $newThumb;
                    $toResizeArr[$k]['import'] = true;
                }else{
                    $toResizeArr[$k]['import'] = false;
                }
            }
        }
        return $toResizeArr;
    }
}