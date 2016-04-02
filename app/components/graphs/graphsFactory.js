/*global Highcharts, angular */
(function () {
    'use strict';
    angular.module('wxApp').factory('graphsFactory', function () {
        var graphsFactory = {
            loadGraphs: function (data) {
                Highcharts.charts = [];
                var charts = [],
                    xData = data.xData,
                    datasets = data.datasets,
                    id;
                //create chart for each dataset
                angular.forEach(datasets, function (dataset) {
                    var chart;
                    //set the x and y data array
                    angular.forEach(dataset, function (series) {
                        series.data = Highcharts.map(series.data,
                            function (val, j) {
                                return [xData[j], val];
                            });
                    });
                    chart = {
                        options: {
                            chart: {
                                marginLeft: 40, // Keep all charts left aligned
                                spacingTop: 20,
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
                                        tag =
                                            '<span class="graphToolTip">' +
                                            '<span style="color:',
                                        dotTag = '">\u25CF</span> ';
                                    for (index = 0; index < pointsLength;
                                            index += 1) {
                                        s.push(tag + points[index]
                                                        .series.color +
                                            dotTag +
                                            points[index].series.name + ': ' +
                                            points[index].y +
                                            points[index]
                                                .series.userOptions.unit +
                                            '<span>'
                                            );
                                    }

                                    return s.join(', ');
                                },
                                positioner: function () {
                                    return {
                                        x: this.chart.chartWidth -
                                            this.label.width, // right aligned
                                        y: -1 // align to title
                                    };
                                }


                            }

                        },
                        loading: false,
                        series: [],
                        size: {},
                        xAxis: {
                            currentMin: xData[xData.length - 1] -
                                (12 * 60 * 60) * 1000,
                            currentMax: xData[xData.length - 1]
                        },
                        func: function () {
                            Highcharts.Pointer.prototype.reset = function () {
                                return undefined;
                            };
                        }
                    };


                    //assign the data of the graph
                    chart.series = dataset;
                    //set the dimensions of the graph
                    chart.size = dataset[0].size;
                    //Optionally set min and max of y axis
                    if (typeof dataset[0].ymin !== 'undefined') {
                        chart.options.yAxis[0].min = dataset[0].ymin;
                    }
                    if (typeof dataset[0].ymax !== 'undefined') {
                        chart.options.yAxis[0].max = dataset[0].ymax;
                    }

                    //Set custom tick positions of the graph
                    if (typeof dataset[0].tickPositions !== 'undefined') {
                        chart.options.yAxis[0].tickPositions =
                            dataset[0].tickPositions;
                    }

                    //Change wind Direction labels
                    if (dataset[0].name === 'Wind Direction') {
                        chart.options.yAxis[0].labels = {
                            formatter: function () {
                                var dirs = ["N", "NNE", "NE", "ENE", "E", "ESE",
                                    "SE", "SSE", "S", "SSW", "SW", "WSW", "W",
                                    "WNW", "NW", "NNW", "N"];
                                return dirs[Math.round(this.value / 22.5)];
                            }
                        };
                    }


                    id = dataset[0].name.substr(0, 1).toLowerCase() +
                            dataset[0].name.substr(1);
                    id = id.replace(/\s/g, '');
                    charts = charts.concat([
                        {
                            "id": id,
                            "chart": chart
                        }
                    ]);

                    Highcharts.charts.push(chart);

                });

                return charts;
            }
        };
        /**
         * Create the graphs from the given data
         * @param {array} data array of graph data
         * @returns {undefined}
         */


        return graphsFactory;
    });
}());