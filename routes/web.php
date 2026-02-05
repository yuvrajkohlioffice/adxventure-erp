<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    HomeController,
    EmployeeController,
    ProjectsController,
    ReportController,
    TaskController,
    AdminController,
    ClientController,
    LogsController,
    UserLeaveController,
    AdminInvoice,
    CrmController,
    leadController,
    ProjectCategoryController,
    CategoryController,
    BankController,
    TempletController,
    PdfController,
    ExpensesController,
    PermissionController,
    DepartmentController,
    DashboardController,
    SkillController,
    OfficeController,
    FollowupController,
    CandidateController,
    DatatableController,
    PaymentController,
    QuotationController,
    CronController,
    CampaignsController
};
use App\Http\Controllers\settings\{ApiSettingsController};
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Models\User;
use App\Models\{Role, lead, Api};
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Services\Core;
use Illuminate\Support\Facades\Artisan;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require __DIR__ . '/auth.php';
require_once app_path('Helpers/helpers.php');


Route::get('/clear-cache', function () {
    $output = [];

    try {
        // 1. Clear everything using the core optimize command
        Artisan::call('optimize:clear');
        $output[] = "Step 1: " . Artisan::output();

        // 2. Clear individual caches just to be safe
        Artisan::call('config:clear');
        $output[] = "Step 2: Config cache cleared.";

        Artisan::call('cache:clear');
        $output[] = "Step 3: Application cache cleared.";

        Artisan::call('view:clear');
        $output[] = "Step 4: Compiled views cleared.";
        
        Artisan::call('route:clear');
        $output[] = "Step 5: Route cache cleared.";

        // 3. Optional: Try to run dump-autoload (Requires shell_exec enabled on server)
        if (function_exists('shell_exec')) {
    // We move up one directory (../) before running composer
    $composerOutput = shell_exec('cd .. && composer dump-autoload 2>&1');
    $output[] = "Step 6 (Composer): " . $composerOutput;
} else {
            $output[] = "Step 6: shell_exec is disabled. Please run 'composer dump-autoload' in terminal.";
        }

        // Professional Output
        echo "<h1>🚀 TMS System Maintenance</h1>";
        echo "<pre style='background:#f4f4f4; padding:20px; border-left:5px solid #28a745;'>" . implode("\n", $output) . "</pre>";
        echo "<br><b style='color:green;'>Chalo ho gya mere bhai!!</b> System is now fresh.";
        
    } catch (\Exception $e) {
        return "Oops! Kuch error aa gaya: " . $e->getMessage();
    }
});

Route::get('verify-email', EmailVerificationPromptController::class)
    ->middleware('auth')
    ->name('verification.notice');



Route::get('demo-email', function () {
    $result = Core::sendMail(
        'yuvrajkohli8090ylt@gmail.com',
        'Test Subject',
        'Welcome to TMS',
        'This is the main message body.',
        'Adxventure ERP Team',
        'work@adxventure.com'
    );

    if ($result === true) {
        return "Email Sent Successfully via Core Service!";
    } else {
        return "Error: " . $result;
    }
});

