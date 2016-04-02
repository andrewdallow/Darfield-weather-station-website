/*global Highcharts, angular*/

(function () {
    'use strict';
    angular.module('wxApp').controller('homeController', [
        '$scope', '$http', '$interval', '$timeout', 'unitConversionService',
        'windVaneFactory', 'thermoFactory',
        'liveTempGraphFactory', 'liveWindGraphFactory',
        'liveRainGraphFactory', 'graphs24HrFactory',
        'realtimeService',
        function ($scope, $http, $interval, $timeout, unitConversionService,
            windVaneFactory, thermoFactory,
            liveTempGraphFactory, liveWindGraphFactory,
            liveRainGraphFactory, graphs24HrFactory, realtimeService) {


            $scope.page.setTitle('Home');
            $scope.page.setDescription(
                'Live Weather data at Darfield, New Zealand. Including: ' +
                    'Temperature, Wind, Rainfall, Pressure, Humidity.'
            );

            var orgRealtimeData = {},
                orgExtremes,
                unitConverter = unitConversionService,
                //Realtime updates config
                delay = 30000, //ms
                totUpdateTime = 60 * 10, //seconds
                totUpdates = Math.floor((totUpdateTime * 1000) / delay),
                maxOfflineTime = 18000 * 1000,
                ajaxRealtimeTimer,
                timer,
                maxTime = 60,
                rapidTimer,
                rapidIntervel = 2500,
                months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul',
                    'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                realtimeConvertable = [
                    'outTemp', 'tempchangehour', 'tempMaxValueT',
                    'tempMinValueT', 'windchill', 'dewpoint', 'windSpeed',
                    'windGust', 'avwindlastimediate10', 'avwindlastimediate60',
                    'windMaxValue', 'rainRate', 'rainSumDay', 'rainSumMonth',
                    'rainSum', 'barometer', 'barometerTrendData'
                ];

            $scope.unitTypes = ['Metric', 'Imperial'];
            $scope.selectedUnit = 'Metric';
            $scope.current = {};
            $scope.extremes = {};

            $scope.hideRestart = true;
            $scope.numUpdates = 0;
            $scope.stationOnline = true;

            //WindVane
            $scope.windVane = windVaneFactory;
            //Thermometer
            $scope.thermo = thermoFactory;
            //Live Temperature Graph
            $scope.liveTemp = liveTempGraphFactory;
            //Live Wind Graph
            $scope.liveWind = liveWindGraphFactory;
            //Live Rain Graph
            $scope.liveRain = liveRainGraphFactory;

            //24 Hr Graphs 
            $scope.temp24Hr = graphs24HrFactory.temp24Hr;
            $scope.rainBaro24Hr = graphs24HrFactory.rainBaro24Hr;
            $scope.wind24Hr = graphs24HrFactory.wind24Hr;

            $scope.secondsAgo = 0;

            function addZero(number) {
                if (number < 10) {
                    number = "0" + number;
                }
                return number;
            }

            $scope.getDateString = function (timestamp) {
                var time = new Date(timestamp),
                    dateString = addZero(time.getDate()) + '-' +
                        months[time.getMonth()] + '-' +
                        time.getFullYear() + ' ' +
                        addZero(time.getHours()) + ':' +
                        addZero(time.getMinutes()) + ':' +
                        addZero(time.getSeconds());

                return dateString;
            };

            /*
             *Check if the station is still online by checking the the 
             *data time is recent. 
             */
            function checkStationOnline() {

                var timeDif = Date.now() - $scope.current.time;
                if (timeDif > maxOfflineTime) {
                    $scope.stationOnline = false;
                }
            }

            /**
             * Update 24hr graphs
             */
            function update24HrGraphs() {
                var timeOffset = 24 * 3600 * 1000,
                    timeZone = 12 * 3600 * 1000;
                $scope.temp24Hr.series[0].pointStart = Date.parse(
                    $scope.graphs24Hrs.timedate
                ) - timeOffset + timeZone;
                $scope.rainBaro24Hr.series[0].pointStart = Date.parse(
                    $scope.graphs24Hrs.timedate
                ) - timeOffset + timeZone;
                $scope.rainBaro24Hr.series[1].pointStart = Date.parse(
                    $scope.graphs24Hrs.timedate
                ) - timeOffset + timeZone;

                $scope.temp24Hr.series[0].data = $scope.graphs24Hrs.temp;
                $scope.rainBaro24Hr.series[0].data = $scope.graphs24Hrs.rain;
                $scope.rainBaro24Hr.series[1].data = $scope.graphs24Hrs.baro;
            }

            /*
             * Get the JSON data for the 24Hr graphs. 
             */
            $scope.get24HrGraphData = function () {
                $http.get("data/graphs24Hr.json?" + (new Date()).getTime())
                    .then(function (response) {

                        $scope.graphs24Hrs = response.data;

                        update24HrGraphs();

                    });
            };

            /**
             * Update the status of the arrows for the trend data
             */
            function updateTrends() {
                //Temperature Trend
                $scope.current.tempTrend = realtimeService
                    .getTrendIcon($scope.current.tempchangehour.value);
                //Barometric Pressure Trend
                $scope.current.baroTrend = realtimeService
                    .getTrendIcon($scope.current.barometerTrendData.value);
            }

            /**
             * Update data, axes and lables on thermometer guage
             */
            function updateThermometer() {

                var tempOffset = 5.0,
                    thermoBackgroundOffset = 4.1;

                //Update current temperature
                $scope.thermo.series[1].data =
                    [parseFloat($scope.current.outTemp.value)];
                //Set height of background rectangle 
                $scope.thermo.series[0].data =
                    [parseFloat($scope.current.tempMaxValueT.value) +
                        thermoBackgroundOffset];
                //Update Thermometer Scales
                $scope.thermo.yAxis[0].max =
                    parseFloat($scope.current.tempMaxValueT.value) + tempOffset;
                $scope.thermo.yAxis[0].min =
                    parseFloat($scope.current.tempMinValueT.value) - tempOffset;

                //Set High Tick position
                $scope.thermo.yAxis[1].max =
                    parseFloat($scope.current.tempMaxValueT.value) + tempOffset;
                $scope.thermo.yAxis[1].min =
                    parseFloat($scope.current.tempMinValueT.value) - tempOffset;
                $scope.thermo.yAxis[1].tickPositions =
                    [parseFloat($scope.current.tempMaxValueT.value)];

                //Set Low Tick position
                $scope.thermo.yAxis[2].max =
                    parseFloat($scope.current.tempMaxValueT.value) + tempOffset;
                $scope.thermo.yAxis[2].min =
                    parseFloat($scope.current.tempMinValueT.value) - tempOffset;
                $scope.thermo.yAxis[2].tickPositions =
                    [parseFloat($scope.current.tempMinValueT.value)];
            }

            /**
             * Update Live Temperture
             */
            function updateLiveTempGraph() {

                $scope.liveTemp.series[0].data =
                    $scope.liveTemp.series[0].data
                    .concat([[(new Date()).getTime(),
                            parseFloat(orgRealtimeData.outTemp.value)]]);
            }
            /**
             * Update Wind Vane data
             */
            function updateWindVane() {
                $scope.windVane.series[0].data =
                    [parseFloat($scope.current.windDir)];
            }

            /**
             * Update Live Wind data
             */
            function updateLiveWindGraph() {
                $scope.liveWind.series[0].data = $scope.liveWind.series[0].data
                    .concat([[(new Date()).getTime(),
                            parseFloat(orgRealtimeData.windSpeed.value)]]);
            }
            /**
             * Update Live Rainfall data
             */
            function updateLiveRainGraph() {
                var rain = orgRealtimeData.rainRate.value;
                $scope.liveRain.series[0].data =
                    $scope.liveRain.series[0].data
                    .concat([[(new Date()).getTime(), parseFloat(rain[0])]]);
            }
            /**
             * Update the realtime charts
             */
            function updateLiveData() {
                updateTrends();
                updateThermometer();
                updateLiveTempGraph();
                updateWindVane();
                updateLiveWindGraph();
                updateLiveRainGraph();
            }

            /**
             * Restart the ajaxRealtime updates. 
             */
            $scope.restartUpdates = function () {
                $scope.startUpdate(delay, totUpdates);
                $scope.hideRestart = true;
            };

            /*
             * Check if live updates need to be paused, if so, show the restart 
             * updates button, but only if the station is online. 
             */
            function pauseUpdates(numUpdates) {
                if (numUpdates >= totUpdates) {
                    if ($scope.stationOnline) {
                        $scope.hideRestart = false;
                    }
                }
            }

            function convertRealtimeUnits() {
                if ($scope.selectedUnit === 'Metric') {
                    //Realtime Values
                    angular.forEach(orgRealtimeData, function (value, key) {

                        $scope.current[key] = value;
                    });
                } else if ($scope.selectedUnit === 'Imperial') {
                    angular.forEach(realtimeConvertable, function (measure) {
                        $scope.current[measure] = unitConverter.convert(
                            orgRealtimeData[measure]
                        );
                    });
                }
            }

            function convertExtremes() {
                if ($scope.selectedUnit === 'Metric') {
                    //Realtime Values
                    angular.forEach(orgExtremes, function (value, key) {
                        $scope.extremes[key] = value;
                    });
                } else if ($scope.selectedUnit === 'Imperial') {
                    angular.forEach(orgExtremes, function (day, key) {
                        angular.forEach(day, function (measure, idx) {
                            var converted = unitConverter.convert(
                                {"value": measure.value, "unit": measure.unit}
                            );

                            $scope.extremes[key][idx].value = converted.value;
                            $scope.extremes[key][idx].unit = converted.unit;

                        });
                    });
                }
            }

            $scope.convertUnits = function (metric) {
                $scope.selectedUnit = metric;
                convertRealtimeUnits();
                convertExtremes();
                updateThermometer();

            };

            /**
             * Restart the rapid updates. 
             */
            function stopRapidUpdate() {
                $interval.cancel(rapidTimer);
            }

            /**
             * Stops the ajax timer
             * 
             */
            function stopAjaxTimer() {
                $timeout.cancel(timer);

            }

            /*
             * Resets ans starts the ajax timer
             */
            function startAjaxTimer() {
                stopAjaxTimer();
                $scope.secondsAgo = 0;
                timer = $timeout($scope.ajaxTimer, 1000);
            }

            /**
             * The timer which counts how many seconds have passed since 
             * the last ajax update.
             */
            $scope.ajaxTimer = function () {
                if ($scope.secondsAgo < maxTime) {
                    $scope.secondsAgo += 1;
                    timer = $timeout($scope.ajaxTimer, 1000);

                } else {
                    stopAjaxTimer();
                    stopRapidUpdate();
                }

            };

            function rapidUpdate() {
                $http.get("data/now.json?" + (new Date()).getTime())
                    .success(function (data) {

                        angular.forEach(data, function (value, key) {
                            if (key !== 'units') {
                                if (key === 'time') {
                                    var time = new Date(value * 1000);

                                    orgRealtimeData[key] = time;
                                } else if (key === 'windDir') {
                                    orgRealtimeData[key] = value;
                                } else {
                                    orgRealtimeData[key].value = value;
                                }
                            }
                        });
                        convertRealtimeUnits();
                        checkStationOnline();
                        startAjaxTimer();
                        updateLiveData();

                    });
            }

            /**
             * Begin a season of rapid updates  up 
             * until the spcified number of updates and a specified delay.
             * @param {int} delay time in millisecond for delay between updates
             * @returns None
             */
            function startRapidUpdate(delay) {
                rapidUpdate();
                rapidTimer = $interval(rapidUpdate, delay);
            }
            /**
             * Retreive realtime JSON data and update the dashboard
             */
            function ajaxRealtime() {
                if ($scope.stationOnline) {

                    $http.get("data/realtime.json?" + (new Date()).getTime())
                        .success(function (response) {


                            orgRealtimeData = response;
                            $scope.numUpdates += 1;
                            pauseUpdates($scope.numUpdates);
                            rapidUpdate();




                        });

                    $http.get("data/extremes.json?" + (new Date()).getTime())
                        .success(function (response) {
                            orgExtremes = response;
                            convertExtremes();
                        });
                } else {
                    $interval.cancel(ajaxRealtimeTimer);
                    stopRapidUpdate();
                }
            }
            /**
             * Begin a season of realtime updates  up 
             * until the spcified number of updates and a specified delay.
             * @param {int} delay time in millisecond for delay between updates
             * @param {int} numUpdates total updates before stoping
             * @returns None
             */
            $scope.startUpdate = function (delay, numUpdates) {
                $scope.numUpdates = 0;
                ajaxRealtime();
                startRapidUpdate(rapidIntervel);
                ajaxRealtimeTimer = $interval(ajaxRealtime, delay, numUpdates);

            };

            $scope.$on('$destroy', function () {
                $interval.cancel(ajaxRealtimeTimer);
                stopRapidUpdate();
                stopAjaxTimer();

            });

            ajaxRealtime();
            $scope.get24HrGraphData();
            $scope.startUpdate(delay, totUpdates - 1);


        }]);
}());