/* global angular, Highcharts */
(function () {
    'use strict';
    angular.module('wxApp').controller('historicGraphsController', [
        '$scope', '$http', 'historicGraphsFactory',
        function ($scope, $http, historicGraphsFactory) {
            $scope.page.setTitle('Historic Graphs');
            $scope.page.setDescription('Historic weather data graphs for the ' +
                'Darfield Weather Station, including: Temperature, Rainfall,' +
                ' Pressure, Wind, Humidity');


            $scope.dataTypes = [
                {
                    name: 'Daily',
                    options: [
                        {
                            name: '1 month',
                            time: 1
                        },
                        {
                            name: '6 month',
                            time: 6
                        },
                        {
                            name: '1 year',
                            time: 12
                        },
                        {
                            name: '2 years',
                            time: 24
                        }
                    ]
                },
                {
                    name: 'HiLo',
                    options: []
                },
                {
                    name: 'Monthly',
                    options: [
                        {
                            name: '1 year',
                            time: 12
                        },
                        {
                            name: '2 years',
                            time: 24
                        },
                        {
                            name: '3 years',
                            time: 36
                        },
                        {
                            name: '4 years',
                            time: 48
                        }
                    ]
                },
                {
                    name: 'Yearly',
                    options: []
                }
            ];

            $scope.tabs = [
                {
                    title: 'Temperature',
                    src: {
                        daily: 'temperature/historicTemperature.php',
                        hilo: 'temperature/historicTemperatureHiLo.php',
                        yearly: 'temperature/historicTemperatureYearly.php'
                    },
                    content: 'Temperature'
                },
                {
                    title: 'Humidity',
                    src: {
                        daily: 'humidity/historicHumidity.php',
                        yearly: 'humidity/historicHumidityYearly.php'
                    },
                    content: 'Humidity'
                },
                {
                    title: 'Pressure',
                    src: {
                        daily: 'pressure/historicPressure.php'
                    },
                    content: 'Pressure'
                },
                {
                    title: 'Wind',
                    src: {
                        daily: 'wind/historicWind.php',
                        yearly: 'wind/historicWindYearly.php'
                    },
                    content: 'Wind'
                },
                {
                    title: 'Rainfall',
                    src: {
                        daily: 'rainfall/historicRainDaily.php',
                        monthly: 'rainfall/historicRainMonthly.php',
                        yearly: 'rainfall/historicRainYearly.php'
                    },
                    content: 'Rainfall'
                }

            ];

            $scope.isDropdown = function (options) {
                var isDropdown = true;

                if (options.length === 0) {
                    isDropdown = false;
                }
                return isDropdown;
            };
            /**
             * 
             * @param {type} src
             * @param {type} button
             * @returns {Boolean}
             */
            $scope.isDisabled = function (src, button) {
                var disabled;
                if (src[button.toLowerCase()]) {
                    disabled = false;
                } else {
                    disabled = true;
                }

                return disabled;
            };

            $scope.getGraph = function (src, button, months) {

                months = months || 0;

                var path = src[button.toLowerCase()];

                if (months !== 0) {
                    path = path + "?months=" + months;
                }


                $http.get("components/history/graphs/" + path)
                    .then(function (response) {
                        $scope.charts =
                               historicGraphsFactory.loadGraphs(response.data);
                    });
            };

            $scope.getFirstGraph = function (tab) {

                $scope.getGraph(tab.src, 'daily', 1);

            };

            $scope.getGraph($scope.tabs[0].src, 'daily', 1);
        }]);
}());