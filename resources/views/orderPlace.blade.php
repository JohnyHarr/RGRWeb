@extends('template')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('script')
    <script>

        const query = /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/;

        function fetchPlaces(id, selectPlace) {
            fetch('/order/places/' + id, {
                method: 'GET',
                'Accept': 'application/json'
            }).then(response => response.text()).then(data => {
                const parsedData = JSON.parse(data);
                console.log(parsedData['places'])
                parsedData['places'].forEach(place => {
                    selectPlace.append('<option value="' + place['id'] + '">' + place['id'] + '</option>');
                })
            })
        }

        function sendEmail(formData) {
            fetch("{{route('order.notify')}}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            }).then(response => {
                if (response.status === 200)
                    console.log('notified')
            })
        }

        function isDateValid(date) {
            let currentDate = new Date();
            if (date > currentDate) {
                $('#errorDate').addClass('hidden');
                return true
            } else {
                $('#errorDate').removeClass('hidden');
                return false;
            }
        }

        function isNameValid(name) {
            if (name.length > 0) {
                $('#errorName').addClass('hidden');
                return true
            } else {
                $('#errorName').removeClass('hidden');
                return false;
            }
        }

        function isPhoneValid(phone) {
            if (phone.match(query)) {
                $('#errorPhone').addClass('hidden');
                return true
            } else {
                $('#errorPhone').removeClass('hidden');
                return false;
            }
        }

        $(document).ready(function () {

            let isName = false;
            let isPhone = false;
            let isDate = false;

            function notifyValidStatusChanged(nameValid, phoneValid, dateValid) {
                if (nameValid !== undefined) {
                    isName = nameValid;
                }
                if (phoneValid !== undefined) {
                    isPhone = phoneValid;
                }
                if (dateValid !== undefined) {
                    isDate = dateValid;
                }
                changeSubmitButtonStatus();
            }

            function changeSubmitButtonStatus() {
                $("#bookPlace").prop("disabled", !(isPhone && isName && isDate));
            }

            let currentRest = $('#curRestaurant')
            currentRest.on('change', function () {
                const id = $(this).val();
                const selectPlace = $('#updatePlace');
                selectPlace.html('');
                fetchPlaces(id, selectPlace);
            })

            $('#personName').on('keyup', function () {
                notifyValidStatusChanged(isNameValid(this.value), undefined, undefined);
            })

            $('#phone').on('keyup', function () {
                notifyValidStatusChanged(undefined, isPhoneValid(this.value), undefined);
            })

            $('#date').on('change', function () {
                notifyValidStatusChanged(undefined, undefined, isDateValid(new Date(this.value)));
            })

            $('#bookingForm').on('submit', function (ev) {
                ev.preventDefault();
                const formData = new FormData(this)
                fetch("{{route('order.order')}}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                }).then(response => response.text()).then(data => {
                    const parsed = JSON.parse(data);
                    if (parsed['result'] === 'added') {
                        $('#confirmBook').removeClass('hidden');
                        $('#errorBook').addClass('hidden');
                        $('#bookingForm')[0].reset();
                        sendEmail(formData);
                    } else {
                        $('#confirmBook').addClass('hidden');
                        $('#errorBook').removeClass('hidden');
                    }
                })
            })

            fetchPlaces(currentRest.val(), $('#updatePlace'));
        })

    </script>
@endsection

@section('backgroundImage')
    <div id="background"></div>
@endsection

@section('content')
    <h1>Забронировать столик</h1>
    <form id="bookingForm" class="form requests" method="POST" action="{{route('order.order')}}">
        @csrf
        <input name="name" id="personName" class="lastInput" placeholder="Имя">
        <div class="error" id="errorName">Поле имя должно быть непусто</div>
        <input name="phone" id="phone" class="lastInput" placeholder="Телефон">
        <div class="error" id="errorPhone">Некорректный формат телефона</div>
        <input name="date" id="date" type="date" class="lastInput" placeholder="Дата">
        <div class="error" id="errorDate">Некорректный формат даты</div>
        <select class="select_rest minimal" id="curRestaurant" name="restaurant" type="text">
            @foreach($restaurants as $restaurant)
                <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
            @endforeach
        </select>
        <label for="updatePlace">Столик:</label>
        <select class="lastInput" id="updatePlace" name="place">
        </select>
        <div class="error hidden" id="errorBook">Ошибка при попытке зарезервировать столик. Попробуйте записаться на
            другое время
        </div>
        <div class="not_error hidden" id="confirmBook">Столик забронирован успешно</div>
        <input id="bookPlace" class="button" disabled type="submit" value="Забронировать">
    </form>
@endsection
