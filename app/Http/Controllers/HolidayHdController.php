<?php

namespace App\Http\Controllers;

use App\Models\HolidayHd;
use App\Http\Requests\Holidayhd\StoreHolidayHdRequest;
use App\Http\Requests\Holidayhd\UpdateHolidayHdRequest;
use App\Models\Branch;
use App\Models\HolidayDt;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HolidayHdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('allpages/holidayHd', [
            'holidayHd' => HolidayHd::with(['branch' => function ($query) {
                $query->where('active', 1);
            }])->get(),
            'branch' => Branch::where('active', 1)->get(),
            'year' => $this->createYear(),
            'month' => $this->createMonth(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHolidayHdRequest $request)
    {
        $validated = $request->validated();

        $exists = HolidayHd::where('branch_id', $validated['branch_id'])
            ->where('holiyear', $validated['holiyear'])
            ->where('holimonth', $validated['holimonth'])
            ->exists();

        if ($exists) {
            $message = 'Duplicate entry for this branch, year, and month.';
            return $request->inertia()
                ? back()->withErrors(['holidate' => $message])
                : redirect()->route('holidayHd.index')->with('error', $message);
        }

        HolidayHd::create($validated);

        return redirect()
            ->route('holidayHd.index')
            ->with('success', 'Holiday created successfully.');
    }

    public function updateStatus(Request $request, $holidayhd)
    {
        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $holiday = HolidayHd::findOrFail($holidayhd);
        $updated = $holiday->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('holidayHd.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }

    /**
     * Display the specified resource.
     */
    public function show(HolidayHd $holidayHd)
    {
        $holidayHd->load('branch');
        return response()->json($holidayHd);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HolidayHd $holidayHd)
    {
        return response()->json([
            'success' => true,
            'data' => $holidayHd,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHolidayHdRequest $request, HolidayHd $holidayHd)
    {
        $year  = (int) ($request->input('holiyear',  $holidayHd->holiyear));
        $month = (int) ($request->input('holimonth', $holidayHd->holimonth));
        $exists = HolidayDt::where('holihd_id', $holidayHd->id)
            ->whereYear('holidate', $year)
            ->whereMonth('holidate', $month)
            ->exists();

        
        if (! $exists) {
            $message = 'Invalid year and month. Check holiday details.';
            return $request->inertia()
                ? back()->withErrors(['holidate' => $message])
                : redirect()->route('holidayHd.index')->with('error', $message);
        }

        $holidayHd->update($request->validated());

        return redirect()->route('holidayHd.index')->with('success', 'Holiday Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HolidayHd $holidayHd)
    {
        try {
            $holidayHd->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete attendance setting.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createYear()
    {
        $a = array();
        for ($i = date('Y'); $i >= date('Y') - 5; $i--) {
            $a[$i] = $i;
        }
        return $a;
    }

    public function createMonth()
    {
        $a = array();
        for ($i = 1; $i <= 12; $i++) {
            $a[$i] = date("F", mktime(0, 0, 0, $i, $i));
        }
        return $a;
    }
}
