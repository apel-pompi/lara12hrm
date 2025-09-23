<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFeeHd\StoreProductFeesHdRequest;
use App\Models\Country;
use App\Models\Default\Fees;
use App\Models\Default\Installment;
use App\Models\Product\Product;
use App\Models\Product\ProductFeesDt;
use App\Models\Product\ProductFeesHd;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductActivities extends Controller
{
    public function aplication(Product $product)
    {
        return Inertia::render('allpages/Agency/Product/productlayout', [
            'product' => $product
        ]);
    }

    public function documents(Product $product)
    {
        return Inertia::render('allpages/Agency/Product/documents', [
            'product' => $product,

        ]);
    }

    public function fees(Product $product)
    {
        return Inertia::render('allpages/Agency/Product/fees', [
            'product' => $product,
            'country' => Country::where('status', 1)->get(),
            'instype' => Installment::where('active', 1)->get(),
            'feestype' => Fees::where('active', 1)->get(),
            'feesDt'   => ProductFeesHd::with(['details.fees','installment'])->where('product_id',$product->id)->orderBy('id','DESC')->get()
        ]);
    }

    public function storefess(StoreProductFeesHdRequest $request, Product $product)
    {
        $validated = $request->validated();
        $countryString = is_array($validated['country_id'])
            ? implode(',', $validated['country_id'])
            : $validated['country_id'];

        $feesHd = ProductFeesHd::create([
            'name'       => $validated['name'],
            'product_id'=> $product->id,
            'country_id' => $countryString,
            'ins_id'     => $validated['ins_id'],
            'netamount'  => $validated['netamount'],
            'user_id'    => Auth::id(),
        ]);

        foreach ($request->rows as $row) {
            ProductFeesDt::create([
                'fees_hd_id' => $feesHd->id,
                'fees_id'    => $row['fees_id'],
                'amount' => $row['ins_amount'],
                'insqty'     => $row['insqty'],
                'pay_type'   => $row['pay_type'],
                'totalamount'  => $row['totalfees'],
            ]);
        }
    }

    public function requirement(Product $product)
    {
        return Inertia::render('allpages/Agency/Product/requirement', [
            'product' => $product
        ]);
    }
    public function others(Product $product)
    {
        return Inertia::render('allpages/Agency/Product/others', [
            'product' => $product
        ]);
    }
    public function promotions(Product $product)
    {
        return Inertia::render('allpages/Agency/Product/promotions', [
            'product' => $product
        ]);
    }
}
