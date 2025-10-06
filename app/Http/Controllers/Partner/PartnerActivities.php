<?php

namespace App\Http\Controllers\Partner;

use App\Models\Default\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerBranch\StorePartnerBranchRequest;
use App\Models\Partner\Partner;
use App\Models\Partner\PartnerBranch;
use App\Models\Product\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PartnerActivities extends Controller
{
    use AuthorizesRequests;

    public function aplication(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/partnerlayout', [
            'partner' => $partner
        ]);
    }

    public function product(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/product', [
            'partner' => $partner,
            'product' => Product::with(['productype', 'partner'])->where('partner_id', $partner->id)->get()
        ]);
    }

    public function branch(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/branch', [
            'partner' => $partner,
            'partnerbranch' => PartnerBranch::with(['partner', 'user', 'states.country', 'citys'])->where('partner_id', $partner->id)->orderBy('id', 'DESC')->get(),
            'countries' => Country::where('status', 1)->get(['id', 'name', 'iso3', 'phonecode', 'currency', 'currency_symbol']),
        ]);
    }

    public function branchStore(Partner $partner, StorePartnerBranchRequest $request)
    {

        $this->authorize('partnerBranch.store');

        $validated = $request->validated();
        $validated['active'] = $request->input('active', 0);

        PartnerBranch::create([
            'branch_name' => $validated['branch_name'],
            'branch_email' => $validated['branch_email'],
            'partner_id' => $partner->id,
            'branch_state_id' => $validated['branch_state_id'],
            'branch_city_id' => $validated['branch_city_id'],
            'branch_phoneno' => $request->branch_phonecode . ' ' . $request->branch_phoneno,
            'user_id' => Auth::id(),
            'active' => '1',
        ]);

        return redirect()->back()->with('success', 'Branch created successfully!');
    }

    public function branchDelete(Partner $partner, PartnerBranch $partnerBranch)
    {

        $this->authorize('partnerBranch.destroy');

        try {
            $partnerBranch->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Partner Branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function aggrements(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/aggrements', [
            'partner' => $partner
        ]);
    }
    public function contacts(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/contacts', [
            'partner' => $partner
        ]);
    }
    public function notes(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/notes', [
            'partner' => $partner
        ]);
    }
    public function documents(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/documents', [
            'partner' => $partner
        ]);
    }
    public function appoinments(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/appoinments', [
            'partner' => $partner
        ]);
    }
    public function accounts(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/accounts', [
            'partner' => $partner
        ]);
    }
    public function conversations(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/conversations', [
            'partner' => $partner
        ]);
    }
    public function tasks(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/tasks', [
            'partner' => $partner
        ]);
    }
    public function others(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/others', [
            'partner' => $partner
        ]);
    }
    public function promotions(Partner $partner)
    {
        return Inertia::render('allpages/Agency/Partner/promotions', [
            'partner' => $partner
        ]);
    }
}
