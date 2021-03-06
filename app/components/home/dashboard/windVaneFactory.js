/*global */

angular.module('wxApp').factory('windVaneFactory', ['$timeout', function ($timeout) {
        'use strict';
        
        
         /* 
         * WIND VANE
         */
        var langWindDir = ["N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW"];
        var windDirLang = function (dir) {
            return langWindDir[Math.floor(((parseInt(dir, 10) + 11.25) / 22.5))];
        };

        var windVane = {
            options: {
                chart: {
                    type: 'gauge',
                    backgroundColor: 'rgba(0,0,0,0)',
                    plotBorderWidth: 0,
                    plotShadow: false,
                    height: 100
                },
                title: {
                    text: '',
                    style: {
                        display: 'none'
                    }
                },
                subtitle: {
                    text: '',
                    style: {
                        display: 'none'
                    }
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                pane: {
                    startAngle: 0,
                    endAngle: 360,
                    background: [{
                            borderWidth: 0,
                            backgroundColor: 'transparent'
                        }]
                },
                yAxis: {
                    min: 0,
                    max: 360,
                    offset: -6,
                    tickPosition: 'outside',
                    tickInterval: 22.5,
                    tickWidth: 0,
                    minorGridLineWidth: 0,
                    minorTickLength: 0,
                    tickColor: '#A4A4A4',
                    lineColor: '#A4A4A4',
                    lineWidth: 2,
                    labels: {
                        distance: 14,
                        step: 4,
                        formatter: function () {
                            return windDirLang(this.value);
                        },
                        style: {
                            fontSize: '12px',
                            color: 'grey'
                        }
                    }
                },
                plotOptions: {
                    gauge: {
                        dial: {
                            radius: '100%',
                            backgroundColor: 'red',
                            borderColor: 'white',
                            borderWidth: 1,
                            baseWidth: 1,
                            topWidth: 15,
                            baseLength: '55%', // of radius
                            rearLength: '-55%'
                        },
                        pivot: {
                            radius: 0
                        },
                        dataLabels: {
                            enabled: false
                        }
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            series: [{
                    data: [180],
                    animation: {
                        duration: 2000
                    }
                }],
            loading: false,
            func: function (chart) {
                $timeout(function () {
                    chart.reflow();
                }, 0);
            }
    };
    
    return windVane;
}]);
        