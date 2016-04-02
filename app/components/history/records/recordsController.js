/* global angular, Highcharts */
(function () {
    'use strict';
    angular.module('wxApp').controller('recordsController', [
        '$scope', '$http',
        function ($scope, $http) {

            $scope.page.setTitle('Records');
            $scope.page.setDescription(
                "All-time weather records for Darfield Weather Station"
            );

            var getRecords = function () {

                $http.get("data/records.json?" + (new Date()).getTime())
                    .success(function (response) {
                        $scope.records = response;
                    });
            };


            getRecords();

        }]);
}());