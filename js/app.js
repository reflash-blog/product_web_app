var app = angular.module('productDbApp', ['ngRoute']);

app.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'views/list.html',
                controller: 'listController'
            }).
            when('/create', {
                templateUrl: 'views/create.html',
                controller: 'createController'
            }).
            when('/edit/:id', {
                templateUrl: 'views/edit.html',
                controller: 'editController'
            }).
            when('/delete/:id', {
                templateUrl: 'views/delete.html',
                controller: 'deleteController'
            }).
            otherwise({
                redirectTo: '/'
            });
    }
]);

app.service('appService', function ($http) {
    this.getProduct = function (id) {
        return $http.get('get.php', { params: { 'id': id } });
    }

    this.getProductList = function () {
        return $http.get('list.php');
    }

    this.submit = function(url, product) {
        return $http({
            url: url,
            method: "POST",
            data: JSON.stringify({
                'id': product.id,
                'name': product.name,
                'description': product.description,
                'price': product.price,
                'url': product.url
            }),
            headers: { 'Content-Type': 'application/json' }
        });
    }

    this.showError = function($scope, status, message) {
        $scope.toggleModal = function() {
            $scope.showModal = !$scope.showModal;
        };

        $scope.errorCode = status;
        $scope.errorMessage = message;
        $scope.showModal = true;
    }

    this.changeLocation = function($scope, url) {
        $scope = $scope || window.angular.element(document).scope();
        if ($scope.$$phase) {
            window.location = url;
        } else {
            window.$location.path(url);
            $scope.$apply();
        }
    }

});


app.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
            '<div class="modal-dialog">' +
              '<div class="modal-content">' +
                '<div class="modal-header">' +
                  '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
                  '<h4 class="modal-title">{{ title }}</h4>' +
                '</div>' +
                '<div class="modal-body" ng-transclude></div>' +
              '</div>' +
            '</div>' +
          '</div>',
        restrict: 'E',
        transclude: true,
        replace: true,
        scope: true,
        link: function postLink(scope, element, attrs) {
            scope.title = attrs.title;

            scope.$watch(attrs.visible, function (value) {
                if (value == true)
                    $(element).modal('show');
                else
                    $(element).modal('hide');
            });

            $(element).on('shown.bs.modal', function () {
                scope.$apply(function () {
                    scope.$parent[attrs.visible] = true;
                });
            });

            $(element).on('hidden.bs.modal', function () {
                scope.$apply(function () {
                    scope.$parent[attrs.visible] = false;
                });
            });
        }
    };
});