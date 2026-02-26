<?php

namespace App\Http\Controllers;

use App\Models\PartInternal;
use App\Models\PartOperation;
use App\Models\Division;
use App\Models\Area;
use App\Models\PartProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PartInternalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partInternals = PartInternal::with('PartOperations')->orderBy('created_at', 'desc')->paginate(20);
        return view('part-internals.index', compact('partInternals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisions = Division::orderBy('name')->get();

        // Get all areas grouped by division_id
        $areas = Area::orderBy('name')->get();
        $areasByDivision = $areas->groupBy('division_id')->map(function ($items) {
            return $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            });
        });

        return view('part-internals.create', compact('divisions', 'areasByDivision'));
    }

    /**
     * Store a newly created resource in storage along with its operations.
     */
    public function storeWithOperations(Request $request)
    {
        // Validate Part Internal data
        $validatedPart = $request->validate([
            'part_number' => 'required|string|max:255|unique:part_internals,part_number',
            'nointernal' => 'nullable|integer',
            'part_name' => 'nullable|string|max:255',
            'matl_spec' => 'nullable|string|max:255',
            'matl_req' => 'nullable|numeric',
            'comp_per_mould' => 'nullable|integer',
            'deoxidation' => 'nullable|string',
            'target_qty_waxing' => 'nullable|integer',
            'target_qty_mould_room' => 'nullable|integer',
            'target_qty_melting' => 'nullable|integer',
            'target_qty_heat_treatment' => 'nullable|integer',
            'target_qty_cut_off' => 'nullable|integer',
            'target_qty_finishing' => 'nullable|integer',
            'target_qty_machining' => 'nullable|integer',
            'target_qty_quality_control' => 'nullable|integer',
        ]);

        // Validate Operations data (including process fields)
        $request->validate([
            'operations' => 'required|array|min:1',
            'operations.*.route_order' => 'required|integer|min:1',
            'operations.*.division_id' => 'nullable|exists:divisions,id',
            'operations.*.area_id' => 'nullable|exists:areas,id',
            'operations.*.operation_data' => 'nullable|string',
            // Process fields
            'operations.*.process_order' => 'nullable|integer|min:1',
            'operations.*.capacity' => 'nullable|integer|min:0',
            'operations.*.duration' => 'nullable|integer|min:0',
            'operations.*.operator_count' => 'nullable|integer|min:0',
            'operations.*.equipment' => 'nullable|string|max:255',
            'operations.*.is_active' => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            // Create Part Internal
            $partInternal = PartInternal::create($validatedPart);

            // Get all operations from request
            $operations = $request->input('operations', []);

            $processCreatedCount = 0;

            // Create Operations and Processes
            foreach ($operations as $operationData) {
                // Create operation
                $operation = PartOperation::create([
                    'part_internal_id' => $partInternal->id,
                    'route_order' => $operationData['route_order'],
                    'division_id' => $operationData['division_id'] ?? null,
                    'area_id' => $operationData['area_id'] ?? null,
                    'operation_data' => $operationData['operation_data'] ?? null,
                ]);

                // Create process if area is selected
                if (!empty($operationData['area_id'])) {
                    $processData = [
                        'part_internal_id' => $partInternal->id,
                        'part_operation_id' => $operation->id,
                        'area_id' => $operationData['area_id'],
                        'process_order' => !empty($operationData['process_order']) ? $operationData['process_order'] : null,
                        'capacity' => !empty($operationData['capacity']) ? $operationData['capacity'] : null,
                        'duration' => !empty($operationData['duration']) ? $operationData['duration'] : null,
                        'operator_count' => !empty($operationData['operator_count']) ? $operationData['operator_count'] : null,
                        'equipment' => !empty($operationData['equipment']) ? $operationData['equipment'] : null,
                        'is_active' => true, // always true by default for new operations
                    ];

                    PartProcess::create($processData);
                    $processCreatedCount++;
                }
            }

            DB::commit();

            $operationsCount = count($operations);
            $message = "Part Internal created with {$operationsCount} operation(s)";
            if ($processCreatedCount > 0) {
                $message .= " and {$processCreatedCount} process(es)";
            }
            $message .= " successfully!";

            return redirect()->route('part-internals.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error for debugging
            Log::error('Failed to create part internal with operations', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create part: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(PartInternal $partInternal)
    {
        $partInternal->load('PartOperations.division', 'PartOperations.area');
        return view('part-internals.show', compact('partInternal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartInternal $partInternal)
    {
        $divisions = Division::orderBy('name')->get();

        // Get all areas grouped by division_id
        $areas = Area::orderBy('name')->get();
        $areasByDivision = $areas->groupBy('division_id')->map(function ($items) {
            return $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            });
        });

        $partInternal->load('PartOperations.process');

        return view('part-internals.edit', compact('partInternal', 'divisions', 'areasByDivision'));
    }

    /**
     * Update the specified resource in storage along with its operations.
     */
    public function updateWithOperations(Request $request, PartInternal $partInternal)
    {
        // Validate Part Internal data
        $validatedPart = $request->validate([
            'part_number' => 'required|string|max:255|unique:part_internals,part_number,' . $partInternal->id,
            'nointernal' => 'nullable|integer',
            'part_name' => 'nullable|string|max:255',
            'matl_spec' => 'nullable|string|max:255',
            'matl_req' => 'nullable|numeric',
            'comp_per_mould' => 'nullable|integer',
            'deoxidation' => 'nullable|string',
            'target_qty_waxing' => 'nullable|integer',
            'target_qty_mould_room' => 'nullable|integer',
            'target_qty_melting' => 'nullable|integer',
            'target_qty_heat_treatment' => 'nullable|integer',
            'target_qty_cut_off' => 'nullable|integer',
            'target_qty_finishing' => 'nullable|integer',
            'target_qty_machining' => 'nullable|integer',
            'target_qty_quality_control' => 'nullable|integer',
        ]);

        // Validate Operations data
        $validatedOperations = $request->validate([
            'operations' => 'nullable|array',
            'operations.*.id' => 'required|exists:part_operations,id',
            'operations.*.route_order' => 'required|integer|min:1',
            'operations.*.division_id' => 'nullable|exists:divisions,id',
            'operations.*.area_id' => 'nullable|exists:areas,id',
            'operations.*.operation_data' => 'nullable|string',
            'operations.*._delete' => 'nullable|in:0,1',
            // Process fields
            'operations.*.process_id' => 'nullable|exists:part_processes,id',
            'operations.*.process_order' => 'nullable|integer|min:1',
            'operations.*.capacity' => 'nullable|integer|min:0',
            'operations.*.duration' => 'nullable|integer|min:0',
            'operations.*.operator_count' => 'nullable|integer|min:0',
            'operations.*.equipment' => 'nullable|string|max:255',
            'operations.*.is_active' => 'nullable|in:1,0',

            'new_operations' => 'nullable|array',
            'new_operations.*.route_order' => 'required|integer|min:1',
            'new_operations.*.division_id' => 'nullable|exists:divisions,id',
            'new_operations.*.area_id' => 'nullable|exists:areas,id',
            'new_operations.*.operation_data' => 'nullable|string',
            // Process fields for new operations
            'new_operations.*.process_order' => 'nullable|integer|min:1',
            'new_operations.*.capacity' => 'nullable|integer|min:0',
            'new_operations.*.duration' => 'nullable|integer|min:0',
            'new_operations.*.operator_count' => 'nullable|integer|min:0',
            'new_operations.*.equipment' => 'nullable|string|max:255',
            'new_operations.*.is_active' => 'nullable|in:1,0',
        ]);

        DB::beginTransaction();

        try {
            // Update Part Internal
            $partInternal->update($validatedPart);

            // Handle existing operations (update or delete)
            if (isset($validatedOperations['operations'])) {
                foreach ($validatedOperations['operations'] as $operationData) {
                    $operation = PartOperation::find($operationData['id']);

                    if ($operation) {
                        // Check if marked for deletion
                        if (isset($operationData['_delete']) && $operationData['_delete'] == '1') {
                            // Delete process first (cascade will handle it, but explicit is better)
                            $operation->process()->delete();
                            $operation->delete();
                        } else {
                            // Update operation
                            $operation->update([
                                'route_order' => $operationData['route_order'],
                                'division_id' => $operationData['division_id'] ?? null,
                                'area_id' => $operationData['area_id'] ?? null,
                                'operation_data' => $operationData['operation_data'] ?? null,
                            ]);

                            // Handle process update/create
                            if (!empty($operationData['area_id'])) {
                                $processData = [
                                    'part_internal_id' => $partInternal->id,
                                    'part_operation_id' => $operation->id,
                                    'area_id' => $operationData['area_id'],
                                    'process_order' => $operationData['process_order'] ?? null,
                                    'capacity' => $operationData['capacity'] ?? null,
                                    'duration' => $operationData['duration'] ?? null,
                                    'operator_count' => $operationData['operator_count'] ?? null,
                                    'equipment' => $operationData['equipment'] ?? null,
                                    'is_active' => isset($operationData['is_active']) ? (bool)$operationData['is_active'] : true,
                                ];

                                if (!empty($operationData['process_id'])) {
                                    // Update existing process
                                    $process = PartProcess::find($operationData['process_id']);
                                    if ($process) {
                                        $process->update($processData);
                                    }
                                } else {
                                    // Create new process
                                    PartProcess::create($processData);
                                }
                            } else {
                                // If area is removed, delete the process
                                $operation->process()->delete();
                            }
                        }
                    }
                }
            }

            // Handle new operations
            if (isset($validatedOperations['new_operations'])) {
                foreach ($validatedOperations['new_operations'] as $newOperationData) {
                    $newOperation = PartOperation::create([
                        'part_internal_id' => $partInternal->id,
                        'route_order' => $newOperationData['route_order'],
                        'division_id' => $newOperationData['division_id'] ?? null,
                        'area_id' => $newOperationData['area_id'] ?? null,
                        'operation_data' => $newOperationData['operation_data'] ?? null,
                    ]);

                    // Create process if area is selected
                    if (!empty($newOperationData['area_id'])) {
                        PartProcess::create([
                            'part_internal_id' => $partInternal->id,
                            'part_operation_id' => $newOperation->id,
                            'area_id' => $newOperationData['area_id'],
                            'process_order' => $newOperationData['process_order'] ?? null,
                            'capacity' => $newOperationData['capacity'] ?? null,
                            'duration' => $newOperationData['duration'] ?? null,
                            'operator_count' => $newOperationData['operator_count'] ?? null,
                            'equipment' => $newOperationData['equipment'] ?? null,
                            'is_active' => isset($newOperationData['is_active']) ? (bool)$newOperationData['is_active'] : true,
                        ]);
                    }
                }
            }

            DB::commit();

            $totalNew = isset($validatedOperations['new_operations']) ? count($validatedOperations['new_operations']) : 0;
            $message = 'Part Internal updated successfully!';
            if ($totalNew > 0) {
                $message .= ' ' . $totalNew . ' new operation(s) added.';
            }

            return redirect()->route('part-internals.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update part: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage (simple update without operations).
     */
    public function update(Request $request, PartInternal $partInternal)
    {
        $validated = $request->validate([
            'part_number' => 'required|string|max:255|unique:part_internals,part_number,' . $partInternal->id,
            'nointernal' => 'nullable|integer',
            'part_name' => 'nullable|string|max:255',
            'matl_spec' => 'nullable|string|max:255',
            'matl_req' => 'nullable|numeric',
            'comp_per_mould' => 'nullable|integer',
            'deoxidation' => 'nullable|string',
            'target_qty_waxing' => 'nullable|integer',
            'target_qty_mould_room' => 'nullable|integer',
            'target_qty_melting' => 'nullable|integer',
            'target_qty_heat_treatment' => 'nullable|integer',
            'target_qty_cut_off' => 'nullable|integer',
            'target_qty_finishing' => 'nullable|integer',
            'target_qty_machining' => 'nullable|integer',
            'target_qty_quality_control' => 'nullable|integer',
        ]);

        $partInternal->update($validated);

        return redirect()->route('part-internals.index')
            ->with('success', 'Part Internal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartInternal $partInternal)
    {
        try {
            // Delete related operations first
            $partInternal->delete();

            return redirect()->route('part-internals.index')
                ->with('success', 'Part Internal and its operations deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('part-internals.index')
                ->with('error', 'Failed to delete part: ' . $e->getMessage());
        }
    }
}
