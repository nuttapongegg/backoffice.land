<!-- main-content -->
<div class="main-content app-content">
<style>
    .row {
    --bs-gutter-x: 0.5rem;
    --bs-gutter-y: 0;
    margin-top: calc(-1 * var(--bs-gutter-y));
    margin-right: calc(-.5 * var(--bs-gutter-x));
    margin-left: calc(-.5 * var(--bs-gutter-x));
}

.card {
    margin-block-end: 0.6rem;
}
</style>
    <!-- container -->
    <div class="main-container container-fluid">

        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="left-content">
                <span class="main-content-title tx-primary mg-b-0 mg-b-lg-1">รายงานสินเชื่อ</span>
            </div>
            <div class="justify-content-center mt-2">
                <ol class="breadcrumb breadcrumb-style3">
                    <li class="breadcrumb-item tx-15"><a href="javascript:void(0)">สินเชื่อ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">รายงานสินเชื่อ</li>
                </ol>
            </div>
        </div>
        <!-- /breadcrumb -->

        <div class="row">
            <div class="col-xxl-12 col-xl-12">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <button type="button" class="btn btn-outline-primary mb-3 float-end btnEditTargetedMonth" data-bs-toggle="modal" data-bs-target="#modalEditTargetedMonth"> <i class="fa-solid fa-plus"></i>เป้าหมาย</button>
                                                <div align="center" class="ms-5">
                                                    <div class="rounded-circle ht-60 wd-60 bg-light bg-light d-flex align-items-center justify-content-center ms-5">
                                                        <div class="ht-50 wd-50 rounded-circle bg-primary d-flex align-items-center justify-content-center"> <i class="ti-money tx-17 text-white"></i> </div>
                                                    </div>
                                                </div>
                                                <h4 class="tx-18 font-weight-semibold my-1">กำไร 0.00</h4>
                                                <h4 class="tx-18 font-weight-semibold my-1">(การขายเดือนปัจจุบัน)</h4>
                                                <p class="tx-14 mb-1"><span class="">0%</span> of target</p>
                                                <p class="tx-13 text-start" style="margin-bottom: 8px;">เป้าหมาย ( 0 / 0)</p>
                                                <div class="progress progress-style ht-5 mt-2">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="78" style="width:0%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <button type="button" class="btn btn-outline-primary mb-3 float-end btnEditTargeted" data-bs-toggle="modal" data-bs-target="#modalEditTargeted"> <i class="fa-solid fa-plus"></i>เป้าหมาย</button>
                                                <div align="center" class="ms-5">
                                                    <div class="rounded-circle ht-60 wd-60 bg-light bg-light d-flex align-items-center justify-content-center ms-5">
                                                        <div class="ht-50 wd-50 rounded-circle bg-primary d-flex align-items-center justify-content-center"> <i class="fas fa-hand-holding-usd tx-17 text-white"></i> </div>
                                                    </div>
                                                </div>
                                                <h4 class="tx-18 font-weight-semibold my-1">กำไร 75.00</h4>
                                                <h4 class="tx-18 font-weight-semibold my-1">(การขายปีปัจจุบัน)</h4>
                                                <p class="tx-14 mb-1"><span class="bg-success-transparent tx-success">75%</span> of target</p>
                                                <p class="tx-13 text-start" style="margin-bottom: 8px;">เป้าหมาย (75 / 100)</p>
                                                <div class="progress progress-style ht-5 mt-2">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="78" style="width:75%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-9">
                                        <div class="card-title mt-1">รายเดือน
                                            <!-- <input type="text" class="float-end" name="datepickers" id="datepickers"  value="2022"/> -->
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                            <input type="text" class="float-end form-control flatpickr-input text-center" id="datepicker" name="datepicker" placeholder="เลือกปีที่ต้องการดูข้อมูล" readonly="readonly">
                                            <!-- <button type="submit" class="btn btn-primary btnyear" role="button">ยืนยัน</button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane active" id="report_loan"></div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            กราฟแสดงผลกำไรต่อเดือน
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="sessionsDevice" class="my-3"></div>
                                        <div id="graphloan"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-12 col-xl-12">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title mt-2 mb-2">
                                    สรุปกำไร
                                </div>
                            </div>
                            <div class="card-body mt-2 mb-3" id="summarizeloan">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /Container -->

