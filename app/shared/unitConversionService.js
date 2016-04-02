/*global */

angular.module('wxApp').factory('unitConversionService', function () {
    'use strict';

    var unitConverter = {
        units: {
            " km": [" mi", function (x) {
                    return Math.round(x * 0.621371);
                }],
            " km/h": [" mph", function (x) {
                    return Math.round(x / 1.60934);
                }],
            " mi": [" km", function (x) {
                    return Math.round(x * 1.60934);
                }],
            " mph": [" km/h", function (x) {
                    return Math.round(x * 1.60934);
                }],
            " mm": [" in", function (x) {
                    return (Math.round((x * 0.0393701) * 10) / 10).toFixed(1);
                }],
            " mm/hr": [" in/hr", function (x) {
                    return (Math.round((x * 0.0393701) * 10) / 10).toFixed(1);
                }],
            " in": [" mm", function (x) {
                    return Math.round((x * 0.0393701) * 10) / 10;
                }],
            " in/hr": [" mm/hr", function (x) {
                    return Math.round((x * 0.0393701) * 10) / 10;
                }],
            " hPa": [" inHg", function (x) {
                    return Math.round((x * 0.02952998751) * 100) / 100;
                }],
            " hPa/hr": [" inHg/hr", function (x) {
                    return Math.floor((x * 0.02952998751) * 100) / 100;
                }],
            " inHg": [" hPa", function (x) {
                    return Math.round((x * 3386.39) * 1000) / 1000;
                }],
            "&degF": ["&degC", function (x) {
                    return (Math.round(((x - 32.0) * (5.0 / 9.0))* 10) / 10).toFixed(1);
                }],
            "&degC": ["&degF", function (x) {
                    return (Math.round((x * (9.0 / 5.0) + 32.0) * 10) / 10).toFixed(1);
                }],
            "&degF/hr": ["&degC/hr", function (x) {
                    return (Math.round(((x) * (5.0 / 9.0)) * 100) / 100).toFixed(1);
                }],
            "&degC/hr": ["&degF/hr", function (x) {
                    return (Math.round((x * (9.0 / 5.0))* 10) / 10).toFixed(1);
                }]
        },
        convert: function (measure) {
            /*
             * Convert mesurment to alternative units. 
             * Paramenter is an array [number, 'unit'] 
             */
            if (this.units[measure.unit]){
                var converted = this.units[measure.unit][1](parseFloat(measure.value));
                return {"value": converted, "unit": this.units[measure.unit][0]};
            }            
            return measure;
        }
    };


    return unitConverter;
});


