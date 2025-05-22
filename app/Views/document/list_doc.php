<!DOCTYPE html>
<html lang="en" data-layout="horizontal" data-hor-style="hor-hover" data-logo="centerlogo">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Title -->
    <title><?php echo $title; ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url('/assets/img/brand/favicon.ico'); ?>" type="image/x-icon" />

    <!-- Icons css -->
    <link href="<?php echo base_url('/assets/css/icons.css'); ?>" rel="stylesheet">

    <!-- datatable -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <!--  Bootstrap css-->
    <link id="style" href="<?php echo base_url('/assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" />

    <!-- Style css -->
    <link href="<?php echo base_url('/assets/css/style.css'); ?>" rel="stylesheet">

    <!-- Plugins css -->
    <link href="<?php echo base_url('/assets/css/plugins.css'); ?>" rel="stylesheet">

    <!-- Switcher css -->
    <link href="<?php echo base_url('/assets/switcher/css/switcher.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('/assets/switcher/styles.css'); ?>" rel="stylesheet" />

    <?php if (isset($css_critical)) {
        echo $css_critical;
    } ?>

    <link href="<?php echo base_url('/assets/app/css/app.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/image-uploader.min.css'); ?>">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">

    <script>
        var serverUrl = '<?php echo base_url(); ?>'
    </script>
</head>

