var app = angular.module('teacherApp', []);

app.controller('teacherCtrl', function($scope, $http){
    $scope.page = '/teacher/template/main.html';
});