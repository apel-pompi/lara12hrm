<?php

namespace App\Http\Controllers\AgencySetting;

use App\Http\Controllers\Controller;
use App\Models\AgencySetting\MasterCategory;
use App\Models\Partner\PartnerTypeSetup;
use App\Models\Product\ProductTypeSetup;
use App\Services\Agency\Setting\GeneralMaster;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GeneralController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, GeneralMaster $generalMaster)
    {
        try {
            $this->authorize('general.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $perPage = $request->query('per_page', 10);
        return Inertia::render('allpages/Agency/Setting/generalmaster', [
            'masterFillter' => MasterCategory::with('user')->orderBy('id', 'desc')->get(),
            'filters'   => $generalMaster->get($request->query()),
            'mastercategory' => $generalMaster->get(array_merge($request->query(), ['per_page' => $perPage])),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('general.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

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
        try {
            $this->authorize('general.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        if (!$general) {
            return response()->json(['message' => 'Master Category not found'], 404);
        }
        return response()->json($general);
    }

    public function edit(MasterCategory $general)
    {
        try {
            $this->authorize('general.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        return response()->json([
            'success' => true,
            'data' => $general,
        ]);
    }


    public function update(Request $request, MasterCategory $general)
    {
        try {
            $this->authorize('general.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
        try {
            $this->authorize('general.status');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
        try {
            $this->authorize('general.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
        try {
            $this->authorize('patnerSetting.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        return Inertia::render('allpages/Agency/Setting/partnertype', [
            'mastersetup' => MasterCategory::where('active', 1)->get(),
            'partnertype' => PartnerTypeSetup::with(['user', 'mastercategory'])->orderBy('id', 'desc')->get()
        ]);
    }

    public function patnersetupstore(Request $request)
    {
        try {
            $this->authorize('patnerSetting.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
        try {
            $this->authorize('patnerSetting.status');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
        try {
            $this->authorize('patnerSetting.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
        try {
            $this->authorize('productSetting.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        return Inertia::render('allpages/Agency/Setting/productsetup', [
            'mastersetup' => MasterCategory::where('active', 1)->get(),
            'productsetup' => ProductTypeSetup::with(['user', 'mastercategory'])->orderBy('id', 'DESC')->get(),
        ]);
    }

    public function productsetuppstore(Request $request)
    {
        try {
            $this->authorize('productSetting.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
        try {
            $this->authorize('productSetting.status');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

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
        try {
            $this->authorize('productSetting.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
