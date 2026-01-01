<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TourBooking;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Agent;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


class TourQuotationController extends Controller
{

    public function index(Request $request)
    {
        $query = TourBooking::with(['customer', 'package', 'agent']);

        // 🔹 Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->booking_ref) {
            $query->where('booking_ref_no', 'like', '%' . $request->booking_ref . '%');
        }

        // 🔹 Order by published date (latest first)
        $bookings = $query
            ->orderByDesc('published_at') // 👈 key line
            ->paginate(10)
            ->appends($request->all());

        if ($request->ajax()) {
            return view('bookings.tour_table', compact('bookings'))->render();
        }

        return view('bookings.tour_view', compact('bookings'));
    }




    // Show Create Quotation Form
    public function create()
    {
        $customers = Customer::all();
        $packages  = Package::all();
        $agents    = Agent::where('status', 1)->orderBy('name')->get();

        return view('bookings.tour', compact('customers', 'packages', 'agents'));
    }
    // Store Quotation
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:packages,id',
            'agent_id'     => 'nullable|exists:agents,id',
            'published_at' => 'nullable|date',
            'travel_start_date' => 'required|date',
            'travel_end_date' => 'required|date',
            'adults' => 'required|integer|min:0',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'visit_country' => 'required|string|max:100',
            'package_price' => 'required|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:5',
            'status' => 'required',
            'advance_paid' => 'nullable|numeric|min:0',
        ]);

        // Calculate total
        $totalPrice = ($request->package_price + ($request->additional_charges ?? 0)) - ($request->discount ?? 0);


        // Generate invoice/quotation number
        $lastBooking = TourBooking::latest()->first();

        if ($lastBooking && preg_match('/BT-(\d+)/', $lastBooking->booking_ref_no, $matches)) {
            $lastNumber = (int)$matches[1];
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $bookingRefNo = 'BT-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $nextNumber = $lastBooking ? $lastBooking->invoice_number + 1 : 1;
        $invoiceNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $advancePaid = $request->advance_paid ?? 0;

        if ($advancePaid <= 0) {
            $paymentStatus = 'pending';
        } elseif ($advancePaid < $totalPrice) {
            $paymentStatus = 'partial';
        } else {
            $paymentStatus = 'paid';
        }


        // Create booking
        $booking = TourBooking::create([
            'package_id' => $request->package_id,
            'customer_id' => $request->customer_id,
            'agent_id' => $request->agent_id,
            'booking_ref_no' => $bookingRefNo,
            'travel_date' => $request->travel_start_date,
            'travel_end_date' => $request->travel_end_date,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'infants' => $request->infants ?? 0,
            'visit_country' => $request->visit_country,
            'package_price' => $request->package_price,
            'discount' => $request->discount ?? 0,
            'tax' => $request->additional_charges ?? 0,
            'total_price' => $totalPrice,
            'advance_paid' => $advancePaid,
            'amount_paid' => $advancePaid,
            'payment_status' => $paymentStatus,
            'currency' => $request->currency,
            'special_requirements' => $request->special_requirements,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => now(),
            'published_at' => $request->published_at ?? now()->toDateString(),
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);


        return redirect()->back()->with('success', ucfirst($request->status) . ' saved successfully!');
    }

    public function edit(TourBooking $booking)
    {
        $customers = Customer::all();
        $packages = Package::all();
        $agents = Agent::where('status', 1)->get(); // ✅ added

        return view('bookings.edit_tour', [
            'booking' => $booking,
            'customers' => $customers,
            'packages' => $packages,
            'agents' => $agents, // ✅ added
        ]);
    }


    public function update(Request $request, TourBooking $booking)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:packages,id',
            'agent_id' => 'nullable|exists:agents,id',     // ✅ added
            'published_at' => 'nullable|date',
            'travel_start_date' => 'required|date',
            'travel_end_date' => 'required|date|after_or_equal:travel_start_date',
            'adults' => 'required|integer|min:0',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'visit_country' => 'required|string|max:100',
            'package_price' => 'required|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:5',
            'status' => 'required',
            'advance_paid' => 'nullable|numeric|min:0',
            'special_requirements' => 'nullable|string|max:1000',
        ]);

        // Recalculate total price
        $totalPrice = ($request->package_price + ($request->additional_charges ?? 0)) - ($request->discount ?? 0);

        // Determine advance paid
        $advancePaid = $request->advance_paid ?? 0;

        // Determine payment status based on advance
        if ($advancePaid <= 0) {
            $paymentStatus = 'pending';
        } elseif ($advancePaid < $totalPrice) {
            $paymentStatus = 'partial';
        } else {
            $paymentStatus = 'paid';
        }

        // Update booking
        $booking->update([
            'customer_id' => $request->customer_id,
            'package_id' => $request->package_id,
            'agent_id' => $request->agent_id, // ✅ added
            'travel_date' => $request->travel_start_date,
            'travel_end_date' => $request->travel_end_date,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'infants' => $request->infants ?? 0,
            'visit_country' => $request->visit_country,
            'package_price' => $request->package_price,
            'tax' => $request->additional_charges ?? 0,
            'discount' => $request->discount ?? 0,
            'total_price' => $totalPrice,
            'advance_paid' => $advancePaid,
            'amount_paid' => $advancePaid, // same as store logic
            'payment_status' => $paymentStatus,
            'currency' => $request->currency,
            'status' => $request->status,
            'special_requirements' => $request->special_requirements,
            'published_at' => $request->published_at,
            'updated_by' => auth()->id(), // optional
        ]);

        return redirect()->route('admin.tour-bookings.edit', $booking)
            ->with('success', ucfirst($request->status) . ' updated successfully!');
    }



    public function show($id)
    {
        $booking = TourBooking::with(['customer', 'package'])->findOrFail($id);
        return view('bookings.tour_show', compact('booking'));
    }


    public function updateStatus(Request $request, TourBooking $booking)
    {
        $request->validate(['status' => 'required']);
        $booking->status = $request->status;
        $booking->save();

        return response()->json(['success' => true]);
    }


    public function generatePdf(Request $request)
    {
        try {
            $content = $request->input('html');

            // Replace asset() URLs with actual local file paths
            $content = str_replace(
                asset('images/vacayguider.png'),
                public_path('images/vacayguider.png'),
                $content
            );

            $html = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <style>
@page {
    margin: 10mm; /* Increased slightly for safety */
}

body {
    margin: 0;
    padding: 0;
}

.invoice-wrapper {
    width: 100%;
    /* Remove horizontal padding here if it causes overflow */
    padding-top: 2mm;
    padding-bottom: 2mm;
}

.invoice-container {
    border: 2px solid #333;
    padding: 10px;
    
    /* CRITICAL FIXES */
    width: 98%;           /* Give it a tiny bit of "breathing room" */
    margin: 0 auto;       /* Center it so both sides are safe */
    box-sizing: border-box; 
    overflow: hidden;     /* Prevents internal content from stretching the box */
}

            </style>
        </head>
        <body>
          <div class="invoice-wrapper">
    <div class="invoice-container">
                ' . $content . '
            </div>
            </div>
        </body>
        </html>';

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
                ->setPaper('A4', 'portrait')
                ->set_option('isHtml5ParserEnabled', true)
                ->set_option('isRemoteEnabled', true);

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Tour_Invoice.pdf"'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'PDF generation failed: ' . $e->getMessage()], 500);
        }
    }
}
