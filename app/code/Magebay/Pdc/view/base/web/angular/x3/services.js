pdcApp.service("pdcServices", ["$http", "$q", "$rootScope", function($http, $q, $rootScope) {
    var self = this;
    this.base_url = document.getElementById("mst_base_url").value;
    this.media_url = document.getElementById("mst_media_url").value + "pdp/images/";
    this.sides = null;
    this.productInfo = null;
    this.imagecats = null;
    this.coloritems = null;
    this.fontitems = null;
    // Return public API.
    return({
        sides: this.sides,
        productInfo: this.productInfo,
        imagecats: this.imagecats,
        coloritems: this.coloritems,
        fontitems: this.fontitems,
        uploadImage: uploadImage,
        baseUrl: this.base_url,
        mediaUrl: this.media_url,
        addSide: addSide,
        deleteSide: deleteSide,
        getProductId: getProductId,
        getProductInfo: getProductInfo,
        updateProductInfo: updateProductInfo,
        updatePdcData: updatePdcData,
        updateSidesData: updateSidesData,
        broadcastSides: broadcastSides,
        saveProductColor: saveProductColor,
        getProductColorList: getProductColorList,
        deleteProductColor: deleteProductColor,
        getTemplateList: getTemplateList,
        deleteTemplate: deleteTemplate
    });
    function uploadImage(file) {
        var fd = new FormData();
        fd.append('file', file);
        var uploadUrl = self.base_url + "pdc/designarea/uploadimage"
        var request = $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        });
        return request.then(handleSuccess, handleError);
    }
    function addSide( sideData ) {
        var request = $http({
            method: "post",
            url: self.base_url + "pdc/designarea/saveside",
            params: {
                action: "add"
            },
            data: sideData
        });
        return( request.then( handleSuccess, handleError ) );
    }
    function saveProductColor( productColorData ) {
        var request = $http({
            method: "post",
            url: self.base_url + "pdc/designarea/saveproductcolor",
            data: productColorData
        });
        return( request.then( handleSuccess, handleError ) );
    }
    function deleteSide( sideId, productId ) {
        var request = $http({
            method: "get",
            url: self.base_url + "pdc/designarea/deleteside/id/" + sideId + "/product-id/" + productId,
        });
        return( request.then( handleSuccess, handleError ) );
    }
    function deleteProductColor( productColorId ) {
        var request = $http({
            method: "get",
            url: self.base_url + "pdc/designarea/deleteproductcolor/id/" + productColorId,
        });
        return( request.then( handleSuccess, handleError ) );
    }
    function deleteTemplate( templateId ) {
        var request = $http({
            method: "get",
            url: self.base_url + "pdc/designarea/deletetemplate/id/" + templateId,
        });
        return( request.then( handleSuccess, handleError ) );
    }
    function updateProductInfo(productInfo) {
        var request = $http({
            method: "post",
            url: self.base_url + "pdc/designarea/updateinfo",
            data: productInfo
        });
        return( request.then( handleSuccess, handleError ) );
    }
	function updatePdcData(mData) {
        var request = $http({
            method: "post",
            url: self.base_url + "pdc/designarea/updatepdcdata",
            data: mData
        });
        return( request.then( handleSuccess, handleError ) );
    }
    function getProductInfo(productId) {
        var request = $http({
            method: "get",
            url: self.base_url + "pdc/designarea/productinfo/productid/" + productId,
        });
        return( request.then( handleSuccess, handleError ) );
    }
    function getProductColorList(productId) {
        var request = $http({
            method: "get",
            url: self.base_url + "pdc/designarea/getproductcolors/productid/" + productId,
        });
        return( request.then( handleSuccess, handleError ) );
    }
    function getTemplateList(productId) {
        var request = $http({
            method: "get",
            url: self.base_url + "pdc/designarea/templatelist/productid/" + productId,
        });
        return( request.then( handleSuccess, handleError ) );
    }
    function getProductId () {
        var productId = document.getElementById("current_product_id").value;
        if(!productId) {
            alert("Current Product Not Found. Please reload product page and try again!");
            return false;
        }
        return productId;
    }
    //Update sides for controler
    function updateSidesData(sides) {
        this.sides = sides;
        this.broadcastSides();
    }
    function broadcastSides() {
        $rootScope.$broadcast('handleUpdateSides');
    }
    // ---
    // PRIVATE METHODS.
    // ---
    function handleError( response ) {
        if (! angular.isObject( response.data ) ||! response.data.message) {
            return( $q.reject( "An unknown error occurred." ) );
        }
        // Otherwise, use expected error message.
        return( $q.reject( response.data.message ) );
    }
    // transform the successful response, unwrapping the application data
    // from the API response payload.
    function handleSuccess( response ) {
        return( response.data );
    }
}]);

