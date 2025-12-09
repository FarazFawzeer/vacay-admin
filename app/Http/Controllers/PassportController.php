<?php

namespace App\Http\Controllers;

use App\Models\Passport;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class PassportController extends Controller
{
    /**
     * Display a listing of passports.
     */
    public function index(Request $request)
    {
        $query = Passport::with('customer')->latest();

        // Apply search
        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('passport_number', 'like', "%$search%")
                    ->orWhere('nationality', 'like', "%$search%")
                    ->orWhereHas('customer', function ($c) use ($search) {
                        $c->where('name', 'like', "%$search%")
                            ->orWhere('first_name', 'like', "%$search%");
                    });
            });
        }

        $passports = $query->paginate(10);
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
            'id_photo.*' => 'nullable|mimes:jpg,jpeg,png,webp,pdf|max:5120',


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
            'id_photo.*' => 'nullable|mimes:jpg,jpeg,png,webp,pdf',
            'files_to_remove' => 'nullable|array',
            'files_to_remove.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();

        $idPhotos = $passport->id_photo ?? [];

        // Remove files if user requested
        if (isset($data['files_to_remove'])) {
            foreach ($data['files_to_remove'] as $file) {
                if (in_array($file, $idPhotos) && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                    $idPhotos = array_diff($idPhotos, [$file]);
                }
            }
            $idPhotos = array_values($idPhotos); // reindex
        }

        // Add newly uploaded files
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
