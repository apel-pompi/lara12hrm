<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partner\Partner;
use App\Models\Partner\PartnerBranch;
use App\Models\Product\Product;
use Inertia\Inertia;

class PartnerActivities extends Controller
{
    public function aplication(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/partnerlayout', [
            'partner' => $partner
        ]);
    }

    public function product(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/product', [
            'partner' => $partner,
            'product' => Product::with(['productype','partner'])->where('partner_id',$partner->id)->get()
        ]);
    }

    public function branch(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/branch', [
            'partner' => $partner,
            'branch' => PartnerBranch::with(['partner','user','states.country','citys'])->get()
        ]);
    }

    public function aggrements(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/aggrements', [
            'partner' => $partner
        ]);
    }
    public function contacts(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/contacts', [
            'partner' => $partner
        ]);
    }
    public function notes(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/notes', [
            'partner' => $partner
        ]);
    }
    public function documents(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/documents', [
            'partner' => $partner
        ]);
    }
    public function appoinments(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/appoinments', [
            'partner' => $partner
        ]);
    }
    public function accounts(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/accounts', [
            'partner' => $partner
        ]);
    }
    public function conversations(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/conversations', [
            'partner' => $partner
        ]);
    }
    public function tasks(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/tasks', [
            'partner' => $partner
        ]);
    }
    public function others(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/others', [
            'partner' => $partner
        ]);
    }
    public function promotions(Partner $partner){
        return Inertia::render('allpages/Agency/Partner/promotions', [
            'partner' => $partner
        ]);
    }
}
