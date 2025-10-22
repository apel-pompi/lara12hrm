<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;

use App\Models\Partner\Partner;
use App\Http\Requests\Partner\StorePartnerRequest;
use App\Http\Requests\Partner\UpdatePartnerRequest;
use App\Models\AgencySetting\MasterCategory;
use App\Models\AgencySetting\Workflow;
use App\Models\Default\Country;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PartnerController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $this->authorize('Partner.index');

        return Inertia::render('allpages/Agency/Partner/partners',[
            'pertners' => Partner::with(['partnertype','state.country','city'])->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('Partner.create');

        return Inertia::render('allpages/Agency/Partner/partnerscreate', [
            'master' => MasterCategory::with(['parnerTypes'])->where('active', 1)->get(),
            'workflow' => Workflow::where('active', 1)->get(),
            'countries' => Country::where('status', 1)->get(['id', 'name', 'iso3', 'phonecode', 'currency', 'currency_symbol']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePartnerRequest $request)
    {
        $this->authorize('Partner.store');

        $validated = $request->validated();

        $validated['active'] = $request->input('active', 0);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $file_name = time() . '_' . $file->getClientOriginalName();
            // Save to storage/app/public/partner
            $file->storeAs('partner', $file_name, 'public');
            $validated['photo'] = $file_name;
        }

        // Convert workflow_id array to comma-separated string
        if (isset($validated['workflow_id']) && is_array($validated['workflow_id'])) {
            $validated['workflow_id'] = implode(',', $validated['workflow_id']);
        }

        // Create Partner
        Partner::create([
            'name'              => $validated['name'],
            'workflow_id'       => $validated['workflow_id'] ?? null,
            'master_cat_id'     => $validated['master_cat_id'],
            'partner_type_id'   => $validated['partner_type_id'],
            'state_id'          => $validated['state_id'],
            'city_id'           => $validated['city_id'],
            'brn'               => $validated['brn'] ?? null,
            'currency'          => $validated['currency'] ?? null,
            'phone'             => trim(($validated['phone_code'] ?? '') . ' ' . ($validated['phoneno'] ?? '')),
            'email'             => $validated['email'],
            'fax'               => $validated['fax'] ?? null,
            'website'           => $validated['website'] ?? null,
            'photo'             => $validated['photo'] ?? null,
            'user_id'           => Auth::id(),
            'active'            => '0',
        ]);

        return redirect()
            ->route('partner.index')
            ->with('success', 'Partner created successfully.');
    }

   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partner $partner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartnerRequest $request, Partner $partner)
    {
        $this->authorize('Partner.update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner)
    {
         $this->authorize('Partner.destroy');
    }


    public function updateStatus(Request $request, $partner)
    {

        $this->authorize('Partner.updateStatus');

        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $partner = Partner::findOrFail($partner);
        $updated = $partner->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('partner.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }
}
