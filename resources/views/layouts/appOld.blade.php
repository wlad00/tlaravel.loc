<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Style.css') }}" rel="stylesheet">

    <!-- Semantic -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">


    <link data-require="font-awesome@4.3.0" data-semver="4.3.0" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />

    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />--}}
    {{--<link rel="stylesheet" href="https://rawgit.com/fraywing/textAngular/master/dist/textAngular.css" />--}}
    {{--<link href="https://github.com/FortAwesome/Font-Awesome/blob/master/fonts/FontAwesome.otf" />--}}

</head>
<body>
<div class="ui container" ng-app="myApp" ng-controller="testPen">


    <button ng-click="makeFile()">makeFile</button>

    <div class="row">
        <div >


            <text-angular  ng-model="t0" ta-toolbar="myButtons">
            </text-angular>


        </div>
    </div>
    <br>
    <div class="ui big header">Model = @{{ t0 }}</div>


    <br>
    <br>
    <br>

    <text-angular  ng-model="t1" ta-toolbar="myButtons">
    </text-angular>
    <br>

    Model = @{{ t1 }}
    <br>
    <br>
    <br>


    <text-angular  ng-model="t2" ta-toolbar="myButtons">
    </text-angular>

    <br>

    Model = @{{ t2 }}
    <br>

</div>




</body>


</body

<!-- Angular -->

<script src="https://code.angularjs.org/1.3.17/angular.js" ></script>

<script src="js/ta.js"></script>
<script src="js/AnAdm.js"></script>

<!-- textAngular -->

<script src="https://rawgit.com/fraywing/textAngular/master/dist/textAngular-rangy.min.js"></script>
<script src="https://rawgit.com/fraywing/textAngular/master/dist/textAngular-sanitize.min.js"></script>
<script src="https://rawgit.com/fraywing/textAngular/master/dist/textAngular.min.js"></script>


<!-- Semantic -->

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js">

</script>--}}

</html>
