/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
module.exports = function (grunt) {
    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            my_target : {
                options : {
                    sourceMap : true,
                    sourceMapName : 'app/assets/js/sourceMap.map'
                },
                files : {
                    'app/assets/js/wxApp.min.js' : [
                        'app/wxApp.js',
                        'app/mainCtrl.js',
                        'app/components/**/*.js',
                        'app/shared/**/*.js'
                    ],
                    'app/assets/js/external.min.js' : [
                        "app/bower_components/angular/angular.min.js",
                        "app/bower_components/angular-route/angular-route.min.js",
                        "app/bower_components/angular-sanitize/angular-sanitize.min.js",
                        "app/bower_components/angular-utils-pagination/dirPagination.js",
                        "app/bower_components/angulartics/dist/angulartics.min.js",
                        "app/bower_components/angulartics-google-tag-manager/dist/angulartics-google-tag-manager.min.js",
                        "app/bower_components/angular-bootstrap/ui-bootstrap.min.js",
                        "app/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js",
                        "app/bower_components/highcharts/adapters/standalone-framework.js",
                        "app/bower_components/highcharts/highcharts.js",
                        "app/bower_components/highcharts/highcharts-more.js",
                        "app/bower_components/highcharts/modules/exporting.js",
                        "app/bower_components/highcharts-ng/dist/highcharts-ng.min.js"
                    ]
                }
                
            }
        }
    });
    
    grunt.loadNpmTasks('grunt-contrib-uglify');

    // Default task(s).
    grunt.registerTask('default', ['uglify']);
};
