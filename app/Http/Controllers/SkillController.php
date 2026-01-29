<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Skill};
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

class SkillController extends Controller
{

    public function index(Request $request){
        if($request->has('skill')){
            $skills = Skill::where('name',$request->skill)->paginate(10);
        }else{
            $skills = Skill::paginate(10);
        }
        return view('admin.skill.index',compact('skills'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $skill = new Skill();
        $skill->name = $request->name;
        $skill->status =1;
        if($skill->save()){
            $url = url('/skills');
            return $this->success('Created','Skill Add Successfully',$url);
        }else{
            abort(503);
        }
    }

    public function update(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $skill = Skill::findorfail($id);
        if(!$skill){
            abort(503);
        }

        $skill->name = $request->name;
        $skill->status =1;
        if($skill->save()){
            $url = url('/skills');
            return $this->success('Update','Skill Updated Successfully',$url);
        }else{
            abort(503);
        }
    }

    public function show($id){
        $skill = Skill::findorfail($id);
        if(!$skill){
            abort(503);
        }
        if($skill->delete()){
            return back()->with('message','Skill Deleted Successfully !!');
        }else{
            abort(503);
        }
    }
}