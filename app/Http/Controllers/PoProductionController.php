<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PoProduction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;


class PoProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $poProductions = PoProduction::orderBy('created_at', 'desc')->get();

            return view('po-productions.index', compact('poProductions'));
        } catch (Exception $e) {
            Log::error('Error fetching PO Productions: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load PO Productions');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('po-productions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|max:255|unique:po_productions,po_number',
            'quantity' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
            'po_source' => 'nullable|url|max:500',
            'po_snapshot' => 'nullable|json',
        ], [
            'po_number.required' => 'PO Number is required',
            'po_number.unique' => 'PO Number already exists',
            'quantity.required' => 'Quantity is required',
            'quantity.min' => 'Quantity must be at least 1',
            'po_source.url' => 'PO Source must be a valid URL',
            'po_snapshot.json' => 'PO Snapshot must be valid JSON format',
        ]);

        try {
            DB::beginTransaction();

            // If po_source is provided and no snapshot, try to fetch from source
            if (!empty($validated['po_source']) && empty($validated['po_snapshot'])) {
                try {
                    $snapshotData = $this->fetchPoDataFromSource($validated['po_source']);
                    if ($snapshotData) {
                        $validated['po_snapshot'] = json_encode($snapshotData);
                    }
                } catch (Exception $e) {
                    Log::warning('Failed to fetch PO snapshot from source: ' . $e->getMessage());
                    // Continue without snapshot
                }
            }

            $poProduction = PoProduction::create($validated);

            DB::commit();

            return redirect()
                ->route('po-productions.index')
                ->with('success', 'PO Production created successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating PO Production: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create PO Production: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PoProduction $poProduction)
    {
        return view('po-productions.show', compact('poProduction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PoProduction $poProduction)
    {
        return view('po-productions.edit', compact('poProduction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PoProduction $poProduction)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|max:255|unique:po_productions,po_number,' . $poProduction->id,
            'quantity' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
            'po_source' => 'nullable|url|max:500',
            'po_snapshot' => 'nullable|json',
        ], [
            'po_number.required' => 'PO Number is required',
            'po_number.unique' => 'PO Number already exists',
            'quantity.required' => 'Quantity is required',
            'quantity.min' => 'Quantity must be at least 1',
            'po_source.url' => 'PO Source must be a valid URL',
            'po_snapshot.json' => 'PO Snapshot must be valid JSON format',
        ]);

        try {
            DB::beginTransaction();

            $poProduction->update($validated);

            DB::commit();

            return redirect()
                ->route('po-productions.index')
                ->with('success', 'PO Production updated successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating PO Production: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update PO Production: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PoProduction $poProduction)
    {
        try {
            DB::beginTransaction();

            $poProduction->delete();

            DB::commit();

            return redirect()
                ->route('po-productions.index')
                ->with('success', 'PO Production deleted successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting PO Production: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to delete PO Production: ' . $e->getMessage());
        }
    }

    /**
     * Refresh snapshot data from PO source
     */
    public function refreshSnapshot(PoProduction $poProduction)
    {
        if (empty($poProduction->po_source)) {
            return response()->json([
                'success' => false,
                'message' => 'PO Source is not configured'
            ], 400);
        }

        try {
            $snapshotData = $this->fetchPoDataFromSource($poProduction->po_source);

            if ($snapshotData) {
                $poProduction->update([
                    'po_snapshot' => json_encode($snapshotData)
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Snapshot refreshed successfully',
                    'data' => $snapshotData
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No data returned from source'
            ], 400);

        } catch (Exception $e) {
            Log::error('Error refreshing PO snapshot: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh snapshot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch PO data from external source
     *
     * @param string $sourceUrl
     * @return array|null
     */
    private function fetchPoDataFromSource($sourceUrl)
    {
        try {
            // Set timeout untuk request
            $response = Http::timeout(30)->get($sourceUrl);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Failed to fetch PO data from source. Status: ' . $response->status());
            return null;

        } catch (Exception $e) {
            Log::error('Error fetching PO data from source: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get PO snapshot data (for API/AJAX)
     */
    public function getSnapshot(PoProduction $poProduction)
    {
        if (empty($poProduction->po_snapshot)) {
            return response()->json([
                'success' => false,
                'message' => 'No snapshot data available'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => json_decode((string)$poProduction->po_snapshot)
        ]);
    }

    /**
     * Bulk delete PO Productions
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:po_productions,id'
        ]);

        try {
            DB::beginTransaction();

            PoProduction::whereIn('id', $request->ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' PO Production(s) deleted successfully'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error bulk deleting PO Productions: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete PO Productions'
            ], 500);
        }
    }

    /**
     * Restore soft deleted PO Production
     */
    public function restore($id)
    {
        try {
            $poProduction = PoProduction::withTrashed()->findOrFail($id);
            $poProduction->restore();

            return redirect()
                ->route('po-productions.index')
                ->with('success', 'PO Production restored successfully');

        } catch (Exception $e) {
            Log::error('Error restoring PO Production: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to restore PO Production');
        }
    }

    /**
     * Force delete PO Production
     */
    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();

            $poProduction = PoProduction::withTrashed()->findOrFail($id);
            $poProduction->forceDelete();

            DB::commit();

            return redirect()
                ->route('po-productions.index')
                ->with('success', 'PO Production permanently deleted');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error force deleting PO Production: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to permanently delete PO Production');
        }
    }

    /**
     * Export PO Productions to CSV
     */
    public function export()
    {
        try {
            $poProductions = PoProduction::all();

            $filename = 'po_productions_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($poProductions) {
                $file = fopen('php://output', 'w');

                // Header
                fputcsv($file, ['PO Number', 'Quantity', 'Due Date', 'PO Source', 'Created At']);

                // Data
                foreach ($poProductions as $po) {
                    fputcsv($file, [
                        $po->po_number,
                        $po->quantity,
                        $po->due_date,
                        $po->po_source,
                        $po->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (Exception $e) {
            Log::error('Error exporting PO Productions: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Failed to export PO Productions');
        }
    }

    public function getDetail($poId)
    {
        $data = PoProduction::findOrFail($poId);
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
