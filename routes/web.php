<?php

use App\Http\Controllers\RoutingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\DestinationHighlightController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\PackageController;

require __DIR__ . '/auth.php';

Route::prefix('admin')->name('admin.')->group(function () {

    //admin
    Route::resource('users', UserController::class);

    //customer
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy'); // Delete customer

    //destination
    Route::resource('destinations', DestinationController::class);

    //tour packages
    Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
    Route::post('packages/store', [PackageController::class, 'store'])
        ->name('packages.store');
    Route::get('packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('packages/{id}/edit', [PackageController::class, 'edit'])->name('packages.edit');
    Route::put('packages/{id}/update', [PackageController::class, 'update'])->name('packages.update');

    Route::post('packages/status/{package}', [PackageController::class, 'toggleStatus'])->name('admin.packages.toggleStatus');
    Route::get('packages/{id}', [PackageController::class, 'show'])->name('packages.show');
Route::get('/package/{id}/pdf', [PackageController::class, 'downloadPackagePdf'])->name('package.pdf');



    //destination highlights
    Route::resource('destination-highlights', DestinationHighlightController::class)->only([
        'index',
        'store',
        'update',
        'destroy'
    ]);

    //hotels
    Route::resource('hotels', HotelController::class);

    Route::resource('vehicles', VehicleController::class);
    Route::patch('vehicles/{vehicle}/toggle-status', [VehicleController::class, 'toggleStatus']);


    Route::get('/destinations/{id}/details', [DestinationController::class, 'getDetails']);


    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});



Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});


Route::get('/login', function () {
    return view('auth.signin');
})->name('login');

// Login action
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('index'); // create resources/views/dashboard.blade.php
    });
});
