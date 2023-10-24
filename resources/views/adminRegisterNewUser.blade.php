@extends('template')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('script')
    <script>
        const query = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

        async function isValidLogin(login) {
            const error = $('#invalid_login');
            if (!login.match(query)) {
                error.removeClass('hidden');
                error.text("Некорректный логин(email)");
                return false;
            } else {
                error.addClass('hidden');
                return await fetch("{{route('register.checkEmail')}}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'text/plain',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: login
                }).then(response => response.text()).then(data => {
                    console.log(' content:' + data);
                    if (data === 'valid') {
                        error.addClass('hidden');
                        return true
                    } else {
                        error.text("Пользователь с таким логином уже существует");
                        error.removeClass('hidden');
                        return false;
                    }
                })
            }
        }

        function isValidPassword(password) {
            const error = $('#invalid_password');
            if (password.length < 8) {
                error.removeClass('hidden');
                return false
            } else {
                error.addClass('hidden');
                return true;
            }
        }

        function isValidUserName(username) {
            const error = $('#invalid_username');
            if (username.length === 0) {
                error.removeClass('hidden');
                return false
            } else {
                error.addClass('hidden');
                return true;
            }
        }

        $(document).ready(function () {
            const login = $("#login");
            const username = $("#username");
            const password = $("#password");
            let isLoginValid = false;
            let isPasswordValid = false;
            let isUserNameValid = false;

            function notifyValidStatusChanged(loginValid, passwordValid, userNameValid) {
                if (loginValid !== undefined) {
                    isLoginValid = loginValid;
                }
                if (passwordValid !== undefined) {
                    isPasswordValid = passwordValid;
                }
                if (userNameValid !== undefined) {
                    isUserNameValid = userNameValid;
                }
                console.log('notified');
                changeSubmitButtonStatus();
            }

            function changeSubmitButtonStatus() {
                console.log('inside change:: ' +!(isUserNameValid && isPasswordValid && isLoginValid));
                $("#register").prop("disabled", !(isUserNameValid && isPasswordValid && isLoginValid));
            }

            login.on("blur", async function () {
                let value = await isValidLogin(login.val())
                console.log(value);
                notifyValidStatusChanged(value, undefined, undefined);
            });
            password.on("keyup", function () {
                console.log(isValidPassword(password.val()));
                notifyValidStatusChanged(undefined, isValidPassword(password.val()), undefined);
            })
            username.on("keyup", function () {
                console.log(isValidUserName(username.val()));
                notifyValidStatusChanged(undefined, undefined, isValidUserName(username.val()));
            })
        })
    </script>
@endsection

@section('backgroundImage')
    <div id="background"></div>
@endsection

@section('content')
    <h1 style="width: 100%; text-align: center">Регистрация нового пользователя</h1>
    <form id="form" class="loginForm form"  method="POST" action="{{route('admin.register')}}">
        @csrf
        <input type="text" class="lastInput" name="email" placeholder="Логин(email)" id="login">
        <div id="invalid_login" class="error hidden">Некорректный логин(email)</div>
        <input type="text" class="lastInput" placeholder="Имя" name="username" id="username">
        <div id="invalid_username" class="error hidden">Имя пользователя должно быть непусто</div>
        <input type="password" class="lastInput" placeholder="Пароль" name="password" id="password">
        <div id="invalid_password" class="error hidden">Пароль должен содержать хотя бы 8 символов</div>
        <input type="text" class="lastInput" placeholder="Роль(необязательно)" name="role" id="role" class="lastInput">
        <input type="submit" id="register" disabled class="button" value="Зарегистрироваться"></input>
    </form>
@endsection
