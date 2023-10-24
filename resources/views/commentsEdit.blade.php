@extends('adminTemplate')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('script')
    <script>

        $(document).ready(function () {
            $('.form').on('submit', function (events) {
                events.preventDefault();
                const id = this['id'].value;
                const error = $('#error-'+id);
                if(!$('#textAreaId-'+id).val().length){
                    error.removeClass('hidden');
                    return;
                } else {
                    error.addClass('hidden');
                }
                fetch("{{route('admin.changeComment')}}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this)
                }).then(response => {
                    if(response.status === 200){
                        $('#not_error-'+id).removeClass('hidden');
                    } else {
                        error.removeClass('hidden');
                        error.text('Произошла ошибка при изменении');
                    }
                })
            })

            $('.deleteBtn').on('click', function () {
                const id = this.parentNode['id'].value;
                fetch("{{route('admin.deleteComment')}}",{
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this.parentNode)
                    }
                ).then(response => {
                    if(response.status === 200){
                        $('#comment-block-'+id).remove();
                    } else {
                        let error = $('#error-'+id);
                        error.removeClass('hidden');
                        error.text('Удаление не удалось');
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
    <h1>Отзывы</h1>
    <div class="comments_container">
        @if($comments->count())
            @foreach($comments as $comment)
                <div class="comment_block" id="comment-block-{{$comment->id}}">
                    <div class="comment_username">{{$comment->user->name}}</div>
                    <div class="comment_timestamp">{{$comment->created_at->format("d-m-Y")}}</div>
                    <hr id="hr">
                    <form class="form" method="POST" action="{{route('admin.changeComment')}}">
                        @csrf
                        <input class="hidden" name="id" value="{{$comment->id}}">
                        <textarea name="comment" id="textAreaId-{{$comment->id}}" class="comment_content">{{$comment->comment}} </textarea>
                        <div id="error-{{$comment->id}}" class="error hidden">Комментарий не может быть пуст</div>
                        <div id="not_error-{{$comment->id}}" class="not_error hidden">Комментарий изменен</div>
                        <input class="button" type="submit" value="Изменить">
                        <input class="button deleteBtn" value="Удалить">
                    </form>
                </div>
            @endforeach
        @else
            <h2 class="noComments">Нет отзывов</h2>
        @endif
    </div>
@endsection
