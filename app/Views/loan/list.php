<input type="hidden" value="<?php echo session()->get('employee_position_name'); ?>" name="employee_position_name">
<!-- main-content -->
<div class="main-content app-content">
    <style>
        .dataTables_scrollBody {
            transform: rotateX(180deg);
        }

        .dataTables_scrollBody table {
            transform: rotateX(180deg);
        }

        .text-right {
            text-align: right;
        }

        .card {
            margin-block-end: 0rem;
        }
    </style>
    <!-- container -->
    <div class="main-container container-fluid">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between d-flex">
        </div>
        <!-- /breadcrumb -->
        <div>
            <div class="col-xxl-12 col-xl-12" id="summarizeLoan">
            </div>
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="card-title justify-content-between d-flex">
                            <div>
                                <div id="count_car"></div>
                                <!-- <div id="count_loan_on" style="color: #FF8800;">สินเชื่อ 0 ราย</div> -->
                            </div>
                            <div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary Loan_open text-center" data-bs-toggle="modal" data-bs-target="#modalAddLoan"><i class="fa-solid fa-plus text-center" id="addStockCar" name="addStockCar"></i>&nbsp;&nbsp;เพิ่มสินเชื่อ</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-wrap">
                            <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
                            </div>
                            <div class="panel tabs-style1">
                                <div class="panel-body">
                                    <div class="table-responsive double-scroll">
                                        <table class="table table-bordered text-nowrap border-bottom" id="tableLoanOn">
                                            <thead>
                                                <tr>
                                                    <th class="wd-5p">#</th>
                                                    <th class="wd-15p text-center">เลขที่สินเชื่อ</th>
                                                    <th class="wd-40p text-center">ชื่อลูกค้า</th>
                                                    <th class="wd-20p text-center">ชื่อสถานที่</th>
                                                    <th class="wd-20p text-center">เนื้อที่</th>
                                                    <th class="wd-20p text-center">เลขที่ดิน</th>
                                                    <th class="wd-20p text-center">วันที่ขอสินเชื่อ</th>
                                                    <th class="wd-20p text-center">ประเภทสินเชื่อ</th>
                                                    <th class="wd-40p text-center">วงเงิน</th>
                                                    <th class="wd-30p text-center">ระยะเวลาการผ่อน</th>
                                                    <th class="wd-20p text-center">ชำระทุกวันที</th>
                                                    <th class="wd-30p text-center">สถานะ</th>
                                                    <th class="wd-30p text-center">เกินกำหนดชำระ</th>
                                                    <th class="wd-30p text-center">ดอกเบี้ย</th>
                                                    <th class="wd-30p text-center">จำนวนงวด</th>
                                                    <th class="wd-30p text-center">ชำระแล้ว</th>
                                                    <th class="wd-30p text-center">งวดละ</th>
                                                    <th class="wd-30p text-center">ชำระแล้วเป็นเงิน</th>
                                                    <th class="wd-30p text-center">เงินที่ต้องชำระคงเหลือ</th>
                                                    <th class="wd-30p text-center">รายละเอียด</th>
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
                <!-- End Row -->
            </div>
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="card-title justify-content-between d-flex">
                            <div>
                                <div id="count_car_history"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-wrap">
                            <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
                            </div>
                            <div class="panel tabs-style1">
                                <div class="panel-body">
                                    <div class="table-responsive double-scroll">
                                        <table class="table table-bordered text-nowrap border-bottom" id="tableLoanClose">
                                            <thead>
                                                <tr>
                                                    <th class="wd-5p">#</th>
                                                    <th class="wd-15p text-center">เลขที่สินเชื่อ</th>
                                                    <th class="wd-40p text-center">ชื่อลูกค้า</th>
                                                    <th class="wd-20p text-center">ชื่อสถานที่</th>
                                                    <th class="wd-20p text-center">เนื้อที่</th>
                                                    <th class="wd-20p text-center">เลขที่ดิน</th>
                                                    <th class="wd-20p text-center">วันที่ขอสินเชื่อ</th>
                                                    <th class="wd-20p text-center">ประเภทสินเชื่อ</th>
                                                    <th class="wd-40p text-center">วงเงิน</th>
                                                    <th class="wd-30p text-center">ระยะเวลาการผ่อน</th>
                                                    <th class="wd-30p text-center">สถานะ</th>
                                                    <th class="wd-20p text-center">วันเริ่มชำระ</th>
                                                    <th class="wd-30p text-center">ดอกเบี้ย</th>
                                                    <th class="wd-30p text-center">จำนวนงวด</th>
                                                    <th class="wd-30p text-center">ชำระแล้ว</th>
                                                    <th class="wd-30p text-center">งวดละ</th>
                                                    <th class="wd-30p text-center">ชำระแล้วเป็นเงิน</th>
                                                    <th class="wd-30p text-center">เงินที่ต้องชำระคงเหลือ</th>
                                                    <th class="wd-30p text-center">รายละเอียด</th>
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
                <!-- End Row -->
            </div>
            <div id="SummarizeLoan" class="card mt-3">
            </div>
        </div>


        <div class="modal fade" id="modalAddLoan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <input type="hidden" name="carStockDetailBuySaleNoVat" id="carStockDetailBuySaleNoVat" value="" />
            <input type="hidden" name="carStockDetailBuySaleDow" id="carStockDetailBuySaleDow" value="" />
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">เปิดสินเชื่อ</h5>
                        <button type="button" class="btn-close modalAddLoanClose"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="panel tabs-style5 w-fit-content mx-auto">
                            <div class="panel-head">
                                <ul class="nav nav-tabs bg-white">
                                    <li class="nav-item tabBookingPaymentType"><a class="nav-link tx-14 font-weight-semibold tabBookingPaymentType2 active" data-bs-toggle="tab" href="javascript:void(0);" onclick="setingTab();">เช่าซื้อ</a></li>
                                </ul>
                            </div>
                        </div>
                        <?php $FORM_KEY = 'FORM_KEY_' . strtotime('now') . '_' . rand(10, 100); ?>
                        <form method="POST" enctype="multipart/form-data" name="formAddLoan" id="<?php echo $FORM_KEY; ?>" data-form-key="<?php echo $FORM_KEY; ?>" novalidate>
                            <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary mt-5">ข้อมูลพื้นฐาน</p>
                            <div class="row mb-3">
                                <div class="col-6">
                                </div>
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">วันที่ออกสินเชื่อ</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                </div>
                                                <input type="text" class="form-control dateToBooking" name="date_to_loan" id="date_to_loan" placeholder="เลือกวันที่" value="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">

                                </div>
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">กำหนดชำระสินเชื่อ</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                </div>
                                                <input type="text" class="form-control dateToBooking" name="date_to_loan_pay_date" id="date_to_loan_pay_date" placeholder="เลือกวันที่" value="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">ชื่อลูกค้า <span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input name="customer_name" id="customer_name" class="form-control" type="text" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">เจ้าหน้าที่ <span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="employee_name" id="employee_name" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 tx-right">
                                            <label class="form-label mt-0">ชื่อสถานที่</label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="loan_address" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">เลขที่ดิน<span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="loan_number" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">เนื้อที่</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="loan_area" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0" for="account_id">บัญชีสินเชื่อ<span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <select name="account_id" id='account_id' class="form-control custom-select" data-bs-placeholder="Select ..." required>
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
                                            <label class="form-label mt-0">ยอดสินเชื่อ(ไม่รวม Vat) <span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group mb-3">
                                                <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" value="0" name="loan_without_vat" id="loan_without_vat" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==10) return false;" type="number" required>
                                                <span class="input-group-text" id="basic-addon2">บาท</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="bookingWrapperFormPaymentType">
                                <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary mt-5">ข้อมูลการคำนวนรายการสินเชื่อ</p>
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ยอดสินเชื่อ</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="money_loan" id="money_loan" type="text" readonly>
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">จำนวนปี</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input class="form-control" name="payment_year_counter" id="payment_year_counter" type="number" value="4" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==3) return false;" required>
                                                    <span class="input-group-text" id="basic-addon2">ปี</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ยอดดอกเบี้ยรวม</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="total_loan_interest" id="total_loan_interest" type="text" value="" readonly>
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ดอกเบี้ย/ปี</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input name="payment_interest" id="payment_interest" class="form-control" type="flot" value="1" step="0.01" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==5) return false;" required>
                                                    <span class=" input-group-text" id="basic-addon2">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0" for="car_name_update">ยอดสินเชื่อรวม</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="total_loan" id="total_loan" type="text" value="" readonly>
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">งวดละ</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="pricePerMonth" id="pricePerMonth" type="text" readonly>
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6"></div>
                                </div>
                            </div>
                            <div id="other_cash">
                                <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary mt-5">ค่าใช้จ่ายอื่น ๆ</p>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ค่าดำเนินการ</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="charges_process" type="text" value="0">
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ค่าโอน</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="charges_transfer" type="text" value="0">
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ค่าใช้จ่ายอื่น ๆ</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="charges_etc" type="text" value="0">
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row align-items-center">
                                            <div class="col-md-2 tx-right">
                                                <label class="form-label mt-0" for="remark">หมายเหตุ</label>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="หมายเหตุ..." id="remark" name="remark"></ร>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div style="display: flex; justify-content: center;">
                                <button class="btn btn-primary btn-block btn-add-loan" type="button">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- main-content closed -->
</div>