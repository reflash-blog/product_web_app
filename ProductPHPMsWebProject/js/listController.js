app.controller('listController', function ($scope, $http) {
    $http.get('getlist.php').
        success(function (data, status, headers, config) {
            $scope.products = data;
        });
});