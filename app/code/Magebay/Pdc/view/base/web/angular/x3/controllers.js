//==== Index CONTROLLER ====//
pdcApp.controller('pdcIndexController', ["$scope", "pdcServices", "$location", "$routeParams", function($scope, pdcServices, $location, $routeParams) {
    $scope.product_id = pdcServices.getProductId();
    $scope.addNewSide = function() {
        $location.path("/side/0/product/" + $scope.product_id);
    }
    //Get product info
    if(!pdcServices.sides || !pdcServices.productInfo || !pdcServices.imagecats) {
        $scope.isSaving = true;
        pdcServices.getProductInfo($scope.product_id)
        .then(function(response) {
            try {
                if(response.status == "error") {
                    alert(response.message);
                    $scope.isSaving = false;
                    return false;
                }
                if(response.status == "success" && response.data) {
                    if(response.data.productinfo) {
                        $scope.productInfo = response.data.productinfo;
                        pdcServices.productInfo = $scope.productInfo;
                    }
                    if(response.data.sides) {
                        $scope.sides = response.data.sides;
                        pdcServices.sides = $scope.sides;
                    }  
					if(response.data.imagecats) {
                        $scope.imagecats = response.data.imagecats;
                        pdcServices.imagecats = $scope.imagecats;
                    }
					if(response.data.coloritems)
					{
						$scope.coloritems = response.data.coloritems;
                        pdcServices.coloritems = $scope.coloritems;
					}
					if(response.data.fontitems)
					{
						$scope.fontitems = response.data.fontitems;
                        pdcServices.fontitems = $scope.fontitems;
					}
                }
            } catch(error) {
                console.log(error);
            }
            $scope.isSaving = false;
        }, function(error) {
            console.warn(error);
        });   
    } else {
        $scope.sides = pdcServices.sides;
        $scope.productInfo = pdcServices.productInfo;
        $scope.imagecats = pdcServices.imagecats;
        $scope.coloritems = pdcServices.coloritems;
        $scope.fontitems = pdcServices.fontitems;
		
    }
    //Save product info
	$scope.updateProductInfo = function() {
        $scope.isSaving = true;
        pdcServices.updateProductInfo($scope.productInfo)
        .then(function(response) {
            try {
                if(response.status == "error") {
                    alert(response.message);
                    $scope.isSaving = false;
                    return false;
                }
                if(response.status == "success" && response.data) {
                    $scope.productInfo = response.data;
                }
            } catch(error) {
                console.log(error);
            }
            $scope.isSaving = false;
        }, function(error) {
            console.warn(error);
        });
    }
	$scope.catImages = {};
	$scope.colorItemModel = {};
	$scope.fontItemsModel = {};
    $scope.updatePdcData = function(mTask) {
		 $scope.isSaving = true;
		var itemIds = [];
		if(mTask == 2)
		{
			angular.forEach($scope.catImages,function(key,value){
				if(key)
				{
					itemIds.push(value);
				}
			});
		}
		else if(mTask == 3)
		{
			angular.forEach($scope.colorItemModel,function(key,value){
				if(key)
				{
					itemIds.push(value);
				}
			});
		}
		else if(mTask == 4)
		{
			angular.forEach($scope.fontItemsModel,function(key,value){
				if(key)
				{
					itemIds.push(value);
				}
			});
		}
		var dataSend = {
			'product_id' : $scope.productInfo.product_id,
			'pdc_product_id' : $scope.productInfo.id,
			'item_ids' : itemIds,
			'type' : mTask
		};
		pdcServices.updatePdcData(dataSend)
        .then(function(response) {
            try {
                if(response.status == "error") {
                    alert(response.message);
                    $scope.isSaving = false;
                    return false;
                }
                if(response.status == "success" && response.data) {
                    $scope.productInfo = response.data;
                }
            } catch(error) {
                console.log(error);
            }
            $scope.isSaving = false;
        }, function(error) {
            console.warn(error);
        });
    }
	$scope.advancedTabOptoins = false;
	$scope.pdcDataImage = false;
	$scope.pdcDataColor = false;
	$scope.pdcDataFont = false;
	$scope.configPdcData = function(mTab)
	{
		$scope.advancedTabOptoins = false;
		$scope.pdcDataImage = false;
		$scope.pdcDataColor = false;
		$scope.pdcDataFont = false;
		if(mTab == 1)
		{
			$scope.advancedTabOptoins = true;
		}
		else if(mTab == 2)
		{
			var imageSelected = $scope.productInfo.selected_image; 
			if(imageSelected != '')
			{
				imageSelected = JSON.parse(imageSelected);
				for(var kimc in imageSelected)
				{
					$scope.catImages[kimc] = true;
				}
			}
			
			$scope.pdcDataImage = true;
		}
		else if(mTab == 3)
		{
			var selectedColor = $scope.productInfo.selected_color;
			if(selectedColor != '')
			{
				selectedColor = JSON.parse(selectedColor);
				for(var kcolor in selectedColor)
				{
					$scope.colorItemModel[kcolor] = true;
				}
			}
			$scope.pdcDataColor = true;
		}
		else if(mTab == 4)
		{
			var selectedFont = $scope.productInfo.selected_font; 
			if(selectedFont != '')
			{
				selectedFont = JSON.parse(selectedFont);
				for(var kfont in selectedFont)
				{
					$scope.fontItemsModel[kfont] = true;
				}
			}
			$scope.pdcDataFont = true;
		}
		
	}
	$scope.catSelectAll = function($event) {
		var checkbox = $event.target;
		var action = (checkbox.checked ? 'add' : 'remove');
		if(action == 'add')
		{
			$scope.catSelected = true;
		}
		else
		{
			$scope.catSelected = false;
		}
		angular.forEach($scope.imagecats , function (imagecat) {
			if(action == 'add')
			{
				$scope.catImages[imagecat.id] = true;
			}
			else
			{
				$scope.catImages[imagecat.id] = false;
				$scope.productInfo.selected_image = '';
			}
          
        });
	}
	$scope.colorSelectAll = function($event) {
		var checkbox = $event.target;
		var action = (checkbox.checked ? 'add' : 'remove');
		if(action == 'add')
		{
			$scope.colorSelected = true;
		}
		else
		{
			$scope.colorSelected = false;
		}
		angular.forEach($scope.coloritems , function (coloritem) {
			if(action == 'add')
			{
				$scope.colorItemModel[coloritem.color_id] = true;
			}
			else
			{
				$scope.colorItemModel[coloritem.color_id] = false;
				$scope.productInfo.selected_color = '';
			}
          
        });
	}
	$scope.fontSelectAll = function($event) {
		var checkbox = $event.target;
		var action = (checkbox.checked ? 'add' : 'remove');
		if(action == 'add')
		{
			$scope.fontSelected = true;
		}
		else
		{
			$scope.fontSelected = false;
		}
		angular.forEach($scope.fontitems , function (fontitem) {
			if(action == 'add')
			{
				$scope.fontItemsModel[fontitem.font_id] = true;
			}
			else
			{
				$scope.fontItemsModel[fontitem.font_id] = false;
				$scope.productInfo.selected_font = '';
			}
          
        });
	}
    //Update sides data between services and controller 
    //Update sides whenever pdcServices.sides change
    $scope.$on('handleUpdateSides', function() {
        $scope.sides = pdcServices.sides;
    });
    //Delete side action
    $scope.deleteSide = function(sideId, productId) {
        if(!confirm("Are you sure?")) {
            return false;
        }
        if(sideId) {
            pdcServices.deleteSide(sideId, productId)
            .then(function(response) {
                try {
                    if(response.status == "error") {
                        alert(response.message);
                        $scope.isSaving = false;
                        return false;
                    }
                    if(response.data && response.data.sides) {
                        pdcServices.sides = response.data.sides;
                        pdcServices.updateSidesData(response.data.sides);
                    }
                } catch(error) {
                    console.log(error);
                }
                $scope.isSaving = false;
            }, function(error) {
                console.warn(error);
            });
        }
    }
}]);
//==== ADD NEW SIDE CONTROLLER ====//
pdcApp.controller('sideController', ["$scope", "pdcServices", "$location", "$routeParams", function($scope, pdcServices, $location, $routeParams) {
    $scope.id = $routeParams.id || 0;
    $scope.product_id = $routeParams.productid;
    //Background and mask image
    $scope.initSideData = function() {
        $scope.sideData = {
            id: '0',
            label: '',
            filename: $scope.filename,
            overlay: $scope.overlay,
            price: '0',
            position: '0',
            status: '1',
            product_id: $scope.product_id,
            canvaswidth: '',
            canvasheight: '',
            background_type: 'blank', // blank or image depend on filename
            use_mask: 2, // 1 or 2 depend on overlay
        }
    }
    if($scope.id !== "0") {
        if(!pdcServices.sides) {
            $location.path("/");
            return false;
        }
        if(pdcServices.sides[$scope.id]) {
            console.info(pdcServices.sides[$scope.id]);
            $scope.sideData = pdcServices.sides[$scope.id];
            $scope.filename = $scope.sideData.filename;
            $scope.overlay = $scope.sideData.overlay;
        }
    } else {
        $scope.filename = '';
        $scope.overlay = '';
        $scope.initSideData();
    }
    //Preview Canvas
    function initCanvas() {
        canvas = new fabric.Canvas('canvas');
        canvas.setWidth(400);
        canvas.setHeight(400);
        canvas.selection = false;
        var sampleText = new fabric.Text("Sample Text", {});
        canvas.centerObject(sampleText);
        canvas.setActiveObject(sampleText);
        canvas.add(sampleText);
        canvas.renderAll();       
    }
    initCanvas();
    //If filename change, then update sideData.filename 
    $scope.addBackgroundLayer = function(src) {
        console.info("change background");
        var self = this;
        //Remove background layer before add
        canvas.forEachObject(function(obj) {
            if(obj.object_type && obj.object_type == "background") {
                canvas.remove(obj);
            }
        });
        var tempExt = src.split(".");
        $scope.showLoadingBar();
        if(tempExt.slice(-1) == "svg") {
            fabric.loadSVGFromURL(src, function (objects, options) {
                var loadedObject = fabric.util.groupSVGElements(objects, options);
                loadedObject.set({
                    top: 0,
                    left: 0,
                    originX: 'left', 
                    originY: 'top',
                    width: parseInt(canvas.getWidth() - 0), // - 2 fix border not wrap whole canvas
                    height: parseInt(canvas.getHeight() - 0),
                    alignX: 'min', // none, mid, min, max
                    alignY: 'min',
                    meetOrSlice: 'slice', // meet
                    selectable: false,
                    object_type: 'background',
                    hasBorders: false,
                    evented: false,
                });
                canvas.insertAt(loadedObject, 0);
                canvas.renderAll();
                $scope.hideLoadingBar();
            });
        } else {
            fabric.Image.fromURL(src, function(oImg) {
                oImg.set({
                    top: 0,
                    left: 0,
                    originX: 'left', 
                    originY: 'top',
                    width: parseInt(canvas.getWidth() - 0), // - 2 fix border not wrap whole canvas
                    height: parseInt(canvas.getHeight() - 0),
                    alignX: 'min', // none, mid, min, max
                    alignY: 'min',
                    meetOrSlice: 'slice', // meet
                    //selectable: false,
                    object_type: 'background',
                    //hasBorders: false,
                    //evented: false,
                });
                canvas.insertAt(oImg, 0);
                canvas.renderAll();
                $scope.hideLoadingBar();
            });
        }
    }
    $scope.$watch('filename', function() {
        $scope.sideData.filename = $scope.filename;
        if($scope.sideData.filename !="") {
            $scope.sideData.background_type = 'image';
            $scope.addBackgroundLayer(pdcServices.mediaUrl + $scope.sideData.filename);
        } else {
            $scope.sideData.background_type = 'blank';
            //remove canvas backgound
            canvas.getObjects().forEach(function(obj) {
                if(obj.object_type && obj.object_type == "background") {
                    canvas.remove(obj);
                    return false;
                }
            });
            canvas.renderAll();
        }
    });
    $scope.$watch('overlay', function() {
        $scope.sideData.overlay = $scope.overlay;
        if($scope.sideData.overlay !="") {
            $scope.sideData.use_mask = 1;
        } else {
            $scope.sideData.use_mask = 2;
        }
    });
    //Back or cancel
    $scope.backOrCancel = function() {
        $location.path("/");
    }
    $scope.showLoadingBar = function() {
        console.info("Will show loading bar later;");
    }
    $scope.hideLoadingBar = function() {
        console.info("Hide loading bar");
    }
    //Save side data
    $scope.saveSideData = function() {
		console.log('Save Data fire')
        if(!$scope.validForm()) return false;
        $scope.isSaving = true;
        pdcServices.addSide($scope.sideData)
        .then(function(response) {
            try {
                if(response.status == "error") {
                    alert(response.message);
                    $scope.isSaving = false;
                    return false;
                }
                if(response.data && response.data.sides) {
                    pdcServices.sides = response.data.sides;
					console.log(pdcServices.sides);
                }
                //Reset the form 
                document.getElementById("add_new_side_form").reset();
                //Redirect to index page
                $scope.isSaving = false;
                $scope.backOrCancel();
            } catch(error) {
                console.log(error);
            }
            $scope.isSaving = false;
        }, function(error) {
            console.warn(error);
        });
    }
    //Validate form
    $scope.validForm = function() {
        if(!$scope.sideData.label 
            || !$scope.sideData.canvaswidth 
            || !$scope.sideData.canvasheight 
            || !$scope.sideData.product_id) {
            return false;
        }
        return true;
    }
}]);
//==== PRODUCT COLORS CONTROLLER ====//
pdcApp.controller('productColorsController', ["$scope", "pdcServices", "$location", "$routeParams", "$timeout", function($scope, pdcServices, $location, $routeParams, $timeout) {
    $scope.product_id = $routeParams.productid;
    $scope.productColor = {
        color_code: '',
        color_name: '',
        product_id: $scope.product_id,
        design_sides: []
    }
    if(!pdcServices.sides) {
        $location.path("/");
        return false;
    } else {
        $scope.sides = pdcServices.sides;
		$scope.color_label = pdcServices.productInfo;
        angular.forEach($scope.sides, function(side, index) {
            var colorImageId = 'color_image_' + side.id,
                overlayImageId = 'overlay_image_' + side.id;
            $scope.productColor[colorImageId] = '';
            $scope.productColor[overlayImageId] = '';
            $scope.productColor.design_sides.push(side.id);
            $scope[colorImageId] = '';//file1459832042.png
            $scope[overlayImageId] = '';//file1459832050.png
            $scope.$watch(colorImageId, function() {
                $scope.productColor[colorImageId] = $scope[colorImageId];
            });
            $scope.$watch(overlayImageId, function() {
                $scope.productColor[overlayImageId] = $scope[overlayImageId];
            });
        });
    }
    //Back or cancel
    $scope.backOrCancel = function() {
        $location.path("/");
    }
    //Save side data
    $scope.saveProductColor = function() {
		console.log($scope.productColor);
        if(!$scope.validForm()) return false;
        $scope.isSaving = true;
        pdcServices.saveProductColor($scope.productColor)
        .then(function(response) {
            try {
                if(response.status == "error") {
                    alert(response.message);
                    $scope.isSaving = false;
                    return false;
                }
                //Redirect to index page
                $scope.isSaving = false;
                $scope.backOrCancel();
            } catch(error) {
                console.log(error);
            }
            $scope.isSaving = false;
        }, function(error) {
            console.warn(error);
        });
    }
    //Validate form
    $scope.validForm = function() {
        if(!$scope.productColor.color_code || !($scope.productColor.design_sides.length) || !$scope.sides) {
            return false;
        } else {
            //Check all sides has image
            var isInvalid = false;
			var requireBg = true;
			var requireMask = false;
			console.log($scope.sides);
			for(var keySide in $scope.sides)
			{
				if(isNaN(keySide))
				{
					continue;
				}
				var tempsideData = $scope.sides[keySide];
				if(tempsideData.use_mask == '1')
				{
					requireMask = true;
					break;
				}
			}
			console.log(requireBg);
			console.log(requireMask);
            angular.forEach($scope.sides, function(side, index) {
                if(requireBg && $scope.productColor['color_image_' + side.id] == "")
				{
					isInvalid = true;
                    return false;
				}
				if(requireMask && $scope.productColor['overlay_image_' + side.id] == "")
				{
					isInvalid = true;
                    return false;
				}
            });
            if(isInvalid) {
                alert("Please upload all backgound image and mask image of all sides!");
                return false;
            }
        }
        return true;
    }
    $scope.$on("doneRemovedFile", function(events, args) {
        $scope[args.name] = "";
    });
    $scope.$on("doneUploadFile", function(events, args) {
        $scope[args.name] = args.filename;  
    });
}]);
//==== PRODUCT COLORS LIST CONTROLLER ====//
pdcApp.controller('productColorsListController', ["$scope", "pdcServices", "$location", "$routeParams", "$timeout", function($scope, pdcServices, $location, $routeParams, $timeout) {
    $scope.product_id = $routeParams.productid;
    $scope.baseUrl = '';//http://localhost:8888/m2sample/';
	$scope.color_label = pdcServices.productInfo;
    //Back or cancel
    $scope.backOrCancel = function() {
        $location.path("/");
    }
    $scope.productColorInfo = null; 
    // $scope.productColorInfoString = '{"status":"success","message":"Get product color successfully!","data":{"default_side":{"7":{"id":"7","product_id":"1","color_id":"0","filename":"file1459529877.png","thumbnail":"","overlay":"file1459755972.png","color_name":"","color_code":"","price":"1.00","background_type":"image","canvassize":"","canvaswidth":"780","canvasheight":"820","position":"0","use_mask":"1","status":"1","label":"Front"},"8":{"id":"8","product_id":"1","color_id":"0","filename":"file1459757691.png","thumbnail":"","overlay":"file1459756533.png","color_name":"","color_code":"","price":"0.00","background_type":"image","canvassize":"","canvaswidth":"800","canvasheight":"800","position":"0","use_mask":"1","status":"1","label":"Back Side"}},"product_color_sides":{"2":{"id":"2","product_id":"1","color_id":"0","color_name":"Green","color_code":"#006699","color_thumbnail":"","position":"0","status":"1","images":[{"id":"1","product_color_id":"2","side_id":"7","filename":"file1459832042.png","overlay":"file1459832050.png","filename_thumbnail":"","overlay_thumbnail":"","label":"Front"},{"id":"2","product_color_id":"2","side_id":"8","filename":"file1459832042.png","overlay":"file1459832050.png","filename_thumbnail":"","overlay_thumbnail":"","label":"Back Side"}]}}}}';
    // $scope.productColorInfoJson = JSON.parse($scope.productColorInfoString);
    // $scope.productColorInfo = $scope.productColorInfoJson.data;
    pdcServices.getProductColorList($scope.product_id)
    .then(function(response) {
        try {
            if(response.status == "error") {
                alert(response.message);
                $location.path("/");
                return false;
            }
            //Redirect to index page
            $scope.productColorInfo = response.data;
            $scope.baseUrl = $scope.productColorInfo.base_url;
            angular.forEach($scope.productColorInfo.default_side, function(side, index) {
                if(!$scope.default_color_name) {
                    $scope.default_color_name = side.color_name;
                }
                if(!$scope.default_color_code) {
                    $scope.default_color_code = side.color_code;
                }
            });
            
        } catch(error) {
            console.log(error);
        }
    }, function(error) {
        console.warn(error);
    });
    $scope.updateProductColor = function() {
        if($scope.validForm()) {
            document.getElementById("product_color_list_form").submit();
        }
    }
    //Validate form
    $scope.validForm = function() {
        //Check default color_code 
        var defaultColorCode = document.getElementById("default_color_code").value;
        if(!defaultColorCode) {
            alert("Please enter color code for default side!");
            return false;
        }
        //Check all product color has fill color code
        var isInvalid = false;
        angular.forEach($scope.productColorInfo.product_color_sides, function(colorItem, index) {
            if(!document.getElementById("color_code_" + colorItem.id).value) {
                isInvalid = true;
            }
        });
        if(isInvalid) {
            alert("Please enter all color code of all color item!");
            return false;
        }
        return true;
    }
    //Delete product color 
    $scope.deleteProductColor = function(productColorId) {
        if(!confirm("Are you sure?")) {
            return false;
        }
        angular.element(document.getElementById("delete_color_" + productColorId)).text("Deleting...").addClass("disabled");
        pdcServices.deleteProductColor(productColorId)
        .then(function(response) {
            try {
                if(response.status == "error") {
                    alert(response.message);
                    return false;
                }
                //console.info("Remove this color from product color info");
                delete $scope.productColorInfo.product_color_sides[productColorId];
            } catch(error) {
                console.log(error);
            }
        }, function(error) {
            console.warn(error);
        });
    }
}]);
//==== TEMPLATE LIST CONTROLLER ====//
pdcApp.controller('templateListController', ["$scope", "pdcServices", "$location", "$routeParams", "$timeout", function($scope, pdcServices, $location, $routeParams, $timeout) {
    $scope.product_id = $routeParams.productid;
    $scope.baseUrl = '';
    $scope.mediaUrl = '';
	$scope.template_label = pdcServices.productInfo;
    //Back or cancel
    $scope.backOrCancel = function() {
        $location.path("/");
    }
    $scope.editTemplate = function(id) {
        if(!$scope.baseUrl || !$scope.product_id) {
            alert("Can not found product ID or base url. Something went wrong. Please reload the page!");
            return false;
        }
        window.location.href = $scope.baseUrl + 'pdc/view/designtool/product-id/' + $scope.product_id + '/area/backend/key/060379b1b9a3c638491fc97d57c1e2d48faa672a6264f7bc3a0ff749cc2ac57e/template-id/' + id + '/';   
    }
    $scope.templateListInfo = null; 
    pdcServices.getTemplateList($scope.product_id)
    .then(function(response) {
        try {
            $scope.baseUrl = response.base_url || '';
            $scope.mediaUrl = response.media_url || '';
            if(response.status == "error") {
                alert(response.message);
                //$location.path("/");
                return false;
            }
            $scope.templateListInfo = response.data;
        } catch(error) {
            console.log(error);
        }
    }, function(error) {
        console.warn(error);
    });
    $scope.saveChanges = function() {
        document.getElementById("template_list_form").submit();
    }
    //Validate form
    $scope.validForm = function() {
        return true;
    }
    //Delete product color 
    $scope.deleteTemplate = function(templateId) {
        if(!confirm("Are you sure?")) {
            return false;
        }
        angular.element(document.getElementById("delete_template_" + templateId)).text("Deleting...").addClass("disabled");
        pdcServices.deleteTemplate(templateId)
        .then(function(response) {
            try {
                if(response.status == "error") {
                    alert(response.message);
                    return false;
                }
                delete $scope.templateListInfo.templates[templateId];
            } catch(error) {
                console.log(error);
            }
        }, function(error) {
            console.warn(error);
        });
    }
}]);