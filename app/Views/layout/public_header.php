<!-- Page Header Start-->
<?php
$name = (session('firstName'))?:''.(session('lastName'))?:'';
$role = (session('role'))?:'';
?>
<div class="page-header">
    <div class="header-wrapper row m-0">
        <form class="form-inline search-full col" action="#" method="get">
            <div class="form-group w-100">
                <div class="Typeahead Typeahead--twitterUsers">
                    <div class="u-posRelative">
                        <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Cuba .." name="q" title="" autofocus>
                        <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div><i class="close-search" data-feather="x"></i>
                    </div>
                    <div class="Typeahead-menu"></div>
                </div>
            </div>
        </form>
        <div class="header-logo-wrapper col-auto p-0">
            <div class="logo-wrapper"><a href="<?= base_url("/") ?>"><img class="img-fluid" src="<?= base_url() ?>/assets/images/logo/logo.png" alt=""></a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
        </div>
        <div class="left-header col-xxl-5 col-xl-6 col-lg-5 col-md-4 col-sm-3 p-0">
            <div class="notification-slider">
                <div class="buy-btn rounded-pill">
                    <a class="nav-link js-scroll" href="<?=base_url()?>create-appointment" target="_self">MAKE AN APPOINTMENT</a>
                </div>
            </div>
        </div>
        <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
            <ul class="nav-menus">
                <li class="onhover-dropdown">
                    <div class="notification-box">
                        <svg>
                            <use href="<?= base_url() ?>/assets/svg/icon-sprite.svg#notification"></use>
                        </svg><span class="badge rounded-pill badge-secondary">4 </span>
                    </div>
                    <div class="onhover-show-div notification-dropdown">
                        <h6 class="f-18 mb-0 dropdown-title">Notitications </h6>
                        <ul>

                        </ul>
                    </div>
                </li>
                <li class="profile-nav onhover-dropdown pe-0 py-0">
                    <div class="media profile-media"><img class="b-r-10" src="<?= base_url() ?>/assets/images/dashboard/profile.png" alt="">
                        <div class="media-body"><span><?=$name?></span>
                            <p class="mb-0 font-roboto"><?=$role?> <i class="middle fa fa-angle-down"></i></p>
                        </div>
                    </div>
                    <ul class="profile-dropdown onhover-show-div">

                        <li><a href="<?= (session('user_id'))?base_url().'sign-out':base_url().'login' ?>"><i data-feather="log-in"> </i><span><?= (session('user_id'))?'Sign out':'Log in' ?></span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Page Header Ends -->