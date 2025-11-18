<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VisaBooking;
use App\Models\Customer;
use App\Models\Visa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class VisaBookingController extends Controller
{
    /**
     * List all visa bookings with filters
     */
    public function index(Request $request)
    {
        $query = VisaBooking::with(['customer', 'visa']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->inv_no) {
            $query->where('inv_no', 'like', '%' . $request->inv_no . '%');
        }

        $bookings = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->all());

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
        $customers = Customer::all();
        $visas     = Visa::all();

        $lastInvoice = VisaBooking::orderBy('id', 'desc')->first()?->inv_no;
        if ($lastInvoice && preg_match('/VB-(\d+)/', $lastInvoice, $m)) {
            $number = intval($m[1]) + 1;
            $nextInvoice = 'VB-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } else {
            $nextInvoice = 'VB-0001';
        }

        return view('bookings.visa.create', compact('customers', 'visas', 'nextInvoice'));
    }

    /**
     * Store new booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'      => 'required|exists:customers,id',
            'visa_id'          => 'required|exists:visa,id',
            'passport_number'  => 'required|string|max:50',
            'type'             => 'required|string|max:50',
            'agent'            => 'nullable|string|max:100',
            'visa_issue_date'  => 'nullable|date',
            'visa_expiry_date' => 'nullable|date|after_or_equal:visa_issue_date',
            'status'           => 'required|in:pending,approved,rejected,cancelled',
        ]);

        $lastInvoice = VisaBooking::orderBy('id', 'desc')->first()?->inv_no;
        if ($lastInvoice && preg_match('/VB-(\d+)/', $lastInvoice, $m)) {
            $number = intval($m[1]) + 1;
            $invNo = 'VB-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } else {
            $invNo = 'VB-0001';
        }

        VisaBooking::create([
            'inv_no'          => $invNo,
            'customer_id'     => $request->customer_id,
            'visa_id'         => $request->visa_id,
            'passport_number' => $request->passport_number,
            'type'            => $request->type,
            'agent'           => $request->agent,
            'visa_issue_date' => $request->visa_issue_date,
            'visa_expiry_date'=> $request->visa_expiry_date,
            'status'          => $request->status,
            'created_by'      => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Visa booking saved successfully!');
    }

    /**
     * Show booking
     */
    public function show($id)
    {
        $booking = VisaBooking::with(['customer', 'visa'])->findOrFail($id);
        return view('bookings.visa.show', compact('booking'));
    }

    /**
     * Edit booking
     */
    public function edit(VisaBooking $booking)
    {
        $customers = Customer::all();
        $visas     = Visa::all();

        return view('bookings.visa.edit', compact('booking', 'customers', 'visas'));
    }

    /**
     * Update booking
     */
    public function update(Request $request, VisaBooking $booking)
    {
        $request->validate([
            'customer_id'      => 'required|exists:customers,id',
            'visa_id'          => 'required|exists:visa,id',
            'passport_number'  => 'required|string|max:50',
            'type'             => 'required|string|max:50',
            'agent'            => 'nullable|string|max:100',
            'visa_issue_date'  => 'nullable|date',
            'visa_expiry_date' => 'nullable|date|after_or_equal:visa_issue_date',
            'status'           => 'required|in:pending,approved,rejected,cancelled',
        ]);

        $booking->update($request->only([
            'customer_id', 'visa_id', 'passport_number', 'type', 'agent', 'visa_issue_date', 'visa_expiry_date', 'status'
        ]));

        return redirect()->route('admin.visa-bookings.edit', $booking->id)
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
            'status' => 'required|in:pending,approved,rejected,cancelled',
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
