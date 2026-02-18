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
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <?php
                                            $Month_Jan_Loan = 0;
                                            $Month_Feb_Loan = 0;
                                            $Month_Mar_Loan = 0;
                                            $Month_Apr_Loan = 0;
                                            $Month_May_Loan = 0;
                                            $Month_Jun_Loan = 0;
                                            $Month_Jul_Loan = 0;
                                            $Month_Aug_Loan = 0;
                                            $Month_Sep_Loan = 0;
                                            $Month_Oct_Loan = 0;
                                            $Month_Nov_Loan = 0;
                                            $Month_Dec_Loan = 0;

                                            foreach ($OpenLoanMonths as $OpenLoanMonth) {
                                                switch ($OpenLoanMonth->loan_month) {
                                                    case "1":
                                                        $Month_Jan_Loan = $Month_Jan_Loan + $OpenLoanMonth->loan_summary_no_vat;
                                                        break;
                                                    case "2":
                                                        $Month_Feb_Loan = $Month_Feb_Loan + $OpenLoanMonth->loan_summary_no_vat;
                                                        break;
                                                    case "3":
                                                        $Month_Mar_Loan = $Month_Mar_Loan + $OpenLoanMonth->loan_summary_no_vat;
                                                        break;
                                                    case "4":
                                                        $Month_Apr_Loan = $Month_Apr_Loan + $OpenLoanMonth->loan_summary_no_vat;
                                                        break;
                                                    case "5":
                                                        $Month_May_Loan = $Month_May_Loan + $OpenLoanMonth->loan_summary_no_vat;
                                                        break;
                                                    case "6":
                                                        $Month_Jun_Loan = $Month_Jun_Loan + $OpenLoanMonth->loan_summary_no_vat;
                                                        break;
                                                    case "7":
                                                        $Month_Jul_Loan = $Month_Jul_Loan + $OpenLoanMonth->loan_summary_no_vat;
                                                        break;
                                                    case "8":
                                                        $Month_Aug_Loan = $Month_Aug_Loan + $OpenLoanMonth->loan_summary_no_vat;
                                                        break;
                                                    case "9":
                                                        $Month_Sep_Loan = $Month_Sep_Loan + $OpenLoanMonth->loan_summary_no_vat;
                                                        break;
                                                    case "10":
                                                        $Month_Oct_Loan = $Month_Oct_Loan + $OpenLoanMonth->loan_summary_no_vat;
                                                        break;
                                                    case "11":
                                                        $Month_Nov_Loan = $Month_Nov_Loan + $OpenLoanMonth->loan_summary_no_vat;
                                                        break;
                                                    case "12":
                                                        $Month_Dec_Loan = $Month_Dec_Loan + $OpenLoanMonth->loan_summary_no_vat;
                                                        break;
                                                }
                                            }

                                            $total_open_loan_month = 0;
                                            switch (date('m')) {
                                                case "1":
                                                    $total_open_loan_month = $Month_Jan_Loan;
                                                    break;
                                                case "2":
                                                    $total_open_loan_month = $Month_Feb_Loan;
                                                    break;
                                                case "3":
                                                    $total_open_loan_month = $Month_Mar_Loan;
                                                    break;
                                                case "4":
                                                    $total_open_loan_month = $Month_Apr_Loan;
                                                    break;
                                                case "5":
                                                    $total_open_loan_month = $Month_May_Loan;
                                                    break;
                                                case "6":
                                                    $total_open_loan_month = $Month_Jun_Loan;
                                                    break;
                                                case "7":
                                                    $total_open_loan_month = $Month_Jul_Loan;
                                                    break;
                                                case "8":
                                                    $total_open_loan_month = $Month_Aug_Loan;
                                                    break;
                                                case "9":
                                                    $total_open_loan_month = $Month_Sep_Loan;
                                                    break;
                                                case "10":
                                                    $total_open_loan_month = $Month_Oct_Loan;
                                                    break;
                                                case "11":
                                                    $total_open_loan_month = $Month_Nov_Loan;
                                                    break;
                                                case "12":
                                                    $total_open_loan_month = $Month_Dec_Loan;
                                                    break;
                                            }
                                            ?>

                                            <div class="col-md-12 text-center">
                                                <div align="center">
                                                    <div class="rounded-circle ht-60 wd-60 bg-light bg-light d-flex align-items-center justify-content-center ">
                                                        <div class="ht-50 wd-50 rounded-circle bg-primary d-flex align-items-center justify-content-center"> <i class="fa fa-book tx-17 text-white"></i> </div>
                                                    </div>
                                                </div>
                                                <h4 class="tx-18 font-weight-semibold my-1">ยอดเปิดสินเชื่อ <?php echo number_format($total_open_loan_month, 2) ?></h4>
                                                <h4 class="tx-18 font-weight-semibold my-1">(ยอดเปิดสินเชื่อเดือนปัจจุบัน)</h4>
                                                <?php $Sum_Open_Loan_percent = $total_open_loan_month * 100 / $targeteds->open_loan_target; ?>
                                                <?php if ($Sum_Open_Loan_percent < 0) {
                                                    $open_Loan_Color = "bg-danger-transparent tx-danger";
                                                    $open_Loan_profit = number_format($Sum_Open_Loan_percent, 2) . '%';
                                                } elseif ($Sum_Open_Loan_percent > 0) {
                                                    $open_Loan_Color = "bg-success-transparent tx-success";
                                                    $open_Loan_profit = '+' . number_format($Sum_Open_Loan_percent, 2) . '%';
                                                } else {
                                                    $open_Loan_Color = "";
                                                    $open_Loan_profit = number_format($Sum_Open_Loan_percent, 2) . '%';
                                                } ?>
                                                <p class="tx-14 mb-1"><span class="<?php echo $open_Loan_Color ?>"><?php echo $open_Loan_profit ?></span> of target</p>
                                                <p class="tx-13 text-start" style="margin-bottom: 8px;">เป้าหมาย ( <?php echo number_format($total_open_loan_month, 2) ?> / <?php echo number_format($targeteds->open_loan_target, 2) ?>)</p>
                                                <div class="progress progress-style ht-5 mt-2">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="78" style="width:<?php echo number_format($Sum_Open_Loan_percent, 2) . "%" ?>"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            // เตรียม array 12 เดือน ค่าเริ่มต้น = 0
                            $MonthProfit = array_fill(1, 12, 0);

                            // รวมกำไร 1.25% ของแต่ละเดือน
                            foreach ($LoanClosePaymentMonths as $LoanClosePaymentMonth) {
                                $month = (int)$LoanClosePaymentMonth->loan_date_close_month;
                                $profit = $LoanClosePaymentMonth->loan_summary_no_vat * 0.0125; // 1.25% ของยอด
                                $MonthProfit[$month] += $profit;
                            }

                            // กำไรเดือนปัจจุบัน
                            $currentMonth = (int)date('m');
                            $total_month = $MonthProfit[$currentMonth];

                            // กำไรปีปัจจุบัน (รวมทุกเดือน)
                            $Month_Diff_Payment_Sum = array_sum($MonthProfit);
                            ?>

                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <div align="center">
                                                    <div class="rounded-circle ht-60 wd-60 bg-light d-flex align-items-center justify-content-center">
                                                        <div class="ht-50 wd-50 rounded-circle bg-primary d-flex align-items-center justify-content-center">
                                                            <i class="ti-money tx-17 text-white"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h4 class="tx-18 font-weight-semibold my-1">
                                                    กำไร <?php echo number_format($total_month, 2) ?>
                                                </h4>
                                                <h4 class="tx-18 font-weight-semibold my-1">(กำไรเดือนปัจจุบัน)</h4>
                                                <?php
                                                $Sum_profit_month = $total_month * 100 / $targeteds->desired_goals_month;
                                                if ($Sum_profit_month < 0) {
                                                    $profit_Color = "bg-danger-transparent tx-danger";
                                                    $Sum_profit = number_format($Sum_profit_month, 2) . '%';
                                                } elseif ($Sum_profit_month > 0) {
                                                    $profit_Color = "bg-success-transparent tx-success";
                                                    $Sum_profit = '+' . number_format($Sum_profit_month, 2) . '%';
                                                } else {
                                                    $profit_Color = "";
                                                    $Sum_profit = number_format($Sum_profit_month, 2) . '%';
                                                }
                                                ?>
                                                <p class="tx-14 mb-1">
                                                    <span class="<?php echo $profit_Color ?>"><?php echo $Sum_profit ?></span> of target
                                                </p>
                                                <p class="tx-13 text-start" style="margin-bottom: 8px;">
                                                    เป้าหมาย ( <?php echo number_format($total_month, 2) ?> / <?php echo number_format($targeteds->desired_goals_month, 2) ?>)
                                                </p>
                                                <div class="progress progress-style ht-5 mt-2">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar"
                                                        style="width:<?php echo number_format($Sum_profit_month, 2) . "%" ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <div align="center">
                                                    <div class="rounded-circle ht-60 wd-60 bg-light d-flex align-items-center justify-content-center">
                                                        <div class="ht-50 wd-50 rounded-circle bg-primary d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-hand-holding-usd tx-17 text-white"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h4 class="tx-18 font-weight-semibold my-1">
                                                    กำไร <?php echo number_format($Month_Diff_Payment_Sum, 2) ?>
                                                </h4>
                                                <h4 class="tx-18 font-weight-semibold my-1">(กำไรปีปัจจุบัน)</h4>
                                                <?php
                                                $Sum_price_percent = $Month_Diff_Payment_Sum * 100 / $targeteds->desired_goal;
                                                if ($Sum_price_percent < 0) {
                                                    $profit_Color_year = "bg-danger-transparent tx-danger";
                                                    $Sum_profit_year = number_format($Sum_price_percent, 2) . '%';
                                                } elseif ($Sum_price_percent > 0) {
                                                    $profit_Color_year = "bg-success-transparent tx-success";
                                                    $Sum_profit_year = '+' . number_format($Sum_price_percent, 2) . '%';
                                                } else {
                                                    $profit_Color_year = "";
                                                    $Sum_profit_year = number_format($Sum_price_percent, 2) . '%';
                                                }
                                                ?>
                                                <p class="tx-14 mb-1">
                                                    <span class="<?php echo $profit_Color_year ?>"><?php echo $Sum_profit_year ?></span> of target
                                                </p>
                                                <p class="tx-13 text-start" style="margin-bottom: 8px;">
                                                    เป้าหมาย (<?php echo number_format($Month_Diff_Payment_Sum, 2) ?> / <?php echo number_format($targeteds->desired_goal, 2) ?>)
                                                </p>
                                                <div class="progress progress-style ht-5 mt-2">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar"
                                                        style="width:<?php echo number_format($Sum_price_percent, 2) . "%" ?>">
                                                    </div>
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
                            <div class="tab-pane active" id="report_finx"></div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            กราฟแสดงผลชำระค่างวดต่อเดือน
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
            <!-- <div class="col-xxl-12 col-xl-12">
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
            </div> -->
        </div>
    </div>

