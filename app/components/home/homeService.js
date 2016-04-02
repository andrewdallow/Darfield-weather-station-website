/*global */

angular.module('wxApp').service('realtimeService', function () {
    'use strict';
    this.getTrendIcon = function (value) {
        var numValue = parseFloat(value), icon;
        if (numValue < 0) {
            icon = 'down';
        } else if (numValue > 0) {
            icon = 'up';
        } else {
            icon = 'minus';
        }
        
        return icon;
    };
});