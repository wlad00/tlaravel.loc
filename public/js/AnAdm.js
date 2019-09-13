myApp

    .factory("AnAdm", function ($rootScope, $http) {

        return {

            cl: function(str,fn, Ctrl){

                if (!Ctrl) Ctrl='AnDic';

                if (typeof str === 'string' || str instanceof String)

                    console.log(Ctrl+ '.' + fn + ': ' + str);
                else {
                    console.log(Ctrl+ '.' + fn + ' =>');
                    console.log(str);
                }
            },


            /*   START */


            postObj: function(obj,url){

                return $http(this.getHttp
                    (url, "POST", obj)
                );
            },

            postLocale: function(newLocale){
                let obj={'new-locale':newLocale},method, url;

                method = "POST";
                url = '/t_post_locale';

                let http = $http(this.getHttp(url, method, obj));

                return http;
            },



            getHttp: function(url,method,obj){

                return {
                    method: method,
                    url: url,
                    data: $.param(obj),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                };
            },




        }
    });

