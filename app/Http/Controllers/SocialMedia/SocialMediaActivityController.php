<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialMediaActivity\StoreSocialMediaActivityRequest;
use App\Http\Requests\SocialMediaActivity\UpdateSocialMediaActivityRequest;
use App\Models\SocialMedia\SocialMediaActivity;

class SocialMediaActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreSocialMediaActivityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialMediaActivity $socialMediaActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialMediaActivity $socialMediaActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocialMediaActivityRequest $request, SocialMediaActivity $socialMediaActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialMediaActivity $socialMediaActivity)
    {
        //
    }
}
