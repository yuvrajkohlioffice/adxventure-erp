<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Lead,Department};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class DepartmentController extends Controller
{     

    public function index(){
        $departments = Department::orderBy('name','asc')->paginate(20);
        return view('admin.department.index',compact('departments'));
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'departments' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $departments = new Department();
        $departments->name = $request->departments;
        if($departments->save()){
        $url = route('departments');
        return $this->success('created', 'Leave Request Send', $url);
       }else{
            abort(503);
       }
    }


    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'departments' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    
        // Find the department or fail
        $departments = Department::findOrFail($id);
    
        // Update the department name
        $departments->name = $request->departments;
    
        // Save the department and return a response
        if ($departments->save()) {
            $url = route('departments');
            return $this->success('updated', 'Department Updated', $url);
        } else {
            abort(503);
        }
    }
    

    public function status($id,$status){
        $departments = Department::findOrFail($id);
        $departments->status = $status;
        if ($departments->save() ){
            if($status == 1){
                return back()->with('message','Department Active Successfully');
            }else{
                return back()->with('message','Department De-Active Successfully');
            }
        } else{
            abort(503);
        }

    }

    public function delete($id){
        $departments = Department::findOrFail($id);
        if($departments->delete()){
            return back()->with('message','Department Deleted Successfully');
        }else{
            abort(503);
        }
    }


}