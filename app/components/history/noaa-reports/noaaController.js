/* global angular, Highcharts */
(function () {
    'use strict';
    angular.module('wxApp').controller('noaaController', [
        '$scope', '$http',
        function ($scope, $http) {
            $scope.page.setTitle('NOAA-style Climate Reports');
            $scope.page.setDescription(
                "NOAA-style Climate Summaries for Darfield, New Zealand"
            );

            var dir = 'data/reports/',
                fileExt = '.txt',
                monthTpl = "NOAAMO",
                monthNamesShort = {
                    "01": "January",
                    "02": "February",
                    "03": "March",
                    "04": "April",
                    "05": "May",
                    "06": "June",
                    "07": "July",
                    "08": "August",
                    "09": "September",
                    "10": "October",
                    "11": "November",
                    "12": "December"
                };
            $scope.noaaFile = "";
            $scope.selectedMonth = "";
            $scope.selectedYear = "";

            $scope.months = [
                {"short": "Jan", "num": "01", "disabled": false},
                {"short": "Feb", "num": "02", "disabled": false},
                {"short": "Mar", "num": "03", "disabled": false},
                {"short": "Apr", "num": "04", "disabled": false},
                {"short": "May", "num": "05", "disabled": false},
                {"short": "Jun", "num": "06", "disabled": false},
                {"short": "Jul", "num": "07", "disabled": false},
                {"short": "Aug", "num": "08", "disabled": false},
                {"short": "Sep", "num": "09", "disabled": false},
                {"short": "Oct", "num": "10", "disabled": false},
                {"short": "Nov", "num": "11", "disabled": false},
                {"short": "Dec", "num": "12", "disabled": false}
            ];

            function getNoaaFilenames() {

                $http.get("components/history/noaa-reports/noaaFiles.php?" +
                    (new Date()).getTime()
                    ).success(
                    function (response) {
                        $scope.filenames = response;
                        $scope.getYearData($scope.filenames[0]);
                        $scope.selectedMonth = "";
                    }
                );
            }

            function getFilePath(file) {
                return dir + file + fileExt;
            }

            $scope.getYearData = function (name) {
                $scope.selectedYear = $scope.getYearName(name.year);
                $scope.noaaFile = getFilePath(name.year);
                $scope.selectedMonth = "";

                angular.forEach($scope.months, function (month) {
                    var monthFile = "NOAAMO" + month.num + $scope.selectedYear;
                    if (name.months.indexOf(monthFile) !== -1) {
                        month.disabled = false;
                    } else {
                        month.disabled = true;
                    }

                });

            };

            $scope.getMonthData = function (name) {
                $scope.selectedMonth = monthTpl + name.num +
                    $scope.selectedYear;
                $scope.noaaFile = getFilePath($scope.selectedMonth);

            };

            $scope.getYearName = function (name) {
                return name.substring(6);
            };

            $scope.getMonthName = function (monthFile) {
                if (monthFile !== "") {
                    return monthNamesShort[monthFile.substring(6, 8)];
                }

                return "";
            };
            getNoaaFilenames();
        }]);
}());