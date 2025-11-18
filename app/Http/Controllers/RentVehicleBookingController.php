<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RentVehicleBooking;
use App\Models\Customer;
use App\Models\VehicleDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class RentVehicleBookingController extends Controller
{
    /**
     * List all rent vehicle bookings with filters
     */
    public function index(Request $request)
    {
        $query = RentVehicleBooking::with(['customer', 'vehicle']);

        // Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->inv_no) {
            $query->where('inv_no', 'like', '%' . $request->inv_no . '%');
        }

        $bookings = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->all());

        if ($request->ajax()) {
            return view('bookings.rent.table', compact('bookings'))->render();
        }

        return view('bookings.rent.index', compact('bookings'));
    }

    /**
     * Create booking form
     */
    public function create()
    {
        $customers = Customer::all();
        $vehicles  = VehicleDetail::all();

        $lastInvoice = RentVehicleBooking::orderBy('id', 'desc')->first()?->inv_no;

        if ($lastInvoice && preg_match('/RV-(\d+)/', $lastInvoice, $m)) {
            $number = intval($m[1]) + 1;
            $nextInvoice = 'RV-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } else {
            $nextInvoice = 'RV-0001';
        }

        return view('bookings.rent.create', compact('customers', 'vehicles', 'nextInvoice'));
    }

    /**
     * Store new booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'vehicle_id'      => 'required|exists:vehicle_details,id',
            'start_datetime'  => 'required|date',
            'end_datetime'    => 'required|date|after_or_equal:start_datetime',
            'price'           => 'required|numeric|min:0',
            'additional_price' => 'nullable|numeric|min:0',
            'discount'        => 'nullable|numeric|min:0',
            'tax'             => 'nullable|numeric|min:0',
            'currency'        => 'required|string|max:5',
            'status'          => 'required',
            'payment_status'  => 'required',
        ]);

        $total = $request->price + ($request->additional_price ?? 0) + ($request->tax ?? 0) - ($request->discount ?? 0);

        $last = RentVehicleBooking::latest()->first();
        $invNo = 'RV-' . str_pad(($last?->inv_no && preg_match('/RV-(\d+)/', $last->inv_no, $m) ? intval($m[1]) + 1 : 1), 4, '0', STR_PAD_LEFT);

        RentVehicleBooking::create([
            'inv_no'         => $invNo,
            'customer_id'    => $request->customer_id,
            'vehicle_id'     => $request->vehicle_id,
            'start_datetime' => $request->start_datetime,
            'end_datetime'   => $request->end_datetime,
            'price'          => $request->price,
            'additional_price' => $request->additional_price ?? 0,
            'discount'       => $request->discount ?? 0,
            'tax'            => $request->tax ?? 0,
            'total_price'    => $total,
            'notes'          => $request->notes,
            'currency'       => $request->currency,
            'status'         => $request->status,
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'created_by'     => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Rent Vehicle Booking saved successfully!');
    }

    /**
     * Show booking
     */
    public function show($id)
    {
        $booking = RentVehicleBooking::with(['customer', 'vehicle'])->findOrFail($id);
        return view('bookings.rent.show', compact('booking'));
    }

    /**
     * Edit booking
     */
    public function edit(RentVehicleBooking $booking)
    {
        $customers = Customer::all();
        $vehicles  = VehicleDetail::all();

        return view('bookings.rent.edit', compact('booking', 'customers', 'vehicles'));
    }

    /**
     * Update booking
     */
    public function update(Request $request, RentVehicleBooking $booking)
    {
        $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'vehicle_id'      => 'required|exists:vehicle_details,id',
            'start_datetime'  => 'required|date',
            'end_datetime'    => 'required|date|after_or_equal:start_datetime',
            'price'           => 'required|numeric|min:0',
            'additional_price' => 'nullable|numeric|min:0',
            'discount'        => 'nullable|numeric|min:0',
            'tax'             => 'nullable|numeric|min:0',
            'currency'        => 'required|string|max:5',
            'status'          => 'required',
            'payment_status'  => 'required',
        ]);

        $total = $request->price + ($request->additional_price ?? 0) + ($request->tax ?? 0) - ($request->discount ?? 0);

        $booking->update([
            'customer_id'     => $request->customer_id,
            'vehicle_id'      => $request->vehicle_id,
            'start_datetime'  => $request->start_datetime,
            'end_datetime'    => $request->end_datetime,
            'price'           => $request->price,
            'additional_price' => $request->additional_price ?? 0,
            'discount'        => $request->discount ?? 0,
            'tax'             => $request->tax ?? 0,
            'total_price'     => $total,
            'notes'           => $request->notes,
            'currency'        => $request->currency,
            'status'          => $request->status,
            'payment_status'  => $request->payment_status,
            'payment_method'  => $request->payment_method,
        ]);

        return redirect()->route('admin.rent-vehicle-bookings.edit', $booking->id)
            ->with('success', 'Rent Vehicle Booking updated successfully!');
    }

    /**
     * Delete booking
     */
    public function destroy($id)
    {
        try {
            $booking = RentVehicleBooking::findOrFail($id);
            $booking->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:quotation,invoice,confirmed,completed,cancelled',
        ]);

        $booking = RentVehicleBooking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status'  => $booking->status,
        ]);
    }


    /**
     * Generate PDF of booking
     */
    public function generatePdf(Request $request)
    {
        try {
            $content = $request->input('html');

            // Fix <img> paths so DomPDF can load them
            $content = preg_replace_callback('/src="([^"]+)"/', function ($m) {
                $src = $m[1];

                if (str_starts_with($src, 'data:image')) return 'src="' . $src . '"';

                if (preg_match('#/storage/(.+)$#', $src, $mm)) {
                    $path = public_path('storage/' . $mm[1]);
                    if (file_exists($path)) return 'src="file://' . $path . '"';
                }

                if (str_starts_with($src, '/storage/')) {
                    $path = public_path(ltrim($src, '/'));
                    if (file_exists($path)) return 'src="file://' . $path . '"';
                }

                if (str_contains($src, 'vacayguider.png')) {
                    return 'src="file://' . public_path('images/vacayguider.png') . '"';
                }

                return 'src="' . $src . '"';
            }, $content);

            // PDF HTML Wrapper with improved styles
            $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
            @page {
    margin: 2mm 2mm; /* top/bottom, left/right */
}
body {
    margin: 0;
    padding: 0;
}
.bookingPreviewBody {
    padding: 1mm 1mm; /* inner padding for layout */
}
                body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
                h2, h3 { margin: 0; }
                h2 { font-size: 20px; }
                h3 { font-size: 14px; font-weight: bold; }
                p { margin: 5px 0; }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; color: #fff; font-size: 10px; font-weight: bold; }

                th { background: #333; color: #fff; text-align: left; }
                .total-row td { font-weight: bold; background: #f0f0f0; }
                .vehicle-images img { margin-right: 5px; border-radius: 3px; border: 1px solid #ddd; }
                .section { margin-bottom: 25px; }
                .notes { padding: 10px 15px; background: #fffbea; border-left: 4px solid #ffc107; margin-bottom: 25px; }
                .company-info p { font-size: 11px; color: #666; margin: 2px 0; }
                img { max-width: 100%; height: auto; }
            </style>
        </head>
        <body>
            ' . $content . '
        </body>
        </html>';

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('A4', 'portrait');

            return $pdf->download("vehicle-booking.pdf");
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
