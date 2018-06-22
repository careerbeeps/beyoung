<?php
namespace Magebay\Pdc\Setup;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface {
    protected $imageFactory;
    protected $fontFactory;
    protected $colorFactory;
    protected $artworkcateFactory;

    public function __construct(
        \Magebay\Pdc\Model\ArtworkcateFactory $artworkcateFactory,
        \Magebay\Pdc\Model\FontsFactory $fontFactory,
        \Magebay\Pdc\Model\ColorFactory $colorFactory,
        \Magebay\Pdc\Model\ImageFactory $imageFactory
        
    )
    {
        $this->artworkcateFactory = $artworkcateFactory;
        $this->imageFactory = $imageFactory;
        $this->colorFactory = $colorFactory;
        $this->fontFactory = $fontFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        //if ($context->getVersion()
        //    && version_compare($context->getVersion(), '2.0.0') < 0
        //) {
            $this->installSampleImageCategory();
            $this->installSampleFont();
            $this->installSampleColor();
        //}
        $setup->endSetup();
    }
    private function installSampleImageCategory() {
        $categories = array(
            array(
                'title' => 'Great man',
                'status' => 1,
                'position' => 0,
                'image_types' => 'clipart'
            ),
            array(
                'title' => 'Animal',
                'status' => 1,
                'position' => 4,
                'image_types' => 'shape'
            ),
            array(
                'title' => 'Sport',
                'status' => 1,
                'position' => 0,
                'image_types' => 'image'
            )
        );
        $animals = array(
            array(
                'filename' => 'Animal_svg/reindeer3.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/rabbit5.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/print48.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/lamb1.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/fish52.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/fish2.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/ewe2.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/elephant6.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/dog56.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/cow12.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/chick2.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/animal570.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/animal569.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Animal_svg/animal39.svg',
                'category' => '4',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            )
        );
        $sport = array(
            array(
                'filename' => 'Sport_svg/sportive15.svg',
                'category' => '21',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Sport_svg/sport270.svg',
                'category' => '21',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Sport_svg/sport269.svg',
                'category' => '21',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Sport_svg/football28.svg',
                'category' => '21',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Sport_svg/clothing389.svg',
                'category' => '21',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Sport_svg/bodyparts71.svg',
                'category' => '21',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Sport_svg/basketball51.svg',
                'category' => '21',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            ),
            array(
                'filename' => 'Sport_svg/basketball32.svg',
                'category' => '21',
                'image_name' => '',
                'price' => '0.00',
                'thumbnail' => '',
            )
        );
        $greatMen = array(
            array(
                'filename' => 'filename1449503335.png',
                'category' => '1',
                'image_name' => 'Steve Jobs',
                'price' => '1.00',
                'thumbnail' => 'resize/resize_filename1449503335.png',
            ),
            array(
                'filename' => 'filename1449503623.png',
                'category' => '1',
                'image_name' => 'Michael Jackson',
                'price' => '1.00',
                'thumbnail' => 'resize/resize_filename1449503623.png',
            ),
            array(
                'filename' => 'filename1449504103.png',
                'category' => '1',
                'image_name' => 'Lionel Messi',
                'price' => '1.00',
                'thumbnail' => 'resize/resize_filename1449504103.png',
            )
        );
        //Greate men category
        $categoryModel = $this->artworkcateFactory->create();
        $categoryModel->setData($categories['0'])->save();
        $imageModel = $this->imageFactory->create();
        foreach($greatMen as $image) {
            $image['category'] = $categoryModel->getId();
            $imageModel->setData($image)->save();
        }
        //Animal category
        $categoryModel = $this->artworkcateFactory->create();
        $categoryModel->setData($categories['1'])->save();
        $imageModel = $this->imageFactory->create();
        foreach($animals as $image) {
            $image['category'] = $categoryModel->getId();
            $imageModel->setData($image)->save();
        }
        //Sport model
        $categoryModel = $this->artworkcateFactory->create();
        $categoryModel->setData($categories['2'])->save();
        $imageModel = $this->imageFactory->create();
        foreach($sport as $image) {
            $image['category'] = $categoryModel->getId();
            $imageModel->setData($image)->save();
        }
    }
    private function installSampleFont() {
        $fonts = array(
            array('name' => 'GoodDog','ext' => 'otf','original_filename'=>'gooddog.otf','dispay_text'=>'gooddog','font_position'=>0,'status'=>1),
            array('name' => 'lobster','ext' => 'otf','original_filename'=>'lobster.otf','dispay_text'=>'lobster','font_position'=>0,'status'=>1),
            array('name' => 'Loki Cola','ext' => 'ttf','original_filename'=>'lokicola.ttf','dispay_text'=>'lokicola','font_position'=>0,'status'=>1),
            array('name' => 'Made With B','ext' => 'ttf','original_filename'=>'madewithb.ttf','dispay_text'=>'madewithb','font_position'=>0,'status'=>1),
            array('name' => 'Montague','ext' => 'ttf','original_filename'=>'montague.ttf','dispay_text'=>'montague','font_position'=>0,'status'=>1),
            array('name' => 'organo','ext' => 'ttf','original_filename'=>'organo.ttf','dispay_text'=>'organo','font_position'=>0,'status'=>1),
            array('name' => 'Playball','ext' => 'ttf','original_filename'=>'playball.ttf','dispay_text'=>'playball','font_position'=>0,'status'=>1),
            array('name' => 'Riesling','ext' => 'ttf','original_filename'=>'riesling.ttf','dispay_text'=>'riesling','font_position'=>0,'status'=>1),
        );
        $fontModel = $this->fontFactory->create();
        foreach($fonts as $font) {
            $fontModel->setData($font)->save();
        }
    }
    private function installSampleColor() {
        $colors = array(
            array(
                'color_name' => 'White', 
                'color_code' => 'FFFFFF',
                'position' => 1
            ),
            array(
                'color_name' => 'Cream', 
                'color_code' => 'F2F3E2',
                'position' => 2
            ),
            array(
                'color_name' => 'Sulfur-yellow', 
                'color_code' => 'FDF027',
                'position' => 3
            ),
            array(
                'color_name' => 'Yellow', 
                'color_code' => 'FAD431',
                'position' => 4
            ),
            array(
                'color_name' => 'Golden yellow ', 
                'color_code' => 'EFA14A',
                'position' => 5
            ),
            array(
                'color_name' => 'Pastel orange', 
                'color_code' => 'E7722F',
                'position' => 6
            ),
            array(
                'color_name' => 'Orange', 
                'color_code' => 'E45D2C',
                'position' => 7
            ),
            array(
                'color_name' => 'Red', 
                'color_code' => 'C20034',
                'position' => 8
            ),
            array(
                'color_name' => 'Purple', 
                'color_code' => '9B0F33',
                'position' => 9
            ),
            array(
                'color_name' => 'Light pink', 
                'color_code' => 'EF7490',
                'position' => 10
            ),
            array(
                'color_name' => 'Pink', 
                'color_code' => 'D01A74',
                'position' => 11
            ),
            array(
                'color_name' => 'Lilac', 
                'color_code' => 'D0A2C8',
                'position' => 12
            ),
            array(
                'color_name' => 'Lavender', 
                'color_code' => '8766A6',
                'position' => 13
            ),
            array(
                'color_name' => 'Violet', 
                'color_code' => '692C89',
                'position' => 14
            ),
            array(
                'color_name' => 'Gentian', 
                'color_code' => '23529E',
                'position' => 15
            ),
            array(
                'color_name' => 'Royal blue', 
                'color_code' => '333181',
                'position' => 16
            ),
            array(
                'color_name' => 'Light blue', 
                'color_code' => '7EACD8',
                'position' => 17
            ),
            array(
                'color_name' => 'Mint', 
                'color_code' => '81D1CF',
                'position' => 18
            ),
            array(
                'color_name' => 'Turquoise blue', 
                'color_code' => '1B96A8',
                'position' => 19
            ),
            array(
                'color_name' => 'Green', 
                'color_code' => '13874E',
                'position' => 20
            ),
            array(
                'color_name' => 'Dark green', 
                'color_code' => '264F2B',
                'position' => 21
            ),
            array(
                'color_name' => 'Sage', 
                'color_code' => '8B8D59',
                'position' => 22
            ),
            array(
                'color_name' => 'Beige', 
                'color_code' => 'E6DBBF',
                'position' => 23
            ),
            array(
                'color_name' => 'Tan', 
                'color_code' => 'AE7735',
                'position' => 24
            ),
            array(
                'color_name' => 'Mustard', 
                'color_code' => 'EEB673',
                'position' => 25
            ),
            array(
                'color_name' => 'Hazelnut', 
                'color_code' => 'BD502C',
                'position' => 26
            ),
            array(
                'color_name' => 'Brown', 
                'color_code' => '593A2B',
                'position' => 27
            ),
            array(
                'color_name' => 'Gray', 
                'color_code' => '919287',
                'position' => 28
            ),
            array(
                'color_name' => 'Black', 
                'color_code' => '000000',
                'position' => 29
            ),
            array(
                'color_name' => 'Dark gray', 
                'color_code' => '575C61',
                'position' => 30
            ),
            array(
                'color_name' => 'Silver', 
                'color_code' => 'A4A1AD',
                'position' => 31
            ),
            array(
                'color_name' => 'Gold', 
                'color_code' => 'AB9C6B',
                'position' => 32
            ),
            array(
                'color_name' => 'Copper', 
                'color_code' => '9E7B52',
                'position' => 33
            )
            
        );
        $colorModel = $this->colorFactory->create();
        foreach($colors as $color) {
            $colorModel->setData($color)->save();
        }
    }
}
