<!-- main-content -->
<div class="main-content app-content">
    <!-- container -->
    <div class="main-container container-fluid">

        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="left-content">
                <span class="main-content-title mg-b-0 mg-b-lg-1"></span>
            </div>
            <div class="justify-content-center mt-2">
                <ol class="breadcrumb breadcrumb-style3">
                    <!-- <li class="breadcrumb-item tx-15">รายชื่อรถ</li> -->
                </ol>
            </div>
        </div>
        <!-- /breadcrumb -->

        <!-- row -->
        <div class="row">

            <!-- เมนูซ้ายมือ -->
            <div id="cardMainMenu" class="col-lg-12 col-xl-3">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom">
                            <ul class="nav nav-pills main-nav-column">
                                <li class="nav-item" id="detail_car_name"><a class="nav-link thumb active" data-bs-toggle="tab" href="#detail_loan"><i class="fe fe-home"></i> รายละเอียดสินเชื่อ</a></li>
                                <li class="nav-item" id="contract_loan"><a class="nav-link thumb pdf_loan" id='<?php echo $loanData->loan_code; ?>' data-bs-toggle="tab" href="#"><i class="fa fa-clipboard"></i> หนังสือสัญญากู้เงิน</a></li>
                                <li class="nav-item" id="table_loan"><a class="nav-link thumb pdf_installment_schedule" id='<?php echo $loanData->loan_code; ?>' data-bs-toggle="tab" href="#"><i class="far fa-newspaper"></i> ตารางการผ่อนชำระ</a></li>
                                <li class="nav-item" id="pay_loan"><a class="nav-link thumb" data-bs-toggle="tab" href="#payment_loan"><i class="fab fa-cc-stripe"></i> ชำระสินเชื่อ</a></li>
                                <li class="nav-item car_cancel_btn"><a class="nav-link thumb" id='<?php echo $loanData->loan_code; ?>' href="javascript:cancelLoan(this.id);"><i class="fa fa-trash"></i> ยกเลิกสินเชื่อ</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- เนื้อหาขวามือ -->
            <div id="cardDetailStockCar" class="col-lg-12 col-xl-9">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="detail_loan">
                                <div class="d-flex justify-content-between">
                                    <div class="panel tabs-style5 w-fit-content mx-auto">
                                        <div class="panel-head">
                                            <ul class="nav nav-tabs bg-white">
                                                <li class="nav-item tabBookingPaymentType"><a class="nav-link tx-14 font-weight-semibold tabBookingPaymentType2 active" data-bs-toggle="tab" href="javascript:void(0);" onclick="setingTab();">เช่าซื้อ</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <?php $FORM_KEY = 'FORM_KEY_' . strtotime('now') . '_' . rand(10, 100); ?>
                                <form method="POST" enctype="multipart/form-data" name="formUpdateLoan" id="<?php echo $FORM_KEY; ?>" data-form-key="<?php echo $FORM_KEY; ?>" novalidate>
                                    <div class="row">
                                        <p class="font-weight-semibold tx-17 pb-2 border-bottom-dashed tx-primary">ข้อมูลพื้นฐาน</p>
                                        <div class="col-6">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 tx-right">
                                                    <label class="form-label mt-0">ชื่อลูกค้า <span class="tx-danger">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-group openCustomerList">
                                                        <input name="customer_name" id="customer_name" class="form-control" type="text" required>
                                                        <input name="loan_code" id="loan_code" type="hidden">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 tx-right">
                                                    <label class="form-label mt-0" for="car_type_ocpb_update">เจ้าหน้าที่ <span class="tx-danger">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <input name="employee_name" id="employee_name" class="form-control" type="text" required>
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
                                                        <input class="form-control" type="text" name="loan_address" id="loan_address" required>
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
                                                        <input class="form-control" type="text" name="loan_number" id="loan_number" required>
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
                                                        <input class="form-control" type="text" name="loan_area" id="loan_area" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
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
                                        <div class="col-6">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 tx-right">
                                                    <label class="form-label mt-0">ยอดสินเชื่อ(ไม่รวม Vat) <span class="tx-danger">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-group mb-3">
                                                        <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" value="0" name="loan_without_vat" id="loan_without_vat" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==10) return false;" type="number" required readonly>
                                                        <span class="input-group-text" id="basic-addon2">บาท</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary mt-1">ข้อมูลการคำนวนรายการสินเชื่อ</p>
                                    <div class="row">
                                        <div class="col-6" id="car_name">
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
                                                        <input class="form-control" name="payment_year_counter" id="payment_year_counter" type="number" value="4" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==3) return false;" required readonly>
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
                                                    <label class="form-label mt-0">ดอกเบี้ย</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-group mb-3">
                                                        <input name="payment_interest" id="payment_interest" class="form-control" type="number" value="1" step="0.01" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==5) return false;" required readonly>
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
                                                        <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="total_loan" id="total_loan" type="text" readonly>
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
                                                            <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" id="charges_process" name="charges_process" type="text" value="0">
                                                            <span class="input-group-text" id="basic-addon2">บาท</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row align-items-center">
                                                    <div class="col-md-4 tx-right">
                                                        <label class="form-label mt-0">ยอดจ่ายจริง</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="input-group mb-3">
                                                            <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="really_pay_loan" id="really_pay_loan" type="text" readonly>
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
                                                            <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" id="charges_transfer" name="charges_transfer" type="text" value="0">
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
                                                            <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" id="charges_etc" name="charges_etc" type="text" value="0">
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
                                    <div align="right">
                                        <div class="form-group mb-2 mt-2" id="btn_edit_detail_">
                                            <button type="button" id="edit_loan_detail_btn" class="btn btn-primary mb-0 me-2" role="button">บันทึก</button>
                                        </div>
                                    </div>
                                </form>
                                <hr />
                                <form id="AddPicture" name="AddPicture" method="POST" enctype="multipart/form-data" novalidate>
                                    <div class="col-sm-12 col-md-12" style="text-align: center;">
                                        <label for="file_picture_other_update" class="form-label" style="font-weight: bold;">รูปอื่นๆ</label>
                                        <!-- <input id="file_picture_other_update" type="file" class="dropify" name="file_picture_other_update[]" accept="image/jpeg, image/png" data-height="200" multiple /> -->
                                        <div class="input-other-images">
                                        </div>
                                    </div>
                                    <p class="border-bottom-dashed tx-primary"></p>
                                    <div align="right">
                                        <div class="form-group mb-2 mt-2">
                                            <button type="submit" id="add_btn_picture" class="btn btn-primary mb-0 me-2" role="button">แก้ไขรูปภาพ</button>
                                        </div>
                                    </div>
                                    <p class="border-bottom-dashed tx-primary"></p>
                                </form>
                                <hr />
                                <div class="masonry row">
                                    <label style="font-weight: bold;">รูปอื่นๆ</label>
                                    <div class="row mb-4" id="other_picture"></div>
                                    <div class="col-sm-12 col-md-12" style="text-align: right;">
                                        <button type="button" class="btn btn-dark button-icon mx-2 button-icon" onclick="downloadOther();"><i class="fas fa-download me-2"></i>โหลดรูป</button>
                                    </div>
                                </div>
                                <!-- <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary mt-1"></p>
                                <div class="card-body">
                                    <h4 class="font-weight-semibold tx-25 pb-2  tx-primary">ตารางผ่อนต่องวด</h4>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class=" col-md-12">
                                                <div class="chartjs-wrapper-demo">
                                                    <canvas id="chartBarLoan"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="tab-pane" id="payment_loan">
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
        <div class="modal fade" id="modalPayLoan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <input type="hidden" name="carStockDetailBuySaleNoVat" id="carStockDetailBuySaleNoVat" value="" />
            <input type="hidden" name="carStockDetailBuySaleDow" id="carStockDetailBuySaleDow" value="" />
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
                                    <!-- <li class="nav-item PaymentLoanType"><a class="nav-link tx-14 font-weight-semibold PaymentLoanType2" data-bs-toggle="tab" href="javascript:void(0);" onclick="closeTab();">ชำระทั้งหมด</a></li> -->
                                    <li class="nav-item PaymentLoanType"><a class="nav-link tx-14 font-weight-semibold PaymentLoanType3" data-bs-toggle="tab" href="javascript:void(0);" onclick="closeLoanTab();">ปิดสินเชื่อ</a></li>
                                </ul>
                            </div>
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
                                <div class="col-6">
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
                            <div class="row">
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="collapse" id="bill_credit">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">หลักฐานการชำระ <span class="tx-danger">*</span></label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input class="form-control" type="file" id="file_payment" name="file_payment" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                <div class="collapse" id="pay_close_loan_tab">
                                    <div class="row">
                                        <div class="col-6" id="car_name">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 tx-right">
                                                    <label class="form-label mt-0">ยอดชำระปิดสินเชื่อ</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-group mb-3">
                                                        <input aria-describedby="basic-addon2" aria-label="" class="form-control" placeholder="" name="close_loan_payment" id="close_loan_payment" type="text" readonly>
                                                        <span class="input-group-text" id="basic-addon2">บาท</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 tx-right">
                                                    <label class="form-label mt-0" for="car_name_update">ยอดเปิดสินเชื่อ</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-group mb-3">
                                                        <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="open_loan_payment" id="open_loan_payment" type="text" value="" readonly>
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