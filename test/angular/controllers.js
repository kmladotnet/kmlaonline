var controllerModule = angular.module("exampleApp.Controllers", []);

controllerModule.controller("dayCtrl", function($scope, days){
    $scope.day = days.today;
});

controllerModule.controller("tomorrowCtrl", function($scope, days){
    $scope.tomorrow = days.tomorrow;
});