<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use Illuminate\Support\Facades\Log;
use Exception;

class DivisionController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $divisions = Division::with(['operations'])->paginate(10);
        return view('divisions.index',compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('divisions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:divisions,code',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active') ? $validated['is_active'] : true;
        Division::create($validated);
        return redirect()->route('divisions.index')->with('success','Division created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($divisionId)
    {
        //
        $division = Division::findOrFail($divisionId);
        return view('divisions.edit',compact('division'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Division $division)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:divisions,code,'. $division->code,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active') ? $validated['is_active'] : true;
        $division->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);
        return redirect()->route('divisions.index')->with('success','Berhasil Update divisi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division)
    {
        //
        try {
            if ($division) {
                return redirect()->back()->with('error', 'Unknown');
            }

            $division->delete();
            return redirect()->route('divisions.index')->with('success', 'division deleted successfully');
        } catch (Exception $e) {
            Log::error('Error deleting division: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete division');
        }
    }
}
