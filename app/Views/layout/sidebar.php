<?php $roleId = session('role_id'); ?>

<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
        <div class="logo-wrapper"><a href="<?= base_url("/") ?>"><img class="img-fluid for-light" src="<?= base_url() ?>/assets/images/logo/logo.png" alt=""><img class="img-fluid for-dark" src="<?= base_url() ?>/assets/images/logo/logo_dark.png" alt=""></a>
            <div class="back-btn"><i class="fa fa-angle-left"></i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
        </div>
        <div class="logo-icon-wrapper"><a href="<?= base_url("/") ?>"><img class="img-fluid" src="<?= base_url() ?>/assets/images/logo/logo-icon.png" alt=""></a></div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn"><a href="<?= base_url("/") ?>"><img class="img-fluid" src="<?= base_url() ?>/assets/images/logo/logo-icon.png" alt=""></a>
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="pin-title sidebar-main-title">
                        <div>
                            <h6>Pinned</h6>
                        </div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6 class="lan-1">General</h6>
                        </div>
                    </li>
                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav" href="<?= base_url("/admin/dashboard") ?>">
                            <svg class="stroke-icon">
                                <use href="<?= base_url() ?>/assets/svg/icon-sprite.svg#stroke-home"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?= base_url() ?>/assets/svg/icon-sprite.svg#fill-home"></use>
                            </svg><span>Dashboard</span></a>
                    </li>
                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="#">
                            <svg class="stroke-icon">
                                <use href="<?= base_url() ?>/assets/svg/icon-sprite.svg#stroke-user"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?= base_url() ?>/assets/svg/icon-sprite.svg#fill-user"></use>
                            </svg><span>Patients</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?= base_url("/admin/patient/all") ?>">All Patients</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="#">
                            <i data-feather="calendar"></i><span>Appointments</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?= base_url("/admin/appointment/all") ?>">All Appointments</a></li>
                        </ul>
                    </li>
                    <?php if(isset($roleId) && $roleId <=2){?>
                    <li class="sidebar-main-title">
                        <div>
                            <h6 class="lan-">Admin</h6>
                        </div>
                    </li>
                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="#">
                            <i data-feather="users"></i><span>User</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?= base_url("/admin/user/all") ?>">View All</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="#">
                            <i data-feather="database"></i><span>Data Feed</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?= base_url("/admin/roles/all") ?>">Roles</a></li>
                            <li><a href="<?= base_url("/admin/permissions/all") ?>">Permission</a></li>
                            <li><a href="<?= base_url("/admin/permissions/assign") ?>">Permission Assign</a></li>
                            <li><a href="<?= base_url("/admin/labtest/all") ?>">Lab Tests</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>