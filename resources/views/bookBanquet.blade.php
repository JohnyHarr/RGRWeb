@extends('template')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('script')
    <script>

        const query = /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/;

        function sendEmail(formData) {
            fetch("{{route('banquet.notify')}}", {
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

        async function fetchPrice(amount, menuItems) {
            let formData = new FormData;
            if (!menuItems.length || !isValidAmount(amount)) {
                return '';
            }
            formData.append('amount', amount);
            formData.append('menuItems[]', menuItems);
            let data = {
                menuItems,
                amount
            }
            console.log(formData.getAll('menuItems[]'))
            return await fetch("{{route('banquet.totalSum')}}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            }).then(response => response.text()).then(data => {
                return JSON.parse(data)['totalPrice'].original;
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

        function isValidAmount(amount) {
            const error = $('#errorAmount');
            if (isNaN(amount) || Number(amount) <= 0) {
                error.removeClass('hidden');
                console.log('amount ' + amount)
                return false
            } else {
                error.addClass('hidden');
                return true;
            }
        }

        $(document).ready(function () {

            let isName = false;
            let isPhone = false;
            let isDate = false;
            let isAmount = false;

            function notifyValidStatusChanged(nameValid, phoneValid, dateValid, amountValid) {
                if (nameValid !== undefined) {
                    isName = nameValid;
                }
                if (phoneValid !== undefined) {
                    isPhone = phoneValid;
                }
                if (dateValid !== undefined) {
                    isDate = dateValid;
                }
                if (amountValid !== undefined) {
                    isAmount = amountValid;
                }
                changeSubmitButtonStatus();
            }

            function changeSubmitButtonStatus() {
                console.log(!(isPhone && isName && isDate && isAmount));
                $("#bookPlace").prop("disabled", !(isPhone && isName && isDate && isAmount));
            }

            $('#personName').on('keyup', function () {
                notifyValidStatusChanged(isNameValid(this.value), undefined, undefined);
            })

            $('#phone').on('keyup', function () {
                notifyValidStatusChanged(undefined, isPhoneValid(this.value), undefined);
            })

            $('#date').on('change', function () {
                notifyValidStatusChanged(undefined, undefined, isDateValid(new Date(this.value)));
            })

            $('#amountOfPerson').on('keyup', async function () {
                notifyValidStatusChanged(undefined, undefined, undefined, isValidAmount(this.value))
                let data = await fetchPrice(this.value, getCheckBoxes());
                $('#bookPlace').val('Организовать (' + data + '₽)');
            })

            $('#bookingForm').on('submit', function (ev) {
                ev.preventDefault();
                const formData = new FormData(this)
                fetch("{{route('banquet.book')}}", {
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

            function getCheckBoxes() {
                let checkedBoxes = document.querySelectorAll('input[name="dish[]"]:checked');
                let arr = [];
                checkedBoxes.forEach(checkedBox => {
                    arr.push(checkedBox.value);
                })
                return arr;
            }

            $('.checkbox').on('change', async function () {
                console.log('trig')
                let data = await fetchPrice($('#amountOfPerson').val(), getCheckBoxes());
                $('#bookPlace').val('Организовать (' + data + '₽)');
            })

            $('.foodMenuItemBlock').on('click', function () {
                const checkbox = $(this).find('.checkbox');
                checkbox.prop('checked', !checkbox.prop('checked'))
                    $(this).toggleClass('checked');
                checkbox.trigger('change');
            })

        })

    </script>
@endsection

@section('backgroundImage')
    <div id="background"></div>
@endsection

@section('content')
    <h1>Организовать банкет</h1>
    <form id="bookingForm" class="form requests" method="POST" action="{{route('banquet.book')}}">
        @csrf
        <input name="name" id="personName" class="lastInput" placeholder="Имя">
        <div class="error" id="errorName">Поле имя должно быть непусто</div>
        <input name="phone" id="phone" class="lastInput" placeholder="Телефон">
        <div class="error" id="errorPhone">Некорректный формат телефона</div>
        <input name="date" id="date" type="date" class="lastInput" placeholder="Дата">
        <div class="error" id="errorDate">Некорректный формат даты</div>
        <input name="amountOfPerson" id="amountOfPerson" type="text" class="lastInput" placeholder="Количетсво человек">
        <div class="error" id="errorAmount">Количество человек должно быть больше 0</div>
        <label for="curRestaurant">Ресторан: </label>
        <select class="select_rest" id="curRestaurant" name="restaurant" type="text">
            @foreach($restaurants as $restaurant)
                <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
            @endforeach
        </select>
        <h2>Меню:</h2>
        @foreach($menuCategories as $menuCategory)
            <h3>{{$menuCategory->category_name}}</h3>
            <div class="checkBoxContainer foodMenuItemContainer">
                @foreach($menuCategory->menuItems as $menuItem)
                    <div class="foodMenuItemBlock">
                        <input type="checkbox" id="checkBoxMenuItem-{{$menuItem->id}}" name="dish[]"
                               value="{{$menuItem->id}}" class="checkbox hidden">
                        @if($menuItem->image == null)
                            <img src="{{asset("./imgs/menuItemTestImg.webp")}}" class="foodMenuItemImg">
                        @else
                            <img src="{{asset('/storage/'.$menuItem->image)}}" class="foodMenuItemImg">
                        @endif
                        <div class="foodMenuItemName"><strong>{{$menuItem->name}}</strong></div>
                        <div class="foodMenuItemPrice">{{$menuItem->price}} руб</div>
                    </div>
                @endforeach
            </div>
        @endforeach
        <div class="error hidden" id="errorBook">Ошибка при попытке обработать банкет. Попробуйте записаться на другое
            время
        </div>
        <div class="not_error hidden" id="confirmBook">Банкет сформирован успешно</div>
        <input id="bookPlace" class="button" disabled type="submit" value="Забронировать">
    </form>
@endsection
