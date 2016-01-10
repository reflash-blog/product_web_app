app.controller('createController', function($scope, $http, appService) {
    $scope.product = new Array();

    $scope.submit = appService.submit('create.php', $scope.product).
        success(function(data, status, headers, config) {
            appService.changeLocation($scope, '/');
        }).
        error(function(data, status, headers, config) {
            appService.showError($scope, status, data.message);
        });
});

