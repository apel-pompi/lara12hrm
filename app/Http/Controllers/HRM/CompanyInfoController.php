<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\CompanyInfo;
use App\Http\Requests\Company\UpdateCompanyInfoRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class CompanyInfoController extends Controller
{

    use AuthorizesRequests;

    public function edit()
    {
        try {
            $this->authorize('company.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        return Inertia::render('allpages/hrm/company',[
            'company' => CompanyInfo::firstOrNew()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyInfoRequest $request, CompanyInfo $companyInfo): RedirectResponse
    {
       
        try {
            $this->authorize('company.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validated();

        if ($request->hasfile('companylogo')) {
            $filePath = public_path('storage/company');
            $file = $request->file('companylogo');
            $file_name = time() . $file->getClientOriginalName();
            $file->move($filePath, $file_name);
            // delete old photo
            if ($companyInfo->companylogo && $request->hasFile('companylogo')) {
                $oldImage = public_path('storage/company/' . $companyInfo->companylogo);
                if (File::exists($oldImage)) {
                    unlink($oldImage);
                }
            }
            $validated['companylogo'] = $file_name;
        }

        if ($request->hasfile('loginimage')) {
            $filePath = public_path('storage/company');
            $file = $request->file('loginimage');
            $file_name = time() . $file->getClientOriginalName();
            $file->move($filePath, $file_name);
            // delete old photo
            if ($companyInfo->loginimage && $request->hasFile('loginimage')) {
                $oldImage = public_path('storage/company/' . $companyInfo->loginimage);
                if (File::exists($oldImage)) {
                    unlink($oldImage);
                }
            }
            $validated['loginimage'] = $file_name;
        }

        $companyInfo->update($validated);

        return redirect()->route('company.index')
                        ->with('success', 'Company information updated successfully');
    }
}
