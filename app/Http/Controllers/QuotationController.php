<?php
namespace App\Http\Controllers;
use App\Client;
use Illuminate\Support\Facades\Mail;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Models\{Invoice,Role,User,Work,Payment,ProjectCategory,lead,Category,TotalAmount,Projects,Followup,Template,Email,Office,Country,CustomRole,Bank,Proposal,Expenses,ProjectInvoice,Message};
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use App\Helpers\EncodingHelper;



class QuotationController extends Controller
{

    public function client($id){
        $lead = Lead::with('category')->findorfail($id);
        $projectCategories = ProjectCategory::all();
        $services = ProjectCategory::orderBy('name', 'ASC')->get(['name','id']);
        $categories = Category::orderBy('name', 'ASC')->get(['name', 'category_id']);
        $countries = Country::orderBy('nicename', 'asc')->get(['id', 'nicename', 'phonecode']);
        
        $user = auth()->user(); // Access the stored user property
    
        if ($user->hasRole(['Super-Admin', 'Admin'])) {
            // Get the roles you want to filter by
            $roleIds = Role::whereIn('name', ['Marketing-Manager', 'BDE', 'Business Development Intern'])->pluck('id');
            // Get users with those roles
            $users = User::whereHas('roles', function ($query) use ($roleIds) {
                $query->whereIn('role_id', $roleIds);
            })->get();
        } elseif ($user->hasRole('Marketing-Manager')) {
            // Get the roles you want to filter by
            $roleIds = Role::whereIn('name', ['BDE', 'Business Development Intern'])->pluck('id');
            // Get users with those roles
            $users = User::whereHas('roles', function ($query) use ($roleIds) {
                $query->whereIn('role_id', $roleIds);
            })->get();
        } else {
            $users = collect();
        }
        return view('admin.crm.prposal.client',compact('lead','projectCategories','services','categories','countries','users'));
    }


}

