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
        }]);

// Declare app level module which depends on views, and components
angular.module('eanoisFrontEnd', [
    'ui.router',
    'ngFitText',
    'core.api',
    'ngSanitize',
    'ngFldGrd',
    'infinite-scroll'
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
                                var templates = {
                                    "entry-template": ['id', 'title', 'slug', 'desc', 'image', 'tags', 'links'],
                                    'heading-template': ['id', 'title', 'slug', 'desc', 'published_on', 'meta'],
                                    'gallery-template': ['id', 'title', 'slug', 'image']
                                };
                                cate['postdata'] = PostAPI.get({posts: post_id, select: templates[cate.template]});
                                return cate;
                            }).$promise;
                        }],
                    $title: ["cate",
                        function(cate) {
                            return cate['name'];
                    }]
                },
                // templateUrl: theme_root + "/works/lists/entry-template.html",
                templateProvider: ['cate', '$templateRequest',
                    function (cate, $templateRequest) {
                        var tPath = theme_root + "/works/lists/" + cate.template + ".html";
                        return $templateRequest(tPath);
                    }]
            })
            .state("works-category-single", {
                url: '/works/:category/:post',
                controller: "worksSingleController as post",
                resolve: {
                    post: ["PostAPI", "$stateParams", "$http",
                        function(PostAPI, $stateParams, $http) {
                            console.log("post_fired");
                            return PostAPI.get({slug: $stateParams.post}).$promise;
                        }],
                    $title: ['post', '$timeout', function(post, $timeout) {
                            return post[0].title;
                        }]
                },
                templateProvider: ['post', '$templateRequest',
                    function(post, $templateRequest){
                        var tPath = theme_root + "/works/singles/" + post[0].cate.template + ".html";
                        return $templateRequest(tPath);
                    }]
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
.controller("worksCategoryController", ["cate", "CategoryAPI", "PostAPI", "$scope",
    function(cate, CategoryAPI, PostAPI, $scope){
        this.pause = false;
        var self = this;
        this.loadNext = function () {
            if (self.pause) return;
            if (self.category.posts.current_page >= self.category.posts.last_page) return;
            var postData = self.category.postdata;
            self.pause = true;
            CategoryAPI.get({slug: self.category.slug, page: self.category.posts.current_page + 1}, function(cate) {
                var post_id = [];
                cate.posts.data.forEach(function(val){
                    post_id.push(val.id);
                });
                var templates = {
                    "entry-template": ['id', 'title', 'slug', 'desc', 'image', 'tags', 'links'],
                    'heading-template': ['id', 'title', 'slug', 'desc', 'published_on', 'meta'],
                    'gallery-template': ['id', 'title', 'slug', 'image']
                };
                PostAPI.get({posts: post_id, select: templates[cate.template]}, function(newPostData) {
                    cate.postdata = postData.concat(newPostData);
                    self.category = cate;
                    self.pause = false;
                    $scope.$emit("scrollPaginationUpdate");
                    return cate.postdata;
                });
                return cate;
            });
        };
        this.category = cate;
    }])
.controller("worksSingleController", ["post", function(post) {
    this.post = post[0];
}]);