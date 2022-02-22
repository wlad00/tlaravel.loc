
var conn = new WebSocket('ws://localhost:8081');

conn.onopen = function(e){

    console.log("Connection established!");

};


var app = angular.module('appHome', ['AnService']);

app.controller('AngHome', function($scope, AnService, AnSocket, $rootScope) {

        $scope.login = function(userName){

            localStorage.setItem('loggedInUser',userName);

            AnSocket.login(userName);
        };

        $scope.logout = function(){
            localStorage.removeItem('loggedInUser');

            AnSocket.logout();

        };



        conn.onmessage = function(e) {
            var data = JSON.parse(e.data);

            console.log(data);

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