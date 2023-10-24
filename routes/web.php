<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin/home', [\App\Http\Controllers\HomeController::class, 'indexAdmin'])->name('admin.home')->middleware('checkIfAdmin');
Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::get('/admin/about', [\App\Http\Controllers\AboutController::class, 'adminIndex'])->name('admin.about')->middleware('checkIfAdmin');
Route::get('/admin/commentsModeration', [\App\Http\Controllers\CommentsModerationController::class, 'index'])->name('admin.commentsModeration')->middleware('checkIfAdmin');
Route::post('/admin/commentsModeration/change', [\App\Http\Controllers\CommentsModerationController::class, 'changeComment'])->name('admin.changeComment')->middleware('checkIfAdmin');
Route::post('/admin/commentsModeration/delete', [\App\Http\Controllers\CommentsModerationController::class, 'deleteComment'])->name('admin.deleteComment')->middleware('checkIfAdmin');
Route::post('/admin/about/uploadImage', [\App\Http\Controllers\AboutController::class, 'storeImage'])->name('admin.about.uploadImage')->middleware('checkIfAdmin');
Route::post('/admin/about/change', [\App\Http\Controllers\AboutController::class, 'changeAboutPage'])->name('admin.about.changeAboutPage')->middleware('checkIfAdmin');
Route::post('/about/storeComment', [\App\Http\Controllers\AboutController::class, 'storeComment'])->name('about.storeComment');
Route::get('/menu', [\App\Http\Controllers\MenuController::class, 'index'])->name('menu');
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/login/authenticate', [\App\Http\Controllers\AuthController::class, 'authenticate'])->name('authenticate');
Route::get('/registration', [\App\Http\Controllers\AuthController::class, 'registration'])->name('registration');
Route::post('/registration/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/registration/checkEmail', [\App\Http\Controllers\AuthController::class, 'checkEmail'])->name('register.checkEmail');
Route::post('/admin/registration/registerNewUser', [\App\Http\Controllers\AuthController::class, 'registerNewUser'])->name('admin.register')->middleware('checkIfAdmin');
Route::get('/admin/registration', [\App\Http\Controllers\AuthController::class, 'adminRegistration'])->name('admin.registration')->middleware('checkIfAdmin');
Route::get('/registration/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/maps', [\App\Http\Controllers\MapsController::class, 'index'])->name('maps');
Route::get('/admin/menu/editor/{categoryId}', [\App\Http\Controllers\MenuEditorController::class, 'index'])->name('admin.menu.editor')->middleware('checkIfAdmin');
Route::post('/admin/menu/editor/addNewDish', [\App\Http\Controllers\MenuEditorController::class, 'addNewDish'])->name('admin.menu.addNewDish')->middleware('checkIfAdmin');
Route::post('/admin/menu/editor/updateDish', [\App\Http\Controllers\MenuEditorController::class, 'updateMenuItem'])->name('admin.menu.updateDish')->middleware('checkIfAdmin');
Route::get('/admin/menu/editor/deleteDish/{id}', [\App\Http\Controllers\MenuEditorController::class, 'deleteDish'])->name('admin.menu.deleteDish')->middleware('checkIfAdmin');
Route::post('/admin/menu/addNewCategory', [\App\Http\Controllers\MenuEditorController::class, 'addNewCategory'])->name('admin.menu.addNewCategory')->middleware('checkIfAdmin');
Route::get('/admin/menu/categories', [\App\Http\Controllers\MenuEditorController::class, 'categories'])->name('admin.menu.categories')->middleware('checkIfAdmin');
Route::post('/admin/menu/categories/edit', [\App\Http\Controllers\MenuEditorController::class, 'editCategoryName'])->name('admin.menu.editCategory')->middleware('checkIfAdmin');
Route::get('/admin/requests', [\App\Http\Controllers\RequestsController::class, 'index'])->name('admin.requests')->middleware('checkIfAdmin');
Route::get('/admin/restaurants', [\App\Http\Controllers\RestaurantsInfoEditorController::class, 'index'])->name('admin.editRestaurants')->middleware('checkIfAdmin');
Route::post('/admin/restaurants/save', [\App\Http\Controllers\RestaurantsInfoEditorController::class, 'createNewRestaurant'])->name('admin.editRestaurants.save')->middleware('checkIfAdmin');
Route::post('/admin/restaurants/update', [\App\Http\Controllers\RestaurantsInfoEditorController::class, 'updateRestaurant'])->name('admin.editRestaurants.update')->middleware('checkIfAdmin');
Route::post('/admin/restaurants/delete', [\App\Http\Controllers\RestaurantsInfoEditorController::class, 'deleteRestaurant'])->name('admin.editRestaurants.delete')->middleware('checkIfAdmin');
Route::get('/order', [\App\Http\Controllers\OrderPlaceController::class, 'index'])->name('order');
Route::get('/order/places/{id}', [\App\Http\Controllers\OrderPlaceController::class, 'getPlaces'])->name('order.places');
Route::post('/order/places/order', [\App\Http\Controllers\OrderPlaceController::class, 'order'])->name('order.order');
Route::get('/order/places/checkAvailable/{}', [\App\Http\Controllers\OrderPlaceController::class, 'checkTimeSpace'])->name('order.available');
Route::post('/order/notify', [\App\Http\Controllers\MailController::class, 'index'])->name('order.notify');
Route::post('/banquet/notify', [\App\Http\Controllers\MailController::class, 'indexBanquet'])->name('banquet.notify');
Route::get('/banquet', [\App\Http\Controllers\BookBanquetController::class, 'index'])->name('banquet');
Route::post('/banquet/book', [\App\Http\Controllers\BookBanquetController::class, 'book'])->name('banquet.book');
Route::post('/banquet/totalSum', [\App\Http\Controllers\BookBanquetController::class, 'getTotalPrice'])->name('banquet.totalSum');
Route::post('/admin/requests/deleteBooking', [\App\Http\Controllers\RequestsController::class, 'delete'])->name('admin.request.deleteBooking')->middleware('checkIfAdmin');
Route::post('/admin/requests/deleteBanquet', [\App\Http\Controllers\RequestsController::class, 'deleteBanquet'])->name('admin.request.deleteBanquet')->middleware('checkIfAdmin');
