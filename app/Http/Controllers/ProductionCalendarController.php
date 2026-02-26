<?php

namespace App\Http\Controllers;

use App\Models\ProductionCalendar;
use Illuminate\Http\Request;

class ProductionCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get paginated calendars for table
        $calendars = ProductionCalendar::orderBy('start_date', 'desc')->paginate(10);

        // Get all calendars for FullCalendar (without pagination)
        $allCalendars = ProductionCalendar::all();

        // Format data for FullCalendar
        $calendarEvents = $allCalendars->map(function ($calendar) {
            return [
                'id' => $calendar->id,
                'title' => $calendar->activity,
                'start' => $calendar->start_date->format('Y-m-d'),
                'end' => $calendar->end_date->addDay()->format('Y-m-d'), // FullCalendar end date is exclusive
                'day_type' => $calendar->day_type,
                'backgroundColor' => $calendar->day_type === 'holiday' ? '#0dcaf0' : '#ffc107',
                'borderColor' => $calendar->day_type === 'holiday' ? '#0dcaf0' : '#ffc107',
                'textColor' => $calendar->day_type === 'mass leave' ? '#000' : '#fff',
            ];
        });

        return view('production_calendars.index', compact('calendars', 'calendarEvents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('production_calendars.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'activity' => 'required|string|max:255',
            'day_type' => 'required|in:mass leave,holiday',
        ]);

        ProductionCalendar::create($validated);

        return redirect()->route('production_calendars.index')
            ->with('success', 'Production calendar created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionCalendar $productionCalendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductionCalendar $productionCalendar)
    {
        return view('production_calendars.edit', compact('productionCalendar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductionCalendar $productionCalendar)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'activity' => 'required|string|max:255',
            'day_type' => 'required|in:mass leave,holiday',
        ]);

        $productionCalendar->update($validated);

        return redirect()->route('production_calendars.index')
            ->with('success', 'Production calendar updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionCalendar $productionCalendar)
    {
        $productionCalendar->delete();

        return redirect()->route('production_calendars.index')
            ->with('success', 'Production calendar deleted successfully.');
    }
}
