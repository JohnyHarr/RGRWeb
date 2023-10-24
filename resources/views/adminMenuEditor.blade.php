@extends('adminTemplate')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('script')
    <script>

        function isValidName(name, prefix = '') {
            const error = $('#' + prefix + 'invalid_name');
            if (name.length === 0) {
                error.removeClass('hidden');
                return false
            } else {
                error.addClass('hidden');
                return true;
            }
        }

        function isValidDescr(descr, prefix = '') {
            const error = $('#' + prefix + 'invalid_descr');
            if (descr.length === 0) {
                error.removeClass('hidden');
                return false
            } else {
                error.addClass('hidden');
                return true;
            }
        }

        function isValidPrice(price, prefix = '') {
            const error = $('#' + prefix + 'invalid_price');
            if (isNaN(price) || Number(price) < 0) {
                error.removeClass('hidden');
                return false
            } else {
                error.addClass('hidden');
                return true;
            }
        }

        $(document).ready(function () {
            let isNameValid = false;
            let isDescrValid = false;
            let isPriceValid = false;

            function notifyValidStatusChanged(nameValid, descrValid, priceValid, action = 'add') {
                if (nameValid !== undefined) {
                    isNameValid = nameValid;
                }
                if (descrValid !== undefined) {
                    isDescrValid = descrValid;
                }
                if (priceValid !== undefined) {
                    isPriceValid = priceValid;
                }
                console.log('notified');
                changeSubmitButtonStatus(action);
            }


            function changeSubmitButtonStatus(action) {
                $("#" + action + "ItemButton").prop("disabled", !(isNameValid && isDescrValid && isPriceValid));
            }

            function appendNewItem(id, name, description, imgPath, price) {
                const newItem = $('<div class="foodMenuItemBlock"></div>');
                if (imgPath !== null) {
                    newItem.html('<img src="{{asset('/storage/')}}/' + imgPath + '" class="foodMenuItemImg">' +
                        '<div class="foodMenuItemName">' + name + '</div>' +
                        '<div class="foodMenuItemDescription">' + description + '</div>' +
                        '<div class="foodMenuItemPrice">' + price + '</div>' +
                        '<input id="' + id + '" class="button updateBtn" value="Изменить">' +
                        '<input id="' + id + '" class="button deleteBtn" value="Удалить">');
                } else {
                    newItem.html('<img src="{{asset("./imgs/menuItemTestImg.webp")}}" class="foodMenuItemImg">' +
                        '<div class="foodMenuItemName">' + name + '</div>' +
                        '<div class="foodMenuItemDescription">' + description + '</div>' +
                        '<div class="foodMenuItemPrice">' + price + '</div>' +
                        '<input id="' + id + '" class="button updateBtn" value="Изменить">' +
                        '<input id="' + id + '" class="button deleteBtn" value="Удалить">')
                }
                $('.foodMenuItemNew').before(newItem);
                updateButtons();
            }

            function updateItem(id, name, description, imgPath, price) {
                const item = $('#itemId-' + id);
                if (imgPath != null) {
                    item.find('.foodMenuItemImg')[0].src = "{{asset('/storage/')}}/" + imgPath;
                } else {
                    item.find('.foodMenuItemImg')[0].src = "{{asset("./imgs/menuItemTestImg.webp")}}"
                }
                item.find('.foodMenuItemName')[0].innerText = name;
                item.find('.foodMenuItemPrice')[0].innerText = price + '₽';
                item.find('.foodMenuItemDescription')[0].innerText = description;
            }

            $('.foodMenuItemNew').on('click', function () {
                isPriceValid = false;
                isDescrValid = false;
                isNameValid = false;
                changeSubmitButtonStatus();
                $('#createNewItem').trigger('click');
            })

            $('#name').on('keyup', function () {
                notifyValidStatusChanged(isValidName(this.value), undefined, undefined);
            })

            $('#description').on('keyup', function () {
                notifyValidStatusChanged(undefined, isValidDescr(this.value), undefined);
            })

            $('#price').on('keyup', function () {
                notifyValidStatusChanged(undefined, undefined, isValidPrice(this.value));
            })

            $('#updateName').on('keyup', function () {
                notifyValidStatusChanged(isValidName(this.value, 'update_'), undefined, undefined, 'update');
            })

            $('#updateDescription').on('keyup', function () {
                notifyValidStatusChanged(undefined, isValidDescr(this.value, 'update_'), undefined, 'update');
            })

            $('#updatePrice').on('keyup', function () {
                notifyValidStatusChanged(undefined, undefined, isValidPrice(this.value, 'update_'), 'update');
            })

            $('#newItemForm').on('submit', function (e) {
                e.preventDefault();
                fetch("{{route('admin.menu.addNewDish')}}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this)
                }).then(response => response.text()).then(data => {
                    console.log('data:' + data);
                    let parsedData = JSON.parse(data);
                    if (parsedData['result'] === 'saved') {
                        appendNewItem(
                            parsedData['id'],
                            parsedData['name'],
                            parsedData['description'],
                            parsedData['imgPath'],
                            parsedData['price']
                        );
                        $('#createNewItemClose').trigger('click');
                    } else {
                        $('#addError').removeClass('hidden');
                    }
                })
                $('#newItemForm')[0].reset();
            })

            $('#editItemForm').on('submit', function (e) {
                e.preventDefault();
                fetch("{{route('admin.menu.updateDish')}}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(this)
                }).then(response => response.text()).then(data => {
                    console.log('data:' + data);
                    let parsedData = JSON.parse(data);
                    if (parsedData['result'] === 'saved') {
                        updateItem(
                            parsedData['id'],
                            parsedData['name'],
                            parsedData['description'],
                            parsedData['imgPath'],
                            parsedData['price']
                        );
                        $('#editItemClose').trigger('click');
                    } else {
                        $('#editError').removeClass('hidden');
                    }
                })
            })

            function updateButtons() {
                $('.deleteBtn').on('click', function () {
                    let id = this.id;
                    console.log("{{route('admin.menu.addNewDish')}}/" + id);
                    fetch("/admin/menu/editor/deleteDish/" + id, {
                            method: 'GET'
                        }
                    ).then(response => {
                        if (response.status === 200) {
                            $('#' + id).parent().remove();
                        }
                    })
                })

                $('.updateBtn').on('click', function () {
                    isPriceValid = true;
                    isDescrValid = true;
                    isNameValid = true;
                    changeSubmitButtonStatus('update')
                    let id = this.id;
                    $('#editItem').trigger('click');
                    $('#updateDishId').val(id);
                    const editForm = $('#editItemForm')
                    editForm.find('#updateName')[0].value = this.parentNode.getElementsByClassName('foodMenuItemName')[0].innerText;
                    editForm.find('#updateDescription')[0].value = this.parentNode.getElementsByClassName('foodMenuItemDescription')[0].innerText;
                    editForm.find('#updatePrice')[0].value = this.parentNode.getElementsByClassName('foodMenuItemPrice')[0].innerText.replace('₽', '');
                })

                $('.menuCategory').on('click', function () {
                    $('#editCategory').trigger('click');
                })

                $('#editCategoryForm').on('submit', function (ev) {
                    ev.preventDefault();
                    if (!$(this).find('#updateCategoryName').val()) {
                        $('#update_invalid_category_name').removeClass('hidden');
                        return;
                    }
                    fetch("{{route('admin.menu.editCategory')}}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: new FormData(this)
                    }).then(response => response.text()).then(data => {
                        const parsed = JSON.parse(data);
                        if(parsed['result']==='success'){
                            $('.menuCategory').text(parsed['name']);
                            $('#editCategoryClose').trigger('click');
                        }
                    })
                })
            }

            updateButtons();
        })
    </script>
