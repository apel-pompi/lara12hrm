<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;

use App\Models\Product\Product;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Partner\Partner;
use App\Models\Partner\PartnerBranch;
use App\Models\Product\ProductTypeSetup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        
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

        return redirect()
            ->route('product.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $product->update([
            'name'              => $validated['name'],
            'partner_id'        => $validated['partner_id'],
            'partner_branch_id' => $validated['partner_branch_id'],
            'partner_type_id'   => $validated['partner_type_id'],
            'revinue_type'      => $validated['revinue_type'],
            'duration'          => $validated['duration'],
            'intak_month'       => $validated['intak_month'],
            'description'       => $validated['description'],
            'note'              => $validated['note'] ?? null,
            'active'            => $validated['active'] ?? $product->active,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
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
