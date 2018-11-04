angular
    .module("myApp", ['textAngular', 'ngSanitize'])
    .controller('testPen', function testPen($scope) {

        // Register custom tool and toolbar
        $scope.myButtons = [

            ['bold', 'italics', 'underline']

        ];

        $scope.t0 = '<p><b>When </b>you insert the cursor at the end of set</p>';


        $scope.t2 = '<p>asdf <b>AAAA</b></p>'
        $scope.t1 = '<p>adfasd <i>asdff</i></p>'
    })
;