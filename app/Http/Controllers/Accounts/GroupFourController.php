<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\GroupFour;
use App\Http\Requests\GroupFour\StoreGroupFourRequest;
use App\Http\Requests\GroupFour\UpdateGroupFourRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GroupFourController extends Controller
{
    use AuthorizesRequests;


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupFourRequest $request)
    {
        try {
            $this->authorize('GroupFour.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $store = GroupFour::create($data);
        if ($store) {
            return back()->with([
                'success' => true,
                'message' => 'Group Four created successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Group Four not created'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupFour $groupFour)
    {
        try {
            $this->authorize('GroupThree.four');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        return response()->json($groupFour);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupFour $groupFour)
    {
        try {
            $this->authorize('GroupFour.edit');

            return response()->json([
                'success' => true,
                'data' => $groupFour,
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupFourRequest $request, GroupFour $groupFour)
    {
        try {
            $this->authorize('GroupFour.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $groupFour->update($request->validated());

        if ($groupFour) {
            return back()->with([
                'success' => true,
                'message' => 'Group Four Update successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Group Four not Updateed'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupFour $groupFour)
    {
        try {
            $this->authorize('GroupFour.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $groupFour->forceDelete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Group Three.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $groupFour)
    {
        try {
            $this->authorize('GroupFour.status');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $groupFour = GroupFour::findOrFail($groupFour);
        $updated = $groupFour->update(['active' =>  $validated['active']]);
        if ($updated) {
            return back()->with([
                'message' => 'Group Four status updated successfully.'
            ]);
        }
        return back()->with([
            'message' => 'Failed to update status'
        ]);
    }
}
