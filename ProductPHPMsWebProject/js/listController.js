app.controller('listController', function($scope, $http, appService) {
    $scope.sortOrder = 'id';

    $scope.update = function() {
        appService.getProductList().
            success(function(data, status, headers, config) {
                $scope.products = data;
            }).
            error(function(data, status, headers, config) {
                appService.showError($scope, status, data.message);
            });
    }

    $scope.update();
});