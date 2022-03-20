



var app = angular.module('appHome', ['AnService']);

app.controller('AngHome', function($scope, AnService, AnSocket, $rootScope, $interval) {

    let cl = function (res, fn) {
        AnService.cl(res, fn, 'AngHome');
    };

        $rootScope.loggedInUser = '11';
        $rootScope.newMessage = 'newwwwwww messss';
        $scope.onlineUsers = {'key1':'val1','key2':'val2'};
        $rootScope.toUser = 'Vassssssja';
        $rootScope.massages = [{'to':'Vasja1','message':'message1'},{'to':'Vasja2',message:'message2'}];
        $rootScope.messageModel = '';
        // $rootScope.newMessage =

        /*let user_elem = document.getElementById('user');
        $scope.user = JSON.parse(user_elem.getAttribute('data-content')) ;

        cl($scope.user,'/scope.user------------');*/


        $rootScope.conn = new WebSocket('ws://localhost:8081');

        $rootScope.conn.onopen = function(e){

            console.log("Connection established!");

        };

        $interval(function(){
            cl('/send interval------5000----');
            $rootScope.conn.send('{"type":"interval"}');
        },5000);


        $scope.sendMsg = function (message){


            $rootScope.messageModel = '';
        };

        $scope.login = function(userName){

            localStorage.setItem('loggedInUser',userName);

            AnSocket.login(userName);
        };

        $scope.logout = function(){
            localStorage.removeItem('loggedInUser');

            AnSocket.logout();

        };


        /*$interval(function () {


            conn.send('send-----');


        },5000);*/




    $rootScope.conn.onmessage = function(e) {

            let objMsg = JSON.parse(e.data);


            // cl(objMsg.rand2 + ' ' +objMsg.arrPersons.length,'/onmessage/arrPersons.length-----------------');

            // cl(objMsg.arrIndexes,'/arrIndexes---------');
            cl(objMsg.arrPersons,'/arrPersons---------');


            /*if(data.type == "onlineUsers"){
                $scope.onlineUsers = data.onlineUsers;
                $scope.$apply();
            }else if (data.type == "message"){
                $scope.messages.push(data.data);
                $scope.newMessage = data.data.from;
                $scope.$apply();
                //$scope.playAudio();
                setTimeout(() => {
                    $scope.newMessage = null;
                    $scope.$apply();
                },2000)
            }*/
        };


        $scope.selectUser = function(toUser){
            $scope.toUser = toUser;
        };
        /*$scope.playAudio = function() {
            var audio = new Audio('audio/finish2.mp3');
            audio.play();
        };*/



    });