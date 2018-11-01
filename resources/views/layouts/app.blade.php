<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

    <!-- textAngular -->
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,300">
    <link rel="stylesheet" href="http://textangular.com/dist/textAngular.css" type="text/css">

    <script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.3.11/angular.min.js'></script>
    <script src='http://textangular.com/dist/textAngular-rangy.min.js'></script>
    <script src='http://textangular.com/dist/textAngular-sanitize.min.js'></script>
    <script src='http://textangular.com/dist/textAngular.min.js'></script>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>

<div ng-app="textAngularTest" ng-controller="wysiwygeditor" class="container app">
    <h1>Editor <span>@{{version}}</span></h1>

<button ng-click="disabled = !disabled" unselectable>   Disable text-angular Toggle
</button>

    <div text-angular="text-angular" name="htmlcontent" ng-model="htmlcontent" ta-disabled='disabled'></div>

    <h1>Raw HTML in a text area</h1>
    <textarea ng-model="htmlcontent" style="width: 100%"></textarea>
    <h1>Bound with ng-bind-html</h1>
    <div ng-bind-html="htmlcontent"></div>
    <h1>Bound with ta-bind, our internal html-binding directive</h1>
    <div ta-bind="text" ng-model="htmlcontent" ta-readonly='disabled'></div>
    <button type="button" ng-click="htmlcontent = orightml">Reset</button>
    <p>Note: although we support classes and styles, angularjs' ng-bind-html directive will strip out all style
        attributes.</p>
    <h1>Option to masquerade as a fancy text-area - complete with form submission and optional ngModel</h1>

    <text-angular name="htmlcontent" ng-model="htmlcontenttwo">
        <p>Any <b>HTML</b> we put in-between the text-angular tags gets automatically put into the editor if there
            <strong style="font-size: 12pt;"><u><em>is not</em></u></strong> a value assigned to the ngModel.</p>
        <p>If there is a value assigned to the ngModel, it replaces any html here. To see this, uncomment the line at
            the bottom of demo.html</p>
    </text-angular>
    <h1>Bound with ta-bind, our internal html-binding directive</h1>
    <div ta-bind="text" ng-model="htmlcontenttwo" ta-readonly='disabled'></div>
</div>

<script>
    angular.module("textAngularTest", ['textAngular'])
        .controller('wysiwygeditor', ['$scope', 'textAngularManager', function wysiwygeditor($scope, textAngularManager) {
            $scope.version = textAngularManager.getVersion();
            $scope.versionNumber = $scope.version.substring(1);
            $scope.orightml = '<h2>Try me!</h2><p>textAngular is a super cool WYSIWYG Text Editor directive for AngularJS</p><p><img class="ta-insert-video" ta-insert-video="http://www.youtube.com/embed/2maA1-mvicY" src="" allowfullscreen="true" width="300" frameborder="0" height="250"/></p><p><b>Features:</b></p><ol><li>Automatic Seamless Two-Way-Binding</li><li>Super Easy <b>Theming</b> Options</li><li style="color: green;">Simple Editor Instance Creation</li><li>Safely Parses Html for Custom Toolbar Icons</li><li class="text-danger">Doesn&apos;t Use an iFrame</li><li>Works with Firefox, Chrome, and IE9+</li></ol><p><b>Code at GitHub:</b> <a href="https://github.com/fraywing/textAngular">Here</a> </p><h4>Supports non-latin Characters</h4><p>昮朐 魡 燚璒瘭 譾躒鑅, 皾籈譧 紵脭脧 逯郹酟 煃 瑐瑍, 踆跾踄 趡趛踠 顣飁 廞 熥獘 豥 蔰蝯蝺 廦廥彋 蕍蕧螛 溹溦 幨懅憴 妎岓岕 緁, 滍 蘹蠮 蟷蠉蟼 鱐鱍鱕, 阰刲 鞮鞢騉 烳牼翐 魡 骱 銇韎餀 媓幁惁 嵉愊惵 蛶觢, 犝獫 嶵嶯幯 縓罃蔾 魵 踄 罃蔾 獿譿躐 峷敊浭, 媓幁 黐曮禷 椵楘溍 輗 漀 摲摓 墐墆墏 捃挸栚 蛣袹跜, 岓岕 溿 斶檎檦 匢奾灱 逜郰傃</p>';
            $scope.htmlcontent = $scope.orightml;
            $scope.disabled = false;
        }]);
</script>
</body>
</html>
