<?php

namespace App\Http\Controllers;

use App\Models\PartInternal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartInternalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partInternals = PartInternal::orderBy('created_at', 'desc')->get();
        return view('part-internals.index', compact('partInternals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('part-internals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_number' => 'required|string|max:255|unique:part_internals,part_number',
            'nointernal' => 'nullable|integer',
            'part_name' => 'nullable|string|max:255',
            'matl_spec' => 'nullable|string|max:255',
            'matl_req' => 'nullable|numeric|min:0',
            'deoxidation' => 'nullable|string',
            'comp_per_mould' => 'nullable|integer|min:0',
            'target_qty_waxing' => 'nullable|integer|min:0',
            'target_qty_mould_room' => 'nullable|integer|min:0',
            'target_qty_melting' => 'nullable|integer|min:0',
            'target_qty_heat_treatment' => 'nullable|integer|min:0',
            'target_qty_cut_off' => 'nullable|integer|min:0',
            'target_qty_finishing' => 'nullable|integer|min:0',
            'target_qty_machining' => 'nullable|integer|min:0',
            'target_qty_quality_control' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            PartInternal::create($validated);
            
            DB::commit();
            
            return redirect()
                ->route('part-internals.index')
                ->with('success', 'Part internal created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create part internal: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PartInternal $partInternal)
    {
        return view('part-internals.show', compact('partInternal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartInternal $partInternal)
    {
        return view('part-internals.edit', compact('partInternal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PartInternal $partInternal)
    {
        $validated = $request->validate([
            'part_number' => 'required|string|max:255|unique:part_internals,part_number,' . $partInternal->id,
            'nointernal' => 'nullable|integer',
            'part_name' => 'nullable|string|max:255',
            'matl_spec' => 'nullable|string|max:255',
            'matl_req' => 'nullable|numeric|min:0',
            'deoxidation' => 'nullable|string',
            'comp_per_mould' => 'nullable|integer|min:0',
            'target_qty_waxing' => 'nullable|integer|min:0',
            'target_qty_mould_room' => 'nullable|integer|min:0',
            'target_qty_melting' => 'nullable|integer|min:0',
            'target_qty_heat_treatment' => 'nullable|integer|min:0',
            'target_qty_cut_off' => 'nullable|integer|min:0',
            'target_qty_finishing' => 'nullable|integer|min:0',
            'target_qty_machining' => 'nullable|integer|min:0',
            'target_qty_quality_control' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            $partInternal->update($validated);
            
            DB::commit();
            
            return redirect()
                ->route('part-internals.index')
                ->with('success', 'Part internal updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update part internal: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartInternal $partInternal)
    {
        try {
            DB::beginTransaction();
            
            $partInternal->delete();
            
            DB::commit();
            
            return redirect()
                ->route('part-internals.index')
                ->with('success', 'Part internal deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Failed to delete part internal: ' . $e->getMessage());
        }
    }
}