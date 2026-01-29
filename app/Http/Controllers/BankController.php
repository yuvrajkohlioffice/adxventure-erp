<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Projects,Tasks,Category,ProjectCategory,Work,Bank};
use Illuminate\Support\Facades\Validator;


class BankController extends Controller
{
    public function index(){
        $banks = Bank::all();
        return view('admin.bank.index',compact('banks'));
    }
    
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'account_holder_name' => 'required',
            'account_no' => 'required|numeric|unique:bank,account_no',
            'ifsc' => 'required',
            'scanner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bank = new Bank();
        $bank->bank_name = $request->bank_name;
        $bank->user_id = auth()->user()->id;
        $bank->holder_name = $request->account_holder_name;
        $bank->account_no = $request->account_no;
        $bank->ifsc = $request->ifsc;
        $bank->gst = $request->gst;
        $bank->status = '1';

        if ($request->hasFile('scanner')) {
            $image = $request->file('scanner');
            $currentYear = date('Y');
            $currentMonth = date('m');
            $storagePath = "images/scanner/{$currentYear}/{$currentMonth}/";
            $fileName = time() . '_' . $image->getClientOriginalName();
            $image->move($storagePath, $fileName);
            $bank->scanner = $storagePath . $fileName; 
        }
        if($bank->save()){
            return redirect()->back()->with('message','Bank Details Add Successfully'); 
        }else{
            return redirect()->back()->with('error','Bank Details  Not Add Successfully');
        } 
    }

    public function update(Request $request,$id){
  
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'account_holder_name' => 'required',
            'account_no' => 'required|numeric',
            'ifsc' => 'required',
            'gst' => 'required',
            'scanner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bank = Bank::find($id);
        $bank->bank_name = $request->bank_name;
        $bank->user_id = auth()->user()->id;
        $bank->holder_name = $request->account_holder_name;
        $bank->account_no = $request->account_no;
        $bank->ifsc = $request->ifsc;
        $bank->gst = $request->gst;
        $bank->status = '1';

        if ($request->hasFile('scanner')) {
            $image = $request->file('scanner');
            $currentYear = date('Y');
            $currentMonth = date('m');
            $storagePath = "images/scanner/{$currentYear}/{$currentMonth}/";
            $fileName = time() . '_' . $image->getClientOriginalName();
            $image->move($storagePath, $fileName);
            $bank->scanner = $storagePath . $fileName; 
        }

        if($bank->save()){
            return redirect()->back()->with('message','Bank Details Update Successfully'); 
        }
        else{
            return redirect()->back()->with('error','Bank Details  Not Update  Successfully');
        }    
    }

    public function getBankDetail(Request $request){
        $data = $request->gst;
        $banks = Bank::where('gst', $data)->where('verify',1)->where('status',1)->get();
        return response()->json(['banks' => $banks]);
    }

    public function status($id,$status){
        $bank = Bank::find($id);
        $bank->status = $status;
        if($status == 1){
            if($bank->save()){
                return redirect()->back()->with('message','Bank Details Active');
            }
        }elseif($status == 0){
            if($bank->save()){
                return redirect()->back()->with('message','Bank Details Inactive');
                }
        }
        else{
            return redirect()->back()->with('message','Status not Change');
        }        
    }

    public function verified($id){
       $bank = Bank::findorfail($id);
        $bank->verify = 1;
        if($bank->save()){
            return redirect()->back()->with('message','Bank Verified Successfully');
        }
    }
}