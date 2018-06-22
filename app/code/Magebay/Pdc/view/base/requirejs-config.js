var config = {
    map: {
       '*': {
            "fancybox": "Magebay_Pdc/pdc/fancybox/jquery.fancybox",
            "colorpicker": "Magebay_Pdc/pdc/jquery/colorpicker/colorpicker",
			priceBox : 'Magebay_Pdc/price-box',
			'Magento_Catalog/js/price-box' : 'Magebay_Pdc/price-box',
        }
    },
    shim: {
        'fancybox' : {
            'deps': ['jquery']
        },
        'colorpicker' : {
            'deps': ['jquery']
        }
    }
}