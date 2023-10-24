<!DOCTYPE html>

<html lang="ru">
<head>
    <meta charset="utf-8">
    @yield('meta')
    <title>@yield('title')</title>
    <link href="{{asset('./plugins/css/froala_style.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('./css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    @yield('script')
</head>



<body>
@yield('backgroundImage')

<nav>
    <ul class="topmenu">
        <li><a href="{{route('home')}}" class="active">Главная<span class="fa fa-angle-down"></span></a>
        </li>
        <li><a href="{{route('about')}}">О нас</a>
            <ul class="submenu">
                <li><a href="{{route('about')}}#restaurants">Сеть ресторанов</a></li>
                <li><a href="{{route('about')}}#accustom">Обслуживание<span class="fa fa-angle-down"></span></a>
                <li><a href="{{route('about')}}#contacts">Контакты</a></li>
                <li><a href="{{route('about')}}#awards">Награды</a></li>
                <li><a href="{{route('about')}}#staff">Персонал</a></li>
                <li><a href="{{route('about')}}#comments">Отзывы</a></li>
            </ul></li>
        <li><a href="{{route('menu')}}">Меню</a></li>
        <li><a href="{{route('order')}}">Заказ столика</a></li>
        <li><a href="{{route('banquet')}}">Заказ мероприятия</a></li>
        <li><a href="{{route('maps')}}">Схема проезда</a></li>
    </ul>
    @if(!Auth::check())
    <a href="{{route('login')}}" class="authorizationBlock">Войти</a>
    @else
        <a href="{{route('logout')}}" class="authorizationBlock">{{auth()->user()->name}} <img src="{{asset('./imgs/logout_111063.svg')}}" height="20" style="vertical-align: text-bottom"></a>
    @endif
</nav>
<div class="content">
@yield('content')
</div>
<footer>
    Телефон: 8 800 555 3 555<br>Эл. почта: example@gmail.com
</footer>
</body>
</html>
