<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;

use App\Models\Product\Product;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Partner\Partner;
use App\Models\Partner\PartnerBranch;
use App\Models\Product\ProductTypeSetup;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('Product.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/Agency/Product/product', [
            'product' => Product::with(['partner','productype'])->get(),
            'partner' => Partner::where('active', 1)->get(),
            'partnerBrnach' => PartnerBranch::where('active', 1)->get(),
            'productType' => ProductTypeSetup::where('active', 1)->get(),
            'months' => collect($this->createMonth())
                ->map(fn($name, $id) => ['id' => $id, 'month' => $name])
                ->values()
                ->toArray(),
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $this->authorize('Product.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $validated = $request->validated();

        // Convert partner_branch_id array to comma-separated string
        if (isset($validated['partner_branch_id']) && is_array($validated['partner_branch_id'])) {
            $validated['partner_branch_id'] = implode(',', $validated['partner_branch_id']);
        }

        // Convert intak_month array to comma-separated string
        if (isset($validated['intak_month']) && is_array($validated['intak_month'])) {
            $validated['intak_month'] = implode(',', $validated['intak_month']);
        }

        $product = Product::create([
            'name'              => $validated['name'],
            'partner_id'        => $validated['partner_id'],
            'partner_branch_id' => $validated['partner_branch_id'] ?? null,
            'product_type_id'   => $validated['product_type_id'],
            'revinue_type'      => $validated['revinue_type'],
            'duration'          => $validated['duration'],
            'intak_month'       => $validated['intak_month'],
            'description'       => $validated['description'] ?? null,
            'note'              => $validated['note'] ?? null,
            'user_id'           => Auth::id(), // logged-in user
            'active'            => $validated['active'] ?? 0,
        ]);

        return back()
            ->with('success', 'Product created successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        try {
            $this->authorize('Product.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


         return response()->json([
            'success' => true,
            'data' => $product,
            'partner' => Partner::where('active', 1)->get(),
            'product' => ProductTypeSetup::where('active', 1)->get(),
            'months' => collect($this->createMonth())
                ->map(fn($name, $id) => ['id' => $id, 'month' => $name])
                ->values()
                ->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $this->authorize('Product.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        if (isset($request['intake_months']) && is_array($request['intake_months'])) {
            $request['intake_months'] = implode(',', $request['intake_months']);
        }

        $product->update([
            'name'              => $request['name'],
            'partner_id'        => $request['partner_id'],
            'partner_branch_id' => $request['partner_branch_id'] ?? $product->partner_branch_id,
            'partner_type_id'   => $request['product_type_id'],
            'revinue_type'      => $request['revinue_type'],
            'duration'          => $request['duration'],
            'intak_month'       => $request['intake_months'],
            'description'       => $request['description'],
            'note'              => $request['note'] ?? null,
            'active'            => $request['active'] ?? $product->active,
        ]);

        return redirect()
            ->route('productActivities.application',$product->id)
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $this->authorize('Product.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        try {
            
            $product->delete();
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete designation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $product)
    {
        try {
            $this->authorize('Product.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $product = Product::findOrFail($product);
        $updated = $product->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('product.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
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
