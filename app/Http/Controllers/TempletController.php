<?php
namespace App\Http\Controllers;
use App\Client;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Models\{Invoice,User,Work,Payment,Projects,Bank,Template};
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TempletController extends Controller
{
    public function index(){
        $templets = Template::orderBy('id','desc')->get();
        $projects = Projects::orderBy('name','asc')->get();
        return view('admin.templets.index',compact('templets','projects'));
    }

    public function category(Request $request)
    {
        if ($request->category === 'project') {
            $projects = Projects::orderBy('name', 'asc')
                ->where('status', '!=', 0)
                ->get();
            return response()->json(['projects' => $projects]);
        }
    }   

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'title' => 'required',
            'type' => 'required|numeric',
            'description' => 'required', 
        ]);
    
        if ($request->category === 'project') {
            $validator = Validator::make($request->all(), [
                'project' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        $templet = new Template();
        $templet->title = $request->input('title');
        $templet->type = $request->input('type');
        $templet->message = $request->input('description');
        $templet->category = $request->input('category');
        
        if ($request->category === 'project') {
            $templet->project_id = $request->input('project');
        }
        $url = url('templets');
        if ($templet->save()) {
            return $this->success('created','',$url);
        }
        return $this->error('created','',$url);
    }


    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'title' => 'required',
            'type' => 'required|numeric',
            'description' => 'required',
        ]);
    
        if ($request->category === 'project') {
            $validator = Validator::make($request->all(), [
                'project' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        $templet = Template::find($id);
        $templet->title = $request->input('title');
        $templet->type = $request->input('type');
        $templet->message = $request->input('description');
        $templet->category = $request->input('category');
        
        if ($request->category === 'project') {
            $templet->project_id = $request->input('project');
        }
        $url = url('templets');
        if ($templet->save()) {
            return $this->success('created','',$url);
        }
        return $this->error('created','',$url);
    }

    public function delete($id){
        $templet = Template::findorfail($id);
        if($templet->delete()){
            return back()->with('message','Template Delete Successfully');  
        }

    }
    

}