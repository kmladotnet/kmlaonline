var app = angular.module('courtApp', ['ngRoute', 'ngAnimate', 'toaster']);

app.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
        when('/login', {
            title: 'Login',
            templateUrl: 'template/login.html',
            controller: 'authCtrl'
        })
            .when('/logout', {
                title: 'Logout',
                templateUrl: 'template/login.html',
                controller: 'logoutCtrl'
            })
            .when('/dashboard', {
                title: 'Court Dashboard',
                templateUrl: 'template/dashboard.html',
                controller: 'authCtrl'
            })
            .when('/', {
                title: 'Login',
                templateUrl: 'template/login.html',
                controller: 'authCtrl',
                role: '0'
            })
            .otherwise({
                redirectTo: '/login'
            });
    }])
    .run(function($rootScope, $location, Data) {
        $rootScope.$on("$routeChangeStart", function (event, next, current){
            $rootScope.authenticated = false;
            Data.get('session').then(function (results){
                if(results.userid) {
                    $rootScope.authenticated = true;
                    $rootScope.userid = results.userid;
                    $rootScope.name = results.name;
                    $rootScope.grade = results.grade;
                    $rootScope.studentid = results.studentid;
                } else {
                    var nextUrl = next.$$route.originalPath;
                    if(nextUrl == '/signup' || nextUrl == '/login') {

                    } else {
                        $location.path("/login");
                    }
                }
            })
        })
    })