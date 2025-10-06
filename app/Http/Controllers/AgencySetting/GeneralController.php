<?php

namespace App\Http\Controllers\AgencySetting;

use App\Http\Controllers\Controller;
use App\Models\AgencySetting\MasterCategory;
use App\Models\Partner\PartnerTypeSetup;
use App\Models\Product\ProductTypeSetup;
use App\Services\Agency\Setting\GeneralMaster;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GeneralController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, GeneralMaster $generalMaster)
    {
        $this->authorize('general.index');

        return Inertia::render('allpages/Agency/Setting/generalmaster', [

            'masterFillter' => MasterCategory::with('user')->orderBy('id', 'desc')->get(),
            'mastercategory' => $generalMaster->get($request->query()),
            'filters'   => $generalMaster->get($request->query()),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('general.store');

        $validated = $request->validate([
            'catname' => 'required|string',
            'active' => 'required|boolean',
        ]);

        MasterCategory::create([
            'catname' => $validated['catname'],
            'catadddate' => Date('Y-m-d'),
            'user_id' => Auth::id(),
            'active' => $validated['active'],
        ]);

        return redirect()->route('general.index')->with('success', 'Master Category Create successfully.');
    }

    public function show(MasterCategory $general)
    {
        $this->authorize('general.show');

        if (!$general) {
            return response()->json(['message' => 'Master Category not found'], 404);
        }
        return response()->json($general);
    }

    public function edit(MasterCategory $general)
    {
        $this->authorize('general.edit');

        return response()->json([
            'success' => true,
            'data' => $general,
        ]);
    }


    public function update(Request $request, MasterCategory $general)
    {
        $this->authorize('general.update');

        $validated = $request->validate([
            'catname' => 'required',
        ]);

        $general->update($validated);

        return redirect()
            ->route('general.index')
            ->with('success', 'Master Category updated successfully.');
    }

    public function updateStatus(Request $request, $general)
    {
        $this->authorize('general.updateStatus');

        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $general = MasterCategory::findOrFail($general);
        $updated = $general->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('general.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }

    public function destroy(MasterCategory $general)
    {
        $this->authorize('general.destroy');

        try {
            $general->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Master Category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function patnersetup()
    {
        $this->authorize('patnerSetting.index');

        return Inertia::render('allpages/Agency/Setting/partnertype', [
            'mastersetup' => MasterCategory::where('active', 1)->get(),
            'partnertype' => PartnerTypeSetup::with(['user', 'mastercategory'])->orderBy('id', 'desc')->get()
        ]);
    }

    public function patnersetupstore(Request $request)
    {
        $this->authorize('patnerSetting.store');

        $validated = $request->validate([
            'partnertypename' => 'required|string',
            'mastercaterory_id' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        PartnerTypeSetup::create([
            'partnertypename' => $validated['partnertypename'],
            'mastercaterory_id' => $validated['mastercaterory_id'],
            'user_id' => Auth::id(),
            'active' => $validated['active'],
        ]);

        return redirect()->route('general.patnersetup')->with('success', 'Partner Setup Create successfully.');
    }

    public function patnersetupUpdateStatus(Request $request, $patnersetup)
    {
        $this->authorize('patnerSetting.updateStatus');

        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $patnersetup = PartnerTypeSetup::findOrFail($patnersetup);
        $updated = $patnersetup->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('general.patnersetup')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }

    public function patnersetupdestroy(PartnerTypeSetup $patnersetup)
    {
        $this->authorize('patnerSetting.destroy');

        try {
            $patnersetup->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Product type.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function productsetup()
    {
        $this->authorize('productSetting.index');

        return Inertia::render('allpages/Agency/Setting/productsetup', [
            'mastersetup' => MasterCategory::where('active', 1)->get(),
            'productsetup' => ProductTypeSetup::with(['user', 'mastercategory'])->orderBy('id', 'DESC')->get(),
        ]);
    }

    public function productsetuppstore(Request $request)
    {
        $this->authorize('productSetting.store');

        $validated = $request->validate([
            'producttypename' => 'required|string',
            'mastercaterory_id' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        ProductTypeSetup::create([
            'producttypename' => $validated['producttypename'],
            'mastercaterory_id' => $validated['mastercaterory_id'],
            'user_id' => Auth::id(),
            'active' => $validated['active'],
        ]);

        return redirect()->route('general.productsetup')->with('success', 'Product Type Create successfully.');
    }

    public function producttypeUpdateStatus(Request $request, $productsetup)
    {
        $this->authorize('productSetting.updateStatus');

        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $productsetup = ProductTypeSetup::findOrFail($productsetup);
        $updated = $productsetup->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('general.productsetup')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }

    public function productsetupdestroy(ProductTypeSetup $productsetup)
    {
        $this->authorize('productSetting.destroy');

        try {
            $productsetup->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Product type.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
