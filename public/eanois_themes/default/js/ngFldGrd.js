angular.module("ngFldGrd", [])
    .directive("ngFldGrd", ["$window", "$timeout",
        function ($window, $timeout) {
            return {
                restrict: 'A',
                link: function (scope, elem, attr) {
                    var conf = scope.$eval(attr.ngFldGrd);
                    var localConf = {
                        rowHeight: 400,
                        rowHeightOrphan: function (rows) {return Math.round(rows.heightAvg)},
                        itemSelector: "*",
                        objSelector: "img",
                        dataWidth: 'data-fld-width',
                        dataHeight: 'data-fld-height',
                    };
                    $timeout(function () {
                        var fldGrd_generate = function () {
                            return new $window.FldGrd(elem[0], angular.extend(localConf, conf));
                        };
                        $timeout(function(){
                            fldGrd_generate();
                        }, 100);
                        scope.$on("scrollPaginationUpdate", function() {
                            $timeout(function(){
                                fldGrd_generate();
                            }, 0);
                        });
                        scope.$on("$stateChangeSuccess", function() {
                            $timeout(function(){
                                fldGrd_generate();
                            }, 0);
                        });
                        scope.$on("$viewContentLoaded", function() {
                            $timeout(function(){
                                fldGrd_generate();
                            }, 0);
                        });
                    }, 0);
                }
            }
    }]);