<!-- Data Table or List Section -->
<div id="data-content">
    <!-- Invoice summery   -->
    <div class="col-12 my-2">
        <div class="row">
            <div class="col"  style="font-size: 20px;"> Total Invoice: <strong><?php echo e($totalInvoice); ?></strong></div>
            <div class="col"  style="font-size: 20px;"> Total Amount:  <strong><?php echo e($totalInvoicePrice); ?></strong> </div>
            <!-- <div class="col"  style="font-size: 20px;"> Total GST:  <strong><?php echo e($totalGstAmount); ?></strong></div> -->
            <div class="col"  style="font-size: 20px;"> Total pay Amount:  <strong><?php echo e($totalInvoicePay); ?> </strong></div>
            <div class="col"  style="font-size: 20px;"> Total Balance:  <strong><?php echo e($totalInvoiceBalance); ?></strong></div>
        </div>
    </div>
    <!-- table data  -->
    <table class="table table-striped">
        <thead>
            <tr class="bg-success text-white table-bordered ">
                <th scope="col">Client Details</th>
                <th scope="col">Amount Details</th>
                <th scope="col">Followup</th>
                <th scope="col">Invoice</th>
                <th scope="col">Mark as Paid</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($data) && $data->count() >= 1): ?>
                <?php $i=1 ?>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $projects = collect(); // Initialize as an empty collection
                        if (optional($d->client)->id) {
                            $projects = DB::table('projects')->where('client_id', $d->client->id)->get();
                        }
                    ?>
                    <tr class="">
                        <td>
                            <strong><span style="font-size:19px;"><?php echo e($data->firstItem() + $key); ?>. <?php echo e(optional($d->lead)->name ?? $d->client->name); ?> </span>
                                <?php if($d->lead_id): ?>
                                    <span class="badge bg-success">Fresh Sale</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">Up Sale</span>
                                <?php endif; ?>
                            </strong><br> 
                            <?php if(isset($d->lead->email) || isset($d->client->email)): ?>
                                <small><?php echo e(optional($d->lead)->email ?? optional($d->client)->email); ?></small><br>
                            <?php endif; ?>
                            <span style="font-size:18px;"><?php echo e(optional($d->lead)->phone ?? $d->client->phone_no); ?></span><br>
                            Billing Date: <strong><?php echo e((new \DateTime($d->billing_date))->format('d-m-Y')); ?></strong><br>
                                <b>Service:</b> <?php if($d->service): ?>
                                <?php echo e($d->service->work_name); ?>

                                <?php else: ?>
                                <?php $__currentLoopData = $d->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($service->work_name); ?><br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </td>
                        
                        <td>
                        <?php 
                            $latestPayment = $d->payment()->latest('created_at')->first();
                            $nextPyament = $d->payment()->latest('next_billing_date')->first();
                            $latestPaymentDate = $latestPayment ? \Carbon\Carbon::parse($latestPayment->created_at) : null;
                            $dayDifference = $latestPaymentDate ? $latestPaymentDate->diffInDays(\Carbon\Carbon::today()) : 'N/A';
                        ?>
                    <span style="font-size: 20px;">Total Amount : <b><?php echo e($d->currency ?? 'N/A'); ?> <?php echo e($d->total_amount); ?></b></span>
                            <?php if($d->gst == 0 && isset($d->gst)): ?>
                            <span class="badge bg-danger mb-2">Without GST Bill</span>
                            <?php else: ?> 
                            <span class="badge bg-success mb-2">GST Bill</span>
                            <?php endif; ?><br> 
                        <?php if($d->pay_amount): ?>
                        <small style="font-size: 18px;">Pay Amount: <b> <?php echo e($d->currency ?? 'N/A'); ?>  <?php echo e($d->pay_amount); ?></b> 
                        <?php if($dayDifference >= 1): ?>
                        <?php echo e($dayDifference); ?> days ago
                        <?php endif; ?>
                        </small><br>
                        
                        <?php endif; ?>
                        <small style="font-size: 18px;">Balance: <b><?php echo e($d->currency ?? 'N/A'); ?> <?php echo e($d->balance); ?></b></small><br>
                        <?php if($d->status !="2"): ?>
                        Next  Payment Date: <strong><?php echo e($nextPyament ? \Carbon\Carbon::parse($nextPyament->next_billing_date)->format('d-m-Y') : 'N/A'); ?></strong> 
                        <br>
                        <?php endif; ?>
                        Deposit Date: <strong><?php echo e($latestPayment && $latestPayment->desopite_date ? \Carbon\Carbon::parse($latestPayment->desopite_date)->format('d-m-Y') : 'N/A'); ?></strong><br>
                        <?php if(isset($d->payment->sortByDesc('created_at')->first()->delay_days ) && $d->payment->sortByDesc('created_at')->first()->delay_days >=1): ?>
                        <span class="badge bg-danger">
                            <?php echo e($d->payment->sortByDesc('created_at')->first()->delay_days ?? 'No'); ?> Days Delay
                        </span>
                        <?php endif; ?>
                            <?php if($d->status == "2"): ?>
                                <strong class="text-success">Paid<br>
                                    <?php if($d->payment->isNotEmpty()): ?>
                                    <?php echo e(\Carbon\Carbon::parse($d->payment->last()->created_at ?? "N/A")->format('d-m-Y / H:i:s')); ?>

                                    <?php else: ?>
                                        Unpaid
                                    <?php endif; ?>
                                    <br>
                                    <?php if($latestPayment = $d->payment->sortByDesc('id')->first()): ?>
                                    <?php
                                    $delayInDays =
                                    \Carbon\Carbon::parse($latestPayment->created_at)->startOfDay()->diffInDays(\Carbon\Carbon::parse($latestPayment->desopite_date)->startOfDay());
                                    ?>
                                    <?php if($delayInDays == 0): ?>
                                    <?php else: ?>
                                    <strong class="text-danger" style="cursor:pointer" data-bs-toggle="modal"
                                        data-bs-target="#delaypaidreson<?php echo e($d->payment->last()->id); ?>">
                                        Delay: <?php echo e($delayInDays); ?> Days
                                    </strong>
                                    <?php endif; ?>
                                    <?php else: ?>
                                    <?php endif; ?>
                                </strong>
                            <?php elseif($d->status == "1"): ?>
                                <strong class="badge bg-warning text-dark">Partial-Paid</strong><br>
                            <?php else: ?>
                                <strong class="badge bg-danger">Unpaid</strong><br>
                            <?php endif; ?>
                            
                        </td>
                        <td>
                            <a class="btn btn-sm btn-primary"  onclick="Followup(<?php echo e($d->id); ?>,'<?php echo e($d->client->name ?? $d->lead->name); ?>')">Followup 
                                <?php if($d->followup->count() >=1): ?>
                                <?php echo e($d->followup->count()); ?>

                                <?php endif; ?>
                            </a>
                            <button class="btn btn-success" onclick="Whatsapp(<?php echo e($d->id); ?>)"><i class="bi bi-whatsapp"></i></button><br>
                            <button class="btn btn-outline-info mt-2 text-dark" onclick="SendPaymentLink(<?php echo e($d->id); ?>, <?php echo e($d->Bank->id); ?>, '<?php echo e($d->Bank->bank_name); ?>', '<?php echo e($d->Bank->account_no); ?>')">Send Payment Details</button><br>
                            <?php if($d->followup->isNotEmpty()): ?>
                            <small> Last Followup: <strong><?php echo e($d->followup->last()->created_at); ?></strong></small><br>
                        <?php endif; ?>
                        <?php
                            $delay = DB::table('follow_up')->where('invoice_id',$d->id)->where('delay','!=','0')->count();
                        ?>
                        <?php if($delay >=1): ?>
                        <span class="badge bg-danger">Delay :<?php echo e($delay); ?></span>
                        <?php endif; ?>
                        </td>
                        <td>
                            <?php if($d->status == "2"): ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('bill',$d->id)); ?>">View Bill</a>
                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('invoice.details',$d->id)); ?>" >All Details</a>
                            <?php else: ?>
                                <a class="btn btn-sm btn-primary" href="<?php echo e($d->pdf); ?>" target="_blank">View Invoice</a>
                                <?php if($d->payment && $d->payment->count() >= 1): ?>
                                    <a class="btn btn-sm btn-warning" href="<?php echo e(route('receipts', $d->id)); ?>">View Receipts</a>
                                <?php endif; ?>

                            <?php endif; ?>  

                            <div class="btn-group">
                            
                            </div>
                        </td>
                        <td>
                            <?php if($d->status !="2"): ?>
                            <a class="btn btn-sm btn-warning" onclick="MarkAsPaid(<?php echo e($d->id); ?>,<?php echo e($d->balance); ?>,'<?php echo e($d->client->name ?? $d->lead->name); ?>')">Mark as Paid</a><br>
                            <?php endif; ?>
                            <?php if($d->status): ?>
                                <?php if($d->is_project != 1): ?>
                                <a class="btn btn-sm btn-primary mt-2" href="<?php echo e(route('projects.create',['invoiceId'=>$d->id])); ?>">Add Project</a>
                                <?php else: ?>
                                <a class="btn btn-sm btn-success mt-2" href="#">Project Already Add</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical " type="button" id="dropdownMenuButton2"
                                    data-bs-toggle="dropdown" aria-expanded="false"></i>
                                    
                                <ul class="dropdown-menu dropdown-menu-light"
                                    aria-labelledby="dropdownMenuButton2">
                                    <?php if($d->status): ?>
                                    <!-- <li><a class="dropdown-item active" href="<?php echo e(route('projects.create',['invoiceId'=>$d->id])); ?>">Add Project</a></li> -->
                                    <!-- <li><a class="dropdown-item" href="<?php echo e(route('invoice.status', ['status' => '4', 'id' => $d->id])); ?>">Receipts</a></li> -->
                                    <?php else: ?>
                                    <li><a class="dropdown-item active" href="#">Cancel</a></li>
                                    <?php endif; ?>
                                    
                                    
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
            <tr>
                <td colspan="9" class="text-center">No data available</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php /**PATH /home/adxventure/lara_tms/resources/views/admin/invoice/partials/data.blade.php ENDPATH**/ ?>