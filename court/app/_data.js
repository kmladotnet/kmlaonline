app.factory("Data", ['$http', 'toaster',
    function($http, toaster) {
        var serviceBase = 'api/presenTool/';

        var obj = {};
        obj.toast = function (data) {
            toaster.pop(data.status, "", data.message, 10000, 'trustedHtml');
        };
        obj.get = function(q) {
            return $http.get(serviceBase + q).then(function (results) {
                return results.data;
            });
        };
        obj.post = function (q, object) {
            return $http.post(serviceBase + q).then(function (results) {
                return results.data;
            });
        };
        obj.put = function(q, object) {
            return $http.put(serviceBase + q).then(function (results) {
                return results.data;
            });
        };
        obj.delete = function(q) {
            return $http.put(serviceBase + q).then(function (results) {

            });
        };

        return obj;
}]);