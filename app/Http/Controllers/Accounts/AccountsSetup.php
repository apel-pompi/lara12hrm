<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
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
            'code' => GroupOne::select(DB::raw("IF(MAX('groupone') IS NULL,1,MAX(`groupone`)+1) as onecode"))->first(),
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
            'groupone' => GroupOne::where('groupone', $GroupOne)->first(),

            'code' => GroupTwo::select(DB::raw("IF(MAX('grouptwo') IS NULL,$idcode,MAX(`grouptwo`)+1) as twocode"))->where('groupone', $GroupOne)->first(),

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
        // GroupThree full info if exists
        $groupthree = GroupThree::with(['GroupOne', 'GroupTwo'])
            ->where('groupone', $GroupOne)
            ->where('grouptwo', $GroupTwo)
            ->first();

        // If not exist then GroupTwo info only
        $fallbackTwo = GroupTwo::with('GroupOne')
            ->where('groupone', $GroupOne)
            ->where('grouptwo', $GroupTwo)
            ->first();

        // Generate auto three code
        $codethree = GroupThree::where('groupone', $GroupOne)
            ->where('grouptwo', $GroupTwo)
            ->selectRaw("IF(MAX(RIGHT(groupthree,3)) IS NULL, 1, MAX(RIGHT(groupthree,3)) + 1) AS threecode")
            ->first()
            ->threecode;

        if ($codethree <= 9) {
            $code = "{$GroupTwo}-00{$codethree}";
        } elseif ($codethree <= 99) {
            $code = "{$GroupTwo}-0{$codethree}";
        } else {
            $code = "{$GroupTwo}-{$codethree}";
        }

        return Inertia::render('allpages/accounts/setting/groupthree', [
            'groupInfo'  => $groupthree ?: $fallbackTwo,
            'code'       => $code,
            'groupthree' => GroupThree::with(['GroupOne', 'GroupTwo', 'user'])
                ->where('groupone', $GroupOne)
                ->where('grouptwo', $GroupTwo)
                ->orderBy('id', 'DESC')
                ->paginate(10),
        ]);
    }
}
