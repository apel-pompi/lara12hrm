<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HolidayDt;
use App\Http\Requests\Holidaydt\StoreHolidayDtRequest;
use App\Http\Requests\Holidaydt\UpdateHolidayDtRequest;
use App\Models\HRM\Branch;
use App\Models\HRM\HolidayHd;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class HolidayDtController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $this->authorize('holidaydt.create');

        return Inertia::render('allpages/hrm/holidayDt', [
            'holidayHd' => HolidayHd::with(['branch' => function ($query) {
                $query->where('active', 1);
            }])->findOrFail($id),

            'holidaydt' => HolidayDt::where('holihd_id', $id)->get(),
            'branch' => Branch::where('active', 1)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHolidayDtRequest $request)
    {
        $this->authorize('holidaydt.store');
        
        $holidayHd = HolidayHd::findOrFail($request->holihd_id);
        $holidays = $holidayHd->holidays;
        $year = $holidayHd->yearname;
        $month = $holidayHd->monthname;
        $request_year = date("Y", strtotime($request->holidate));
        $request_month = date("m", strtotime($request->holidate));

        $count_holiday_details = HolidayDt::where('holihd_id', $request->holihd_id)->count();
        //Check if holiday already exists for this date
        $exists = HolidayDt::where('holihd_id', $request->holihd_id)
            ->where('holidate', $request->holidate)
            ->exists();
            
        if ($exists) {
            $message = 'Date already exists.';
            return $request->inertia()
                ? back()->withErrors(['holidate' => $message])
                : redirect()->route('holidaydt.create', $request->holihd_id)->with('error', $message);
        }

        if ($year == $request_year && $month == $request_month) {
            if ($count_holiday_details < $holidays) {
                HolidayDt::create($request->validated());
            } else {
                $message = $count_holiday_details . ' Holidays already exist.';
                return $request->inertia()
                    ? back()->withErrors(['holidate' => $message])
                    : redirect()->route('holidaydt.create', $request->holihd_id)->with('error', $message);
            }
        } else {
            if ($request->inertia()) {
                return back()->withErrors(['holidate' => 'Date is not valid.']);
            }
            return redirect()->route('holidaydt.create', $request->holihd_id)
                ->with('error', 'Date is not valid.');
        }

        if ($request->inertia()) {
            // This tells Inertia to reload only the holiday_dts prop
            return redirect()->route('holidaydt.create', $request->holihd_id)
                ->with('success', 'Holiday Details created successfully.');
        }
        return redirect()->route('holidaydt.create', $request->holihd_id)
            ->with('success', 'Holiday Details created successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HolidayDt $holidayDt)
    {
        $this->authorize('holidaydt.edit');

        return response()->json([
            'success' => true,
            'data' => $holidayDt,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHolidayDtRequest $request, HolidayDt $holidayDt)
    {
        $this->authorize('holidaydt.update');

        $exists = HolidayDt::where('holihd_id', $request->holihd_id)
            ->where('holidate', $request->holidate)
            ->exists();

        if ($exists) {
            $message = 'Date already exists.';
            return $request->inertia()
                ? back()->withErrors(['holidate' => $message])
                : redirect()->route('holidaydt.create', $request->holihd_id)->with('error', $message);
        }

        $holidayDt->update($request->validated());
        return redirect()->route('holidaydt.create', $request->holihd_id)->with('success', 'Holiday Details Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HolidayDt $holidayDt)
    {
        $this->authorize('holidaydt.destroy');

        try {
            $holidayDt->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete attendance setting.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
