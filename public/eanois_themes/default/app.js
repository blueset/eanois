'use strict';

// Declare app level module which depends on views, and components
angular.module('eanoisFrontEnd', [
    'ngRoute',
    'ngFitText'
]).
config(['$locationProvider', '$routeProvider', function($locationProvider, $routeProvider) {
    $locationProvider.hashPrefix('!');
    $routeProvider
        .when('/', {
            templateUrl: "/eanois_themes/default/index/template.html",
            contorller: "indexController"
        })
        .otherwise('/');
}])
.controller("indexController", [function () {

}]);
