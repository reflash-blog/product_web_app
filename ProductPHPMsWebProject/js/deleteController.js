function changeLocation($scope, url) {
    $scope = $scope || window.angular.element(document).scope();
    if ($scope.$$phase) {
        window.location = url;
    } else {

        window.$location.path(url);
        $scope.$apply();
    }
}

app.controller('deleteController', function ($scope, $http, $routeParams) {
    $scope.showModal = false;
    $scope.toggleModal = function () {
        $scope.showModal = !$scope.showModal;
    };

    $scope.product = new Array();
    $scope.product.id = $routeParams.id;

    // Get the product by id
    $http.get('edit.php', {params:{ 'id': $scope.product.id }}).
        success(function (data, status, headers, config) {
            // Correct the output
            data.price = parseFloat(data.price);
            $scope.product = data;
        }).
        error(function(data, status, headers, config) {
            $scope.errorCode = status;
            $scope.errorMessage = data.message;
            $scope.toggleModal();
        });

    $scope.submit = function () {
        $http({
                url: 'delete.php',
                method: "POST",
                data: JSON.stringify({ 'id': $scope.product.id}),
                headers: { 'Content-Type': 'application/json' }
            }).
            success(function (data, status, headers, config) {
                changeLocation($scope, '/');
            }).
            error(function (data, status, headers, config) {
                $scope.errorCode = status;
                $scope.errorMessage = data.message;
                $scope.toggleModal();
            });
    };

    $scope.close = function() {
        changeLocation($scope, '/');
    }
});

