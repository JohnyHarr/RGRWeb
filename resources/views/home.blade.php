@extends('template')

@section('script')
    <script src="{{asset('./js/slider.js')}}"></script>
@endsection

@section('backgroundImage')
    <div id="background"></div>
@endsection

@section('content')
   <div class="content">
       <div class="slideshow-container">

           <div class="mySlides fade">
               <img src="{{asset('./imgs/home/1.jpg')}}" style="width:100%" alt="r1img1">
               <div class="text">Ресторан 1</div>
           </div>

           <div class="mySlides fade">
               <img src="{{asset('./imgs/home/2.jpg')}}" style="width:100%" alt="r1img2">
               <div class="text">Ресторан 1</div>
           </div>

           <div class="mySlides fade">
               <img src="{{asset('./imgs/home/3.jpg')}}" style="width:100%" alt="r1img3">
               <div class="text">Ресторан 1</div>
           </div>

           <a class="prev" onclick="plusSlides(-1)">❮</a>
           <a class="next" onclick="plusSlides(1)">❯</a>

       </div>
       <br>

       <div style="text-align:center">
           <span class="dot" onclick="currentSlide(1)"></span>
           <span class="dot" onclick="currentSlide(2)"></span>
           <span class="dot" onclick="currentSlide(3)"></span>
       </div>
       <br>
   </div>
@endsection
