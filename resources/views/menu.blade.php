@extends('template')

@section('script')
    <script src="{{asset('./js/slider.js')}}"></script>
@endsection

@section('backgroundImage')
    <div id="background"></div>
@endsection

@section('content')
    <h1>Меню</h1>
    <div class="slideshow-container_menu">

        <div class="mySlides fade">
            <img src="{{asset('./imgs/menu/XXXL.webp')}}" style="width:100%" alt="r1img1">
            <div class="text_menu"></div>
        </div>

        <div class="mySlides fade">
            <img src="{{asset('./imgs/menu/XXXL (1).webp')}}" style="width:100%" alt="r1img2">
            <div class="text_menu"></div>
        </div>

        <div class="mySlides fade">
            <img src="{{asset('./imgs/menu/XXXL (2).webp')}}" style="width:100%" alt="r1img3">
            <div class="text_menu"></div>
        </div>
        <div style="text-align:center">
            <span class="slide_dot" onclick="currentSlide(1)"></span>
            <span class="slide_dot" onclick="currentSlide(2)"></span>
            <span class="slide_dot" onclick="currentSlide(3)"></span>
        </div>

        <a class="prev" onclick="plusSlides(-1)">❮</a>
        <a class="next" onclick="plusSlides(1)">❯</a>

    </div>
    @foreach($categories as $category)
    <h2>{{$category->category_name}}</h2>
    <div class="foodMenuItemContainer">
        @foreach($category->menuItems as $menuItem)
            <div class="foodMenuItemBlock">
                @if($menuItem->image == null)
                    <img src="{{asset("./imgs/menuItemTestImg.webp")}}" class="foodMenuItemImg">
                @else
                    <img src="{{asset('/storage/'.$menuItem->image)}}" class="foodMenuItemImg">
                @endif
                <div class="foodMenuItemName"><strong>{{$menuItem->name}}</strong></div>
                <div class="foodMenuItemDescription">{{$menuItem->description}}</div>
                <div class="foodMenuItemPrice">{{$menuItem->price}} руб</div>
            </div>
        @endforeach
    </div>
    @endforeach
@endsection
