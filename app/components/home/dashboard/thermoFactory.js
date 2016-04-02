/*global */

angular.module('wxApp').factory('thermoFactory', function () {
        'use strict';


        var thermo = {
            options: {
                chart: {
                    backgroundColor: 'rgba(0,0,0,0)',
                    plotBorderWidth: 0,
                    plotShadow: false,
                    height: 150,
                    marginBottom: 50,
                    marginTop: 5,
                    marginLeft: 0,
                    spacingRight: 0
                },
                tooltip: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                series: [
                    {
                        data: [30.5],
                        type: 'column',
                        pointWidth: 28,
                        threshold: -40,
                        borderWidth: 1,
                        borderRadius: 0,
                        borderColor: 'black',
                        name: 'background',
                        grouping: false,
                        color: '#FFFFFF',
                        animation: false
                    },
                    {
                        data: [12],
                        type: 'column',
                        pointWidth: 20,
                        threshold: -40,
                        borderWidth: 0,
                        name: 'Temp'
                    }


                ],
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false
                },
                xAxis: {
                    labels: {
                        enabled: false
                    },
                    lineWidth: 0,
                    tickWidth: 0
                },
                yAxis: [{
                        min: 0,
                        max: 31.5,
                        minPadding: 0,
                        maxPadding: 0,
                        startOnTick: false,
                        endOnTick: false,
                        title: {
                            text: ''
                        },
                        tickInterval: 5,
                        minorTickInterval: 1,
                        gridLineWidth: 0,
                        minorGridLineWidth: 0,
                        tickWidth: 1,
                        minorTickWidth: 1,
                        offset: -52
                    },
                    {
                        min: 0,
                        max: 31.5,
                        minPadding: 0,
                        maxPadding: 0,
                        startOnTick: false,
                        endOnTick: false,
                        title: {
                            text: ''
                        },
                        tickPositions: [20],
                        gridLineWidth: 0,
                        minorGridLineWidth: 0,
                        tickWidth: 2,
                        minorTickWidth: 1,
                        offset: -55,
                        opposite: true,
                        tickColor: '#FF0000',
                        labels: {
                            style: {
                                color: '#FF0000'
                            },
                            formatter: function () {
                                return '';
                            }
                        }
                    },
                    {
                        min: 0,
                        max: 31.5,
                        minPadding: 0,
                        maxPadding: 0,
                        startOnTick: false,
                        endOnTick: false,
                        title: {
                            text: ''
                        },
                        tickPositions: [5],
                        gridLineWidth: 0,
                        minorGridLineWidth: 0,
                        tickWidth: 2,
                        minorTickWidth: 1,
                        offset: -55,
                        opposite: true,
                        tickColor: '#0000FF',
                        labels: {
                            style: {
                                color: '#0000FF'
                            },
                            formatter: function () {
                                return '';
                            }
                        }

                    }

                ],
                plotOptions: {
                    series: {
                        color: '#FF0000'
                    }

                },
                title: {
                    text: ''
                }

            },
            loading: false,
            series: [
                {
                    type: 'column',
                    pointWidth: 21,
                    threshold: -40,
                    borderWidth: 1,
                    borderRadius: 0,
                    borderColor: 'black',
                    name: 'background',
                    grouping: false,
                    color: '#FFFFFF',
                    animation: false
                },
                {
                    type: 'column',
                    pointWidth: 15,
                    threshold: -40,
                    borderWidth: 0,
                    name: 'Temp'
                }
            ],
            yAxis: [{
                    /*Thermometer*/
                    min: 0,
                    max: 30
                },
                {
                    /*High Tick*/
                    min: 0,
                    max: 30,
                    tickPositions: [25]
                },
                {
                    /*Low Tick*/
                    min: 0,
                    max: 30,
                    tickPositions: [0]
                }

            ],
            func: function (chart) {
                // Draw the shape
                var series = chart.series[0],
                        point = series.points[0],
                        radius = 15;
                chart.renderer.circle(
                        chart.plotLeft + point.shapeArgs.x + (point.shapeArgs.width / 2),
                        chart.plotTop + series.yAxis.len + radius - 2.5,
                        17
                        )
                        .attr({
                            fill: 'white',
                            'stroke-width': 1,
                            stroke: 'black'
                        })
                        .add();
                chart.renderer.circle(
                        chart.plotLeft + point.shapeArgs.x + (point.shapeArgs.width / 2),
                        chart.plotTop + series.yAxis.len + radius - 2.5,
                        15
                        )
                        .attr({
                            fill: {
                                radialGradient: {
                                    cx: 0.5,
                                    cy: 0.5,
                                    r: 0.5
                                },
                                stops: [
                                    [0, '#FFFFFF'],
                                    [1, '#FF0000']
                                ]
                            }
                        })
                        .add();
            }
        };

        return thermo;
    });
        