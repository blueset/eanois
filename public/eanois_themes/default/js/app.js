'use strict';

Array.prototype.clean = function(deleteValue) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == deleteValue) {
            this.splice(i, 1);
            i--;
        }
    }
    return this;
};

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
                    method: "GET",
                    isArray: false
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
    .factory("PageAPI", ["$resource",
        function ($resource) {
            return $resource("/api/pages/:slug", {}, {
                get: {method: "GET", isArray: false}
            });
        }])
    .factory("LinkAPI", ["$resource",
        function ($resource) {
            return $resource("/api/links", {}, {
                get: {method: "GET", isArray: true}
            });
        }]);


// Declare app level module which depends on views, and components
angular.module('eanoisFrontEnd', [
    'ui.router',
    'ngFitText',
    'core.api',
    'ngSanitize',
    'ngFldGrd',
    'infinite-scroll',
    'ngMeta',
    'ngAnimate',
    'hj.gsapifyRouter'
])
.filter('unsafe', function($sce) {
    return function(val) {
        return $sce.trustAsHtml(val);
    };
})
.filter('htmlToPlaintext', function() {
    return function(text) {
        return text ? String(text).replace(/<[^>]+>/gm, '') : '';
    };
})
.run([
    "$rootScope", "$state", "$stateParams", "$timeout", "ngMeta",
    function ($rootScope, $state, $stateParams, $timeout, ngMeta) {
        $rootScope.$state = $state;
        $rootScope.$stateParams = $stateParams;
        $rootScope.toDate = function(d) {
            return new Date(d);
        };

        $rootScope.$on('$stateChangeStart', function() {
            $rootScope.stateLoading = true;
        });

        $rootScope.$on('$stateChangeSuccess', function() {
            $rootScope.stateLoading = false;
        });

        $rootScope.remToPx = function(size) {
            return size * parseInt($("body").css('font-size'));
        };

        function getTitleValue(title) {
            return angular.isFunction(title) ? title() : title;
        }

        $rootScope.$on("$stateChangeSuccess", function() {
            var title = getTitleValue($state.$current.locals.globals.$title);
            var baseTitle = "1A23 Studio";
            $timeout(function() {
                $rootScope.$title = title ? title + " - " + baseTitle : baseTitle;
            });
        });

        ngMeta.init();
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
.component('eanoisAds', {
    templateUrl: 'api/ads'
})
.config(['$locationProvider', '$urlRouterProvider', '$stateProvider', 'ngMetaProvider', 'gsapifyRouterProvider',
    function($locationProvider, $urlRouterProvider, $stateProvider, ngMetaProvider, gsapifyRouterProvider) {
        var theme_root = $("meta[name=theme_root]").attr("content");
        // ngMeta configs
        ngMetaProvider.setDefaultTitle($("meta[name='site:name']").attr("content"));
        ngMetaProvider.setDefaultTag("description", $("meta[name='site:desc']").attr("content"));
        ngMetaProvider.setDefaultTag("image", $("meta[name='site:logo']").attr("content"));
        ngMetaProvider.setDefaultTag("og:type", "website");
        ngMetaProvider.setDefaultTag("twitter:card", "summary");
        // $locationProvider.hashPrefix('!');
        $locationProvider.html5Mode(true);
        // hard-coded redirection for old links
        $urlRouterProvider
            .when('/work/opensources', '/works/open-source')
            .when('/page/about', '/about')
            .otherwise('/');
        // Actual routing goes here!!

        // ngMeta wrapper
        var ngMetaUpdate = function(ngMeta, title, desc, imgsrc) {
            if (title) ngMeta.setTitle(title);
            if (desc) ngMeta.setTag('description', desc);
            ngMeta.setTag('og:type', 'article');
            if (imgsrc) {
                ngMeta.setTag('image', imgsrc.path);
                ngMeta.setTag('twitter:card', 'summary_large_image');
            } else {
                ngMeta.setTag('twitter:card', 'summary');
            }
        };

        // Animation defaults
        // gsapifyRouterProvider.defaults = {enter: null, leave: null};
        // gsapifyRouterProvider.initialTransitionEnabled = true;

        gsapifyRouterProvider.transition('slideAbove', {
            duration: 1,
            ease: 'Quart.easeInOut',
            css: {
                y: '-100%',
                opacity: 0
            },
        });

        gsapifyRouterProvider.transition('slideBelow', {
            duration: 1,
            ease: 'Quart.easeInOut',
            css: {
                y: '100%',
            },
        });

        gsapifyRouterProvider.transition('slideLeft', {
            duration: 1,
            ease: 'Quint.easeInOut',
            css: {
                x: '-100%',
            },
        });

        gsapifyRouterProvider.transition('slideRight', {
            duration: 1,
            ease: 'Quint.easeInOut',
            css: {
                x: '100%',
            },
        });

        gsapifyRouterProvider.transition('slideAboveDelay', {
            duration: 1,
            ease: 'Quart.easeInOut',
            delay: 0.5,
            css: {
                y: '-100%',
                opacity: 0
            },
        });

        gsapifyRouterProvider.transition('slideBelowDelay', {
            duration: 1,
            ease: 'Quart.easeInOut',
            delay: 0.5,
            css: {
                y: '100%',
            },
        });

        gsapifyRouterProvider.transition('slideLeftDelay', {
            duration: 1,
            ease: 'Quint.easeInOut',
            delay: 0.5,
            css: {
                x: '-100%',
            },
        });

        gsapifyRouterProvider.transition('slideRightDelay', {
            duration: 1,
            ease: 'Quint.easeInOut',
            delay: 0.5,
            css: {
                x: '100%',
            },
        });



        $stateProvider
            .state("home", {
                url: '/',
                templateUrl: theme_root + "/index/template.html",
                controller: "indexController as index",
                resolve: {
                    updates: ["LastUpdateAPI", function(LastUpdateAPI) {
                        return LastUpdateAPI.get().$promise;
                    }],
                    lyricovaTotalNumber: ['$http', function ($http) {
                        return $http({method: 'JSONP', url:'https://1a23.com/lyricova/main/postcountjson?callback=JSON_CALLBACK'});
                    }]
                },
                data: {
                    'gsapifyRouter.undefined': {
                        enter: {'in': {transition: 'slideLeftDelay'}},
                        leave: {out:  {transition: 'slideLeft'}}
                    }
                }
            })
            .state("about", {
                url: '/about',
                templateUrl: theme_root + "/index/about.html",
                resolve: {
                    page: ["PageAPI", function (PageAPI) {
                        return PageAPI.get({slug: "about"}).$promise;
                    }],
                    title: function() {return "About";},
                    meta: ['page', 'ngMeta', function(page, ngMeta){
                        ngMetaUpdate(ngMeta, page.title, page.metadesc);
                    }],
                },
                controller: "pageController as page",
                meta: {disableUpdate: true},
                data: {
                    'gsapifyRouter.undefined': {
                        enter: {'in': {transition: 'slideLeftDelay'}},
                        leave: {out:  {transition: 'slideLeft'}}
                    }
                }
            })
            .state("links", {
                url: '/links',
                templateUrl: theme_root + "/index/links.html",
                resolve: {
                    link: ["LinkAPI", function (LinkAPI) {
                        return LinkAPI.get().$promise;
                    }],
                    title: function() {return "Links";},
                    meta: ['ngMeta', function (ngMeta) {
                        ngMeta.setTitle('Links');
                        ngMeta.setTag('description', 'Websites related to 1A23 Studio');
                        ngMeta.setTag('og:type', 'article');
                    }]
                },
                controller: "linkController as link",
                meta: {disableUpdate: true},
                data: {
                    'gsapifyRouter.undefined': {
                        enter: {'in': {transition: 'slideLeftDelay'}},
                        leave: {out:  {transition: 'slideLeft'}}
                    }
                }
            })
            .state("works", {
                url: '/works',
                templateUrl: theme_root + "/works/index.html",
                controller: "worksIndexController as index",
                meta: {disableUpdate: true},
                resolve: {
                    $title: function() {
                        return "Works";
                    },
                    categories: ["CategoryAPI", function (CategoryAPI) {
                        return CategoryAPI.all().$promise;
                    }],
                    meta: ['ngMeta', 'categories', function (ngMeta, categories) {
                        var categoryList = "Categories: ";
                        categories.forEach(function(val){categoryList += val.name + ", ";});
                        categoryList += "...";
                        ngMeta.setTitle('Works');
                        ngMeta.setTag('description', categoryList);
                        ngMeta.setTag('og:type', 'article');
                    }]
                },
                data: {
                    'gsapifyRouter.undefined': {
                        enter: {'in': {transition: 'slideRightDelay'}},
                        leave: {out:  {transition: 'slideRight'}}
                    }
                }
            })
            .state("works-category", {
                url: '^/works/:category',
                controller: "worksCategoryController as cate",
                resolve: {
                    cate: ["CategoryAPI", "PostAPI", "$stateParams", "$state", "$rootScope",
                        function(CategoryAPI, PostAPI, $stateParams, $state, $rootScope) {
                            return CategoryAPI.get({slug: $stateParams.category}, function(cate) {
                                var post_id = [];
                                cate.posts.data.forEach(function(val){
                                    post_id.push(val.id);
                                });
                                var templates = {
                                    "entry-template": ['id', 'title', 'slug', 'desc', 'image', 'tags', 'links'],
                                    'heading-template': ['id', 'title', 'slug', 'desc', 'published_on', 'meta'],
                                    'gallery-template': ['id', 'title', 'slug', 'image', 'meta']
                                };
                                cate['postdata'] = PostAPI.get({posts: post_id, select: templates[cate.template]}, function(data) {
                                    data.forEach(function (val) {
                                        if (!val['meta'] || val['meta']['link'] == "") {
                                            val['meta'] = val['meta'] || {};
                                            val['meta']['link'] = $state.href("works-category-single", {
                                                category: cate.slug,
                                                post: val['slug']
                                            }, {absolute: true});
                                        }
                                    });
                                    return data;
                                });
                                $state.template = cate.template;
                                return cate;
                            }).$promise;
                        }],
                    $title: ["cate",
                        function(cate) {
                            return cate['name'];
                    }],
                    meta: ['ngMeta', 'cate', function (ngMeta, cate) {
                        var categoryList = "";
                        var imgsrc = null;
                        cate.postdata.forEach(function(val){
                            categoryList += val.title + "; ";
                            if (val.imageMeta & !imgsrc){
                                imgsrc = val.imageMeta.path;
                            }
                        });
                        categoryList += "...";
                        ngMeta.setTitle(cate.name);
                        ngMeta.setTag('description', categoryList);
                        if (imgsrc) ngMeta.setTag('image', imgsrc);
                        ngMeta.setTag('og:type', 'article');
                    }]
                },
                // templateUrl: theme_root + "/works/lists/entry-template.html",
                templateProvider: ['cate', '$templateRequest',
                    function (cate, $templateRequest) {
                        var tPath = theme_root + "/works/lists/" + cate.template + ".html";
                        return $templateRequest(tPath);
                    }],
                meta: {disableUpdate: true},
                data: {
                    'gsapifyRouter.undefined': {
                        enter: {'in': {transition: ['$state', function(cate) {
                            if (cate.template == "gallery-template") {
                                return 'slideAboveDelay';
                            }
                            return 'slideLeftDelay';
                        }]}},
                        leave: {out: {transition: ['$state', function(cate) {
                            if (cate.template == "gallery-template") {
                                return 'slideAbove';
                            }
                            return 'slideLeft';
                        }]}},
                    }
                }
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
                        }],
                    meta: ['ngMeta', 'post', function (ngMeta, post) {
                        ngMetaUpdate(ngMeta, post[0].title, post[0].metadesc, post[0].imageMeta);
                    }]
                },
                templateProvider: ['post', '$templateRequest',
                    function(post, $templateRequest){
                        var tPath = theme_root + "/works/singles/" + post[0].cate.template + ".html";
                        return $templateRequest(tPath);
                    }],
                meta: {disableUpdate: true},
                data: {
                    'gsapifyRouter.undefined': {
                        enter: {'in': {transition: 'slideAboveDelay'}},
                        leave: {out:  {transition: 'slideAbove'}}
                    }
                }
            });
}])
.controller("indexController", ["$scope", "updates", 'lyricovaTotalNumber', '$http',
    function ($scope, updates, lyricovaTotalNumber, $http) {
        var self = this;
        updates.forEach(function (val) {
            if (val['type'] == "post") {
                val['url'] = $scope.$state.href("works-category-single", {
                        category: val['category'],
                        post: val['slug']
                    }, {absolute: true});
            }
        });
        self.LastUpdate = updates;

        self.lyricovaQuote = "信じたものは<br>都合のいい妄想を<br>繰り返し映し出す鏡";

        var lyricovaTotalNumber = lyricovaTotalNumber.data;
        var getLyricovaQuote = function(target) {
            var maxLineCount = 6;
            var id = Math.floor(Math.random() * (lyricovaTotalNumber - 1));
            if (target) {target[0][target[1]] = "...";}
            return $http.jsonp("https://1a23.com/lyricova/main/getlyricjson?post_id=" + id + "&post_cat=1&callback=JSON_CALLBACK").success(function (words){
                words = words.filter(Boolean);
                if (words.length > maxLineCount){
                    if (target) {target[0][target[1]] += ".";}
                    return getLyricovaQuote(target);
                } else {
                    if (target) {target[0][target[1]] = words.clean("").join("<br>");}
                    else return words.clean("").join("<br>");
                }
            });
        };

        self.updateQuote = function(){getLyricovaQuote([self, 'lyricovaQuote']);};
        self.updateQuote();
    }])
