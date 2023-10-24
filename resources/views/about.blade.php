@extends('template')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

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
                const comment = $("#message").val();
                if (handleComment(comment)) {
                    const commentsContainer = $(".comments_container");
                    fetch("{{route('about.storeComment')}}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: comment
                    }).then(response => response.text())
                        .then(data => {
                            console.log('data:'+data);
                            let parsedData = JSON.parse(data);
                            const newComment = $("<div class='comment_block'></div>");
                            newComment.html(
                                '<div class="comment_username">'+parsedData['username']+'</div>' +
                                '<div class="comment_timestamp">'+parsedData['created_at']+'</div>' +
                                '<hr id="hr">' +
                                '<div class="comment_content">'+comment+'</div>'
                            );
                            $(".noComments").remove();
                            commentsContainer.prepend(newComment);
                        })
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
    <div class="fr-view">
    {!! $aboutEditorNet->content !!}
    </div>
    <h1 id="accustom">Обслуживание</h1>
    <div class="fr-view">
        {!! $aboutEditorCust->content !!}
    </div>
    <h1 id="awards">Награды</h1>
    <div class="fr-view">
        {!! $aboutEditorAwards->content !!}
    </div>
    <h1 id="staff">Персонал</h1>
    <div class="fr-view">
        {!! $aboutEditorStaff->content !!}
    </div>
    <h1 id="comments">Отзывы</h1>
    <div class="fr-view">
    </div>
    <form class="topBefore form">
        @csrf
        <textarea id="message" type="text" placeholder="Отзыв"></textarea>
        <div id="comment_error" class="error hidden"></div>
        <input id="submit" type="submit" class="button" value="Отправить">
    </form>
    <div class="comments_container">
        @if($comments->count())
            @foreach($comments as $comment)
            <div class="comment_block">
                <div class="comment_username">{{$comment->user->name}}</div>
                <div class="comment_timestamp">{{$comment->created_at->format("d-m-Y")}}</div>
                <hr id="hr">
                <div class="comment_content">{{$comment->comment}}</div>
            </div>
            @endforeach
        @else
            <h2 class="noComments">Нет отзывов. Стань первым!</h2>
        @endif
    </div>
@endsection
