/*global */

angular.module('wxApp').factory('graphs24HrFactory', function () {
    'use strict';

    var temp24Hr = {
        options: {
            chart: {
                //backgroundColor: '#EFEFFB',
                plotBorderWidth: 0,
                plotShadow: false
            },
            title: {
                useHTML: true,
                text: '<span class="graphTitles">Temperature (&deg;C)</span>',
                align: 'left',
                floating: false
            },
            exporting: {
                enabled: false
            },
            legend: {
                enabled: false
            },
            tooltip: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            xAxis: {
                type: 'datetime',
               
                crosshair: true,
                labels: {
                    enabled: false
                },
                
                tickInterval: 2 * 3600 * 1000,
                gridLineWidth: 0.7,
                gridLineDashStyle: 'longdash'
            },
            yAxis: {
                title: {
                    text: ''
                },
                gridLineWidth: 0,
                labels: {
                    enabled: false
                }
            },
            plotOptions: {
                series: {
                    dataLabels: {
                        useHTML: true,
                        enabled: true,
                        align: 'center',
                        verticalAlign: 'middle',
                        style: {
                            fontSize: '15px'
                        },
                        //formats datalabel colours based on temperature
                        formatter: function () {
                            var color = '#000000';
                            if (this.y >= 30) {
                                color = 'temp8';
                            } else if (this.y >= 25 && this.y < 30) {
                                color = 'temp7';
                            } else if (this.y >= 20 && this.y < 25) {
                                color = 'temp6';
                            } else if (this.y >= 15 && this.y < 20) {
                                color = 'temp5';
                            } else if (this.y >= 10 && this.y < 15) {
                                color = 'temp4';
                            } else if (this.y >= 5 && this.y < 10) {
                                color = 'temp3';
                            } else if (this.y >= 0 && this.y < 5) {
                                color = 'temp2';
                            } else if (this.y < 0) {
                                color = 'temp1';
                            }
                            return '<span class="' + color + '">' + this.y + '</span>';
                        }
                    },
                    marker: {
                        enabled: false
                    },
                    enableMouseTracking: false,
                    lineWidth: 1,
                    color: '#BDBDBD'
                }
            }
        },
        loading: false,
        series: [{
                name: 'Temperature',
                type: 'line',
                pointInterval: 3600 * 1000, // one hour
                data: []
            }]
    };

    var rainBaro24Hr = {
        options: {
            chart: temp24Hr.options.chart,
            title: {
                useHTML: true,
                text: '<span class="graphTitles">Rainfall (mm) and Pressure (hPa)</span>',
                align: 'left',
                floating: false
            },
            exporting: temp24Hr.options.exporting,
            legend: temp24Hr.options.legend,
            tooltip: {
                backgroundColor: '#EFEFFB',
                borderWidth: false,
                shadow: false,
                useHTML: true,
                padding: 0,
                formatter: function () {
                    var s = [];
                    var units = [' mm', ' hPa'];
                    var points = this.points;
                    var pointsLength = points.length;
                    var index;

                    for (index = 0; index < pointsLength; index += 1) {
                        s.push('<span class="graphToolTip"><span style="color:' +
                                points[index].series.color + '">\u25CF</span> ' +
                                points[index].series.name + ': ' +
                                points[index].y + units[index] + '<span>');
                    }

                    return s.join(', ');
                },
                shared: true,
                positioner: function () {
                    return {
                        x: this.chart.chartWidth - this.label.width, // right aligned
                        y: -1 // align to title
                    };
                },
                hideDelay: 600000
            },
            credits: temp24Hr.options.credits,
            xAxis: {
                type: temp24Hr.options.xAxis.type,
                
                
                crosshair: true,
                labels: {
                    enabled: true
                },
                
                tickInterval: temp24Hr.options.xAxis.tickInterval,
                gridLineWidth: temp24Hr.options.xAxis.gridLineWidth,
                gridLineDashStyle: temp24Hr.options.xAxis.gridLineDashStyle
            },
            yAxis: [{
                    //Rainfall
                    title: {
                        text: ''
                    },
                    gridLineWidth: 0,
                    labels: {
                        enabled: false
                    }
                }, {
                    //Pressure
                    title: {
                        text: ''
                    },
                    gridLineWidth: 0,
                    labels: {
                        enabled: false
                    }
                }

            ],
            plotOptions: {
                series: {
                    marker: {
                        enabled: false
                    }
                }
            }
        },
        loading: false,
        series: [{
                name: 'Rainfall',
                type: 'areaspline',
                color: '#58ACFA',
                pointStart: temp24Hr.series[0].pointStart,
                pointInterval: temp24Hr.series[0].pointInterval,
                yAxis: 0 
            },
            {
                name: 'Pressure',
                type: 'line',
                color: '#585858',
                pointStart: temp24Hr.series[0].pointStart,
                pointInterval: temp24Hr.series[0].pointInterval,
                yAxis: 1,
                marker: {
                    enabled: false
                }
            }
        ]
    };


    return {
        'temp24Hr': temp24Hr,
        'rainBaro24Hr': rainBaro24Hr
    };
});
