<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        $query = Customer::query();

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('service')) {
            $query->whereJsonContains('service', $request->service);
        }
        if ($request->filled('heard_us')) {
            $query->where('heard_us', $request->heard_us);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Sorting A-Z / Z-A
        if ($request->filled('sort')) {
            if ($request->sort == 'asc') {
                $query->orderBy('name', 'asc');
            } elseif ($request->sort == 'desc') {
                $query->orderBy('name', 'desc');
            }
        }

        $customers = $query->paginate(10);

        // Existing filter values
        $types = Customer::whereNotNull('type')->where('type', '!=', '')->distinct()->pluck('type');
        $services = Customer::whereNotNull('service')
            ->where('service', '!=', '')
            ->get()
            ->pluck('service')
            ->flatten()
            ->unique();
        $portals = Customer::whereNotNull('portal')->where('portal', '!=', '')->distinct()->pluck('portal');
        $heard_us_list = Customer::whereNotNull('heard_us')->where('heard_us', '!=', '')->distinct()->pluck('heard_us');

        if ($request->ajax()) {
            return view('customer.index-table', compact('customers'))->render();
        }

        return view('customer.view', compact('customers', 'types', 'services', 'heard_us_list'));
    }







    // Show create form
    public function create()
    {
        return view('admin.customers.create'); // point to your Blade file
    }

    // Store customer data
    // Store customer data
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact' => 'required|string|max:20',
            'other_phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'type' => 'required|in:Individual,Corporate',
            'company_name' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'service' => 'nullable',
            'heard_us' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        }

        try {
            $data = $validator->validated();
            $data['date_of_entry'] = now();
            $data['portal'] = 'Admin BE';

            // Decode service JSON or set empty array
            $data['service'] = json_decode($request->service ?? '[]', true);

            // Auto-generate customer code
            $last = Customer::orderBy('id', 'desc')->first();
            $next = $last ? ((int) filter_var($last->customer_code, FILTER_SANITIZE_NUMBER_INT)) + 1 : 1;

            $data['customer_code'] = 'VG' . str_pad($next, 3, '0', STR_PAD_LEFT);

            Customer::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => ['Something went wrong']
            ]);
        }
    }




    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact' => 'required|string|max:20',
            'other_phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'type' => 'required|in:Individual,Corporate',
            'company_name' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'service' => 'nullable',
            'heard_us' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        }

        try {
            $data = $validator->validated();
            $data['service'] = json_decode($request->service ?? '[]', true);

            $customer->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => ['Something went wrong']
            ]);
        }
    }



    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully!'
        ]);
    }
}
