<?php

namespace App\Http\Controllers\settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Api;


class ApiSettingsController extends Controller
{
    public function index(){
        $apis = Api::all();
        return view('admin.settings.api.index',compact('apis'));
    }

    public function store(Request $request){
        # 1 validate data
        $validator = Validator::make($request->all(), [
            'api_name' => 'required|string|min:2|max:50',
            'api_url' => 'required|string|min:2|max:200',
            'api_key' => 'required|string|min:2|max:100|unique:apis,key',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try{
            Api::create([
                'name' => $request->api_name,
                'url' => $request->api_url,
                'key' => $request->api_key,
            ]);
            return response()->json(['success' => 'Api add successfully']);
        }catch(\Exception $e){
            return response()->json(['errors' => 'Somthing went wrong, Please try again later.']);
            // return response()->json(['errors' => $e->getMessage()]);
        }
    }


    public function update(Request $request,$id){
        # 1 validate data
        $validator = Validator::make($request->all(), [
            'api_name' => 'required|string|min:2|max:50',
            'api_url' => 'required|string|min:2|max:200',
            'api_key' => 'required|string|min:2|max:100|unique:apis,key,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try{
            $api = Api::find($id);
            $api->update([
                'name' => $request->api_name,
                'url' => $request->api_url,
                'key' => $request->api_key,
            ]);
            return response()->json(['success' => 'Api update successfully']);
        }catch(\Exception $e){
            return response()->json(['errors' => 'Somthing went wrong, Please try again later.']);
            // return response()->json(['errors' => $e->getMessage()]);
        }
    }
}
   