</div>
<!-- /main-content -->
<!-- <div align="center">
    <div class="modal fade" id="modalEditTargetedMonth" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row col-xl-12">
                        <h7 class="modal-title">ตั้งค่าเป้าหมายต่อเดือน</h7>
                    </div>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="updateTargetedMonth" name="updateTargetedMonth" action="#">
                        <input type="hidden" name="TargetedId" id="TargetedId" value="<php echo $targeteds->id ?>" />

                        <div class="form-group">
                            <div align="left">
                                <label for="editTargeted" class="tx-15">เป้าหมาย</label>
                            </div>
                            <input type="text" class="form-control" id="editTargetedMonth" name="editTargetedMonth" placeholder="เป้าหมาย" value="<php echo $targeteds->desired_goals_month ?>">
                        </div>
                        <button type="submit" class="btn btn-primary btnSaveTargetedMonth" role="button">ยืนยัน</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- End Edit EditTargetedMonth -->
<!-- <div align="center">
    <div class="modal fade" id="modalEditTargeted" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row col-xl-12">
                        <h7 class="modal-title">ตั้งค่าเป้าหมาย</h7>
                    </div>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="updateTargeted" name="updateTargeted" action="#">
                        <input type="hidden" name="TargetedId" id="TargetedId" value="<php echo $targeteds->id ?>" />

                        <div class="form-group">
                            <div align="left">
                                <label for="editTargeted" class="tx-15">เป้าหมาย</label>
                            </div>
                            <input type="text" class="form-control" id="editTargeted" name="editTargeted" placeholder="เป้าหมาย" value="<php echo $targeteds->desired_goal ?>">
                        </div>
                        <button type="submit" class="btn btn-primary btnSave" role="button">ยืนยัน</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- End Edit Finance -->

<!-- ตารางยอดเปิดสินเชื่อ -->
<div class="modal fade" id="modalLoanMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="card-title">ตารางยอดเปิดสินเชื่อ (เดือน)</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalLoanMonth"><span aria-hidden="true">&times;</span></button>

            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_Loan">
                            <thead>
                                <tr>
                                    <th style="width: 22px;">#</th>
                                    <th style="width: 50px;">เลขที่สินเชื่อ</th>
                                    <th style="width: 100px;">ชื่อลูกค้า</th>
                                    <th style="width: 100px;">ชื่อพนักงาน</th>
                                    <th style="width: 30px;">สาขา</th>
                                    <th style="width: 50px;">บัญชีสินเชื่อ</th>
                                    <th style="width: 70px;">ยอดสินเชื่อ</th>
                                    <th style="width: 90px;">วันที่ขอสินเชื่อ</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                        <div style="display: flex; justify-content: center;">
                            <button type="button" class="btn btn-primary " data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- จบ ตารางยอดเปิดสินเชื่อ -->

<!-- ตารางยอดรับชำระ -->
<div class="modal fade" id="modalPaymentMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">ตารางยอดรับชำระ (เดือน)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalPaymentMonth"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_Payment">
                            <thead>
                                <tr>
                                    <th style="width: 22px;">#</th>
                                    <th style="width: 50px;">เลขที่สินเชื่อ</th>
                                    <th style="width: 100px;">ผู้ชำระ</th>
                                    <th style="width: 100px;">ผู้รับชำระ</th>
                                    <th style="width: 30px;">งวด</th>
                                    <th style="width: 50px;">บัญชีสินเชื่อ</th>
                                    <th style="width: 50px;">ช่องทางการชำระ</th>
                                    <th style="width: 70px;">ยอดชำระ</th>
                                    <th style="width: 90px;">วันที่ชำระ</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <div style="display: flex; justify-content: center;">
                            <button type="button" class="btn btn-primary " data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- จบ ตารางยอดรับชำระ -->