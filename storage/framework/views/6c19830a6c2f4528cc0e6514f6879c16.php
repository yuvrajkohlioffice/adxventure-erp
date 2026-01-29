<header id=header class="header fixed-top">
    <div class="row align-items-center">
        <div class="col-2">
            <a href="<?php echo e(url('/dashboard')); ?>" class="logo d-flex align-items-center">
                <img src="<?php echo e(asset('logo.png')); ?>" alt="logo">
            </a>
        </div>
        <div class="col-2">
            <div class=search-bar>
                <h6 style=font-weight:600;font-size:small;>
                   <i class="bi bi-calendar-check-fill"></i> <?php echo e(date('d/m/Y')); ?> 
                    <spna id=live-time></spna>
                </h6>
            </div>
        </div>
        <div class="col-5 d-flex gap-5">
            <?php if(auth()->check()): ?> 
            <?php $userId = auth()->user()->id; $times = \App\Helpers\LogHelper::getLoginLogoutTimes($userId); ?>
            <div class=search-bar>
                <h6 style=font-weight:600;font-size:small;><i class="bi bi-alarm"></i> Login Time: <?php echo e($times['login_time'] ?? 'Not Available'); ?></h6>
            </div>
            <div class=search-bar>
                <h6 style=font-weight:600;font-size:small;><i class="bi bi-alarm-fill"></i> Logout Time: <?php echo e($times['logout_time'] ?? 'Not Available'); ?></h6>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-1"></div>
        <div class="col-2">
            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center justify-content-end">
                    <li class="nav-item d-block d-lg-none">
                        <a class="nav-link nav-icon search-bar-toggle" href=#>
                        <i class="bi bi-search"></i>
                        </a>
                    </li>
                    <?php if(false): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon" href=# data-bs-toggle=dropdown>
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-primary badge-number">4</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                        <li class=dropdown-header>
                            You have 4 new notifications
                            <a href=#><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=notification-item>
                            <i class="bi bi-exclamation-circle text-warning"></i>
                            <div>
                                <h4>Lorem Ipsum</h4>
                                <p>Quae dolorem earum veritatis oditseno</p>
                                <p>30 min. ago</p>
                            </div>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=notification-item>
                            <i class="bi bi-x-circle text-danger"></i>
                            <div>
                                <h4>Atque rerum nesciunt</h4>
                                <p>Quae dolorem earum veritatis oditseno</p>
                                <p>1 hr. ago</p>
                            </div>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=notification-item>
                            <i class="bi bi-check-circle text-success"></i>
                            <div>
                                <h4>Sit rerum fuga</h4>
                                <p>Quae dolorem earum veritatis oditseno</p>
                                <p>2 hrs. ago</p>
                            </div>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=notification-item>
                            <i class="bi bi-info-circle text-primary"></i>
                            <div>
                                <h4>Dicta reprehenderit</h4>
                                <p>Quae dolorem earum veritatis oditseno</p>
                                <p>4 hrs. ago</p>
                            </div>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=dropdown-footer>
                            <a href=#>Show all notifications</a>
                        </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon" href=# data-bs-toggle=dropdown>
                        <i class="bi bi-chat-left-text"></i>
                        <span class="badge bg-success badge-number">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                        <li class=dropdown-header>
                            You have 3 new messages
                            <a href=#><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=message-item>
                            <a href=#>
                                <img src=assets/img/messages-1.jpg alt="" class=rounded-circle>
                                <div>
                                    <h4>Maria Hudson</h4>
                                    <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                    <p>4 hrs. ago</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=message-item>
                            <a href=#>
                                <img src=assets/img/messages-2.jpg alt="" class=rounded-circle>
                                <div>
                                    <h4>Anna Nelson</h4>
                                    <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                    <p>6 hrs. ago</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=message-item>
                            <a href=#>
                                <img src=assets/img/messages-3.jpg alt="" class=rounded-circle>
                                <div>
                                    <h4>David Muldon</h4>
                                    <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                    <p>8 hrs. ago</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class=dropdown-divider>
                        </li>
                        <li class=dropdown-footer>
                            <a href=#>Show all messages</a>
                        </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <li class="dropdown notification-list topbar-dropdown ">
                        <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <?php if(!auth()->user()->image): ?>
                                <img src="<?php echo e(asset('/user1.png')); ?>" alt="user-image" class="rounded-circle" style="height: 32px;width: 32px;">
                            <?php else: ?>
                                <img src="<?php echo e(asset(auth()->user()->image)); ?>" alt="user-image" class="rounded-circle" style="height: 32px;width: 32px;">
                            <?php endif; ?>
                            <span style=font-weight:600;font-size:small;><?php echo e(auth()->user()->name); ?> (<?php echo e(auth()->user()->roles()->first()->name); ?>) <i class="mdi mdi-chevron-down"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown p-2">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <!-- item-->
                            <a class="dropdown-item notify-item" href="<?php echo e(url('profile')); ?>">
                                   <i class="bi bi-person-circle"></i>
                                    <span>My Profile</span>
                                </a>

                            <div class="dropdown-divider"></div>
                            <!-- item-->
                            <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="type" value="0" id="logout-type">
                                <button class="btn btn-danger w-100 mt-2" type="button" id="logout-button"><i class="bi bi-box-arrow-left"></i> Log out</button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    


</header>


 





<!-- Modal HTML -->
<div class="modal fade" id="lateModal" tabindex="-1" aria-labelledby="lateModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
              <h5 class="modal-title" id="lateModalLabel">Good Morning 👋🏼,<b><?php echo e(ucfirst(auth()->user()->name)); ?></b></h5>
         </div>
         <div class="modal-body">
            <div class="row align-items-center">
                <div class="col-6">
                    <div>
                        <img src="<?php echo e(asset('late-image.jpg')); ?>" alt="late-image" width="100%" style="border-radius: 8px;">
                    </div>
                </div>
                <div class="col-6">
                   <h3 class="text-center my-2 fs-2" style="color:#FE6600;"> 
                        <span style="font-size:80px;">🕔</span><br>
                        <span style="font-weight:600">Every day is a new chance to do better!</span>
                    </h3>
                    <form id="lateReasonForm">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Please provide a reason for being late:</label>
                            <textarea class="form-control" id="reason" name="reason" required></textarea>
                            <span class="text-danger" id="reason-error"></span>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="late-reason-submit-btn">Submit your valid reason</button>
                    </form>
                </div>
            </div>
        
         </div>
      </div>
   </div>
</div>
<?php /**PATH /home/bookmziw/lara_tms/laravel/resources/views/components/admin/header.blade.php ENDPATH**/ ?>