'use strict';

var app = angular.module("kmla_court", ['ui.select', 'ngSanitize', 'ui.bootstrap']);

app.filter('propsFilter', function() {
  return function(items, props) {
    var out = [];

    if (angular.isArray(items)) {
      var keys = Object.keys(props);

      items.forEach(function(item) {
        var itemMatches = false;

        for (var i = 0; i < keys.length; i++) {
          var prop = keys[i];
          var text = props[prop].toLowerCase();
          if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
            itemMatches = true;
            break;
          }
        }

        if (itemMatches) {
          out.push(item);
        }
      });
    } else {
      // Let the output be the input untouched
      out = items;
    }

    return out;
  };
});

app.controller("courtCtrl", function($scope, $http){
        $scope.disabled = undefined;
        $scope.point_disabled = true;

        $scope.enable = function(){
            $scope.disabled = true;
        }

        $scope.disable = function(){
            $scope.disabled = true;
        }

        $scope.init = function(){
            $scope.accusedFetch();
            $scope.accuserFetch();
            $scope.articleKindFetch();
            $scope.today();
        }

        $scope.accusedSelect = {};
        $scope.accuserSelect = {};
        $scope.articleKindSelect = {};
        $scope.accusedArray = [];
        $scope.accuserArray = [];
        $scope.articleKindArray = [];

        $scope.accusedFetch = function() {
            $scope.code = null;
            $scope.response = null;
            $scope.dateFormat = 'MM-dd';
            $http({
                method: "GET",
                url: "process/getMemberList.php"
            }).then(function mySuccess(response){
                $scope.status2 = response.statusText;
                $scope.accusedArray = response.data;
            }, function myError(response){
                $scope.data2 = response.data || 'Request failed';
                $scope.status2 = response.statusText;
            });
        }

        $scope.accuserFetch = function() {
            $scope.code = null;
            $scope.response = null;

            $http({
                method: "GET",
                url: "process/getAccuserList.php"
            }).then(function mySuccess(response){
                $scope.status2 = response.statusText;
                $scope.accuserArray = response.data;
            }, function myError(response){
                $scope.data2 = response.data || 'Request failed';
                $scope.status2 = response.statusText;
            });
        };

        $scope.articleKindFetch = function() {
            $scope.code = null;
            $scope.response = null;

            $http({
                method: "GET",
                url: "process/getArticleKindList.php"
            }).then(function mySuccess(response){
                $scope.status2 = response.statusText;
                $scope.articleKindArray = response.data;
            }, function myError(response){
                $scope.data2 = response.data || 'Request failed';
                $scope.status2 = response.statusText;
            });
        };

        $scope.today = function() {
            $scope.accused_date2 = new Date();
        };

        $scope.popup = false;
        $scope.openCalender = function(){
            $scope.popup = true;
        }
        $scope.format="yyyy-MM-dd";

        $scope.submitNewArticle = function(_grade, _name, _accuse_date, _accuser, _article, _point){
            var temp_data = {grade: _grade, name: _name, accuse_date: _accuse_date, accuser: _accuser, article: _article, point: _point};
            var config = {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            };

            $http({
                method: 'POST',
                url: 'process/accuse.php',
                data: temp_data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }).then(function mySuccess(response){
                $scope.status = response.data;
                $scope.accused_grade = "";
                $scope.accused_name = "";
                $scope.accuser = "";
                $scope.accused_article = "";
                $scope.accused_point = "";
            }, function myError(response){
                $scope.status = "Request failed";
            });
            return false;
        };

        $scope.test = function(){
            console.log($scope.accusedSelect);
            console.log($scope.accuserSelect);
            console.log($scope.articleKindSelect);
            console.log($scope.accused_date2);
        }

        $scope.submitListofArticle = function(){
            var temp_result = [];
            var temp_data;
            var date = $scope.dateTest();

            $scope.accusedSelect.selectedPeople.forEach(function(item){
                temp_data = {grade: item['grade'], name: item['name'], accuse_date: date, accuser: $scope.accuserSelect.selected['name'], article: $scope.articleKindSelect.selected['ak_eng']};
                temp_result.push(temp_data);
            });

            var config = {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            };

            $http({
                method: 'POST',
                url: 'process/accuse.php',
                data: temp_result,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }).then(function mySuccess(response){
                $scope.status = response.data;
                $scope.accusedSelect.selectedPeople = [];
                $scope.accuserSelect.selected = [];
                $scope.articleKindSelect.selected = [];
            }, function myError(response){
                $scope.status = "Request failed";
            });
            return false;
        };

        $scope.dateTest = function(){
            var date = new Date($scope.accused_date2);
            var result = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            return result;
        }

        $scope.loadTags = function($query){
            return $http.get('process/suggest.php').then(function(response){
                var result = response.data;
                return result.filter(function(accused){
                    return accused.text.indexOf($query) != -1;
                })
            });
        };

        $scope.fetch = function() {
            $scope.code = null;
            $scope.response = null;


            $http({
                method: "GET",
                url: "/test/user.json"
            }).then(function mySuccess(response){
                $scope.status = response.statusText;
                $scope.data = response.data;
            }, function myError(response){
                $scope.data = response.data || 'Request failed';
                $scope.status = response.statusText;
            });
        };

        var articleList = [];
        $scope.articleList = articleList;
        $scope.addNewArticle = function(_grade, _name, _accuse_date, _accuser, _article, _point){
            $scope.articleList.push({grade: _grade, name: _name, accuse_date: _accuse_date, accuser: _accuser, article: _article, point: _point});
            $scope.accused_grade = "";
            $scope.accused_name = "";
            $scope.accuser = "";
            $scope.accused_article = "";
            $scope.accused_point = "";

            console.log($scope.accused_date);

        };
});

