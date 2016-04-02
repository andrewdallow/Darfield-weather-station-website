/*global angular*/
(function () {
    'use strict';
    angular.module('wxApp').controller('aboutController', [
        '$scope',
        function ($scope) {

            $scope.page.setTitle('About');
            $scope.page.setDescription(
                'Information on the Darfield Weather Station and website'
            );
        }]);
}());