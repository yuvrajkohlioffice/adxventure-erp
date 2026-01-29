<?php
namespace App\Http\Controllers;

use App\Models\{Office};
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;


class OfficeController extends Controller
{

    public function index(){
        $offices = Office::orderBy('name','asc')->get();
        return view('admin.office.index',compact('offices'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'tax_no' => 'nullable',
            'zip_code' => 'nullable|numeric',
            'city' => 'required',
            'country' => 'required',
            'state' => 'nullable',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $office = new Office();
        $office->name = $request->name;
        $office->email = $request->email;
        $office->phone = $request->phone;
        $office->tax_no = $request->tax_no ?? '';
        $office->zip_code = $request->zip_code ?? 0;
        $office->city = $request->city;
        $office->state = $request->state ?? '';
        $office->country = $request->country;
        $office->address = $request->address;
        $url = url('office');
        if($office->save()){
         return $this->success('created','',$url);
        }
        return $this->error('created','',$url);
    }

    public function Update(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'tax_no' => 'nullable',
            'zip_code' => 'nullable|numeric',
            'city' => 'required',
            'country' => 'required',
            'state' => 'nullable',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $office = Office::findorfail($id);
        $office->name = $request->name;
        $office->email = $request->email;
        $office->phone = $request->phone;
        $office->tax_no = $request->tax_no ?? '';
        $office->zip_code = $request->zip_code ?? '';
        $office->city = $request->city;
        $office->state = $request->state ?? '';
        $office->country = $request->country;
        $office->address = $request->address;
        $url = url('office');
        if($office->save()){
         return $this->success('updated','',$url);
        }
        return $this->error('updated','',$url);
    }

    public function show($id){
        $office = Office::findorfail($id);
        $office->delete();
        return back()->with('message','Office Delete Succesfully');
    }   
}