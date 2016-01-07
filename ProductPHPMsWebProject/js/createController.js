function changeLocation($scope, url) {
    $scope = $scope || window.angular.element(document).scope();
    if ($scope.$$phase) {
        window.location = url;
    } else {

        window.$location.path(url);
        $scope.$apply();
    }
}

app.controller('createController', function ($scope, $http) {
    $scope.showModal = false;
    $scope.toggleModal = function () {
        $scope.showModal = !$scope.showModal;
    };

    $scope.product = new Array();

    $scope.submit = function() {
        $http({
                url: 'create.php',
                method: "POST",
                data: JSON.stringify({ 'name': $scope.product.name, 'description': $scope.product.description, 'price': $scope.product.price, 'url': $scope.product.url }),
                headers: { 'Content-Type': 'application/json' }
            }).
            success(function (data, status, headers, config) {
                changeLocation($scope, '/index.html');
            }).
            error(function (data, status, headers, config) {
                $scope.errorCode = status;
                $scope.errorMessage = data.message;
                $scope.toggleModal();
            });
    };
});

