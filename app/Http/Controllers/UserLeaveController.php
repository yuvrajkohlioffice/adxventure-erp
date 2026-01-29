<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Leaves,User};
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
class UserLeaveController extends Controller
{       


    public function create(Request $request)
    {
        // dd(1);
        $excludedRoles = ['Client', 'super-admin', 'Admin'];
        $users = User::whereDoesntHave('roles', function ($query) use ($excludedRoles) {
            $query->whereNotIn('name', $excludedRoles)
                ->where('is_active',1);
        })->get();
        $user = auth()->user();
        // // Apply user-specific filtering
        // if (!$user->hasRole(['Super-Admin','Admin','Human Resources Executive'])) {
        //     $leavesQuery->where('user_id', $user->id);
        // }

        if ($request->ajax()) {
            
            $leavesQuery = Leaves::orderby('id','desc');

            if (!$user->hasRole(['Super-Admin','Admin','Human Resources Executive'])) {
                $leavesQuery->where('user_id', $user->id);
            }

            if ($request->has('user') && !is_null($request->user)) {
                $leavesQuery->where('user_id', $request->user);
            }

            if ($request->has('type') && !is_null($request->type)) {
                $leavesQuery->where('type', $request->type);
            }

            if ($request->has('status') && !is_null($request->status)){
                $leavesQuery->where('status', $request->status);
            }

            $leaves = $leavesQuery->get();
              
            return DataTables::of($leaves)
                ->addIndexColumn()
                ->addColumn('employee', function ($leave) {
                    $roles = $leave->users->roles->pluck('name')->implode(', ');
                    return '<div class="order-md-1">
                            <h6 class="mb-1 text-dark fs-15 fw-bold">'.$leave->users->name.'</h6>
                            <small class="text-muted">'.$roles.'</small> 
                        </div>';
                })
                ->addColumn('created_at', function ($leave) {
                    return '<small>' . $leave->created_at->format('d-m-Y, h:i:s') . '</small>';
                })
                ->addColumn('leave_dates', function ($leave) {
                    $from = \Carbon\Carbon::parse($leave->from_date)->format('d-m-Y');
                    $to = \Carbon\Carbon::parse($leave->to_date)->format('d-m-Y');
                    return "<small>{$from} to {$to}</small>";
                })
                ->addColumn('leave_days', function ($leave) {
                    return "<small>{$leave->days}</small>";
                })
                ->addColumn('reason', function ($leave) {
                    $html = '';

                    if ($leave->document) {
                        $html .= "<a href='" . asset('leaves/' . $leave->document) . "' target='_blank'><i class='bi bi-file-earmark-pdf-fill'></i></a> ";
                    }

                    $tooltip = htmlspecialchars($leave->request); // for safety
                    $shortRequest = strlen($leave->request) > 20 ? substr($leave->request, 0, 20) . '...' : $leave->request;

                    $html .= "<small style='cursor:pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='" . $tooltip . "'>" . $shortRequest . "</small>";

                    return $html;
                })
                ->addColumn('approval_status', function ($leave) {
                    if($leave->status == 1){
                        return '<span class="badge bg-success">Approved</span>';
                    }elseif($leave->status == 2){
                        $tooltip = htmlspecialchars($leave->remark);
                        $shortremark = strlen($leave->remark) > 20 ? substr($leave->remark, 0, 20) . '...' : $leave->remark;
                        return '<span class="badge bg-danger">Un-Approved</span><br>
                                <small style="cursor:pointer" data-bs-toggle="tooltip" data-bs-placement="top" title='. $tooltip .'>'.$shortremark.'</small>';
                    }else{
                         return '<span class="badge bg-primary">Pending</span>';
                    }
                })
                ->addColumn('approved_by', function ($leave) {
                    return '
                        <small>
                            Team lead: ' . ($leave->approved_tl_id ? '<input class="form-check-input" type="checkbox" checked disabled>' : '') . '<br>
                            Hr: ' . ($leave->approved_hr_id ? '<input class="form-check-input" type="checkbox" checked disabled>' : '') . '<br>
                            Manager: ' . ($leave->approved_manager_id ? '<input class="form-check-input" type="checkbox" checked disabled>' : '') . '
                        </small>';
                })
                ->addColumn('action', function ($leave) {
                    if (auth()->user()->hasRole(['Super-Admin','Admin'])) {
                       return '
                        <a class="btn btn-sm btn-danger" href="#" onclick="deleteConfirmation(\'' . route('leave.delete', ['id' => $leave->id]) . '\')">
                            <i class="bi bi-trash"></i>
                        </a>
                        <a class="btn btn-sm btn-success" href="#" onclick="Stauts(' . $leave->id . ', \'approve\')">
                            <i class="bi bi-check2-circle"></i>
                        </a>
                        <a class="btn btn-sm btn-warning" href="#" onclick="Stauts(' . $leave->id . ', \'reject\')">
                            <i class="bi bi-exclamation-circle"></i>
                        </a>';
                    }
                    return '';
                })
                ->rawColumns(['employee', 'approved_by', 'created_at', 'leave_dates', 'leave_days','reason','approval_status', 'action'])
                ->make(true);
        }

        return view('admin.leaves.create', compact('users'));
    }

    
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'reason' => 'required||min:5|max:150',
            'to_date' => 'required|date',
            'from_date' => 'required|date',
            'type' => 'required',
            'document' => 'nullable|max:2048',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        // Calculate the difference in days
        $timestamp1 = strtotime($request->from_date);
        $timestamp2 = strtotime($request->to_date);
        $diff_in_days = ceil(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)) + 1;
        $request->days = $diff_in_days;
    
        $document = null;
        if($request->document){
            $attach = $request->document;
            $destinationPath = 'leaves/'; // Set your desired destination path
            $document = time().rand(1,998587899) . '.' . $attach->getClientOriginalExtension();
            $attach->move($destinationPath, $document);
        }
        // Create a new leave request
        $leave = new Leaves();
        $leave->user_id = $request->user_id ?? auth()->user()->id;
        $leave->request  = $request->reason;
        $leave->to_date = $request->to_date;
        $leave->from_date = $request->from_date;
        $leave->status = 0;
        $leave->type = $request->type;
        $leave->days = $request->days;
        $leave->document = $document;
        $leave->save();

        $to = "hr@adxventure.com";
        $bcc = "manjeetchand01@gmail.com";

        $senderName = $leave->users->name ?? auth()->user()->name;
        $senderEmail = $leave->users->email ?? auth()->user()->email;
        $subject = $senderName . ' Leave Request';

        $leave['to_date'] = date('d-m-Y', $timestamp2);
        $leave['from_date'] = date('d-m-Y', $timestamp1);
    

        $html_content = view('admin.email.leave-email', compact('leave'))->render();

        $fromName = 'Adxvenutre';
        $fromEmail = 'info@adxventure.com';
        // Set the email headers
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: $fromName <$fromEmail>\r\n";
        $headers .= "Bcc: $bcc\r\n"; // ✅ Add BCC properly
        // Send the email
        $response = mail($to, $subject, $html_content, $headers);
    
        // Handle the response
        if ($response) {
            $url = url('leave');
            return $this->success('created', 'Leave Request Send', $url);
        }else{
            return $this->success('error', 'Error');
        }
 
    }


    public function status(Request $request, $id, $status)
    {
        try {
            $leave = Leaves::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|max:150',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }

            $leave->status = ($status == 'reject') ? 2 : 1;
            $leave->remark = $request->reason;
            $leave->approved_hr_id = auth()->user()->id;
            $leave->save();

            // Email details
            $to = "hr@adxventure.com";
            $bcc = "manjeetchand01@gmail.com";
            $fromName = 'Adxventure';
            $fromEmail = 'info@adxventure.com';
            $fromDate = \Carbon\Carbon::parse($leave->from_date)->format('d M Y');
            $toDate = \Carbon\Carbon::parse($leave->to_date)->format('d M Y');
            if ($status == 'reject') {
                $subject = '❌ Leave Request Rejected';
                $html_content = "
                    <div style='font-family: Arial, sans-serif; padding: 10px;'>
                        <h2 style='color: #d9534f;'>Leave Rejected</h2>
                        <p><strong>Leave Period:</strong> {$fromDate} to {$toDate}</p>
                        <p><strong>Rejected By:</strong> " . auth()->user()->name . "</p>
                        <p><strong>Reason for Rejection:</strong> {$request->reason}</p>
                        <p style='color: #d9534f;'>Please contact HR for more information.</p>
                    </div>
                ";
            } else {
                $subject = '✅ Leave Request Approved';
                $html_content = "
                    <div style='font-family: Arial, sans-serif; padding: 10px;'>
                        <h2 style='color: #5cb85c;'>Leave Approved</h2>
                        <p><strong>Leave Period:</strong> {$fromDate} to {$toDate}</p>
                        <p><strong>Approved By:</strong> " . auth()->user()->name . "</p>
                        <p style='color: #5cb85c;'>Enjoy your leave and take care!</p>
                    </div>
                ";
            }

            // Set the email headers
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8\r\n";
            $headers .= "From: $fromName <$fromEmail>\r\n";
            $headers .= "Bcc: $bcc\r\n";

            // Send the email
            $mailResponse = mail($to, $subject, $html_content, $headers);

            return response()->json([
                'success' => true,
                'mail_sent' => $mailResponse
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ]);
        }
    }



    public function delete($id){
        $leave = Leaves::find($id);
        if($leave){
            $leave->delete();
            return response()->json([
                'message' => 'Leave delete successfully.',
            ]);
        }
    }
}
