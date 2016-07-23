'use strict';

// RESTful API Modules

angular.module('core.api', ['ngResource'])
    .factory("LastUpdateAPI", ["$resource",
        function ($resource){
            return $resource("/api/last_update", {}, {
                get: {
                    method: "GET",
                    isArray: true
                }
            })
        }])
    .factory("CategoryAPI", ["$resource",
        function ($resource) {
            return $resource("/api/categories/:slug", {}, {
                get: {
                    method: "GET"
                },
                all: {
                    method: "GET",
                    isArray: true
                }
            })
        }])
    .factory("PostAPI", ["$resource",
        function ($resource) {
            return $resource("/api/posts/:id", {}, {
                get: {
                    method: "POST",
                    isArray: true
                }
            })
        }])
    .factory("PostMultpleAPI", ["$resource",
        function ($resource) {
            return $resource("/api/post/multiple", {}, {
                get: {
                    method: "POST",
                    isArray: true
                }
            })
        }]);

// Declare app level module which depends on views, and components
angular.module('eanoisFrontEnd', [
    'ui.router',
    'ngFitText',
    'core.api',
    'ngSanitize'
])
.filter('unsafe', function($sce) {
    return function(val) {
        return $sce.trustAsHtml(val);
    };
})
.run([
    "$rootScope", "$state", "$stateParams", "$timeout",
    function ($rootScope, $state, $stateParams, $timeout) {
        $rootScope.$state = $state;
        $rootScope.$stateParams = $stateParams;
        $rootScope.toDate = function(d) {
            return new Date(d);
        };
        $rootScope.remToPx = function(size) {
            return size * parseInt($("body").css('font-size'));
        };
        $rootScope.$on("$stateChangeSuccess", function() {
            var title = getTitleValue($state.$current.locals.globals.$title);
            var baseTitle = "1A23 Studio";
            $timeout(function() {
                $rootScope.$title = title ? title + " - " + baseTitle : baseTitle;
            });
        });

        function getTitleValue(title) {
            return angular.isFunction(title) ? title() : title;
        }
    }
])
.directive('eanoisSrc', function () {
    return {
        restrict: 'A',
        link: function (scope, elem, attr){
            var theme_root = $("meta[name=theme_root]").attr("content");
            $(elem).attr("src", theme_root + "/" + attr.eanoisSrc);
        }
    };
})
.directive('eanoisSrcset', function () {
    return {
        restrict: 'A',
        link: function (scope, elem, attr){
            var theme_root = $("meta[name=theme_root]").attr("content");
            $(elem).attr("srcset", theme_root + "/" + attr.eanoisSrcset);
        }
    };
})
.config(['$locationProvider', '$urlRouterProvider', '$stateProvider',
    function($locationProvider, $urlRouterProvider, $stateProvider) {
        var theme_root = $("meta[name=theme_root]").attr("content");
        // $locationProvider.hashPrefix('!');
        $locationProvider.html5Mode(true);
        // This is only for redirect only.
        $urlRouterProvider
            .when('/test', '/test-redirect')  // for no purpose :P
            .otherwise('/');
        // Actual routing goes here!!
        $stateProvider
            .state("home", {
                url: '/',
                templateUrl: theme_root + "/index/template.html",
                controller: "indexController as index",
                resolve: {
                    updates: ["LastUpdateAPI", function(LastUpdateAPI) {
                        return LastUpdateAPI.get();
                    }]
                }
            })
            .state("about", {
                url: '/about'
            })
            .state("works", {
                url: '/works',
                templateUrl: theme_root + "/works/index.html",
                controller: "worksIndexController as index",
                resolve: {
                    $title: function() {
                        return "Works";
                    },
                    categories: ["CategoryAPI", function (CategoryAPI) {
                        return CategoryAPI.all();
                    }]
                }
            })
            .state("works-category", {
                url: '^/works/:category',
                controller: "worksCategoryController as cate",
                resolve: {
                    cate: ["CategoryAPI", "PostAPI", "$stateParams", "$state",
                        function(CategoryAPI, PostAPI, $stateParams, $state) {
                            return CategoryAPI.get({slug: $stateParams.category}, function(cate) {
                                var post_id = [];
                                cate.posts.data.forEach(function(val){
                                    post_id.push(val.id);
                                });
                                cate['postdata'] = PostAPI.get({posts: post_id});
                                return cate;
                            });
                        }],
                    $title: ["cate", '$timeout',
                        function(cate, $timeout) {
                            return $timeout(function() {
                                return cate['name'];
                            }, 1000);
                    }]
                },
                // templateUrl: theme_root + "/works/lists/entry-template.html",
                templateProvider: ['cate', '$templateRequest', '$timeout',
                    function (cate, $templateRequest, $timeout) {
                        return $timeout(function(){
                            var tPath = theme_root + "/works/lists/" + cate.template + ".html";
                            return $templateRequest(tPath);
                        }, 1000);
                    }]
            })
            .state("works-category-single", {
                url: '/:slug'
            });
}])
.controller("indexController", ["$scope", "updates",
    function ($scope, updates) {
        var self = this;
        updates.forEach(function (val) {
            if (val['type'] == "post") {
                val['url'] = $scope.$state.href("works.category.single", {
                        category: val['category'],
                        slug: val['slug']
                    }, {absolute: true});
            }
        });
        self.LastUpdate = updates;
    }])
.controller("worksIndexController", ["$scope", "categories",
    function($scope, categories) {
        this.categories = categories;
    }])
.controller("worksCategoryController", ["cate",
    function(cate){
        this.category = cate;
    }]);