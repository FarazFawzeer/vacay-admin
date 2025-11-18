<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PackageBooking;
use App\Models\CustomTourRequest;
use App\Models\VehicleBooking;
use App\Models\TransportationBooking;
use App\Models\AirlineBooking;
use App\Models\DrivingPermitRequest;
use Illuminate\Http\Request;
use App\Models\ContactInfor;

class EnquiryController extends Controller
{
    public function tours(Request $request)
    {
        $query = PackageBooking::query();

        // search filter
        if ($request->has('name') && $request->name != '') {
            $query->where('full_name', 'like', '%' . $request->name . '%');
        }

        $bookings = $query->orderBy('id', 'desc')->paginate(10);

        // for AJAX calls return the table only
        if ($request->ajax()) {
            return view('enquiry.tours', compact('bookings'))->render();
        }

        return view('enquiry.tours', compact('bookings'));
    }


    public function enquiryupdateStatus(Request $request, $id)
    {
        $booking = PackageBooking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json(['success' => true]);
    }


    public function customerTour(Request $request)
    {
        $query = CustomTourRequest::query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $requests = $query->orderBy('id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('enquiry.custom-tour-table', compact('requests'))->render();
        }

        return view('enquiry.custom-tour', compact('requests'));
    }

    public function updateCustomTourStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|string|in:pending,viewed,completed'
        ]);

        $req = CustomTourRequest::find($request->id);
        if (!$req) {
            return response()->json(['success' => false, 'message' => 'Not found']);
        }

        $req->status = $request->status;
        $req->save();

        return response()->json(['success' => true]);
    }

    public function rentVehicle(Request $request)
    {
        $query = VehicleBooking::with('vehicle');

        if ($request->name) {
            $query->where('full_name', 'like', '%' . $request->name . '%');
        }

        $bookings = $query->orderBy('id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('enquiry.rent-table', compact('bookings'))->render();
        }

        return view('enquiry.rent', compact('bookings'));
    }

    public function updateRentBookingStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|string|in:pending,viewed,completed'
        ]);

        $booking = VehicleBooking::find($request->id);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Not found']);
        }

        $booking->status = $request->status;
        $booking->save();

        return response()->json(['success' => true]);
    }

    public function transport(Request $request)
    {
        $query = TransportationBooking::with('vehicle'); // eager load vehicle

        // Search by full name
        if ($request->has('name') && $request->name != '') {
            $query->where('full_name', 'like', '%' . $request->name . '%');
        }

        // Order by latest
        $bookings = $query->orderBy('id', 'desc')->paginate(10);

        // If AJAX request, return only the table partial
        if ($request->ajax()) {
            return view('enquiry.transport-table', compact('bookings'))->render();
        }

        // Return full page for normal requests
        return view('enquiry.transport', compact('bookings'));
    }

    public function transportUpdateStatus(Request $request)
    {
        $booking = TransportationBooking::findOrFail($request->id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json(['success' => true]);
    }


    public function airTicket(Request $request)
    {

        $query = AirlineBooking::query();

        if ($request->name) {
            $query->where('full_name', 'like', '%' . $request->name . '%');
        }

        $bookings = $query->orderBy('id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('enquiry.air-ticket-table', compact('bookings'))->render();
        }

        return view('enquiry.air-ticket', compact('bookings'));
    }

    public function airTicketUpdateStatus(Request $request)
    {
        $booking = AirlineBooking::findOrFail($request->id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json(['success' => true]);
    }

    public function drivingPermit(Request $request)
    {
        $query = DrivingPermitRequest::query();

        if ($request->name) {
            $query->where('guest_name', 'like', '%' . $request->name . '%');
        }

        $requests = $query->orderBy('id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('enquiry.driving-permit-table', compact('requests'))->render();
        }

        return view('enquiry.driving-permits', compact('requests'));
    }

    /**
     * Update status of a Driving Permit Request
     */
    public function drivingPermitupdateStatus(Request $request)
    {
        $requestEntry = DrivingPermitRequest::findOrFail($request->id);
        $requestEntry->status = $request->status;
        $requestEntry->save();

        return response()->json(['success' => true]);
    }

public function contactInfor(Request $request)
{
    $query = ContactInfor::query();

    if($request->name) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    $contacts = $query->orderBy('id','desc')->paginate(10);

    if($request->ajax()){
        return view('enquiry.contact-infor-table', compact('contacts'))->render();
    }

    return view('enquiry.contact-infor', compact('contacts'));
}

public function contactInforUpdateStatus(Request $request)
{
    $contact = ContactInfor::findOrFail($request->id);
    $contact->status = $request->status;
    $contact->save();

    return response()->json(['success' => true]);
}
}
