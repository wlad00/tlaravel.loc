
var AngService = angular.module( 'AnService', []);

AngService

    .factory("AnService", function ($rootScope) {

        let audioObj = new Audio('audio/finish2.mp3');

        return {

            cl: function(str,fn, Ctrl){
                if (typeof str === 'string' || str instanceof String ||
                    str instanceof Number || typeof str === 'number' || typeof str === 'boolean')

                    console.log(Ctrl+ '.' + fn + ': ' + str);
                else {
                    console.log(Ctrl+ '.' + fn + ' =>');
                    console.log(str);
                }
            },
            playAudio: function(){

                audioObj.play();
            }


        }
    });

