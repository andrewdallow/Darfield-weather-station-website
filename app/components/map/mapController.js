/*global angular*/
(function () {
    'use strict';
    angular.module('wxApp').controller('mapController', [
        '$scope', 
        function ($scope) {

            $scope.page.setTitle('Weather Map');
            $scope.page.setDescription(
                'Weather Map of Canterbury, New Zealand Weather Stations'
            );          
        }]);
}());