<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\User;
use App\Models\Area;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $divisions = Division::withCount('areas')->paginate(10);
        return view('divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('is_active', true)->where('role', 'foreman')->get();
        return view('divisions.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:divisions,code',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'areas' => 'nullable|array',
            'areas.*.name' => 'required|string|max:255',
            'areas.*.foreman_id' => 'nullable|exists:users,id',
            'areas.*.max_operator' => 'nullable|integer|min:0',
            'areas.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $validated['is_active'] = $request->has('is_active') ? $validated['is_active'] : true;

            $division = Division::create([
                'name' => $validated['name'],
                'code' => $validated['code'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Create areas if provided
            if (!empty($validated['areas'])) {
                foreach ($validated['areas'] as $areaData) {
                    $division->areas()->create([
                        'name' => $areaData['name'],
                        'foreman_id' => $areaData['foreman_id'] ?? null,
                        'max_operator' => $areaData['max_operator'] ?? null,
                        'description' => $areaData['description'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('divisions.index')->with('success', 'Division created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating division: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create division: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $division = Division::with(['areas.foreman'])->findOrFail($id);
        return view('divisions.show', compact('division'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($divisionId)
    {
        $division = Division::with('areas')->findOrFail($divisionId);
        $users = User::where('is_active', true)->where('role', 'foreman')->get();
        return view('divisions.edit', compact('division', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:divisions,code,' . $division->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'areas' => 'nullable|array',
            'areas.*.id' => 'nullable|exists:areas,id',
            'areas.*.name' => 'required|string|max:255',
            'areas.*.foreman_id' => 'nullable|exists:users,id',
            'areas.*.max_operator' => 'nullable|integer|min:0',
            'areas.*.description' => 'nullable|string',
            'deleted_areas' => 'nullable|array',
            'deleted_areas.*' => 'exists:areas,id',
        ]);

        DB::beginTransaction();
        try {
            $validated['is_active'] = $request->has('is_active') ? $validated['is_active'] : true;

            $division->update([
                'name' => $validated['name'],
                'code' => $validated['code'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            // Handle deleted areas
            if (!empty($validated['deleted_areas'])) {
                Area::whereIn('id', $validated['deleted_areas'])
                    ->where('division_id', $division->id)
                    ->delete();
            }

            // Handle areas update/create
            if (!empty($validated['areas'])) {
                foreach ($validated['areas'] as $areaData) {
                    if (!empty($areaData['id'])) {
                        // Update existing area
                        $area = Area::where('id', $areaData['id'])
                            ->where('division_id', $division->id)
                            ->first();

                        if ($area) {
                            $area->update([
                                'name' => $areaData['name'],
                                'foreman_id' => $areaData['foreman_id'] ?? null,
                                'max_operator' => $areaData['max_operator'] ?? null,
                                'description' => $areaData['description'] ?? null,
                            ]);
                        }
                    } else {
                        // Create new area
                        $division->areas()->create([
                            'name' => $areaData['name'],
                            'foreman_id' => $areaData['foreman_id'] ?? null,
                            'max_operator' => $areaData['max_operator'] ?? null,
                            'description' => $areaData['description'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('divisions.index')->with('success', 'Division updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating division: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update division: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division)
    {
        try {
            if (!$division) {
                return redirect()->back()->with('error', 'Division not found');
            }

            $division->delete();
            return redirect()->route('divisions.index')->with('success', 'Division deleted successfully');
        } catch (Exception $e) {
            Log::error('Error deleting division: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete division');
        }
    }
}
