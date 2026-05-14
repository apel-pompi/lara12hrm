<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\GroupThree;
use App\Http\Requests\GroupThree\StoreGroupThreeRequest;
use App\Http\Requests\GroupThree\UpdateGroupThreeRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GroupThreeController extends Controller
{
    use AuthorizesRequests;
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupThreeRequest $request)
    {

        try {
            $this->authorize('GroupThree.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $store = GroupThree::create($data);
        if ($store) {
            return back()->with([
                'success' => true,
                'message' => 'Group Three created successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Group Three not created'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupThree $groupThree)
    {
        try {
            $this->authorize('GroupThree.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        return response()->json($groupThree);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupThree $groupThree)
    {

        try {
            $this->authorize('GroupThree.edit');

            return response()->json([
                'success' => true,
                'data' => $groupThree,
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
    public function update(UpdateGroupThreeRequest $request, GroupThree $groupThree)
    {
        try {
            $this->authorize('GroupThree.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $groupThree->update($request->validated());

        if ($groupThree) {
            return back()->with([
                'success' => true,
                'message' => 'Group Three Update successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Group Three not Updateed'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupThree $groupThree)
    {
        try {
            $this->authorize('GroupThree.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $groupThree->forceDelete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Group Three.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $groupThree)
    {
        try {
            $this->authorize('GroupThree.status');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $groupThree = GroupThree::findOrFail($groupThree);
        $updated = $groupThree->update(['active' =>  $validated['active']]);
        if ($updated) {
            return back()->with([
                'message' => 'Group Three status updated successfully.'
            ]);
        }
        return back()->with([
            'message' => 'Failed to update status'
        ]);
    }
}
