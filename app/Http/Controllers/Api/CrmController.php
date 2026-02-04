<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\{User,Lead,Followup};
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables; 



class CrmController extends Controller
{
    public function leads()
    {
        try{
            $user = auth()->user();
            $query = Lead::with('Followup', 'countries');
    
            // Filter for BDE role
            if ($user && $user->hasRole('BDE')) {
                $query->where('assigned_user_id', $user->id);
            }
    
            $leads = $query->orderBy('id', 'DESC')->paginate(20);
            return response()->json([
                'success' => true,
                'total_data' => $leads->total(),
                'data' => $leads,
                'message' => $leads->count() ? 'Leads successfully retrieved.' : 'Leads not found.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while retrieving leads.',
                // 'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function lead_profile($id){
        try{
            $lead = Lead::with('Followup', 'countries')->findorFail($id);
            return response()->json([
                'success' => true,
                'data' => $lead,
                'message' => $lead ? 'Lead successfully retrieved.' : 'Lead not found.',
            ], 200);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while retrieving lead.',
                // 'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function followup(Request $request){

        $rules = [
            'lead_id' => 'required|numeric',
            'reason' => 'required|string',
        ];

        // If reason is 'Other', remark is required
        if (in_array($request->reason,['Other'])){
            $rules['remark'] = 'required|string|min:5|max:100';
        }
            
        // If reason is NOT one of the following, require next_date and next_time
        $skipNextDateReasons = [
            'call Me Tomorrow', 'Not interested', 'Wrong Information',
            'Not pickup', 'Payment Tomorrow', 'Interested', 'Work with other company'
        ];

        if (!in_array($request->reason, $skipNextDateReasons)) {
            $rules['next_date'] = 'required|date';
            $rules['next_time'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try{
            $nextDateTime = $request->next_date . ' ' . $request->next_time;

            $followup = new Followup();
            $followup->lead_id = $request->lead_id;
            $followup->user_id = auth()->user()->id;
            $followup->reason = $request->reason;
            $followup->remark = $request->remark;
            $followup->delay = 0;
            if ($request->next_date) {
                $followup->next_date = date('Y-m-d H:i:s', strtotime($request->next_date));
            } elseif ($request->reason === 'call Me Tomorrow') {
                $followup->next_date = date('Y-m-d H:i:s', strtotime('+1 day ' . $request->next_time));
            } elseif ($request->reason === 'call back later' ||  $request->reason === 'Not pickup' || $request->reason === 'Payment Tomorrow' || $request->reason === 'Interested' ) {
                $now = Carbon::now();
                if ($now->hour < 13) { // Before 1 PM
                    // Set the next date to today at 3 PM
                    $followup->next_date = Carbon::today()->setTime(15, 0)->format('Y-m-d H:i:s');
                } else { // After 1 PM
                    // Set the next date to tomorrow at 10 AM
                    $followup->next_date = Carbon::tomorrow()->setTime(10, 0)->format('Y-m-d H:i:s');
                }   
            }
            $followup->save();
            return response()->json([
                'success' => true,
                'message' => 'Folloup successfully submit.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while retrieving leads.',
                'error' => $e->getMessage(),
            ], 500);
        }
              
    }



}