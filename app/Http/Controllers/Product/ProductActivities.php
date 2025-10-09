<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFeeHd\StoreProductFeesHdRequest;
use App\Models\AgencySetting\Academic;
use App\Models\AgencySetting\Fees;
use App\Models\AgencySetting\Installment;
use App\Models\Default\Country;
use App\Models\Product\EnglishTest;
use App\Models\Product\OthersTest;
use App\Models\Product\Product;
use App\Models\Product\ProductFeesDt;
use App\Models\Product\ProductFeesHd;
use App\Models\Product\ProductRequirement;
use Illuminate\Http\Request;
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
            'feesDt'   => ProductFeesHd::with(['details.fees', 'installment'])->where('product_id', $product->id)->orderBy('id', 'DESC')->get()
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
            'product_id' => $product->id,
            'country_id' => $countryString,
            'ins_id'     => $validated['ins_id'],
            'netamount'  => $validated['netamount'],
            'user_id'    => Auth::id(),
        ]);

        if (!empty($validated['rows']) && is_array($validated['rows'])) {
            foreach ($validated['rows'] as $row) {
                ProductFeesDt::create([
                    'fees_hd_id'  => $feesHd->id,
                    'fees_id'     => $row['fees_id'],
                    'amount'      => $row['ins_amount'],
                    'insqty'      => $row['insqty'],
                    'pay_type'    => $row['pay_type'],
                    'totalamount' => ($row['totalfees'] ?? ($row['ins_amount'] ?? 0) * ($row['insqty'] ?? 1)),
                ]);
            }
        }
    }

    public function feeDelete(Product $product, ProductFeesHd $productFeesHd)
    {

        try {
            $productFeesHd->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Partner Branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function requirement(Product $product)
    {
        return Inertia::render('allpages/Agency/Product/requirement', [
            'product' => $product,
            'academic' => Academic::where('active', 1)->get(),
            'requirement' => ProductRequirement::with(['degree'])->where('product_id', $product->id)->get(),
            'requirementEnglish' => EnglishTest::where('product_id', $product->id)->get(),
            'requirementOthers' => OthersTest::where('product_id', $product->id)->get()
        ]);
    }

    public function editRequirement(Product $product, $requirement)
    {
        $requirement = ProductRequirement::with('degree')
            ->where('product_id', $product->id)
            ->findOrFail($requirement);

        return response()->json([
            'requirement' => $requirement
        ]);
    }



    public function storeRequirement(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'degree_id' => 'required|integer|exists:academics,id',
            'scoretype' => 'required|string|in:percentage,gpa,cgpa',
            'score' => 'required|numeric|min:0',
        ]);

        $insert = ProductRequirement::with('degree')->updateOrCreate(
            ['id' => $request->id],
            [
                'product_id' => $validated['product_id'],
                'degree_id' => $validated['degree_id'],
                'scoretype' => $validated['scoretype'],
                'score' => $validated['score'],
                'user_id' => Auth::id(),
            ]

        );
        $insert->load('degree');
        return response()->json([
            'success' => true,
            'message' => $request->id ? 'Requirement updated' : 'Requirement created',
            'requirement' => $insert,
        ]);
    }

    public function editRequirementEng(Product $product, $requirement)
    {
        $requirement = EnglishTest::where('product_id', $product->id)
            ->findOrFail($requirement);

        return response()->json([
            'requirementEnglish' => $requirement
        ]);
    }

    public function storeRequirementEng(Request $request, Product $product)
    {

        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'testTypes' => 'required|array|min:3',
            'testTypes.*.name' => 'required|string|in:TOEFL,IELTS,PTE',
            'testTypes.*.listening' => 'nullable|numeric',
            'testTypes.*.reading' => 'nullable|numeric',
            'testTypes.*.writing' => 'nullable|numeric',
            'testTypes.*.speaking' => 'nullable|numeric',
            'testTypes.*.overall' => 'nullable|numeric',
        ]);
        $userId = auth()->id();

        foreach ($request->testTypes as $test) {
            EnglishTest::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'name' => $test['name'],
                ],
                [
                    'listening' => $test['listening'],
                    'reading' => $test['reading'],
                    'writing' => $test['writing'],
                    'speaking' => $test['speaking'],
                    'overall' => $test['overall'],
                    'user_id' => $userId,
                ]
            );
        }

        // return full fresh list
        $requirementEnglish = EnglishTest::where('product_id', $product->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'English test scores updated successfully',
            'requirementEnglish' => $requirementEnglish,
        ]);
    }

    public function editRequirementOthers(Product $product, $requirement)
    {
        $requirement = OthersTest::where('product_id', $product->id)
            ->findOrFail($requirement);

        return response()->json([
            'requirementOthers' => $requirement
        ]);
    }

    public function storeRequirementOthers(Request $request, Product $product)
    {

        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'othersTypes' => 'required|array|min:4',
            'othersTypes.*.name' => 'required|string|in:SAT I,SAT II,GRE,GMAT',
            'othersTypes.*.scores' => 'nullable|numeric',
        ]);
        $userId = auth()->id();

        foreach ($request->othersTypes as $test) {
            OthersTest::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'name' => $test['name'],
                ],
                [
                    'scores' => $test['scores'],
                    'user_id' => $userId,
                ]
            );
        }

        // return full fresh list
        $requirementOthers = OthersTest::where('product_id', $product->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Others test scores updated successfully',
            'requirementOthers' => $requirementOthers,
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
