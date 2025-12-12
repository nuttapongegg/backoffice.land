<!DOCTYPE html>
<html lang="en" data-layout="horizontal" data-hor-style="hor-hover" data-logo="centerlogo">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />

    <!-- Title -->
    <title> Land Backoffice | <?php echo $title; ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url('/assets/img/brand/logo.png'); ?>" type="image/x-icon" />

    <!-- Icons css -->
    <link href="<?php echo base_url('/assets/css/icons.css'); ?>" rel="stylesheet">

    <!-- <link href="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">

    <!-- datatable -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <!-- <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css" /> -->

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="<?php echo base_url('/assets/plugins/datatable/tableCards.css'); ?>" />
    <!--  Bootstrap css-->
    <link id="style" href="<?php echo base_url('/assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" />

    <!-- datatable time -->
    <!-- <link id="style" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" /> -->
    <link id="style" href="https://cdn.datatables.net/datetime/1.1.2/css/dataTables.dateTime.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/smartphoto@1.1.0/css/smartphoto.min.css">

    <!-- Style css -->
    <link href="<?php echo base_url('/assets/css/style.css'); ?>" rel="stylesheet">

    <!-- iziToast css -->
    <link href="<?php echo base_url('/assets/app/css/izitoast/iziToast.min.css'); ?>" rel="stylesheet">

    <!-- Plugins css -->
    <link href="<?php echo base_url('/assets/css/plugins.css'); ?>" rel="stylesheet">

    <!-- Switcher css -->
    <link href="<?php echo base_url('/assets/switcher/css/switcher.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('/assets/switcher/styles.css?v=' . time()); ?>" rel="stylesheet" />

    <?php if (isset($css_critical)) {
        echo $css_critical;
    } ?>

    <link href="<?php echo base_url('/assets/app/css/app.css'); ?>" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url('/assets/css/image-uploader.min.css'); ?>">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <style>
        /*=====================================
         preload class
        ======================================*/
        html.preload_screen {
            overflow: hidden;
            position: relative;
        }

        .preload {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            background-color: #000;
            z-index: 1035;
            transition: all 0.3s;
            -webkit-transition: all 0.3s;
            -moz-transition: all 0.3s;
            -ms-transition: all 0.3s;
            -o-transition: all 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 5rem;
            line-height: 0;
        }

        .preload.hide {
            opacity: 0;
            visibility: hidden;
        }

        .processing-transfer {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, .7);
            z-index: 1035;
            transition: all 0.3s;
            -webkit-transition: all 0.3s;
            -moz-transition: all 0.3s;
            -ms-transition: all 0.3s;
            -o-transition: all 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 5rem;
            line-height: 0;
        }

        .processing-transfer.hide {
            opacity: 0;
            visibility: hidden;
        }

        @keyframes blink {
            0% {
                opacity: .2;
            }

            20% {
                opacity: 1;
            }

            100% {
                opacity: .2;
            }
        }

        .preload span {
            animation-name: blink;
            animation-duration: 1.4s;
            animation-iteration-count: infinite;
            animation-fill-mode: both;
            color: #fff;
        }

        .preload span:nth-child(2) {
            animation-delay: .2s;
        }

        .preload span:nth-child(3) {
            animation-delay: .4s;
        }

        .profile-logo {
            width: 20px;
            height: 20px;
        }

        .preload-logo {
            width: 60px;
            height: 60px;
        }
    </style>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            var div = document.getElementsByTagName("div")
            setTimeout(function() {
                var class_data = document.querySelector('html').classList[0]
                document.querySelector('html').classList.remove(class_data)
                document.querySelector(".preload").classList.add('hide')
            }, 1000)
        })
    </script>
    <script>
        var serverUrl = '<?php echo base_url(); ?>'
        var PUSHER_KEY = '<?php echo getenv('PUSHER_KEY'); ?>'
        var PUSHER_CLUSTER = '<?php echo getenv('PUSHER_CLUSTER'); ?>'
        var CDN_IMG = '<?php echo getenv('CDN_IMG'); ?>'
    </script>
