@extends('template')

@section('script')
    <script>
        function handleComment(str) {
            const error = $("#comment_error");
            const loggedIn = {{ auth()->check() ? 'true' : 'false' }};
            let statusOk = true;
            if (str.length === 0) {
                statusOk = false;
                error.text("Комментарий не должен быть пустым");
            }
            if (!loggedIn) {
                error.text("Авторизуйтесь, чтобы оставлять комментарии");
                statusOk = false;
            }
            if (str.length === 0 && !loggedIn) {
                statusOk = false;
                error.html("Авторизуйтесь, чтобы оставить комментарий.<br>Комментарий не может быть пуст.")
            }
            if (!statusOk) {
                error.removeClass("hidden");
            } else {
                error.addClass("hidden")
            }
            return statusOk;
        }

        $(document).ready(function () {
            $(".form").on("submit", function (event) {
                event.preventDefault();
                const str = $("#message").val();
                if (handleComment(str)) {
                    console.log('comment:'+str);
                }
                $(".form")[0].reset();
            });
            $("#message").on("keyup", function () {
                handleComment($("#message").val(), true);
            })
        })
    </script>
@endsection

@section('backgroundImage')
    <div id="background"></div>
@endsection

@section('content')
    <h1 id="restaurants">Сеть ресторанов</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
        ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
        aliquip ex ea commodo consequat.
        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
        laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
        laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
        laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
        laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
        laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
        laborum.</p>
    <h1 id="accustom">Обслуживание</h1>
    <h1 id="awards">Награды</h1>
    <h1 id="staff">Персонал</h1>
    <h1 id="comments">Отзывы</h1>
    <form  class="topBefore form" >
        @csrf
        <textarea id="message" type="text" placeholder="Отзыв"></textarea>
        <div id="comment_error" class="error hidden"></div>
        <input id="submit" type="submit" class="button" value="Отправить">
    </form>
    <div class="comments_container">
        <div class="comment_block">
            <div class="comment_username">username</div>
            <div class="comment_timestamp">timestamp</div>
            <hr id="hr">
            <div class="comment_content">comment</div>
        </div>
    </div>
@endsection
