<?php

namespace App\Http\Controllers;

use App\Models\Passport;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PassportController extends Controller
{
    /**
     * Display a listing of passports.
     */
    public function index()
    {
        $passports = Passport::with('customer')->latest()->paginate(10); // <-- FIXED
        $customers = Customer::all();

        return view('details.passport', compact('passports', 'customers'));
    }


    /**
     * Show the form for creating a new passport.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('details.passport', [
            'customers' => $customers,
            'passport' => null
        ]);
    }

    /**
     * Store a newly created passport in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'first_name' => 'required|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'passport_number' => 'required|string|max:255|unique:passports,passport_number',
            'passport_expire_date' => 'required|date',
            'nationality' => 'required|string|max:255',
            'dob' => 'required|date',
            'sex' => 'nullable|in:male,female,other',
            'issue_date' => 'nullable|date',
            'id_number' => 'nullable|string|max:255',
            'id_photo.*' => 'nullable|image|mimes:jpg,jpeg,png', // handle multiple images
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();

        $idPhotos = [];
        if ($request->hasFile('id_photo')) {
            foreach ($request->file('id_photo') as $file) {
                $idPhotos[] = $file->store('passport_photos', 'public');
            }
        }
        $data['id_photo'] = $idPhotos;

        Passport::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Passport created successfully!',
        ]);
    }



    /**
     * Show the form for editing the specified passport.
     */
    public function edit($id)
    {
        $passport = Passport::findOrFail($id);
        $customers = Customer::all();

        return view('details.passport', compact('passport', 'customers'));
    }

    /**
     * Update the specified passport in storage.
     */
    public function update(Request $request, Passport $passport)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'first_name' => 'required|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'passport_number' => 'required|string|max:255|unique:passports,passport_number,' . $passport->id,
            'passport_expire_date' => 'required|date',
            'nationality' => 'required|string|max:255',
            'dob' => 'required|date',
            'sex' => 'nullable|in:male,female,other',
            'issue_date' => 'nullable|date',
            'id_number' => 'nullable|string|max:255',
            'id_photo.*' => 'nullable|image|mimes:jpg,jpeg,png', // multiple images
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();

        $idPhotos = $passport->id_photo ?? []; // existing photos

        if ($request->hasFile('id_photo')) {
            foreach ($request->file('id_photo') as $file) {
                $idPhotos[] = $file->store('passport_photos', 'public');
            }
        }

        $data['id_photo'] = $idPhotos;

        $passport->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Passport updated successfully!',
        ]);
    }




    public function show($id)
    {
        $passport = Passport::with('customer')->findOrFail($id);
        return view('details.show_passport', compact('passport'));
    }

    /**
     * Remove the specified passport from storage.
     */
    public function destroy($id)
    {
        $passport = Passport::findOrFail($id);

        if ($passport->id_photo && file_exists(storage_path('app/public/' . $passport->id_photo))) {
            unlink(storage_path('app/public/' . $passport->id_photo));
        }

        $passport->delete();

        return redirect()->back()->with('success', 'Passport deleted successfully.');
    }
}
