<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TourBooking;
use App\Models\Customer;
use App\Models\Package;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


class TourQuotationController extends Controller
{

    public function index(Request $request)
    {
        $query = TourBooking::with(['customer', 'package']);

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

        // 🔹 Pagination with filters
        $bookings = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->all());

        // 🔹 AJAX: return table only
        if ($request->ajax()) {
            return view('bookings.tour_table', compact('bookings'))->render();
        }

        // 🔹 Get filter options for the view
        return view('bookings.tour_view', compact('bookings'));
    }



    // Show Create Quotation Form
    public function create()
    {
        $customers = Customer::all();
        $packages = Package::all();

        return view('bookings.tour', compact('customers', 'packages'));
    }

    // Store Quotation
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:packages,id',
            'travel_start_date' => 'required|date',
            'travel_end_date' => 'required|date',
            'adults' => 'required|integer|min:0',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'package_price' => 'required|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:5',
            'status' => 'required|in:quotation,invoiced,confirmed,completed,cancelled',
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

        // Create booking
        $booking = TourBooking::create([
            'package_id' => $request->package_id,
            'customer_id' => $request->customer_id,
            'booking_ref_no' => $bookingRefNo,
            'travel_date' => $request->travel_start_date,
            'travel_end_date' => $request->travel_end_date,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'infants' => $request->infants ?? 0,
            'package_price' => $request->package_price,
            'discount' => $request->discount ?? 0,
            'tax' => $request->additional_charges ?? 0, // stored in tax column as additional charges
            'total_price' => $totalPrice,
            'currency' => $request->currency,
            'special_requirements' => $request->special_requirements,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => now(),
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', ucfirst($request->status) . ' saved successfully!');
    }

    public function edit(TourBooking $booking)
    {
        $customers = Customer::all();
        $packages = Package::all();

        return view('bookings.edit_tour', [
            'booking' => $booking,
            'customers' => $customers,
            'packages' => $packages,
        ]);
    }


    public function update(Request $request, TourBooking $booking)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:packages,id',
            'travel_start_date' => 'required|date',
            'travel_end_date' => 'required|date|after_or_equal:travel_start_date',
            'adults' => 'required|integer|min:0',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'package_price' => 'required|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:5',
            'status' => 'required|in:quotation,invoiced,confirmed,completed,cancelled',
            'payment_status' => 'required|in:pending,partial,paid',
            'special_requirements' => 'nullable|string|max:1000',
        ]);

        // Recalculate total price
        $totalPrice = ($request->package_price + ($request->additional_charges ?? 0)) - ($request->discount ?? 0);

        $booking->update([
            'customer_id' => $request->customer_id,
            'package_id' => $request->package_id,
            'travel_date' => $request->travel_start_date,
            'travel_end_date' => $request->travel_end_date,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'infants' => $request->infants ?? 0,
            'package_price' => $request->package_price,
            'tax' => $request->additional_charges ?? 0, // stored in tax column
            'discount' => $request->discount ?? 0,
            'total_price' => $totalPrice,
            'currency' => $request->currency,
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'special_requirements' => $request->special_requirements,
            // Optionally, you can track who updated it
            //'updated_by' => auth()->id(),
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
        $request->validate(['status' => 'required|in:quotation,invoiced,confirmed,completed,cancelled']);
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
    margin: 2mm 2mm; /* top/bottom, left/right */
}
body {
    margin: 0;
    padding: 0;
}
.invoice-container {
    padding: 1mm 1mm; /* inner padding for layout */
}
</style>
        </head>
        <body>
            <div class="invoice-container">
                ' . $content . '
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
