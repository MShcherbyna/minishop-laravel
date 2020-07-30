<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script
        src="https://code.jquery.com/jquery-3.5.0.min.js"
        integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
        crossorigin="anonymous"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
    <header>
        <div class="top-nav container">
            <div class="logo"><a href="/">Laravel eCommerce</a></div>
            <ul class='right-menu'>
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li class='cart-delimeter'><a href="{{ route('register') }}">Register</a></li>
                @else
                    <li><a href="{{ route('dashboard') }}">My Account</a></li>
                    <li class='cart-delimeter'>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                @endguest
                @if (\Cart::getContent()->count() > 0)
                    <li><a href="{{ route('cart') }}">Cart <span class='cart-count'>({{ Cart::getTotalQuantity() }})</span></a></li>
                @else
                    <li><a href="{{ route('cart') }}">Cart</a></li>
                @endif
            </ul>
        </div> <!-- end top-nav -->
        @yield('header-content')
    </header>

    <div class="featured-section" id='app'>
    @yield('content')
    </div>

    <footer>
        <div class="footer-content container">
            <div class="made-with">Made with <i class="fa fa-heart"></i> by MTakumi</div>
        </div> <!-- end footer-content -->
    </footer>

    @stack('scripts')
</body>
</html>
