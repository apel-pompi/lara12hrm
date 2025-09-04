<?php

namespace App\Http\Controllers;

use App\Models\PartnerBranch;
use App\Http\Requests\PartnerBranch\StorePartnerBranchRequest;
use App\Http\Requests\PartnerBranch\UpdatePartnerBranchRequest;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PartnerBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('allpages/Agency/Setting/partnerbranch',[
            'partnerbranch' => PartnerBranch::with(['user','states.country','citys'])->orderBy('id','DESC')->get(),
            'countries' => Country::where('status',1)->get(['id','name','iso3','phonecode','currency','currency_symbol']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_name'       => 'required|string|max:255',
            'branch_email'      => 'required|email|unique:partner_branches,branch_email',
        ]);

        PartnerBranch::create([
            'branch_name' => $validated['branch_name'],
            'branch_email' => $validated['branch_email'],
            'branch_state_id' => $request->branch_state_id,
            'branch_city_id' => $request->branch_city_id,
            'branch_phoneno' => $request->branch_phonecode.' '. $request->branch_phoneno,
            'user_id' => Auth::id(),
            'active' => '0',
        ]);

        return redirect()->back()->with('success', 'Branch created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PartnerBranch $partnerBranch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartnerBranch $partnerBranch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartnerBranchRequest $request, PartnerBranch $partnerBranch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartnerBranch $partnerBranch)
    {
        //
    }

    public function updateStatus(Request $request, $PartnerBranch)
    {
        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $partnerbranch = PartnerBranch::findOrFail($PartnerBranch);
        $partnerbranch->update(['active' =>  $validated['active']]);
        
    }
}
