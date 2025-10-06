<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\CompanyInfo;
use App\Http\Requests\Company\UpdateCompanyInfoRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class CompanyInfoController extends Controller
{

    use AuthorizesRequests;

    public function edit()
    {
        $this->authorize('company.edit');

        return Inertia::render('allpages/hrm/company',[
            'company' => CompanyInfo::firstOrNew()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyInfoRequest $request, CompanyInfo $companyInfo): RedirectResponse
    {
        $this->authorize('company.update');

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
