<!-- main-content -->
<div class="main-content app-content">

    <!-- container -->
    <div class="main-container container-fluid">

        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="left-content">
                <span class="main-content-title tx-primary mg-b-0 mg-b-lg-1">รายงานรายรับ/รายจ่าย</span>
            </div>
            <div class="justify-content-center mt-2">
                <ol class="breadcrumb breadcrumb-style3">
                    <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">สินเชื่อ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">รายงานรายรับ/รายจ่าย</li>
                </ol>
            </div>
        </div>
        <!-- /breadcrumb -->

        <!-- Row -->
        <div class="row row-sm">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title mt-1">รายเดือน
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                    </div>
                                    <input type="text" class="float-end form-control flatpickr-input text-center" id="datepicker" name="datepicker" placeholder="เลือกปีที่ต้องการดูข้อมูล" readonly="readonly">
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane active" id="revenues"></div>
                </div>
            </div><!-- col-end -->
        </div>
        <!-- End Row -->
    </div>
    <!-- Container closed -->
</div>
<!-- main-content closed -->

<!-- ตารางยอดรายรับ(ค่าดำเนินการ) -->
<div class="modal fade" id="modalProcessMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">ตารางยอดรายรับค่าดำเนินการ (เดือน)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalProcessMonth"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_Process">
                            <thead>
                                <tr>
                                    <th style="width: 5px;">#</th>
                                    <th style="width: 15px;">เลขที่สินเชื่อ</th>
                                    <th style="width: 10px;">ผู้ทำรายการ</th>
                                    <th style="width: 40px;">ค่าดำเนินการ</th>
                                    <th style="width: 20px;">ค่าโอน</th>
                                    <th style="width: 20px;">ค่าใช้จ่ายอื่น ๆ</th>
                                    <th style="width: 20px;">จำนวนเงิน(รวม)</th>
                                    <th style="width: 15px;">วันที่ขอสินเชื่อ</th>
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
<!-- จบ ตารางยอดรายรับ(ค่าดำเนินการ) -->


<!-- ตารางยอดรายรับ(ใบสำคัญรับ) -->
<div class="modal fade" id="modalReceiptMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">ตารางยอดรายรับใบสำคัญรับ (เดือน)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalReceiptMonth"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_Receipt">
                            <thead>
                                <tr>
                                    <th style="width: 5px;">#</th>
                                    <th style="width: 15px;">เลขที่</th>
                                    <th style="width: 15px;">วันที่</th>
                                    <th style="width: 20px;">รายการ</th>
                                    <th style="width: 40px;">รายละเอียด</th>
                                    <th style="width: 20px;">ชื่อบัญชี</th>
                                    <th style="width: 20px;">จำนวนเงิน</th>
                                    <th style="width: 10px;">ผู้ทำรายการ</th>
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
<!-- จบ ตารางยอดรายรับ(ใบสำคัญรับ) -->

 <!-- ตารางยอดรายจ่าย -->
<div class="modal fade" id="modalExpensesMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">ตารางยอดรายจ่าย (เดือน)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalExpensesMonth"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_Expenses">
                            <thead>
                                <tr>
                                    <th style="width: 5px;">#</th>
                                    <th style="width: 15px;">เลขที่</th>
                                    <th style="width: 15px;">วันที่</th>
                                    <th style="width: 20px;">รายการ</th>
                                    <th style="width: 40px;">รายละเอียด</th>
                                    <th style="width: 20px;">ชื่อบัญชี</th>
                                    <th style="width: 20px;">จำนวนเงิน</th>
                                    <th style="width: 10px;">ผู้ทำรายการ</th>
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
<!-- จบ ตารางยอดรายจ่าย -->