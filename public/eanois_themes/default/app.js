'use strict';

// RESTful API Modules

angular.module('core.lastupdate', ['ngResource'])
    .factory("LastUpdate", ["$resource",
    function ($resource){
        return $resource("/api/last_update", {}, {
            get: {
                method: "GET",
                isArray: true,
                transformResponse: function(data, headersGetter) {
                    return angular.fromJson(data);
                }
            }
        })
    }]);

// Declare app level module which depends on views, and components
angular.module('eanoisFrontEnd', [
    'ui.router',
    'ngFitText',
    'core.lastupdate'
])
.run([
    "$rootScope", "$state", "$stateParams",
    function ($rootScope, $state, $stateParams) {
        $rootScope.$state = $state;
        $rootScope.$stateParams = $stateParams;
    }
])
.config(['$locationProvider', '$urlRouterProvider', '$stateProvider',
    function($locationProvider, $urlRouterProvider, $stateProvider) {
        $locationProvider.hashPrefix('!');
        // This is only for redirect only.
        $urlRouterProvider
            .when('/test', '/test-redirect')  // for no purpose :P
            .otherwise('/');
        // Actual routing goes here!!
        $stateProvider
            .state("home", {
                url: '/',
                templateUrl: "index/template.html"
            })
            .state("about", {
                url: '/about'
            })
            .state("works", {
                url: '/works'
            })
            .state("works.category", {
                url: '/:category'
            })
            .state("works.category.single", {
                url: '/:slug'
            });


}])
.controller("indexController", ["$scope", "LastUpdate",
    function ($scope, LastUpdate) {
        var updates = LastUpdate.get(function () {
            updates.forEach(function (val) {
                if (val['type'] == "post") {
                    val['url'] = "/" + $scope.$state.href("works.category.single", {
                        category: val['category'],
                        slug: val['slug']
                    });
                }
            });
            $scope.LastUpdate = updates;
        });
    }]);
