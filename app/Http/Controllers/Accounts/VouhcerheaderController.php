<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Vouhcerheader;
use App\Http\Requests\Vouhcerheader\StoreVouhcerheaderRequest;
use App\Http\Requests\Vouhcerheader\UpdateVouhcerheaderRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class VouhcerheaderController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function credit()
    {
        try {
            $this->authorize('Vouhcerheader.credit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/accounts/voucher/creditvoucher', [
            'vouhcerheaders' => Vouhcerheader::all(),
        ]);
    }

    public function debitVoucher()
    {
        try {
            $this->authorize('Vouhcerheader.debit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/accounts/voucher/debitvoucher', [
            'vouhcerheaders' => Vouhcerheader::all(),
        ]);
    }

    public function reverseVoucher()
    {
        try {
            $this->authorize('Vouhcerheader.reverse');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/accounts/voucher/reversevoucher', [
            'vouhcerheaders' => Vouhcerheader::all(),
        ]);
    }
    
}
