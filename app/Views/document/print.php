<!doctype html>
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

    <!--  Bootstrap css-->
    <link id="style" href="<?php echo base_url('/assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" />

    <!-- Style css -->
    <link href="<?php echo base_url('/assets/css/style.css'); ?>" rel="stylesheet">

    <!-- Plugins css -->
    <link href="<?php echo base_url('/assets/css/plugins.css'); ?>" rel="stylesheet">

    <!-- Switcher css -->
    <link href="<?php echo base_url('/assets/switcher/css/switcher.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('/assets/switcher/styles.css'); ?>" rel="stylesheet" />

    <link href="<?php echo base_url('/assets/app/css/app.css'); ?>" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
</head>

<body class="ltr main-body app sidebar-mini">

    <!-- Progress bar on scroll -->
    <div class="progress-top-bar"></div>

    <!-- Loader -->
    <div id="global-loader">
        <img src="<?php echo base_url('/assets/img/loader.svg'); ?>" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->

    <div style="width: 760px; !important;" class="card container">
        <?php foreach (getDocumentSetUp() as $DocumentSetUp); ?>

        <!-- ตัวจริง -->
        <div class="card-body">
            <div class="d-lg-flex">
                <div class="d-flex">
                    <a><img src="<?php echo base_url('/uploads/img/' . $website->logo); ?>" class="sign-favicon ht-40" alt="logo"></a>
                    <!--                                    <div class="mb-4 ms-2">-->
                    <!--                                        <h2 class="mb-0">Zem</h2>-->
                    <!--                                        <span class="tx-muted">zembt5@gmail.com</span>-->
                    <!--                                    </div>-->
                </div>
                <div class="ms-auto">
                    <?php try{ ?>
                        <?php $backup_number = '' ?>
                        <?php if ($DocumentSetUp->set_up_backup_number != '') {
                            $backup_number = ' , ' . $DocumentSetUp->set_up_backup_number;
                        }
                        ?>
                        <address class="tx-muted text-end">
                            <?php echo $DocumentSetUp->set_up_name ?><br>
                            ที่อยู่ <?php echo $DocumentSetUp->set_up_address ?><br>
                            โทร.<?php echo $DocumentSetUp->set_up_phone_number ?><?php echo $backup_number ?> เลขประจำตัวผู้เสียภาษี <?php echo $DocumentSetUp->set_up_taxpayer_number ?>
                        </address>
                    <?php } catch (Exception $e) {
                    } ?>
                </div>
            </div>
            <div class="invoice-highlight">
                <div class="row row-sm p-3">
                    <div class="col-lg-6">
                        <p class="h5 mb-3"><?php echo $docTitle; ?> :</p>
                        <p class="mb-2">#<?php echo $document->doc_number; ?></p>
                        <p class="mb-2">วันที่ <span class="op-8"><?php echo $document->doc_date; ?></span></p>
                    </div>
                    <div class="col-lg-6 text-end">
                        <p class="h5">ถึง :</p>
                        <address class="mb-0">
                            <?php echo isset($client->fullname) ? $client->fullname : ''; ?><br>
                            <?php echo isset($client->address) ? $client->address : ''; ?><br>
                            <?php echo isset($client->phone) ? $client->phone : ''; ?><br>
                        </address>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 tx-center">ยี่ห้อ</div>
                    <div class="col-md-2 tx-center">เลขทะเบียน</div>
                    <div class="col-md-2 tx-center">ทะเบียนจังหวัด</div>
                    <div class="col-md-2 tx-center">หมายเลขเครื่อง</div>
                    <div class="col-md-3 tx-center">หมายเลขตัวถัง</div>
                </div>
                <div class="row mt-2 mb-3">
                    <div class="col-md-3 tx-center"><?php echo $document->car_stock_owner_car_brand . " " . $document->car_stock_owner_car_sub_model ?></div>
                    <div class="col-md-2 tx-center"><?php echo $document->car_stock_car_vin ?></div>
                    <div class="col-md-2 tx-center"><?php echo $document->car_stock_car_province ?></div>
                    <div class="col-md-2 tx-center"><?php echo $document->car_stock_car_engine_number ?></div>
                    <div class="col-md-3 tx-center"><?php echo $document->car_stock_tank_number ?></div>
                </div>
                <!-- <div class="row ms-2 mb-2">
                    <?php echo "ยี่ห้อ: " . $document->car_stock_owner_car_brand . " " . $document->car_stock_owner_car_sub_model . " เลขทะเบียน: " . $document->car_stock_car_vin . " ทะเบียนจังหวัด: " . $document->car_stock_car_province . " หมายเลขเครื่อง: " . $document->car_stock_car_engine_number . " หมายเลขตัวถัง: " . $document->car_stock_tank_number ?>
                </div> -->
                <!-- <div class="row">
                    <table class="table mb-0 border-0">
                        <thead style="color: black;">
                            <tr>
                                <th class="tx-center">ยี่ห้อ</th>
                                <th class="tx-center">เลขทะเบียน</th>
                                <th class="tx-center">ทะเบียนจังหวัด</th>
                                <th class="tx-center">หมายเลขเครื่อง</th>
                                <th class="tx-center">หมายเลขตัวถัง</th>
                            </tr>
                        </thead>
                        <tbody style="color: black;">
                            <tr>
                                <td class="tx-center"><?php echo $document->car_stock_owner_car_brand . " " . $document->car_stock_owner_car_sub_model ?></td>
                                <td class="tx-center"><?php echo $document->car_stock_car_vin ?></td>
                                <td class="tx-center"><?php echo $document->car_stock_car_province ?></td>
                                <td class="tx-center"><?php echo $document->car_stock_car_engine_number ?></td>
                                <td class="tx-center"><?php echo $document->car_stock_tank_number ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div> -->
            </div>
            <div class="table-responsive border radius-4 mg-t-30">
                <table class="table table-invoice mb-0 border-0">
                    <thead>
                        <tr>
                            <th class="wd-20p">รายการ</th>
                            <th class="wd-40p">รายละเอียด</th>
                            <th class="tx-center"></th>
                            <th class="tx-right">จำนวนเงิน (บาท)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <php $Tax = $document->price * 0.07;
                        $total_money = $document->price + $Tax; ?> -->
                        <tr>
                            <td><?php echo $document->title; ?></td>
                            <td class="tx-12"><?php echo $document->car_title; ?></td>
                            <td></td>
                            <td class="tx-right"><?php echo number_format($document->price, 2); ?></td>
                        </tr>
                        <!-- <tr>

                            <td class="valign-middle text-center" colspan="2" rowspan="1">
                            </td>
                            <td class="tx-right">จำนวนภาษีมูลค่าเพิ่ม</td>
                            <td class="tx-right" colspan="2"><php echo number_format($Tax, 2); ?></td>
                        </tr> -->
                        <tr>
                            <td class="valign-middle text-center" colspan="2" rowspan="1">
                                (<?php echo numToThaiBath(number_format($document->price, 2)); ?>)
                            </td>
                            <td class="tx-right">จำนวนเงินรวมทั้งสิ้น</td>
                            <td class="tx-right" colspan="2"><?php echo number_format($document->price, 2); ?></td>
                        </tr>
                        <!--                                    <tr>-->
                        <!--                                        <td class="tx-right">ภาษี</td>-->
                        <!--                                        <td class="tx-right" colspan="2">7%</td>-->
                        <!--                                    </tr>-->
                        <!--                                    <tr>-->
                        <!--                                        <td class="tx-right">ส่วนลด</td>-->
                        <!--                                        <td class="tx-right" colspan="2">10%</td>-->
                        <!--                                    </tr>-->
                        <!--                                    <tr>-->
                        <!--                                        <td class="tx-right tx-uppercase tx-bold tx-inverse">รวมทั้งหมด</td>-->
                        <!--                                        <td class="tx-right" colspan="2">-->
                        <!--                                            <h4 class="tx-bold mb-0">--><?php //echo number_format($document->price, 2); 
                                                                                                    ?>
                        <!--</h4>-->
                        <!--                                        </td>-->
                        <!--                                    </tr>-->
                    </tbody>
                </table>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    รับ
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="ckbox"><input type="checkbox"> <span class="tx-13">เงินสด</span> </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="ckbox"><input type="checkbox"> <span class="tx-13">A/C</span> </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="ckbox"><input type="checkbox"> <span class="tx-13">อื่น ๆ ระบุ ...............................</span> </label>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="form-group"> <label class="ckbox"> <input type="checkbox"> <span class="tx-13">รับเช็คในนาม ....................................................................................................</span> </label> </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-3">ธนาคาร ...............................................</div>
                <div class="col-md-3">สาขา .....................................................</div>
                <div class="col-md-3">เลขที่ .....................................................</div>
                <div class="col-md-3">วันที่ .......................................................</div>
            </div>

            <div class="row mt-5">
                <div class="col-md-3 text-center">
                    ____________________ <br>
                    ผู้จ่ายเงิน
                </div>
                <div class="col-md-3 text-center">
                    ____________________ <br>
                    ผู้จัดทำ
                </div>
                <div class="col-md-3 text-center">
                    ____________________ <br>
                    ผู้รับเงิน
                </div>
                <div class="col-md-3 text-center">
                    ____________________ <br>
                    ผู้มีอำนาจลงนาม
                </div>
            </div>
        </div>

        <!-- สำเนา -->
        <!-- <div class="card-body">
            <div class="d-lg-flex">
                <div class="d-flex">
                    <a><img src="<?php echo base_url('/uploads/img/' . $website->logo); ?>" class="sign-favicon ht-40" alt="logo"></a>
                </div>
                <div class="ms-auto">
                    <?php try { ?>
                        <address class="tx-muted text-end">
                            <?php echo $DocumentSetUp->set_up_name ?><br>
                            ที่อยู่ <?php echo $DocumentSetUp->set_up_address ?><br>
                            โทร.<?php echo $DocumentSetUp->set_up_phone_number ?><?php echo $backup_number ?> เลขประจำตัวผู้เสียภาษี <?php echo $DocumentSetUp->set_up_taxpayer_number ?>
                        </address>
                    <?php } catch (Exception $e) {
                    } ?>
                </div>
            </div>
            <div class="invoice-highlight">
                <div class="row row-sm ms-3 mt-2 mb-2 me-3">
                    <div class="col-lg-6">
                        <p class="h5 mb-2"><?php echo $docTitle; ?> :</p>
                        <p class="mb-2">#<?php echo $document->doc_number; ?></p>
                        <p class="mb-2">วันที่ <span class="op-8"><?php echo $document->doc_date; ?></span></p>
                    </div>
                    <div class="col-lg-6 text-end">
                        <p class="h5">ถึง :</p>
                        <address class="mb-0">
                            <?php echo isset($client->fullname) ? $client->fullname : ''; ?><br>
                            <?php echo isset($client->address) ? $client->address : ''; ?><br>
                            <?php echo isset($client->phone) ? $client->phone : ''; ?><br>
                        </address>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 tx-center">ยี่ห้อ</div>
                    <div class="col-md-2 tx-center">เลขทะเบียน</div>
                    <div class="col-md-2 tx-center">ทะเบียนจังหวัด</div>
                    <div class="col-md-2 tx-center">หมายเลขเครื่อง</div>
                    <div class="col-md-3 tx-center">หมายเลขตัวถัง</div>
                </div>
                <div class="row mt-1 mb-1">
                    <div class="col-md-3 tx-center"><?php echo $document->car_stock_owner_car_brand . " " . $document->car_stock_owner_car_sub_model ?></div>
                    <div class="col-md-2 tx-center"><?php echo $document->car_stock_car_vin ?></div>
                    <div class="col-md-2 tx-center"><?php echo $document->car_stock_car_province ?></div>
                    <div class="col-md-2 tx-center"><?php echo $document->car_stock_car_engine_number ?></div>
                    <div class="col-md-3 tx-center"><?php echo $document->car_stock_tank_number ?></div>
                </div>
            </div>
            <div class="table-responsive border radius-4 mg-t-30">
                <table class="table table-invoice mb-0 border-0">
                    <thead>
                        <tr>
                            <th class="wd-20p">รายการ</th>
                            <th class="wd-40p">รายละเอียด</th>
                            <th class="tx-center"></th>
                            <th class="tx-right">จำนวนเงิน (บาท)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $document->title; ?></td>
                            <td class="tx-12"><?php echo $document->car_title; ?></td>
                            <td></td>
                            <td class="tx-right"><?php echo number_format($document->price, 2); ?></td>
                        </tr>
                        <tr>

                            <td class="valign-middle text-center" colspan="2" rowspan="1">
                            </td>
                            <td class="tx-right">จำนวนภาษีมูลค่าเพิ่ม</td>
                            <td class="tx-right" colspan="2"><php echo number_format($Tax, 2); ?></td>
                        </tr>
                        <tr>
                            <td class="valign-middle text-center" colspan="2" rowspan="1">
                                (<php echo numToThaiBath(number_format($total_money, 2)); ?>)
                            </td>
                            <td class="tx-right">จำนวนเงินรวมทั้งสิ้น</td>
                            <td class="tx-right" colspan="2"><php echo number_format($total_money, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    รับ
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="ckbox"><input type="checkbox"> <span class="tx-13">เงินสด</span> </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="ckbox"><input type="checkbox"> <span class="tx-13">A/C</span> </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="ckbox"><input type="checkbox"> <span class="tx-13">อื่น ๆ ระบุ ...............................</span> </label>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="form-group"> <label class="ckbox"> <input type="checkbox"> <span class="tx-13">รับเช็คในนาม ....................................................................................................</span> </label> </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-3">ธนาคาร ...............................................</div>
                <div class="col-md-3">สาขา .....................................................</div>
                <div class="col-md-3">เลขที่ .....................................................</div>
                <div class="col-md-3">วันที่ .......................................................</div>
            </div>

            <div class="row mt-5">
                <div class="col-md-3 text-center">
                    ____________________ <br>
                    ผู้จ่ายเงิน
                </div>
                <div class="col-md-3 text-center">
                    ____________________ <br>
                    ผู้จัดทำ
                </div>
                <div class="col-md-3 text-center">
                    ____________________ <br>
                    ผู้รับเงิน
                </div>
                <div class="col-md-3 text-center">
                    ____________________ <br>
                    ผู้มีอำนาจลงนาม
                </div>
            </div>
        </div> -->

        <style>
            .css-1xamfmm,
            [data-css-1xamfmm] {
                display: -webkit-box;
                display: -moz-box;
                display: -ms-flexbox;
                display: -webkit-flex;
                display: flex;
                justify-content: center;
                align-items: center;
                width: 56px;
                height: 56px;
                background-color: #fff;
                color: #000;
                position: fixed;
                /* left: 50%; */
                top: 8%;
                /* top: 50%; */
                right: 0px;
                cursor: pointer;
                transform: translate(-50%, -50%);
                border-radius: 50%;
                visibility: visible;
                box-shadow: 0 2px 2px 0 rgb(0 0 0 / 14%), 0 1px 5px 0 rgb(0 0 0 / 12%), 0 3px 1px -2px rgb(0 0 0 / 20%);
                -webkit-box-pack: center;
                -webkit-justify-content: center;
                -webkit-box-align: center;
                -webkit-align-items: center;
                -webkit-transform: translate(-50%, -50%);
                box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
            }
        </style>
        <div id="tryhtml2canvas" class="css-1xamfmm btnPrint" onclick="printDoc()">
            <i class="fe fe-printer me-1"></i> พิมพ์
        </div>
    </div>

    <!-- JQuery min js -->
    <script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap js -->
    <script src="<?php echo base_url('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

    <!-- custom-switcher js -->
    <script src="<?php echo base_url('/assets/js/custom-switcher.js'); ?>"></script>

    <!-- custom js -->
    <script src="<?php echo base_url('/assets/js/custom.js'); ?>"></script>

    <script src="<?php echo base_url('/assets/switcher/js/switcher.js'); ?>"></script>
    <script>
        function printDoc() {
            $(".btnPrint").hide()
            print()
            $(".btnPrint").show()
        }
    </script>
</body>

</html>