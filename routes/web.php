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
use App\Http\Controllers\VisaController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\InclusionController;
use App\Http\Controllers\TourQuotationController;
use App\Http\Controllers\VehicleInvBookingController;
use App\Http\Controllers\RentVehicleBookingController;
use App\Http\Controllers\VisaBookingController;
use App\Http\Controllers\EnquiryController;

require __DIR__ . '/auth.php';

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    //admin
    Route::resource('users', UserController::class);

    //customer
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
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


    Route::get('tour-bookings', [TourQuotationController::class, 'index'])->name('tour-bookings.index');
    Route::get('tour-bookings/{booking}', [TourQuotationController::class, 'show'])->name('tour-bookings.show');
    Route::get('tour-bookings/{booking}/edit', [TourQuotationController::class, 'edit'])->name('tour-bookings.edit');
    Route::put('tour-bookings/{booking}', [TourQuotationController::class, 'update'])->name('tour-bookings.update');
    Route::delete('tour-bookings/{booking}', [TourQuotationController::class, 'destroy'])->name('tour-bookings.destroy');
    Route::get('/tour-quotations/create', [TourQuotationController::class, 'create'])->name('tour-quotations.create');
    Route::post('/tour-quotations/store', [TourQuotationController::class, 'store'])->name('tour-quotations.store');
    Route::post('/tour-bookings/{booking}/status', [TourQuotationController::class, 'updateStatus'])->name('tour-bookings.updateStatus');
    Route::post('/tour-quotations/generate-pdf', [TourQuotationController::class, 'generatePdf'])->name('tour-quotations.generatePdf');


    //Vehicle INV bookings

    Route::get('vehicle-bookings', [VehicleInvBookingController::class, 'index'])->name('vehicle-bookings.index');
    Route::get('vehicle-bookings/show/{booking}', [VehicleInvBookingController::class, 'show'])->name('vehicle-bookings.show');
    Route::get('vehicle-bookings/{booking}/edit', [VehicleInvBookingController::class, 'edit'])->name('vehicle-bookings.edit');
    Route::put('vehicle-bookings/{booking}', [VehicleInvBookingController::class, 'update'])->name('vehicle-bookings.update');
    Route::delete('vehicle-bookings/{booking}', [VehicleInvBookingController::class, 'destroy'])->name('vehicle-bookings.destroy');
    Route::get('vehicle-inv-bookings/create', [VehicleInvBookingController::class, 'create'])->name('vehicle-inv-bookings.create');
    Route::post('vehicle-inv-bookings/store', [VehicleInvBookingController::class, 'store'])->name('vehicle-inv-bookings.store');
    Route::post('vehicle-bookings/{booking}/status', [VehicleInvBookingController::class, 'updateStatus'])->name('vehicle-bookings.updateStatus');
    Route::post('vehicle-bookings/generate-pdf', [VehicleInvBookingController::class, 'generatePdf'])->name('vehicle-bookings.generatePdf');


    //rent vehicle bookings
    Route::get('rent-vehicle-bookings', [RentVehicleBookingController::class, 'index'])->name('rent-vehicle-bookings.index');
    Route::get('rent-vehicle-bookings/{booking}', [RentVehicleBookingController::class, 'show'])->name('rent-vehicle-bookings.show');
    Route::get('rent-vehicle-bookings/{booking}/edit', [RentVehicleBookingController::class, 'edit'])->name('rent-vehicle-bookings.edit');
    Route::put('rent-vehicle-bookings/{booking}', [RentVehicleBookingController::class, 'update'])->name('rent-vehicle-bookings.update');
    Route::delete('rent-vehicle-bookings/{booking}', [RentVehicleBookingController::class, 'destroy'])->name('rent-vehicle-bookings.destroy');
    Route::get('rent-vehicle-bookings/rent/create', [RentVehicleBookingController::class, 'create'])->name('rent-vehicle-bookings.create');
    Route::post('rent-vehicle-bookings/store', [RentVehicleBookingController::class, 'store'])->name('rent-vehicle-bookings.store');
    Route::post('rent-vehicle-bookings/{booking}/status', [RentVehicleBookingController::class, 'updateStatus'])->name('rent-vehicle-bookings.updateStatus');
    Route::post('rent-vehicle-bookings/generate-pdf', [RentVehicleBookingController::class, 'generatePdf'])->name('rent-vehicle-bookings.generatePdf');


    // Visa bookings
    Route::get('visa-bookings', [VisaBookingController::class, 'index'])->name('visa-bookings.index');
    Route::get('visa-bookings/{booking}', [VisaBookingController::class, 'show'])->name('visa-bookings.show');
    Route::get('visa-bookings/{booking}/edit', [VisaBookingController::class, 'edit'])->name('visa-bookings.edit');
    Route::put('visa-bookings/{booking}', [VisaBookingController::class, 'update'])->name('visa-bookings.update');
    Route::delete('visa-bookings/{booking}', [VisaBookingController::class, 'destroy'])->name('visa-bookings.destroy');
    Route::get('visa-bookings/visa/create', [VisaBookingController::class, 'create'])->name('visa-bookings.create');
    Route::post('visa-bookings/store', [VisaBookingController::class, 'store'])->name('visa-bookings.store');
    Route::post('visa-bookings/{booking}/status', [VisaBookingController::class, 'updateStatus'])->name('visa-bookings.updateStatus');
    Route::post('visa-bookings/generate-pdf', [VisaBookingController::class, 'generatePdf'])->name('visa-bookings.generatePdf');


    Route::prefix('enquiry')->name('enquiry.')->group(function () {
        Route::get('tours', [EnquiryController::class, 'tours'])->name('tours');
        Route::post('{id}/status', [EnquiryController::class, 'enquiryupdateStatus'])->name('enquiryupdateStatus');
        Route::get('customer-tour', [EnquiryController::class, 'customerTour'])->name('customTour');
        Route::post('/custom-tour/update-status', [EnquiryController::class, 'updateCustomTourStatus'])->name('customTour.updateStatus');
        Route::get('rent-vehicle', [EnquiryController::class, 'rentVehicle'])->name('rentVehicle');
        Route::post('/vehicle-bookings/update-status', [EnquiryController::class, 'updateRentBookingStatus'])->name('vehicle.updateStatus');
        Route::get('transport', [EnquiryController::class, 'transport'])->name('transport');
        Route::post('transport/update-status', [EnquiryController::class, 'transportUpdateStatus'])->name('transport.updateStatus');
        Route::get('air-ticket', [EnquiryController::class, 'airTicket'])->name('airTicket');
        Route::post('air-ticket/update-status', [EnquiryController::class, 'airTicketUpdateStatus'])->name('airTicket.updateStatus');
        Route::get('driving-permit', [EnquiryController::class, 'drivingPermit'])->name('drivingPermit');
        Route::post('driving-permits/update-status', [EnquiryController::class, 'drivingPermitupdateStatus'])->name('drivingPermits.updateStatus');
        Route::get('contact-us', [EnquiryController::class, 'contactInfor'])->name('contactUs');
        Route::post('contact-us/update-status', [EnquiryController::class, 'contactInforUpdateStatus'])->name('contactUs.updateStatus');
        Route::get('chatbot', [EnquiryController::class, 'chatbot'])->name('chatbot');
        Route::post('chatbot/update-status', [EnquiryController::class, 'chatbotUpdateStatus'])->name('chatbot.updateStatus');
    });
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


    Route::resource('inclusions', InclusionController::class);


    Route::resource('visa', VisaController::class);


    Route::get('/destinations/{id}/details', [DestinationController::class, 'getDetails']);


    Route::get('blogs', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::post('blogs/store', [BlogController::class, 'store'])->name('blogs.store');

    Route::get('blogs/{blog}', [BlogController::class, 'show'])->name('blogs.show');
    Route::get('blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');

    Route::post('blogs/status/{blog}', [BlogController::class, 'toggleStatus'])->name('blogs.status');


    Route::resource('testimonials', TestimonialController::class);
    Route::post('testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])
        ->name('testimonials.toggleStatus');
    Route::post('testimonials/toggle-status/{blog}', [TestimonialController::class, 'toggleStatus'])->name('blogs.status');

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
