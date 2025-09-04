<?php

namespace App\Http\Controllers;

use App\Models\MasterCategory;
use App\Models\PartnerTypeSetup;
use App\Models\ProductTypeSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GeneralController extends Controller
{
    public function index()
    {

        return Inertia::render('allpages/Agency/Setting/generalmaster', [
            'mastercategory' => MasterCategory::with('user')->orderBy('id', 'desc')->get()
        ]);
    }

    public function store(Request $request)
    {

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
        if (!$general) {
            return response()->json(['message' => 'Master Category not found'], 404);
        }
        return response()->json($general);
    }

    public function edit(MasterCategory $general)
    {
        return response()->json([
            'success' => true,
            'data' => $general,
        ]);
    }


    public function update(Request $request, MasterCategory $general)
    {
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
        return Inertia::render('allpages/Agency/Setting/partnertype', [
            'mastersetup' => MasterCategory::where('active', 1)->get(),
            'partnertype' => PartnerTypeSetup::with(['user', 'mastercategory'])->orderBy('id', 'desc')->get()
        ]);
    }

    public function patnersetupstore(Request $request)
    {

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

        return Inertia::render('allpages/Agency/Setting/productsetup', [
            'mastersetup' => MasterCategory::where('active', 1)->get(),
            'productsetup' => ProductTypeSetup::with(['user', 'mastercategory'])->orderBy('id', 'DESC')->get(),
        ]);
    }

    public function productsetuppstore(Request $request)
    {

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
            $productsetup->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Product type.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
