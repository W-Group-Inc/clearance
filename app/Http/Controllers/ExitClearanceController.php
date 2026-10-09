<?php

namespace App\Http\Controllers;

use App\Employee;
use App\ExitResign;
use App\ExitClearance;
use App\ExitClearanceComment;
use App\ExitClearanceChecklist;
use App\ExitClearanceSignatory;
use App\ExitResignStatusUpdate;
use App\Mail\ClearanceStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
class ExitClearanceController extends Controller
{
    //

    public function index(Request $request)
    {
        $resigns = ExitResign::with('exit_clearance.signatories')->where('status','Ongoing Clearance')->get();
        // dd($resigns);
        return view('ongoing_clearances',array(
            'resigns'=>$resigns
        ));
    }

    public function view(Request $request,$id)
    {
        $resign = ExitResign::with('exit_clearance.department','exit_clearance.checklists','exit_clearance.signatories')->findOrfail($id);
        // dd($resigns);
        $employees = Employee::where('status','Active')->get();
        
        return view('view_clearance',array(
            'resignEmployee'=>$resign,
            'employees' => $employees
        ));
    }
    public function forClearance(Request $request)
    {
        $status = $request->status;
        if(empty($status))
        {
            $status = "Pending";
        }
        $for_clearances = ExitClearanceSignatory::with('clearance.department')->where('employee_id',auth()->user()->employee->id)->get();
        // dd($for_clearances);
        return view('for_clearances',array(
            'for_clearances'=>$for_clearances,
            'status'=>$status
        ));
    }

    public function viewAsSignatory (Request $request,$id)
    {   
        $for_clearances = ExitClearanceSignatory::with('clearance.department')->where('employee_id',auth()->user()->employee->id)->where('id',$id)->first();   
        
        $exitClearanceIds = ExitClearanceSignatory::where('exit_clearance_id', $for_clearances->exit_clearance_id)->pluck('employee_id')->toArray();
        $resign = ExitResign::with('exit_clearance.department','exit_clearance.checklists','exit_clearance.signatories')->findOrfail($for_clearances->clearance->resign_id);
        return view('view_as_signatory',array(
            'for_clearances'=>$for_clearances,
            'resignEmployee'=>$resign,
            'exitClearanceIds'=>$exitClearanceIds,
        ));

    }
    public function viewComments(Request $request,$id)
    {   
        $employeeId = auth()->user()->employee->id;
        $exitClearanceIds = ExitClearanceSignatory::where('exit_clearance_id', $id)->pluck('employee_id')->toArray();
        $exit = ExitClearance::findOrFail($id);

        // Check if the current user is authorized to view the page
        if ($exit->employee_id == $employeeId || in_array($employeeId, $exitClearanceIds) || auth()->user()->clearance_admin == 1) {
            // Retrieve the necessary data once
            $for_clearances = ExitClearanceSignatory::with('clearance.department')
                ->where('exit_clearance_id', $exit->id)
                ->first();

            $resign = ExitResign::with('exit_clearance.department', 'exit_clearance.checklists', 'exit_clearance.signatories')
                ->findOrFail($for_clearances->clearance->resign_id);

            return view('view_as_signatory', [
                'for_clearances' => $for_clearances,
                'exitClearanceIds' => $exitClearanceIds,
                'resignEmployee' => $resign,
            ]);
        }

        // If user is not authorized
        Alert::error('You are not allowed to view this page.')->persistent('Dismiss');
        return redirect('home');

    }
    public function viewMyClearance()
    {   
        $employeeId = auth()->user()->employee->id;
        // dd($employeeId); 
        $resignExit = ExitResign::with('exit_clearance.department','exit_clearance.checklists','exit_clearance.signatories')->where('employee_id',auth()->user()->employee->id)->where('status','!=','Retracted')->first();
        
        if($resignExit)
        {
            return view('view_clearance',array(
                'resignEmployee'=>$resignExit
            ));
        }
        else
        {
            Alert::error('Your clearance is not yet processed, please coordinate with HR.')->persistent('Dismiss');
            return redirect('home');
        }
       

        // If user is not authorized
        // Alert::error('You are not allowed to view this page.')->persistent('Dismiss');
        // return redirect('home');

    }
    public function submitComment(Request $request,$id)
    {
        // dd($request->all());
        $comment = new ExitClearanceComment;
        $remarks = $request->observation."<br> ";
        if($request->file('file')){
            $proof = $request->file('file');
            $original_name = $proof->getClientOriginalName();
            $name = time() . '_' . $proof->getClientOriginalName();
            $proof->move(public_path() . '/files/', $name);
            $file_name = '/files/' . $name;
            $remarks = $remarks."<a class='btn btn-sm btn-success' href='".url($file_name)."'  target='blank'>".$original_name."</a> ";
        }
        $comment->remarks = $remarks;
        $comment->exit_clearance_id = $id;
        $comment->user_id = auth()->user()->id;
        $comment->save();
        Alert::success('Successfully Stored')->persistent('Dismiss');

        return back();
    }

