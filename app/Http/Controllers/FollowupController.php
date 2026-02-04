<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Support\Facades\Mail;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Models\{Invoice, User, Work, Payment, ProjectCategory, lead, Category, TotalAmount, Projects, Followup, Template, Email, Office, Country, Leaves};
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use App\Exports\MultiSheetExport;
use App\Exports\ServiceExport;
use App\Exports\CategoryExport;
use Illuminate\Support\Facades\Http;


class FollowupController extends Controller
{
    public function index(Request $request) {}
    public function create() {}


    public function invoiceFollowup(Request $request)
    {
        $followups = Followup::where('invoice_id', $request->id)->get();
        return response()->json(['followups' => $followups]);
    }

    public function lead_followup(Request $request)
    {
        $followups = Followup::with('user')->where('lead_id', $request->id)->orderBy('id', 'desc')->paginate(10);
        return response()->json([
            'followups' => $followups->items(),
            'pagination' => [
                'current_page' => $followups->currentPage(),
                'last_page' => $followups->lastPage(),
                'total' => $followups->total(),
                'per_page' => $followups->perPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = validator::make($request->all(), [
            'reason' => 'required|string',
        ]);

        // Check if the reason is either 'Other' or 'Not pickup'
        $rules = [];
        if (in_array($request->reason, ['Other'])) {
            $rules['remark'] = 'required|string|min:5|max:100';
        }

        if (!in_array($request->reason, ['call Me Tomorrow', 'Not interested', 'Wrong Information', 'Not pickup', 'Payment Tomorrow', 'Interested', 'Work with other company'])) {
            $rules['next_date'] = 'required'; // This will overwrite if already set, which is okay.
            $rules['next_time'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }



        // dd($request->all());

        $followup = new Followup();
        $lastfollowup = null;
        if ($request->invoice_id) {
            $followup->invoice_id = $request->invoice_id;
            $lastfollowup = Followup::where('invoice_id', $request->invoice_id)->orderBy('id', 'desc')->first();
        } elseif ($request->lead_id) {
            $followup->lead_id = $request->lead_id;
            $lastfollowup = Followup::where('lead_id', $request->lead_id)
                ->orderBy('id', 'desc')
                ->first();
        } else {
            $followup->project_id = $request->project_id;
        }


        $followup->reason = $request->reason;
        $followup->remark = $request->remark;
        $followup->user_id = auth()->user()->id;
        $nextDateTime = $request->next_date . ' ' . $request->next_time;
        if ($request->next_date) {
            $followup->next_date = date('Y-m-d H:i:s', strtotime($request->next_date));
        } elseif ($request->reason === 'call Me Tomorrow') {
            $followup->next_date = date('Y-m-d H:i:s', strtotime('+1 day ' . $request->next_time));
        } elseif ($request->reason === 'call back later' ||  $request->reason === 'Not pickup' || $request->reason === 'Payment Tomorrow' || $request->reason === 'Interested') {
            $now = Carbon::now();
            if ($now->hour < 13) { // Before 1 PM
                // Set the next date to today at 3 PM
                $followup->next_date = Carbon::today()->setTime(15, 0)->format('Y-m-d H:i:s');
            } else { // After 1 PM
                // Set the next date to tomorrow at 10 AM
                $followup->next_date = Carbon::tomorrow()->setTime(10, 0)->format('Y-m-d H:i:s');
            }
        }


        if ($lastfollowup) {
            $lastFollowupDate = Carbon::parse($lastfollowup->next_date)->startOfDay();
            $today = Carbon::today();
            $dayDifference = $lastFollowupDate->diffInDays($today, false);

            // If the day difference is negative or less than one, set delay to 0, otherwise use the calculated difference
            $followup->delay = ($dayDifference < 1) ? 0 : $dayDifference;

            if ($followup->delay >= 1) {
                $leave = Leaves::where('user_id', auth()->user()->id)->whereDate('created_at', $lastFollowupDate)->count();
                if ($leave > 0) {
                    $followup->delay_reason = "Due to Leave";
                }
            }
            $lastfollowup->is_completed = 1;
            $lastfollowup->update();
        } else {
            $followup->delay = 0;
        }

        if ($followup->save()) {
            // $url = $request->invoice_id ? url('/invoice') :
            //        ($request->lead_id ? url('/crm/leads') : url('/projects'));

            // return $this->success('created', 'followup', $url);
            if ($request->invoice_id) {
                $url = url('/invoice');
                return $this->success('created', 'followup', $url);
            } elseif ($request->lead_id) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Followup created successfully',
                    'data' => $followup,
                ]);
            }
        }
    }

    public function edit() {}

    public function update($id)
    {

        $validator = validator::make($request->all(), [
            'remark' => 'required|string|max:100 ',
            'reason' => 'required|string',
        ]);

        // Check if the reason is either 'Other' or 'Not pickup'
        if ($request->reason == 'Other' || $request->reason == 'Not pickup') {
            $validator = validator::make($request->all(), [
                'next_date' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
    }

    public function message(Request $request)
    {
        // dd($request->all());
        $user = $request->message_user;

        if ($request->messagetype == 'offer') {
            $validator = Validator::make($request->all(), [
                'offer_subject' => $request->send_mail ? 'required' : '',
                'offer_message' => 'required|string',
                'offer_docs' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
                'send_mail' => 'required_without_all:send_whatsapp',
                'send_whatsapp' => 'required_without_all:send_mail',
            ], [
                'required_without_all' => 'Please select at least one option: Mail,or WhatsApp.',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'template' => $request->type === 'common' ? 'required|numeric' : '',
                'title' => $request->type !== 'common' ? 'required' : '',
                'description' => $request->type !== 'common' ? 'required' : '',
                'send_mail' => 'required_without_all:send_sms,send_whatsapp',
                'send_whatsapp' => 'required_without_all:send_mail,send_sms',
            ], [
                'required_without_all' => 'Please select at least one option: Mail, SMS, or WhatsApp.',
            ]);
        }

        // dd($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $lead = lead::findOrFail($user);
        $apiKey = 'EfJ3kJdXG6cz';
        $whatsappApiUrl = 'http://api.textmebot.com/send.php';
        $message = $request->offer_message;
        $file = $request->offer_docs;
        if ($request->messagetype == 'offer') {
            if ($request->has('send_whatsapp')) {
                // Ensure phone number is in correct format
                // $phone = explode('-', $lead->phone);
                // $phone = isset($phone[1]) ? $phone[0] . $phone[1] : $lead->phone;
                $phone = '+919997294527';

                if ($file) {
                    $currentYear = date('Y');
                    $currentMonth = date('m');
                    $directoryPath = "lead/offer/{$currentYear}/{$currentMonth}";

                    // Format the current date and time for uniqueness
                    $dateTime = date('Ymd_His'); // Format: 20240731_153212 (YearMonthDay_HourMinuteSecond)
                    $fileName = $lead->name . '_offer_' . $dateTime . '.' . $file->getClientOriginalExtension();


                    // Ensure the directory exists, create if not
                    if (!file_exists($directoryPath)) {
                        mkdir($directoryPath, 0755, true); // Create directory with full permissions
                    }

                    $filePath = $directoryPath . '/' . $fileName;
                    $file->move($directoryPath, $fileName); // Move file to the correct path
                    $fileUrl = 'https://tms.adxventure.com/' . $filePath;

                    // Determine if the file is a PDF or an image
                    if ($file->getClientOriginalExtension() === 'pdf') {
                        // Send message with document (PDF)
                        $response = Http::get($whatsappApiUrl, [
                            'recipient' => $phone,
                            'apikey' => $apiKey,
                            'text' => $message,
                            'document' => $fileUrl, // Send as document for PDFs
                        ]);
                    } else {
                        // Send message with file (image)
                        $response = Http::get($whatsappApiUrl, [
                            'recipient' => $phone,
                            'apikey' => $apiKey,
                            'text' => $message,
                            'file' => $fileUrl, // Send as file for images
                        ]);
                    }
                } else {
                    // Send message without document or file
                    $response = Http::get($whatsappApiUrl, [
                        'recipient' => $phone,
                        'apikey' => $apiKey,
                        'text' => $message,
                    ]);
                }
            }

            if ($lead->email) {
                if ($request->has('send_mail')) {
                    // Fetching lead details
                    // $to = $lead->email;
                    $to = 'manjeetchand01@gmail.com';
                    $name = strtoupper($lead->name);

                    $subject = $request->offer_subject;
                    $message = $message;

                    // Email headers
                    $boundary = md5(uniqid(time()));
                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
                    $headers .= "From: info@adxventure.com\r\n";

                    // Email body
                    $body = "--{$boundary}\r\n";
                    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
                    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                    $body .= $message . "\r\n";
                    $body .= "--{$boundary}--";

                    // Send email
                    mail($to, $subject, $body, $headers);
                }
            } else {
                $url = url('crm/leads');
                return $this->success('Email Not Found', 'lead Email not found', $url);
            }
        } elseif ($request->messagetype == 'message') {
            // dd(1);
            // Creating a new Email record
            $emails = new Email();
            $emails->sender_id = auth()->user()->id;
            $emails->lead_id = $user;

            if ($request->type === 'common') {
                $emails->template_id = $request->template;
            } else {
                $emails->title = $request->title;
                $emails->message = $request->description;
            }

            if ($emails->save()) {
                $lead = lead::findOrFail($user);
                $template = Template::find($emails->template_id);

                if ($request->has('send_mail')) {
                    // Fetching lead details
                    // $to = $lead->email;
                    $to = 'manjeetchand01@gmail.com';
                    $name = strtoupper($lead->name);

                    if ($template) {
                        $subject = $template->title;
                        $message = 'Dear <strong>' . $name . '</strong>,<br><br>' .
                            'Please find attached the invoice for your recent work.<br><br>' .
                            'Thank you for your business.<br>' .
                            $template->message . '<br>';
                    } else {
                        $subject = $emails->title;
                        $message = 'Dear <strong>' . $name . '</strong>,<br><br>' .
                            'Please find attached the invoice for your recent work.<br><br>' .
                            'Thank you for your business.<br>' .
                            $emails->message . '<br>';
                    }

                    // Email headers
                    $boundary = md5(uniqid(time()));
                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
                    $headers .= "From: info@adxventure.com\r\n";

                    // Email body
                    $body = "--{$boundary}\r\n";
                    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
                    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                    $body .= $message . "\r\n";
                    $body .= "--{$boundary}--";

                    // Send email
                    mail($to, $subject, $body, $headers);
                }

                if ($request->has('send_whatsapp')) {
                    // Ensure phone number is in correct format
                    $phone = explode('-', $lead->phone);
                    $phone = isset($phone[1]) ? $phone[0] . $phone[1] : $lead->phone;

                    // Parameters for the WhatsApp API
                    if ($template) {
                        $params = [
                            'recipient' => '+919997294527', // Replace hardcoded number with the processed phone
                            'apikey' => 'EfJ3kJdXG6cz',
                            'text' => 'Hello ' . $lead->name . ', ' . $template->message,
                        ];
                    } else {
                        $params = [
                            'recipient' => $phone, // Replace hardcoded number with the processed phone
                            'apikey' => 'EfJ3kJdXG6cz',
                            'text' => 'Hello ' . $lead->name . ', ' . $emails->message,
                        ];
                    }

                    // API endpoint
                    $apiUrl = "http://api.textmebot.com/send.php";

                    // Build the query string
                    $queryString = http_build_query($params);
                    $url = "{$apiUrl}?{$queryString}";

                    // Initialize cURL and send the request
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // Get the response from the API
                    $response = curl_exec($ch);
                    curl_close($ch);

                    // Handle the API response if needed
                    if ($response === false) {
                        // cURL error
                        return response()->json(['error' => 'Failed to send WhatsApp message.']);
                    } else {
                        // Handle successful response or log the response if needed
                        // Example: Log::info('WhatsApp response: ' . $response);
                    }
                }
            }
        }
        $url = url('crm/leads');
        return $this->success('', '', $url);
    }
}
