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


    <link data-require="font-awesome@4.3.0" data-semver="4.3.0" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />
    {{--<link rel="stylesheet" href="https://rawgit.com/fraywing/textAngular/master/dist/textAngular.css" />--}}
    <link href="https://github.com/FortAwesome/Font-Awesome/blob/master/fonts/FontAwesome.otf" />

</head>
<body >
<div class="container-fluid" ng-app="textAngularTest" ng-controller="testPen">
    <div class="row">
        <div >
            <text-angular name="htmlcontent" ng-model="htmlcontent"
                          ta-toolbar="editorToolbarButtonConfig"/>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>

    Model = @{{ htmlcontent }}

</div>








</body>

<!-- Angular -->

<script src="https://code.angularjs.org/1.3.17/angular.js" ></script>
{{--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.5/angular.min.js"></script>--}}

<script src="js/ta.js"></script>

<!-- textAngular -->

<script src="https://rawgit.com/fraywing/textAngular/master/dist/textAngular-rangy.min.js"></script>
<script src="https://rawgit.com/fraywing/textAngular/master/dist/textAngular-sanitize.min.js"></script>
<script src="https://rawgit.com/fraywing/textAngular/master/dist/textAngular.min.js"></script>



<script src="js/textAngularSetup.js"></script>
</html>
