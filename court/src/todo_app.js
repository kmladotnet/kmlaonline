var app = angular.module("myApp", []);

var todoList = [ { done: true, title: "AngularJS 독서"},
    {done: false, title: "ACT Reading"},
    {done: false, title: "Studying Set Theory"}
];


app.controller('todoCtrl', function($scope){
    $scope.appName = "AngularJS TODO APP";
    $scope.todoList = todoList;

    $scope.addNewTodo = function(newTitle){
        todoList.push({ done: false, title: newTitle });
        $scope.newTitle = '';
    };

    $scope.archive = function(){
        for(var i = $scope.todoList.length - 1; i >= 0; i--) {
            if($scope.todoList[i].done) {
                $scope.todoList.splice(i, 1);
            }
        }
    };

    $scope.remain = function(){
        var count = 0;
        for(var i = 0; i < $scope.todoList.length; i++){
            if(!$scope.todoList[i].done) count++;
        }

        return count;
    }
});



