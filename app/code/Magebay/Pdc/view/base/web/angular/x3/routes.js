// ROUTES
pdcApp.config(["$routeProvider", function ($routeProvider, $log) {
    $routeProvider
    
    .when('/', {
        templateUrl: function() {
            var baseUrl = angular.element(document.querySelector("#mst_base_url_templage")).val();
			var isMinJs = angular.element(document.querySelector("#is_min_js")).val();
            var defaultUrl = "/";
            if (baseUrl !== "") {
                defaultUrl = baseUrl.replace("app.js", "") + 'templates/index.html';
				if(isMinJs == '1')
				{
					defaultUrl = baseUrl.replace("app.min.js", "") + 'templates/index.html';
				}
            }
            return defaultUrl;
        },
        //templateUrl: 'http://localhost:8888/m2sample/magebay/index.html',
        controller: 'pdcIndexController'
    })
    .when('/side/:id/product/:productid', {
        templateUrl: function() {
           var baseUrl = angular.element(document.querySelector("#mst_base_url_templage")).val();
		   var isMinJs = angular.element(document.querySelector("#is_min_js")).val();
            var defaultUrl = "/";
            if (baseUrl !== "") {
                defaultUrl = baseUrl.replace("app.js", "") + 'templates/side.html';
				if(isMinJs == '1')
				{
					 defaultUrl = baseUrl.replace("app.min.js", "") + 'templates/side.html';
				}
            }
            return defaultUrl;
        },
        //templateUrl: 'http://localhost:8888/m2sample/magebay/side.html',
        controller: 'sideController'
    })
    .when('/productcolors/product/:productid', {
        templateUrl: function() {
             var baseUrl = angular.element(document.querySelector("#mst_base_url_templage")).val();
			 var isMinJs = angular.element(document.querySelector("#is_min_js")).val();
            var defaultUrl = "/";
            if (baseUrl !== "") {
                defaultUrl = baseUrl.replace("app.js", "") + 'templates/product_colors.html';
				if(isMinJs == '1')
				{
					defaultUrl = baseUrl.replace("app.min.js", "") + 'templates/product_colors.html';
				}
            }
            return defaultUrl;
        },
        //templateUrl: 'http://localhost:8888/m2sample/magebay/product_colors.html',
        controller: 'productColorsController'
    })
    .when('/productcolorslist/product/:productid', {
        templateUrl: function() {
            var baseUrl = angular.element(document.querySelector("#mst_base_url_templage")).val();
			var isMinJs = angular.element(document.querySelector("#is_min_js")).val();
            var defaultUrl = "/";
            if (baseUrl !== "") {
                defaultUrl = baseUrl.replace("app.js", "") + 'templates/product_colors_list.html';
				if(isMinJs == '1')
				{
					defaultUrl = baseUrl.replace("app.min.js", "") + 'templates/product_colors_list.html';
				}
            }
            return defaultUrl;
        },
        //templateUrl: 'http://localhost:8888/m2sample/magebay/product_colors_list.html',
        controller: 'productColorsListController'
    })
    .when('/templatelist/product/:productid', {
        templateUrl: function() {
            var baseUrl = angular.element(document.querySelector("#mst_base_url_templage")).val();
			var isMinJs = angular.element(document.querySelector("#is_min_js")).val();
            var defaultUrl = "/";
            if (baseUrl !== "") {
                defaultUrl = baseUrl.replace("app.js", "") + 'templates/template_list.html';
				if(isMinJs == '1')
				{
					defaultUrl = baseUrl.replace("app.min.js", "") + 'templates/template_list.html';
				}
            }
            return defaultUrl;
        },
        //templateUrl: 'http://localhost:8888/m2sample/magebay/template_list.html',
        controller: 'templateListController'
    })
    .otherwise({
        redirectTo: '/'
    });
    
}]);