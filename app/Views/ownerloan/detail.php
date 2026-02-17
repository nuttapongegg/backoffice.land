<!-- main-content -->
<div class="main-content app-content">
    <div class="main-container container-fluid">

        <div class="breadcrumb-header justify-content-between mb-3">
            <h4 class="tx-primary mb-0">รายละเอียดการยืมเงิน</h4>
        </div>

        <div class="row">

            <!-- ซ้าย -->
            <div class="col-lg-12 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="p-3 border-bottom">
                            <ul class="nav nav-pills main-nav-column">
                                <li class="nav-item">
                                    <a class="nav-link thumb active" href="#detail_loan">
                                        <i class="fe fe-home me-2"></i> รายละเอียดการยืม
                                    </a>
                                </li>

                                <li class="nav-item mt-2">
                                    <a class="nav-link thumb"
                                        href="javascript:void(0);"
                                        onclick="cancelOwnerLoan('<?= esc($ownerLoanData->owner_code) ?>')">
                                        <i class="fa fa-trash me-2"></i> ยกเลิกรายการ
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- ✅ แนะนำ UX: แถบข้อมูลเล็กๆ -->
                        <div class="text-muted small mt-3">
                            สร้างโดย: <?= esc($ownerLoanData->username ?? '-') ?><br>
                            อัปเดตล่าสุด: <?= esc($ownerLoanData->updated_at ?? $ownerLoanData->created_at ?? '-') ?>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ขวา -->
            <div class="col-lg-12 col-xl-9" id="detail_loan">

                <!-- ข้อมูลการยืม -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="tx-primary mb-0">ข้อมูลการยืม</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- ⭐ Interest Rate -->
                                    <span class="badge bg-primary modal_Edit_Owner_Interest px-3 py-2" data-id="<?= $ownerLoanData->id ?>" data-rate="<?= $ownerLoanData->interest_rate ?? $owner_setting->default_interest_rate ?>" style="cursor:pointer;">
                                        <?= number_format($ownerLoanData->interest_rate ?? $owner_setting->default_interest_rate, 2) ?>% ต่อปี
                                    </span>
                                    <!-- STATUS -->
                                    <span id="loanStatusBadge"
                                        class="badge px-3 py-2
                                        <?= $ownerLoanData->status === 'OPEN' ? 'bg-success'
                                            : (in_array($ownerLoanData->status, ['CANCELLED', 'CANCEL']) ? 'bg-danger' : 'bg-secondary') ?>">
                                        <?= esc($ownerLoanData->status) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>เลขที่รายการ</label>
                                    <input class="form-control" readonly value="<?= esc($ownerLoanData->owner_code) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>วันที่ยืม</label>
                                    <input class="form-control" readonly value="<?= esc($ownerLoanData->owner_loan_date) ?>">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>บัญชีรับโอน</label>
                                    <input class="form-control" readonly value="<?= esc($ownerLoanData->land_account_name ?? '-') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>จำนวนเงิน</label>
                                    <input class="form-control text-end" readonly value="<?= number_format((float)$ownerLoanData->amount, 2) ?> บาท">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <label>หมายเหตุ</label>
                                    <textarea class="form-control" rows="2" readonly><?= esc($ownerLoanData->note ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- เอกสารแนบ -->
                    <?php if (!empty($ownerLoanData->owner_loan_file)): ?>
                        <?php
                        $file = $ownerLoanData->owner_loan_file;
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                        $isPdf = ($ext === 'pdf');
                        ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="tx-primary mb-3">เอกสารแนบ</h5>

                                <?php if ($isImage): ?>
                                    <div class="text-center">
                                        <img src="<?= getenv('CDN_IMG') . '/uploads/file_owner_loan/' . $file ?>" class="img-fluid rounded" style="max-height:350px">
                                        <div class="mt-2">
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="<?= getenv('CDN_IMG') . '/uploads/file_owner_loan/' . $file ?>">
                                                เปิดรูปเต็ม
                                            </a>
                                        </div>
                                    </div>
                                <?php elseif ($isPdf): ?>
                                    <a class="btn btn-outline-primary" target="_blank" href="<?= getenv('CDN_IMG') . '/uploads/file_owner_loan/' . $file ?>">
                                        <i class="fa fa-file-pdf me-2"></i> เปิดไฟล์ PDF
                                    </a>
                                <?php else: ?>
                                    <a class="btn btn-outline-primary" target="_blank" href="<?= getenv('CDN_IMG') . '/uploads/file_owner_loan/' . $file ?>">
                                        <i class="fa fa-paperclip me-2"></i> ดาวน์โหลดไฟล์แนบ
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- ตารางผ่อน (โหลดผ่าน JS) -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="tx-primary mb-0">ประวัติการชำระ</h5>

                                <div class="d-flex align-items-center gap-2">
                                    <div class="text-end small">
                                        ทั้งหมด: <b id="loanAmount">0.00</b> |
                                        ชำระแล้ว: <b id="sumPaid">0.00</b> |
                                        คงเหลือ: <b id="sumRemain">0.00</b> |
                                        ล่าสุด: <b id="lastPayDate">-</b>
                                    </div>


                                    <?php $isClosed = in_array($ownerLoanData->status, ['CLOSED', 'PAID', 'CANCELLED', 'CANCEL']); ?>

                                    <button class="btn btn-outline-primary"
                                        type="button"
                                        id="btnOpenPayModal"
                                        <?= $isClosed ? 'disabled' : '' ?>>
                                        <i class="fa fa-plus me-1"></i> เพิ่มการชำระ
                                    </button>

                                    <div id="payDisabledHint"
                                        class="small text-danger ms-2"
                                        style="<?= $isClosed ? '' : 'display:none;' ?>">
                                        รายการนี้ปิดแล้ว ไม่สามารถเพิ่มการชำระได้
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap">
                                    <thead class="text-center">
                                        <tr>
                                            <th style="width:60px;">#</th>
                                            <th>วันที่ชำระ</th>
                                            <th>ยอดชำระ</th>
                                            <th>จำนวนวัน</th>
                                            <th>อัตราดอกเบี้ย</th>
                                            <th>ดอก/วัน</th>
                                            <th>หมายเหตุ</th>
                                            <th>ผู้รับชำระ</th>
                                            <th>หลักฐาน</th>
                                            <th>สถานะ</th>
                                            <th style="width:120px;">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="installmentBody">
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">กำลังโหลดข้อมูล...</td>
                                        </tr>
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

<!-- Modal ชำระ -->
<div class="modal fade" id="modalPayInstallment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">เพิ่มการชำระ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="pay_owner_loan_id" value="<?= (int)$ownerLoanData->id ?>">

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="form-label">วันที่ชำระ</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                            </div>
                            <input type="text"
                                class="form-control"
                                id="pay_paid_date"
                                value="<?= date('Y-m-d') ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ยอดชำระ</label>
                        <input type="text" class="form-control text-end price_pay_amount" id="pay_amount" placeholder="0.00">
                        <div class="text-muted small mt-1">คงเหลือ: <b id="modalRemain">0.00</b></div>
                    </div>
                </div>

                <!-- ✅ เพิ่มบัญชีโอนออก -->
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="mb-2">
                            <label class="form-label">โอนออกจากบัญชีไหน</label>
                            <select class="form-control custom-select" id="pay_land_account_id" style="font-size: 13px;">
                                <option value="">-- กำลังโหลดบัญชี --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label">หมายเหตุ</label>
                    <input type="text" class="form-control" id="pay_note" placeholder="หมายเหตุ...">
                </div>

                <div class="mb-2">
                    <label class="form-label">แนบหลักฐาน (ถ้ามี)</label>
                    <input type="file" class="form-control" id="pay_file" accept="image/*,application/pdf">
                </div>

                <div id="pay_file_preview" class="mt-2"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">ปิด</button>
                <button class="btn btn-outline-primary" type="button" id="btnSubmitPay">บันทึกการชำระ</button>
            </div>
        </div>
    </div>
</div>
<!-- modal modal_Edit_Owner_Interest -->
<div class="modal fade" id="modal_Edit_Owner_Interest" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">ตั้งค่าอัตราดอกเบี้ย</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="card-body">
                <form method="post" id="form_Edit_Loan_Interest" name="form_Edit_Loan_Interest" action="#">
                    <input type="hidden" name="owner_loan_id" id="owner_loan_id" />
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <label for="loan_interest_rate" class="form-label">
                                ตั้งค่าอัตราดอกเบี้ย (% ต่อปี)
                            </label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input type="number"
                                    class="form-control text-end"
                                    id="loan_interest_rate"
                                    name="loan_interest_rate"
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00">
                                <span class="input-group-text">% ต่อปี</span>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div style="display:flex; justify-content:center;">
                        <button class="btn btn-primary btn-block btnSaveLoanInterest" type="button">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    window.CDN_IMG = "<?= rtrim(getenv('CDN_IMG'), '/') ?>";
</script>