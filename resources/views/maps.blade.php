@extends('template')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('script')
    <script>
    </script>
@endsection

@section('backgroundImage')
    <div id="background"></div>
@endsection

@section('content')
    <h1>Где мы находимся?</h1>
    <script type="text/javascript" charset="utf-8" async
            src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Aaadf2dadc76670371a4ca4bb315ba569be563c614ead79e53a9dee0946221fd6&amp;width=100%25&amp;height=600&amp;lang=ru_RU&amp;scroll=true"></script>
@endsection
