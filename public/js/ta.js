
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


        $scope.t2 = '<p>asdf <b>AAAA</b></p>';
        $scope.t1 = '<p>adfasd <i>asdff</i></p>';

    $scope.areaStacked = {
        "type": "serial",
        "categoryField": "category",
        "startDuration": 1,
        "categoryAxis": {
            "gridPosition": "start"
        },
        "trendLines": [],
        "graphs": [
            {
                "balloonText": "[[title]] of [[category]]:[[value]]",
                "fillAlphas": 0.7,
                "id": "AmGraph-1",
                "lineAlpha": 0,
                "title": "graph 1",
                "valueField": "column-1"
            },
            {
                "balloonText": "[[title]] of [[category]]:[[value]]",
                "fillAlphas": 0.7,
                "id": "AmGraph-2",
                "lineAlpha": 0,
                "title": "graph 2",
                "valueField": "column-2"
            }
        ],
        "guides": [],
        "valueAxes": [
            {
                "id": "ValueAxis-1",
                "stackType": "regular",
                "title": "Axis title"
            }
        ],
        "allLabels": [],
        "balloon": {},
        "legend": {},
        "titles": [
            {
                "id": "Title-1",
                "size": 15,
                "text": "Chart Title"
            }
        ],
        "data": [
            {
                "category": "category 1",
                "column-1": 8,
                "column-2": 5
            },
            {
                "category": "category 2",
                "column-1": 6,
                "column-2": 7
            },
            {
                "category": "category 3",
                "column-1": 2,
                "column-2": 3
            },
            {
                "category": "category 4",
                "column-1": 1,
                "column-2": 3
            },
            {
                "category": "category 5",
                "column-1": 2,
                "column-2": 1
            },
            {
                "category": "category 6",
                "column-1": 3,
                "column-2": 2
            },
            {
                "category": "category 7",
                "column-1": 6,
                "column-2": 8
            }
        ]
    };



    })
;