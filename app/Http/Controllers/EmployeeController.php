<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Department, Roles, User, CustomRole, Leaves};
use Illuminate\Support\Facades\{Auth, DB, Session, Validator, Mail, Storage};
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    // --- Login as User ---
    public function user_login($id)
    {
        $user = User::findOrFail($id);
        Auth::login($user); 
        return redirect('/dashboard');
    }

    // --- Index with Yajra DataTables ---
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with(['department', 'roles'])
                ->whereNotIn('role_id', [1, 5]) // Exclude SuperAdmin/Clients
                ->select('users.*');

            // --- Server Side Filtering ---
            if ($request->filled('department')) {
                $query->where('department_id', $request->department);
            }
            if ($request->filled('status')) {
                $query->where('is_active', $request->status);
            }
            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('profile_image', function ($row) {
                    $src = $row->image;
                    return '<img src="'.$src.'" style="width:100px !important;height:120px;" class="img-fluid rounded"/>';
                })
                ->addColumn('name', function ($row) {
                    return '<b>' . ucfirst($row->name) . '</b>';
                })
                ->addColumn('department_role', function ($row) {
                    $dept = ucfirst($row->department->name ?? '');
                    $role = optional($row->roles->first())->name ?? 'No Approved Role';
                    return '<b>' . $dept . '</b><br>' . $role;
                })
                ->addColumn('contact_details', function ($row) {
                    return 'Email: <a href="mailto:'.$row->email.'"> '.$row->email.'</a> <br>Phone No: '.$row->phone_no;
                })
                ->editColumn('date_of_joining', function ($row) {
                    return date("d M, Y", strtotime($row->date_of_joining));
                })
                ->addColumn('date_of_birth', function ($row) {
                     return $row->date_of_birth ? date("d M, Y", strtotime($row->date_of_birth)) : '-';
                })
                ->editColumn('is_active', function ($row) {
                    if ($row->is_active == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    }
                    return '<span class="badge bg-danger">In-Active</span>';
                })
                ->addColumn('login_btn', function($row){
                    return '<a href="'.route('user.login', $row->id).'" class="btn btn-sm btn-primary">Login</a>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('users.edit', $row->id);
                    $logUrl = url('/logs/index/' . $row->id);
                    
                    $actionBtn = '<a href="'.$editUrl.'" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i> Edit</a> ';
                    
                    if ($row->is_active != 1) {
                        $url = url('/user/update/status/'.$row->id.'/1');
                        $actionBtn .= '<a href="'.$url.'" onclick="return confirm(\'Are you sure to activate?\');" class="btn btn-sm btn-success mt-1"><i class="fa fa-check"></i> Active</a>';
                    } else {
                        $url = url('/user/update/status/'.$row->id.'/0');
                        $actionBtn .= '<a href="'.$url.'" onclick="return confirm(\'Are you sure to deactivate?\');" class="btn btn-sm btn-danger mt-1"><i class="fa fa-times"></i> In-Active</a>';
                    }

                    $actionBtn .= '<br><a style="margin-top:10px;" href="'.$logUrl.'" class="btn btn-sm btn-danger"><i class="fa fa-list"></i> Activity Log</a>';
                    
                    return $actionBtn;
                })
                ->rawColumns(['profile_image', 'name', 'department_role', 'contact_details', 'is_active', 'login_btn', 'action'])
                ->make(true);
        }

        $departments = Department::orderBy('name', 'asc')->get();
        // Return view without data, data comes via AJAX
        return view('admin.employee.index', compact('departments'));
    }

    public function create()
    {
        $user = auth()->user();
        $query = CustomRole::query();

        if ($user->hasRole('Super-Admin')) {
            // No filter
        } elseif ($user->hasRole('Admin')) {
            $query->whereNotIn('name', ['Super-Admin', 'Admin']);
        } else {
            $query->where('name', '!=', 'Super-Admin');
        }
        
        $designation = $query->select('id', 'name')->get();
        $department = Department::orderBy('id', 'desc')->get();
        
        return view('admin.employee.create', compact('department', 'designation')); 
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users',
            'profile_image'   => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone_no'        => 'required|unique:users|numeric|digits:10',
            'date_of_joining' => 'required|date',
            'skills'          => 'required|string|max:255',
            'designation'     => 'required|exists:roles,id',
            'department'      => 'required|exists:department,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::beginTransaction();
        try {
            $data = $request->except('profile_image', 'designation');
            $data['user_id'] = auth()->id();
            $data['role_id'] = $request->designation;
            $data['department_id'] = $request->department;
            $data['date_of_joining'] = Carbon::parse($request->date_of_joining)->format('Y-m-d');
            $data['status'] = 1;
            // Provide default password if needed, or handle elsewhere
            $data['password'] = bcrypt('password'); 
        
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('profile'), $filename);
                $data['image'] = $filename;
            }
        
            $user = User::create($data);
            $role = CustomRole::findById($request->designation);
            $user->assignRole($role);
            
            DB::commit();
            return response()->json(['success' => 'User created successfully', 'redirect_url' => route('users.index')]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error creating user: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $roleId = auth()->user()->role_id;
        $designationQuery = Roles::query();

        if ($roleId == 1) {
             $designationQuery->whereNotIn('id', [1, 5]);
        } elseif ($roleId == 2) {
             $designationQuery->whereNotIn('id', [1, 2]);
        } elseif ($roleId == 3) {
             $designationQuery->whereNotIn('id', [1, 2, 3, 5]);
        } else {
             $designationQuery->whereIn('id', [3, 4, 5]);
        }

        $designation = $designationQuery->get();
        $department = Department::orderBy('id', 'desc')->get();
        $data = User::findOrFail($id);    

        return view('admin.employee.edit', compact('department', 'designation', 'data'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required',
            'email'           => 'required|email|unique:users,email,' . $id,
            'profile_image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone_no'        => 'required|numeric|digits:10|unique:users,phone_no,' . $id,
            'date_of_joining' => 'required|date',
            'skills'          => 'required',
            'designation'     => 'required|exists:roles,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        $user = User::findOrFail($id);
        $data = $request->except(['password', 'profile_image']);
        $data['role_id'] = $request->designation;
        $data['date_of_joining'] = Carbon::parse($request->date_of_joining)->format('Y-m-d');
    
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profile'), $filename);
            $data['image'] = $filename;
        }
    
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
    
        $user->update($data);
        $role = CustomRole::findById($data['role_id']);
        $user->syncRoles([$role]);
    
        Session::flash('success', 'User updated successfully.');
        // Assuming $this->success is a custom Helper trait you use
        return response()->json(['success' => 'updated', 'redirect_url' => route('users.index')]); 
    }
    
    public function updateStatus($id, $status)
    {
        $user = User::findOrFail($id);
        $user->is_active = $status;
        $user->save();

        $msg = ($status == 1) ? 'Activated' : 'De-Activated';
        $redirectRoute = ($user->role_id == 5) ? 'user.client.index' : 'users.index';

        return redirect()->route($redirectRoute)->with('message', "Success! User $msg Successfully.");
    }

    // --- Optimized Offer Letter Logic ---
    public function offer_letter_edit(Request $request, $id)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($id)],
            'phone_no' => ['required', 'numeric', 'digits:10', Rule::unique('users')->ignore($id)],
            'role'     => 'required',
        ];

        if ($request->filled('before_ctc')) {
            $rules += [
                'before_ctc' => 'required|numeric', 'before_period' => 'required|numeric',
                'after_ctc' => 'required|numeric', 'after_period' => 'required|numeric',
            ];
        } else {
            $rules += ['ctc' => 'required|numeric', 'period' => 'required|numeric'];
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = $request->only(['name', 'email', 'phone_no', 'role', 'ctc', 'period', 'before_ctc', 'before_period', 'after_ctc', 'after_period']);
        $data['date'] = Carbon::today();

        // Generate PDF
        $html = view('admin.offer-letter.mail', compact('data'))->render();
        if (empty($html)) { return response()->json(['error' => 'HTML Generation Failed'], 500); }

        $pdf = PDF::loadHTML($html);
        $dir = "offer-letter/pdf/" . date('Y') . "/" . date('m');
        if (!file_exists(public_path($dir))) { mkdir(public_path($dir), 0755, true); }
        
        $pdfPathRelative = $dir . '/' . $request->name . '_offer_' . date('Ymd_His') . '.pdf';
        $pdf->save(public_path($pdfPathRelative));

        // Send Email using Laravel Mail Facade (Clean & Robust)
        $attachments = [
            public_path($pdfPathRelative),
            public_path("offer-letter/Security.pdf"),
            public_path("offer-letter/SOP-leave Policy.pdf")
        ];

        try {
            Mail::send([], [], function ($message) use ($request, $attachments) {
                $message->to('manjeetchand01@gmail.com') // Should use $request->email in production?
                        ->cc('hr@adxventure.com')
                        ->subject('We Welcome You to Adxventure Family! | Offer Letter')
                        ->from('no-reply@adxventure.com', 'Adxventure');

                // HTML Body
                $body = '<p>Hello ' . strtoupper($request->name) . ',</p>
                         <p>Thank you for exploring career opportunities with AdxVenture...</p>
                         <p><b>Documents required:</b> Signed offer letter, Photos, Bank Details, Aadhar, PAN, Marksheets.</p>
                         <p>Thank you,<br>Adxventure<br>Dehradun</p>';
                
                $message->html($body);

                foreach ($attachments as $file) {
                    if (file_exists($file)) $message->attach($file);
                }
            });

            // Update DB
            DB::table('users')->where('id', $id)->update([
                'name' => $request->name, 'email' => $request->email, 'phone_no' => $request->phone_no,
                'offer_letter' => $pdfPathRelative, 'offer_letter_status' => 1, 'user_id' => auth()->id(),
            ]);

            return response()->json(['success' => 'Updated', 'redirect_url' => route('offer.letter')]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Mail Error: ' . $e->getMessage()], 500);
        }
    }
}