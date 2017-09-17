'use strict';

var app = angular.module("bbqApp", ['ui.bootstrap', 'ngSanitize', 'ui.select', 'ngAnimate']);

app.controller("bbqCtrl", function($scope, $http, $uibModal, $document, $log){

    $scope.new_bbq = {};
    $scope.status = "ready";
    $scope.bbqList = [];

    $scope.items = ['item1', 'item2', 'item3'];
    $scope.animationEnabled = true;


    $scope.openModal=function(bbq_id){
        $scope.modalInstance=$uibModal.open({
            ariaLabelledBy: 'detail-modal-header',
            ariaDescribedBy: 'detail-modal-body',
            templateUrl: 'myTestModal.tmpl.html',
            size: 'lg',
            scope:$scope
        });

        console.log(bbq_id);
        console.log($scope.bbqList.find($scope._findBBQById));
    }

    $scope.close=function(){
        $scope.modalInstance.dismiss();//$scope.modalInstance.close() also works I think
    };

    $scope.doSomething=function(){
        //any actions to take place
        console.log("Do Something");
    }
    /*$scope.open = function(size){
         $scope.modalInstance = $uibModal.open({
            animation: $scope.animationEnabled,
            ariaLabelledBy: 'modal-title',
            ariaDescribedBy: 'modal-body',
            templateUrl: 'viewDetails.html',
            scope: $scope,
            size: size,
            resolve: function() {
                return $scope.items;
            }
        });

        $scope.modalInstance.result.then(function(selectedItem){
            $scope.selected = selectedItem;
        }, function (){
            $log.info('Modal dismissed at: ' + new Date());
        });
    };*/

    $scope.init = function() {
        $scope.config_calender();
        $scope.studentFetch();
        $scope.changePage('home');
        $scope.dateSelected = false;
    }

    $scope.config_calender = function(){

        $scope.hourArray = [];
        $scope.minArray = [];

        for(var j = 9; j < 21; j++){
            $scope.hourArray.push({ name: (j < 12 ? "오전 " : "오후 ") + (j == 12 ? 12 : j % 12) + "시", value: j});
        }

        for(var i = 0; i < 6; i++){
            $scope.minArray.push({ name: i + "0분", value: i * 10});
        }

        var now = new Date();

        var now_year = now.getFullYear();
        var now_month = now.getMonth(); // getMonth() returns month(0 - 11)

        $scope.numberOfDays = new Date(now_year, now_month + 1, 0).getDate();
        $scope.firstDay = new Date(now_year, now_month, 1).getDay();
        //$scope.numberOfWeeks = 1 + ($scope.numberOfDays - (7 - $scope.firstDay) - 1) / 7 + 1;
        $scope.calender = [];

        for(var i = -$scope.firstDay, j = 0; i < $scope.numberOfDays; i++, j++){
            if(typeof $scope.calender[Math.floor(j / 7)] === 'undefined') $scope.calender[Math.floor(j / 7)] = [];
            if(i >= 0) $scope.calender[Math.floor(j / 7)][j % 7] = i + 1;
            else $scope.calender[Math.floor(j / 7)][j % 7] = "";
        }
    };

    $scope.studentFetch = function() {
        $http({
            method: "GET",
            url: "/proc/util/barbeque_suggest_student"
        }).then(function mySuccess(response){
            $scope.status = response.statusText;
            $scope.studentArray = response.data;
        }, function myError(response){
            $scope.data = response.data || 'Request failed';
            $scope.status = response.statusText;
        });
    }

    $scope.teacherFetch = function() {
        /*http method 작동 시점 생각해보기*/
        $http({
            method: "GET",
            url: "/proc/util/barbeque_suggest_teacher"
        }).then(function mySuccess(response){
            $scope.status = response.statusText;
            $scope.tArray = response.data;
        }, function myError(response){
            $scope.data = response.data || 'Request failed';
            $scope.status = response.statusText;
        });

        console.log($scope.tArray);
    };

    $scope.changePage = function(page){
        switch(page){
            case 'home':
                $scope.page = '/src/content/template/barbeque_home.html';
                break;
            case 'new-barbeque':
                $scope.page = '/src/content/template/barbeque_new.html';
                $scope.teacherFetch();
                break;
            case 'my-barbeque':
                $scope.page = '/src/content/template/barbeque_list.html';
                $scope.fetchRepList();
                $scope.fetchList();
                break;
        }
    };

    $scope.test = function(date){
        if(date != "") {
            $scope.new_bbq.date = date;
            $scope.dateSelected = true;
            alert(date + "일을 선택하셨습니다.");
        }
        console.log($scope.bbqList);
    };

    $scope.submit = function(){

        var config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        };

        $http({
            method: 'POST',
            url: '/proc/util/barbeque_submit_new',
            data: $scope.new_bbq,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }).then(function mySuccess(response){
            $scope.new_bbq = {};
            $scope.changePage('my-barbeque');
            console.log("submit success");
        }, function myError(response){
            $scope.status = "Request failed";
            console.log("submit failed");
        });

        return false;
    };

    $scope.fetchList = function(){
        $http({
            method: "GET",
            url: "/proc/util/barbeque_my_list"
        }).then(function mySuccess(response){
            $scope.status = response.statusText;
            $scope.bbqList = response.data;
            console.log("success- fetchList");
        }, function myError(response){
            $scope.data = response.data || 'Request failed';
            $scope.status = response.statusText;
            console.log("failed - fetchList");
        });
    };

    $scope.fetchRepList = function(){
        $http({
            method: "GET",
            url: "/proc/util/barbeque_my_list_rep"
        }).then(function mySuccess(response){
            $scope.status = response.statusText;
            $scope.bbqRepList = response.data;
            console.log("success- fetchList");
        }, function myError(response){
            $scope.data = response.data || 'Request failed';
            $scope.status = response.statusText;
            console.log("failed - fetchList");
        });
    };

    $scope._findBBQById = function(id){
        return element.n_id === id;
    };
});

/*app.controller('ModalInstanceCtrl', function($uibModalInstance, items){
    $mctrl = this;
    $mctrl.items = items;

    $mctrl.selected = {
        item: $mctrl.items[0]
    };

    $mctrl.ok = function(){
        $uibModalInstance.close($mctrl.selected.item);
    };

    $mctrl.cancel = function(){
        $uibModalInstance.dismiss('cancel');
    };
});

app.component('modalComponent', {
    templateUrl: 'viewDetails',
    bindings: {
        resolve: '<',
        close: '&',
        dismiss: '&'
    },
    controller: function(){

        this.onInit = function(){
            this.items = this.resolve.items;
            this.selected = {
                item: this.items[0]
            };
        };

        this.ok = function(){
            this.close({ $value: this.seletedItem});
        };

        this.cancel = function(){
            this.dismiss({ $value: 'cancel'});
        };
    }
}); */