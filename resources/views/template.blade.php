<!DOCTYPE html>

<html lang="ru">
<head>
    <meta charset="utf-8">
    @yield('meta')
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('./css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    @yield('script')
</head>



<body>
@yield('backgroundImage')

<nav>
    <ul class="topmenu">
        <li><a href="" class="active">Главная<span class="fa fa-angle-down"></span></a>
        </li>
        <li><a href="">Компания</a>
            <ul class="submenu">
                <li><a href="">меню второго уровня</a>
                </li>
                <li><a href="">меню второго уровня<span class="fa fa-angle-down"></span></a>
                    <ul class="submenu">
                        <li><a href="">меню третьего уровня</a></li>
                        <li><a href="">меню третьего уровня</a></li>
                        <li><a href="">меню третьего уровня</a></li>
                    </ul>
                </li>
                <li><a href="">меню второго уровня</a></li>
            </ul></li>
        <li><a href="">Блог</a></li>
        <li><a href="">Контакты</a></li>
    </ul>
</nav>

@yield('content')

<footer>
    Телефон: 555 3 555<br>Эл. почта: example@gmail.com
</footer>
</body>
</html>
