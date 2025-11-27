<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partner\PartnerBranch;
use App\Http\Requests\PartnerBranch\StorePartnerBranchRequest;
use App\Http\Requests\PartnerBranch\UpdatePartnerBranchRequest;
use App\Models\Default\Country;
use App\Models\Partner\Partner;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PartnerBranchController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('partnerBranch.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        return Inertia::render('allpages/Agency/Setting/partnerbranch',[
            'partner' => Partner::where('active',1)->get(['id','name']),
            'partnerbranch' => PartnerBranch::with(['partner','user','states.country','citys'])->orderBy('id','DESC')->get(),
            'countries' => Country::where('status',1)->get(['id','name','iso3','phonecode','currency','currency_symbol']),
        ]);
    }

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePartnerBranchRequest $request)
    {
        try {
            $this->authorize('partnerBranch.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validated();
        $validated['active'] = $request->input('active', 0);

        PartnerBranch::create([
            'branch_name' => $validated['branch_name'],
            'branch_email' => $validated['branch_email'],
            'partner_id' => $validated['partner_id'],
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
        try {
            $this->authorize('partnerBranch.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartnerBranch $partnerBranch)
    {
        try {
            $this->authorize('partnerBranch.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartnerBranchRequest $request, PartnerBranch $partnerBranch)
    {
        try {
            $this->authorize('partnerBranch.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartnerBranch $partnerBranch)
    {
        try {
            $this->authorize('partnerBranch.destory');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

    }

    public function updateStatus(Request $request, $PartnerBranch)
    {
        try {
            $this->authorize('partnerBranch.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $partner = PartnerBranch::findOrFail($PartnerBranch);
        $updated = $partner->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('partner.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }

    
}
