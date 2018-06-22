// DIRECTIVES
pdcApp.directive("pdcMedia", ["$compile", "pdcServices", function($compile, pdcServices) {
    //=== MEDIA CONTROLLER ===//
    mediaController.$inject = ["$scope", "pdcServices"];
    function mediaController($scope, pdcServices) {
        $scope.baseUrl = angular.element(document.querySelector("#mst_base_url")).val();
        $scope.mediaUrl = angular.element(document.querySelector("#mst_media_url")).val() + "pdp/images/";
        //Image list
        $scope.showMedia = false;
        $scope.isUploading = false;
        $scope.imgSrc = '';

        $scope.removeImage = function() {
            if(!confirm("Are you sure?")) {
                return false;
            }
            $scope.imgSrc = "";
            $scope.$parent[$scope.name] = "";
            //Dispatch an event to update in parent scope, 
            //pdcMedia directive not working properly with ng-repeat
            //so solution is use $emit
            $scope.$emit('doneRemovedFile', {name: $scope.name});
        }
        $scope.uploadFile = function(){
            var file = $scope.myFile;
            if(file === undefined) {
                return false;
            }
            $scope.isUploading = true;
            pdcServices.uploadImage(file)
            .then(function(response) {
                //Update filename to parent scope
                $scope.$parent[$scope.name] = response.filename;
                $scope.imgSrc = response.filename;
                $scope.isUploading = false;
                //Upload same file once
                $scope.myFile = undefined;
                $scope.$emit('doneUploadFile', {name: $scope.name, filename: $scope.imgSrc});
            }, function(error) {
                console.warn(error);
            });
        };
    }
    return {
        restrict: 'AE',
        template: '<div clas="pbp-media-wrapper"> <div class="panel panel-default"> <div class="panel-body"> <div class="row"> <div class="col-md-5 form-inline"> <div class="form-group" style="padding-left: 15px;"> <label class="custom-file-upload" style="background: #006699;color: white;cursor: pointer;display: inline-block;padding: 6px 12px;"> <input type="file" style="display: none;" file-model="myFile" accept="image/*"/> <i class="glyphicon glyphicon-upload"></i> Browse... </label> </div> </div> <div class="col-md-5"> <img ng-src="{{ mediaUrl + imgSrc}}" ng-if="imgSrc" class="preview-{{name}}" alt="" width="50px" /> <pbp-loading ng-if="isUploading"></pbp-loading> <a class="btn btn-danger btn-xs" ng-show="imgSrc" ng-click="removeImage()" title="Remove this image"> <span aria-hidden="true" class="glyphicon glyphicon-remove-sign"></span> </a> </div> </div> </div> </div></div>',
        replace: true,
        link: function (scope, element, attrs, controller) {
            
        },
        controller: mediaController,
        scope: {
            name: '@',
            imgSrc: '@'
        }
    }
}]);
//==== FILE UPLOAD DIRECTIVE ====//
pdcApp.directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;
            element.bind('change', function(){
                scope.$apply(function(){
                    modelSetter(scope, element[0].files[0]);
                    scope.uploadFile();
                });
            });
        }
    };
}]);
//==== LOADING DIRECTIVE ====//
pdcApp.directive("pbpLoading", [function() {
    this.media_url = document.getElementById("mst_media_url").value + "pdp/images/";
    return {
        restrict: 'AE',
        template: '<span class="pbp-loading"><img style="width: 30px" src="'+ this.media_url + 'loading.gif"/></span>',
        replace: true
    }
}]);
//==== DOWNLOADING DIRECTIVE ====//
pdcApp.directive("pbpDownloading", [function() {
    this.media_url = document.getElementById("mst_media_url").value + "pdp/images/";
    return {
        restrict: 'AE',
        template: '<span class="pbp-loading"><img style="width: 30px" src="'+ this.media_url + 'downloading.gif"/></span>',
        replace: true
    }
}]);
//==== LOADINGBAR DIRECTIVE ====//
pdcApp.directive("pdcLoadingbar", [function() {
    this.media_url = document.getElementById("mst_media_url").value + "pdp/images/";
    return {
        restrict: 'AE',
        template: '<span class="pbp-loading"><img src="'+ this.media_url + 'loading2.gif"/></span>',
        replace: true
    }
}]);