<?php
    $bank = DB::table('bank')->find($invoice->bank);
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            /* background-color: #fff; */
        }
        .invoice {
            width: 100%;
            min-height: 297mm;
            margin: 0 auto;
            /* background-color: #fff; */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
        }
        th {
            background-color: #f2f2f2;
        }
        .invoice-header {
            text-align: right;
        }
        .invoice-header img {
            width: 300px;
        }
        .invoice-header div {
            margin-bottom: 5px;
        }
        .invoice-footer {
            text-align: center;
            font-size: 2em;
            margin-bottom: 50px;
        }
        .account-details {
            margin-bottom: 20px;
        }
        .scanner {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <table>
            <thead>
                <tr>
                    <td colspan="2" style="padding-bottom:0;">
                        <table>
                            <tr>
                                <td>
                                    <a href="<?php echo e(url('/')); ?>">
                                        <img src="<?php echo e(url('/')); ?>/logo.png" alt="Logo" width="300px"/>
                                    </a>
                                </td>
                                <td class="invoice-header">
                                    <div><?php echo e($invoice->Office->address); ?>,<br /><?php echo e($invoice->Office->city); ?> <?php echo e($invoice->Office->zip_code); ?> - <?php echo e($invoice->Office->state); ?></div>
                                    <div><?php echo e($invoice->Office->phone); ?></div>
                                    <div><?php echo e($invoice->Office->email); ?></div>
                                    <?php if(isset($bank->gst) && $bank->gst == '1'): ?>
                                        <div><b>GST Number: </b><?php echo e($invoice->Office->tax_no); ?></div>
                                    <?php endif; ?> 
                                    <div><b>Created by: </b>  <?php echo e(Auth::User()->name ?? '--'); ?></div> 
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><hr></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <td style="text-align: left;" colspan="2">
                                    <div style="color: #777;">Quotation To:</div>
                                    <?php if($id == 1): ?>
                                    <h5 style="margin: 0; font-weight: bold; text-transform: uppercase;"><strong><?php echo e(strtoupper($invoice->client->company_name ?? $invoice->client->name )); ?></strong></h5>
                                    <?php if($invoice->client->email): ?>
                                    <div >Email : <a href="mailto:<?php echo e($invoice->client->email); ?>"><strong><?php echo e($invoice->client->email); ?></strong></a></div>
                                    <?php endif; ?>
                                    <div class="address">Phone No.:  <strong><?php echo e($invoice->client->phone_no); ?></strong></div>
                                    <?php if($invoice->client->client_gst_no): ?>
                                        <div class="email">GST No.: <?php echo e($invoice->client->client_gst_no ?? 'N/A'); ?></div>
                                    <?php endif; ?>
                                    <?php else: ?>
                                        <h5 style="margin: 0; font-weight: bold; text-transform: uppercase;"><strong><?php echo e(strtoupper($invoice->lead->company_name ?? $invoice->lead->name )); ?></strong></h5>
                                        <?php if($invoice->lead->email): ?>
                                        <div>Email : <a href="mailto:<?php echo e($invoice->lead->email); ?>"><strong><?php echo e($invoice->lead->email); ?></strong></a></div>
                                        <?php endif; ?>
                                        <div class="address">Phone No.:  <strong><?php echo e($invoice->lead->phone ?? $invoice->lead->phone); ?></strong></div>  
                                        <?php if($invoice->lead->client_gst_no): ?>
                                        <div class="email">GST No.: <?php echo e($invoice->client_gst_no ?? 'N/A'); ?></div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;" colspan="2">
                                    <h3 style="margin: 0; color: #3989c6;">Quotation <br>#00<?php echo e($invoice->id); ?>/<?php echo e(date('M', strtotime($invoice->created_at))); ?>/<?php echo e(date('Y', strtotime($invoice->created_at))); ?>-<?php echo e(date('y', strtotime($invoice->created_at)) + 1); ?></h3>
                                    <div>Date of Invoice: <?php echo e(date('d/m/Y', strtotime($invoice->created_at))); ?></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="padding: 0 15px;">
                        <table>
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #ddd;">#</th>
                                    <th class="text-left" style="border: 1px solid #ddd;" colspan="2">Service</th>
                                    <th class="text-left" style="border: 1px solid #ddd;" colspan="2">type</th>
                                    <th class="text-left" style="border: 1px solid #ddd;" colspan="2">Quantity</th>
                                    <th class="text-center" style="border: 1px solid #ddd;">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $tot = 0; ?>
                                <?php if(isset($invoice->services)): ?>
                                <?php $__currentLoopData = $invoice->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="text-align:right;border: 1px solid #ddd;"><?php echo e($key +1); ?></td>
                                        <td class="text-left" style="border: 1px solid #ddd;" colspan="2"><?php echo e($service->work_name); ?></td>
                                        <td class="text-left" style="border: 1px solid #ddd;" colspan="2"><?php echo e($service->work_type); ?></td>
                                        <td class="text-left" style="border: 1px solid #ddd;" colspan="2"><?php echo e($service->work_quality); ?></td>
                                        <td class="text-center" style="border: 1px solid #ddd;"><?php echo e(number_format($service->work_price, 2)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                    <tr>
                                        <td <?php if($bank->gst == 1): ?> rowspan="3" <?php else: ?> rowspan="2" <?php endif; ?>  style="border: 1px solid #ddd;"> </td>
                                        <td style="border: 1px solid #ddd;text-align:right;" colspan="6"><strong>Subtotal</strong></td>
                                        <td class="text-right" style="border: 1px solid #ddd;"><strong style="float: right;"><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->subtotal_amount ?? 0, 2)); ?></strong></td>
                                    </tr>
                                    <?php if($bank->gst == 1): ?>
                                    <tr>
                                        <td style="border: 1px solid #ddd;text-align:right;" colspan="6"><strong>GST: <?php echo e($invoice->gst ?? 0); ?>%</strong></td>
                                        <td class="text-right" style="border: 1px solid #ddd;"><strong style="float: right;"><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->gst_amount?? 0, 2)); ?></strong></td>
                                    </tr>
                                    <?php endif; ?>  
                                    <?php if($invoice->discount ?? 0): ?>
                                    <tr>
                                        <td style="border: 1px solid #ddd;text-align:right;" colspan="6"><strong>Discount</strong></td>
                                        <td  class="text-right" style="border: 1px solid #ddd;"><strong style="float: right;"> <?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->discount ?? 00)); ?>.00</strong></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td style="border: 1px solid #ddd;text-align:right;" colspan="6" ><strong>Total Amount</strong></td>
                                        <td class="text-right" style="border: 1px solid #ddd;"><strong style="float: right;"><?php echo e($invoice->currency); ?> <?php echo e(number_format(($invoice->total_amount ?? '00'))); ?>.00</strong></td>
                                    </tr> 
                            </tbody>
                            <tfoot style="margin-top:10px"> 
                                <tr>
                                   
                                    <td colspan="3" style="display:flex; justify-content:center"> 
                                      
                                    </td>
                                </tr>
                                <tr class="scanner">
                                    <td colspan="3">
                                    <?php if(isset($bank) && !empty($bank->scanner)): ?>
                                            <h6><strong>Scan Now</strong></h6>
                                            <br>
                                            <img src="<?php echo e(asset($bank->scanner)); ?>" alt="scanner" width="120px" style="border: 1px solid">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="invoice-footer">
                                        Thank you!
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
 <?php /**PATH /home/bookmziw/adx_tms/laravel/resources/views/admin/crm/prposal/mail.blade.php ENDPATH**/ ?>