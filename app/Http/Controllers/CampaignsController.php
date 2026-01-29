<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Campaign,CampaignRecipient};
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DateTime;

use DB;
use Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\CampaignLeadImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Events\CampaignStarted; 
use Illuminate\Support\Facades\Bus;




class CampaignsController extends Controller
{

    public function index(){
        $campaigns = Campaign::all();
        return view('admin.campaign.index',compact('campaigns'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:50',
            'type' => 'required|in:email,whatsapp',
            'message' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try{
            Campaign::create($request->only('name','type','message'));
            return response()->json(['success' => 'Campaign created successfully']);
        }catch(\Exception $e){
            return response()->json(['errors' => 'Somthing went wrong, Please try again later.']);
            // return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function show(Request $request, $id){
        $campaign = Campaign::find($id);
        if($request->ajax()){
            $query = CampaignRecipient::where('campaign_id', $id);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('recipient', function ($row) {
                return $row->email ?? $row->phone;
            })

            ->editColumn('status', function ($row) {
                $color = match ($row->status) {
                    'sent' => 'success',
                    'failed' => 'danger',
                    'processing' => 'info',
                    default => 'warning'
                };
                return "<span class='badge bg-{$color}'>"
                        . ucfirst($row->status) .
                       "</span>";
            })

            ->editColumn('failed_reason', function ($row) {
                return $row->failed_reason ?? '-';
            })

            ->rawColumns(['status'])
            ->make(true);
        }
        
        return view('admin.campaign.show',compact('campaign'));
    }



    public function import(Request $request,$campaignId){
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,xlsx',
            'channel' => 'required|in:email,whatsapp'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try{
            Excel::import(new CampaignLeadImport($campaignId, $request->channel), $request->file('file'));
            return response()->json(['success' => 'Leads imported successfully']);
        }catch(\Exception $e){
            return response()->json(['errors' => 'Somthing went wrong, Please try again later.']);
            // return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function start($campaignId)
    {
        try {
            $campaign = Campaign::findOrFail($campaignId);

            // Prevent re-start
            if ($campaign->status !== 'pending') {
                return response()->json([
                    'status' => false,
                    'message' => 'Campaign already started'
                ]);
            }

            // Update status immediately
            $campaign->update([
                'status' => 'processing'
            ]);

            // Fire background event
            dispatch(function () use ($campaign) {
                event(new CampaignStarted($campaign));
            })->afterResponse();

            // 🔥 IMPORTANT: return immediately
            return response()->json([
                'status' => true,
                'message' => 'Campaign started'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}