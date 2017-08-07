app.controller('authCtrl', function($scope, $rootScope, $routeParams, $location, $http, Data) {
    $scope.login = {};
    $scope.signup = {};
    $scope.doLogin = function (login_info) {
        Data.post('login', {
            login_info: login_info
        }).then(function (results) {
            Data.toast(results);
            if(results.status == "success") {
                $location.path('dashboard');
            }
        });
    };
    $scope.logout = function () {
        Data.get('logout').then(function(results){
            Data.toast(results);
            $location.path('login');
        });
    }

})