/*global*/


angular.module('wxApp').controller('mainCtrl', 
['$scope', 'page', 
    function ($scope, page) {
        
        $scope.page = page;
        
}]);