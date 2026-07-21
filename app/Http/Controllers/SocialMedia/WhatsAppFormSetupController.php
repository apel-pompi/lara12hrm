<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhatsAppFormSetup\StoreWhatsAppFormSetupRequest;
use App\Http\Requests\WhatsAppFormSetup\UpdateWhatsAppFormSetupRequest;
use App\Models\SocialMedia\WhatsAppFormSetup;
use App\Models\SocialMedia\WhatsAppsNumber;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class WhatsAppFormSetupController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('whatsappFormSetup.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/default/whatsapp-form-setup', [
            'whatsAppFormSetups' => WhatsAppFormSetup::with('number', 'teamLeader')->get(),
            'numbers' => WhatsAppsNumber::whereNotIn(
                'phone_id',
                WhatsAppFormSetup::pluck('phone_id')
            )->get(),
            'allNumbers' => WhatsAppsNumber::all(),
            'users' => User::select(['id', 'name'])->whereNull('banned_at')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWhatsAppFormSetupRequest $request)
    {
        try {
            $this->authorize('whatsappFormSetup.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $this->validatedDataWithNumber($request->validated());

        WhatsAppFormSetup::create($data);

        return redirect()->route('whatsapp-form-setup.index')->with('success', 'WhatsApp form setup created successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('whatsapp-form-setup.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(WhatsAppFormSetup $whatsappFormSetup)
    {
        try {
            $this->authorize('whatsappFormSetup.show');
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
    public function edit(WhatsAppFormSetup $whatsappFormSetup)
    {
        try {
            $this->authorize('whatsappFormSetup.edit');
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
    public function update(UpdateWhatsAppFormSetupRequest $request, WhatsAppFormSetup $whatsappFormSetup)
    {
        try {
            $this->authorize('whatsappFormSetup.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $this->validatedDataWithNumber($request->validated());

        $whatsappFormSetup->update($data);

        return redirect()->route('whatsapp-form-setup.index')->with('success', 'WhatsApp form setup updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WhatsAppFormSetup $whatsappFormSetup)
    {
        try {
            $this->authorize('whatsappFormSetup.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $whatsappFormSetup->delete();

        return redirect()->route('whatsapp-form-setup.index')->with('success', 'WhatsApp form setup deleted successfully.');
    }

    private function validatedDataWithNumber(array $data): array
    {
        $number = WhatsAppsNumber::where('phone_id', $data['phone_id'])->firstOrFail();

        $data['phone_number'] = $number->phoneno;
        $data['waba_id'] = $number->waba_id;
        $data['counsilor_id'] = is_array($data['counsilor_id'])
            ? json_encode($data['counsilor_id'])
            : json_encode([$data['counsilor_id']]);

        return $data;
    }
}
