module.exports = function(grunt) {

    var jslist = [
        'bower_components/jquery/dist/jquery.min.js',
        'bower_components/angular/angular.min.js',
        'bower_components/ngFitText/dist/ng-FitText.min.js',
        'bower_components/ngMeta/dist/ngMeta.min.js',
        'bower_components/angular-ui-router/release/angular-ui-router.min.js',
        'bower_components/angular-animate/angular-animate.min.js',
        'bower_components/angular-resource/angular-resource.min.js',
        'bower_components/angular-sanitize/angular-sanitize.min.js',
        'js/fldGrd.min.js',
        'bower_components/ngInfiniteScroll/build/ng-infinite-scroll.min.js',
        'bower_components/gsap/src/minified/TweenMax.min.js',
        'bower_components/angular-gsapify-router/angular-gsapify-router.js',
        'js/ngFldGrd.js',
        'js/app.js'
    ];

    grunt.initConfig({
        uglify: {
            app: {
                mangle: {
                    except: ['jQuery']
                },
                files: {
                    "js/app.min.js": jslist
                }
            }
        },
        sass: {
            dist: {

                options: {
                    sourceMap: true,
                    outputStyle: 'compressed'
                },
                dist: {
                    files: {
                        'css/app.css': 'css/app.scss'
                    }
                }
            }
        },
        watch: {
            uglify: {
                files: jslist,
                tasks: ['uglify']
            },
            sass: {
                files: ['css/app.css'],
                tasks: ['sass']
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');

    grunt.registerTask('default', ['uglify', 'sass']);

};