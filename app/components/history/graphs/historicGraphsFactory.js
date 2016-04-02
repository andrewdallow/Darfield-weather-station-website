/*global angular, Highcharts */
(function () {
    'use strict';
    angular.module('wxApp').factory('historicGraphsFactory',
        ['$timeout', function ($timeout) {
            var graphsFactory = {
                    loadGraphs: function (data) {
                        Highcharts.charts = [];
                        var datasets = data.datasets,
                            chart;

                        chart = {
                            options: {
                                chart: {
                                    marginLeft: 40, // Keep charts left aligned
                                    spacingTop: 30,
                                    spacingBottom: 20
                                },
                                title: {
                                    text: "",
                                    align: 'left',
                                    margin: 0,
                                    x: 30
                                },
                                credits: {
                                    enabled: false
                                },
                                legend: {
                                    enabled: true,
                                    align: 'left',
                                    verticalAlign: 'top'

                                },
                                plotOptions: {
                                    series: {
                                        fillOpacity: 0.9
                                    },
                                    line: {
                                        marker: {
                                            enabled: false,
                                            lineWidth: 1
                                        }
                                    },
                                    areaspline: {
                                        marker: {
                                            enabled: false
                                        }
                                    },
                                    area: {
                                        marker: {
                                            enabled: false
                                        }
                                    },
                                    scatter: {
                                        marker: {
                                            states: {
                                                hover: {
                                                    enabled: true
                                                }
                                            }
                                        }
                                    }
                                },
                                xAxis: {
                                    crosshair: true,
                                    type: 'datetime'
                                },
                                yAxis: [{
                                    lineWidth: 1,
                                    title: {
                                        text: null
                                    }
                                }, {
                                    title: {
                                        text: null
                                    }
                                }],
                                exporting: {
                                    enabled: false
                                },
                                tooltip: {
                                    crosshairs: true,
                                    backgroundColor: '#EFEFFB',
                                    borderWidth: false,
                                    shared: true,
                                    shadow: false,
                                    useHTML: true,
                                    formatter: function () {
                                        var s = [],
                                            points = this.points,
                                            pointsLength = points.length,
                                            index,
                                            str,
                                            tag =
                                                '<span class="graphToolTip">' +
                                                '<span style="color:';
                                        for (index = 0; index < pointsLength;
                                                index += 1) {
                                            str = tag +
                                                points[index].series.color +
                                                '">\u25CF</span> ' +
                                                points[index].series.name +
                                                ': ';
                                            if (points[index].series.type ===
                                                    "columnrange" ||
                                                    points[index]
                                                        .series.type ===
                                                        "arearange") {
                                                str = str +
                                                    points[index].point.low +
                                                    points[index].series
                                                        .userOptions.unit +
                                                    " - " +
                                                    points[index].point.high +
                                                    points[index].series
                                                        .userOptions.unit +
                                                    '<span>';
                                            } else {
                                                str = str +
                                                    points[index].y +
                                                    points[index].series
                                                        .userOptions.unit +
                                                    '<span>';
                                            }
                                            s.push(str);
                                        }
                                        return s.join(', ');
                                    },
                                    positioner: function () {
                                        return {
                                            // right aligned
                                            x: this.chart.chartWidth -
                                                this.label.width,
                                            y: -1 // align to title
                                        };
                                    }
                                }

                            },
                            loading: false,
                            series: [],
                            size: {
                            },
                            resetChartSize: function (chart) {
                                chart.reflow();
                            },
                            func: function (chart) {

                                $timeout(function () {
                                    chart.reflow();
                                }, 0);

                                Highcharts.Pointer.prototype.reset =
                                    function () {
                                        return undefined;
                                    };
                            }
                        };

                        //assign the data of the graph
                        chart.series = datasets;

                        if (typeof data.categories !== 'undefined') {
                            chart.options.xAxis.categories = data.categories;
                        }

                        if (typeof data.yAxis !== 'undefined') {
                            if (typeof data.yAxis.plotBands !== 'undefined') {
                                chart.options.yAxis[0].plotBands = 
                                    data.yAxis.plotBands;
                            }
                            if (typeof data.yAxis.plotLines !== 'undefined') {
                                chart.options.yAxis[0].plotLines = 
                                    data.yAxis.plotLines;
                            }
                        }
                        return chart;
                    }
                };
                /**
                 * Create the graphs from the given data
                 * @param {array} data array of graph data
                 * @returns {undefined}
                 */


            return graphsFactory;
        }]);
}());