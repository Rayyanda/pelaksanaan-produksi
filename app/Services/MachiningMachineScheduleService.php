<?php

namespace app\Http\Services;

use app\Http\Repository\MachineRepository;

class MachiningMachineService {

    protected $machineRepository;

    public function __construct(MachineRepository $machineRepository)
    {
        $this->machineRepository = $machineRepository;
    }

    public function getMachiningMachine(){
        return $this->machineRepository->getAllMachines();
    }


}
