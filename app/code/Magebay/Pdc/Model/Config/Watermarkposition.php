<?php
namespace Magebay\Pdc\Model\Config;

class Watermarkposition implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value'=> 'top_left', 'label'=> __('Top Left')),
			array('value'=> 'right_top', 'label'=> __('Top Right')),
			array('value'=> 'left_bottom', 'label'=> __('Bottom Left')),
			array('value'=> 'bottom_right', 'label'=>__('Bottom Right')),
			array('value'=> 'center_center', 'label'=>__('Center')),
        );
    }
}
