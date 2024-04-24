<!-- main-content -->
<div class="main-content app-content">

    <!-- container -->
    <div class="main-container container-fluid">

        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="left-content">
                <span class="main-content-title tx-primary mg-b-0 mg-b-lg-1">ตั้งค่า</span>
            </div>
            <div class="justify-content-center mt-2">
                <ol class="breadcrumb breadcrumb-style3">
                    <li class="breadcrumb-item active tx-15" aria-current="page">ตั้งค่า</li>
                </ol>
            </div>
        </div>
        <!-- /breadcrumb -->

        <!-- row -->
        <div class="row">
            <div class="card-header">
                <div class="col-xxl-12">
                    <div class="row">
                        <div class="col-xl-4">
                            <a href="<?php echo base_url('/setting_land/land'); ?>">
                                <div class="card text-center nav-link">
                                    <div class="card-body">
                                        <div class="feature widget-2 text-center mt-0 mb-3">
                                            <i class="bi bi-cash-stack project bg-info-transparent mx-auto text-info "></i>
                                        </div>
                                        <h6 class="mb-2 tx-muted">เพิ่ม/แก้ไข/ลบ บัญชี</h6>
                                        <h3 class="font-weight-semibold">ตั้งค่าบัญชี</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4">
                            <a class="setting_Overdue_Status" href="#">
                                <div class="card text-center nav-link">
                                    <div class="card-body">
                                        <div class="feature widget-2 text-center mt-0 mb-3">
                                            <i class="icon ion-md-alarm  project bg-orange-transparent mx-auto text-orange"></i>
                                        </div>
                                        <h6 class="mb-2 tx-muted">กำหนดระยะเวลาแจ้งเตือน สินเชื่อ</h6>
                                        <h3 class="font-weight-semibold">ตั้งค่าแจ้งเตือนสินเชื่อ</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /row -->
    </div>
</div>
<!-- main-content closed -->

<!-- modal setting_Overdue_Status -->
<div class="modal fade" id="setting_Overdue_Status" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">ตั้งค่าแจ้งเตือนสินเชื่อ</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="card-body">
                <form method="post" id="form_Setting_Overdue_Status" name="form_Setting_Overdue_Status" action="#">
                    <div class="form-group mb-0">
                        <div class="row row-sm">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-7 col-md-12">
                                        <label for="overdue_Loan" class="form-label">ระยะเวลาแจ้งเตือนสินเชื่อเกินกำหนดชำระ</label>
                                    </div>
                                    <div class="col-xl-4 col-md-12"> <input type="text" class="form-control text-center" id="overdue_Loan" name="overdue_Loan" placeholder="วัน">
                                    </div>
                                    <div class="col-xl-1 col-md-12" style="margin-top: 8px;"> <a>วัน</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 mb-1">
                                        <label for="token_Loan" class="form-label">Line Token แจ้งเตือนสถานะสินเชื่อเกินกำหนด &nbsp;&nbsp;<a href="https://www.smith.in.th/สร้าง-line-notify-สำหรับ-post-ลงกลุ่ม/" target="_blank"><i>(ดูวิธีได้รับ)</i></a></label>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: -25px;">
                                    <div class="col-xl-11 col-md-12"> <input type="text" class="form-control" id="token_Loan" name="token_Loan" placeholder="Line Token"></div>
                                    <div class="col-xl-1 col-md-12 mt-2">
                                        <label class="custom-control custom-checkbox custom-control-md" style="margin-left:-5px;">
                                            <input type="checkbox" class="custom-control-input" name="checkbox_Token_Loan" id="checkbox_Token_Loan">
                                            <span class="custom-control-label custom-control-label-md"> </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div style="display: flex; justify-content: center;">
                        <button type="submit" class="btn btn-primary btn-block btnEditSettingOverdueStatus" role="button">ยืนยัน</button>
                    </div>
                </form>
                <hr>
            </div>
        </div>
    </div>
</div>