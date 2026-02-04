<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Lead,Category,Expenses};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use DB;

class ExpensesController extends Controller
{       
    public function index(Request $request)
    {
        if ($request->has('name')) {
            $expenses = Expenses::join('expenses_type', 'expenses.type', '=', 'expenses_type.id')
                ->select('expenses.*', 'expenses_type.name as type_name')
                ->where(function($query) use ($request) {
                    $query->where('expenses_type.name', $request->name)
                        ->orWhere('expenses.name', $request->name);
                })
                ->paginate(20);
        } else {
            $expenses = Expenses::join('expenses_type', 'expenses.type', '=', 'expenses_type.id')
                ->select('expenses.*', 'expenses_type.name as type_name')
                ->paginate(10);
        }

        $typs = DB::table('expenses_type')->get();
        $totalExpenses = Expenses::sum('price');
        return view('admin.expenses.index', compact('expenses', 'typs', 'totalExpenses'));
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required|max:100',
            'price' => 'required|numeric',
            'date' =>'required',
            'type' =>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $expenses =  new Expenses();
        $expenses->name = $request->name;
        $expenses->description = $request->description;
        $expenses->price = $request->price;
        $expenses->date = $request->date;
        $expenses->type = $request->type;
        if($expenses->save()){
            $url = url('/expenses');
            return $this->success('Creatd','Expenses Added',$url);
        }else{
            abort(503);
        }
    }


    public function type(Request $request)
    {  
        $validator = Validator::make($request->all(),[
        'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        DB::table('expenses_type')->insert([
            'name' => $request->name,
        ]);

        $url = url('/expenses');
        return $this->success('Creatd','Expenses Added',$url);
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required|max:100',
            'price' => 'required|numeric',
            'date' =>'required',
            'type' =>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $expenses = Expenses::find($id);
        if(!$expenses){
            abort(503);
        }
        $expenses->name = $request->name;
        $expenses->description = $request->description;
        $expenses->price = $request->price;
        $expenses->date = $request->date;
        $expenses->type = $request->type;
        if($expenses->save()){
            $url = url('/expenses');
            return $this->success('Creatd','Expenses Added',$url);
        }else{
            abort(503);
        }
    }

    public function destroy($id){
       
    }


    public function show($id){
        $expenses = Expenses::find($id);
        $expenses->delete();
        return back()->with('message','Expenses Delete Successfully');
    }


    public function summery(Request $request)
    {
        $year = $request->input('year');
        $currentMonth = now()->month; // Get the current month
    
        // Fetch expenses for the selected year and group by month
        $expenses = Expenses::selectRaw('MONTH(date) as month, SUM(price) as total_amount')
            ->whereYear('date', $year)
            ->groupBy('month')
            ->havingRaw('SUM(price) > 0') // Include only months with expenses
            ->orderBy('month')
            ->get();
    
        // Define all months
        $months = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];
    
        // Prepare result data with default 0 values for each month
        $result = [];
        foreach ($months as $key => $monthName) {
            // Only include months less than or equal to the current month
            if ($key <= str_pad($currentMonth, 2, '0', STR_PAD_LEFT)) {
                $result[$monthName] = $expenses->where('month', $key)->first()->total_amount ?? 0;
            }
        }
    
        return response()->json($result);
    }


    public function details(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
    
        // Join with the expenses_type table and apply filtering
        $expenses = Expenses::join('expenses_type', 'expenses.type', '=', 'expenses_type.id')
            ->select('expenses.*', 'expenses_type.name as type_name')
            ->whereYear('expenses.date', $year)
            ->whereMonth('expenses.date', date('m', strtotime($month)))
            ->get(); // Use get() to retrieve all matching records
    
        return response()->json($expenses);
    }   
    
    

    


}