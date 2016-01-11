app.controller('editController', function ($scope, $http, $routeParams, appService) {
    $scope.product = new Array();

    $scope.get = function() {
        appService.getProduct($routeParams.id).
            success(function(data, status, headers, config) {
                // Correct the output
                data.price = parseFloat(data.price);
                $scope.product = data;
            }).
            error(function(data, status, headers, config) {
                appService.showError($scope, status, data.message);
            });
    }

    $scope.submit = function() {
        appService.submit('edit.php', $scope.product).
            success(function(data, status, headers, config) {
                appService.changeLocation($scope, '/');
            }).
            error(function(data, status, headers, config) {
                appService.showError($scope, status, data.message);
            });
    }

    $scope.$on('$routeChangeSuccess', $scope.get);

    $scope.close = function() {
        appService.changeLocation($scope, '/');
    }
});