
var myApp=

angular.module("myApp", ['textAngular', 'ngSanitize']);


myApp.controller('testPen', function testPen($scope, AnAdm) {

        let cl = function(res,fn){
            AnAdm.cl(res,fn, 'AngAdmin');
        };

        // Register custom tool and toolbar
        $scope.myButtons = [
            ['bold', 'italics', 'underline']
        ];

        $scope.makeFile = function(){

            console.log('makeFile');
            let obj = {};

            AnAdm.postObj(obj,'/admin/make_file').then(function(res){

                cl(res, 'makeFile/res');
            });
        };


        $scope.t0 = '<p><b>When </b>you insert the cursor at the end of set</p>';


        $scope.t2 = '<p>asdf <b>AAAA</b></p>'
        $scope.t1 = '<p>adfasd <i>asdff</i></p>'
    })
;