</head>

<body class="ltr main-body app sidebar-mini">

    <section class="preload">
        <img src="<?php echo base_url('/assets/img/logo.png'); ?>" class="img-fluid" alt="logo" width="20%">
    </section>

    <!-- เลือกธีม -->
    <div class="profiles-gate-container" style="display: none;">
        <div class="centered-div list-profiles-container">
            <div class="list-profiles">
                <h1 class="profile-gate-label">เลือกรูปแบบ</h1>
                <ul class="choose-profile">
                    <li class="profile theme" data-theme-name="switchbtn-light-theme">
                        <div>
                            <a class="profile-link" tabindex="0" data-uia="action-select-profile+primary">
                                <div class="avatar-wrapper">
                                    <div class="profile-icon" style="background-image:url(https://cdn-icons-png.flaticon.com/512/3594/3594101.png)"></div>
                                </div>
                                <span class="profile-name">สว่าง</span>
                            </a>
                            <div class="profile-children"></div>
                        </div>
                    </li>
                    <li class="profile theme" data-theme-name="switchbtn-default">
                        <div>
                            <a class="profile-link" tabindex="0" data-uia="action-select-profile+secondary">
                                <div class="avatar-wrapper">
                                    <div class="profile-icon" style="background-image:url(https://cdn-icons-png.flaticon.com/512/3751/3751403.png)"></div>
                                </div>
                                <span class="profile-name">ปกติ</span>
                            </a>
                            <div class="profile-children"></div>
                        </div>
                    </li>
                    <li class="profile theme" data-theme-name="switchbtn-dark">
                        <div>
                            <a class="profile-link" tabindex="0" data-uia="action-select-profile+secondary">
                                <div class="avatar-wrapper">
                                    <div class="profile-icon" style="background-image:url(https://cdn-icons-png.flaticon.com/512/740/740878.png)"></div>
                                </div>
                                <span class="profile-name">มืด</span>
                            </a>
                            <div class="profile-children"></div>
                        </div>
                    </li>
                </ul>
            </div>
            <span data-uia="profile-button">
                <button class="btn-x btn--snakeBorder">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <div>สุ่ม <b id="countdown">(30)</b></div>
                </button>
            </span>
        </div>
    </div>

    <div class="progress-top-bar"></div>

    <!-- Back-to-top -->
    <a href="#top" id="back-to-top" class="back-to-top rounded-circle shadow"><i class="las la-arrow-up"></i></a>

    <!-- Switcher -->
    <div class="switcher-wrapper">
        <div class="demo_changer">
            <div class="form_holder sidebar-right1">
                <div class="row">
                    <div class="predefined_styles">
                        <div class="swichermainleft">
                            <h4>รูปแบบ</h4>
                            <div class="skin-body">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex">
                                        <span class="me-auto">ปกติ</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch1" id="switchbtn-default" class="onoffswitch2-checkbox" checked>
                                            <label for="switchbtn-default" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">สว่าง</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch1" id="switchbtn-light-theme" class="onoffswitch2-checkbox">
                                            <label for="switchbtn-light-theme" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                    <div class="switch-toggle d-flex mt-2">
                                        <span class="me-auto">มืด</span>
                                        <p class="onoffswitch2 my-0"><input type="radio" name="onoffswitch1" id="switchbtn-dark" class="onoffswitch2-checkbox">
                                            <label for="switchbtn-dark" class="onoffswitch2-label"></label>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Switcher -->

    <!-- Loader -->
    <div id="global-loader">
        <img src="<?php echo base_url('/assets/img/loader.svg'); ?>" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->
    <div class="star-shadow">
        <div class="stars small"></div>
        <div class="stars medium"></div>
    </div>
    <!-- Page -->
    <div class="page">

        <div class="layout-position-binder">
            <!-- main-header -->
            <div class="main-header side-header sticky nav nav-item">
                <div class=" main-container container-fluid">
                    <div class="main-header-left">
                        <?php
                        $positionID = session()->get('positionID');
                        $link = ($positionID != 0) ? base_url() : base_url('/finx/list');
                        ?>
                        <div class="responsive-logo">
                            <a href="<?php echo $link; ?>" class="header-logo">
                                <img src="<?php echo base_url('/assets/img/logo.png'); ?>" class="mobile-logo dark-logo-1" alt="logo">
                            </a>
                        </div>
                        <div class="app-sidebar__toggle" data-bs-toggle="sidebar">
                            <!-- <div class="icon"></div> -->
                            <a class="open-toggle" href="javascript:void(0)"><i class="header-icon fe fe-align-left"></i></a>
                            <a class="close-toggle" href="javascript:void(0)"><i class="header-icon fe fe-x"></i></a>
                        </div>
                        <div class="logo-horizontal">
                            <a href="<?php echo $link; ?>" class="header-logo">
                                <img src="<?php echo base_url('/assets/img/logo.png'); ?>" class="mobile-logo dark-logo-1" alt="logo">
                                <img src="<?php echo base_url('/assets/img/logo.png'); ?>" class="mobile-logo-1 dark-logo-1" alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="main-header-right">
                        <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                        </button>
                        <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                <ul class="nav nav-item header-icons navbar-nav-right ms-auto">
                                    <!-- <li class="dropdown nav-item  main-header-message">
                                        <a class="new nav-link" data-bs-toggle="dropdown" href="javascript:void(0)">
                                            <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                <title>Messages</title>
                                                <rect x="48" y="96" width="416" height="320" rx="40" ry="40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M112 160l144 112 144-112" />
                                            </svg>
                                            <span class="badge bg-secondary-transparent tx-secondary header-badge">5</span></a>
                                        <div class="dropdown-menu">
                                            <div class="p-3 text-start border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="dropdown-title mb-1 tx-15 font-weight-semibold">Messages
                                                    </h6>
                                                    <a href="chat.html" class="btn btn-sm btn-primary ms-auto my-auto float-end tx-13">View
                                                        All</a>
                                                </div>
                                                <span class="tx-muted tx-11">You have 5 unread messages</span>
                                            </div>
                                            <ul class="list-unstyled main-message-list chat-scroll">
                                                <li class="mb-0">
                                                    <div class="d-flex pd-x-13 py-2 pos-relative">
                                                        <a href="chat.html" class="masked-link"></a>
                                                        <div class="mg-e-10">
                                                            <span class="avatar-sm"><img alt="" src="../assets/img/faces/5.jpg" class="rounded-circle"></span>
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex-between mb-1">
                                                                <p class="mb-0 tx-default tx-13 font-weight-semibold mb-0">
                                                                    Socrates Itumay</p> <span class="tx-muted tx-11 align-self-start min-w-fit-content">2
                                                                    hr</span>
                                                            </div>
                                                            <p class="mb-0 tx-12 tx-muted">Consetetur sanctus consetetur
                                                                amet amet stet,.</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="mb-0">
                                                    <div class="d-flex pd-x-13 py-2 pos-relative">
                                                        <a href="chat.html" class="masked-link"></a>
                                                        <div class="mg-e-10">
                                                            <span class="avatar-sm avatar-status"><img alt="" src="../assets/img/faces/1.jpg" class="rounded-circle"></span>
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex-between mb-1">
                                                                <p class="mb-0 tx-default tx-13 font-weight-semibold mb-0">
                                                                    Sadipscing Et</p> <span class="tx-muted tx-11 align-self-start min-w-fit-content">1
                                                                    D</span>
                                                            </div>
                                                            <p class="mb-0 tx-12 tx-muted">Accusam amet ea voluptua
                                                                labore ipsum.</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="mb-0">
                                                    <div class="d-flex pd-x-13 py-2 pos-relative">
                                                        <a href="chat.html" class="masked-link"></a>
                                                        <div class="mg-e-10">
                                                            <span class="avatar-sm avatar-status"><img alt="" src="../assets/img/faces/9.jpg" class="rounded-circle"></span>
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex-between mb-1">
                                                                <p class="mb-0 tx-default tx-13 font-weight-semibold mb-0">
                                                                    Ea Labore</p> <span class="tx-muted tx-11 align-self-start min-w-fit-content">2
                                                                    D</span>
                                                            </div>
                                                            <p class="mb-0 tx-12 tx-muted">Diam ea nonumy kasd eirmod
                                                                sed..</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="mb-0">
                                                    <div class="d-flex pd-x-13 py-2 pos-relative">
                                                        <a href="chat.html" class="masked-link"></a>
                                                        <div class="mg-e-10">
                                                            <span class="avatar-sm"><img alt="" src="../assets/img/faces/8.jpg" class="rounded-circle"></span>
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex-between mb-1">
                                                                <p class="mb-0 tx-default tx-13 font-weight-semibold mb-0">
                                                                    Kasd Ipsum</p> <span class="tx-muted tx-11 align-self-start min-w-fit-content">1
                                                                    W</span>
                                                            </div>
                                                            <p class="mb-0 tx-12 tx-muted">Et diam aliquyam ut dolor
                                                                labore consetetur.</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="mb-0">
                                                    <div class="d-flex pd-x-13 py-2 pos-relative">
                                                        <a href="chat.html" class="masked-link"></a>
                                                        <div class="mg-e-10">
                                                            <span class="avatar-sm"><img alt="" src="../assets/img/faces/6.jpg" class="rounded-circle"></span>
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex-between mb-1">
                                                                <p class="mb-0 tx-default tx-13 font-weight-semibold mb-0">
                                                                    Eirmod Emet</p> <span class="tx-muted tx-11 align-self-start min-w-fit-content">2
                                                                    W</span>
                                                            </div>
                                                            <p class="mb-0 tx-12 tx-muted">Est sea accusam no ea sea ea.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="text-center dropdown-footer">
                                                <a class="btn btn-primary btn-block text-center" href="javascript:void(0)">MARK ALL AS READ</a>
                                            </div>
                                        </div>
                                    </li> -->
                                    <!-- <li class="dropdown nav-item main-header-notification d-flex">
                                        <a class="new nav-link" href="javascript:void(0)" data-bs-toggle="dropdown">
                                            <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                <title>Shortcuts</title>
                                                <path d="M448 256L272 88v96C103.57 184 64 304.77 64 424c48.61-62.24 91.6-96 208-96v96z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                                            </svg>
                                        </a>
                                        <div class="dropdown-menu">
                                            <div class="p-3 text-start border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="dropdown-title mb-1 tx-15 font-weight-semibold">Shortcuts
                                                    </h6>
                                                    <ul class="ah-actions actions align-items-center ms-auto d-flex">
                                                        <li>
                                                            <a href="javascript:void(0)" class="header-icon-svgs">
                                                                <i class="fe fe-edit"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" class="header-icon-svgs">
                                                                <i class="fe fe-plus"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <span class="tx-muted tx-11">At nonumy duo dolores ipsum.</span>
                                            </div>
                                            <ul class="list-unstyled main-shortcut-list text-center p-2">
                                                <li class="d-inline-block m-1">
                                                    <a href="calendar.html" class="p-3 dropdown-item border radius-4" data-bs-toggle="tooltip" data-bs-placement="top" title="calendar">
                                                        <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                            <title>calendar</title>
                                                            <rect x="48" y="80" width="416" height="384" rx="48" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M128 48v32M384 48v32M464 160H48M304 260l43.42-32H352v168M191.87 306.63c9.11 0 25.79-4.28 36.72-15.47a37.9 37.9 0 0011.13-27.26c0-26.12-22.59-39.9-47.89-39.9-21.4 0-33.52 11.61-37.85 18.93M149 374.16c4.88 8.27 19.71 25.84 43.88 25.84 28.59 0 52.12-15.94 52.12-43.82 0-12.62-3.66-24-11.58-32.07-12.36-12.64-31.25-17.48-41.55-17.48" />
                                                        </svg>
                                                    </a>
                                                </li>
                                                <li class="d-inline-block m-1">
                                                    <a href="contacts.html" class="p-3 dropdown-item border radius-4" data-bs-toggle="tooltip" data-bs-placement="top" title="contacts">
                                                        <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                            <title>contacts</title>
                                                            <path d="M402 168c-2.93 40.67-33.1 72-66 72s-63.12-31.32-66-72c-3-42.31 26.37-72 66-72s69 30.46 66 72z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                                            <path d="M336 304c-65.17 0-127.84 32.37-143.54 95.41-2.08 8.34 3.15 16.59 11.72 16.59h263.65c8.57 0 13.77-8.25 11.72-16.59C463.85 335.36 401.18 304 336 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" />
                                                            <path d="M200 185.94c-2.34 32.48-26.72 58.06-53 58.06s-50.7-25.57-53-58.06C91.61 152.15 115.34 128 147 128s55.39 24.77 53 57.94z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                                            <path d="M206 306c-18.05-8.27-37.93-11.45-59-11.45-52 0-102.1 25.85-114.65 76.2-1.65 6.66 2.53 13.25 9.37 13.25H154" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" />
                                                        </svg>
                                                    </a>
                                                </li>
                                                <li class="d-inline-block m-1">
                                                    <a href="file-manager.html" class="p-3 dropdown-item border radius-4" data-bs-toggle="tooltip" data-bs-placement="top" title="file-manager">
                                                        <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                            <title>file-manager</title>
                                                            <path d="M440 432H72a40 40 0 01-40-40V120a40 40 0 0140-40h75.89a40 40 0 0122.19 6.72l27.84 18.56a40 40 0 0022.19 6.72H440a40 40 0 0140 40v240a40 40 0 01-40 40zM32 192h448" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                                        </svg>
                                                    </a>
                                                </li>
                                                <li class="d-inline-block m-1">
                                                    <a href="mail-inbox.html" class="p-3 dropdown-item border radius-4" data-bs-toggle="tooltip" data-bs-placement="top" title="mail">
                                                        <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                            <title>mail</title>
                                                            <rect x="48" y="96" width="416" height="320" rx="40" ry="40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M112 160l144 112 144-112" />
                                                        </svg>
                                                    </a>
                                                </li>
                                                <li class="d-inline-block m-1">
                                                    <a href="gallery.html" class="p-3 dropdown-item border radius-4" data-bs-toggle="tooltip" data-bs-placement="top" title="gallery">
                                                        <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                            <title>gallery</title>
                                                            <rect x="48" y="80" width="416" height="352" rx="48" ry="48" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                                                            <circle cx="336" cy="176" r="32" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" />
                                                            <path d="M304 335.79l-90.66-90.49a32 32 0 00-43.87-1.3L48 352M224 432l123.34-123.34a32 32 0 0143.11-2L464 368" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                                        </svg>
                                                    </a>
                                                </li>
                                                <li class="d-inline-block m-1">
                                                    <a href="blog.html" class="p-3 dropdown-item border radius-4" data-bs-toggle="tooltip" data-bs-placement="top" title="blog">
                                                        <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                            <title>blog</title>
                                                            <path d="M368 415.86V72a24.07 24.07 0 00-24-24H72a24.07 24.07 0 00-24 24v352a40.12 40.12 0 0040 40h328" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                                                            <path d="M416 464h0a48 48 0 01-48-48V128h72a24 24 0 0124 24v264a48 48 0 01-48 48z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M240 128h64M240 192h64M112 256h192M112 320h192M112 384h192" />
                                                            <path d="M176 208h-64a16 16 0 01-16-16v-64a16 16 0 0116-16h64a16 16 0 0116 16v64a16 16 0 01-16 16z" />
                                                        </svg>
                                                    </a>
                                                </li>
                                                <li class="d-inline-block m-1">
                                                    <a href="shop.html" class="p-3 dropdown-item border radius-4" data-bs-toggle="tooltip" data-bs-placement="top" title="shop">
                                                        <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                            <title>shop</title>
                                                            <circle cx="176" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                                            <circle cx="400" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M48 80h64l48 272h256" />
                                                            <path d="M160 288h249.44a8 8 0 007.85-6.43l28.8-144a8 8 0 00-7.85-9.57H128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                                        </svg>
                                                    </a>
                                                </li>
                                                <li class="d-inline-block m-1">
                                                    <a href="form-elements.html" class="p-3 dropdown-item border radius-4" data-bs-toggle="tooltip" data-bs-placement="top" title="forms">
                                                        <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                            <title>forms</title>
                                                            <path d="M416 221.25V416a48 48 0 01-48 48H144a48 48 0 01-48-48V96a48 48 0 0148-48h98.75a32 32 0 0122.62 9.37l141.26 141.26a32 32 0 019.37 22.62z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                                                            <path d="M256 56v120a32 32 0 0032 32h120" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                                        </svg>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="dropdown-footer">
                                                <a href="javascript:void(0)" class="btn btn-outline-primary btn-block" id="openAllBtn">OPEN ALL</a>
                                            </div>
                                        </div>
                                    </li> -->
                                    <li class="nav-item full-screen fullscreen-button">
                                        <a class="new nav-link full-screen-link" href="javascript:void(0)">
                                            <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                <title>Full Width</title>
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M432 320v112H320M421.8 421.77L304 304M80 192V80h112M90.2 90.23L208 208M320 80h112v112M421.77 90.2L304 208M192 432H80V320M90.23 421.8L208 304" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li class="dropdown right-toggle">
                                        <a class="new nav-link nav-link pe-0" data-bs-toggle="sidebar-right" data-bs-target=".sidebar-right">
                                            <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                <title>ประวัติการใช้งาน</title>
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 160h352M80 256h352M80 352h352" />
                                            </svg>
                                            <span class="pulse"></span>
                                        </a>
                                    </li>
                                    <li class="search-icon d-lg-none d-block">
                                        <form class="navbar-form" role="search">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Search">
                                                <span class="input-group-btn">
                                                    <button type="reset" class="btn btn-default">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <button type="submit" class="btn btn-default nav-link resp-btn">
                                                        <svg class="ionicon header-icon-svgs" viewBox="0 0 512 512">
                                                            <title>Search</title>
                                                            <path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" />
                                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448" />
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                        </form>
                                    </li>
                                    <li class="dropdown main-profile-menu nav-item">
                                        <a class="new nav-link profile-user rounded-circle shadow d-flex" href="javascript:void(0)" data-bs-toggle="dropdown"><img alt="" src="https://evxspst.sgp1.cdn.digitaloceanspaces.com/uploads/img/nullthumbnail.png"></a>
                                        <ul class="dropdown-menu">
                                            <li class="bg-primary p-3 br-ts-5 br-te-5 ">
                                                <div class="d-flex wd-100p">

                                                    <div class="profile-user"><img class="rounded-circle" src="https://evxspst.sgp1.cdn.digitaloceanspaces.com/uploads/img/nullthumbnail.png"></div>
                                                    <div class="ms-3 my-auto">
                                                        <h6 class="tx-15 text-black font-weight-semibold mb-0"><?php echo session()->get('username'); ?>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- </ul> -->
                                            <li><a class="dropdown-item" href="<?php echo base_url('/logout'); ?>"><i class="fe fe-power"></i>ออกจากระบบ</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="d-flex">
                            <a class="switcher-icon new nav-link" href="javascript:void(0);">
                                <svg class="ionicon header-icon-svgs fa-spin" viewBox="0 0 512 512">
                                    <title>Switcher</title>
                                    <path d="M262.29 192.31a64 64 0 1057.4 57.4 64.13 64.13 0 00-57.4-57.4zM416.39 256a154.34 154.34 0 01-1.53 20.79l45.21 35.46a10.81 10.81 0 012.45 13.75l-42.77 74a10.81 10.81 0 01-13.14 4.59l-44.9-18.08a16.11 16.11 0 00-15.17 1.75A164.48 164.48 0 01325 400.8a15.94 15.94 0 00-8.82 12.14l-6.73 47.89a11.08 11.08 0 01-10.68 9.17h-85.54a11.11 11.11 0 01-10.69-8.87l-6.72-47.82a16.07 16.07 0 00-9-12.22 155.3 155.3 0 01-21.46-12.57 16 16 0 00-15.11-1.71l-44.89 18.07a10.81 10.81 0 01-13.14-4.58l-42.77-74a10.8 10.8 0 012.45-13.75l38.21-30a16.05 16.05 0 006-14.08c-.36-4.17-.58-8.33-.58-12.5s.21-8.27.58-12.35a16 16 0 00-6.07-13.94l-38.19-30A10.81 10.81 0 0149.48 186l42.77-74a10.81 10.81 0 0113.14-4.59l44.9 18.08a16.11 16.11 0 0015.17-1.75A164.48 164.48 0 01187 111.2a15.94 15.94 0 008.82-12.14l6.73-47.89A11.08 11.08 0 01213.23 42h85.54a11.11 11.11 0 0110.69 8.87l6.72 47.82a16.07 16.07 0 009 12.22 155.3 155.3 0 0121.46 12.57 16 16 0 0015.11 1.71l44.89-18.07a10.81 10.81 0 0113.14 4.58l42.77 74a10.8 10.8 0 01-2.45 13.75l-38.21 30a16.05 16.05 0 00-6.05 14.08c.33 4.14.55 8.3.55 12.47z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /main-header -->

            <!-- main-sidebar -->
            <div class="sticky">
                <aside class="app-sidebar">
                    <div class="main-sidebar-header active">
                        <a class="header-logo active" href="index.html">
                            <img src="../assets/img/brand/logo-white.png" class="main-logo  desktop-dark" alt="logo">
                            <img src="../assets/img/brand/favicon-white.png" class="main-logo  mobile-dark" alt="logo">
                        </a>
                    </div>
                    <div class="main-sidemenu">
                        <div class="slide-left" id="slide-left">
                            <svg fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                            </svg>
                        </div>
                        <ul class="side-menu">
                            <?php if (session()->get('positionID') != 0) { ?>
                                <li class="side-item side-item-category">บริหาร</li>
                                <li class="slide">
                                    <a class="side-menu__item has-link <?php if (service('uri')->getSegment(1) == 'loan') {
                                                                            echo 'active';
                                                                        } ?>" data-bs-toggle="slide" href="javascript:void(0)" id="other_menu">
                                        <i class="ionicon side-menu__icon fas fa-hand-holding-usd"></i>
                                        <span class="side-menu__label">สินเชื่อ</span><i class="angle fe fe-chevron-right"></i>
                                    </a>
                                    <ul class="slide-menu">
                                        <li class="side-menu__label1"><a href="<?php echo base_url('/loan/list'); ?>">สินเชื่อ</a></li>
                                        <li><a class="slide-item <?php if (service('uri')->getSegment(1) == 'loan' && service('uri')->getSegment(2) == 'list') {
                                                                        echo 'active';
                                                                    } ?>" href="<?php echo base_url('/loan/list'); ?>">รายการสินเชื่อ/เปิดสินเชื่อ</a></li>
                                        <!-- <li><a class="slide-item <?php if (service('uri')->getSegment(1) == 'loan' && service('uri')->getSegment(2) == 'list_history') {
                                                                            echo 'active';
                                                                        } ?>" href="<?php echo base_url('/loan/list_history'); ?>">ประวัติสินเชื่อ</a></li> -->
                                        <li><a class="slide-item <?php if (service('uri')->getSegment(1) == 'loan' && service('uri')->getSegment(2) == 'report_loan') {
                                                                        echo 'active';
                                                                    } ?>" href="<?php echo base_url('/loan/report_loan'); ?>">รายงานสินเชื่อ</a></li>
                                        <li><a class="slide-item <?php if (service('uri')->getSegment(1) == 'loan' && service('uri')->getSegment(2) == 'report_revenues') {
                                                                        echo 'active';
                                                                    } ?>" href="<?php echo base_url('/loan/report_revenues'); ?>">รายงานรายรับ/รายจ่าย</a></li>
                                        <li><a class="slide-item <?php if (service('uri')->getSegment(1) == 'Maps') {
                                                                        echo 'active';
                                                                    } ?>" href="<?php echo base_url('/Maps'); ?>">Maps</a></li>
                                    </ul>
                                </li>
                                <li class="side-item side-item-category">จัดการ</li>
                                <li class="slide">
                                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)">
                                        <i class="ionicon side-menu__icon icon ion-ios-calculator"></i>
                                        <span class="side-menu__label">บัญชี</span><i class="angle fe fe-chevron-right"></i>
                                    </a>
                                    <ul class="slide-menu">
                                        <li class="side-menu__label1"><a href="#">บัญชี</a></li>
                                        <li><a class="slide-item selectDoc" href="#" data-bs-toggle="modal" data-bs-target="#docModal" data-doc-type="ใบสำคัญรับ">ใบสำคัญรับ</a></li>
                                        <li><a class="slide-item selectDoc" href="#" data-bs-toggle="modal" data-bs-target="#docModal" data-doc-type="ใบสำคัญจ่าย">ใบสำคัญจ่าย</a></li>
                                        <!-- class="disabled" -->
                                    </ul>
                                </li>
                                <li class="slide">
                                    <a class="side-menu__item has-link <?php if (service('uri')->getSegment(1) == 'setting_land' && service('uri')->getSegment(2) == 'index') {
                                                                            echo 'active';
                                                                        } ?>" data-bs-toggle="slide" href="<?php echo base_url('/setting_land/index'); ?>">
                                        <i class="ionicon side-menu__icon icon ion-ios-construct"></i>
                                        <span class="side-menu__label">ตั้งค่า</span>
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="slide">
                                <a class="side-menu__item has-link <?php if (service('uri')->getSegment(1) == 'finx') {
                                                                        echo 'active';
                                                                    } ?>" data-bs-toggle="slide" href="javascript:void(0)" id="other_menu">
                                    <i class="ionicon side-menu__icon fas fa-hand-holding-usd"></i>
                                    <span class="side-menu__label">Finx</span><i class="angle fe fe-chevron-right"></i>
                                </a>
                                <ul class="slide-menu">
                                    <li class="side-menu__label1"><a href="<?php echo base_url('/finx/list'); ?>">Finx</a></li>
                                    <li><a class="slide-item <?php if (service('uri')->getSegment(1) == 'finx' && service('uri')->getSegment(2) == 'list') {
                                                                    echo 'active';
                                                                } ?>" href="<?php echo base_url('/finx/list'); ?>">รายการสินเชื่อ</a></li>
                                    <li><a class="slide-item <?php if (service('uri')->getSegment(1) == 'finx' && service('uri')->getSegment(2) == 'report_finx') {
                                                                    echo 'active';
                                                                } ?>" href="<?php echo base_url('/finx/report_finx'); ?>">รายงานสินเชื่อ</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="slide-right" id="slide-right">
                            <svg fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                            </svg>
                        </div>
                    </div>
                </aside>
            </div>
            <!-- main-sidebar -->
        </div>