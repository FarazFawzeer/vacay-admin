<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehicleInvBooking;
use App\Models\Customer;
use App\Models\VehicleDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class VehicleInvBookingController extends Controller
{
    /**
     * List all vehicle bookings with filters
     */
    public function index(Request $request)
    {
        $query = VehicleInvBooking::with(['customer', 'vehicle']);

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

        // AJAX load only table
        if ($request->ajax()) {
            return view('bookings.vehicle.table', compact('bookings'))->render();
        }

        return view('bookings.vehicle.index', compact('bookings'));
    }

    /**
     * Create Booking Form
     */
    public function create()
    {
        $customers = Customer::all();
        $vehicles  = VehicleDetail::all();

        // Get last invoice number
        $lastInvoice = VehicleInvBooking::orderBy('id', 'desc')->first()?->invoice_no;

        if ($lastInvoice) {
            // Example: VB-0001 → increment
            $number = (int) str_replace('VB-', '', $lastInvoice) + 1;
            $nextInvoice = 'VB-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } else {
            $nextInvoice = 'VB-0001';
        }


        return view('bookings.vehicle.create', compact('customers', 'vehicles', 'nextInvoice'));
    }

    /**
     * Store new booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'        => 'required|exists:customers,id',
            'vehicle_id'         => 'required|exists:vehicle_details,id',
            'pickup_location'    => 'required|string',
            'pickup_datetime'    => 'required|date',
            'dropoff_location'   => 'required|string',
            'dropoff_datetime'   => 'required|date|after_or_equal:pickup_datetime',
            'price'              => 'required|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'advance_paid'       => 'nullable|numeric|min:0', // <-- added validation
            'currency'           => 'required|string|max:5',
            'status'             => 'required',
            'mileage'            => 'required|in:unlimited,limited',
            'total_km'           => 'required_if:mileage,limited|nullable|numeric|min:1',
            'note'               => 'nullable|string|max:1000', // optional note
        ]);

        // Calculate total
        $total = ($request->price + ($request->additional_charges ?? 0)) - ($request->discount ?? 0);

        // Generate Invoice Number
        $last = VehicleInvBooking::latest()->first();
        $next = 1;
        if ($last && preg_match('/VB-(\d+)/', $last->inv_no, $m)) {
            $next = intval($m[1]) + 1;
        }
        $invNo = 'VB-' . str_pad($next, 4, '0', STR_PAD_LEFT);

        // Store Booking
        VehicleInvBooking::create([
            'inv_no'             => $invNo,
            'customer_id'        => $request->customer_id,
            'vehicle_id'         => $request->vehicle_id,
            'pickup_location'    => $request->pickup_location,
            'pickup_datetime'    => $request->pickup_datetime,
            'dropoff_location'   => $request->dropoff_location,
            'dropoff_datetime'   => $request->dropoff_datetime,
            'mileage'            => $request->mileage,
            'total_km'           => $request->total_km,
            'price'              => $request->price,
            'additional_charges' => $request->additional_charges ?? 0,
            'discount'           => $request->discount ?? 0,
            'advance_paid'       => $request->advance_paid ?? 0, // <-- store advance paid
            'total_price'        => $total,
            'note'               => $request->note ?? '',       // <-- store note
            'currency'           => $request->currency,
            'status'             => $request->status,
            'payment_status'     => 'unpaid',
            'payment_method'     => $request->payment_method,
        ]);

        return redirect()->back()->with('success', 'Vehicle Booking saved successfully!');
    }



    /**
     * Edit Booking
     */
    public function edit(VehicleInvBooking $booking)
    {
        $customers = Customer::all();
        $vehicles  = VehicleDetail::all();

        return view('bookings.vehicle.edit', compact('booking', 'customers', 'vehicles'));
    }

    /**
     * Update Booking
     */
    public function update(Request $request, VehicleInvBooking $booking)
    {
        // Validate request
        $request->validate([
            'customer_id'        => 'required|exists:customers,id',
            'vehicle_id'         => 'required|exists:vehicle_details,id',
            'pickup_location'    => 'required|string',
            'pickup_datetime'    => 'required|date',
            'dropoff_location'   => 'required|string',
            'dropoff_datetime'   => 'required|date|after_or_equal:pickup_datetime',
            'price'              => 'required|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'currency'           => 'required|string|max:5',
            'status'             => 'required|in:quotation,invoice,confirmed,completed,cancelled',
            'payment_status'     => 'required|in:unpaid,partial,paid',
            'mileage'            => 'required|in:unlimited,limited',
            'total_km'           => 'required_if:mileage,limited|nullable|numeric|min:1',
        ]);

        // Recalculate total
        $total = ($request->price + ($request->additional_charges ?? 0)) - ($request->discount ?? 0);

        // Update booking
        $booking->update([
            'customer_id'        => $request->customer_id,
            'vehicle_id'         => $request->vehicle_id,
            'pickup_location'    => $request->pickup_location,
            'pickup_datetime'    => $request->pickup_datetime,
            'dropoff_location'   => $request->dropoff_location,
            'dropoff_datetime'   => $request->dropoff_datetime,
            'mileage'            => $request->mileage,
            'total_km'           => $request->total_km,
            'price'              => $request->price,
            'additional_charges' => $request->additional_charges ?? 0,
            'discount'           => $request->discount ?? 0,
            'total_price'        => $total,
            'note'               => $request->note,
            'currency'           => $request->currency,
            'status'             => $request->status,
            'payment_status'     => $request->payment_status,
            'payment_method'     => $request->payment_method,
        ]);

        return redirect()->route('admin.vehicle-bookings.edit', $booking->id)
            ->with('success', 'Vehicle Booking updated successfully!');
    }


    /**
     * View booking
     */
    public function show($id)
    {
        $booking = VehicleInvBooking::with(['customer', 'vehicle'])->findOrFail($id);
        return view('bookings.vehicle.show', compact('booking'));
    }

    /**
     * Update status only (AJAX)
     */
    public function updateStatus(Request $request, VehicleInvBooking $booking)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $booking->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        try {
            $booking = VehicleBooking::findOrFail($id);
            $booking->delete();

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Generate Invoice PDF from HTML
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
