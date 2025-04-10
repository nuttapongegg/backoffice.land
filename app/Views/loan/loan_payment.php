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

    <div class="progress-top-bar"></div>

    <!-- Back-to-top -->
    <a href="#top" id="back-to-top" class="back-to-top rounded-circle shadow"><i class="las la-arrow-up"></i></a>

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
                        <div class="responsive-logo">
                            <a href="javascript:void(0)" class="header-logo">
                                <img src="<?php echo base_url('/assets/img/logo.png'); ?>" class="mobile-logo dark-logo-1" alt="logo">
                            </a>
                        </div>
                        <div class="app-sidebar__toggle" data-bs-toggle="sidebar">
                            <!-- <div class="icon"></div> -->
                            <a class="open-toggle" href="javascript:void(0)"><i class="header-icon fe fe-align-left"></i></a>
                            <a class="close-toggle" href="javascript:void(0)"><i class="header-icon fe fe-x"></i></a>
                        </div>
                        <div class="logo-horizontal">
                            <a href="javascript:void(0)" class="header-logo">
                                <img src="<?php echo base_url('/assets/img/logo.png'); ?>" class="mobile-logo dark-logo-1" alt="logo">
                                <img src="<?php echo base_url('/assets/img/logo.png'); ?>" class="mobile-logo-1 dark-logo-1" alt="logo">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /main-header -->
            <!-- main-sidebar -->
        </div><!-- main-content -->
        <div class="main-content app-content">
            <!-- container -->
            <div class="main-container container-fluid">
                <!-- row -->
                <div class="row">
                    <!-- เนื้อหาขวามือ -->
                    <div id="cardDetailStockCar" class="col-lg-12 col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title justify-content-between d-flex">
                                    <div>
                                        <div class="tx-primary tx-18" id="count_car">ชำระสินเชื่อ</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="payment_loan">
                                        <div class="panel tabs-style2">
                                            <div class="panel-head mb-2">
                                                <div id="btn_seting" class="d-flex justify-content-end">
                                                    <!-- <a href="javascript:void(0);" class="btn btn-outline-primary text-center" id="paymentBTN" name="paymentBTN"><i class="fa-solid fa-plus text-center"></i>&nbsp;&nbsp;ชำระสินเชื่อ</a> -->
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-nowrap border-bottom" id="tablePayment">
                                                        <thead>
                                                            <tr>
                                                                <th class="wd-5p">#</th>
                                                                <th class="wd-15p text-center">เลขที่สินเชื่อ</th>
                                                                <th class="wd-20p text-center">ผู้ชำระ</th>
                                                                <th class="wd-20p text-center">ผู้รับชำระ</th>
                                                                <th class="wd-20p text-center">ยอดชำระ</th>
                                                                <th class="wd-5p text-center">ดอกเบี้ยคงเหลือ</th>
                                                                <th class="wd-5p text-center">งวด</th>
                                                                <th class="wd-5p text-center">วันออกสินเชื่อ</th>
                                                                <th class="wd-5p text-center">กำหนดชำระ</th>
                                                                <th class="wd-5p text-center">วันที่ชำระจริง</th>
                                                                <th class="wd-5p text-center">สถานะ</th>
                                                                <th class="wd-5p text-center"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->


                <!-- modal pay loan -->
                <div class="modal fade" id="modalPayLoanNoLogin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">ชำระสินเชื่อ</h5>
                                <button type="button" class="btn-close modalPaymentLoanClose"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="panel tabs-style5 w-fit-content mx-auto">
                                    <div class="panel-head">
                                        <ul class="nav nav-tabs bg-white">
                                            <li class="nav-item PaymentLoanType"><a class="nav-link tx-14 font-weight-semibold PaymentLoanType1 active" data-bs-toggle="tab" href="javascript:void(0);" onclick="installmentTab();">ชำระรายงวด</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mt-3 mb-3" style="text-align: right;">
                                    <a href="javascript:void(0);" class="btn btn-outline-secondary" id="btnAiAutoCapture" style="display: none;">
                                        <i class="fas fa-camera"></i> ถ่ายรูป
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-outline-primary" id="btnAiAutoSelect" style="display: none;">
                                        <i class="fab fa-reddit-alien"></i> ใช้ AI Auto Input
                                    </a>
                                </div>
                                <div id="detectImageFormInvoice" style="display: none;">
                                    <div class="row">
                                        <div class="col text-center">
                                            <!-- แสดงตัวอย่างภาพ -->
                                            <img id="imagePreviewInvoice" width="17%" class="img-thumbnail" style="display: none;" /><br>
                                            <!-- แสดงตัวอย่าง PDF -->
                                            <iframe id="pdfPreviewInvoice" style="display: none;" width="100%" height="400px"></iframe><br>
                                            <button type="button" class="btn btn-outline-danger btn-rounded mt-3" id="btnAiAutoInputInvoiceClear">ยกเลิก</button>
                                            <button type="submit" class="btn btn-success btn-rounded mt-3" id="btnAiAutoInputInvoiceSubmit">ยืนยัน</button>
                                        </div>
                                    </div>
                                    <div>
                                        <!-- input file ที่รองรับทั้งการเลือกไฟล์และการถ่ายภาพจากกล้องมือถือ -->
                                        <input type="file" class="custom-file-input" id="imageFileInvoice" name="imageFileInvoice" accept="image/*,application/pdf" style="display: none;" />
                                    </div>
                                    <hr>
                                </div>
                                <?php $FORM_KEY = 'FORM_KEY_' . strtotime('now') . '_' . rand(10, 100); ?>
                                <form method="POST" enctype="multipart/form-data" name="formPayloan" id="<?php echo $FORM_KEY; ?>" data-form-key="<?php echo $FORM_KEY; ?>" novalidate>
                                    <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary mt-5">รายละเอียดผู้ชำระ</p>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 tx-right">
                                                    <label class="form-label mt-0">ผู้ชำระ <span class="tx-danger">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="payment_name" id='payment_name' required>
                                                        <input type="hidden" name="codeloan_hidden" id="codeloan_hidden">
                                                        <input type="hidden" name="payment_type" id="payment_type">
                                                        <input type="hidden" name="payment_id" id="payment_id">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 tx-right">
                                                    <label class="form-label mt-0" for="payment_employee_name">ผู้รับชำระ <span class="tx-danger">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <input name="payment_employee_name" id="payment_employee_name" class="form-control" type="text" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <div class="collapse" id="installment_bar">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-4 tx-right">
                                                            <label class="form-label mt-0">งวดที่</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="input-group mb-3">
                                                                <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" value="0" name="installment_count" id="installment_count" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==10) return false;" type="number" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row align-items-center">
                                                    <div class="col-md-4 tx-right">
                                                        <label class="form-label mt-0">วันที่ชำระ</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <div class="input-group-text">
                                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                            </div>
                                                            <input type="text" class="form-control dateToBooking" name="date_to_payment" id="date_to_payment" placeholder="เลือกวันที่" value="<?php echo date('Y-m-d'); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 tx-right">
                                                    <label class="form-label mt-0" for="account_name">บัญชีสินเชื่อ <span class="tx-danger">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <select name="account_name" id='account_name' class="form-control custom-select" data-bs-placeholder="Select ..." required>
                                                            <?php if ($land_accounts) : ?>
                                                                <?php foreach ($land_accounts as $land_account) { ?>
                                                                    <option value="<?php echo $land_account->id; ?>"><?php echo $land_account->land_account_name; ?></option>
                                                                <?php } ?>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 tx-right">
                                                    <label class="form-label mt-0">ยอดชำระ <span class="tx-danger">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-group mb-3">
                                                        <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="payment_now" id="payment_now" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==10) return false;" type="text" required>
                                                        <span class="input-group-text" id="basic-addon2">บาท</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center">
                                                <div class="col-md-12 tx-right">
                                                    <label class="form-label mt-0">ยอดที่ต้องชำระ <font id="price_month">X</font> บาท</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0" for="customer_payment_type">ช่องทางการชำระ <span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <select name="customer_payment_type" id="customer_payment_type" class="form-control form-select" data-bs-placeholder="Select ..." required>
                                                    <option value="" style="color: #000;">เลือกการชำระ</option>
                                                    <option value="โอน">โอน</option>
                                                    <option value="เงินสด">เงินสด</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                        <!-- <div class="col-6">
                                            <div class="collapse" id="bill_credit">
                                                <div class="row align-items-center">
                                                    <div class="col-md-4 tx-right">
                                                        <label class="form-label mt-0">หลักฐานการชำระ <span class="tx-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input class="form-control" type="file" id="file_payment" name="file_payment">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="col-6">
                                        </div>
                                    </div>
                                    <div id="bookingWrapperFormPaymentType">
                                        <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary mt-5">ข้อมูลการคำนวนรายการสินเชื่อ</p>
                                        <div class="collapse" id="pay_sum_loan">
                                            <div class="row">
                                                <div class="col-6" id="car_name">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-4 tx-right">
                                                            <label class="form-label mt-0">ยอดชำระรวมทั้งสิ้น</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="input-group mb-3">
                                                                <input aria-describedby="basic-addon2" aria-label="" class="form-control" placeholder="" name="pay_sum" id="pay_sum" type="text" readonly>
                                                                <span class="input-group-text" id="basic-addon2">บาท</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-4 tx-right">
                                                            <label class="form-label mt-0">ยอดสินเชื่อรวม</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="input-group mb-3">
                                                                <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="total_loan_payment" id="total_loan_payment" type="text" value="" readonly>
                                                                <span class="input-group-text" id="basic-addon2">บาท</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div id="btn_edit_detail" style="display: flex; justify-content: center;">
                                        <button class="btn btn-primary btn-block btn-add-loan-payment" type="button">บันทึก</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar-right-->

        <?php echo $this->include('/layouts/modal'); ?>

        <!-- Footer opened -->
        <div class="main-footer">
            <div class="col-md-12 col-sm-12 text-center">
                <div class="container-fluid pd-t-0 ht-100p">
                    Copyright © 2022 <a href="javascript:void(0)" class="tx-primary">Backoffice</a>. Designed by <a href="javascript:void(0)"> Land </a> All rights reserved
                </div>
            </div>
        </div>
        <!-- Footer closed -->

    </div>
    <!-- End Page -->

    <!-- JQuery min js -->
    <script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap js -->
    <script src="<?php echo base_url('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

    <!-- Perfect-scrollbar js -->
    <script src="<?php echo base_url('/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/perfect-scrollbar/p-scroll.js'); ?>"></script>

    <!-- Sidebar js -->
    <script src="<?php echo base_url('/assets/plugins/side-menu/sidemenu.js'); ?>"></script>

    <!-- Sticky js -->
    <script src="<?php echo base_url('/assets/js/sticky.js'); ?>"></script>

    <!-- Select2 js -->
    <script src="<?php echo base_url('/assets/plugins/select2/js/select2.full.min.js'); ?>"></script>

    <!-- Internal Select2.min js -->
    <script src="<?php echo base_url('/assets/plugins/select2/js/select2.min.js'); ?>"></script>

    <!-- Data tables -->
    <script src="<?php echo base_url('/assets/plugins/datatable/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/js/dataTables.bootstrap5.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/js/dataTables.buttons.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/js/buttons.bootstrap5.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/js/jszip.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/pdfmake/pdfmake.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/pdfmake/vfs_fonts.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/dataTables.responsive.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/responsive.bootstrap5.min.js'); ?>"></script>
    <script src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <!-- Flatpickr js -->
    <script src="<?php echo base_url('/assets/plugins/flatpickr/flatpickr.js'); ?>"></script>

    <!--Internal  jquery.maskedinput js -->
    <script src="<?php echo base_url('/assets/plugins/jquery.maskedinput/jquery.maskedinput.js'); ?>"></script>

    <!-- right-sidebar js -->
    <script src="<?php echo base_url('/assets/plugins/sidebar/sidebar.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/sidebar/sidebar-custom.js'); ?>"></script>

    <script src="https://amiryxe.github.io/easy-number-separator/easy-number-separator.js"></script>

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
    <!-- <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

    <!-- pusher -->
    <script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>

    <script src="<?php echo base_url('/assets/plugins/fileuploads/js/fileupload.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/fileuploads/js/file-upload.js'); ?>"></script>

    <!-- custom-switcher js -->
    <script src="<?php echo base_url('/assets/js/custom-switcher.js'); ?>"></script>

    <!-- custom js -->
    <script src="<?php echo base_url('/assets/js/custom.js'); ?>"></script>

    <!-- iziToast -->
    <script src="<?php echo base_url('/assets/app/js/izitoast/iziToast.min.js'); ?>" type="text/javascript"></script>

    <script src="<?php echo base_url('/assets/switcher/js/switcher.js'); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.all.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/1.9.3/countUp.min.js" integrity="sha512-fojFLrCKRmoGiEXroMMaF88NlzkQLbBGIQ0LwgmxDM6vGSh6fnm04ClpwheRDrLnY+gi/1CfOWV5+YqcPSSh7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="<?php echo base_url('/assets/app/js/app.js?v=' . time()); ?>"></script>
    <script src="<?php echo base_url('/assets/app/js/pusher.js?v=' . time()); ?>"></script>

    <script src="<?php echo base_url('/assets/plugins/notify/js/notifIt.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/jquery.maskedinput/jquery.maskedinput.js'); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?php echo base_url('/assets/plugins/jquery-steps/jquery.steps.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/parsleyjs/parsley.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/fancyuploder/jquery.ui.widget.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/fancyuploder/jquery.fileupload.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/fancyuploder/jquery.iframe-transport.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/fancyuploder/jquery.fancy-fileupload.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/fancyuploder/fancy-uploader.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/chart.js/Chart.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/js/image-uploader.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/app/js/loan/loan_payment.js?v=' . time()); ?>"></script>
</body>

</html>