Route::get('/send-test-mail', function () {
    $subject = "Web Dev Testing in Eve – ";
    $header = "Late Arrival Report";
    $date = now()->format('d M Y');
    $scheduled_time = '09:30 AM';
    $arrival_time = session('login_time');
    $delay_duration = gmdate('H:i:s', strtotime($arrival_time) - strtotime($scheduled_time));
    $reason = $request->reason ?? 'Not specified';
    $count = $count ?? 1;

    // =============================
    // 📩 HR Mail
    // =============================
    $message = "
        <p>Dear HR Team,</p>
        <p>This is to inform you that <strong>Manjeet Chand</strong> arrived late today.</p>
        <p><strong>Date:</strong> {$date}<br>
        <strong>Scheduled Time:</strong> {$scheduled_time}<br>
        <strong>Arrival Time:</strong> {$arrival_time}<br>
        <strong>Delay Duration:</strong> {$delay_duration}<br>
        <strong>Reason:</strong> {$reason}<br>
        <strong>Late Count:</strong> {$count}</p>
        <p>Please take note of this for attendance records.</p>
    ";

    // HR recipients
    $to = [
        'yuvrajkohli8090ylt@gmail.com',
        'robintomr@icloud.com',
        'digitarttech@gmail.com',
        'robntomr@gmail.com',
        'manjeetchand01@gmail.com',
        'priyanka@adxventure.com',
        'hr@adxventure.com',
        'suyalvikas@gmail.com',
        'work@adxventure.com'
    ];
    $recipients = implode(',', $to);
    // dd($recipients);
    // Send mail to HR
    sendLaravelMail($to, $subject, $header, $footer = null, $message);
    // sendLaravelMail($recipients, $subject, $header,$footer= null, $message);
    return 'Email has been sent successfully!';
});

Route::get('/test-mail', function () {
    $result = sendLaravelMail(
        'your@email.com',
        'Test Mail from Adxventure',
        'Hello!',
        'Regards, Adxventure Team',
        '<strong>This is a test HTML email sent using Laravel.</strong>'
    );

    return $result ? '✅ Mail sent!' : '❌ Mail failed.';
});

Route::get('/assign-role', function () {
    // Permission::create(['name' => 'View lead']);
    $user = User::find(auth()->user()->id);
    // dd($user);
    // $user->assignRole(Role::where('name', "Technology Tech Lead")->firstOrFail()); 
    // dd('Role assigedsd'); 

    $data = DB::select('SELECT * FROM `model_has_roles`');

    DB::statement('TRUNCATE TABLE `model_has_roles`');

    foreach ($data as $d) {
        $role = Role::find($d->role_id);
        $user = User::find($d->model_id);

        if ($role && $user) {
            $user->assignRole($role);
            // dump($role->name, $user->name);
        }
    }

    dump('Done');
});

Route::get('give-permission', function () {
    Permission::create(['name' => 'campaigns']);
    dd('done');
});

Route::get('/send-mail', function () {
    $to_email = 'manjetchand01@gmail.com';
    $subject = 'Test Email';
    $message = 'This is a test email message.';
    $mail = Mail::raw($message, function ($msg) use ($to_email, $subject) {
        $msg->to($to_email)
            ->subject($subject);
    });
    dd($mail);
});


Route::get('/', function () {
    return redirect()->route('login');
});



Route::get('assign-lead', function () {
    $leads = Lead::with('Followup')
        ->where('client_category', '2')
        ->whereJsonContains('project_category', '2')
        ->where('assigned_user_id', 456)
        ->whereDoesntHave('Followup', function ($query) {
            $query->whereNotNull('lead_id');
        })
        ->get();
    // dd($leads);

    foreach ($leads as $lead) {
        $lead->update([
            'assigned_user_id' => '456',

        ]);
    }
    dd('chalo ho gya ');
});
// Route::get('get-intrested-lead', function () {

//     $leads = Lead::with('lastFollowup')
//         ->where('client_category', '8')
//         ->where('lead_source','!=','4')
//         ->get();

//     // dd($leads);
//     $fileName = 'interested_leads.csv';

//     $headers = [
//         "Content-Type" => "text/csv",
//         "Content-Disposition" => "attachment; filename=$fileName",
//     ];

//     $callback = function () use ($leads) {
//         $file = fopen('php://output', 'w');

//         // CSV header
//         fputcsv($file, [
//             'Lead Name',
//             'Email',
//             'Phone',
//             'City'
//         ]);

//         foreach ($leads as $lead) {
//             fputcsv($file, [
//                 $lead->name ?? '',
//                 $lead->email ?? '',
//                 $lead->phone ?? $lead->mobile ?? '',
//                 $lead->city ?? '',
//             ]);
//         }

//         fclose($file);
//     };