.controller("worksIndexController", ["$scope", "categories",
    function($scope, categories) {
        this.categories = categories;
    }])
.controller("worksCategoryController", ["cate", "CategoryAPI", "PostAPI", "$scope", "$rootScope",
    function(cate, CategoryAPI, PostAPI, $scope, $rootScope){
        this.pause = false;
        var self = this;
        this.loadNext = function () {
            $rootScope.stateLoading = true;
            if (self.pause) {
                return;
            }
            if (self.category.posts.current_page >= self.category.posts.last_page) {
                $rootScope.stateLoading = false;
                return;
            }
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
                    cate.postdata.forEach(function (val) {
                        if (!val['meta'] || val['meta']['link'] == "") {
                            val['meta'] = val['meta'] || {};
                            val['meta']['link'] = $scope.$state.href("works-category-single", {
                                category: cate.slug,
                                post: val['slug']
                            }, {absolute: true});
                        }
                    });
                    $scope.$emit("scrollPaginationUpdate");
                    $rootScope.stateLoading = false;
                    return cate.postdata;
                });
                return cate;
            });
        };
        this.category = cate;
    }])
.controller("worksSingleController", ["post", function(post) {
    this.post = post[0];
}])
.controller("pageController", ["page", function(page) {
    this.page = page;
}])
.controller("linkController", ["link", function(link) {
    this.link = [];
    angular.forEach(link, function(val, key){
        if (val["type"] == "divider"){
            this.push({name: val.name, links: []});
        } else {
            this[this.length - 1].links.push(val);
        }
    }, this.link);
}])
.animation('.html-body', function(){
    return {
        enter: function(elem, done){
            console.log("enter");
            done();
        },
        leave: function(elem, done){
            console.log("leave");
            done();
        }
    }
});