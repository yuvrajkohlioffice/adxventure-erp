<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,lead,Category};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;


class ClientController extends Controller
{       

    public function index(Request $request){
        $data = User::query();
        $data->with('project:id,name,client_id');
        $data->where('role_id','5');  
        
        if($request->name){
            $data->where('name','LIKE','%'.$request->name.'%');
        }
         
        if($request->status || $request->status == "0"){
            $data->where('status',$request->status);
        }
        
        $data = $data->orderBy('id', 'desc')->paginate('20');
        
        return  view('admin.client.index',compact('data'));
    }

    public function create($id = null){
        $categories = Category::orderBy('name','asc')->get();
        if (isset($id)) {
            $lead = Lead::find($id);
            return view('admin.client.create', compact('lead','categories'));
        } else {
            return view('admin.client.create',compact('categories'));
        }
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_no' => 'required|unique:users|numeric|digits:10',
            'client_category' =>'required|numeric',
            'address' => 'required',
            'city' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['role_id'] = "5";
        $data['address'] = $request->address;
        $data['city'] = $request->city;
        $data['name'] =$request->name;
        $data['phone_no'] =$request->phone_no;
        $data['email'] =$request->email;
        $data['client_category'] = $request->client_category;
        $data['password'] = Hash::make($request->password);
        
        $response = User::create($data);
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: tms@adxventure.com";
        
        $to = $request->email;
        $subject = 'Adxventure Client add email';
        $name = $request->name;
        
        $loginUrl = asset('login');
        
        $html = '<html>
                <head>
                    <title>' . $subject . '</title>
                </head>
                <body style="font-family: Arial, sans-serif;">
        
                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f9f9f9;">
                    <tr>
                        <td align="center">
                            <table width="600" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                                <tr>
                                    <td style="background-color: #0d6efd; color: white; text-align: center; padding: 20px;">
                                        <h1 style="margin: 0;">Welcome to Adxventure</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #ffffff; color: #333; padding: 30px; text-align: left;">
                                        <p style="font-size: 18px; margin-bottom: 20px;">Dear ' . $name . ',</p>
                                        <p style="font-size: 16px;">Congratulations on becoming a part of Adxventure!</p>
                                        <p style="font-size: 16px;">We are thrilled to have you as our valued client, and we appreciate your trust in us.</p>
                                        <p style="font-size: 16px;">Our team is dedicated to providing you with an exceptional experience, and we look forward to assisting you in achieving your goals.</p>
                                        <p style="font-size: 16px;">To get started, please check your account by clicking on the following link:</p>
                                        <a href="' . $loginUrl . '" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px;">
                                            Account Login Url
                                        </a>
                                        <h5>Login Credentials</h5>
                                        <p> Email   : '. $request->email .'</p>
                                        <p> Password : '. $request->password .'</p>
                                        
                                        <p style="font-size: 16px;">If you have any questions or need assistance, feel free to reach out to our support team.</p>
                                        <p style="font-size: 16px;">Best regards,</p>
                                        <p style="font-size: 16px;">The Adxventure Team</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #0d6efd; color: white; text-align: center; padding: 10px;">
                                        <p style="font-size: 14px;">&copy; 2024 <a href="https://adxventure.com/" style="color: white; text-decoration: none;">Adxventure</a></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
        
            </body>
            </html>';

        
        mail($to, $subject, $html, $headers);
    
        if($response){
            $url = route('lead.prposel.service',['leadId'=>$response->id,'id'=>1]);
            return $this->success('created','Client ',$url);
        }
        return $this->success('error','Client ');
    }

    public function EditClient($id){
        $data = User::find($id);
        return  view('admin.client.edit',compact('data')); 

    }

    public function updateClient(Request $request,$id){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,id',
            'phone_no' => 'required|numeric|digits:10|unique:users,id',
            // 'company_name' => 'required|max:200',
            // 'company_gst' => 'nullable|max:200'
            'address' => 'required',
            'city' => 'required'
        ]);

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['role_id'] = "5";
        $data['address'] = $request->address;
        $data['city'] = $request->city;

        if(is_null($data['password'])){
            unset($data['password']);
        }
        $response = User::find($id)->update($data);
        if($response){
            $url = url('/user/client/index');
            return $this->success('created','Client ',$url);
        }

        return $this->success('error','Client ');
    }
    
    public function mail(){
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // $headers .= 'From: Your Name <your@email.com>' . "\r\n";
        
        $to = 'nick123@mailinator.com';
        $subject = 'Your Email Subject';
        
        if(mail($to, $subject, $htmlContent, $headers)){
            echo "Email sent successfully";
        } else{
            echo "Email sending failed";
        }   
    }


    public function clientIndex() {
        $data = User::where('client_status','0')
                    ->where('role_id',5)
                    ->paginate(20);

        return  view('admin.client.client',compact('data'));

    }

    public function my_client(Request $request)
    {
        $clientIds = Lead::whereNotNull('client_id')
            ->where('status', 1)
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('assigned_user_id', auth()->id());
            })
            ->pluck('client_id')
            ->unique();

        $users = User::with('project:id,name,client_id')
            ->whereIn('id', $clientIds)
            ->get();

        if ($request->ajax()) {
            return DataTables::of($users)
                ->editColumn('client_info', function ($user) {
                    $html = "<div class='order-md-1'>
                        <h6 class='mb-1 text-dark fs-15 lead-name fw-bold'>" . substr(ucfirst($user->name), 0, 20) . "..</h6>
                        <small class='mb-1 text-muted fs-15 w-bold'><i class='bi bi-telephone'></i> {$user->phone_no}</a></small><br>
                        <small><a href='mailto:{$user->email}' class='text-muted'><i class='bi bi-envelope'></i> {$user->email}</a></small>";
               

                    $html .= "</div>";
                    return $html;
                })
                ->addColumn('projects', function ($user) {
                    return 'All Projects('. $user->project->count().')'; 
                })
                ->addColumn('actions', function ($user) {
                    $url = route('crm.upsale', $user->id);
                    return '<a class="btn btn-sm btn-warning" href="' . $url . '">Upsale</a>';
                })
                ->rawColumns(['client_info', 'projects', 'actions'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.client.my_client', compact('users'));
    }
       
}
