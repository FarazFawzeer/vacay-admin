<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AirlineInvBooking;
use App\Models\Customer;
use App\Models\Agent;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AirlineInvBookingController extends Controller
{
    /**
     * List all airline bookings with filters
     */
    public function index(Request $request)
    {
        $query = AirlineInvBooking::with(['customer', 'creator']);

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Invoice filter
        if ($request->filled('inv_no')) {
            $query->where('invoice_no', 'like', '%' . $request->inv_no . '%');
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->all());

        if ($request->ajax()) {
            return view('bookings.airline.table', compact('bookings'))->render();
        }

        return view('bookings.airline.index', compact('bookings'));
    }

    /**
     * Create booking form
     */
    public function create()
    {
        $customers = Customer::all();

        // Generate next invoice number
        $lastInvoice = AirlineInvBooking::orderBy('id', 'desc')->first()?->invoice_no;
        if ($lastInvoice && preg_match('/AB(\d+)/', $lastInvoice, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
            $nextInvoice = 'AB' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        } else {
            $nextInvoice = 'AB0001';
        }

        // Load countries from JSON
        $countries = json_decode(file_get_contents(resource_path('data/countries.json')), true);

        $agents = Agent::all();

        return view('bookings.airline.create', compact('customers', 'nextInvoice', 'countries', 'agents'));
    }


    /**
     * Store new booking
     */

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'        => 'required|exists:customers,id',
            'agent_id'           => 'required|exists:agents,id',
            'from_country'       => 'required|string|max:100',
            'to_country'         => 'required|string|max:100',
            'departure_datetime' => 'required|date',
            'arrival_datetime'   => 'required|date|after_or_equal:departure_datetime',
            'airline'            => 'nullable|string|max:150',
            'currency'           => 'required|string|max:10',
            'base_price'         => 'required|numeric|min:0',
            'additional_price'   => 'nullable|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'total_amount'       => 'required|numeric|min:0',
            'advanced_paid'      => 'nullable|numeric|min:0',
            'balance'            => 'required|numeric|min:0',
            'status'             => 'required|string|max:50',
            'payment_status'     => 'required|string|max:50',
        ]);

        // Invoice number
        $lastInvoice = AirlineInvBooking::orderBy('id', 'desc')->first()?->invoice_no;
        $invoiceNo = $lastInvoice && preg_match('/AB(\d+)/', $lastInvoice, $m)
            ? 'AB' . str_pad(((int)$m[1]) + 1, 4, '0', STR_PAD_LEFT)
            : 'AB0001';

        AirlineInvBooking::create([
            'invoice_no'        => $invoiceNo,
            'customer_id'       => $request->customer_id,
            'agent'             => $request->agent_id, // ✅ STORE ID HERE
            'from_country'      => $request->from_country,
            'to_country'        => $request->to_country,
            'departure_datetime' => $request->departure_datetime,
            'arrival_datetime'  => $request->arrival_datetime,
            'airline'           => $request->airline,
            'currency'          => $request->currency,
            'base_price'        => $request->base_price,
            'additional_price'  => $request->additional_price ?? 0,
            'discount'          => $request->discount ?? 0,
            'total_amount'      => $request->total_amount,
            'advanced_paid'     => $request->advanced_paid ?? 0,
            'balance'           => $request->balance,
            'status'            => $request->status,
            'payment_status'    => $request->payment_status,
            'created_by'        => auth()->id(),
        ]);

        return redirect()
            ->route('admin.airline-bookings.index')
            ->with('success', "Airline booking created successfully! Invoice No: {$invoiceNo}");
    }


    /**
     * Show booking
     */
    public function show($id)
    {
        $airline_booking  = AirlineInvBooking::with([
            'customer',
            'creator',
            'agent' // 👈 load agent using agent ID stored in `agent`
        ])->findOrFail($id);

        return view('bookings.airline.show', compact('airline_booking'));
    }


    /**
     * Edit booking
     */
    public function edit(AirlineInvBooking $airline_booking)
    {
        $customers = Customer::all();
        $agents = Agent::all();
        $countries = json_decode(file_get_contents(resource_path('data/countries.json')), true);
        return view('bookings.airline.edit', compact('airline_booking', 'customers', 'agents', 'countries'));
    }


    /**
     * Update booking
     */
    public function update(Request $request, AirlineInvBooking $airline_booking)
    {
        $request->validate([
            'customer_id'        => 'required|exists:customers,id',
            'agent_id'           => 'required|exists:agents,id',
            'from_country'       => 'required|string|max:100',
            'to_country'         => 'required|string|max:100',
            'departure_datetime' => 'required|date',
            'arrival_datetime'   => 'required|date|after_or_equal:departure_datetime',
            'airline'            => 'nullable|string|max:150',
            'currency'           => 'required|string|max:10',
            'base_price'         => 'required|numeric|min:0',
            'additional_price'   => 'nullable|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'total_amount'       => 'required|numeric|min:0',
            'advanced_paid'      => 'nullable|numeric|min:0',
            'balance'            => 'required|numeric|min:0',
            'status'             => 'required|string|max:50',
            'payment_status'     => 'required|string|max:50',
        ]);

        $airline_booking->update([
            'customer_id'        => $request->customer_id,
            'agent'              => $request->agent_id, // ✅ STORE AGENT ID HERE
            'from_country'       => $request->from_country,
            'to_country'         => $request->to_country,
            'departure_datetime' => $request->departure_datetime,
            'arrival_datetime'   => $request->arrival_datetime,
            'airline'            => $request->airline,
            'currency'           => $request->currency,
            'base_price'         => $request->base_price,
            'additional_price'   => $request->additional_price ?? 0,
            'discount'           => $request->discount ?? 0,
            'total_amount'       => $request->total_amount,
            'advanced_paid'      => $request->advanced_paid ?? 0,
            'balance'            => $request->balance,
            'status'             => $request->status,
            'payment_status'     => $request->payment_status,
        ]);

        return redirect()
            ->route('admin.airline-bookings.edit', $airline_booking->id)
            ->with('success', 'Airline booking updated successfully!');
    }



    /**
     * Delete booking
     */
    public function destroy($id)
    {
        try {
            $booking = AirlineInvBooking::findOrFail($id);
            $booking->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
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
                    "{{ asset('images/vacayguider.png') }}",
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
            return $pdf->download("airline-booking.pdf");
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
