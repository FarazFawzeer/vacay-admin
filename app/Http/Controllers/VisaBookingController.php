<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VisaBooking;
use App\Models\Customer;
use App\Models\Passport;
use App\Models\Visa;
use App\Models\VisaCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class VisaBookingController extends Controller
{
    /**
     * List all visa bookings with filters
     */
    public function index(Request $request)
    {
        $query = VisaBooking::with([
            'passport',
            'visa',
            'visaCategory',
            'agent'
        ]);

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Invoice number filter (FIXED)
        if ($request->filled('inv_no')) {
            $query->where('invoice_no', 'like', '%' . $request->inv_no . '%');
        }

        $bookings = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->all());

        // AJAX request
        if ($request->ajax()) {
            return view('bookings.visa.table', compact('bookings'))->render();
        }

        return view('bookings.visa.index', compact('bookings'));
    }


    /**
     * Create booking form
     */

    public function create()
    {
        // Fetch passports instead of customers
        $passports = Passport::with('customer')->get();

        // Fetch visas
        $visas = Visa::with('categories')->get();

        // Optional: Invoice number logic (keep only if you still use inv_no)
        $lastInvoice = VisaBooking::orderBy('id', 'desc')->first()?->inv_no;

        if ($lastInvoice && preg_match('/VB-(\d+)/', $lastInvoice, $m)) {
            $number = intval($m[1]) + 1;
            $nextInvoice = 'VB-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } else {
            $nextInvoice = 'VB-0001';
        }

        return view('bookings.visa.create', compact(
            'passports',
            'visas',
            'nextInvoice'
        ));
    }

    public function getVisasByCountry(Request $request)
    {
        $request->validate([
            'from_country' => 'required',
            'to_country'   => 'required',
        ]);

        $visas = Visa::where('from_country', $request->from_country)
            ->where('to_country', $request->to_country)
            ->select('id', 'visa_type')
            ->get();

        return response()->json($visas);
    }

    public function getVisaCategories(Visa $visa)
    {
        return response()->json(
            $visa->categories()->get()
        );
    }


    /**
     * Store new booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'passport_id'        => 'required|exists:passports,id',
            'visa_id'            => 'required|exists:visas,id',
            'visa_category_id'   => 'required|exists:visa_categories,id',

            'currency'           => 'required|string|max:10',
            'base_price'         => 'required|numeric|min:0',
            'additional_price'   => 'nullable|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'total_amount'       => 'required|numeric|min:0',
            'advanced_paid'      => 'nullable|numeric|min:0',
            'balance'            => 'required|numeric|min:0',

            'status'             => 'required',
            'payment_status'     => 'required',
        ]);

        // Ensure the selected category belongs to the selected visa
        $category = VisaCategory::where('id', $request->visa_category_id)
            ->where('visa_id', $request->visa_id)
            ->firstOrFail();

        // Generate Invoice No
        $lastInvoice = VisaBooking::orderBy('id', 'desc')->first()?->invoice_no;

        if ($lastInvoice && preg_match('/VB(\d+)/', $lastInvoice, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
            $invoiceNo = 'VB' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        } else {
            $invoiceNo = 'VB0001';
        }

        // Create Visa Booking
        $booking = VisaBooking::create([
            'invoice_no'         => $invoiceNo,
            'passport_id'        => $request->passport_id,
            'visa_id'            => $request->visa_id,
            'visa_category_id'   => $request->visa_category_id,

            'currency'           => $request->currency,
            'base_price'         => $request->base_price,
            'additional_price'   => $request->additional_price ?? 0,
            'discount'           => $request->discount ?? 0,
            'total_amount'       => $request->total_amount,
            'advanced_paid'      => $request->advanced_paid ?? 0,
            'balance'            => $request->balance,

            'status'             => $request->status,
            'payment_status'     => $request->payment_status,

            'created_by'         => Auth::id(),
        ]);

        return redirect()
            ->route('admin.visa-bookings.index')
            ->with('success', "Visa booking created successfully! Invoice No: {$invoiceNo}");
    }

    /**
     * Show booking
     */
    public function show($id)
    {
        $booking = VisaBooking::with([
            'customer',        // Customer info
            'passport',        // Passport info
            'visa',            // Visa info
            'visaCategory'     // Visa category info
        ])->findOrFail($id);

        return view('bookings.visa.show', compact('booking'));
    }


    /**
     * Edit booking
     */
    public function edit(VisaBooking $booking)
    {
        $booking->load([
            'passport.customer',
            'visa',
            'visaCategory'
        ]);

        $passports = Passport::with('customer')->get();

        // Needed only for country route dropdown
        $visas = Visa::select('from_country', 'to_country')
            ->groupBy('from_country', 'to_country')
            ->get();

        return view('bookings.visa.edit', compact(
            'booking',
            'passports',
            'visas'
        ));
    }

    /**
     * Update booking
     */
    public function update(Request $request, VisaBooking $booking)
    {
        $request->validate([
            'passport_id'        => 'required|exists:passports,id',
            'visa_id'            => 'required|exists:visas,id',
            'visa_category_id'   => 'required|exists:visa_categories,id',

            'currency'           => 'required|string|max:10',
            'base_price'         => 'required|numeric|min:0',
            'additional_price'   => 'nullable|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'total_amount'       => 'required|numeric|min:0',
            'advanced_paid'      => 'nullable|numeric|min:0',
            'balance'            => 'required|numeric|min:0',

            'status'             => 'required',
            'payment_status'     => 'required',
        ]);

        // Ensure the selected category belongs to the selected visa
        $category = VisaCategory::where('id', $request->visa_category_id)
            ->where('visa_id', $request->visa_id)
            ->firstOrFail();

        // Update Visa Booking
        $booking->update([
            'passport_id'        => $request->passport_id,
            'visa_id'            => $request->visa_id,
            'visa_category_id'   => $request->visa_category_id,

            'currency'           => $request->currency,
            'base_price'         => $request->base_price,
            'additional_price'   => $request->additional_price ?? 0,
            'discount'           => $request->discount ?? 0,
            'total_amount'       => $request->total_amount,
            'advanced_paid'      => $request->advanced_paid ?? 0,
            'balance'            => $request->balance,

            'status'             => $request->status,
            'payment_status'     => $request->payment_status,

            'updated_by'         => Auth::id(),
        ]);

        return redirect()
            ->route('admin.visa-bookings.edit', $booking->id)
            ->with('success', 'Visa booking updated successfully!');
    }



    /**
     * Delete booking
     */
    public function destroy($id)
    {
        try {
            $booking = VisaBooking::findOrFail($id);
            $booking->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $booking = VisaBooking::findOrFail($id);
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

            // 1️⃣ Convert company logo to Base64
            $logoPath = public_path('images/vacayguider.png');
            if (file_exists($logoPath)) {
                $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                $logoData = file_get_contents($logoPath);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($logoData);
                $content = str_replace(
                    '{{ asset(\'images/vacayguider.png\') }}',
                    $logoBase64,
                    $content
                );
            }

            // 2️⃣ Fix all other <img> src paths
            $content = preg_replace_callback('/<img[^>]+src="([^"]+)"/', function ($matches) {
                $src = $matches[1];

                // Skip if already base64
                if (str_starts_with($src, 'data:image')) {
                    return $matches[0];
                }

                // Try converting relative/public path to file://
                $path = public_path(ltrim(str_replace(url('/'), '', $src), '/'));
                if (file_exists($path)) {
                    return str_replace($src, 'file://' . $path, $matches[0]);
                }

                // Otherwise leave unchanged
                return $matches[0];
            }, $content);

            // 3️⃣ Wrap content in proper HTML for DomPDF
            $html = '<html>
                    <head>
                        <meta charset="utf-8">
                        <style>
                            body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
                            table { border-collapse: collapse; }
                        </style>
                    </head>
                    <body>' . $content . '</body>
                </html>';

            // 4️⃣ Generate PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('A4', 'portrait');

            // 5️⃣ Return as download
            return $pdf->download("visa-booking.pdf");
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
