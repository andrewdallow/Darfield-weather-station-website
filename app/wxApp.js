/*global angular */
(function () {
    'use strict';
    angular.module('wxApp', [
        'ngRoute',
        'highcharts-ng',
        'ui.bootstrap',
        'ui.bootstrap.tpls',
        'angularUtils.directives.dirPagination',
        'ngSanitize',
        'angulartics',
        'angulartics.google.tagmanager'
    ]);

    //configure routes
    angular.module('wxApp').config(['$routeProvider', '$locationProvider',
        function (
            $routeProvider,
            $locationProvider
        ) {
            $locationProvider.html5Mode(true);
            $locationProvider.hashPrefix('!');
            $routeProvider
                .when('/', {
                    templateUrl: 'components/home/home.html',
                    controller: 'homeController'
                })
                .when('/webcam', {
                    templateUrl: 'components/webcam/webcam.html',
                    controller: 'webcamController'
                })
                .when('/map', {
                    templateUrl: 'components/map/weathermap.html',
                    controller: 'mapController'
                })
                .when('/gauges', {
                    templateUrl: 'components/gauges/gauges.php',
                    controller: 'gaugesController'
                })
                .when('/graphs', {
                    templateUrl: 'components/graphs/graphs.html',
                    controller: 'graphsController'
                })
                .when('/forecast', {
                    templateUrl: 'components/forecast/forecast.html',
                    controller: 'forecastController'
                })
                .when('/records', {
                    templateUrl: 'components/history/records/records.html',
                    controller: 'recordsController'
                })

                .when('/noaa-style-reports', {
                    templateUrl:
                        'components/history/noaa-reports/' +
                        'noaa-style-reports.html',
                    controller: 'noaaController'
                })
                .when('/about', {
                    templateUrl: 'components/about/about.html',
                    controller: 'aboutController'
                })
                .when('/historicGraphs', {
                    templateUrl:
                        'components/history/graphs/historicGraphs.html',
                    controller: 'historicGraphsController'
                })
                .otherwise({
                    redirectTo: '/'
                });
        }]);
}());
