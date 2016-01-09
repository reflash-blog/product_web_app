app.controller('listController', function ($scope, $http) {
    $scope.showModal = false;
    $scope.toggleModal = function () {
        $scope.showModal = !$scope.showModal;
    };

    $scope.sortOrder = 'id';


    $scope.update = function () {
        var url = 'list.php';
        $http.get(url).
            success(function(data, status, headers, config) {
                $scope.products = data;
            }).
            error(function (data, status, headers, config) {
                $scope.errorCode = status;
                $scope.errorMessage = data.message;
                $scope.toggleModal();
            });
    }

    $scope.update();
});