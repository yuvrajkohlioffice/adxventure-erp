


<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title','Receipt'); ?>
    <?php
    $bank = DB::table('bank')->find($invoice->bank);
    ?>
    <style type="text/css">
    .invoice {
        position: relative;
        background-color: #FFF;
        min-height: 680px;
        padding: 15px
    }
    .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #3989c6
    }
    .invoice .company-details {
         text-align: right
    }
    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0
    }
    .invoice .contacts {
        margin-bottom: 20px
    }
    .invoice .invoice-to {
        text-align: left
    }
    .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0
    }
    .invoice .invoice-details {
        text-align: right
    }
    .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #3989c6
    }
    .invoice main .thanks {
        font-size: 2em;
        margin-bottom: 50px
    }
    .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #3989c6
    }
    .invoice main .notices .notice {
        font-size: 1.2em
    }
    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px
    }
    .invoice table td,
    .invoice table th {
        padding: 15px;
        background: #f2f2f2;
        border-bottom: 1px solid #fff
    }
    .invoice table th {
        white-space: nowrap;
        font-weight: 400;
        font-size: 16px
    }
    .invoice table td h3 {
        margin: 0;
        font-weight: 400;
        font-size: 1.2em
    }
    .invoice table .qty,
    .invoice table .total,
    .invoice table .unit {
        text-align: right;
        font-size: 1.2em
    }
    .invoice table tbody tr:last-child td {
        border: none
    }
    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: left;
        padding: 10px 20px;
        font-size: 1.2em;
        border-top: 1px solid #aaa
    }
    .invoice table tfoot tr:first-child td {
        border-top: none
    }
    .invoice table tfoot tr:last-child td {
        color: #3989c6;
        font-size: 1.4em;
        border-top: 1px solid #3989c6
    }
    .invoice table tfoot tr td:first-child {
        border: none
    }
    .invoice footer {
        width: 100%;
        text-align: center;
        color: #777;
        border-top: 1px solid #aaa;
        padding: 8px 0
    }
    </style>


    <div class="pagetitle">
        <h1>Generate Receipt</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Receipt </li>
            </ol>
        </nav>
    </div>
    <?php echo $__env->make('include.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <?php if(Session::has('insert')): ?>
                <div class="alert alert-success">
                    <strong> <?php echo e(session('insert')); ?></strong>
                </div>
                <?php endif; ?>
                <?php if(Session::has('danger')): ?>
                <div class="alert alert-danger">
                    <strong> <?php echo e(session('danger')); ?></strong>
                </div>
                <?php endif; ?>
                <!-- <a href="<?php echo e(url('/')); ?>" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left fa-fw"></i>Go Back</a> -->
                <a href="javascript:void(0)" onClick="history.back();" class="btn btn-primary btn-sm"><i
                        class="fa fa-arrow-left fa-fw"></i> Go Back</a>
                        
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div id="invoice">
                            <div class="invoice overflow-auto">
                                <div style="min-width: 600px">
                                    <header>
                                        <div class="row">
                                            <div class="col">
                                                <a target="_blank" href="<?php echo e(url('/')); ?>">
                                                    <img src="<?php echo e(url('/')); ?>/logo.png" style="width:300px;" data-holder-rendered="true" />
                                                </a>
                                            </div>
                                            <div class="col company-details">
                                                <div>  29 Tagore Villa, Above Bank of Baroda, <br>
                                                            Connaught Place,<br>
                                                            Dehradun 248001 - Uttarakhand</div>
                                                <div><b>Phone No.</b>: 918077226637</div>
                                                <div><b>Email </b>: contact@adxventure.com</div>
                                                <?php if($invoice->invoice->gst >=1): ?>
                                                <div><b>GST Number </b>: 05ABRFA1281B1ZS</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </header>
                                    <main>
                                        <div class="row contacts">
                                            <div class="col invoice-to">
                                                <div class="text-gray-light">Receipt To:</div>
                                                <?php if($invoice->client): ?>
                                                    <h5 class="to"><strong><?php echo e(strtoupper($invoice->client->company_name ?? $invoice->client->name )); ?></strong></h5>
                                                    <?php if($invoice->client->email): ?>
                                                    <div class="email">Email : <a href="<?php echo e($invoice->client->email); ?>"><strong><?php echo e($invoice->client->email); ?></strong></a></div>
                                                    <?php endif; ?>
                                                    <div class="address">Phone No.:  <strong><?php echo e($invoice->client->phone_no); ?></strong></div>
                                                    <?php if($invoice->client->client_gst_no): ?>
                                                        <div class="email">GST No.: <?php echo e($invoice->client->client_gst_no ?? 'N/A'); ?></div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <h5 class="to"><strong><?php echo e(strtoupper($invoice->lead->company_name ?? $invoice->lead->name )); ?></strong></h5>
                                                    <?php if($invoice->lead->email): ?>
                                                    <div class="email">Email : <a href="<?php echo e($invoice->lead->email); ?>"><strong><?php echo e($invoice->lead->email); ?></strong></a></div>
                                                    <?php endif; ?>
                                                    <div class="address">Phone No.:  <strong><?php echo e($invoice->lead->phone ?? $invoice->lead->phone); ?></strong></div>  
                                                    <?php if($invoice->lead->client_gst_no): ?>
                                                    <div class="email">GST No.: <?php echo e($invoice->client_gst_no ?? 'N/A'); ?></div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col invoice-details">
                                                <h3 class="invoice-id">Receipt <br>
                                                    #00<?php echo e($invoice->id); ?>/<?php echo e(date('M', strtotime($invoice->created_at))); ?>/<?php echo e(date('Y', strtotime($invoice->created_at))); ?>-<?php echo e(date('y', strtotime($invoice->created_at)) + 1); ?>

                                                </h3>
                                                <div class="date">Date of Receipt : <?php echo e(date('d/m/Y', strtotime($invoice->created_at))); ?></div>
                                            </div>
                                        </div>
                                        <!-- Work Table  -->
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <h5>Service</h5>
                                            <thead>
                                                <tr>
                                                    <th style="font-weight: 700;">#</th>
                                                    <th class="text-left" style="font-weight: 700;">Service</th>
                                                    <th class="text-center" style="font-weight: 700;">Type</th>
                                                    <th class="text-center" style="font-weight: 700;">Quantity</th>
                                                    <th class="text-center" style="font-weight: 700;">Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($invoice->service)): ?>
                                                <?php $__currentLoopData = $invoice->service; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td class="no"><?php echo e($key +1); ?></td>
                                                        <td class="text-left">
                                                            <h3><?php echo e($service->work_name); ?></h3>
                                                        </td>
                                                        <td class="unit text-center"><?php echo e($service->work_type); ?></td>
                                                        <td class="unit text-center"><?php echo e($service->work_quality); ?></td>
                                                        <td class="unit text-center"><?php echo e($service->work_price); ?></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr style="background: #fff;">
                                                    <td colspan="2">
                                                        <?php if(isset($bank)): ?>
                                                        <h5><strong>Account Details: </strong></h5>
                                                        <h5><strong>Bank Name: </strong><?php echo e($bank->bank_name); ?></h5>
                                                        <h5><strong>Account Holder Name: </strong><?php echo e($bank->holder_name); ?>

                                                        </h5>
                                                        <h5><strong>Account Number : </strong><?php echo e($bank->account_no); ?></h5>
                                                        <h5><strong>Bank Ifsc : </strong><?php echo e($bank->ifsc); ?></h5>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td></td>
                                                    <td colspan="2" rowspan="1">
                                                        <h5 style="border-bottom:1px solid ;padding: 10px 0;">
                                                          Subtotal<strong
                                                                style="float: right"><?php echo e($invoice->invoice->currency); ?> <?php echo e(number_format($invoice->invoice->subtotal_amount ?? 00)); ?>.00</strong>
                                                        </h5>
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                            GST: <?php echo e($invoice->invoice->gst ?? ''); ?>%<strong
                                                                style="float: right">
                                                                <?php echo e($invoice->invoice->currency); ?> <?php echo e(number_format($invoice->invoice->gst_amount ?? 00)); ?>.00</strong>
                                                        </h5>
                                                        <?php if($invoice->invoice->discount ?? 0): ?>
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                            Discount: <strong style="float: right">
                                                            <?php echo e($invoice->invoice->currency); ?> <?php echo e(number_format($invoice->invoice->discount ?? 00)); ?>.00</strong></h5>
                                                        <?php endif; ?> 
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                           Total Amount :<span><strong
                                                                style="float: right"><?php echo e($invoice->invoice->currency); ?> <?php echo e(number_format(($invoice->invoice->total_amount ?? '00'))); ?>.00</strong></span> 
                                                        </h5>
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                           Pay Amount :<span><strong
                                                                style="float: right"><?php echo e($invoice->invoice->currency); ?> <?php echo e(number_format(($invoice->amount ?? '00'))); ?>.00</strong></span> 
                                                        </h5>
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                           Total Paid Amount :<span><strong
                                                                style="float: right"><?php echo e($invoice->invoice->currency); ?> <?php echo e(number_format(($invoice->invoice->pay_amount ?? '00'))); ?>.00</strong></span> 
                                                        </h5>
                                                  
                                                        <h5 style="border-bottom:1px solid; padding: 10px 0;">
                                                            Balance  :<span><strong
                                                                style="float: right"><?php echo e($invoice->invoice->currency); ?> <?php echo e(number_format(($invoice->invoice->balance ?? '00'))); ?>.00</strong></span> 
                                                        </h5>
                                                    </td>
                                                </tr>
                                                <tr style="background:#fff;">
                                                    <td>
                                                        <?php if(isset( $bank)): ?>
                                                        <h6 class=""><strong>
                                                                Scan Now
                                                            </strong></h6>
                                                        <img src="<?php echo e(asset($bank->scanner)); ?>" alt="scanner" width="120px"
                                                            style="border: 1px solid">
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr style="background:#fff;">
                                                    <td class="text-center" colspan="5">
                                                        <div><strong>
                                                                Note:
                                                            </strong>A finance charge of 1.5% Monthly will be made on unpaid
                                                            balances
                                                            after 30 days.</div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        
                                    </main>
                                    <footer>
                                        Receipt Generate By AdxVenture- <?php echo e(Date('Y')); ?> | <a href="https://adxventure.com/"
                                            target="_blank">www.adxventure.com</a>

                                            <a style="float:right">Created By: <?php echo e(Auth::User()->name); ?></a>
                                    </footer>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    <div>
        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#sendModal"><i class="bi bi-send"></i> Send</button>
    </div>

    <div class="modal fade" id="sendModal" tabindex="-1" aria-labelledby="leadModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leadModalLabel">Send Invoice </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form class="ajax-form" data-action="<?php echo e(route('receipt.send',[$invoice->id])); ?>" data-method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label class="form-label">Payment Link / Invoice Send Via <span class="text-danger" >*</span> </label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="sendbywhatshapp" id="sendByWhatsapp" value="1">
                                            <label class="form-check-label" for="sendByWhatsapp">Send by Whatsapp</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="sendbyemail" id="sendbyemail" value="1">
                                            <label class="form-check-label" for="sendbyemail">Send by Email</label>
                                        </div>     
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /home/adxventure/lara_tms/resources/views/admin/invoice/receipt.blade.php ENDPATH**/ ?>