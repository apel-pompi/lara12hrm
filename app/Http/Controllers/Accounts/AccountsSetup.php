<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\GroupFour;
use App\Models\Accounts\GroupOne;
use App\Models\Accounts\GroupTwo;
use App\Models\Accounts\GroupThree;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AccountsSetup extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        try {
            $this->authorize('accsetting.GroupOne');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/accounts/setting/groupone', [
            'code' => GroupOne::select(DB::raw("IF(MAX('code') IS NULL,1,MAX(`code`)+1) as onecode"))->first(),
            'groupone' => GroupOne::with(['user'])->paginate(10)
        ]);
    }

    public function Grouptwo($GroupOne)
    {

        try {
            $this->authorize('accsetting.GroupTwo');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $idcode = $GroupOne . '0' . +1;

        return Inertia::render('allpages/accounts/setting/grouptwo', [
            'groupone' => GroupOne::where('code', $GroupOne)->first(),

            'code' => GroupTwo::select(DB::raw("IF(MAX('code') IS NULL,$idcode,MAX(`code`)+1) as twocode"))->where('groupone', $GroupOne)->first(),

            'grouptwo' => GroupTwo::with(['GroupOne', 'user'])->where('groupone', $GroupOne)->paginate(10)
        ]);
    }

    public function Groupthree($GroupOne, $GroupTwo)
    {

        try {
            $this->authorize('accsetting.GroupThree');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        // If not exist then GroupTwo info only
        $grouptwo = GroupTwo::with('GroupOne')
            ->where('groupone', $GroupOne)
            ->where('id', $GroupTwo)
            ->first();

        // Generate auto three code
        $codethree = GroupThree::where('groupone', $GroupOne)
            ->where('grouptwo', $GroupTwo)
            ->selectRaw("IF(MAX(RIGHT(code,3)) IS NULL, 1, MAX(RIGHT(code,3)) + 1) AS threecode")
            ->first()
            ->threecode;

        if ($codethree <= 9) {
            $code = "{$grouptwo->code}-00{$codethree}";
        } elseif ($codethree <= 99) {
            $code = "{$grouptwo->code}-0{$codethree}";
        } else {
            $code = "{$grouptwo->code}-{$codethree}";
        }

        return Inertia::render('allpages/accounts/setting/groupthree', [
            'groupInfo'  => $grouptwo,
            'code'       => $code,
            'groupthree' => GroupThree::with(['GroupOne', 'GroupTwo', 'user'])
                ->where('groupone', $GroupOne)
                ->where('grouptwo', $GroupTwo)
                ->orderBy('id', 'DESC')
                ->paginate(10),
        ]);
    }

    public function Groupfour($GroupOne, $GroupTwo, $GroupThree)
    {

        try {
            $this->authorize('accsetting.GroupFour');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        // If not exist then GroupThree info only
        $groupthree = GroupThree::with('GroupOne', 'GroupTwo',)
            ->where('groupone', $GroupOne)
            ->where('id', $GroupThree)
            ->first();

        // Generate auto three code
        $codefour = GroupFour::where('groupone', $GroupOne)
            ->where('grouptwo', $GroupTwo)
            ->where('groupthree', $GroupThree)
            ->selectRaw("IF(MAX(RIGHT(code,3)) IS NULL, 1, MAX(RIGHT(code,3)) + 1) AS fourcode")
            ->first()
            ->fourcode;

        if ($codefour <= 9) {
            $code = "{$groupthree->code}-00{$codefour}";
        } elseif ($codefour <= 99) {
            $code = "{$groupthree->code}-0{$codefour}";
        } else {
            $code = "{$groupthree->code}-{$codefour}";
        }

        return Inertia::render('allpages/accounts/setting/groupfour', [
            'groupInfo'  => $groupthree,
            'code'       => $code,
            'groupfour' => GroupFour::with(['GroupOne', 'GroupTwo', 'GroupThree', 'user'])
                ->where('groupone', $GroupOne)
                ->where('grouptwo', $GroupTwo)
                ->where('groupthree', $GroupThree)
                ->orderBy('id', 'DESC')
                ->paginate(10),
        ]);
    }
}
