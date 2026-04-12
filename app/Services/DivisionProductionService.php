<?php

namespace app\Services;

use App\Models\WipTracking;

class DivisionProductionService {

    public function dashboard($slug)
    {

    }

    public function getHistoryWips($div_id)
    {
        $historyWips = WipTracking::with(['partInternal', 'partOperation', 'batch'])
                ->whereHas('partOperation', function($q) use ($div_id) {
                    $q->where('division_id', $div_id);
                })
                ->where('status', 'completed')
                ->orderBy('finished_at', 'desc')
                ->limit(50) // Last 50 completed
                ->get();
        return $historyWips;

    }

}
