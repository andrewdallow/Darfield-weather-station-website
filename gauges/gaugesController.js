/*global */

angular.module('wxApp').controller('gaugesController', [
    '$scope',
    function ($scope) {
        'use strict';
        
        $scope.page.setTitle('Guages');
        $scope.page.setDescription("Weather Guages for Darfield, New Zealand.");
    }]);