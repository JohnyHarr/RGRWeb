@extends('template')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('script')
    <script>
        const query = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

        let isLoginValid = false;
        let isPasswordValid = false;

        function notifyValidStatusChanged(loginValid, passwordValid) {
            if (loginValid !== undefined) {
                isLoginValid = loginValid;
            }
            if (passwordValid !== undefined) {
                isPasswordValid = passwordValid;
            }
            changeSubmitButtonStatus();
        }

        function changeSubmitButtonStatus() {
            $("#submit").prop("disabled", !(isPasswordValid && isLoginValid));
        }

        $(document).ready(function () {
            const form = $("#form");
            const login = $('#login');
            const password = $('#password');
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
            });

            login.on('change', function (){
                if(!login.val().match(query)){
                    $("#invalid_login").removeClass('hidden');
                    notifyValidStatusChanged(false, undefined);
                } else {
                    $("#invalid_login").addClass('hidden');
                    notifyValidStatusChanged(true, undefined);
                }
            })

            password.on('keyup', function (){
                if(password.val().length === 0){
                    $("#invalid_password").removeClass('hidden');
                    notifyValidStatusChanged(undefined, false);
                } else {
                    $("#invalid_password").addClass('hidden');
                    notifyValidStatusChanged(undefined, true);
                }
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
        <input type="text" class="lastInput" placeholder="Логин" id="login">
        <div id="invalid_login" class="error">Некорректный логин(email)</div>
        <input type="password" placeholder="Пароль" id="password" class="lastInput">
        <div id="invalid_password" class="error">Пароль не может быть пуст</div>
        <div id="login_error" class="error hidden"></div>
        <input type="submit" disabled id="submit" class="button" value="Войти">
        <input id="register"  class="button" value="Зарегистрироваться"
               onclick="window.location='{{route('registration')}}'">
    </form>
@endsection
