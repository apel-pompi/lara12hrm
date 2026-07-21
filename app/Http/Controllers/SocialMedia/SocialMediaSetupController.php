<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia\SocialMediaSetup;
use App\Http\Requests\SocialMediaSetup\StoreSocialMediaSetupRequest;
use App\Http\Requests\SocialMediaSetup\UpdateSocialMediaSetupRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SocialMediaSetupController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('SocialMediaSetup.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $items = SocialMediaSetup::orderBy('id', 'desc')->get();
        return Inertia::render('allpages/default/social-media-setup', [
            'socialMediaSetups' => $items,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used for Inertia-driven UI
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSocialMediaSetupRequest $request)
    {
        try {
            $this->authorize('SocialMediaSetup.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $validated = $request->validated();
        SocialMediaSetup::create($validated);

        return redirect()->route('social-media-setup.index')->with('success', 'Social media setup created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialMediaSetup $socialMediaSetup)
    {
        // Optional: return a single resource if needed
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialMediaSetup $socialMediaSetup)
    {
        // Not used for Inertia; editing handled on index page
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocialMediaSetupRequest $request, SocialMediaSetup $socialMediaSetup)
    {
        try {
            $this->authorize('SocialMediaSetup.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $validated = $request->validated();
        $socialMediaSetup->update($validated);

        return redirect()->route('social-media-setup.index')->with('success', 'Social media setup updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialMediaSetup $socialMediaSetup)
    {
        try {
            $this->authorize('SocialMediaSetup.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $socialMediaSetup->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete social media setup.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json(['message' => 'Deleted'], 200);
    }
}
