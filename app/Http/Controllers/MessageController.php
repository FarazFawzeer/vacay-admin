<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\EmailSender;
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

        // ✅ NEW: FROM EMAIL LIST
        $senders = EmailSender::where('status', 1)
            ->orderBy('email')
            ->get(['id', 'name', 'email']);

        return view('messages.create', compact('customers', 'types', 'subTypes', 'senders'));
    }

    public function filterCustomers(Request $request)
    {
        $query = Customer::whereNotNull('email')
            ->where('email', '!=', '');

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
            'sender_id' => 'required|exists:email_senders,id', // ✅ NEW
            'send_mode' => 'required|in:all,filter,selected',
            'customer_ids' => 'nullable|array',
            'type' => 'nullable|string',
            'sub_type' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'greeting' => 'nullable|string|max:255',
            'message' => 'required|string',
            'footer' => 'nullable|string',
        ]);

        // ✅ get selected sender (only active)
        $sender = EmailSender::where('status', 1)->findOrFail($request->sender_id);

        $customers = collect();

        if ($request->send_mode === 'all') {
            $customers = Customer::withEmail()->get();
        } elseif ($request->send_mode === 'filter') {

            $query = Customer::withEmail();

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('sub_type')) {
                $query->where('sub_type', $request->sub_type);
            }

            if (!empty($request->customer_ids)) {
                $query->whereIn('id', $request->customer_ids);
            }

            $customers = $query->get();
        } elseif ($request->send_mode === 'selected') {

            if (empty($request->customer_ids)) {
                return back()->withErrors(['customer_ids' => 'Please select at least one customer']);
            }

            $customers = Customer::withEmail()
                ->whereIn('id', $request->customer_ids)
                ->get();
        }

        if ($customers->isEmpty()) {
            return back()->withErrors(['customers' => 'No customers found for the selected criteria']);
        }

        foreach ($customers as $customer) {
            Mail::to($customer->email)->send(
                (new CustomNotificationMail(
                    $request->subject,
                    $request->greeting,
                    $request->message,
                    $request->footer
                ))->from($sender->email, $sender->name ?? config('mail.from.name'))
            );
        }

        return redirect()->back()->with('success', 'Emails sent successfully!');
    }
}