</div>
<!-- /Container -->

</div>
<!-- /main-content -->

<!-- ตารางยอดเปิดสินเชื่อ -->
<div class="modal fade" id="modalOpenLoanMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="card-title">ตารางยอดเปิดสินเชื่อ (เดือน)</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalLoanMonth"><span aria-hidden="true">&times;</span></button>

            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_OpenLoan">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 22px;">#</th>
                                    <th class="text-center" style="width: 50px;">เลขที่สินเชื่อ</th>
                                    <th class="text-center" style="width: 100px;">ชื่อลูกค้า</th>
                                    <th class="text-center" style="width: 100px;">ชื่อพนักงาน</th>
                                    <th class="text-center" style="width: 70px;">ยอดสินเชื่อ</th>
                                    <th class="text-center" style="width: 90px;">วันที่ขอสินเชื่อ</th>
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

<!-- ยอดชำระดอกเบี้ย -->
<div class="modal fade" id="modalLoanPaymentMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">ยอดชำระดอกเบี้ย (เดือน)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalLoanPaymentMonth"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_LoanPayment">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%;">#</th>
                                    <th class="text-center" style="width: 15%;">เลขที่สินเชื่อ</th>
                                    <th class="text-center" style="width: 20%;">ชื่อลูกค้า</th>
                                    <th class="text-center" style="width: 20%;">ผู้รับชำระ</th>
                                    <th class="text-center" style="width: 20%;">ยอดชำระ</th>
                                    <th class="text-center" style="width: 20%;">วันที่ชำระ</th>
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
<!-- จบ ยอดชำระดอกเบี้ย -->

<!-- ยอดชำระปิดบัญชี -->
<div class="modal fade" id="modalLoanClosePaymentMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">ยอดชำระปิดบัญชี (เดือน)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalLoanClosePaymentMonth"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_LoanFinxClosePayment">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%;">#</th>
                                    <th class="text-center" style="width: 15%;">เลขที่สินเชื่อ</th>
                                    <th class="text-center" style="width: 20%;">ชื่อลูกค้า</th>
                                    <th class="text-center" style="width: 20%;">ผู้รับชำระ</th>
                                    <th class="text-center" style="width: 20%;">ยอดชำระ</th>
                                    <th class="text-center" style="width: 20%;">วันที่ชำระ</th>
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
<!-- จบ ยอดชำระปิดบัญชี -->

<!-- ตารางยอดรายจ่าย -->
<div class="modal fade" id="modalDocumentsPayMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">ตารางยอดรายจ่าย (เดือน)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalDocumentsPayMonth"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_DocumentsPay">
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