//==== Index CONTROLLER ====//
pdcApp.controller('pdcIndexController', ["$scope", "pdcServices", "$location", "$routeParams", function($scope, pdcServices, $location, $routeParams) {
    $scope.product_id = pdcServices.getProductId();
    $scope.addNewSide = function() {
        $location.path("/side/0/product/" + $scope.product_id);
    }
    //Get product info
    if(!pdcServices.sides || !pdcServices.productInfo) {
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
    //Show or hide advanced tab
    $scope.showAdvancedTab = false;
    $scope.showAndHideAdvancedTab = function() {
        $scope.showAdvancedTab = !$scope.showAdvancedTab;
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
pdcApp.controller('productColorsController', ["$scope", "pdcServices", "$location", "$routeParams", function($scope, pdcServices, $location, $routeParams) {
    $scope.product_id = $routeParams.productid;
    $scope.productColor = {
        color_code: '',
        color_name: '',
        color_image_7: ''
    }
    $scope.filename = 'file1459529877.png';
    if(!pdcServices.sides) {
        $location.path("/");
        return false;
    } else {
        $scope.sides = pdcServices.sides;
        angular.forEach($scope.sides, function(side, index) {
            // var colorImageId = 'color_image_' + side.id,
            //     overlayImageId = 'overlay_image_' + side.id;
            // $scope.productColor[colorImageId] = '';
            // $scope.productColor[overlayImageId] = '';
            // $scope[colorImageId] = 'file1459529877.png';
            // $scope[overlayImageId] = '';
            // $scope.$watch(colorImageId, function() {
            //     $scope.productColor[colorImageId] = $scope[colorImageId];
            // });
            // $scope.$watch(overlayImageId, function() {
            //     $scope.productColor[overlayImageId] = $scope[overlayImageId];
            // });
        });
    }
    $scope.$watch('filename', function() {
        alert("Change");
        $scope.productColor.color_image_7 = $scope.filename;
    });
    //Back or cancel
    $scope.backOrCancel = function() {
        $location.path("/");
    }
    //Save side data
    $scope.saveProductColor = function() {
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