@endsection

@section('backgroundImage')
    <div id="background"></div>
@endsection

@section('content')
    <h1>Изменение меню</h1>
    <div class="menuCategory clickable">{{$category->category_name}}</div>
    <div class="foodMenuItemContainer">
        @foreach($menuItems as $menuItem)
            <div class="foodMenuItemBlock" id="itemId-{{$menuItem->id}}">
                @if($menuItem->image == null)
                    <img src="{{asset("./imgs/menuItemTestImg.webp")}}" class="foodMenuItemImg">
                @else
                    <img src="{{asset('/storage/'.$menuItem->image)}}" class="foodMenuItemImg">
                @endif
                <div class="foodMenuItemName">{{$menuItem->name}}</div>
                <div class="foodMenuItemDescription">{{$menuItem->description}}</div>
                <div class="foodMenuItemPrice">{{$menuItem->price}}₽</div>
                <input id="{{$menuItem->id}}" class="button updateBtn" value="Изменить">
                <input id="{{$menuItem->id}}" class="button deleteBtn" value="Удалить">
            </div>
        @endforeach
        <div class=" clickable foodMenuItemNew">
            <img src="{{asset("./imgs/icon-add.svg")}}" class="foodMenuItemImg">
            <div class="foodMenuItemName"><strong>Новое блюдо</strong></div>
            <div class="foodMenuItemDescription" style="color: transparent">Описание</div>
            <div class="foodMenuItemPrice" style="color: transparent">цена</div>
            <input class="button deleteBtn" disabled style="border: none; background: transparent">
            <input class="button updateBtn" disabled style="border: none; background: transparent">
        </div>
    </div>
    <div id="newItemModal" class="modal">
        <form id="newItemForm" class="form" method="POST" enctype="multipart/form-data"
              action="{{route('admin.menu.addNewDish')}}">
            @csrf
            <input class="hidden" name="category_id" value="{{$category->id}}">
            <input class="lastInput" id="name" name="name" type="text" placeholder="Название блюда">
            <div id="invalid_name" class="error">Имя не должно быть пустым</div>
            <textarea class="lastInput" id="description" name="description" type="text"
                      placeholder="Описание"></textarea>
            <div id="invalid_descr" class="error">Описание не должно быть пустым</div>
            <input class="lastInput" id="price" name="price" type="text" placeholder="Цена">
            <div id="invalid_price" class="error">Цена должна быть указана и быть больше 0</div>
            <input id="image" name="image" type="file" style="border: none; padding-top: 10px">
            <div id="addError" class="error hidden">Ошибка добавления элемента</div>
            <input id="addItemButton" disabled type="submit" class="button" value="Сохранить">
        </form>
    </div>
    <div id="editItemModal" class="modal">
        <form id="editItemForm" class="form" method="POST" enctype="multipart/form-data"
              action="{{route('admin.menu.updateDish')}}">
            @csrf
            <input class="hidden" id="updateDishId" name="id">
            <input class="hidden" name="category_id" value="{{$category->id}}">
            <input class="lastInput" id="updateName" name="name" type="text" placeholder="Название блюда">
            <div id="update_invalid_name" class="error hidden">Имя не должно быть пустым</div>
            <textarea class="lastInput" id="updateDescription" name="description" type="text"
                      placeholder="Описание"></textarea>
            <div id="update_invalid_descr" class="error hidden">Описание не должно быть пустым</div>
            <input class="lastInput" id="updatePrice" name="price" type="text" placeholder="Цена">
            <div id="update_invalid_price" class="error hidden">Цена должна быть указана и быть больше 0</div>
            <input id="image" name="image" type="file" style="border: none; padding-top: 10px">
            <div id="editError" class="error hidden">Ошибка изменения элемента</div>
            <input id="updateItemButton" type="submit" class="button" value="Сохранить">
        </form>
    </div>
    <div id="editCategoryModal" class="modal">
        <h2>Изменить категорию</h2>
        <form id="editCategoryForm" class="form" method="POST" enctype="multipart/form-data"
              action="{{route('admin.menu.updateDish')}}">
            @csrf
            <input class="hidden" name="category_id" value="{{$category->id}}">
            <input class="lastInput" id="updateCategoryName" name="name" type="text" placeholder="Название категории"
                   value="{{$category->category_name}}">
            <div id="update_invalid_category_name" class="error hidden">Имя не должно быть пустым</div>
            <input id="updateItemButton" type="submit" class="button" value="Сохранить">
            <input id="deleteCategory" class="button" value="Удалить">
        </form>
    </div>
    <a id="createNewItem" class="hidden" href="#newItemModal" rel="modal:open">Open Modal</a>
    <a id="createNewItemClose" class="hidden" href="#newItemModal" rel="modal:close">close Modal</a>
    <a id="editItem" class="hidden" href="#editItemModal" rel="modal:open">Open Modal</a>
    <a id="editItemClose" class="hidden" href="#editItemModal" rel="modal:close">Close Modal</a>
    <a id="editCategory" class="hidden" href="#editCategoryModal" rel="modal:open">Open Modal</a>
    <a id="editCategoryClose" class="hidden" href="#editCategoryModal" rel="modal:close">Close Modal</a>
@endsection
