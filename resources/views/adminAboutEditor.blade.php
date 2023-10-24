@extends('adminTemplate')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('./plugins/css/froala_editor.pkgd.css') }}">
@endsection

@section('script')
    <script src="{{asset('./plugins/js/froala_editor.pkgd.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            var editor = new FroalaEditor('.froalaEditor', {
                heightMin: 350,
                imageUploadParam: 'image_param',
                imageUploadURL: "{{route('admin.about.uploadImage')}}",
                imageUploadMethod: 'POST',
                imageUploadParams: {
                    froala: 'true',
                    _token: '{{csrf_token()}}'
                }
            });

            $('#netChange').on('submit', function (event) {
                event.preventDefault();
                 fetch("{{route('admin.about.changeAboutPage')}}", {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                     },
                     body: new FormData(this)
                 }).then(response => {
                    if(response.status === 200){
                        $('#not_error_1').removeClass('hidden');
                    } else {
                        $('#error_1').removeClass('hidden');
                    }
                 })
            });

            $('#custChange').on('submit', function (event) {
                event.preventDefault();
                fetch("{{route('admin.about.changeAboutPage')}}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this)
                }).then(response => {
                    if(response.status === 200){
                        $('#not_error_2').removeClass('hidden');
                    } else {
                        $('#error_2').removeClass('hidden');
                    }
                })
            })
            $('#contactsChange').on('submit', function (event) {
                event.preventDefault();
                fetch("{{route('admin.about.changeAboutPage')}}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this)
                }).then(response => {
                    if(response.status === 200){
                        $('#not_error_3').removeClass('hidden');
                    } else {
                        $('#error_3').removeClass('hidden');
                    }
                })
            })
            $('#awardsChange').on('submit', function (event) {
                event.preventDefault();
                fetch("{{route('admin.about.changeAboutPage')}}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this)
                }).then(response => {
                    if(response.status === 200){
                        $('#not_error_4').removeClass('hidden');
                    } else {
                        $('#error_4').removeClass('hidden');
                    }
                })
            })
            $('#staffChange').on('submit', function (event) {
                event.preventDefault();
                fetch("{{route('admin.about.changeAboutPage')}}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this)
                }).then(response => {
                    if(response.status === 200){
                        $('#not_error_5').removeClass('hidden');
                    } else {
                        $('#error_5').removeClass('hidden');
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
    <h1>Сеть ресторанов</h1>
    <form id="netChange" method="POST" action="{{route('admin.about.changeAboutPage')}}">
        @csrf
        <input name="id" class="hidden" value="1">
        <input name="section" class="hidden" value="Сеть ресторанов">
        <textarea class="froalaEditor" name="editor">
            @if($aboutEditorNet)
            {{$aboutEditorNet->content}}
            @endif
        </textarea>
        <div class="not_error hidden" id="not_error_1">Изменено</div>
        <div class="error hidden" id="error_1">Ошибка при изменении</div>
        <input type="submit" value="Сохранить изменения" class="button">
    </form>
    <h1>Обслуживание</h1>
    <form id="custChange" method="POST" action="{{route('admin.about.changeAboutPage')}}">
        @csrf
        <input name="id" class="hidden" value="2">
        <input name="section" class="hidden" value="Обслуживание">
        <textarea class="froalaEditor" name="editor">
            @if($aboutEditorCust)
                {{$aboutEditorCust->content}}
            @endif
        </textarea>
        <div class="not_error hidden" id="not_error_2">Изменено</div>
        <div class="error hidden" id="error_2">Ошибка при изменении</div>
        <input type="submit" value="Сохранить изменения" class="button">
    </form>
    <h1>Контакты</h1>
    <form id="contactsChange" method="POST" action="{{route('admin.about.changeAboutPage')}}">
        @csrf
        <input name="id" class="hidden" value="3">
        <input name="section" class="hidden" value="Контакты">
        <textarea class="froalaEditor" name="editor">
            @if($aboutEditorContacts)
                {{$aboutEditorContacts->content}}
            @endif
        </textarea>
        <div class="not_error hidden" id="not_error_3">Изменено</div>
        <div class="error hidden" id="error_3">Ошибка при изменении</div>
        <input type="submit" value="Сохранить изменения" class="button">
    </form>
    <h1>Награды</h1>
    <form id="awardsChange" method="POST" action="{{route('admin.about.changeAboutPage')}}">
        @csrf
        <input name="id" class="hidden" value="4">
        <input name="section" class="hidden" value="Награды">
        <textarea class="froalaEditor" name="editor">
            @if($aboutEditorAwards)
                {{$aboutEditorAwards->content}}
            @endif
        </textarea>
        <div class="not_error hidden" id="not_error_4">Изменено</div>
        <div class="error hidden" id="error_4">Ошибка при изменении</div>
        <input type="submit" value="Сохранить изменения" class="button">
    </form>
    <h1>Персонал</h1>
    <form id="staffChange" method="POST" action="{{route('admin.about.changeAboutPage')}}">
        @csrf
        <input name="id" class="hidden" value="5">
        <input name="section" class="hidden" value="Персонал">
        <textarea class="froalaEditor" name="editor">
            @if($aboutEditorStaff)
                {{$aboutEditorStaff->content}}
            @endif
        </textarea>
        <div class="not_error hidden" id="not_error_5">Изменено</div>
        <div class="error hidden" id="error_5">Ошибка при изменении</div>
        <input type="submit" value="Сохранить изменения" class="button">
    </form>
@endsection
