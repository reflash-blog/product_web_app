app.controller('listController', function ($scope, $http, appService) {
    $scope.sortValue = 'id';
    $scope.sortOrder = 'ASC';

    $scope.nextPage = function () {
        if ($scope.loadingEnded) return;
        if ($scope.busy) return;
        $scope.busy = true;

        appService.getProductList($scope.start, $scope.amount, $scope.sortValue, $scope.sortOrder).
            success(function (data, status, headers, config) {
                if (data.length === 0) $scope.loadingEnded = true;
                $scope.products.push.apply($scope.products, data);
                $scope.start += $scope.amount;
                $scope.busy = false;
            }).
            error(function (data, status, headers, config) {
                appService.showError($scope, status, data.message);
            });
    }

    $scope.refresh = function () {
        $scope.start = 0;
        $scope.amount = 20;
        $scope.products = new Array();
        $scope.busy = false;
        $scope.loadingEnded = false;
    }

    $scope.$on('$routeChangeSuccess', $scope.refresh);
});