app.factory('Excel', function($window){
        var uri='data:application/vnd.ms-excel;base64,',
            template='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
            base64=function(s){return $window.btoa(unescape(encodeURIComponent(s)));},
            format=function(s,c){return s.replace(/{(\w+)}/g,function(m,p){return c[p];})};
        return {
            tableToExcel:function(tableId,worksheetName){
                var table=$(tableId),
                    ctx={worksheet:worksheetName,table:table.html()},
                    href=uri+base64(format(template,ctx));
                return href;
        }
    };
}).controller("listCtrl", function($scope, $http, Excel, $timeout){
    var articleList = [];
    var articleList_RT = [];
    var articleList_FD = [];
    var articleList_OD = [];
    var articleList_CL = [];

    $scope.articleList = articleList;
    $scope.articleList_RT = articleList_RT;
    $scope.articleList_FD = articleList_FD;
    $scope.articleList_OD = articleList_OD;
    $scope.articleList_CL = articleList_CL;

    $scope.setup = function(){
        $scope.fetch();
    };

    $scope.fetch = function(){
        $scope.code = null;
        $scope.response = null;

        $http({
            method: "GET",
            url: "/judicial/process/articleSort.php"
        }).then(function mySuccess(response){
            $scope.status = response.statusText;
            $scope.articleList = response.data;
            /* 고치기 전!
            $scope.identifyCouncilMember();
            $scope.calculateRows();
            */
            console.log($scope.articleList);
            $scope.divideData();
            console.log($scope.articleList_OD);
            $scope.calculateRows2();

        }, function myError(response){
            $scope.data = response.data || 'Request failed';
            $scope.status = response.statusText;
        });
    };

    $scope.divideData = function(){
        for (var accused in $scope.articleList){
            if($scope.articleList.hasOwnProperty(accused)){
                console.log(accused);
            }
        }
        for(var i = 0; i < $scope.articleList.length; i ++){
            console.log($scope.articleList[i].status);
            if(isRT(parseInt($scope.articleList[i].status))){
                for(var j = 0; j < $scope.articleList[i].article_array.length; j++){
                    $scope.articleList_RT.push($scope.articleList[i].article_array[j]);
                }
            } else if (isFD(parseInt($scope.articleList[i]))){
                for(var j = 0; j < $scope.articleList[i].article_array.length; j++){
                    $scope.articleList_FD.push($scope.articleList[i].article_array[j]);
                }
            } else if (isOD(parseInt($scope.articleList[i]))){
                for(var j = 0; j < $scope.articleList[i].article_array.length; j++){
                    $scope.articleList_OD.push($scope.articleList[i].article_array[j]);
                }
            } else {
                for(var j = 0; j < $scope.articleList[i].article_array.length; j++){
                    $scope.articleList_CL.push($scope.articleList[i].article_array[j]);
                }
            }
        }
    };

    $scope.calculateRows2 = function(){
        var row_span, sum;
        var num = 1;
        if($scope.articleList_RT.length > 0){
            $scope.articleList_RT[0].matchPreviousRow = false;
            for(var i = 0; i < $scope.articleList_RT.length; i += row_span){
                var name = $scope.articleList_RT[i].name;
                row_span = 1;
                sum = parseInt($scope.articleList_RT[i].point);
                for(var j = i + 1; j < $scope.articleList_RT.length; j++){
                    if($scope.articleList_RT[j].name === name){
                        $scope.articleList_RT[j].matchPreviousRow = true;
                        row_span++;
                        sum += parseInt($scope.articleList_RT[j].point);
                    } else {
                        $scope.articleList_RT[j].matchPreviousRow = false;
                        break;
                    }
                }
                $scope.articleList_RT[i].row_span = row_span;
                $scope.articleList_RT[i].sum = sum;
                $scope.articleList_RT[i].num = num++;
            }
        }

        num = 1;
        if($scope.articleList_FD.length > 0){
            $scope.articleList_FD[0].matchPreviousRow = false;
            for(var i = 0; i < $scope.articleList_FD.length; i += row_span){
                var name = $scope.articleList_FD[i].name;
                row_span = 1;
                sum = parseInt($scope.articleList_FD[i].point);
                for(var j = i + 1; j < $scope.articleList_FD.length; j++){
                    if($scope.articleList_FD[j].name === name){
                        $scope.articleList_FD[j].matchPreviousRow = true;
                        row_span++;
                        sum += parseInt($scope.articleList_FD[j].point);
                    } else {
                        $scope.articleList_FD[j].matchPreviousRow = false;
                        break;
                    }
                }
                $scope.articleList_FD[i].row_span = row_span;
                $scope.articleList_FD[i].sum = sum;
                $scope.articleList_FD[i].num = num++;
            }
        }

        num = 1;
        if($scope.articleList_OD.length > 0){
            $scope.articleList_OD[0].matchPreviousRow = false;
            for(var i = 0; i < $scope.articleList_OD.length; i += row_span){
                var name = $scope.articleList_OD[i].name;
                row_span = 1;
                sum = parseInt($scope.articleList_OD[i].point);
                for(var j = i + 1; j < $scope.articleList_OD.length; j++){
                    if($scope.articleList_OD[j].name === name){
                        $scope.articleList_OD[j].matchPreviousRow = true;
                        row_span++;
                        sum += parseInt($scope.articleList_OD[j].point);
                    } else {
                        $scope.articleList_OD[j].matchPreviousRow = false;
                        break;
                    }
                }
                $scope.articleList_OD[i].row_span = row_span;
                $scope.articleList_OD[i].sum = sum;
                $scope.articleList_OD[i].num = num++;
            }
        }

        num = 1;
        if($scope.articleList_CL.length > 0){
            $scope.articleList_CL[0].matchPreviousRow = false;
            for(var i = 0; i < $scope.articleList_CL.length; i += row_span){
                var name = $scope.articleList_CL[i].name;
                row_span = 1;
                sum = parseInt($scope.articleList_CL[i].point);
                for(var j = i + 1; j < $scope.articleList_CL.length; j++){
                    if($scope.articleList_CL[j].name === name){
                        $scope.articleList_CL[j].matchPreviousRow = true;
                        row_span++;
                        sum += parseInt($scope.articleList_CL[j].point);
                    } else {
                        $scope.articleList_CL[j].matchPreviousRow = false;
                        break;
                    }
                }
                $scope.articleList_CL[i].row_span = row_span;
                $scope.articleList_CL[i].sum = sum;
                $scope.articleList_CL[i].num = num++;
            }
        }
    };

    $scope.calculateRows = function(){
        var row_span, sum;
        var num = 1;
        if($scope.articleList.length > 0){
            $scope.articleList[0].matchPreviousRow = false;
            for(var i = 0; i < $scope.articleList.length; i += row_span){
                var name = $scope.articleList[i].name;
                row_span = 1;
                sum = parseInt($scope.articleList[i].point);
                for(var j = i + 1; j < $scope.articleList.length; j++){
                    if($scope.articleList[j].name === name){
                        $scope.articleList[j].matchPreviousRow = true;
                        row_span++;
                        sum += parseInt($scope.articleList[j].point);
                    } else {
                        $scope.articleList[j].matchPreviousRow = false;
                        break;
                    }
                }
                $scope.articleList[i].row_span = row_span;
                $scope.articleList[i].sum = sum;
                $scope.articleList[i].num = num++;
            }
        }
    };

    $scope.tempBuffList = ["11신주혁", "11김정현", "11정태웅", "10김민주", "11남진우",
                            "11이지인", "10김태준", "10심소현", "11김성진", "11박대해", "11김채영"];

    $scope.identifyCouncilMember = function(){
        if($scope.articleList.length > 0){
            $scope.articleList[0].matchPreviousRow = false;
            for(var i = 0; i < $scope.articleList.length; i++){
                if($scope.tempBuffList.includes($scope.articleList[i].grade + $scope.articleList[i].name)) $scope.articleList[i].point = parseInt($scope.articleList[i].point) + 1;
            }
        }
    };

    $scope.isRT = function($status){
        if($status < 20000) return true;
        else false;
    };

    $scope.isFD = function($status){
        if($status >= 20000 && $status < 30000) return true;
        else false;
    };

    $scope.isOD = function($status){
        if($status >= 30000 && $status < 50000) return true;
        else false;
    };

    $scope.isCL = function($status){
        if($status >= 50000) return true;
        else false;
    };

    $scope.exportToExcel = function(tableId){
        //console.log("function start!!!");
        var exportHref = Excel.tableToExcel(tableId, 'Court List');
        //console.log(exportHref);

        $timeout(function(){location.href = exportHref;}, 100);

    }
});