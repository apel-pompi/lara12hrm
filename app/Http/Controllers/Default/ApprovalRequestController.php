<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Default\ApprovalRequest;
use App\Models\HRM\Leave;
use App\Models\Student\Student;
use App\Models\Student\StudentActivities;
use App\Models\Student\StudentInService;
use App\Models\Student\StudentQuotationHD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalRequestController extends Controller
{
    public function studentArchive(Student $student, Request $request)
    {

        if (ApprovalRequest::where('reference_id', $student->id)->where('remarks','Archive')->where('status', null)->exists()) {
            return back()->with('error', 'Archive request is allready exits');
        }

        $created = ApprovalRequest::create([
            'reference_id' => $student->id,
            'description' => $request->details,
            'remarks' => 'Archive',
            'status' => null,
            'user_id' => Auth::id()
        ]);
        if ($created) {
            StudentActivities::create([
                'student_id'    => $student->id,
                'title'         => "has request student archive",
                'fristactivity' => null,
                'lastactivity'  => null,
                'user_id'       => Auth::id(),
            ]);
        }
        return back()->with('success', 'Archive request send successfully.');
    }

    public function studentTransfer(Student $student, Request $request)
    {

        if (ApprovalRequest::where('reference_id', $student->id)->where('remarks','Transfer')->where('status', null)->exists()) {
            return back()->with('error', 'Student transfer request is allready exits');
        }

        $created = ApprovalRequest::create([
            'reference_id' => $student->id,
            'description' => $request->details,
            'remarks' => 'Transfer',
            'status' => null,
            'user_id' => Auth::id()
        ]);
        if ($created) {
            StudentActivities::create([
                'student_id'    => $student->id,
                'title'         => "has request student transfer",
                'fristactivity' => null,
                'lastactivity'  => null,
                'user_id'       => Auth::id(),
            ]);
        }
        return back()->with('success', 'Student transfer request send successfully.');
    }

    public function studentOnBoard(Student $student, Request $request)
    {

        if (ApprovalRequest::where('reference_id', $student->id)->where('remarks','onBoard')->where('status', null)->exists()) {
            return back()->with('error', 'Student onBoard request is allready exits');
        }

        $created = ApprovalRequest::create([
            'reference_id' => $student->id,
            'description' => $request->details,
            'remarks' => 'onBoard',
            'status' => null,
            'user_id' => Auth::id()
        ]);
        if ($created) {
            StudentActivities::create([
                'student_id'    => $student->id,
                'title'         => "has request student onBoard",
                'fristactivity' => null,
                'lastactivity'  => null,
                'user_id'       => Auth::id(),
            ]);
        }
        return back()->with('success', 'Student onBoard request send successfully.');
    }

    public function leaveRequest(Request $request)
    {
        $request->validate([
            'leave_id' => 'required|exists:leaves,id',
            'status'   => 'required|in:0,1,2,3,4',
        ]);

        Leave::where('id', $request->leave_id)
            ->update(['status' => $request->status]);

        return back()->with('success', 'Leave request updated successfully.');
    }

    public function leaveApproved(Request $request)
    {
        $todate        = $request->todate;
        $requestdays   = $request->requestdays;
        $approveddate  = $request->approveddate;
        $approveddays  = $request->approveddays;

        if (empty($approveddate)) {
            Leave::where('id', $request->leave_id)
                ->update([
                    'approveddate' => $todate,
                    'approveddays' => $requestdays,
                    'status' => 3,
                ]);
        } else {
            Leave::where('id', $request->leave_id)
                ->update([
                    'approveddate' => $approveddate,
                    'approveddays' => $approveddays,
                    'status' => 3,
                ]);
        }

        return back()->with('success', 'Leave request updated successfully.');
    }


    public function leaveCancel(Request $request)
    {
        $request->validate([
            'leave_id' => 'required|exists:leaves,id',
            'status'   => 'required|in:0,1,2,3,4',
        ]);

        Leave::where('id', $request->leave_id)
            ->update(['status' => $request->status]);

        return back()->with('success', 'Leave request cancel successfully.');
    }

    public function QuoattionView($quotation)
    {
        if (!$quotation) {
            return back()->with(['error' => true, 'message' => 'Invalid request']);
        }

        $quotHd = StudentQuotationHD::with(['student.country', 'user'])->where('id', $quotation)->first();
        $workflow = StudentInService::with(['workflow', 'partnerBranch.partner', 'product'])->where('student_id', $quotHd->student_id)->where('product_id', $quotHd->product_id)->first();
        $fees = StudentQuotationHD::getFees($quotation);
        return response()->json([
            'success' => true,
            'data' => $quotHd,
            'workflow' => $workflow,
            'fees' => $fees,
        ]);
    }

    public function QuoattionConfirm(Request $request)
    {

        $quotHd = StudentQuotationHD::where('quotation_no', $request->quoat_id)
            ->update(['active' => 1]);

        if ($quotHd) {
            return back()->with(['success' => true, 'message' => 'Quotation request confirm successfully.']);
        }

        return back()->with(['error' => true, 'message' => 'Invalid request']);
    }

    public function QuoattionCancel(Request $request)
    {

        $quotHd = StudentQuotationHD::where('quotation_no', $request->quoat_id)
            ->update(['active' => 2]);

        if ($quotHd) {
            return back()->with(['success' => true, 'message' => 'Quotation request cancel successfully.']);
        }

        return back()->with(['error' => true, 'message' => 'Invalid request']);
    }
}