    public function changestatus(Request $request,$id)
    {
        $checklist = ExitClearanceChecklist::findOrfail($id);
        $checklist->status = $request->status;
        $checklist->save();

        $comment = new ExitClearanceComment;
        $remarks = "<span>Checklist: ".$request->checklist." <br>".$request->old_status." &#x2192; ".$request->status."</span> <br> Remarks : ".$request->remarks."<br> ";
        if($request->file('proof')){
            $proof = $request->file('proof');
            $original_name = $proof->getClientOriginalName();
            $name = time() . '_' . $proof->getClientOriginalName();
            $proof->move(public_path() . '/proof/', $name);
            $file_name = '/proof/' . $name;
            $remarks = $remarks."<a class='btn btn-sm btn-success' href='".url($file_name)."'  target='blank'>".$original_name."</a> ";
        }
        $comment->remarks = $remarks;
        $comment->exit_clearance_id = $checklist->exit_clearance_id;
        $comment->user_id = auth()->user()->id;
        $comment->save();
        Alert::success('Successfully Change Status')->persistent('Dismiss');

        return back();

    }
    public function cleared(Request $request,$id)
    {
        $exit_signatory = ExitClearanceSignatory::findOrfail($id);
        $signatories = ExitClearanceSignatory::where('exit_clearance_id',$exit_signatory->exit_clearance_id)->where('id','!=',$id)->where('status','Pending')->count();
        if($signatories == 0)
        {
            $exitChecklist = ExitClearanceChecklist::where('status','Pending')->where('exit_clearance_id',$exit_signatory->exit_clearance_id)->count();
            if($exitChecklist > 0 )
            {
                Alert::error('Kindly complete the entire checklist, or mark items as "N/A" if they are not applicable.')->persistent('Dismiss');
                return back();
            }
        }
        $resign_employee = ExitClearance::where('resign_id',$exit_signatory->clearance->resign_id)->pluck('id')->toArray();
        $all_signatories = ExitClearanceSignatory::whereIn('exit_clearance_id',$resign_employee)
            ->where('id', '!=', $id)
            ->where('status', "Pending")
            ->count();
        if($all_signatories == 0)
        {
            $update = ExitResign::where('id',$exit_signatory->clearance->resign_id)->first();
            $update->status = 'Cleared';
            $update->date_cleared = date('Y-m-d');
            $update->save();

        }
        
        $exit_signatory = ExitClearanceSignatory::findOrfail($id);
        $exit_signatory->status = "Cleared";
        $exit_signatory->save();

        $comment = new ExitClearanceComment;
        $remarks = "<span>Tag ".$request->name." as Cleared <br> Remarks : ".$request->remarks."<br> ";
        $comment->remarks = $remarks;
        $comment->exit_clearance_id = $exit_signatory->exit_clearance_id;
        $comment->user_id = auth()->user()->id;
        $comment->save();
        Alert::success('Successfully Change Status')->persistent('Dismiss');

        return back();

    }


    public function clear_index(Request $request)
    {
        $resigns = ExitResign::with('exit_clearance.signatories', 'status_updates')->where('status','Cleared')->get();
        // dd($resigns);
        return view('cleared',array(
            'resigns'=>$resigns,
            'pageTitle'=>'Cleared'
        ));
    }