<body class="ltr main-body app sidebar-mini">
    <input type="hidden" name="actionBy" value="<?php echo $actionBy; ?>">
    <input type="hidden" name="formKey" value="<?php echo isset($formKey) ? $formKey : ''; ?>">
    <!-- Loader -->
    <div id="global-loader">
        <img src="<?php echo base_url('/assets/img/loader.svg'); ?>" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->

    <div id="docModal" class="card-body">
        <input type="hidden" name="oldDocType" value="<?php echo $docType; ?>">
        <input type="hidden" name="carStockID" value="<?php echo hashidsEncrypt($carStock->id); ?>">
        <input type="hidden" name="oldDocID" value="<?php echo $docID; ?>">
        <div class="panel tabs-style5 w-fit-content mx-auto">
            <div class="panel-head">
                <ul class="nav nav-tabs bg-white">
                    <li class="nav-item tabDocType"><a class="nav-link tx-14 font-weight-semibold tabDocType1" data-bs-toggle="tab" href="#tabDocType1" data-doc-type="ใบสำคัญรับ">ใบสำคัญรับ</a></li>
                    <li class="nav-item tabDocType"><a class="nav-link tx-14 font-weight-semibold tabDocType2" data-bs-toggle="tab" href="#tabDocType2" data-doc-type="ใบสำคัญจ่าย">ใบสำคัญจ่าย</a></li>
                    <li class="nav-item tabDocType"><a class="nav-link tx-14 font-weight-semibold tabDocType3" data-bs-toggle="tab" href="#tabDocType3" data-doc-type="ใบส่วนลด">ใบส่วนลด</a></li>
                    <!-- <li class="nav-item tabDocType"><a class="nav-link tx-14 font-weight-semibold tabDocType4" data-bs-toggle="tab" href="#tabDocType4" data-doc-type="อื่น ๆ">จ่ายอื่น ๆ</a></li> -->
                </ul>
            </div>
        </div>
        <?php if (
            (base_url() == 'https://stock.evxspst.com') || (base_url() == 'http://localhost:8080') || (base_url() == 'https://stock.up2carsdemo.com')
        ) { ?>
            <div class="mt-3" style="text-align: right;">
                <a href="javascript:void(0);" class="btn btn-outline-primary" id="btnAiAutoInputInvoice"><i class="fab fa-reddit-alien"></i> ใช้ AI Auto Input</a>
            </div>
        <?php } ?>
        <div id="detectImageFormInvoice" style="display: none;">
            <div class="row">
                <div class="col text-center">
                    <img id="imagePreviewInvoice" width="17%" class="img-thumbnail" style="display: none;" /><br>
                    <iframe id="pdfPreviewInvoice" style="display: none;" width="100%" height="400px"></iframe><br>
                    <button type="button" class="btn btn-outline-danger btn-rounded mt-3" id="btnAiAutoInputInvoiceClear">ยกเลิก</button>
                    <button type="submit" class="btn btn-success btn-rounded mt-3" id="btnAiAutoInputInvoiceSubmit">ยืนยัน</button>
                </div>
            </div>
            <div>
                <input type="file" class="custom-file-input" id="imageFileInvoice" name="imageFileInvoice" accept="application/pdf,image/*" />
            </div>
            <hr>
        </div>
        <?php $FORM_KEY = 'FORM_KEY_' . strtotime('now') . '_' . rand(10, 100); ?>
        <form action="#" name="formDoc" id="<?php echo $FORM_KEY; ?>" data-form-key="<?php echo $FORM_KEY; ?>">
            <input type="hidden" name="doc_type">
            <input type="hidden" name="doc_id">
            <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary">ข้อมูลพื้นฐาน</p>
            <div class="form-group mb-0">
                <div class="row row-sm">
                    <div class="col-sm-12">
                        <label class="form-label">รถยนต์</label>
                        <div class="form-group disabled">
                            <input class="form-control pd-r-80" name="car_title" type="text" value="<?php echo isset($carStock) ? "$carStock->car_stock_owner_car_brand $carStock->car_stock_owner_car_model $carStock->car_stock_owner_car_vin $carStock->car_stock_owner_car_province" : ''; ?>" readonly required>
                            <input name="car_stock_id" type="hidden" value="<?php echo isset($carStock) ? hashidsEncrypt($carStock->id) : ''; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
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
                        <label class="form-label">หมวดหมู่ <span class="tx-danger">*</span></label>
                        <div class="input-group">
                            <input class="form-control pd-r-80" name="title" type="text" value="<?php echo isset($inputTitle) ? $inputTitle->title : ''; ?>" required>
                            <input name="title_id" type="hidden" value="<?php echo isset($inputTitle) ? hashidsEncrypt($inputTitle->id) : ''; ?>">
                            <div class="input-group-text" id="openTitleList">
                                ค้นหา
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">จำนวนเงิน <span class="tx-danger">*</span></label>
                        <div class="input-group mb-3">
                            <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="price" type="text" value="<?php echo isset($inputPrice) ? $inputPrice : ''; ?>">
                            <span class="input-group-text" id="basic-addon2">บาท</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row row-sm">
                    <div class="col-sm-12">
                        <label class="form-label">รายละเอียด</label>
                        <div class="input-group">
                            <input class="form-control pd-r-80" name="doc_detail" id="doc_detail" type="text" autocomplete="off" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group selectCustomer">
                <div class="row row-sm">
                    <div class="col-sm-12">
                        <label class="form-label">ลูกค้า <span class="tx-danger">*</span></label>
                        <div class="input-group" id="openCustomerList">
                            <input class="form-control pd-r-80" name="customer_title" type="text" value="<?php echo isset($customer) ? $customer->fullname : ''; ?>" readonly required>
                            <input name="customer_id" type="hidden" value="<?php echo isset($customer) ? hashidsEncrypt($customer->id) : ''; ?>">
                            <div class="input-group-text">
                                <i class="onicon side-menu__icon icon ion-md-woman"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group selectSeller">
                <div class="row row-sm">
                    <div class="col-sm-12">
                        <label class="form-label">ผู้ขาย <span class="tx-danger">*</span></label>
                        <div class="input-group" id="openSellerList">
                            <input class="form-control pd-r-80" name="seller_title" type="text" value="<?php echo isset($seller) ? $seller->seller_name : ''; ?>" readonly required>
                            <input name="seller_id" type="hidden" value="<?php echo isset($seller) ? hashidsEncrypt($seller->id) : ''; ?>">
                            <div class="input-group-text">
                                <i class="onicon side-menu__icon icon ion-md-woman"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-0">
                <div class="row row-sm">
                    <div class="col-sm-12">
                        <label class="form-label">เลขที่อ้างอิง</label>
                        <div class="input-group mb-3">
                            <input aria-describedby="basic-addon2" aria-label="" class="form-control reference_number" placeholder="" name="reference_number" id="reference_number" type="text">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-0">
                <div class="row row-sm">
                    <div class="col-sm-6">
                        <label class="form-label">ราคาสินค้า <span class="tx-danger">*</span></label>
                        <select name="price_vat" id="price_vat" class="form-control form-select " data-bs-placeholder="Select Country" required>
                            <option value="ราคาไม่รวมภาษี">ราคาไม่รวมภาษี</option>
                            <option value="ราคารวมภาษี">ราคารวมภาษี</option>
                        </select>
                    </div>
                    <div class="col-sm-2 mt-4">
                        <div class="collapse show" id="checkbox_price_vat">
                            <label class="label ckbox mt-3">
                                <input type="checkbox" id="checkbox_vat" name="checkbox_vat">
                                <span class="tx-14">ภาษีมูลค่าเพิ่ม 7%</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-2 mt-4">
                        <div class="form-group selectWHT">
                            <label class="label ckbox mt-3">
                                <input type="checkbox" id="checkbox_wht" name="checkbox_wht">
                                <span class="tx-14">หักภาษี ณ ที่จ่าย</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-2 mt-4">
                        <div class="collapse mt-2" id="open_wht">
                            <div class="input-group">
                                <input class="form-control pd-r-80" name="wht_percent" id="wht_percent" type="text" autocomplete="off" required>
                                <span class="input-group-text">%</span>
                            </div>
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
                        <select name="cash_flow_name" class="form-control form-select " data-bs-placeholder="Select Country" required>
                            <?php foreach (getCashFlow() as $cashflow) { ?>
                                <option value="<?php echo $cashflow->cash_flow_name; ?>"><?php echo $cashflow->cash_flow_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group mb-0">
                <div class="row row-sm">
                    <div class="col-sm-12">
                        <label class="form-label">ประเภท <span class="tx-danger">*</span></label>
                        <select name="doc_payment_type" class="form-control form-select " data-bs-placeholder="Select Country" required>
                            <option value="เงินสด">เงินสด</option>
                            <option value="A/C">A/C</option>
                            <option value="อื่น ๆ">อื่น ๆ</option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="wrapperDocPaymentTypeEtc" style="display: none;">
                <div class="form-group mb-0">
                    <div class="row row-sm">
                        <div class="col-sm-12">
                            <label class="form-label">ระบุ</label>
                            <div class="form-group">
                                <input class="form-control" name="" type="text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="wrapperACForm" style="display: none;">
                <div class="form-group mb-0">
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <label class="form-label">รับเช็คในนาม</label>
                            <div class="form-group">
                                <input class="form-control" name="cheque_name" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">เอกสารอ้างอิง</label>
                            <div class="form-group">
                                <input class="form-control" name="cheque_ref" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <label class="form-label">ธนาคาร</label>
                            <div class="form-group">
                                <select name="cheque_bank_title" class="form-control select2 select2-banks" data-bs-placeholder="Selct One">
                                    <option label="Choose one" value="">เลือกธนาคาร</option>
                                    <option value="ธนาคารไทยพาณิชย์" data-icon="1">ธนาคารไทยพาณิชย์</option>
                                    <option value="ธนาคารกสิกรไทย" data-icon="2">ธนาคารกสิกรไทย</option>
                                    <option value="ธนาคารกรุงเทพ" data-icon="3">ธนาคารกรุงเทพ</option>
                                    <option value="ธนาคารกรุงศรีอยุธยา" data-icon="4">ธนาคารกรุงศรีอยุธยา</option>
                                    <option value="ธนาคารทหารไทยธนชาต" data-icon="5">ธนาคารทหารไทยธนชาต</option>
                                    <option value="ธนาคารกรุงไทย" data-icon="6">ธนาคารกรุงไทย</option>
                                    <option value="ธนาคารออมสิน" data-icon="7">ธนาคารออมสิน</option>
                                    <option value="ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร" data-icon="8">ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">สาขา</label>
                            <div class="form-group">
                                <input class="form-control" name="cheque_bank_branch" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <label class="form-label">เลขที่</label>
                            <div class="form-group">
                                <input class="form-control" name="cheque_bank_no" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">วันที่</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                </div>
                                <input type="text" class="form-control" id="chequeBankDate" name="cheque_bank_date" placeholder="เลือกวันที่">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrapperFile mt-3">
                <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary">แนบไฟล์</p>
                <div class="col-sm-12 col-md-12">
                    <input type="file" class="docDropify" name="file" data-height="200" />
                    <!-- <div class="file">
                    </div> -->
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
                                        <input type="text" class="form-control" id="daterange_doc_car" placeholder="เริ่มหาวันที่ ถึง วันที่ (กรณีว่าง จะดึงข้อมูลทั้งหมด)">
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
                                        <th>รถยนต์</th>
                                        <th class="tableTitle-x"></th>
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


    <!-- JQuery min js -->
    <script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap js -->
    <script src="<?php echo base_url('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

    <!-- Select2 js -->
    <script src="<?php echo base_url('/assets/plugins/select2/js/select2.full.min.js'); ?>"></script>

    <!-- Internal Select2.min js -->
    <script src="<?php echo base_url('/assets/plugins/select2/js/select2.min.js'); ?>"></script>

    <!-- Data tables -->
    <script src="<?php echo base_url('/assets/plugins/datatable/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/js/dataTables.bootstrap5.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/dataTables.responsive.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/responsive.bootstrap5.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/dataTables.responsive.min.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/datatable/responsive.bootstrap5.min.js'); ?>"></script>
    <script src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <!-- Flatpickr js -->
    <script src="<?php echo base_url('/assets/plugins/flatpickr/flatpickr.js'); ?>"></script>

    <!--Internal  jquery.maskedinput js -->
    <script src="<?php echo base_url('/assets/plugins/jquery.maskedinput/jquery.maskedinput.js'); ?>"></script>

    <script src="https://amiryxe.github.io/easy-number-separator/easy-number-separator.js"></script>

    <?php if (isset($js_critical)) {
        echo $js_critical;
    }; ?>

    <script src="<?php echo base_url('/assets/plugins/fileuploads/js/fileupload.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/fileuploads/js/file-upload.js'); ?>"></script>

    <script src="<?php echo base_url('/assets/plugins/fancyuploder/jquery.ui.widget.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/fancyuploder/jquery.fileupload.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/fancyuploder/fancy-uploader.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/fancyuploder/jquery.iframe-transport.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/plugins/fancyuploder/jquery.fancy-fileupload.js'); ?>"></script>
    <script src="<?php echo base_url('/assets/js/image-uploader.min.js'); ?>"></script>


    <!-- custom-switcher js -->
    <script src="<?php echo base_url('/assets/js/custom-switcher.js'); ?>"></script>

    <!-- custom js -->
    <script src="<?php echo base_url('/assets/js/custom.js'); ?>"></script>

    <script src="<?php echo base_url('/assets/switcher/js/switcher.js'); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.all.min.js"></script>

    <script>
        function select2Banks(data) {

            if (!data.id) {
                return data.text;
            }

            if (data.id == 'เลือกธนาคาร') {
                return data.text;
            }

            var $data = $(
                '<span class="d-flex align-items-center"><img src="' + serverUrl + '/assets/img/banks/ic-' + data.element.dataset.icon + '.png" class="rounded-circle avatar-xs me-1" /> ' +
                data.text + '</span>'
            );
            return $data;
        }

        $(document).ready(function() {

            // $('.file').imageUploader();

            const $FORM_KEY = $('form').data('form-key')

            const LIST_DOC = {

                // ลิสใบสำคัญ
                docTable() {
                    $('#tableDoc').DataTable().clear().destroy()
                    let $table = $('#tableDoc').DataTable({
                        "oLanguage": {
                            "sInfo": "กำลังแสดง หน้า _PAGE_ ใน _PAGES_",
                            "sSearch": '',
                            "sSearchPlaceholder": "ค้นหา...",
                        },
                        "stripeClasses": [],
                        "pageLength": 5,
                        "lengthMenu": [
                            [5, 25, 50, -1],
                            [5, 25, 50, "ทั้งหมด"]
                        ],

                        // Processing indicator
                        "processing": true,
                        // DataTables server-side processing mode
                        "serverSide": true,
                        // Initial no order.
                        "order": [],
                        // Load data from an Ajax source
                        "ajax": {
                            "url": `${serverUrl}/document/getLists`,
                            "type": "POST",
                            "data": function(d) {

                                // d.data = {
                                //     docType: $docType
                                // }

                                const $date = $("#daterange_doc_car").val();
                                if ($date !== "") {
                                    d.date = $date;
                                }

                                let $tab = $(".tabDocType a.active")

                                d.docType = $tab.data('doc-type')
                                d.carStockID = $('input[name=carStockID]').val()

                                return d
                            }
                        },
                        //Set column definition initialisation properties
                        "columnDefs": [{
                                'className': 'text-center',
                                "targets": [0],
                                "width": '10%'
                            },
                            {
                                'className': 'text-center',
                                "targets": [1],
                                "width": '10%'
                            },
                            {
                                'className': 'text-left',
                                "targets": [2],
                                "width": '10%'
                            },
                            {
                                'className': 'text-left',
                                "targets": [3],
                                "width": '10%'
                            },
                            {
                                'className': 'text-center',
                                "targets": [4],
                                "width": '10%'
                            },
                            {
                                'className': 'text-center',
                                "targets": [5],
                                "width": '10%'
                            },
                            {
                                'className': 'text-center',
                                "targets": [6],
                                "width": '10%'
                            },
                            {
                                'className': 'text-center',
                                "targets": [7],
                                "orderable": false,
                                "width": '20%'
                            }
                        ]
                    })
                },

                // ล้าง DocForm
                docFormClear() {
                    $('form[name=formDoc]').trigger("reset")
                },

                // ตั้งค่าฟอร์มใบสำคัญเริ่มต้น
                docSetEnvForm() {

                    $("#formDate").flatpickr()

                    easyNumberSeparator({
                        selector: '.price',
                        separator: ','
                    })

                    $("#chequeBankDate").flatpickr()

                    $(".select2-banks").select2({
                        templateResult: select2Banks,
                        templateSelection: select2Banks,
                        escapeMarkup: function(m) {
                            return m
                        }
                    })
                },

                // จัดการฟอร์มใบสำคัญเมื่อเปิด
                docOpenForm($docType) {

                    $("#wrapperDocPaymentTypeEtc").hide()
                    $("#wrapperACForm").hide()

                    let $tab = $(".tabDocType"),
                        $modal = $("#docModal"),
                        $inputType = $("input[name=doc_type]")

                    var date = new Date();

                    if (date.getMonth() <= 8) {
                        var date_doc = date.getFullYear() + '0' + (date.getMonth() + 1);
                    } else {
                        var date_doc = date.getFullYear() + '' + (date.getMonth() + 1);
                    }

                    switch ($docType) {
                        case 'ใบสำคัญรับ':
                            $title = 'ใบสำคัญรับ'
                            // $inputDocType = 'CTIN-' + Date.now()
                            $tabAhrefClass = '.tabDocType1'
                            $modal.find('.selectCustomer').show()
                            $modal.find('.selectSeller').hide()
                            $('.tableTitle-x').html('ลูกค้า')
                            $('.wrapperFile').hide()
                            // $(".file").html("")
                            // $(".file").imageUploader()   
                            break

                        case 'ใบสำคัญจ่าย':
                            $title = 'ใบสำคัญจ่าย'
                            // $inputDocType = 'CTEX-' + Date.now()
                            $tabAhrefClass = '.tabDocType2'
                            $modal.find('.selectCustomer').hide()
                            $modal.find('.selectSeller').show()
                            $('.tableTitle-x').html('ผู้ขาย')
                            $('.wrapperFile').show()
                            // $(".file").html("")
                            // $(".file").imageUploader()                       
                            break

                        case 'ใบส่วนลด':
                            $title = 'ใบส่วนลด'
                            // $inputDocType = 'CTDIS-' + Date.now()
                            $tabAhrefClass = '.tabDocType3'
                            $modal.find('.selectCustomer').show()
                            $modal.find('.selectSeller').hide()
                            $('.tableTitle-x').html('ลูกค้า')
                            $('.wrapperFile').hide()
                            $(".file").html("")
                            $(".file").imageUploader()
                            break

                            // case 'อื่น ๆ':
                            //     $title = 'จ่ายอื่น ๆ'
                            //     $inputDocType = 'CTX-' + Date.now()
                            //     $tabAhrefClass = '.tabDocType4'
                            //     $modal.find('.selectCar').hide()
                            //     $modal.find('.selectCustomer').hide()
                            //     $modal.find('.selectSeller').hide()
                            //     $('.tableTitle-x').html('ลูกค้า')
                            //     $('.wrapperFile').hide()
                            //     break
                    }

                    // จัดการ Modal
                    $modal.find('.modal-title').html($title)
                    $modal.find('.table-title').html('ประวัติ' + $title)

                    // จัดการ Tab
                    $tab.find('a').removeClass('active')
                    $tab.find($tabAhrefClass).addClass('active')

                    // จัดการ Form
                    $inputType.val($docType)
                    var $tabDocType = $(".tabDocType a.active"),

                        documentType = $tabDocType.data('doc-type')

                    $.ajax({
                        url: `${serverUrl}/document/docNumber/` + documentType,
                        type: "GET",
                        dataType: 'json',
                        success: function(res) {
                            switch (documentType) {
                                case 'ใบสำคัญรับ':
                                    $inputDocType = 'RV-' + date_doc + res
                                    break
                                case 'ใบสำคัญจ่าย':
                                    $inputDocType = 'PV-' + date_doc + res
                                    break
                                case 'ใบส่วนลด':
                                    $inputDocType = 'CN-' + date_doc + res
                                    break
                                case 'รายจ่าย':
                                    $inputDocType = 'DN-' + date_doc + res
                                    break
                            }
                            $('input[name=doc_number]').val($inputDocType);
                            // console.log(res)
                        },
                        error: function(data) {}
                    });
                    // $('input[name=doc_number]').val($inputDocType)
                    $('.wrapperFile').find('.dropify-preview').css('display', 'none')
                    $('.wrapperFile').find('.dropify-render').html('')
                    $('.docDropify').dropify()

                    // LOAD ใบสำคัญ
                    LIST_DOC.docTable()
                },

                // จัดการฟอร์มใบสำคัญ
                handleDocForm() {
                    let $modalDoc = $("#docModal")
                    let $form = $modalDoc.find('form')

                    /*************************************************************
                     * EVENT
                     */

                    // เมนู TAB
                    $('.tabDocType').on('click', function() {

                        let $me = $(this),
                            $docType = $me.find('a').data('doc-type')

                        LIST_DOC.docFormClear()
                        LIST_DOC.docSetEnvForm()
                        LIST_DOC.docOpenForm($docType)
                    })

                    $modalDoc
                        // เรียกข้อมูลมาแก้ไข
                        .on('click', '.btnEdit', function() {
                            let $me = $(this),
                                $docID = $me.data('doc-id')

                            $.ajax({
                                type: "GET",
                                url: `${serverUrl}/document/edit/${$docID}`,
                                contentType: "application/json; charset=utf-8"
                            }).done(function(res) {
                                if (res.success == 1) {
                                    $("#checkbox_price_vat").removeClass("show")
                                    $("#open_wht").removeClass("show");
                                    $('input:checkbox').removeAttr('checked');
                                    LIST_DOC.docFormClear()
                                    LIST_DOC.docOpenForm()
                                    $("#docModal").animate({
                                        scrollTop: 100
                                    }, 'slow')

                                    let $data = res.data

                                    let $docPaymentType = $data.doc_payment_type,
                                        $docPrice = $data.price,
                                        $cash_flow_name = $data.cash_flow_name

                                    $form.find('input[name=doc_id]').val($data.id)
                                    $form.find('input[name=doc_type]').val($data.doc_type)
                                    $form.find('input[name=doc_number]').val($data.doc_number)
                                    $("#formDate").flatpickr()
                                    $form.find('input[name=doc_date]').val($data.doc_date)
                                    $form.find('input[name=title]').val($data.title)
                                    $form.find('input[name=price]').val($docPrice)
                                    easyNumberSeparator({
                                        selector: '.price',
                                        separator: ','
                                    })
                                    $form.find('input[name=car_stock_id]').val($data.car_stock_id)
                                    $form.find('input[name=car_title]').val($data.car_title)

                                    $form.find('select[name=doc_payment_type]').val($docPaymentType)
                                    $form.find('select[name=cash_flow_name]').val($cash_flow_name)

                                    $form.find('input[name=doc_payment_type_etc]').val($data.doc_payment_type_etc)
                                    $form.find('input[name=cheque_name]').val($data.cheque_name)
                                    $form.find('input[name=cheque_ref]').val($data.cheque_ref)
                                    $form.find('input[name=cheque_bank_title]').val($data.cheque_bank_title)
                                    $form.find('input[name=cheque_bank_branch]').val($data.cheque_bank_branch)
                                    $form.find('input[name=cheque_bank_no]').val($data.cheque_bank_no)
                                    $("#chequeBankDate").flatpickr()
                                    $form.find('input[name=cheque_bank_date]').val($data.cheque_bank_date)
                                    $form.find('input[name=note]').val($data.note)
                                    $form.find('input[name=doc_detail]').val($data.doc_detail)
                                    $form.find('input[name=reference_number]').val($data.reference_number)
                                    $form.find('select[name=price_vat]').val($data.price_vat)
                                    if ($data.price_vat == '') {
                                        $form.find('select[name=price_vat]').val('ราคาไม่รวมภาษี')
                                    }
                                    if ($data.price_vat != 'ราคารวมภาษี') {
                                        $("#checkbox_price_vat").toggleClass("show");
                                    }
                                    if ($data.doc_vat == 1) {
                                        $('#docModal #checkbox_vat').attr("checked", "checked");
                                    }
                                    if ($data.doc_wht == 1) {
                                        $('#docModal #checkbox_wht').attr("checked", "checked");
                                        $("#open_wht").toggleClass("show");
                                    }
                                    $form.find('input[name=wht_percent]').val($data.wht_percent)

                                    switch ($data.doc_type) {
                                        case 'ใบสำคัญรับ':
                                            $form.find('input[name=customer_id]').val($data.customer_id)
                                            $form.find('input[name=customer_title]').val($data.customer_title)
                                            $form.find('input[name=seller_id]').val(0)
                                            $form.find('input[name=seller_title]').val('')
                                            break

                                        case 'ใบสำคัญจ่าย':
                                            $form.find('input[name=customer_id]').val(0)
                                            $form.find('input[name=customer_title]').val('')
                                            $form.find('input[name=seller_id]').val($data.seller_id)
                                            $form.find('input[name=seller_title]').val($data.seller_title)

                                            if ($data.filePath != '') {
                                                $form.find('.dropify-preview').css('display', 'block')
                                                $form.find('.dropify-render').html('<img src="' + `${CDN_IMG}/uploads/file/${$data.filePath}` + '" alt="">')
                                            }
                                            break

                                        case 'ใบส่วนลด':
                                            $form.find('input[name=customer_id]').val($data.customer_id)
                                            $form.find('input[name=customer_title]').val($data.customer_title)
                                            $form.find('input[name=seller_id]').val(0)
                                            $form.find('input[name=seller_title]').val('')
                                            break

                                            // case 'อื่น ๆ':
                                            //     $form.find('input[name=customer_id]').val(0)
                                            //     $form.find('input[name=customer_title]').val('')
                                            //     $form.find('input[name=seller_id]').val(0)
                                            //     $form.find('input[name=seller_title]').val('')
                                            //     break
                                    }

                                    switch ($docPaymentType) {
                                        case 'เงินสด':
                                            $("#wrapperDocPaymentTypeEtc").hide()
                                            $("#wrapperACForm").hide()
                                            break

                                        case 'A/C':
                                            $("#wrapperACForm").show()
                                            $("#wrapperDocPaymentTypeEtc").hide()
                                            break

                                        case 'อื่น ๆ':
                                            $("#wrapperACForm").hide()
                                            $("#wrapperDocPaymentTypeEtc").show()
                                            break
                                    }

                                    $form.find('.btnSave').html('ยืนยันแก้ไขข้อมูล')
                                }
                            })
                        })
                        // ลบใบสำคัญ
                        .on('click', '.btnDelete', function() {

                            let $me = $(this)

                            let $url = $me.data('url'),
                                $name = $me.data('document-number')

                            $me.prop('disabled', true)

                            Swal.fire({
                                text: `คุณต้องการลบ ${$name}`,
                                icon: "warning",
                                buttonsStyling: false,
                                confirmButtonText: "ตกลง",
                                showCloseButton: true,
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function(result) {
                                if (result.isConfirmed) {

                                    $.ajax({
                                        type: 'POST',
                                        url: $url,
                                        success: function(res) {

                                            $me.prop('disabled', false)

                                            Swal.fire({
                                                icon: "success",
                                                text: `${res.message}`,
                                                timer: '2000',
                                                heightAuto: false
                                            })

                                            LIST_DOC.docTable()
                                        },
                                        error: function(res) {
                                            Swal.fire({
                                                icon: "error",
                                                text: `ไม่สามารถลบได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ`,
                                                timer: '2000',
                                                heightAuto: false
                                            })
                                        }
                                    })
                                }
                            })
                        })
                        // ดึงรายการ
                        .on('click', '#openTitleList', function() {
                            let x = screen.width / 2 - 800 / 2
                            let y = screen.height / 2 - 600 / 2

                            let $tab = $(".tabDocType a.active")
                            let $docType = $tab.data('doc-type')

                            let configParam = $.param({
                                'actionBy': 'MAIN',
                                'formKey': $FORM_KEY,
                                'docType': $docType
                            })

                            let url = `${serverUrl}/document/listTitle?${configParam}`
                            window.open(url, 'DF', 'menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no height=600,width=800,left=' + x + ',top=' + y)
                        })
                        // ดึงรายชื่อลูกค้า
                        .on('click', '#openCustomerList', function() {
                            let x = screen.width / 2 - 800 / 2
                            let y = screen.height / 2 - 600 / 2

                            let configParam = $.param({
                                'actionBy': 'MAIN',
                                'formKey': $FORM_KEY
                            })

                            let url = `${serverUrl}/document/listCustomer?${configParam}`
                            window.open(url, 'DF', 'menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no height=600,width=800,left=' + x + ',top=' + y)
                        })
                        // ดึงรายชื่อผู้ขาย
                        .on('click', '#openSellerList', function() {
                            let x = screen.width / 2 - 800 / 2
                            let y = screen.height / 2 - 600 / 2

                            let configParam = $.param({
                                'actionBy': 'MAIN',
                                'formKey': $FORM_KEY
                            })

                            let url = `${serverUrl}/document/listSeller?${configParam}`
                            window.open(url, 'DF', 'menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no height=600,width=800,left=' + x + ',top=' + y)
                        })
                        // สั่งพิมพ์
                        // .on('click', '.btnPrint', function() {
                        //     let $me = $(this),
                        //         $id = $me.data('doc-id')

                        //     let url = `${serverUrl}/document/print/${$id}`
                        //     window.open(url, 'Print', 'menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=994,height=768,top=20,left=20')
                        // })
                        .on('click', '.btnPrint', function() {
                            let $me = $(this),
                                $id = $me.data('doc-id')
                            $type = $me.data('doc-type-')
                            if ($type == 'ใบสำคัญรับ') {
                                let url = `${serverUrl}/pdf_document/` + $id;
                                window.open(
                                    url,
                                    "Doc",
                                    "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
                                );
                            } else if ($type == 'ใบสำคัญจ่าย') {
                                let url = `${serverUrl}/pdf_doc_pay/` + $id;
                                window.open(
                                    url,
                                    "Doc",
                                    "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
                                );
                            } else if ($type == 'ใบส่วนลด') {
                                let url = `${serverUrl}/pdf_doc_rebate/` + $id;
                                window.open(
                                    url,
                                    "Doc",
                                    "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
                                );
                            } else {
                                let url = `${serverUrl}/pdf_doc_expenses/` + $id;
                                window.open(
                                    url,
                                    "Doc",
                                    "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
                                );
                            }
                        });

                    $form
                        // บันทึกข้อมูล
                        .on('click', '.btnSave', function(e) {
                            e.preventDefault()

                            if ($form.find('input[name=doc_date]').val() == '') {
                                alert('กรุณาเลือกวันที่')
                                return false
                            }

                            if ($form.find('input[name=title]').val() == '') {
                                alert('กรุณาเลือกรายการ')
                                return false
                            }

                            if ($form.find('input[name=price]').val() == '') {
                                alert('กรุณาเลือกระบุจำนวนเงิน')
                                return false
                            }

                            let $inputCar = false
                            if ($('input[name=doc_type]').val() == 'ใบสำคัญรับ' || $('input[name=doc_type]').val() == 'ใบสำคัญจ่าย' || $('input[name=doc_type]').val() == 'ใบส่วนลด') {
                                if ($form.find('input[name=car_title]').val() == '') {
                                    alert('กรุณาเลือกรถ')
                                    return false
                                } else {
                                    $inputCar = true
                                }
                            }
                            // else if ($('input[name=doc_type]').val() == 'อื่น ๆ') {
                            //     $inputCar = true
                            // }

                            if ($form.find('select[name=doc_payment_type]').val() == '') {
                                alert('กรุณาเลือกประเภท')
                                return false
                            }

                            let $inputClient = false
                            let $docType = $("input[name=doc_type]").val();
                            if ($('input[name=doc_type]').val() == 'ใบสำคัญรับ') {
                                if ($form.find('input[name=customer_title]').val() == '') {
                                    alert('กรุณาเลือกลูกค้า')
                                    return false
                                } else {
                                    $inputClient = true
                                }
                            } else if ($('input[name=doc_type]').val() == 'ใบสำคัญจ่าย') {
                                if ($form.find('input[name=seller_title]').val() == '') {
                                    alert('กรุณาเลือกผู้ขาย')
                                    return false
                                } else {
                                    $inputClient = true
                                }
                            } else if ($('input[name=doc_type]').val() == 'ใบส่วนลด') {
                                if ($form.find('input[name=customer_title]').val() == '') {
                                    alert('กรุณาเลือกลูกค้า')
                                    return false
                                } else {
                                    $inputClient = true
                                }
                            }
                            // else if ($('input[name=doc_type]').val() == 'อื่น ๆ') {
                            //     $inputClient = true
                            // }

                            // ผ่าน
                            if (
                                $form.find('input[name=doc_date]').val() != '' &&
                                $form.find('input[name=title]').val() != '' &&
                                $form.find('input[name=price]').val() != '' &&
                                $inputCar &&
                                $inputClient &&
                                $form.find('select[name=doc_payment_type]').val() != ''
                            ) {

                                let $me = $(this)

                                $me.attr('disabled', true)

                                let formData = new FormData($form[0])
                                let imageFileInvoice = document.querySelector("#imageFileInvoice");

                                if (imageFileInvoice.files.length > 0) {
                                    formData.append("imageFileInvoice", imageFileInvoice.files[0]);
                                }
                                let $url = ''
                                if ($form.find('input[name=doc_id]').val() != '') {
                                    $url = `${serverUrl}/document/update`
                                } else {
                                    $url = `${serverUrl}/document/store`
                                }

                                $.ajax({
                                    type: "POST",
                                    url: $url,
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                }).done(function(res) {

                                    if (res.success) {

                                        Swal.fire({
                                            text: res.message,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "ตกลง",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        }).then(function(result) {
                                            if (result.isConfirmed) {

                                                LIST_DOC.docTable()

                                                let $actionBy = $('input[name=actionBy]').val()

                                                switch ($actionBy) {
                                                    case 'EDIT_BOOKING':

                                                        let $formKey = $('input[name=formKey]').val()

                                                        let $opener = opener.document.getElementById($formKey)

                                                        $($opener).find('.btnReloadReceipt').trigger('click')
                                                        self.close()

                                                        break
                                                }

                                                switch ($actionBy) {
                                                    case 'EDIT_STOCK_CAR_DETAIL':

                                                        // let $formKey = $('input[name=formKey]').val()

                                                        let $opener = opener.document.getElementById('editStock')

                                                        $($opener).find('.btnReloadStockCarDetail').trigger('click')
                                                        self.close()

                                                        break
                                                }
                                            }
                                        })

                                        $me.attr('disabled', false)
                                        LIST_DOC.docFormClear()
                                        LIST_DOC.docOpenForm($docType)
                                    }

                                    // กรณี: Server มีการตอบกลับ แต่ไม่สำเร็จ
                                    else {
                                        // Show error message.
                                        Swal.fire({
                                            text: res.message,
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "ตกลง",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        }).then(function(result) {
                                            if (result.isConfirmed) {
                                                // LANDING_KNOWLEDGE.reloadPage()
                                            }
                                        })

                                        $me.attr('disabled', false)
                                    }

                                }).fail(function(context) {
                                    let messages = context.responseJSON?.messages || 'ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ'
                                    // Show error message.
                                    Swal.fire({
                                        text: messages,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "ตกลง",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    })

                                    $me.attr('disabled', false)
                                })
                            }
                        })
                        // เลือกประเภทเงิน
                        .on('change', 'select[name=doc_payment_type]', function() {

                            let $me = $(this)

                            if ($me.val() == 'เงินสด') {
                                $("#wrapperDocPaymentTypeEtc").hide()
                                $("#wrapperACForm").hide()
                            } else if ($me.val() == 'A/C') {
                                $("#wrapperACForm").show()
                                $("#wrapperDocPaymentTypeEtc").hide()
                            } else if ($me.val() == 'อื่น ๆ') {
                                $("#wrapperACForm").hide()
                                $("#wrapperDocPaymentTypeEtc").show()
                            }
                        })
                },

                tableDocCarFilter() {
                    flatpickr("#daterange_doc_car", {
                        mode: "range",
                        dateFormat: "Y-m-d",
                        onChange: function(selectedDates) {
                            LIST_DOC.docTable();
                        },
                    });
                },
                // SET UP
                init() {
                    LIST_DOC.handleDocForm()
                    LIST_DOC.tableDocCarFilter()

                    let $oldDocType = $('#docModal').find('input[name=oldDocType]').val()
                    let $docID = $('#docModal').find('input[name=oldDocID]').val()

                    // กรณีเป็นการแก้ไขข้อมูล หลังจากเปิด New Windows จะไปดึงข้อมูลเก่า
                    if ($docID != '') {

                        let $modalDoc = $("#docModal")
                        let $form = $modalDoc.find('form')

                        $.ajax({
                            type: "GET",
                            url: `${serverUrl}/document/edit/${$docID}`,
                            contentType: "application/json; charset=utf-8"
                        }).done(function(res) {
                            if (res.success == 1) {

                                $("#docModal").animate({
                                    scrollTop: 100
                                }, 'slow')

                                let $data = res.data

                                LIST_DOC.docOpenForm($data.doc_type)

                                let $docPaymentType = $data.doc_payment_type,
                                    $docPrice = $data.price,
                                    $cash_flow_name = $data.cash_flow_name

                                $form.find('input[name=doc_id]').val($data.id)
                                $form.find('input[name=doc_type]').val($data.doc_type)
                                $form.find('input[name=doc_number]').val($data.doc_number)
                                $("#formDate").flatpickr()
                                $form.find('input[name=doc_date]').val($data.doc_date)
                                $form.find('input[name=title]').val($data.title)
                                $form.find('input[name=price]').val($docPrice)
                                easyNumberSeparator({
                                    selector: '.price',
                                    separator: ','
                                })
                                $form.find('input[name=car_stock_id]').val($data.car_stock_id)
                                $form.find('input[name=car_title]').val($data.car_title)

                                $form.find('select[name=doc_payment_type]').val($docPaymentType)
                                $form.find('select[name=cash_flow_name]').val($cash_flow_name)

                                $form.find('input[name=doc_payment_type_etc]').val($data.doc_payment_type_etc)
                                $form.find('input[name=cheque_name]').val($data.cheque_name)
                                $form.find('input[name=cheque_ref]').val($data.cheque_ref)
                                $form.find('input[name=cheque_bank_title]').val($data.cheque_bank_title)
                                $form.find('input[name=cheque_bank_branch]').val($data.cheque_bank_branch)
                                $form.find('input[name=cheque_bank_no]').val($data.cheque_bank_no)
                                $("#chequeBankDate").flatpickr()
                                $form.find('input[name=cheque_bank_date]').val($data.cheque_bank_date)
                                $form.find('input[name=note]').val($data.note)
                                $form.find('input[name=doc_detail]').val($data.doc_detail)

                                if ($data.doc_type == 'ใบสำคัญจ่าย') {
                                    $form.find('input[name=customer_id]').val(0)
                                    $form.find('input[name=customer_title]').val('')
                                    $form.find('input[name=seller_id]').val($data.seller_id)
                                    $form.find('input[name=seller_title]').val($data.seller_title)

                                    if ($data.filePath != '') {
                                        $form.find('.dropify-preview').css('display', 'block')
                                        $form.find('.dropify-render').html('<img src="' + `${CDN_IMG}/uploads/file/${$data.filePath}` + '" alt="">')
                                    }

                                } else {
                                    $form.find('input[name=customer_id]').val($data.customer_id)
                                    $form.find('input[name=customer_title]').val($data.customer_title)
                                    $form.find('input[name=seller_id]').val(0)
                                    $form.find('input[name=seller_title]').val('')
                                }

                                switch ($docPaymentType) {
                                    case 'เงินสด':
                                        $("#wrapperDocPaymentTypeEtc").hide()
                                        $("#wrapperACForm").hide()
                                        break

                                    case 'A/C':
                                        $("#wrapperACForm").show()
                                        $("#wrapperDocPaymentTypeEtc").hide()
                                        break

                                    case 'อื่น ๆ':
                                        $("#wrapperACForm").hide()
                                        $("#wrapperDocPaymentTypeEtc").show()
                                        break
                                }

                                $form.find('.btnSave').html('ยืนยันแก้ไขข้อมูล')
                            }
                        })
                    }

                    // ปกติ
                    else {
                        LIST_DOC.docSetEnvForm()
                        LIST_DOC.docOpenForm($oldDocType)
                    }

                }
            }

            LIST_DOC.init()
            $("#note").keyup(function() {
                let searchText = $(this).val();
                let $tab = $(".tabDocType a.active");
                let $docType = $tab.data('doc-type');
                if (searchText != "") {
                    $.ajax({
                        url: `/document/search`,
                        method: "POST",
                        data: {
                            query: searchText,
                            type: $docType
                        },
                        success: function(response) {
                            $("#show-list").html(response);
                        }
                    })
                } else {
                    $("#show-list").html("");
                }
                $(document).on('click', 'a', function() {
                    $("#note").val($(this).text())
                    $("#show-list").html("");
                })
            })
            // เมื่อคลิกปุ่ม 'ใช้ AI Auto Input'
            $("#btnAiAutoInputInvoice").on("click", function() {
                $("#imageFileInvoice").click(); // เปิด Dialog เลือกไฟล์
            });

            // เมื่อเลือกไฟล์ใน input
            $("#imageFileInvoice").on("change", function() {
                const fileInvoice = this.files[0]; // รับไฟล์ที่เลือก
                if (!fileInvoice) return; // ถ้าไม่มีไฟล์ ให้ return ออกไปเลย

                const fileType = fileInvoice.type;
                $("#detectImageFormInvoice").show(); // แสดงส่วนแสดงตัวอย่างและปุ่ม Submit

                // ตรวจสอบประเภทไฟล์และแสดงตัวอย่าง
                if (fileType === "application/pdf") {
                    const fileURL = URL.createObjectURL(fileInvoice);
                    $("#pdfPreviewInvoice").attr("src", fileURL).show(); // แสดง PDF
                    $("#imagePreviewInvoice").hide();
                } else {
                    const readerInvoice = new FileReader();
                    readerInvoice.onload = function(e) {
                        $("#imagePreviewInvoice").attr("src", e.target.result).show(); // แสดงภาพ
                    };
                    readerInvoice.readAsDataURL(fileInvoice);
                    $("#pdfPreviewInvoice").hide();
                }
            });

            function formatDateImg(dateString) {
                if (dateString.includes("-")) {
                    let parts = dateString.split("-");
                    if (parts.length === 3) {
                        let day = parts[0].padStart(2, "0");
                        let month = parts[1].padStart(2, "0");
                        let year = parts[2];

                        // ถ้าปีเป็น 4 หลัก แสดงว่ามันอยู่ในฟอร์แมต dd-mm-yyyy → ต้องแปลง
                        if (year.length === 4) {
                            return `${year}-${month}-${day}`;
                        }
                    }
                }

                if (dateString.includes("/")) {
                    let parts = dateString.split("/");
                    if (parts.length === 3) {
                        let day = parts[0].padStart(2, "0");
                        let month = parts[1].padStart(2, "0");
                        let year = parts[2];

                        return `${year}-${month}-${day}`;
                    }
                }

                return dateString;
            }

            // เมื่อกดปุ่ม Submit เพื่ออัปโหลดไฟล์
            $("#btnAiAutoInputInvoiceSubmit").on("click", function() {
                let fileInput = document.querySelector("#imageFileInvoice");
                if (!fileInput.files.length) return; // ถ้ายังไม่ได้เลือกไฟล์ ให้ return ออกไปเลย

                let fileInvoice = fileInput.files[0];
                let formData = new FormData();
                formData.append("image", fileInvoice);

                // แสดง loading
                $("#btnAiAutoInputInvoiceSubmit")
                    .prop("disabled", false)
                    .html('<i class="fa fa-spinner fa-spin"></i> กำลังประมวลผล');

                // ส่งข้อมูลด้วย AJAX
                $.ajax({
                    url: "/document/ocrInvoice", // URL ของ API
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $("#btnAiAutoInputInvoiceSubmit")
                            .prop("disabled", false)
                            .html("ยืนยัน");

                        if (response.status === "success") {
                            $("#detectImageFormInvoice").hide(); // ซ่อนฟอร์มเมื่ออัปโหลดเสร็จ

                            if (response.json_output) {
                                let jsonData = response.json_output;

                                let amount_usd = 0;

                                let amount = jsonData.amount.toString().replace(/,/g, ""); // ลบจุลภาค
                                amount = parseFloat(amount); // แปลงเป็นตัวเลข

                                if (jsonData.type === "THB") {
                                    amount_usd = (amount / 34.615).toFixed(2);
                                } else if (jsonData.type === "LAK") {
                                    amount_usd = (amount / 21745).toFixed(2);
                                } else {
                                    amount_usd = amount.toFixed(2);
                                }

                                // แปลงวันที่ก่อนที่จะนำไปใส่ใน input
                                let formattedDateImg = formatDateImg(jsonData.date);
                                console.log(formattedDateImg);
                                // เติมข้อมูลลงใน input
                                $("input[name=price]").val(amount_usd).addClass("is-valid");
                                $("input[name=doc_date]")
                                    .val(formattedDateImg)
                                    .addClass("is-valid");
                            }
                        }
                    },
                    error: function() {
                        $("#btnAiAutoInputInvoiceSubmit")
                            .prop("disabled", false)
                            .html("ยืนยัน");
                    },
                });
            });

            // เมื่อคลิกปุ่ม 'ยกเลิก'
            $("#btnAiAutoInputInvoiceClear").on("click", function() {
                $("#detectImageFormInvoice").hide(); // ซ่อนฟอร์ม
                $("#imageFileInvoice").val(""); // รีเซ็ต input file
                $("#imagePreviewInvoice").attr("src", "").hide(); // ซ่อนภาพ preview
                $("#pdfPreviewInvoice").attr("src", "").hide(); // ซ่อน PDF preview
            });
        })
        $("#checkbox_wht").on("change", function() {
            $("#open_wht").toggleClass("show");
        });

        var price_vat = $("#price_vat");
        price_vat.on("change", function() {
            var vat_type = $("#price_vat").val();
            if (vat_type == "ราคารวมภาษี") {
                $("#checkbox_price_vat").removeClass("show");
            } else {
                $("#checkbox_price_vat").addClass("show");
            }
        });
    </script>
</body>

</html>