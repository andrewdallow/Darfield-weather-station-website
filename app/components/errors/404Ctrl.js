/*global angular*/
(function () {
    'use strict';
    angular.module('wxApp').controller('404Ctrl', [
        '$scope',
        function ($scope) {

            $scope.page.setTitle('404');
            $scope.page.setDescription(
                '404 - Page Not Found'
            );
        }]);
}());