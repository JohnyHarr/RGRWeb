@extends('adminTemplate')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('script')
    <script>

        function isValidName(name, prefix) {
            if(name.length === 0){
               $('#'+prefix+'ErrorName').removeClass('hidden');
                return false;
            } else {
                return true;
            }
        }

        function isValidAmount(amount, prefix = '') {
            const error = $('#' + prefix + 'ErrorAmount');
            if (isNaN(amount) || Number(amount) < 0) {
                error.removeClass('hidden');
                return false
            } else {
                error.addClass('hidden');
                return true;
            }
        }

        $(document).ready(function () {

            $('#addRestaurantBtn').on('click', function () {
                $('#newRest').trigger('click');
            })

            function updateButtonUpdate() {
                $('.moderateRequestRow').on('click', function () {
                    let currentEl = this.getElementsByTagName('td');
                    $('#updateName').val(currentEl[0].innerText);
                    $('#updateAmount').val(currentEl[1].innerText);
                    $('#updateId').val(this.id);
                    $('#updateRest').trigger('click');
                })
            }

            $('#newRestForm').on('submit', function (ev) {
                ev.preventDefault();
                const form = $('#newRestForm');
                const name = form.find('#newName').val();
                const amount = form.find('#newAmount').val();
                if(!isValidName(name, 'new') || !isValidAmount(amount, 'new')){
                    return;
                }
                fetch("{{route('admin.editRestaurants.save')}}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this)
                }).then(response => response.text()).then(data => {
                    {
                        let parsedData = JSON.parse(data);
                        if (parsedData['result'] === 'saved') {
                            $('#restTable').append('<tr class="clickable moderateRequestRow" id="' + parsedData["id"] + '"><td>' + parsedData["name"] + '</td><td>' + parsedData["amount"] + '</td></tr>');
                            updateButtonUpdate();
                            $('#newRestModalClose').trigger('click');
                        } else {
                            $('#saveError').removeClass('hidden');
                        }
                    }
                })
                $('#newRestForm')[0].reset();
            })

            $('#updateRestForm').on('submit', function (ev) {
                ev.preventDefault();
                const form = $('#updateRestForm');
                const name = form.find('#updateName').val();
                const amount = form.find('#updateAmount').val();
                if(!isValidName(name, 'update') || !isValidAmount(amount, 'update')){
                    return;
                }
                fetch("{{route('admin.editRestaurants.update')}}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this)
                }).then(response => response.text()).then(data => {
                    {
                        let parsedData = JSON.parse(data);
                        if (parsedData['result'] === 'saved') {
                            $('#updateRestModalClose').trigger('click');
                            $('#'+parsedData['id']).html('<td>' + parsedData["name"] + '</td><td>' + parsedData["amount"] + '</td>');
                        } else {
                            $('#updSaveError').removeClass('hidden');
                        }
                    }
                })
            })

            $('#deleteRestaurantBtn').on('click', function () {
                let id = $('#updateId').val();
                fetch("{{route('admin.editRestaurants.delete')}}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this.parentNode)
                }).then(response => {
                    if(response.status === 200){
                        $('#'+id).remove();
                        $('#updateRestModalClose').trigger('click');
                    }
                });
            })
            updateButtonUpdate()
        })
    </script>
@endsection

@section('backgroundImage')
    <div id="background"></div>
@endsection

@section('content')
    <h1>Редактирование информации о ресторанах</h1>
    <table class="requestsTable">
        <tbody id="restTable">
        <tr>
            <th>Ресторан</th>
            <th>Количество столиков</th>
        </tr>
        @foreach($restaurants as $restaurant)
            <tr class="clickable moderateRequestRow" id="{{$restaurant->id}}">
                <td>{{$restaurant->name}}</td>
                <td>{{$restaurant->places()->count()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <input class="button" value="Добавить ресторан" id="addRestaurantBtn">
    <div id="newRestModal" class="modal">
        <div><h2>Добавить ресторан</h2></div>
        <form id="newRestForm" class="form" method="POST" enctype="multipart/form-data"
              action="{{route('admin.editRestaurants.save')}}">
            @csrf
            <input name="name" id="newName" class="lastInput" placeholder="Название">
            <div id="newErrorName" class="error hidden">Название должно быть непусто</div>
            <input id="newAmount" name="places" class="lastInput" placeholder="Количество стоиликов">
            <div id="newAmountError" class="error hidden">Число столиков должно быть указано и больше 0</div>
            <div class="error hidden" id="saveError">Ошибка сохранения</div>
            <input id="saveRestaurantBtn" type="submit" class="button" value="Сохранить">
        </form>
    </div>
    <div id="updateRestModal" class="modal">
        <div><h2>Изменить</h2></div>
        <form id="updateRestForm" class="form" method="POST" enctype="multipart/form-data"
              action="{{route('admin.editRestaurants.update')}}">
            @csrf
            <input id="updateName" name="name" class="lastInput" placeholder="Название">
            <input id="updateId" name="id" class="lastInput hidden" placeholder="Название">
            <div id="updateErrorName" class="error hidden">Название должно быть непусто</div>
            <input id="updateAmount" name="places" class="lastInput" placeholder="Количество стоиликов">
            <div id="updateErrorAmount" class="error hidden">Число столиков должно быть указано и больше 0</div>
            <div class="error hidden" id="updSaveError">Ошибка сохранения</div>
            <input id="updateRestaurantBtn" type="submit" class="button" value="Сохранить">
            <input id="deleteRestaurantBtn" class="button" value="Удалить">
        </form>
    </div>
    <a id="newRest" href="#newRestModal" class="hidden" rel="modal:open"></a>
    <a id="newRestModalClose" href="#newRestModal" class="hidden" rel="modal:close"></a>
    <a id="updateRest" href="#updateRestModal" class="hidden" rel="modal:open"></a>
    <a id="updateRestModalClose" href="#updateRestModal" class="hidden" rel="modal:close"></a>
@endsection
