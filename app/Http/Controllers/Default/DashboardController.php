<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashboard(){
        $user = Auth::user();

        $roles = $user->getRoleNames();
        if ($roles->contains('superadmin')) {

            return Inertia::render('AdminDashboard', [
                'countAll' => Student::count(),
                'countPending' => Student::where('status', null)->count(),
                'countLead' => Student::where('status', 1)->count(),
                'countProspect' => Student::where('status', 2)->count(),
                'countonBoard' => Student::where('status', 3)->count(),
                'countArchive' => Student::where('status', 4)->count(),

            ]);

        }elseif ($roles->contains('Admin')) {
            return Inertia::render('AdminDashboard', [
                'countAll' => Student::count(),
                'countPending' => Student::where('status', null)->count(),
                'countLead' => Student::where('status', 1)->count(),
                'countProspect' => Student::where('status', 2)->count(),
                'countonBoard' => Student::where('status', 3)->count(),
                'countArchive' => Student::where('status', 4)->count(),

            ]);
        }
        elseif ($roles->contains('Manager')) {
            return Inertia::render('AdminDashboard', [
                'countAll' => Student::count(),
                'countPending' => Student::where('status', null)->count(),
                'countLead' => Student::where('status', 1)->count(),
                'countProspect' => Student::where('status', 2)->count(),
                'countonBoard' => Student::where('status', 3)->count(),
                'countArchive' => Student::where('status', 4)->count(),

            ]);
        }else{
            return Inertia::render('UserDashboard', [
                'countAll' => Student::where('assain_user', Auth::id())->count(),
                'countLead' => Student::where('assain_user', Auth::id())->where('status', 1)->count(),
                'countPending' => Student::where('assain_user', Auth::id())->where('status', null)->count(),
                'countProspect' => Student::where('assain_user', Auth::id())->where('status', 2)->count(),
                'countonBoard' => Student::where('assain_user', Auth::id())->where('status', 3)->count(),
                'countArchive' => Student::where('assain_user', Auth::id())->where('status', 4)->count(),
            ]);
        }
        
    }
}
