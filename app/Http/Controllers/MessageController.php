<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Mail\CustomNotificationMail;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function create()
    {
        $customers = Customer::whereNotNull('email')
            ->where('email', '!=', '')
            ->select('id', 'name', 'email', 'type', 'sub_type')
            ->get();

        $types = Customer::whereNotNull('type')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->distinct()
            ->pluck('type');

        $subTypes = Customer::whereNotNull('sub_type')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->distinct()
            ->pluck('sub_type');

        return view('messages.create', compact('customers', 'types', 'subTypes'));
    }


    public function filterCustomers(Request $request)
    {
        $query = Customer::whereNotNull('email')
            ->where('email', '!=', '');

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('sub_type')) {
            $query->where('sub_type', $request->sub_type);
        }

        $customers = $query->select('id', 'name', 'email')->get();

        return response()->json($customers);
    }


    public function send(Request $request)
    {
        $request->validate([
            'send_mode' => 'required|in:all,filter,selected',
            'customer_ids' => 'nullable|array',
            'type' => 'nullable|string',
            'sub_type' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'greeting' => 'nullable|string|max:255',
            'message' => 'required|string',
            'footer' => 'nullable|string',
        ]);

        $customers = collect();

        /**
         * 1️⃣ ALL CUSTOMERS
         */
        if ($request->send_mode === 'all') {
            $customers = Customer::withEmail()->get();
        }

        /**
         * 2️⃣ FILTER MODE
         */
        elseif ($request->send_mode === 'filter') {

            $query = Customer::withEmail();

            // Filter by type
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            // Filter by sub type
            if ($request->filled('sub_type')) {
                $query->where('sub_type', $request->sub_type);
            }

            // 🔑 If specific customers selected → override filters
            if (!empty($request->customer_ids)) {
                $query->whereIn('id', $request->customer_ids);
            }

            $customers = $query->get();
        }

        /**
         * 3️⃣ SELECTED MODE
         */
        elseif ($request->send_mode === 'selected') {
            if (empty($request->customer_ids)) {
                return back()->withErrors(['customer_ids' => 'Please select at least one customer']);
            }

            $customers = Customer::withEmail()
                ->whereIn('id', $request->customer_ids)
                ->get();
        }

        /**
         * ❌ Safety check
         */
        if ($customers->isEmpty()) {
            return back()->withErrors(['customers' => 'No customers found for the selected criteria']);
        }

        /**
         * 📧 Queue Emails
         */
        foreach ($customers as $customer) {
            Mail::to($customer->email)->send(
                new CustomNotificationMail(
                    $request->subject,
                    $request->greeting,
                    $request->message,
                    $request->footer
                )
            );
        }


        return redirect()->back()->with('success', 'Emails queued successfully!');
    }
}
