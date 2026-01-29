<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\{Invoice,Work};

class PdfController extends Controller
{
       
    public function generatePdfAndSendEmail($clientId, Request $request)
    {
        $client = Invoice::with('bank')->findOrFail($clientId);
        $client->update(['gst' => ($request->gst_price ?? $client->gst)]);
        $works = Work::where('invoice_id', $clientId)->orderBy('id', 'desc')->get();
        // return view('admin.invoice.pdf', compact('client','works'));
        $html = view('admin.invoice.pdf', compact('client', 'works'))->render();
    
        // Generate PDF
        $pdf = PDF::loadHTML($html);
        
        $currentYear = date('Y');
        $currentMonth = date('m');
        $directoryPath = "Invoice/pdf/{$currentYear}/{$currentMonth}";
        $pdfPath = $directoryPath . '/' .  $client->project->name . '_invoice.pdf';
    
        // Ensure the directory exists, create if not
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true); // Create directory with full permissions
        }
        $pdf->save($pdfPath);

        return $pdf->download($clientId . '_invoice.pdf');
    
       // Email details
        $to = "manjeetchand01@gmail.com";
        $subject = 'Adxventure Billing Invoice';
        $name = strtoupper($client->project->name); 
        $message = 'Dear <strong>' .$name . '</strong>,<br><br>' .
                'Please find attached the invoice for your recent work.<br><br>' .
                'Thank you for your business.';

        // File attachment
        $file = $pdfPath;
        $fileSize = filesize($file);
        $fileName = basename($file);

        // Headers
        $boundary = md5(uniqid(time()));
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
        $headers .= "From: accounts@adxventure.com\r\n";

        // Email Body
        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= $message . "\r\n";
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: application/pdf; name=\"{$fileName}\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n\r\n";
        $body .= chunk_split(base64_encode(file_get_contents($file))) . "\r\n";
        $body .= "--{$boundary}--";
        // Send email
        mail($to, $subject, $body, $headers);
       
        return redirect()->back()->with('message', 'Invoice sent successfully!');
    }
}