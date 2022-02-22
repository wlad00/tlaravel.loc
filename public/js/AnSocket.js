app

    .factory("AnSocket", function ( AnService, $rootScope) {

        let cl = function(str,fn){
            AnService.cl(str, fn, 'AnSocket');
        };


        return {



            login: function(userName){
                var data = {'type' : 'login', 'name': userName};

                this.init();
                conn.send(JSON.stringify(data));
            },
            logout: function(){
                this.init();

                conn.close()

            },
            init: function(){
                var userLogedIn = localStorage.getItem('loggedInUser');

                if(userLogedIn){
                    $rootScope.loggedInUser = userLogedIn;
                    $rootScope.isUserLoggedIn = true;
                    setTimeout(() => {
                        var data = {'type' : 'login', 'name': userLogedIn};
                        conn.send(JSON.stringify(data));
                    },100)
                }else{
                    $rootScope.isUserLoggedIn = false;
                    $rootScope.loggedInUser = null;
                }
            }
        }

    });