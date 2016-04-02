/*global */

angular.module('wxApp').controller('webcamController', [
    '$scope', '$http',
    function ($scope, $http) {
        'use strict';

        $scope.page.setTitle('Webcam');
        $scope.page.setDescription(
            "Weather Webcam looking south of Darfield, New Zealand."
        );

        var fileNameTmpl = 'webcamimage',
            webcamIntervel = 60 * 5 * 1000,
            timeOffset = 8 * 60 * 1000;

        $scope.webcamImagesPath = "data/webcam_img";
        $scope.currentSelection = fileNameTmpl + "0";
        $scope.webcamExt = '.jpg';
        $scope.cacheReset = Date.now();

        $scope.maxImages = 1000;
        $scope.imagesPerPage = 12;

        $scope.webcamImages = [];


        function getDateTime(date) {
            var datetime = new Date(date),
                day = datetime.getDate(),
                month = datetime.getMonth() + 1,
                year = datetime.getFullYear(),
                hour = datetime.getHours(),
                min = datetime.getMinutes();

            if (hour.toString().length === 1) {
                hour = "0" + hour;
            }

            if (min.toString().length === 1) {
                min = "0" + min;
            }

            return day + '/' + month + '/' + year + ' ' + hour + ':' + min;
        }

        function loadImageNames() {
            $http.get(
                "components/webcam/webcamFiles.php?" + (new Date()).getTime()
            ).success(function (response) {

                var latestImage = response, i;

                for (i = 0; i < $scope.maxImages + 1; i += 1) {
                    $scope.webcamImages.push({
                        'name': fileNameTmpl + i,
                        'date': getDateTime(latestImage[0].time * 1000 -
                            (webcamIntervel * i) - timeOffset)
                    });
                }
            });
        }
        /**
         * Set the currently selected image. 
         * @param {type} image path
         * @returns {undefined}
         */
        $scope.selectImage = function (image) {
            $scope.currentSelection = image;
        };



        loadImageNames();


    }]);