<?php
namespace App\Http\Controllers;
use App\Client;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Models\{Invoice,User,Work,Payment,Projects,Bank,Followup,Leaves,Office,ProjectInvoice};
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class PaymentController extends Controller
{
    public function stores(Request $request){   
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required',
            'mode' => 'required',
            'deposit_date' => 'required|date',
            'amount' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'payment_status' => 'required',
        ]);
        // Return validation errors if any
        $invoice = ProjectInvoice::with('lead')->findorfail($request->invoice_id);
        if($invoice->balance > $request->amount  && $request->payment_status == 'Partial-Paid'){
            $validator = Validator::make($request->all(), [
                'next_billing_date' => 'required|date',
                'remark' => 'required|string|max:50',
            ]);
        }elseif($invoice->balance > $request->amount  && $request->payment_status == 'Paid'){
            $validator = Validator::make($request->all(), [
                'next_billing_date' => 'required|date',
                'remark' => 'required|string|max:50',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $payment = new Payment();
        $payment->invoice_id = $request->invoice_id;
        if($invoice->lead){
            $payment->lead_id = $invoice->lead->id;
        }else{
            $payment->client_id = $invoice->client->id;
        }

        $payment->mode = $request->mode;
        $payment->desopite_date = $request->deposit_date;
        if($invoice->balance == $request->amount  && $request->payment_status == 'Paid'){
            $payment->remark = 'Invoice Paid';
        }else{
            $payment->remark = $request->remark;
            $payment->next_billing_date = $request->next_billing_date;
        }

        $payment->delay_reason = $request->reason;
        $payment->amount = $request->amount;
        $pendingAmount = $invoice->balance - $request->amount;
        $payment->pending_amount = $pendingAmount;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $destinationPath = 'payment/';
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $timestamp = now()->format('YmdHis'); // Current date and time
            $filename = 'screenshot_' . $timestamp . '.' . $file->getClientOriginalExtension();
            $logo = $filename;
            try {
                $file->move($destinationPath, $filename);
            } catch (\Exception $e) {
                return response()->json(['error' => 'File could not be uploaded.'], 500);
            }
        } else {
            $logo = 'images.png';
        }

        $payment->image = $logo;
    
        if($request->deposit_date < Carbon::today()) {
            $payment->delay = 1;
            $payment->delay_days = Carbon::parse($request->deposit_date)->diffInDays(Carbon::today());
        }else {
            $payment->delay = 0;
            $payment->delay_days = 0;
        }

        if ($pendingAmount == 0) {
            $payment->status = 2;  // Fully paid
        } elseif ($pendingAmount >= 1) {
            $payment->status = 1;  // Partially paid
        } else {
            return response()->json(['errors' => 'Amount Error']);
        }
        
        if($payment->save()){
            $invoice->balance = $pendingAmount;
            $invoice->pay_amount += $request->amount;
            if ($pendingAmount == 0) {
                $invoice->status = 2;  // Fully paid
            } elseif ($pendingAmount >= 1) {
                $invoice->status = 1;  // Partially paid
            } else {
                return response()->json(['errors' => 'Amount Error']);
            }
            $invoice->save();
            
            if($request->generate_bill == 1){
                $url = url('bill/' . $request->invoice_id);
                return $this->success('created','',$url);
            }else{
                $url = url('payment/' . $payment->id);
                return $this->success('created','',$url);
            }
            // return redirect()->route('payment.show', ['payment' => $invoice->id]);
        }else{
            abort(503);
        }
    }

    public function show($id)
    {
        $invoice = Payment::with('lead','client','invoice')->findorfail($id);
        // dd($invoice);
        return view('admin.invoice.receipt',compact('invoice'));
    }

    public function receipt(Request $request, $id)
    {
        $user = auth()->user();
        // Validate the request to ensure at least one option is selected
        $validator = Validator::make($request->all(), [
            'sendbywhatshapp' => 'required_without_all:sendbyemail',
            'sendbyemail' => 'required_without_all:sendbywhatshapp',
        ], [
            'required_without_all' => 'Please select at least one option: Mail or WhatsApp.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        // Retrieve the payment details
        $payment = Payment::with('lead', 'client', 'invoice')->findOrFail($id);
        if ($payment->client) {
            $name = $payment->client->name;
            $phone = $payment->client->phone_no;
            $email = $payment->client->email;
        } else {
            $name = $payment->lead->name;
            $phone = $payment->lead->phone;
            $email = $payment->lead->email;
        }

        // return view('admin.invoice.receipt-mail',compact('payment'));
    
        // Render the HTML page for the receipt
        $html = view('admin.invoice.receipt-mail', compact('payment'))->render();
        if (empty($html)) {
            return response()->json(['error' => 'HTML content is empty']);
        }
    
        // Generate the PDF file
        try {
            $pdf = PDF::loadHTML($html);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }


        // Define the directory path and file name
        $currentYear = date('Y');
        $currentMonth = date('m');
        $directoryPath = "Receipt/pdf/{$currentYear}/{$currentMonth}";
        $dateTime = date('Ymd_His');
        $pdfFileName = ($payment->client ? $payment->client->name : $payment->lead->name) . '_receipt_' . $dateTime . '.pdf';
        $pdfPath = $directoryPath . '/' . $pdfFileName;

        // Create directory if it does not exist
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }
    
        // Save PDF and update database
        $pdf->save($pdfPath);
        $payment->pdf = $pdfPath;
        $payment->receipt_number =  sprintf(
            '#00%02d/%s/%d-%02d', 
            $payment->id, 
            date('M', strtotime($payment->created_at)), 
            date('Y', strtotime($payment->created_at)), 
            date('y', strtotime($payment->created_at)) + 1
        );


        $payment->save();

        $subject ="Thank You for Your Payment!";
        $message =  "Dear " . $name . ",\n\n" .
        "We sincerely appreciate your recent payment for our services. Your timely support and trust in us mean a lot, and we’re thrilled to continue our work together.\n\n" .
        "If you have any questions, feedback, or additional needs, please feel free to reach out. Our team is always here to assist and ensure your complete satisfaction.\n\n" .
        "Thank you once again for your partnership and prompt payment.\n".
        "Warm regards,\nAdxventure\n";
        
        $fileUrl = 'https://tms.adxventure.com/' . $pdfPath;
    
        // Send via WhatsApp
        if ($request->has('sendbywhatshapp')) {
            if (!str_starts_with($phone, '+91')) {
                $phone = '+91' . $phone;
            }

            $apiKey = 'EfJ3kJdXG6cz';
            $whatsappApiUrl = 'http://api.textmebot.com/send.php';
            Http::get($whatsappApiUrl, [
                'recipient' =>    $phone,
                'apikey' => $apiKey,
                'text' => $message,
                'document' => $fileUrl,
            ]);
        }
    
        // Send via email
        if ($request->has('sendbyemail')) {
            $to = 'manjeetchand01@gmail.com';
            $subject = 'Adxventure Billing Receipt';
            
            // HTML message content
            $emailMessage = "Dear <strong>{$name}</strong>,<br><br>
                We sincerely appreciate your recent payment for our services. Your timely support and trust in us mean a lot, 
                and we’re thrilled to continue our work together.<br><br>
                If you have any questions, feedback, or additional needs, please feel free to reach out. 
                Our team is always here to assist and ensure your complete satisfaction.<br><br>
                Thank you once again for your partnership and prompt payment.<br>
                Warm regards,<br>Adxventure<br><br>";

            // Boundary string for separating parts
            $boundary = md5(uniqid(time()));

            // Headers for sending HTML email with attachment
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
            $headers .= "From: info@adxventure.com\r\n";

            // Message body with boundary
            $body = "--{$boundary}\r\n";
            $body .= "Content-Type: text/html; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $body .= $emailMessage . "\r\n";

            // Read and encode the PDF file
            $fileContent = file_get_contents($pdfPath);
            $fileContentEncoded = chunk_split(base64_encode($fileContent));

            // Attach the PDF file to the email
            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: application/pdf; name=\"{$pdfFileName}\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"{$pdfFileName}\"\r\n\r\n";
            $body .= $fileContentEncoded . "\r\n";
            $body .= "--{$boundary}--";

            // Send the email
            mail($to, $subject, $body, $headers);
            // if () {
            //     return response()->json(['success' => 'Email sent successfully']);
            // } else {
            //     return response()->json(['error' => 'Failed to send email']);
            // }
         
        }

        $userRole = $user->roles()->pluck('name')->toArray();
        if (in_array('Super-Admin', $userRole) || in_array('Admin', $userRole) || in_array('Accountant', $userRole)) {  
             $url = url('/invoice');
        }else{
            $url = url('/crm/leads');
        }

        return $this->success('success','',$url);
    }


    public function payment($id){
        $payments = Payment::with('invoice','client','lead')->where('invoice_id',$id)->orderby('id','desc')->get();
        return view('admin.invoice.payment',compact('payments'));
    }

    public function paymentEdit(Request $request){
       
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $destinationPath = 'payment/';
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $timestamp = now()->format('YmdHis'); // Current date and time
            $filename = 'screenshot_' . $timestamp . '.' . $file->getClientOriginalExtension();
            $logo = $filename;
            try {
                $file->move($destinationPath, $filename);
            } catch (\Exception $e) {
                return response()->json(['error' => 'File could not be uploaded.'], 500);
            }
        } else {
            $logo = 'images.png';
        }

        $payment = Payment::findorFail($request->id);
        if($payment){
            $payment->update([
                'image' => $logo,
            ]);
            $url = url('receipts/' . $payment->invoice_id);
            return $this->success('created','',$url);
        }else{
            abort(404);
        }
       
    }


}
