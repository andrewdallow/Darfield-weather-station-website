/* global angular*/
(function () {
    'use strict';
    angular.module('wxApp').directive('ads', function () {
        return {
            restrict: 'A',
            templateUrl: 'shared/googleAds.html',
            controller: function () {
                (adsbygoogle = window.adsbygoogle || []).push({});
            }
        };
    });
}());
