<!doctype html>
<html lang="{{ app()->getLocale() }}"  ng-app="appHome" >
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>


        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
        <script src="js/AngService.js"></script>

        <script src="js/AngHome.js"></script>

        <script src="js/AnSocket.js"></script>
    </head>
    <body>
        <div class="flex-center position-ref full-height"  ng-controller="AngHome">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    tLaravel.loc
                </div>
                {{--<button ng-click="playAudio()">playAudio</button>--}}

                {{------------------------------------------------}}

                <div ng-if="!isUserLoggedIn" class="row">
                    <div class="col-md-8">
                        <input type="text" ng-model="userName" class="form-control" placeholder="Your name">
                    </div>
                    <div class="col-md-4 text-right">
                        <button ng-click="login(userName)" class="btn btn-primary btn-block">Login</button>
                    </div>
                </div>

                {{---------------------------------------------------}}

                <div ng-if="isUserLoggedIn" class="row">

                    <div class="col-md-4 offset-md-8">
                        <button ng-click="logout()" class="btn btn-primary btn-block">Logout</button>
                    </div>
                    <h2>I'm @{{ loggedInUser }}</h2>
                </div>



                <div class="links" style="margin-top: 60px;">

                </div>
            </div>
        </div>


    </body>
</html>
