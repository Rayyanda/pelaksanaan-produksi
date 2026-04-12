<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\User;
use App\Services\MachineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class MachineController extends Controller
{
    protected $machineService;

    public function __construct(MachineService $machineService)
    {
        $this->machineService = $machineService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $machines = $this->machineService->getAllMachines();
        return view('machines.index', compact('machines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('is_active', true)->whereIn('role',['foreman','operator'])->get();
        return view('machines.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:machines,name',
                'model' => 'required|in:CNC,Manual',
                'pic_id' => 'nullable|exists:users,id',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'shift_capability' => 'required|in:1,2,3',
            ]);

            $machine = $this->machineService->createMachine($validated);

            return redirect()->route('machines.show', $machine->id)
                ->with('success', 'Machine created successfully.');
        } catch (Exception $e) {
            Log::error('Error creating machine: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create machine: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Machine $machine)
    {
        $machine = $this->machineService->getMachineById($machine->id);
        return view('machines.show', compact('machine'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Machine $machine)
    {
        $machine = $this->machineService->getMachineById($machine->id);
        $users = User::where('is_active', true)->where('role', 'foreman')->get();
        return view('machines.edit', compact('machine', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Machine $machine)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:machines,name,' . $machine->id,
                'model' => 'required|in:CNC,Manual',
                'pic_id' => 'nullable|exists:users,id',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive',
                'shift_capability' => 'required|in:1,2,3',
            ]);

            $machine = $this->machineService->updateMachine($machine->id, $validated);

            return redirect()->route('machines.show', $machine->id)
                ->with('success', 'Machine updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating machine: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update machine: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Machine $machine)
    {
        try {
            $this->machineService->deleteMachine($machine->id);
            return redirect()->route('machines.index')
                ->with('success', 'Machine deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting machine: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete machine: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
}
