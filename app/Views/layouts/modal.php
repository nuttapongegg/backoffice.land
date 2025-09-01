<!-- ตารางประวัติการใช้งาน -->
<div class="modal fade" id="modalEmployeeLog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">ตารางประวัติการใช้งาน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closemodalPosition"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="EmployeelogAll">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px;">#</th>
                                    <th class="" style="width: 150px;">ชื่อผู้ใช้</th>
                                    <th class="" style="width: 100px;">การกระทำ</th>
                                    <th class="" style="width: 130px;">กระทำเมื่อวันที่</th>
                                    <th class="" style="width: 130px;">กระทำเมื่อเวลา</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <!--                        <div style="display: flex; justify-content: center;">-->
                        <!--                            <button type="button" class="btn btn-primary " data-bs-dismiss="modal" aria-label="Close">ปิด</button>-->
                        <!--                        </div>-->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- จบ ตารางประวัติการใช้งาน -->

<!-- ใบสำคัญ -->
<style>
    @media (min-width: 1200px) {
        #docModal .modal-xl {
            max-width: 1240px;
        }
    }

    #docModal .table th,
    #docModal .table td {
        padding: 0.35rem;
    }
</style>

<div class="modal fade" id="docModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title tx-primary">title</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body pt-0">
                <div class="card-body">
                    <div class="panel tabs-style5 w-fit-content mx-auto">
                        <div class="panel-head">
                            <ul class="nav nav-tabs bg-white">
                                <li class="nav-item tabDocType"><a class="nav-link tx-14 font-weight-semibold tabDocType1" data-bs-toggle="tab" href="#tabDocType1" data-doc-type="ใบสำคัญรับ">ใบสำคัญรับ</a></li>
                                <li class="nav-item tabDocType"><a class="nav-link tx-14 font-weight-semibold tabDocType2" data-bs-toggle="tab" href="#tabDocType2" data-doc-type="ใบสำคัญจ่าย">ใบสำคัญจ่าย</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-3 mb-3" style="text-align: right;">
                        <a href="javascript:void(0);" class="btn btn-outline-secondary" id="btnAiAutoCaptureDoc" style="display: none;">
                            <i class="fas fa-camera"></i> ถ่ายรูป
                        </a>
                        <a href="javascript:void(0);" class="btn btn-outline-primary" id="btnAiAutoSelectDoc" style="display: none;">
                            <i class="fab fa-reddit-alien"></i> ใช้ AI Auto Input
                        </a>
                    </div>
                    <div id="detectImageFormInvoiceDoc" style="display: none;">
                        <div class="row">
                            <div class="col text-center">
                                <!-- แสดงตัวอย่างภาพ -->
                                <img id="imagePreviewInvoiceDoc" width="17%" class="img-thumbnail" style="display: none;" /><br>
                                <!-- แสดงตัวอย่าง PDF -->
                                <iframe id="pdfPreviewInvoiceDoc" style="display: none;" width="100%" height="400px"></iframe><br>
                                <button type="button" class="btn btn-outline-danger btn-rounded mt-3" id="btnAiAutoInputInvoiceDocClear">ยกเลิก</button>
                                <button type="submit" class="btn btn-success btn-rounded mt-3" id="btnAiAutoInputInvoiceDocSubmit">ยืนยัน</button>
                            </div>
                        </div>
                        <div>
                            <!-- input file ที่รองรับทั้งการเลือกไฟล์และการถ่ายภาพจากกล้องมือถือ -->
                            <input type="file" class="custom-file-input" id="imageFileInvoiceDoc" name="imageFileInvoiceDoc" accept="image/*,application/pdf" style="display: none;" />
                        </div>
                        <hr>
                    </div>

                    <?php $FORM_KEY = 'FORM_KEY_' . strtotime('now') . '_' . rand(10, 100); ?>
                    <form action="#" name="formDoc" id="<?php echo $FORM_KEY; ?>" data-form-key="<?php echo $FORM_KEY; ?>">
                        <input type="hidden" name="doc_type">
                        <input type="hidden" name="doc_id">
                        <input type="hidden" name="doc_file_date">
                        <input type="hidden" name="doc_file_time">
                        <input type="hidden" name="doc_file_price">
                        <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary">ข้อมูลพื้นฐาน</p>
                        <div class="form-group mb-0">
                            <div class="row row-sm">
                                <div class="col-sm-6">
                                    <label class="form-label">เลขที่ใบสำคัญ</label>
                                    <div class="form-group disabled">
                                        <input class="form-control" name="doc_number" type="text" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">วันที่ <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                        <input type="text" class="form-control" id="formDate" name="doc_date" placeholder="เลือกวันที่" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <div class="row row-sm">
                                <div class="col-sm-6">
                                    <label class="form-label">รายการ(หมวดหมู่) <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control pd-r-80" name="title" type="text" required>
                                        <input name="title_id" type="hidden">
                                        <div class="input-group-text" id="openTitleList">
                                            ค้นหา
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">จำนวนเงิน <span class="tx-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="price" type="text">
                                        <span class="input-group-text" id="basic-addon2">บาท</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row row-sm">
                                <div class="col-sm-12">
                                    <label class="form-label">หมายเหตุ</label>
                                    <div class="input-group">
                                        <input class="form-control pd-r-80" name="note" id="note" type="text" autocomplete="off" required>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="list-group" style="width: 1170px;" id="show-list"></div>
                                        <!-- position: absolute; -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary">ข้อมูลการเงิน</p>
                        <div class="form-group mb-0">
                            <div class="row row-sm">
                                <div class="col-sm-12">
                                    <label class="form-label">บัญชีบริษัท <span class="tx-danger">*</span></label>
                                    <select name="land_account_name" class="form-control form-select " data-bs-placeholder="Select Country" required>
                                        <?php foreach (SettingLandModel() as $setting_land) { ?>
                                            <option value="<?php echo $setting_land->land_account_name; ?>"><?php echo $setting_land->land_account_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="wrapperFile mt-3">
                            <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary">แนบไฟล์</p>
                            <div class="col-sm-12 col-md-12">
                                <input type="file" class="docDropify" name="file" data-height="200" />
                            </div>
                        </div>
                        <hr>
                        <div style="display: flex; justify-content: center;">
                            <button class="btn btn-primary btn-block btnSave" type="button">ยืนยันเพิ่มข้อมูล</button>
                        </div>
                    </form>
                    <hr>
                    <div class="row row-sm">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title table-title">รายการใบสำคัญ</div>
                                </div>
                                <div class="card-body">
                                    <div class="row justify-content-end">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="daterange_doc" placeholder="เริ่มหาวันที่ ถึง วันที่ (กรณีว่าง จะดึงข้อมูลทั้งหมด)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive ">
                                        <table class="table table-bordered text-nowrap border-bottom" id="tableDoc">
                                            <thead>
                                                <tr>
                                                    <th>เลขที่</th>
                                                    <th>วันที่</th>
                                                    <th class="text-center">รายการ</th>
                                                    <th class="text-center">รายละเอียด</th>
                                                    <th>จำนวน</th>
                                                    <th>ผู้ทำรายการ</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
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
</div>