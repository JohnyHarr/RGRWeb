@extends('template')

@section('backgroundImage')
    <div id="background"></div>
@endsection

@section('content')
    <h1>Меню</h1>
    <h2>Пицца</h2>
    <div class="foodMenuItemContainer">
        <div class="foodMenuItemBlock">
            <img src="{{asset("./imgs/menuItemTestImg.webp")}}" class="foodMenuItemImg">
            <div class="foodMenuItemName">Имя</div>
            <div class="foodMenuItemDescription">Описание</div>
            <div class="foodMenuItemPrice">цена</div>
        </div>
        <div class="foodMenuItemBlock">
            <img src="{{asset("./imgs/menuItemTestImg.webp")}}" class="foodMenuItemImg">
            <div class="foodMenuItemName">Имя</div>
            <div class="foodMenuItemDescription">Описание</div>
            <div class="foodMenuItemPrice">цена</div>
        </div>
        <div class="foodMenuItemBlock">
            <img src="{{asset("./imgs/menuItemTestImg.webp")}}" class="foodMenuItemImg">
            <div class="foodMenuItemName">Имя</div>
            <div class="foodMenuItemDescription">Описание</div>
            <div class="foodMenuItemPrice">цена</div>
        </div>
        <div class="foodMenuItemBlock">
            <img src="{{asset("./imgs/menuItemTestImg.webp")}}" class="foodMenuItemImg">
            <div class="foodMenuItemName">Имя</div>
            <div class="foodMenuItemDescription">Описание</div>
            <div class="foodMenuItemPrice">цена</div>
        </div>
        <div class="foodMenuItemBlock">
            <img src="{{asset("./imgs/menuItemTestImg.webp")}}" class="foodMenuItemImg">
            <div class="foodMenuItemName">Имя</div>
            <div class="foodMenuItemDescription">Описание</div>
            <div class="foodMenuItemPrice">цена</div>
        </div>
    </div>
@endsection
