<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visa;
use App\Models\VisaCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Agent;


class VisaController extends Controller
{
    public function index(Request $request)
    {
        $query = Visa::with('agents')->orderBy('updated_at', 'desc');

        if ($request->filled('country')) {
            $query->where(function ($q) use ($request) {
                $q->where('from_country', $request->country)
                    ->orWhere('to_country', $request->country);
            });
        }


        if ($request->filled('agent')) {
            $agentId = $request->agent;
            $query->whereHas('agents', function ($q) use ($agentId) {
                $q->where('agents.id', $agentId);
            });
        }


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('from_country', 'like', "%{$search}%")
                    ->orWhere('to_country', 'like', "%{$search}%")
                    ->orWhere('visa_type', 'like', "%{$search}%")
                    ->orWhere('custom_visa_type', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%");
            });
        }


        $visas = $query->paginate(10);

        if ($request->ajax()) {
            return view('details.visa_table', compact('visas'))->render(); // Partial
        }

        $agents = Agent::orderBy('name')->get();
        $countries = json_decode(file_get_contents(resource_path('data/countries.json')), true);

        return view('details.visa', compact('visas', 'agents', 'countries'));
    }


    // Store new visa
    public function store(Request $request)
    {
        $request->validate([
            'from_country' => 'required|string|max:255',
            'to_country' => 'required|string|max:255',
            'visa_type' => 'required|string|max:255',
            'custom_visa_type' => 'nullable|string|max:255',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'note' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*.visa_type' => 'required_with:categories|string|max:255',
            'categories.*.state' => 'nullable|string|max:255',
            'categories.*.days' => 'nullable|integer',
            'categories.*.visa_validity' => 'nullable|string|max:255',
            'categories.*.how_many_days' => 'nullable|integer',
            'categories.*.price' => 'nullable|numeric',
            'categories.*.currency' => 'nullable|string|max:10',
            'categories.*.processing_time' => 'nullable|string|max:255',
            'agents' => 'nullable|array',
        ]);

        // Handle documents upload
        $documentPaths = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('visa_documents', 'public');
                $documentPaths[] = $path;
            }
        }

        // Store main Visa
        $visa = Visa::create([
            'from_country' => $request->from_country,
            'to_country' => $request->to_country,
            'visa_type' => $request->visa_type,
            'custom_visa_type' => $request->custom_visa_type,
            'documents' => $documentPaths, // stored as JSON
            'note' => $request->note,
            'auth_id' => Auth::id(),
            'checklist' => $request->checklist,
            'status' => 'pending', // default status
            'agent_id' => $request->agent_id ?? null, // if you add agent select later

        ]);

        // Store Visa Categories
        if ($request->has('categories')) {
            foreach ($request->categories as $category) {
                $visa->categories()->create([
                    'visa_type' => $category['visa_type'] ?? null,
                    'state' => $category['state'] ?? null,
                    'days' => $category['days'] ?? null,
                    'visa_validity' => $category['visa_validity'] ?? null,
                    'how_many_days' => $category['how_many_days'] ?? null,
                    'price' => $category['price'] ?? null,
                    'currency' => $category['currency'] ?? null,
                    'processing_time' => $category['processing_time'] ?? null,
                ]);
            }
        }

        // Attach selected agents
        if ($request->agents) {
            $visa->agents()->attach($request->agents);
        }


        return redirect()->back()->with('success', 'Visa created successfully!');
    }


    public function edit(Visa $visa)
    {
        $visa->load('agents', 'categories');

        // Countries
        $countries = json_decode(file_get_contents(resource_path('data/countries.json')), true);
        $agents = Agent::orderBy('name')->get();
        // Handle documents (stored as array)
        $documents = collect($visa->documents ?? [])->map(function ($docPath) {
            return [
                'name' => $docPath, // keep full relative path
                'url'  => asset('storage/' . $docPath),
            ];
        });


        // Handle checklist as collection
        $checklist = collect($visa->checklist ?? []);

        return response()->json([
            'visa' => $visa,
            'countries' => $countries,
            'documents' => $documents,
            'checklist' => $checklist,
            'agents' => $agents,

        ]);
    }




    // Update visa
    // Update Visa
    public function update(Request $request, Visa $visa)
    {
        $request->validate([
            'from_country' => 'required|string|max:255',
            'to_country' => 'required|string|max:255',
            'visa_type' => 'required|string|max:255',
            'custom_visa_type' => 'nullable|string|max:255',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'existing_documents' => 'nullable|array',
            'note' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*.visa_type' => 'required_with:categories|string|max:255',
            'categories.*.state' => 'nullable|string|max:255',
            'categories.*.days' => 'nullable|integer',
            'categories.*.visa_validity' => 'nullable|string|max:255',
            'categories.*.how_many_days' => 'nullable|integer',
            'categories.*.price' => 'nullable|numeric',
            'categories.*.currency' => 'nullable|string|max:10',
            'categories.*.processing_time' => 'nullable|string|max:255',
            'agents' => 'nullable|array',
        ]);

        // Existing documents that user kept
        $existingDocuments = $request->existing_documents ?? [];
        $currentDocuments = $visa->documents ?? [];

        // Remove deleted documents from storage
        foreach ($currentDocuments as $docPath) {
            if (!in_array($docPath, $existingDocuments)) {
                if (file_exists(storage_path('app/public/' . $docPath))) {
                    unlink(storage_path('app/public/' . $docPath));
                }
            }
        }

        // Start with existing documents
        $documentPaths = $existingDocuments;

        // Add newly uploaded documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('visa_documents', 'public');
                $documentPaths[] = $path;
            }
        }

        // Update main Visa
        $visa->update([
            'from_country' => $request->from_country,
            'to_country' => $request->to_country,
            'visa_type' => $request->visa_type,
            'custom_visa_type' => $request->custom_visa_type,
            'documents' => $documentPaths, // updated JSON array
            'note' => $request->note,
            'checklist' => $request->checklist,
            'status' => $visa->status, // keep current status
            'agent_id' => $request->agent_id ?? $visa->agent_id,
        ]);

        // Update Visa Categories
        $visa->categories()->delete();
        if ($request->has('categories')) {
            foreach ($request->categories as $category) {
                $visa->categories()->create([
                    'visa_type' => $category['visa_type'] ?? null,
                    'state' => $category['state'] ?? null,
                    'days' => $category['days'] ?? null,
                    'visa_validity' => $category['visa_validity'] ?? null,
                    'how_many_days' => $category['how_many_days'] ?? null,
                    'price' => $category['price'] ?? null,
                    'currency' => $category['currency'] ?? null,
                    'processing_time' => $category['processing_time'] ?? null,
                ]);
            }
        }

        // Update agents
        if ($request->agents) {
            $visa->agents()->sync($request->agents);
        } else {
            $visa->agents()->detach();
        }

        return redirect()->back()->with('success', 'Visa updated successfully!');
    }



    public function show($id)
    {
        $visa = Visa::with('agents', 'user')->findOrFail($id);



        return view('details.show_visa', compact('visa'));
    }

    public function destroy($id)
    {
        $visa = Visa::with('categories')->find($id);

        if (!$visa) {
            return response()->json([
                'success' => false,
                'message' => 'Visa record not found.'
            ], 404);
        }

        // Delete documents if exists
        if ($visa->documents && is_array($visa->documents)) {
            foreach ($visa->documents as $docPath) {
                $fullPath = storage_path('app/public/' . $docPath);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
        }

        // Optional: delete categories
        $visa->categories()->delete();

        // Detach agents
        $visa->agents()->detach();

        // Delete visa record
        $visa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Visa deleted successfully.'
        ]);
    }
}
