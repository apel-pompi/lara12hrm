<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;

use App\Models\Accounts\GroupTwo;
use App\Http\Requests\GroupTwo\StoreGroupTwoRequest;
use App\Http\Requests\GroupTwo\UpdateGroupTwoRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GroupTwoController extends Controller
{
    use AuthorizesRequests;
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupTwoRequest $request)
    {

        try {
            $this->authorize('GroupTwo.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $store = GroupTwo::create($data);
        if ($store) {
            return back()->with([
                'success' => true,
                'message' => 'Group Two created successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Group Two not created'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupTwo $groupTwo)
    {
        try {
            $this->authorize('GroupTwo.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        return response()->json($groupTwo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupTwo $groupTwo)
    {
        try {
            $this->authorize('GroupTwo.edit');

            return response()->json([
                'success' => true,
                'data' => $groupTwo,
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
    public function update(UpdateGroupTwoRequest $request, GroupTwo $groupTwo)
    {
        try {
            $this->authorize('GroupTwo.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $groupTwo->update($request->validated());
        
        if ($groupTwo) {
            return back()->with([
                'success' => true,
                'message' => 'Group Two Update successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Group Two not Updateed'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupTwo $groupTwo)
    {
        try {
            $this->authorize('GroupTwo.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $groupTwo->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Group Two.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $groupTwo)
    {
        try {
            $this->authorize('GroupTwo.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $groupTwo = GroupTwo::findOrFail($groupTwo);
        $updated = $groupTwo->update(['active' =>  $validated['active']]);
        if ($updated) {
            return back()->with([
                'message' => 'Group Two status updated successfully.'
            ]);
        }
        return back()->with([
            'message' => 'Failed to update status'
        ]);
    }
}
