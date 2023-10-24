<!DOCTYPE html>

<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('./css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    @yield('stylesheets')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    @yield('script')

    <script>

        $(document).ready(function () {
            $('#newCategoryName').on('keyup', function () {
                let categoryError = $('#invalid_category_name');
                if(this.value.length){
                    categoryError.addClass('hidden');
                    $('#newCategorySubmitBtn').prop('disabled', false);
                } else {
                    categoryError.removeClass('hidden');
                    $('#newCategorySubmitBtn').prop('disabled', true);
                }
            })

            fetch("{{route('admin.menu.categories')}}", {
                method: 'GET'
            }).then(response => response.text()).then(data => {
                const categories = JSON.parse(data).categories;
                categories.forEach(category =>
                    insertCategory(category.category_name, category.id)
                )
            })

            function insertCategory(categoryName, categoryId){
                const catalogCategories = $('#editMenuCategories')
                catalogCategories.prepend('<li><a href="/admin/menu/editor/'+categoryId+'">'+categoryName+'</a></li>')
            }

            $('#newCategoryForm').on('submit', function (ev) {
                ev.preventDefault();
                fetch("{{route('admin.menu.addNewCategory')}}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this)
                }).then(response => response.text()).then(data => {
                    let parsedData = JSON.parse(data);
                    if(parsedData['result'] === 'added'){
                        insertCategory(parsedData['category_name'], parsedData['category_id']);
                        $('#closeNewMenuCategoryModal').trigger('click');
                    } else {
                        $('#errorAddCategory').removeClass('hidden');
                    }
                })
            })

        })
    </script>

</head>



<body>
@yield('backgroundImage')

<nav>
    <ul class="topmenu">
        <li><a href="{{route('admin.home')}}" class="active">Главная<span class="fa fa-angle-down"></span></a>
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
        <li><a href="">Админ-панель</a>
            <ul class="submenu">
                <li><a href="{{route('admin.registration')}}">Зарегистрировать нового пользователя</a></li>
                <li><a href="{{route('admin.about')}}">Редактирование страницы о нас</a></li>
                <li><a href="{{route('admin.commentsModeration')}}">Модерация комментариев</a></li>
                <li><a disabled>Редактор меню</a>
                <ul class="submenu" id="editMenuCategories">
                    <li>
                        <a href="#newMenuCategory" rel="modal:open">Новая категория</a>
                        <a id="closeNewMenuCategoryModal" href="#newMenuCategory" class="hidden" rel="modal:close"></a>
                    </li>
                </ul>
                </li>
                <li><a href="{{route('admin.requests')}}">Просмотр заказов</a></li>
                <li><a href="{{route('admin.editRestaurants')}}">Редактирование рестаранов</a></li>
            </ul>
        </li>
    </ul>
    @if(!Auth::check())
    <a href="{{route('login')}}" class="authorizationBlock">Войти</a>
    @else
        <a href="{{route('logout')}}" class="authorizationBlock">{{auth()->user()->name}} <img src="{{asset('./imgs/logout_111063.svg')}}" height="20" style="vertical-align: text-bottom"></a>
    @endif
</nav>
<div class="content">
    @yield('content')
    <div class="modal" id="newMenuCategory">
        <h2 style="text-align: center">Новая категория</h2>
        <form id="newCategoryForm" class="form" method="POST" action="{{route('admin.menu.addNewCategory')}}">
            @csrf
            <input type="text" class="lastInput" name="categoryName" id="newCategoryName" placeholder="Название категории">
            <div class="error" id="invalid_category_name">Название не может быть пустым</div>
            <div class="error hidden" id="errorAddCategory">Ошибка при добавлении категории</div>
            <input type="submit" id="newCategorySubmitBtn" disabled class="button" value="Создать">
        </form>
    </div>
</div>
<footer>
    Телефон: 8 800 555 3 555<br>Эл. почта: example@gmail.com
</footer>
</body>
</html>
