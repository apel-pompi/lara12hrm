<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Default\FacebookForm;
use App\Models\Default\UserWiseForm;
use App\Models\User;
use App\Http\Requests\FacebookForm\StoreUserWiseFormRequest;
use App\Http\Requests\FacebookForm\UpdateUserWiseFormRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class UserWiseFormController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('userform.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/default/userwise-form', [
            //'userWiseForms' => UserWiseForm::all(),
            'userWiseForms' => UserWiseForm::with('form', 'teamLeader')->get(),
            'forms' => FacebookForm::whereNotIn(
                'id',
                UserWiseForm::pluck('form_id')
            )->get(),
            //'forms' => FacebookForm::select(['id', 'form_name'])->get(),
            'users' => User::select(['id', 'name'])->whereNull('banned_at')->get(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserWiseFormRequest $request)
    {
        try {
            $this->authorize('userform.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $data['counsilor_id'] = is_array($data['counsilor_id']) ? json_encode($data['counsilor_id']) : json_encode([$data['counsilor_id']]);

        UserWiseForm::create($data);

        return redirect()->route('userwise-form.index')->with('success', 'Userwise form created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserWiseForm $userWiseForm)
    {
        try {
            $this->authorize('userform.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserWiseForm $userWiseForm)
    {
        try {
            $this->authorize('userform.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserWiseFormRequest $request, UserWiseForm $userWiseForm)
    {
        try {
            $this->authorize('userform.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $data['counsilor_id'] = is_array($data['counsilor_id']) ? json_encode($data['counsilor_id']) : json_encode([$data['counsilor_id']]);

        $userWiseForm->update($data);

        return redirect()->route('userwise-form.index')->with('success', 'Userwise form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserWiseForm $userWiseForm)
    {
        try {
            $this->authorize('userform.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $userWiseForm->delete();

        return redirect()->route('userwise-form.index')->with('success', 'Userwise form deleted successfully.');
    }
}
