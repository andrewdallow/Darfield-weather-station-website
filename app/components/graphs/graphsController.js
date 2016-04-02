/*global Highcharts, angular */
(function () {
    'use strict';
    angular.module('wxApp').controller('graphsController', [
        '$scope', '$http', 'graphsFactory',
        function ($scope, $http, graphsFactory) {

            $scope.loading = true;

            $scope.page.setTitle('Graphs');
            $scope.page.setDescription(
                '12-hr, 24-hr, and 48-hr graphs of weather data at Darfield, ' +
                    'New Zealand'
            );

            $scope.charts = [];

            $scope.selected = 0;

            $scope.buttons = [
                {
                    'time': 12
                },
                {
                    'time': 24
                },
                {
                    'time': 48
                }
            ];



            /**
             * Get the JSON data for graphs tp plot
             * @returns {response.data}
             */
            function makeGraphs() {

                $http.get("components/graphs/realTimeLogSQL.php")
                    .success(function (response) {
                        $scope.charts = graphsFactory.loadGraphs(response);
                        $scope.loading = false;
                    });
            }

            function getPoints(chart, e) {
                var points = [];
                // Get the hovered points
                Highcharts.each(chart.series, function (series) {
                    var point = series.searchPoint(e, true);
                    if (series.name !== 'Navigator') {
                        if (point) {
                            points.push(point);
                            point.onMouseOver(); // Show the hover marker
                        }
                    }
                });
                return points;
            }


            $scope.graphCombo = function (e) {
                var chart,
                    points,
                    i;

                for (i = 0; i < Highcharts.charts.length; i = i + 1) {
                    chart = Highcharts.charts[i].getHighcharts();

                    // Find coordinates within the chart
                    e = chart.pointer.normalize(e);

                    points = getPoints(chart, e);

                    if (points.length > 0) {
                        chart.tooltip.refresh(points); // Show the tooltip

                        // Show the crosshair
                        chart.xAxis[0].drawCrosshair(e, points[0]);
                    }
                }

            };

            $scope.setExtreme = function (hours, index) {

                var hour = 60 * 60 * 1000;

                $scope.selected = index;
                angular.forEach($scope.charts, function (data) {

                    data.chart.xAxis.currentMin = data.chart.xAxis.currentMax -
                        hours * hour;

                });
            };

            function main() {
                makeGraphs();
            }

            main();
        }]);
}());