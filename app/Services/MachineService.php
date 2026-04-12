<?php

namespace App\Services;

use App\Http\Repository\MachineRepository;
use Exception;

class MachineService
{
    protected $machineRepository;

    public function __construct(MachineRepository $machineRepository)
    {
        $this->machineRepository = $machineRepository;
    }

    /**
     * Get all machines with pagination
     */
    public function getAllMachines($perPage = 10)
    {
        return $this->machineRepository->getAllMachines($perPage);
    }

    /**
     * Get machine by ID
     */
    public function getMachineById($id)
    {
        return $this->machineRepository->getMachineById($id);
    }

    /**
     * Get active machines
     */
    public function getActiveMachines()
    {
        return $this->machineRepository->getActiveMachines();
    }

    /**
     * Get machines by model type
     */
    public function getMachinesByModel($model)
    {
        if (!in_array($model, ['CNC', 'Manual'])) {
            throw new Exception('Invalid machine model: ' . $model);
        }
        return $this->machineRepository->getMachinesByModel($model);
    }

    /**
     * Create a new machine
     */
    public function createMachine(array $data)
    {
        // Validate required fields
        if (empty($data['name']) || empty($data['model'])) {
            throw new Exception('Machine name and model are required');
        }

        return $this->machineRepository->createMachine($data);
    }

    /**
     * Update machine
     */
    public function updateMachine($id, array $data)
    {
        $machine = $this->machineRepository->getMachineById($id);

        if (!$machine) {
            throw new Exception('Machine not found');
        }

        return $this->machineRepository->updateMachine($id, $data);
    }

    /**
     * Delete machine (soft delete)
     */
    public function deleteMachine($id)
    {
        $machine = $this->machineRepository->getMachineById($id);

        if (!$machine) {
            throw new Exception('Machine not found');
        }

        return $this->machineRepository->deleteMachine($id);
    }

    /**
     * Get deleted machines
     */
    public function getDeletedMachines()
    {
        return $this->machineRepository->getDeletedMachines();
    }

    /**
     * Restore deleted machine
     */
    public function restoreMachine($id)
    {
        return $this->machineRepository->restoreMachine($id);
    }

    /**
     * Get machine statistics
     */
    public function getMachineStatistics()
    {
        $activeMachines = $this->machineRepository->getActiveMachines();
        $cncMachines = $this->machineRepository->getMachinesByModel('CNC');
        $manualMachines = $this->machineRepository->getMachinesByModel('Manual');

        return [
            'total' => $activeMachines->count(),
            'active' => $activeMachines->count(),
            'cnc' => $cncMachines->count(),
            'manual' => $manualMachines->count(),
        ];
    }
}
