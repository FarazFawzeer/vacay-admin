<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    // ============================
    // LIST + FILTER + SEARCH
    // ============================
    public function index(Request $request)
    {
        $query = Agent::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name, company, or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('company_name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Sorting by name
        if ($request->filled('sort')) {
            $query->orderBy('name', $request->sort);
        } else {
            $query->orderBy('id', 'desc'); // default sort
        }

        $agents = $query->paginate(10)->withQueryString(); // keep filters in pagination links

        // AJAX table load
        if ($request->ajax()) {
            return view('agent.index-table', compact('agents'))->render();
        }

        return view('agent.view', compact('agents'));
    }



    // ============================
    // CREATE FORM
    // ============================
    public function create()
    {
        return view('agent.create');
    }



    // ============================
    // STORE AGENT
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'company_name' => 'nullable|string|max:255',
            'company_city' => 'nullable|string|max:255',
            'company_country' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'land_line' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'service' => 'nullable|string',   // This will contain JSON string
            'note' => 'nullable|string',
            'status' => 'required',
        ]);

        // Convert JSON string to array before saving
        $services = $request->service ? json_decode($request->service, true) : [];

        Agent::create([
            'name' => $request->name,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'company_city' => $request->company_city,
            'company_country' => $request->company_country,
            'phone' => $request->phone,
            'land_line' => $request->land_line,
            'whatsapp' => $request->whatsapp,
            'service' => $services, // <-- Save as array
            'note' => $request->note,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.agents.create')
            ->with('success', 'Agent created successfully!');
    }



    // ============================
    // EDIT FORM
    // ============================
    public function edit($id)
    {
        $agent = Agent::findOrFail($id);
        return view('agent.edit', compact('agent'));
    }



    // ============================
    // UPDATE AGENT
    // ============================
    public function update(Request $request, $id)
    {
        $agent = Agent::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'company_name' => 'nullable|string|max:255',
            'company_city' => 'nullable|string|max:255',
            'company_country' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'land_line' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'service' => 'nullable|string',
            'note' => 'nullable|string',
            'status' => 'required', // accepts 'active' or 'inactive'
        ]);

        $services = $request->service ? json_decode($request->service, true) : [];

        $agent->update([
            'name' => $request->name,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'company_city' => $request->company_city,
            'company_country' => $request->company_country,
            'phone' => $request->phone,
            'land_line' => $request->land_line,
            'whatsapp' => $request->whatsapp,
            'service' => $services,
            'note' => $request->note,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.agents.edit', $agent->id)
            ->with('success', 'Agent updated successfully.');
    }




    // ============================
    // DELETE AGENT
    // ============================
    public function destroy($id)
    {
        $agent = Agent::findOrFail($id);
        $agent->delete();

        return response()->json([
            'success' => true,
            'message' => 'Agent deleted successfully!'
        ]);
    }
}