    public function forRelease(Request $request)
    {
        $resigns = ExitResign::with('exit_clearance.signatories', 'status_updates')->where('status','For Release')->get();

        return view('cleared',array(
            'resigns'=>$resigns,
            'pageTitle'=>'For Release'
        ));
    }

    public function released(Request $request)
    {
        $resigns = ExitResign::with('exit_clearance.signatories', 'status_updates')->where('status','Released')->get();

        return view('cleared',array(
            'resigns'=>$resigns,
            'pageTitle'=>'Released'
        ));
    }

    public function forComputation(Request $request)
    {
        $resigns = ExitResign::with('exit_clearance.signatories', 'status_updates')
            ->where('status', 'Ongoing Computation')
            ->get();

        return view('cleared', array(
            'resigns' => $resigns,
            'pageTitle' => 'For Computation'
        ));
    }

    public function updateExitStatus(Request $request, $id)
    {
        abort_unless(auth()->user()->clearance_admin, 403);

        $resign = ExitResign::with('employee.user_info')->findOrFail($id);
        $allowedStatuses = $resign->allowedNextStatuses();

        $request->validate(array(
            'status' => 'required|string',
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'remarks' => 'nullable|string|max:2000',
        ));

        if (!in_array($request->status, $allowedStatuses, true)) {
            return back()->withErrors(array(
                'status' => 'That status change is not allowed from '.$resign->status.'.'
            ));
        }

        $document = $request->file('document');
        $directory = storage_path('app/clearance_status_documents');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = time().'_'.$resign->id.'_'.Str::random(8).'.'.$document->getClientOriginalExtension();
        $document->move($directory, $fileName);

        $statusUpdate = new ExitResignStatusUpdate;
        $statusUpdate->exit_resign_id = $resign->id;
        $statusUpdate->status = $request->status;
        $statusUpdate->document = 'clearance_status_documents/'.$fileName;
        $statusUpdate->original_name = substr($document->getClientOriginalName(), 0, 255);
        $statusUpdate->remarks = $request->remarks;
        $statusUpdate->updated_by = auth()->user()->id;
        $statusUpdate->save();

        $resign->status = $request->status;
        if ($request->status === 'For Release') {
            $resign->compute_done = date('Y-m-d');
        }
        $resign->save();

        $statusUpdate->load('resign.employee');
        $recipients = array_values(array_unique(array_filter(array(
            $resign->personal_email,
            $resign->employee && $resign->employee->user_info ? $resign->employee->user_info->email : null,
        ))));

        if (count($recipients)) {
            try {
                Mail::to($recipients)->send(new ClearanceStatusUpdated($statusUpdate));
            } catch (\Exception $exception) {
                Log::error('Unable to send clearance status notification.', array(
                    'exit_resign_id' => $resign->id,
                    'status_update_id' => $statusUpdate->id,
                    'exception' => $exception,
                ));
                Alert::warning('Status updated, but the employee email could not be sent.')->persistent('Dismiss');
                return back();
            }
        } else {
            Alert::warning('Status updated, but the employee has no email address.')->persistent('Dismiss');
            return back();
        }

        Alert::success('Status updated and the employee was notified.')->persistent('Dismiss');

        $redirects = array(
            'Ongoing Computation' => 'for-computation',
            'For Release' => 'for-release',
            'Released' => 'released',
        );

        return redirect($redirects[$request->status]);
    }

    public function downloadStatusDocument($id)
    {
        $statusUpdate = ExitResignStatusUpdate::with('resign.employee')->findOrFail($id);
        $user = auth()->user();
        $isEmployee = $user->employee && $statusUpdate->resign->employee_id == $user->employee->id;

        abort_unless($user->clearance_admin || $isEmployee, 403);

        $path = storage_path('app/'.ltrim($statusUpdate->document, '/'));
        abort_unless(is_file($path), 404);

        return response()->download($path, $statusUpdate->original_name);
    }

    public function generateClearanceForm($id)
    {
        $data = [];

        $resign = ExitResign::with('exit_clearance.department','exit_clearance.checklists','exit_clearance.signatories')->findOrfail($id);
        $data['resign'] = $resign;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('clearance_form',$data)->setPaper('legal', 'portrait');
        return $pdf->stream();
    }
}
