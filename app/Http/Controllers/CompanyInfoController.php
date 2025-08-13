<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use App\Http\Requests\Company\UpdateCompanyInfoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class CompanyInfoController extends Controller
{


    public function edit()
    {
        return Inertia::render('allpages/company',[
            'company' => CompanyInfo::firstOrNew()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyInfoRequest $request, CompanyInfo $companyInfo): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasfile('companylogo')) {
            $filePath = public_path('storage/company');
            $file = $request->file('companylogo');
            $file_name = time() . $file->getClientOriginalName();
            $file->move($filePath, $file_name);
            // delete old photo
            if (!is_null($validated['companylogo'])) {
                $oldImage = public_path('storage/company/' . $companyInfo->companylogo);
                if (File::exists($oldImage)) {
                    unlink($oldImage);
                }
            }
            $validated['companylogo'] = $file_name;
        }

        $companyInfo->update($validated);

        return redirect()->route('company.index')
                        ->with('success', 'Company information updated successfully');
    }
}
