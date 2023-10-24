@extends('adminTemplate')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('script')
    <script>
        $(document).ready(function () {

            $('.moderateRequestRow').on('click', function () {
                $('#moderateRequest').trigger('click');
                $('#deleteModalName').text($(this).find('.name').text())
                $('#deleteModalDate').text($(this).find('.date').text())
                $('#deleteModalPhone').text($(this).find('.phone').text())
                $('#deleteId').val(this.id.replace('bookingPlaceId-', ''))
            })

            $('.moderateBanquetRow').on('click', function () {
                $('#moderateBanquet').trigger('click');
                $('#deleteModalBanquetName').text($(this).find('.name').text())
                $('#deleteModalBanquetDate').text($(this).find('.date').text())
                $('#deleteModalBanquetPhone').text($(this).find('.phone').text())
                $('#deleteModalPrice').text($(this).find('.price').text())
                $('#deleteBanquetId').val(this.id.replace('banquet-', ''))
            })

            $('#deleteBookingForm').on('submit', function (ev) {
                const id = $(this).find('#deleteId').val();
                ev.preventDefault();
                fetch('{{route('admin.request.deleteBooking')}}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this)
                }).then(response => response.text()).then(data => {
                    if(data === 'deleted'){
                        $('#bookingPlaceId-'+id).remove();
                        $('#moderateRequestClose').trigger('click');
                    } else {
                        $('#deleteError').removeClass('hidden');
                    }
                })
            })

            $('#deleteBanquetForm').on('submit', function (ev) {
                const id = $(this).find('#deleteBanquetId').val();
                ev.preventDefault();
                fetch('{{route('admin.request.deleteBanquet')}}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this)
                }).then(response => response.text()).then(data => {
                    if(data === 'deleted'){
                        $('#banquet-'+id).remove();
                        $('#moderateBanquetClose').trigger('click');
                    } else {
                        $('#deleteErrorBanquet').removeClass('hidden');
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
    <h1>Бронирования столиков</h1>
    <table class="requestsTable">
        <tbody>
        <tr>
            <th>Время</th>
            <th>Номер телефона</th>
            <th>Имя</th>
            <th>Номер столика</th>
            <th>Ресторан</th>
        </tr>
        @foreach($bookingsPlaces as $bookingPlace)
            <tr class="clickable moderateRequestRow" id="bookingPlaceId-{{$bookingPlace->id}}">
                <td class="date">{{$bookingPlace->date}}</td>
                <td class="phone">{{$bookingPlace->phone}}</td>
                <td class="name">{{$bookingPlace->name}}</td>
                <td>{{$bookingPlace->place()->first()->id}}</td>
                <td>{{$bookingPlace->place()->first()->restaurant()->first()->name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <h1>Банкеты</h1>
    <table class="requestsTable">
        <tbody>
        <tr>
            <th>Время</th>
            <th>Номер телефона</th>
            <th>Имя</th>
            <th>Ресторан</th>
            <th>Итоговая цена</th>
        </tr>
        @foreach($banquets as $banquet)
            <tr class="clickable moderateBanquetRow" id="banquet-{{$banquet->id}}">
                <td class="date">{{$banquet->date}}</td>
                <td class="phone">{{$banquet->phone}}</td>
                <td class="name">{{$banquet->name}}</td>
                <td>{{$banquet->restaurant()->first()->name}}</td>
                <td class="price">{{$banquet->totalPrice}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div id="moderateRequests" class="modal">
        <h2>Удалить бронь?</h2>
        <form id="deleteBookingForm" class="form requests deleteFormModal" method="POST" enctype="multipart/form-data"
              action="{{route('admin.request.deleteBooking')}}">
            @csrf
            <input class="hidden" name="id" id="deleteId">
            <div class="deleteModalInfoHeader">Имя:</div>
            <div class="deleteModalInfoContent" id="deleteModalName"></div>
            <div class="deleteModalInfoHeader">Телефон:</div>
            <div class="deleteModalInfoContent" id="deleteModalPhone"></div>
            <div class="deleteModalInfoHeader">Дата:</div>
            <div class="deleteModalInfoContent" id="deleteModalDate"></div>
            <div id="deleteError" class="error hidden">Ошибка удаления</div>
            <input id="deleteItemButton" type="submit" class="button" value="Удалить">
        </form>
        <a id="moderateRequest" class="hidden" href="#moderateRequests" rel="modal:open">Open Modal</a>
        <a id="moderateRequestClose" class="hidden" href="#moderateRequests" rel="modal:close">close Modal</a>
    </div>
    <div id="moderateBanquetModal" class="modal">
        <h2>Удалить бронь?</h2>
        <form id="deleteBanquetForm" class="form requests deleteFormModal" method="POST" enctype="multipart/form-data"
              action="">
            @csrf
            <input class="hidden" name="id" id="deleteBanquetId">
            <div class="deleteModalInfoHeader">Имя:</div>
            <div class="deleteModalInfoContent" id="deleteModalBanquetName"></div>
            <div class="deleteModalInfoHeader">Телефон:</div>
            <div class="deleteModalInfoContent" id="deleteModalBanquetPhone"></div>
            <div class="deleteModalInfoHeader">Дата:</div>
            <div class="deleteModalInfoContent" id="deleteModalBanquetDate"></div>
            <div class="deleteModalInfoHeader">Итоговая цена:</div>
            <div class="deleteModalInfoContent" id="deleteModalPrice"></div>
            <div id="deleteErrorBanquet" class="error hidden">Ошибка удаления</div>
            <input id="deleteItemButton" type="submit" class="button" value="Удалить">
        </form>
        <a id="moderateBanquet" class="hidden" href="#moderateBanquetModal" rel="modal:open">Open Modal</a>
        <a id="moderateBanquetClose" class="hidden" href="#moderateBanquetModal" rel="modal:close">close Modal</a>
    </div>
@endsection
