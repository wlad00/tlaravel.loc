app

    .factory("AnSocket", function ( AnService, $rootScope) {

        let cl = function(str,fn){
            AnService.cl(str, fn, 'AnSocket');
        };


        return {



            login: function(userName){
                var data = {'type' : 'login', 'name': userName, 'email': userName };

                this.initRoot();
                conn.send(JSON.stringify(data));
            },
            logout: function(){
                this.initRoot();

                conn.close()

            },
            initRoot: function(){
                var userLogedIn = localStorage.getItem('loggedInUser');

                cl('/initRoot------------');

                if(userLogedIn){
                    $rootScope.loggedInUser = userLogedIn;
                    $rootScope.isUserLoggedIn = true;
                    setTimeout(() => {
                        var data = {'type' : 'login', 'name': userLogedIn, 'email': userName};
                        conn.send(JSON.stringify(data));
                    },100)
                }else{
                    $rootScope.isUserLoggedIn = false;
                    $rootScope.loggedInUser = null;
                }
            }
        }

    });