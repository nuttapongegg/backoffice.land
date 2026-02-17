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

        .text-ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .wd-180 {
            max-width: 180px;
            /* กำหนดความกว้างของคอลัมน์แรก */
        }
    </style>
    <!-- container -->
    <div class="main-container container-fluid">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between d-flex">
        </div>
        <!-- /breadcrumb -->
        <div>
            <div class="col-xxl-12 col-xl-12" id="summarizeOwnerLoan">
            </div>
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="card-title justify-content-between d-flex">
                            <div>
                                <div id="count_owner_loan"></div>
                            </div>
                            <div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary owner_Loan_open text-center me-2" data-bs-toggle="modal" data-bs-target="#modalAddOwnerLoan"><i class="fa-solid fa-plus text-center"></i>&nbsp;&nbsp;เพิ่มเงินยืมจากเจ้าของ</a>
                                <a href="javascript:void(0);" class="btn btn-outline-primary modal_Interest_Rate text-center">ตั้งค่าดอกเบี้ย</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-wrap">
                            <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
                            </div>
                            <div class="panel tabs-style1">
                                <div class="panel-body">
                                    <div class="row justify-content-end">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="daterange_owner_loan" placeholder="เริ่มหาวันที่ ถึง วันที่ (กรณีว่าง จะดึงข้อมูลทั้งหมด)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive double-scroll mt-4">
                                        <table class="table table-bordered text-nowrap border-bottom" id="tableOwnerLoanOn">
                                            <thead>
                                                <tr>
                                                    <th class="wd-5p">#</th>
                                                    <th class="wd-10p text-center">เลขที่รายการ</th>
                                                    <th class="wd-10p text-center">วันที่ยืม</th>
                                                    <th class="wd-10p text-center">จำนวนเงิน</th>

                                                    <th class="wd-10p text-center">จ่ายแล้ว(รวม)</th>
                                                    <th class="wd-10p text-center">ตัดต้นแล้ว</th>
                                                    <th class="wd-10p text-center">จ่ายดอกแล้ว</th>

                                                    <th class="wd-10p text-center">คงเหลือต้น</th>
                                                    <th class="wd-10p text-center">ดอกถึงวันนี้</th>

                                                    <th class="wd-10p text-center">% ดอก</th>
                                                    <th class="wd-10p text-center">วัน</th>
                                                    <th class="wd-10p text-center">ดอก/วัน</th>

                                                    <th class="wd-10p text-center">ยอดปิดวันนี้</th>

                                                    <th class="wd-10p text-center">วันยืมมาแล้ว</th>
                                                    <th class="wd-10p text-center">จ่ายล่าสุด</th>
                                                    <th class="wd-10p text-center">% ตัดต้น</th>

                                                    <th class="wd-10p text-center">บัญชีที่รับเงิน</th>
                                                    <th class="wd-10p text-center">ผู้ทำรายการ</th>
                                                    <!-- <th class="wd-10p text-center">สถานะ</th> -->
                                                    <th class="wd-60p text-center">รายละเอียด</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>

                                            <tfoot>
                                                <tr class="tx-black bg-primary">
                                                    <th colspan="3" style="padding: 12px;">
                                                        <h6 class="tx-left mt-2"><b>รวม</b></h6>
                                                    </th>

                                                    <!-- amount -->
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>

                                                    <!-- paid_total / paid_principal / paid_interest -->
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>

                                                    <!-- outstanding / interest_due_today -->
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>

                                                    <th colspan="2" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                    
                                                    <!--interest_per_day / total_due_today -->
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>

                                                    <!-- rest -->
                                                    <th colspan="6" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                </tr>
                                            </tfoot>
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
                            <div class="panel tabs-style1">
                                <div class="panel-body">

                                    <div class="row justify-content-end">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="daterange_owner_loan_close"
                                                        placeholder="เริ่มหาวันที่ ถึง วันที่ (กรณีว่าง จะดึงข้อมูลทั้งหมด)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive double-scroll">
                                        <table class="table table-bordered text-nowrap border-bottom" id="tableOwnerLoanClose">
                                            <thead>
                                                <tr>
                                                    <th class="wd-5p">#</th>
                                                    <th class="wd-12p text-center">เลขที่รายการ</th>
                                                    <th class="wd-10p text-center">วันที่ยืม</th>
                                                    <th class="wd-10p text-center">วันที่ปิด</th>

                                                    <th class="wd-12p text-center">วงเงิน</th>
                                                    <th class="wd-12p text-center">จ่ายแล้ว (รวม)</th>
                                                    <th class="wd-12p text-center">ตัดต้น</th>
                                                    <th class="wd-12p text-center">จ่ายดอก</th>

                                                    <th class="wd-10p text-center">ชำระล่าสุด</th>
                                                    <th class="wd-8p text-center">วันยืม</th>

                                                    <th class="wd-15p text-center">บัญชีรับเงิน</th>
                                                    <th class="wd-12p text-center">ผู้ทำรายการ</th>
                                                    <th class="wd-30p text-center">รายละเอียด</th>
                                                </tr>
                                            </thead>

                                            <tbody></tbody>

                                            <tfoot>
                                                <tr class="tx-black bg-primary">
                                                    <th colspan="4" style="padding: 12px;">
                                                        <h6 class="tx-left mt-2"><b>รวม (เฉพาะหน้าปัจจุบัน)</b></h6>
                                                    </th>

                                                    <!-- amount -->
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>

                                                    <!-- paid_total -->
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>

                                                    <!-- paid_principal_total -->
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>

                                                    <!-- paid_interest_total -->
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>

                                                    <th colspan="5" style="padding: 12px;"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <div id="SummarizeOwnerLoan" class="card mt-3">
            </div>
        </div>

        <div class="modal fade" id="modalAddOwnerLoan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">เพิ่มเงินยืมจากเจ้าของ</h5>
                        <button type="button" class="btn-close modalAddOwnerLoanClose" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <?php $FORM_KEY = 'FORM_KEY_' . strtotime('now') . '_' . rand(10, 100); ?>
                        <form method="POST" name="formAddOwnerLoan" id="<?php echo $FORM_KEY; ?>" data-form-key="<?php echo $FORM_KEY; ?>" novalidate>

                            <!-- status fix -->
                            <input type="hidden" name="status" value="OPEN">

                            <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary">ข้อมูลรายการยืม</p>

                            <!-- วันที่ยืม -->
                            <div class="row mb-3">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">วันที่ยืม <span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                </div>
                                                <input type="text" class="form-control dateToBooking" name="owner_loan_date" id="owner_loan_date"
                                                    placeholder="เลือกวันที่" value="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- จำนวนเงิน + โอนเข้าบัญชี -->
                            <div class="row mb-1">
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0 mb-3">โอนเข้าบัญชี <span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <select name="land_account_id" id="owner_loan_land_account_id"
                                                    class="form-control custom-select" data-bs-placeholder="Select ..." required>
                                                    <?php if ($land_accounts) : ?>
                                                        <?php foreach ($land_accounts as $land_account) { ?>
                                                            <option value="<?php echo $land_account->id; ?>">
                                                                <?php echo $land_account->land_account_name; ?>
                                                            </option>
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
                                            <label class="form-label mt-2">จำนวนเงิน <span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input class="form-control price_amount" name="amount" id="owner_loan_amount" type="text" value="0" required>
                                                <span class="input-group-text">บาท</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- รายละเอียด -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 tx-right">
                                            <label class="form-label mt-0">รายละเอียด</label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <input class="form-control" placeholder="รายละเอียด..." id="owner_loan_note" name="note">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary">แนบไฟล์</p>
                                <div class="col-sm-12 col-md-12">
                                    <input type="file" class="ownerLoanFile" name="owner_loan_file" data-height="200" />
                                </div>
                            </div>
                            <hr>

                            <div style="display:flex; justify-content:center;">
                                <button class="btn btn-primary btn-block btn-add-owner-loan" type="button">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- main-content closed -->
</div>

<!-- modal modal_Interest_Rate -->
<div class="modal fade" id="modal_Interest_Rate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">ตั้งค่าอัตราดอกเบี้ย</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="card-body">
                <form method="post" id="form_Setting_Interest_Rate" name="form_Setting_Interest_Rate" action="#">
                    <input type="hidden" name="OwnerSettingId" id="OwnerSettingId" />
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <label for="interest_Rate" class="form-label">
                                ตั้งค่าอัตราดอกเบี้ย (% ต่อปี)
                            </label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input type="number"
                                    class="form-control text-end"
                                    id="interest_Rate"
                                    name="interest_Rate"
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00">
                                <span class="input-group-text">% ต่อปี</span>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div style="display:flex; justify-content:center;">
                        <button class="btn btn-primary btn-block btnEditSettingInterestRate" type="button">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>