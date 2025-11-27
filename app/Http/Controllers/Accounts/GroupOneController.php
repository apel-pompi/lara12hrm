<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;

use App\Models\Accounts\GroupOne;
use App\Http\Requests\GroupOne\StoreGroupOneRequest;
use App\Http\Requests\GroupOne\UpdateGroupOneRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GroupOneController extends Controller
{
    use AuthorizesRequests;
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupOneRequest $request)
    {
        try {
            $this->authorize('GroupOne.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $store = GroupOne::create($data);
        if ($store) {
            return back()->with([
                'success' => true,
                'message' => 'Group One created successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Group One not created'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupOne $groupOne)
    {
        try {
            $this->authorize('GroupOne.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        return response()->json($groupOne);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupOne $groupOne)
    {
        try {
            $this->authorize('GroupOne.edit');

            return response()->json([
                'success' => true,
                'data' => $groupOne,
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
    public function update(UpdateGroupOneRequest $request, GroupOne $groupOne)
    {
        try {
            $this->authorize('GroupOne.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $groupOne->update($request->validated());

        if ($groupOne) {
            return back()->with([
                'success' => true,
                'message' => 'Group One Update successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Group One not Updateed'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupOne $groupOne)
    {
        try {
            $this->authorize('GroupOne.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $groupOne->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Group One.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $groupOne)
    {
        try {
            $this->authorize('GroupOne.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $groupOne = GroupOne::findOrFail($groupOne);
        $updated = $groupOne->update(['active' =>  $validated['active']]);
        if ($updated) {
            return back()->with([
                'message' => 'Group One status updated successfully.'
            ]);
        }
        return back()->with([
            'message' => 'Failed to update status'
        ]);
    }
}
