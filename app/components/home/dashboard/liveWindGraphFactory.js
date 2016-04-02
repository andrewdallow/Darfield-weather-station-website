/*global */

angular.module('wxApp').factory('liveWindGraphFactory', function () {
    'use strict';

    var liveWind = {
        options: {
            chart: {
                backgroundColor: 'rgba(0,0,0,0)',
                plotBorderWidth: 0,
                plotShadow: false,
                height: 70,
                spacingLeft: 0,
                spacingRight: 0
            },
            xAxis: {
                categories: [1, 2],
                labels: {
                    enabled: false
                },
                lineWidth: 0,
                gridLineWidth: 0,
                minorGridLineWidth: 0,
                lineColor: 'transparent',
                minorTickLength: 0,
                tickLength: 0
            },
            exporting: {
                enabled: false
            },
            yAxis: {
                labels: {
                    enabled: false
                },
                title: {
                    text: ''
                },
                gridLineColor: 'transparent'
            },
            title: {
                text: ''
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                series: {
                    marker: {
                        enabled: false
                    }
                }
            },
            tooltip: {
                shadow: false,
                borderWidth: 0,
                useHTML: true,
                formatter: function () {
                    var date = new Date(this.x);
                    // Hours part from the timestamp
                    var hours = date.getHours();
                    // Minutes part from the timestamp
                    var minutes = "0" + date.getMinutes();
                    // Seconds part from the timestamp
                    var seconds = "0" + date.getSeconds();
                    // Will display time in 10:30:23 format
                    var formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
                    return formattedTime + '</br>' + this.y + ' km/h';
                }
            }
        },
        loading: false,
        series: [{
                data: [],
                type: 'line',
                color: '#00FF00'
            }]
    };
    return liveWind;
});
