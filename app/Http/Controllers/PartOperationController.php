<?php

namespace App\Http\Controllers;

use App\Models\PartOperation;
use App\Models\PartInternal;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PartOperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = PartOperation::with(['partInternal', 'division']);

            // Filter by part internal
            if ($request->filled('part_internal_id')) {
                $query->where('part_internal_id', $request->part_internal_id);
            }

            // Filter by division
            if ($request->filled('division_id')) {
                $query->where('division_id', $request->division_id);
            }

            $partOperations = $query->orderBy('part_internal_id')
                                   ->orderBy('route_order')
                                   ->get();

            // Get data for filters
            $partInternals = PartInternal::orderBy('part_number')->get();
            $divisions = Division::orderBy('name')->get();

            return view('part-operations.index', compact('partOperations', 'partInternals', 'divisions'));
        } catch (Exception $e) {
            Log::error('Error fetching Part Operations: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load Part Operations');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $partInternals = PartInternal::orderBy('part_number')->get();
        $divisions = Division::orderBy('name')->get();

        return view('part-operations.create', compact('partInternals', 'divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_internal_id' => 'required|exists:part_internals,id',
            'division_id' => 'nullable|exists:divisions,id',
            'operation_data' => 'nullable|string',
            'route_order' => 'required|integer|min:1',
        ], [
            'part_internal_id.required' => 'Part Internal is required',
            'part_internal_id.exists' => 'Selected Part Internal does not exist',
            'division_id.exists' => 'Selected Division does not exist',
            'route_order.required' => 'Route Order is required',
            'route_order.min' => 'Route Order must be at least 1',
        ]);

        try {
            DB::beginTransaction();

            // Check if route order already exists for this part
            $existingRoute = PartOperation::where('part_internal_id', $validated['part_internal_id'])
                ->where('route_order', $validated['route_order'])
                ->first();

            if ($existingRoute) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Route order ' . $validated['route_order'] . ' already exists for this part. Please use a different route order.');
            }

            $partOperation = PartOperation::create($validated);

            DB::commit();

            return redirect()
                ->route('part-operations.index')
                ->with('success', 'Part Operation created successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating Part Operation: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create Part Operation: ' . $e->getMessage());
        }
    }

    /**
     * Store multiple operations at once (bulk create).
     */
    public function storeBulk(Request $request)
    {
        $request->validate([
            'part_internal_id' => 'required|exists:part_internals,id',
            'operations' => 'required|array|min:1',
            'operations.*.route_order' => 'required|integer|min:1',
            'operations.*.division_id' => 'nullable|exists:divisions,id',
            'operations.*.operation_data' => 'nullable|string',
        ], [
            'part_internal_id.required' => 'Part Internal is required',
            'operations.required' => 'At least one operation is required',
            'operations.min' => 'At least one operation is required',
            'operations.*.route_order.required' => 'Route Order is required for all operations',
            'operations.*.route_order.min' => 'Route Order must be at least 1',
        ]);

        try {
            DB::beginTransaction();

            $partId = $request->part_internal_id;
            $operations = $request->operations;
            $createdCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($operations as $index => $operationData) {
                // Check if route order already exists
                $existingRoute = PartOperation::where('part_internal_id', $partId)
                    ->where('route_order', $operationData['route_order'])
                    ->first();

                if ($existingRoute) {
                    $skippedCount++;
                    $errors[] = "Route order {$operationData['route_order']} already exists";
                    continue;
                }

                // Create operation
                PartOperation::create([
                    'part_internal_id' => $partId,
                    'division_id' => $operationData['division_id'] ?? null,
                    'operation_data' => $operationData['operation_data'] ?? null,
                    'route_order' => $operationData['route_order'],
                ]);

                $createdCount++;
            }

            DB::commit();

            $message = "Successfully created {$createdCount} operation(s)";
            if ($skippedCount > 0) {
                $message .= ". Skipped {$skippedCount} duplicate(s): " . implode(', ', $errors);
            }

            return redirect()
                ->route('part-operations.index')
                ->with('success', $message);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating Part Operations (bulk): ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create Part Operations: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PartOperation $partOperation)
    {
        $partOperation->load(['partInternal', 'division']);

        return view('part-operations.show', compact('partOperation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartOperation $partOperation)
    {
        $partOperation->load(['partInternal', 'division']);
        $partInternals = PartInternal::orderBy('part_number')->get();
        $divisions = Division::orderBy('name')->get();

        return view('part-operations.edit', compact('partOperation', 'partInternals', 'divisions'));
    }

    /**
     * Show the form for editing all operations for a specific part (bulk edit).
     */
    public function editBulk($partId)
    {
        $partInternal = PartInternal::findOrFail($partId);
        $operations = PartOperation::where('part_internal_id', $partId)
            ->with('division')
            ->orderBy('route_order')
            ->get();
        $divisions = Division::orderBy('name')->get();

        return view('part-operations.edit', compact('partInternal', 'operations', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PartOperation $partOperation)
    {
        $validated = $request->validate([
            'part_internal_id' => 'required|exists:part_internals,id',
            'division_id' => 'nullable|exists:divisions,id',
            'operation_data' => 'nullable|string',
            'route_order' => 'required|integer|min:1',
        ], [
            'part_internal_id.required' => 'Part Internal is required',
            'part_internal_id.exists' => 'Selected Part Internal does not exist',
            'division_id.exists' => 'Selected Division does not exist',
            'route_order.required' => 'Route Order is required',
            'route_order.min' => 'Route Order must be at least 1',
        ]);

        try {
            DB::beginTransaction();

            // Check if route order already exists for this part (exclude current operation)
            $existingRoute = PartOperation::where('part_internal_id', $validated['part_internal_id'])
                ->where('route_order', $validated['route_order'])
                ->where('id', '!=', $partOperation->id)
                ->first();

            if ($existingRoute) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Route order ' . $validated['route_order'] . ' already exists for this part. Please use a different route order.');
            }

            $partOperation->update($validated);

            DB::commit();

            return redirect()
                ->route('part-operations.index')
                ->with('success', 'Part Operation updated successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating Part Operation: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update Part Operation: ' . $e->getMessage());
        }
    }

    /**
     * Update multiple operations at once (bulk update).
     */
    public function updateBulk(Request $request, $partId)
    {
        $request->validate([
            'part_internal_id' => 'required|exists:part_internals,id',
            'existing' => 'nullable|array',
            'existing.*.id' => 'required|exists:part_operations,id',
            'existing.*.route_order' => 'required|integer|min:1',
            'existing.*.division_id' => 'nullable|exists:divisions,id',
            'existing.*.operation_data' => 'nullable|string',
            'new' => 'nullable|array',
            'new.*.route_order' => 'required|integer|min:1',
            'new.*.division_id' => 'nullable|exists:divisions,id',
            'new.*.operation_data' => 'nullable|string',
            'deleted' => 'nullable|array',
            'deleted.*' => 'exists:part_operations,id',
        ]);

        try {
            DB::beginTransaction();

            $updatedCount = 0;
            $createdCount = 0;
            $deletedCount = 0;

            // Update existing operations
            if ($request->has('existing')) {
                foreach ($request->existing as $id => $data) {
                    $operation = PartOperation::find($id);
                    if ($operation && $operation->part_internal_id == $partId) {
                        $operation->update([
                            'route_order' => $data['route_order'],
                            'division_id' => $data['division_id'] ?? null,
                            'operation_data' => $data['operation_data'] ?? null,
                        ]);
                        $updatedCount++;
                    }
                }
            }

            // Create new operations
            if ($request->has('new')) {
                foreach ($request->new as $data) {
                    PartOperation::create([
                        'part_internal_id' => $partId,
                        'route_order' => $data['route_order'],
                        'division_id' => $data['division_id'] ?? null,
                        'operation_data' => $data['operation_data'] ?? null,
                    ]);
                    $createdCount++;
                }
            }

            // Delete marked operations
            if ($request->has('deleted')) {
                foreach ($request->deleted as $id) {
                    $operation = PartOperation::find($id);
                    if ($operation && $operation->part_internal_id == $partId) {
                        $operation->delete();
                        $deletedCount++;
                    }
                }
            }

            DB::commit();

            $message = "Changes saved successfully";
            $details = [];
            if ($updatedCount > 0) $details[] = "{$updatedCount} updated";
            if ($createdCount > 0) $details[] = "{$createdCount} created";
            if ($deletedCount > 0) $details[] = "{$deletedCount} deleted";

            if (count($details) > 0) {
                $message .= ": " . implode(', ', $details);
            }

            return redirect()
                ->route('part-operations.index')
                ->with('success', $message);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating Part Operations (bulk): ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update Part Operations: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartOperation $partOperation)
    {
        try {
            DB::beginTransaction();

            $partOperation->delete();

            DB::commit();

            return redirect()
                ->route('part-operations.index')
                ->with('success', 'Part Operation deleted successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Part Operation: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to delete Part Operation: ' . $e->getMessage());
        }
    }

    /**
     * Get existing routes for a specific part (API endpoint)
     */
    public function getExistingRoutes($partId)
    {
        try {
            $operations = PartOperation::where('part_internal_id', $partId)
                ->with('division')
                ->orderBy('route_order')
                ->get()
                ->map(function($op) {
                    return [
                        'id' => $op->id,
                        'route_order' => $op->route_order,
                        'division_name' => $op->division ? $op->division->name : null
                    ];
                });

            return response()->json([
                'success' => true,
                'operations' => $operations
            ]);

        } catch (Exception $e) {
            Log::error('Error fetching existing routes: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch existing routes'
            ], 500);
        }
    }

    /**
     * Reorder operations for a specific part
     */
    public function reorder(Request $request, $partId)
    {
        $request->validate([
            'operations' => 'required|array',
            'operations.*.id' => 'required|exists:part_operations,id',
            'operations.*.route_order' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->operations as $operationData) {
                PartOperation::where('id', $operationData['id'])
                    ->where('part_internal_id', $partId)
                    ->update(['route_order' => $operationData['route_order']]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Operations reordered successfully'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error reordering operations: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder operations'
            ], 500);
        }
    }

    /**
     * Duplicate an operation
     */
    public function duplicate(PartOperation $partOperation)
    {
        try {
            DB::beginTransaction();

            // Get the max route order for this part
            $maxOrder = PartOperation::where('part_internal_id', $partOperation->part_internal_id)
                ->max('route_order');

            // Create duplicate with new route order
            $newOperation = $partOperation->replicate();
            $newOperation->route_order = $maxOrder + 1;
            $newOperation->save();

            DB::commit();

            return redirect()
                ->route('part-operations.edit', $newOperation->id)
                ->with('success', 'Operation duplicated successfully as route order ' . $newOperation->route_order);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating operation: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to duplicate operation');
        }
    }

    /**
     * Get operations summary by part
     */
    public function getSummary()
    {
        try {
            $summary = PartOperation::select('part_internal_id', DB::raw('COUNT(*) as operation_count'))
                ->with('partInternal')
                ->groupBy('part_internal_id')
                ->get()
                ->map(function($item) {
                    return [
                        'part_number' => $item->partInternal->part_number,
                        'part_name' => $item->partInternal->part_name,
                        'operation_count' => $item->operation_count
                    ];
                });

            return response()->json([
                'success' => true,
                'summary' => $summary
            ]);

        } catch (Exception $e) {
            Log::error('Error fetching operations summary: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch summary'
            ], 500);
        }
    }

    /**
     * Bulk delete operations
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:part_operations,id'
        ]);

        try {
            DB::beginTransaction();

            PartOperation::whereIn('id', $request->ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' operation(s) deleted successfully'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error bulk deleting operations: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete operations'
            ], 500);
        }
    }

    /**
     * Restore soft deleted operation
     */
    public function restore($id)
    {
        try {
            $partOperation = PartOperation::withTrashed()->findOrFail($id);
            $partOperation->restore();

            return redirect()
                ->route('part-operations.index')
                ->with('success', 'Part Operation restored successfully');

        } catch (Exception $e) {
            Log::error('Error restoring operation: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to restore operation');
        }
    }

    /**
     * Force delete operation
     */
    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();

            $partOperation = PartOperation::withTrashed()->findOrFail($id);
            $partOperation->forceDelete();

            DB::commit();

            return redirect()
                ->route('part-operations.index')
                ->with('success', 'Part Operation permanently deleted');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error force deleting operation: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to permanently delete operation');
        }
    }

    /**
     * Export operations to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = PartOperation::with(['partInternal', 'division']);

            // Apply same filters as index
            if ($request->filled('part_internal_id')) {
                $query->where('part_internal_id', $request->part_internal_id);
            }

            if ($request->filled('division_id')) {
                $query->where('division_id', $request->division_id);
            }

            $operations = $query->orderBy('part_internal_id')
                               ->orderBy('route_order')
                               ->get();

            $filename = 'part_operations_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($operations) {
                $file = fopen('php://output', 'w');

                // Header
                fputcsv($file, ['Route Order', 'Part Number', 'Part Name', 'Division', 'Operation Data', 'Created At']);

                // Data
                foreach ($operations as $op) {
                    fputcsv($file, [
                        $op->route_order,
                        $op->partInternal->part_number,
                        $op->partInternal->part_name,
                        $op->division ? $op->division->name : '-',
                        $op->operation_data ?? '-',
                        $op->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (Exception $e) {
            Log::error('Error exporting operations: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to export operations');
        }
    }

    /**
     * Get routing flow for a specific part
     */
    public function getRoutingFlow($partId)
    {
        try {
            $operations = PartOperation::where('part_internal_id', $partId)
                ->with(['division', 'partInternal'])
                ->orderBy('route_order')
                ->get()
                ->map(function($op) {
                    return [
                        'id' => $op->id,
                        'route_order' => $op->route_order,
                        'division' => $op->division ? [
                            'id' => $op->division->id,
                            'name' => $op->division->name
                        ] : null,
                        'has_operation_data' => !empty($op->operation_data)
                    ];
                });

            return response()->json([
                'success' => true,
                'part' => PartInternal::find($partId),
                'operations' => $operations
            ]);

        } catch (Exception $e) {
            Log::error('Error fetching routing flow: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch routing flow'
            ], 500);
        }
    }
}
