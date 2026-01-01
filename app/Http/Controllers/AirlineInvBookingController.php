<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AirlineInvBooking;
use App\Models\AirlineInvBookingTrip;
use App\Models\Customer;
use App\Models\Agent;
use App\Models\Passport;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AirlineInvBookingController extends Controller
{
    /**
     * List all airline bookings with filters
     */
    public function index(Request $request)
    {
        // Load bookings with trips and related agent/passport
        $query = AirlineInvBooking::with(['trips.agent', 'trips.passport']);

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Invoice filter
        if ($request->filled('inv_no')) {
            $query->where('invoice_id', 'like', '%' . $request->inv_no . '%');
        }

        $bookings = $query->orderBy('published_at', 'desc')
            ->paginate(10)
            ->appends($request->all());

        $airports = collect(
            json_decode(file_get_contents(resource_path('data/airports.json')), true)
        )->keyBy('code');

        if ($request->ajax()) {
            // Ensure $airports is passed if the table needs to decode airport codes
            return view('bookings.airline.table', compact('bookings', 'airports'))->render();
        }

        return view('bookings.airline.index', compact('bookings', 'airports'));
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
        $airports = json_decode(file_get_contents(resource_path('data/airports.json')), true);


        $agents = Agent::all();

        $passports = Passport::with('customer')->get();

        return view('bookings.airline.create', compact('customers', 'nextInvoice', 'airports', 'agents', 'passports'));
    }


    private function airportString($code, $airports)
    {
        $airport = collect($airports)->firstWhere('code', $code);

        return $airport
            ? "{$airport['code']} | {$airport['name']} | {$airport['country']}"
            : null;
    }


    /**
     * Store new booking
     */

    public function store(Request $request)
    {
        // 1️⃣ Validate main booking info
        $request->validate([
            'business_type'   => 'required|in:corporate,individual',
            'ticket_type'     => 'required|in:one_way,return',
            'status'          => 'required',
            'payment_status'  => 'required',
            'currency'        => 'required',
            'base_price'      => 'required|numeric',
            'additional_price' => 'nullable|numeric',
            'discount'        => 'nullable|numeric',
            'total_amount'    => 'required|numeric',
            'advanced_paid'   => 'nullable|numeric',
            'balance'         => 'required|numeric',
            'note'             => 'nullable',
            'published_at'     => 'nullable|date',

        ]);

        $airports = json_decode(
            file_get_contents(resource_path('data/airports.json')),
            true
        );

        // 2️⃣ Generate invoice_id (AB0001, AB0002, ...)
        $lastInvoice = AirlineInvBooking::orderBy('id', 'desc')->first();

        if ($lastInvoice && preg_match('/AB(\d+)/', $lastInvoice->invoice_id, $matches)) {
            $number = (int) $matches[1] + 1;
        } else {
            $number = 1;
        }

        $invoice_id = 'AB' . str_pad($number, 4, '0', STR_PAD_LEFT);

        // 3️⃣ Create booking
        $booking = AirlineInvBooking::create([
            'invoice_id'       => $invoice_id,
            'business_type'    => $request->business_type,
            'company_name'     => $request->company_name,
            'ticket_type'      => $request->ticket_type,
            'return_type'      => $request->return_type, // ← dummy / return_ticket / round_trip
            'status'           => $request->status,
            'payment_status'   => $request->payment_status,
            'currency'         => $request->currency,
            'base_price'       => $request->base_price,
            'additional_price' => $request->additional_price ?? 0,
            'discount'         => $request->discount ?? 0,
            'total_amount'     => $request->total_amount,
            'advanced_paid'    => $request->advanced_paid ?? 0,
            'balance'          => $request->balance,
            'note'             => $request->note,
            'published_at'     => $request->published_at,
        ]);

        // 4️⃣ Prepare trips
        $trips = [];

        /**
         * ONE WAY (normal)
         */
        if ($request->ticket_type === 'one_way') {
            $trips[] = [
                'trip_type'          => 'one_way',
                'passport_id' => $request->final_passport_id,
                'passport_no' => $request->final_passport_no,
                'agent_id'           => $request->oneway_agent_id,
                'airline'            => $request->oneway_airline,
                'airline_no'         => $request->oneway_airline_no,
                'from_country' => $this->airportString(
                    $request->oneway_from_airport,
                    $airports
                ),
                'to_country' => $this->airportString(
                    $request->oneway_to_airport,
                    $airports
                ),
                'pnr'                => $request->oneway_pnr,
                'departure_datetime' => $request->oneway_departure_datetime,
                'arrival_datetime'   => $request->oneway_arrival_datetime,
                'baggage_qty'        => $request->oneway_baggage_qty ?? 0,
                'handluggage_qty'    => $request->oneway_handluggage_qty ?? 0,
            ];
        }

        /**
         * RETURN → DUMMY (uses One Way fields)
         */
        elseif ($request->ticket_type === 'return' && $request->return_type === 'dummy') {
            $trips[] = [
                'trip_type'          => 'dummy',
                'passport_id' => $request->final_passport_id,
                'passport_no' => $request->final_passport_no,
                'agent_id'           => $request->oneway_agent_id,
                'airline'            => $request->oneway_airline,
                'airline_no'         => $request->oneway_airline_no,
                'from_country' => $this->airportString(
                    $request->oneway_from_airport,
                    $airports
                ),
                'to_country' => $this->airportString(
                    $request->oneway_to_airport,
                    $airports
                ),

                'pnr'                => $request->oneway_pnr,
                'departure_datetime' => $request->oneway_departure_datetime,
                'arrival_datetime'   => $request->oneway_arrival_datetime,
                'baggage_qty'        => $request->oneway_baggage_qty ?? 0,
                'handluggage_qty'    => $request->oneway_handluggage_qty ?? 0,
            ];
        }

        /**
         * RETURN → RETURN TICKET
         */
        elseif ($request->ticket_type === 'return' && $request->return_type === 'return_ticket') {

            // Going
            $trips[] = [
                'trip_type'          => 'going',
                'passport_id'        => $request->return_going_customer_id,
                'passport_no'        => $request->return_going_passport_no,
                'agent_id'           => $request->going_agent_id,
                'airline'            => $request->going_airline,
                'airline_no'         => $request->going_airline_no,
                'from_country' => $this->airportString(
                    $request->going_from_airport,
                    $airports
                ),
                'to_country' => $this->airportString(
                    $request->going_to_airport,
                    $airports
                ),

                'pnr'                => $request->going_pnr,
                'departure_datetime' => $request->going_departure_datetime,
                'arrival_datetime'   => $request->going_arrival_datetime,
                'baggage_qty'        => $request->going_baggage_qty ?? 0,
                'handluggage_qty'    => $request->going_handluggage_qty ?? 0,
            ];

            // Return
            $trips[] = [
                'trip_type'          => 'return',
                'passport_id'        => $request->going_customer_id,
                'passport_no'        => $request->going_passport_no,
                'agent_id'           => $request->coming_agent_id,
                'airline'            => $request->coming_airline,
                'airline_no'         => $request->coming_airline_no,
                'from_country' => $this->airportString(
                    $request->coming_from_airport,
                    $airports
                ),
                'to_country' => $this->airportString(
                    $request->coming_to_airport,
                    $airports
                ),

                'pnr'                => $request->coming_pnr,
                'departure_datetime' => $request->coming_departure_datetime,
                'arrival_datetime'   => $request->coming_arrival_datetime,
                'baggage_qty'        => $request->coming_baggage_qty ?? 0,
                'handluggage_qty'    => $request->coming_handluggage_qty ?? 0,
            ];
        }

        /**
         * ROUND TRIP (dynamic)
         */
        foreach ($request->all() as $key => $value) {
            if (preg_match('/round_trip_(\d+)_customer_id/', $key, $matches)) {
                $i = $matches[1];

                $trips[] = [
                    'trip_type'          => 'round_trip',
                    'passport_id'        => $request->input("round_trip_{$i}_customer_id"),
                    'passport_no'        => $request->input("round_trip_{$i}_passport_no"),
                    'agent_id'           => $request->input("round_trip_{$i}_agent_id"),
                    'airline'            => $request->input("round_trip_{$i}_airline"),
                    'airline_no'         => $request->input("round_trip_{$i}_airline_no"),
                    'from_country' => $this->airportString(
                        $request->input("round_trip_{$i}_from_airport"),
                        $airports
                    ),
                    'to_country' => $this->airportString(
                        $request->input("round_trip_{$i}_to_airport"),
                        $airports
                    ),

                    'pnr'                => $request->input("round_trip_{$i}_pnr"),
                    'departure_datetime' => $request->input("round_trip_{$i}_departure_datetime"),
                    'arrival_datetime'   => $request->input("round_trip_{$i}_arrival_datetime"),
                    'baggage_qty'        => $request->input("round_trip_{$i}_baggage_qty") ?? 0,
                    'handluggage_qty'    => $request->input("round_trip_{$i}_handluggage_qty") ?? 0,
                ];
            }
        }

        // 6️⃣ Insert trips
        $booking->trips()->createMany($trips);

        return redirect()
            ->route('admin.airline-bookings.create')
            ->with('success', "Airline booking created successfully with Invoice ID: {$invoice_id}");
    }

    /**
     * Show booking
     */
    public function show($id)
    {
        // Load booking with trips → passport → customer, and trips → agent
        $airline_booking = AirlineInvBooking::with([
            'trips.passport.customer',
            'trips.agent',
        ])->findOrFail($id);

        // Grab first trip for easier access in blade
        $firstTrip = $airline_booking->trips->first();

        // Load airports data
        $airports = collect(
            json_decode(file_get_contents(resource_path('data/airports.json')), true)
        )->keyBy('code');

        return view('bookings.airline.show', compact('airline_booking', 'firstTrip', 'airports'));
    }




    /**
     * Edit booking
     */
    public function edit($id)
    {
        $booking = AirlineInvBooking::with('trips')->findOrFail($id);
        $customers = Customer::all();
        $agents = Agent::all();
        $passports = Passport::with('customer')->get();

        // Load countries from JSON
        $airports = json_decode(file_get_contents(resource_path('data/airports.json')), true);

        return view('bookings.airline.edit', compact('booking', 'customers', 'agents', 'passports', 'airports'));
    }

    /**
     * Update booking
     */
    public function update(Request $request, $id)
    {
        // 1️⃣ Validate main booking info
        $request->validate([
            'business_type'   => 'required|in:corporate,individual',
            'ticket_type'     => 'required|in:one_way,return',
            'status'          => 'required',
            'payment_status'  => 'required',
            'currency'        => 'required',
            'base_price'      => 'required|numeric',
            'additional_price' => 'nullable|numeric',
            'discount'        => 'nullable|numeric',
            'total_amount'    => 'required|numeric',
            'advanced_paid'   => 'nullable|numeric',
            'balance'         => 'required|numeric',
            'note'            => 'nullable',
            'published_at'    => 'nullable|date',
        ]);

        // 2️⃣ Load airports data
        $airports = json_decode(
            file_get_contents(resource_path('data/airports.json')),
            true
        );

        // 3️⃣ Find the booking
        $booking = AirlineInvBooking::findOrFail($id);

        // 4️⃣ Update booking
        $booking->update([
            'business_type'    => $request->business_type,
            'company_name'     => $request->company_name,
            'ticket_type'      => $request->ticket_type,
            'return_type'      => $request->return_type,
            'status'           => $request->status,
            'payment_status'   => $request->payment_status,
            'currency'         => $request->currency,
            'base_price'       => $request->base_price,
            'additional_price' => $request->additional_price ?? 0,
            'discount'         => $request->discount ?? 0,
            'total_amount'     => $request->total_amount,
            'advanced_paid'    => $request->advanced_paid ?? 0,
            'balance'          => $request->balance,
            'note'             => $request->note,
            'published_at'     => $request->published_at,
        ]);

        // 5️⃣ Delete existing trips
        $booking->trips()->delete();

        // 6️⃣ Prepare trips (same logic as store)
        $trips = [];

        if ($request->ticket_type === 'one_way') {
            $trips[] = [
                'trip_type'          => 'one_way',
                'passport_id'        => $request->final_passport_id,
                'passport_no'        => $request->final_passport_no,
                'agent_id'           => $request->oneway_agent_id,
                'airline'            => $request->oneway_airline,
                'airline_no'         => $request->oneway_airline_no,
                'from_country' => $request->oneway_from_airport,
                'to_country'   => $request->oneway_to_airport,

                'pnr'                => $request->oneway_pnr,
                'departure_datetime' => $request->oneway_departure_datetime,
                'arrival_datetime'   => $request->oneway_arrival_datetime,
                'baggage_qty'        => $request->oneway_baggage_qty ?? 0,
                'handluggage_qty'    => $request->oneway_handluggage_qty ?? 0,
            ];
        } elseif ($request->ticket_type === 'return' && $request->return_type === 'dummy') {
            $trips[] = [
                'trip_type'          => 'dummy',
                'passport_id'        => $request->final_passport_id,
                'passport_no'        => $request->final_passport_no,
                'agent_id'           => $request->oneway_agent_id,
                'airline'            => $request->oneway_airline,
                'airline_no'         => $request->oneway_airline_no,
                'from_country' => $request->going_from_airport,
                'to_country'   => $request->going_to_airport,

                'pnr'                => $request->oneway_pnr,
                'departure_datetime' => $request->oneway_departure_datetime,
                'arrival_datetime'   => $request->oneway_arrival_datetime,
                'baggage_qty'        => $request->oneway_baggage_qty ?? 0,
                'handluggage_qty'    => $request->oneway_handluggage_qty ?? 0,
            ];
        } elseif ($request->ticket_type === 'return' && $request->return_type === 'return_ticket') {
            // Going
            $trips[] = [
                'trip_type'          => 'going',
                'passport_id'        => $request->return_going_customer_id,
                'passport_no'        => $request->return_going_passport_no,
                'agent_id'           => $request->going_agent_id,
                'airline'            => $request->going_airline,
                'airline_no'         => $request->going_airline_no,
                'from_country' => $request->going_from_airport,
                'to_country'   => $request->going_to_airport,

                'pnr'                => $request->going_pnr,
                'departure_datetime' => $request->going_departure_datetime,
                'arrival_datetime'   => $request->going_arrival_datetime,
                'baggage_qty'        => $request->going_baggage_qty ?? 0,
                'handluggage_qty'    => $request->going_handluggage_qty ?? 0,
            ];
            // Return
            $trips[] = [
                'trip_type'          => 'return',
                'passport_id'        => $request->going_customer_id,
                'passport_no'        => $request->going_passport_no,
                'agent_id'           => $request->coming_agent_id,
                'airline'            => $request->coming_airline,
                'airline_no'         => $request->coming_airline_no,
                'from_country' => $request->going_from_airport,
                'to_country'   => $request->going_to_airport,

                'pnr'                => $request->coming_pnr,
                'departure_datetime' => $request->coming_departure_datetime,
                'arrival_datetime'   => $request->coming_arrival_datetime,
                'baggage_qty'        => $request->coming_baggage_qty ?? 0,
                'handluggage_qty'    => $request->coming_handluggage_qty ?? 0,
            ];
        }

        // ROUND TRIP dynamic
        foreach ($request->all() as $key => $value) {
            if (preg_match('/round_trip_(\d+)_customer_id/', $key, $matches)) {
                $i = $matches[1];
                $trips[] = [
                    'trip_type'          => 'round_trip',
                    'passport_id'        => $request->input("round_trip_{$i}_customer_id"),
                    'passport_no'        => $request->input("round_trip_{$i}_passport_no"),
                    'agent_id'           => $request->input("round_trip_{$i}_agent_id"),
                    'airline'            => $request->input("round_trip_{$i}_airline"),
                    'airline_no'         => $request->input("round_trip_{$i}_airline_no"),
                    'from_country' => $request->input("round_trip_{$i}_from_airport"),
                    'to_country'   => $request->input("round_trip_{$i}_to_airport"),

                    'pnr'                => $request->input("round_trip_{$i}_pnr"),
                    'departure_datetime' => $request->input("round_trip_{$i}_departure_datetime"),
                    'arrival_datetime'   => $request->input("round_trip_{$i}_arrival_datetime"),
                    'baggage_qty'        => $request->input("round_trip_{$i}_baggage_qty") ?? 0,
                    'handluggage_qty'    => $request->input("round_trip_{$i}_handluggage_qty") ?? 0,
                ];
            }
        }

        // 7️⃣ Insert trips
        $booking->trips()->createMany($trips);

        return redirect()
            ->route('admin.airline-bookings.edit', $booking->id)
            ->with('success', "Airline booking updated successfully! Invoice ID: {$booking->invoice_id}");
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


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $booking = AirlineInvBooking::find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found.'
            ], 404);
        }

        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'status' => $booking->status
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
