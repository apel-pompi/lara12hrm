<?php

namespace App\Http\Controllers\Partner;

use App\Models\Default\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerBranch\StorePartnerBranchRequest;
use App\Models\Partner\Partner;
use App\Models\Partner\PartnerBranch;
use App\Models\Product\Product;
use App\Models\Product\ProductTypeSetup;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PartnerActivities extends Controller
{
    use AuthorizesRequests;

    public function aplication(Partner $partner)
    {
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/partnerlayout', [
            'partner' => $part
        ]);
    }

    public function product(Partner $partner)
    {
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/product', [
            'partner' => $part,
            'product' => Product::with(['productype', 'partner'])->where('partner_id', $partner->id)->get(),
            'partnerBrnach' => PartnerBranch::where('active', 1)->get(),
            'productType' => ProductTypeSetup::where('active', 1)->get(),
            'months' => collect($this->createMonth())
                ->map(fn($name, $id) => ['id' => $id, 'month' => $name])
                ->values()
                ->toArray(),
        ]);
    }

    public function branch(Partner $partner)
    {
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/branch', [
            'partner' => $part,
            'partnerbranch' => PartnerBranch::with(['partner', 'user', 'states.country', 'citys'])->where('partner_id', $partner->id)->orderBy('id', 'DESC')->get(),
            'countries' => Country::where('status', 1)->get(['id', 'name', 'iso3', 'phonecode', 'currency', 'currency_symbol']),
        ]);
    }

    public function branchStore(Partner $partner, StorePartnerBranchRequest $request)
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
        try {
            $this->authorize('partnerBranch.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/aggrements', [
            'partner' => $part
        ]);
    }
    public function contacts(Partner $partner)
    {
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/contacts', [
            'partner' => $part
        ]);
    }
    public function notes(Partner $partner)
    {
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/notes', [
            'partner' => $part
        ]);
    }
    public function documents(Partner $partner)
    {
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/documents', [
            'partner' => $part
        ]);
    }
    public function appoinments(Partner $partner)
    {
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/appoinments', [
            'partner' => $part
        ]);
    }
    public function accounts(Partner $partner)
    {
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/accounts', [
            'partner' => $part
        ]);
    }
    public function conversations(Partner $partner)
    {
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/conversations', [
            'partner' => $part
        ]);
    }
    public function tasks(Partner $partner)
    {
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/tasks', [
            'partner' => $part
        ]);
    }
    public function others(Partner $partner)
    {
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/others', [
            'partner' => $part
        ]);
    }
    public function promotions(Partner $partner)
    {
        $part = Partner::with(['user'])->where('id',$partner->id)->first();
        return Inertia::render('allpages/Agency/Partner/promotions', [
            'partner' => $part
        ]);
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
