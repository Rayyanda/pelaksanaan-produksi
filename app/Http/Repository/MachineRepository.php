<?php

namespace App\Http\Repository;

use App\Models\Machine;

class MachineRepository
{
    /**
     * Get all machines with pagination
     */
    public function getAllMachines($perPage = 10)
    {
        return Machine::with(['pic'])
            ->paginate($perPage);
    }

    /**
     * Get machine by ID with relations
     */
    public function getMachineById($id)
    {
        return Machine::with(['pic'])->findOrFail($id);
    }

    /**
     * Get active machines
     */
    public function getActiveMachines()
    {
        return Machine::where('status', 'active')
            ->with(['pic'])
            ->get();
    }

    /**
     * Get machines by model type
     */
    public function getMachinesByModel($model)
    {
        return Machine::where('model', $model)
            ->with(['pic'])
            ->get();
    }

    /**
     * Create a new machine
     */
    public function createMachine(array $data)
    {
        return Machine::create($data);
    }

    /**
     * Update machine
     */
    public function updateMachine($id, array $data)
    {
        $machine = Machine::findOrFail($id);
        $machine->update($data);
        return $machine;
    }

    /**
     * Delete machine (soft delete)
     */
    public function deleteMachine($id)
    {
        $machine = Machine::findOrFail($id);
        $machine->delete();
        return true;
    }

    /**
     * Get deleted machines
     */
    public function getDeletedMachines()
    {
        return Machine::onlyTrashed()->get();
    }

    /**
     * Restore deleted machine
     */
    public function restoreMachine($id)
    {
        $machine = Machine::onlyTrashed()->findOrFail($id);
        $machine->restore();
        return $machine;
    }
}
