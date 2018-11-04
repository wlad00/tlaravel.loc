angular
    .module("textAngularTest", ['textAngular', 'ngSanitize'])
    .controller('testPen', function testPen($scope, $timeout, taRegisterTool) {

        console.clear();


        // Register custom tool and toolbar
        $scope.editorToolbarButtonConfig = [

            ['bold', 'italics', 'underline']

        ];

        $scope.htmlcontent = '<p><b>When </b>you insert the cursor at the end of set</p>';

    })
;