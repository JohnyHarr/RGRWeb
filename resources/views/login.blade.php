@extends('template')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('script')
    <script>
        const query = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        $(document).ready(function () {
            const form = $("#form");
            form.on("submit", function (event) {
                event.preventDefault()
                const login = $("#login").val();
                const password = $("#password").val();
                const formData = new FormData();
                formData.append("email", login);
                formData.append("password", password);
                fetch('login/authenticate', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                }).then(response => response.text()).then(data => {
                    if (data === 'auth_failed') {
                        $("#login_error").text("Неправильный логин или пароль").removeClass("hidden");
                    }
                    if (data === 'auth_success') {
                        window.location.replace('{{route('home')}}')
                    }
                })
            })
        })
    </script>
@endsection

@section('backgroundImage')
    <div id="background"></div>
@endsection

@section('content')
    <h1 style="width: 100%; text-align: center">Авторизация</h1>
    <form id="form" class="loginForm form">
        @csrf
        <input type="text" placeholder="Логин" id="login">
        <input type="text" placeholder="Пароль" id="password" class="lastInput">
        <div id="login_error" class="error hidden"></div>
        <input type="submit" id="submit" class="button" value="Войти">
        <input id="register" class="button" value="Зарегистрироваться"
               onclick="window.location='{{route('registration')}}'">
    </form>
@endsection
