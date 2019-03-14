<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A application for a cycling club Várzea.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#FFFFFF"/>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Várzea App">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('icons/icon-192x192.png') }}">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="{{ asset('icons/icon-144x144.png') }}">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="{{ asset('icons/icon-72x72.png') }}">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
</head>
<body>
    <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">@yield('title')</span>
                <div class="mdl-layout-spacer"></div>  
                @guest
                    @if (!\Request::is('login')) 
                        <a class="mdl-button mdl-js-button mdl-button--raised  mdl-js-ripple-effect mdl-button--colored" href="{{ route('login') }}"> 
                            {{ __('Login') }}
                        </a>
                    @endif                
                @endguest 
            </div>
        </header>
        <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
            @auth               
                <header class="demo-drawer-header">
                    <div class="demo-avatar-dropdown">
                        <span>{{ Auth::user()->name }}</span>
                        <div class="mdl-layout-spacer"></div>
                        <button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
                          <i class="material-icons" role="presentation">arrow_drop_down</i>
                          <span class="visuallyhidden">Accounts</span>
                        </button>
                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="accbtn">
                            <li class="mdl-menu__item"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </header>
            @endauth
            <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
                <a class="mdl-navigation__link" href="{{ url('/') }}">
                    <i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i>
                    Home
                </a>
                
                <a class="mdl-navigation__link" href="{{ route('races.index') }}">
                    <i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">flag</i>
                    Races
                </a>
                
                <a class="mdl-navigation__link" href="{{ route('racers.index') }}">
                    <i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">people</i>
                    Racers
                </a>

                <a class="mdl-navigation__link" href="{{ route('worldChampionships') }}">
                    <i class="mdl-color-text--blue-grey-400 fas fa-trophy"></i>
                    Campeonato Mundial
                </a>

                <div class="mdl-layout-spacer"></div>
                <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
            </nav>
        </div>
        <main class="mdl-layout__content mdl-color--grey-100">           
             @yield('content')   
        </main>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('javascript')
</body>
</html>