//     return Response::stream($callback, 200, $headers);
// });

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/login-reminder',[CronController::class,'login_reminder']);
// Route::get('/login-admin',[CronController::class,'admin_login_mail']);

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    //common route
    Route::get('project/task/{id}', [TaskController::class, 'index'])->name('project.task.index');
    Route::post('/task/user', [TaskController::class, 'taskUser'])->name('task.user');
    Route::get('task/taskReport', [ReportController::class, 'taskReport'])->name('task.taskReport');
    Route::get('task/taskReport/test', [ReportController::class, 'taskReportTest'])->name('task.taskReport.test');
    Route::get('tasks/Reports/{id}', [ReportController::class, 'taskProjectReport'])->name('task.taskProjectReport');
    Route::resource('/tasks', TaskController::class);
    Route::get('task/create/{id}', [TaskController::class, 'create'])->name('task.create.custom');
    Route::get('/user/tasks/delete/{id}', [TaskController::class, 'destroy'])->name('task.delete');


    Route::get('/user/client/edit/{id}', [ClientController::class, 'EditClient'])->name('user.edit.client');
    Route::post('/user/client/update/{id}', [ClientController::class, 'updateClient'])->name('user.edit.client.store');
    Route::post('/project/update/{id}', [ProjectsController::class, 'update'])->name('project.update');
    Route::get('/report/view/{id}', [AdminController::class, 'tasks'])->name('areport.task');
    Route::get('/report/create/{id}', [ReportController::class, 'create'])->name('report.user.create');
    Route::post('/report/store', [ReportController::class, 'store'])->name('report.user.store');
    Route::get('task/generateReport/{projectiId}', [ReportController::class, 'generateReport'])->name('task.generateReport');
    Route::get('report/index', [ReportController::class, 'index'])->name('report.task.index');


    Route::get('get-lead', [HomeController::class, 'get_lead'])->name('get.lead');
    Route::get('get-proposal', [HomeController::class, 'get_proposal'])->name('get.proposal');
    Route::get('get-followup', [HomeController::class, 'get_followup'])->name('get.followup');


    Route::post('task-completion/{id}', [TaskController::class, 'task_completion'])->name('task.completion');

    Route::get('/logs', [LogsController::class, 'logs'])->name('logs');


    Route::resource('/profiles', ProfileController::class);

    Route::controller(UserLeaveController::class)->group(function () {
        Route::get('leave', 'create')->name('leave.create');
        Route::get('leaves/index', 'index')->name('leave.index');
        Route::post('leave/store', 'store')->name('leave.store');
        Route::any('leave/status/{id}/{status}', 'status')->name('leave.status');
        Route::get('leave/delete/{id}', 'delete')->name('leave.delete');
    });


    Route::controller(DatatableController::class)->group(function () {
        Route::get('datatable', 'index')->name('datatable');
    });


    Route::get('employee/late-report', [ReportController::class, 'late_report'])->name('employee.late.report');
    Route::get('employee/user/late-report/{id}', [ReportController::class, 'user_late_report'])->name('employee.user.late.report');

    // Role & Permission Route
    Route::middleware(['checkPermission:role_permissions'])->controller(PermissionController::class)->group(function () {
        Route::get('role', 'RoleIndex')->name('role');
        Route::get('role/create', 'RoleCreate')->name('role.create');
        Route::post('super-admin/role/store', 'RoleStore')->name('role.store');
        Route::get('super-admin/role/edit/{id}', 'RoleEdit')->name('role.edit');
        Route::get('super-admin/permission/create', 'create')->name('permission.create');
        Route::post('super-admin/permission/store', 'store')->name('permissions.store');
        Route::get('super-admin/permissions', 'index')->name('permissions');
        Route::post('super-admin/permissions/edit/{id}', 'edit')->name('permission.edit');
        Route::get('super-admin/permissions/delete/{id}', 'destroy')->name('permission.delete');
        Route::post('super-admin/permissions/assign', 'assign')->name('permissions.assign');
    });

    // Campaigns Routes
    Route::middleware(['checkPermission:campaigns'])->group(function () {
        Route::resource('campaigns', CampaignsController::class);
        Route::post('campaigns/{campaign}/import', [CampaignsController::class, 'import'])->name('campaigns.import');
        Route::post('campaigns/{campaign}/start', [CampaignsController::class, 'start'])->name('campaigns.start');
    });


    // Department Route
    Route::middleware(['checkPermission:departments'])->controller(DepartmentController::class)->group(function () {
        Route::get('departments', 'index')->name('departments');
        Route::post('departments/create', 'create')->name('departments.create');
        Route::post('departments/edit/{id}', 'edit')->name('departments.edit');
        Route::get('departments/status/{id}/{status}', 'status')->name('departments.status');
        Route::get('departments/delete/{id}', 'delete')->name('departments.delete');
    });

    // Settings Route
    Route::middleware(['checkPermission:settings'])->controller(DepartmentController::class)->group(function () {
        Route::resource('api-settings', ApiSettingsController::class);
    });

    // Crm Route
    // Route::middleware(['checkPermission::crm'])->controller(CrmController::class)->group(function (){

    // });


    // Super-Admin & Admin Routes
    Route::middleware(['checkRolePermission:Super-Admin,Admin'])->group(function () {
        Route::post('/expenses/type', [ExpensesController::class, 'type'])->name('expenses.type');
        Route::get('/expenses/summry', [ExpensesController::class, 'summery'])->name('expenses.summry');
        Route::get('/expenses/details', [ExpensesController::class, 'details'])->name('expenses.details');
        Route::resource('/expenses', ExpensesController::class);
        Route::resource('/skills', SkillController::class);


        Route::get('/AllTaskTypeWise', [AdminController::class, 'AllTaskTypeWise'])->name('a.tasks');
        Route::get('/user/index', [HomeController::class, 'index']);
        Route::get('/user/project', [ProjectsController::class, 'UserAlAssignProjects'])->name('project.user');
        Route::get('/report/{id}', [ReportController::class, 'index'])->name('report.user.index');

        Route::resource('/reports', ReportController::class);
        Route::resource('/areport', AdminController::class);
        Route::resource('/client', EmployeeController::class);
        Route::get('/project-task', [ProjectsController::class, 'taskProjects'])->name('project.taskProjects');
        // Route::get('/user/tasks/status/{id}/{status}',[TaskController::class,'status'])->name('task.update.status');
        Route::get('task/status/{id}/{status}', [TaskController::class, 'taskStatus'])->name('task.status');
        // Route::get('task/create/{id}',[TaskController::class,'create'])->name('task.create.custom');
        Route::get('/user/tasks/view/{id}', [TaskController::class, 'vi6ew'])->name('task.view');

        Route::controller(TempletController::class)->group(function () {
            Route::post('templet/store', 'store')->name('templet.store');
            Route::get('templets', 'index')->name('templet.index');
            Route::post('templet/update/{id}', 'update')->name('templet.update');
            Route::get('templet/delete/{id}', 'delete')->name('templet.delete');
            Route::any('templet/category', 'category')->name('templet.category');
        });

        Route::resource('/office', OfficeController::class);
    });


    // Hr
    Route::middleware(['checkRolePermission:Super-Admin,Admin,Human Resources Executive'])->group(function () {
        Route::get('/user-login/{id}', [EmployeeController::class, 'user_login'])->name('user.login');
        Route::post('/user-approved/{id}', [EmployeeController::class, 'approved'])->name('user.approved');
        Route::post('/offer/letter/genrate', [EmployeeController::class, 'genrate'])->name('offfer.letter.genrate');
        Route::post('/offer/letter/edit/{id}/genrate/', [EmployeeController::class, 'offer_letter_edit'])->name('offfer.letter.genrate.edit');

        Route::resource('/client', EmployeeController::class);

        Route::post('/user/update/{id}', [EmployeeController::class, 'update'])->name('user.update');
        Route::get('/user/update/status/{id}/{status}', [EmployeeController::class, 'updateStatus'])->name('user.status.update');

        Route::get('/users/create', [EmployeeController::class, 'create']);
        Route::resource('/users', EmployeeController::class);
        Route::post('/candidates/interview', [CandidateController::class, 'interview'])->name('interview');
        Route::post('/candidates/genrate/offer/letter', [CandidateController::class, 'genrate'])->name('genrate.offer.letter');
        Route::post('/candidates/offer/letter/view', [CandidateController::class, 'offer_letter'])->name('offer.letter');
        Route::post('/candidates/add', [CandidateController::class, 'add_employee'])->name('add.employee');
        Route::resource('/candidates', CandidateController::class);

        Route::get('profile/verify/{id}/{status}', [ProfileController::class, 'verify'])->name('profile.verify');
    });

    //Super-Admin,Admin,BDE,Marketing Manager Route
    Route::group(
        ['middleware' => ['checkRolePermission:Super-Admin,Admin,BDE,Marketing-Manager,Project-Manager,Business Development Intern']],
        function () {

            Route::any('/lead/followup', [FollowupController::class, 'lead_followup'])->name('get.lead.followup');
            Route::post('send/message', [FollowupController::class, 'message'])->name('send.message');
            Route::resource('/followup', FollowupController::class);
            Route::any('/receipt/send/{id}', [PaymentController::class, 'receipt'])->name('receipt.send');
            Route::post('/receipts/edit', [PaymentController::class, 'paymentEdit'])->name('receipts.edit');
            Route::any('/receipts/{id}', [PaymentController::class, 'payment'])->name('receipts');
            Route::resource('/payment', PaymentController::class);
            Route::post('/payments', [PaymentController::class, 'stores'])->name('payment.store');


            Route::prefix('crm')->name('crm.')->group(function () {

                Route::controller(leadController::class)->group(function () {
                    Route::get('create/leads', 'create')->name('create');
                    Route::post('create/leads', 'store')->name('store');
                    Route::post('leads/update/{id}/{status?}', 'update')->name('lead.update');
                    Route::get('crm/csv/sample', 'downloadSample')->name('sample');
                    Route::post('crm/uplode', 'uploadCsv')->name('csv');
                    Route::post('lead/delete/', 'delete')->name('lead.delete');
                    Route::post('lead/send/mail', 'sendMail')->name('send.mail');
                    Route::get('user/bde/report', 'report')->name('user.bde.report');
                    Route::post('user/crm/leads/edits', 'edit')->name('edit');
                    Route::get('crm/leads/test', 'leadText')->name('leads.index');
                });

                Route::controller(CrmController::class)->group(function () {
                    // crm lead route
                    Route::get('leads', 'index')->name('index');
                    Route::get('counts', 'counts')->name('counts');
                    Route::get('api', 'api')->name('api');
                    Route::post('api-store', 'api_store')->name('api.store');
                    Route::get('data', 'data')->name('data');

                    Route::get('leads/status/{id}/{status}', 'Status')->name('lead.status');


                    Route::get('freshsale/{leadId}', 'freshsale')->name('freshsale');

                    Route::match(['get', 'post'], 'lead/prposel/service/{leadId}/{id?}', 'PrposalService')->name('lead.prposel.service');
                    Route::post('lead/prposel/service/update/{workId}/{leadId}/{id?}', 'PrposalServiceUpdate')->name('lead.prposel.service.update');
                    Route::match(['get', 'post'], 'lead/prposel/invoice/{leadId}/{id?}', 'PrposalInvoice')->name('lead.prposel.invoice');
                    Route::get('prposal/mail/view/{leadId}/{id?}', 'viewMail')->name('prposel.mail.view');
                    Route::any('prposal/send/{invoiceId}/{id?}', 'mail')->name('prposel.mail');
                    Route::post('prposal/send/{leadId}/{id?}', 'payment')->name('prposal.payment');
                    Route::get('lead/convert/client', 'ConvertLeads')->name('convert.leads');

                    Route::get('crm/upsale/{id}', 'upsale')->name('upsale');
                    Route::post('crm/upsale/invoice', 'createInvoice')->name('upsale.invoice');
                    Route::post('crm/freshsale/invoice', 'createFreshInvoice')->name('freshsale.invoice');
                    Route::get('crm/upsale/list', 'AllUpsale')->name('upsale.index');
                    Route::get('crm/convert/leads', 'convert_leads')->name('my.convert.leads');
                    Route::get('crm/leads/payment/{leadId}/{id?}', 'paymentShow')->name('lead.payment.view');
                    Route::post('/lead/bulk-update', 'bulkUpdate')->name('lead.bulkUpdate');
                    Route::any('/lead/assigned/user', 'leadAssigned')->name('lead.assigned');
                    Route::get('/lead/prposal/view/{id}/{status?}', 'prposal')->name('prpeosal.view');
                    Route::get('/crm/lead/converted', 'converted_leads')->name('converted.lead');

                    Route::get('/crm/today/followup', 'today_followup')->name('today.followup');
                    Route::get('/crm/today/proposal', 'today_proposal')->name('today.proposal');

                    Route::get('/invoice/work/delete/{id}', [AdminInvoice::class, 'workDelete'])->name('work.delete');
                    Route::get('/project/work/delete/{id}', [ProjectsController::class, 'workDelete'])->name('project.work.delete');
                    Route::post('/user/tasks/{id}', [TaskController::class, 'update'])->name('task.update');

                    Route::get('/crm/today/report', 'today_report')->name('today.report');

                    Route::post('offer/message', 'offer_message')->name('send.offer.message');
                    Route::post('proposal/custome', 'cutome_proposal')->name('send.custome.proposal');
                    Route::post('proposal/type', 'proposalType')->name('proposalType');
                    Route::get('messages/{id}', 'messages')->name('message.view');
                });

                // Quotation route
                Route::controller(QuotationController::class)->group(function () {
                    Route::get('quotation/client/{id}', 'client')->name('quotation.client');
                });
            });


            Route::controller(TaskController::class)->group(function () {});

            Route::controller(ProjectsController::class)->group(function () {
                Route::get('/projects/create/{invoiceId}', 'create')->name('projects.create');
                Route::get('my/projects', 'MyProject')->name('my.projects');
                Route::match(['get', 'post'], 'projects/assign', 'AssignProjects')->name('projects.assign');
            });

            Route::post('get/bank/details', [BankController::class, 'getBankDetail'])->name('get-bank-details');
            Route::get('/user/client/create/{id?}', [ClientController::class, 'create'])->name('user.client');
            Route::post('/user/client/create', [ClientController::class, 'store'])->name('user.client.store');
            Route::get('/user/client/index', [ClientController::class, 'index'])->name('user.client.index');
            Route::get('client/index/project', [ClientController::class, 'clientIndex'])->name('client.index.project');
            Route::post('invoice/followup', [AdminInvoice::class, 'followup'])->name('invoice.followup');

            // my client 
            Route::get('crm/my-client', [ClientController::class, 'my_client'])->name('crm.my_client');
        }
    );


    Route::group(['middleware' => ['checkRolePermission:Super-Admin,Admin,Technology Tech Lead,Technology Executive,Marketing-Manager,Project-Manager,Digital Marketing Executive,Digital Marketing Intern,Digital Marketing Manager,Graphic Designing Intern,Floor Manager']], function () {
        Route::get('/user/project/tasks/{id?}', [TaskController::class, 'UserAssignTasks'])->name('project.task');
        Route::post('/report/other', [ReportController::class, 'otherReport'])->name('report.other');
        Route::post('/report/{id}/{status}', [ReportController::class, 'Reject'])->name('report.reject');
        Route::get('/report/attachments/{id}', [ReportController::class, 'attachments'])->name('report.attachments');
        Route::get('/user/ajax/report/tasks', [TaskController::class, 'ReportAjaxUserAssignTasks'])->name('project-report.task.ajax');
        Route::post('/project/credintoal', [ProjectsController::class, 'credintoal'])->name('projects.credintoal');
        Route::post('/project/credintoal/edit', [ProjectsController::class, 'credintoalEdit'])->name('projects.credintoal.edit');
        Route::delete('/project/credintoal/delete/{id}', [ProjectsController::class, 'credintoalDelete'])->name('projects.credintoal.delete');
        Route::any('project-details/{project_id}', [ProjectsController::class, 'Project_details'])->name('projects.details');
        Route::resource('/project', ProjectsController::class);

        Route::post('/user/task/complete', [TaskController::class, 'taskComplete'])->name('task.complete');
        Route::any('/user/tasks/status/{id}/{status}', [TaskController::class, 'status'])->name('task.update.status');
        Route::post('/startdateReport/', [TaskController::class, 'startdateReport'])->name('project.startdateReport');
        Route::get('/monthly/report', [ReportController::class, 'getTaskCompletedInAMonth']);
        Route::any('task/send/GenerateReport/{projectiId}', [TaskController::class, 'sendGenerateReport'])->name('task.sendGenerateReport');
        Route::get('task/status/{id}/{status}', [TaskController::class, 'taskStatus'])->name('task.status');
        Route::get('task/index/{id}', [TaskController::class, 'index'])->name('task.index');
        Route::get('/report/delete/{id}', [ReportController::class, 'destroy'])->name('report.delete.index');
        Route::post('/user/tasks/{id}', [TaskController::class, 'update'])->name('task.update');
    });

    Route::middleware(['checkRolePermission:Super-Admin,Admin,Marketing-Manager'])->group(function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::post('category/create', 'create')->name('category.create');
            Route::get('category/index', 'index')->name('category.index');
            Route::get('category/show/{id}', 'show')->name('category.show');
            Route::post('category/update', 'update')->name('category.update');
            Route::post('category/service/{id}', 'service')->name('category.service');
            Route::get('category/service/edit', 'service_edit')->name('category.service.edit');
            Route::get('category/delete/{id}', 'delete')->name('category.delete');
        });
    });

    Route::middleware(['checkRolePermission:Super-Admin,Admin,Manager'])->group(function () {

        // Route::get('/user/project/tasks',[TaskController::class,'UserAssignTasks'])->name('project.task');

        Route::any('/invoice/again/send/{invoice_id}/{id}', [AdminInvoice::class, 'send_again'])->name('invoice.again.send');
        Route::any('/invoice/payment/link/send', [AdminInvoice::class, 'store'])->name('payment.link.send');
        Route::any('/invoice/reminder', [AdminInvoice::class, 'reminder'])->name('reminder.send');
        Route::any('/invoice/createInvoice', [AdminInvoice::class, 'createInvoice'])->name('invoice.add');
        Route::get('/invoice/delete/{id}', [AdminInvoice::class, 'delete'])->name('invoice.delete');
        // Route::post('/invoice/update',[AdminInvoice::class,'UpdateInvoice'])->name('invoice.update');
        Route::any('/invoice/gnerateInvoice/{id}', [AdminInvoice::class, 'gnerateInvoice'])->name('gnerateInvoice');
        Route::any('/invoice/followup', [FollowupController::class, 'invoiceFollowup'])->name('get.invoice.followup');

        Route::get('/invoice/view/{id}', [AdminInvoice::class, 'InvoiceView'])->name('invoice.view');
        Route::get('/invoice/work/index/{id}', [AdminInvoice::class, 'workIndex'])->name('work.Index');
        Route::post('/invoice/work/store', [AdminInvoice::class, 'workStore'])->name('work.store');
        Route::post('/invoice/work/update', [AdminInvoice::class, 'WorkUpdate'])->name('work.update');

        Route::get('/invoice/work/paidform/{id}', [AdminInvoice::class, 'paidform'])->name('work.paidform');
        Route::post('/invoice/work/paid/{id}', [AdminInvoice::class, 'paid'])->name('work.paid');
        Route::get('/invoice/payments/index/{id}', [AdminInvoice::class, 'paymentsIndex'])->name('payments.Index');
        Route::get('/invoice/status/{status}/{id}', [AdminInvoice::class, 'invoiceStatus'])->name('invoice.status');
        Route::post('get-invoice', [AdminInvoice::class, 'get_invoice'])->name('get.invoice');
        Route::get('invoice/today', [AdminInvoice::class, 'today_invoice'])->name('invoice.today');
        Route::get('invoice/debt', [AdminInvoice::class, 'debtInvoice'])->name('invoice.debt');
        Route::any('invoice/send/{id}', [AdminInvoice::class, 'send_invoice'])->name('invoice.send');
        Route::any('invoice/index', [AdminInvoice::class, 'index'])->name('invoice.index');

        Route::any('bill/{id}', [AdminInvoice::class, 'bill'])->name('bill');
        Route::get('invoice-details/{id}', [AdminInvoice::class, 'invoiceDetails'])->name('invoice.details');


        Route::resource('/invoice', AdminInvoice::class);

        Route::get('banks/verified/{id}', [BankController::class, 'verified'])->name('bank.verified');

        Route::get('banks/{id}/{status}', [BankController::class, 'status'])->name('bank.status');
        Route::resource('/banks', BankController::class);
        Route::get('/logs/index/{id}', [LogsController::class, 'index'])->name('logs.index');
        Route::post('projects/save-and-finish', [ProjectsController::class, 'saveAndFinish'])->name('projects.saveAndFinish');
        Route::post('/user/update/{id}', [EmployeeController::class, 'update'])->name('user.update');
        Route::get('/user/update/status/{id}/{status}', [EmployeeController::class, 'updateStatus'])->name('user.status.update');

        Route::any('/project/status/{id}/{status}', [ProjectsController::class, 'status'])->name('profile.status');
        Route::get('/project/work/index/{client_id}/{project_id}', [ProjectsController::class, 'work'])->name('project.work');
        Route::post('/project/work/store', [ProjectsController::class, 'workStore'])->name('project.work.store');
        Route::post('/project/work/update', [ProjectsController::class, 'workUpdate'])->name('project.work.update');

        Route::match(['get', 'post'], '/project/invoice/{client_id}/{project_id}', [ProjectsController::class, 'createInvoice'])->name('project.invoice.create');

        Route::controller(ProjectCategoryController::class)->group(function () {
            Route::post('project/category/create', 'create')->name('project.category.create');
            Route::get('project/category/index', 'index')->name('project.category.index');
            Route::post('project/category/update', 'update')->name('project.category.update');
            Route::get('project/category/delete/{id}', 'delete')->name('project.category.delete');
        });
        Route::get('/generate-pdf-and-send-email/{clientId}', 'App\Http\Controllers\PdfController@generatePdfAndSendEmail')->name('pdf.generate');
    });

    Route::middleware(['checkRolePermission:Super-Admin,Admin,Manager,Team-leader'])->group(function () {
        Route::get('/user/tasks/delete/{id}', [TaskController::class, 'destroy'])->name('task.delete');
    });

    Route::get('/user/ajax/project/tasks', [TaskController::class, 'AjaxUserAssignTasks'])->name('project.task.ajax');
    Route::get('get/project/details/{id}', [TaskController::class, 'getProjectDetails'])->name('getProjectDetails');
    Route::post('get/task/details', [TaskController::class, 'getTaskDetails'])->name('getTaskDetails');
});
