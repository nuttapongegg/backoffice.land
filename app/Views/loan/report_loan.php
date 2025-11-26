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
                                                <button type="button" class="btn btn-outline-primary mb-3 float-end btnEditOpenLoanTargetedMonth" data-bs-toggle="modal" data-bs-target="#modalEditOpenLoanTargetedMonth"> <i class="fa-solid fa-plus"></i>เป้าหมาย</button>
                                                <div align="center" class="ms-5">
                                                    <div class="rounded-circle ht-60 wd-60 bg-light bg-light d-flex align-items-center justify-content-center ms-5">
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
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <?php
                                            
                                            // เดือน1
                                            $Month_Jan_Receipt = 0;
                                            $Month_Jan_Expenses = 0;
                                            // เดือน2
                                            $Month_Feb_Receipt = 0;
                                            $Month_Feb_Expenses = 0;
                                            // เดือน3
                                            $Month_Mar_Receipt = 0;
                                            $Month_Mar_Expenses = 0;
                                            // เดือน4
                                            $Month_Apr_Receipt = 0;
                                            $Month_Apr_Expenses = 0;
                                            // เดือน5
                                            $Month_May_Receipt = 0;
                                            $Month_May_Expenses = 0;
                                            // เดือน6
                                            $Month_Jun_Receipt = 0;
                                            $Month_Jun_Expenses = 0;
                                            // เดือน7
                                            $Month_Jul_Receipt = 0;
                                            $Month_Jul_Expenses = 0;
                                            // เดือน8
                                            $Month_Aug_Receipt = 0;
                                            $Month_Aug_Expenses = 0;
                                            // เดือน9
                                            $Month_Sep_Receipt = 0;
                                            $Month_Sep_Expenses = 0;
                                            // เดือน10
                                            $Month_Oct_Receipt = 0;
                                            $Month_Oct_Expenses = 0;
                                            // เดือน11
                                            $Month_Nov_Receipt = 0;
                                            $Month_Nov_Expenses = 0;
                                            // เดือน12
                                            $Month_Dec_Receipt = 0;
                                            $Month_Dec_Expenses = 0;

                                            //คำนวนรายเดือน
                                            foreach ($DocumentsMonths as $doc_months) {
                                                switch ($doc_months->doc_month) {
                                                    case "1":
                                                        switch ($doc_months->doc_type) {
                                                            case "ใบสำคัญรับ":
                                                                $Month_Jan_Receipt = $doc_months->doc_sum_price;
                                                                break;
                                                            case "ใบสำคัญจ่าย":
                                                                $Month_Jan_Expenses = $doc_months->doc_sum_price;
                                                                break;
                                                        }
                                                        break;
                                                    case "2":
                                                        switch ($doc_months->doc_type) {
                                                            case "ใบสำคัญรับ":
                                                                $Month_Feb_Receipt = $doc_months->doc_sum_price;
                                                                break;
                                                            case "ใบสำคัญจ่าย":
                                                                $Month_Feb_Expenses = $doc_months->doc_sum_price;
                                                                break;
                                                        }
                                                        break;
                                                    case "3":
                                                        switch ($doc_months->doc_type) {
                                                            case "ใบสำคัญรับ":
                                                                $Month_Mar_Receipt = $doc_months->doc_sum_price;
                                                                break;
                                                            case "ใบสำคัญจ่าย":
                                                                $Month_Mar_Expenses = $doc_months->doc_sum_price;
                                                                break;
                                                        }
                                                        break;
                                                    case "4":
                                                        switch ($doc_months->doc_type) {
                                                            case "ใบสำคัญรับ":
                                                                $Month_Apr_Receipt = $doc_months->doc_sum_price;
                                                                break;
                                                            case "ใบสำคัญจ่าย":
                                                                $Month_Apr_Expenses = $doc_months->doc_sum_price;
                                                                break;
                                                        }
                                                        break;
                                                    case "5":
                                                        switch ($doc_months->doc_type) {
                                                            case "ใบสำคัญรับ":
                                                                $Month_May_Receipt = $doc_months->doc_sum_price;
                                                                break;
                                                            case "ใบสำคัญจ่าย":
                                                                $Month_May_Expenses = $doc_months->doc_sum_price;
                                                                break;
                                                        }
                                                        break;
                                                    case "6":
                                                        switch ($doc_months->doc_type) {
                                                            case "ใบสำคัญรับ":
                                                                $Month_Jun_Receipt = $doc_months->doc_sum_price;
                                                                break;
                                                            case "ใบสำคัญจ่าย":
                                                                $Month_Jun_Expenses = $doc_months->doc_sum_price;
                                                                break;
                                                        }
                                                        break;
                                                    case "7":
                                                        switch ($doc_months->doc_type) {
                                                            case "ใบสำคัญรับ":
                                                                $Month_Jul_Receipt = $doc_months->doc_sum_price;
                                                                break;
                                                            case "ใบสำคัญจ่าย":
                                                                $Month_Jul_Expenses = $doc_months->doc_sum_price;
                                                                break;
                                                        }
                                                        break;
                                                    case "8":
                                                        switch ($doc_months->doc_type) {
                                                            case "ใบสำคัญรับ":
                                                                $Month_Aug_Receipt = $doc_months->doc_sum_price;
                                                                break;
                                                            case "ใบสำคัญจ่าย":
                                                                $Month_Aug_Expenses = $doc_months->doc_sum_price;
                                                                break;
                                                        }
                                                        break;
                                                    case "9":
                                                        switch ($doc_months->doc_type) {
                                                            case "ใบสำคัญรับ":
                                                                $Month_Sep_Receipt = $doc_months->doc_sum_price;
                                                                break;
                                                            case "ใบสำคัญจ่าย":
                                                                $Month_Sep_Expenses = $doc_months->doc_sum_price;
                                                                break;
                                                        }
                                                        break;
                                                    case "10":
                                                        switch ($doc_months->doc_type) {
                                                            case "ใบสำคัญรับ":
                                                                $Month_Oct_Receipt = $doc_months->doc_sum_price;
                                                                break;
                                                            case "ใบสำคัญจ่าย":
                                                                $Month_Oct_Expenses = $doc_months->doc_sum_price;
                                                                break;
                                                        }
                                                        break;
                                                    case "11":
                                                        switch ($doc_months->doc_type) {
                                                            case "ใบสำคัญรับ":
                                                                $Month_Nov_Receipt = $doc_months->doc_sum_price;
                                                                break;
                                                            case "ใบสำคัญจ่าย":
                                                                $Month_Nov_Expenses = $doc_months->doc_sum_price;
                                                                break;
                                                        }
                                                        break;
                                                    case "12":
                                                        switch ($doc_months->doc_type) {
                                                            case "ใบสำคัญรับ":
                                                                $Month_Dec_Receipt = $doc_months->doc_sum_price;
                                                                break;
                                                            case "ใบสำคัญจ่าย":
                                                                $Month_Dec_Expenses = $doc_months->doc_sum_price;
                                                                break;
                                                        }
                                                        break;
                                                }
                                            }

                                            $Month_Jan_Process = 0;
                                            $Month_Feb_Process = 0;
                                            $Month_Mar_Process = 0;
                                            $Month_Apr_Process = 0;
                                            $Month_May_Process = 0;
                                            $Month_Jun_Process = 0;
                                            $Month_Jul_Process = 0;
                                            $Month_Aug_Process = 0;
                                            $Month_Sep_Process = 0;
                                            $Month_Oct_Process = 0;
                                            $Month_Nov_Process = 0;
                                            $Month_Dec_Process = 0;

                                            foreach ($LoanProcessMonths as $loanprocessmonth) {
                                                switch ($loanprocessmonth->loan_created_payment) {
                                                    case "1":
                                                        $Month_Jan_Process = $loanprocessmonth->total_payment_process + $loanprocessmonth->total_tranfer + $loanprocessmonth->total_payment_other;
                                                        break;
                                                    case "2":
                                                        $Month_Feb_Process = $loanprocessmonth->total_payment_process + $loanprocessmonth->total_tranfer + $loanprocessmonth->total_payment_other;
                                                        break;
                                                    case "3":
                                                        $Month_Mar_Process = $loanprocessmonth->total_payment_process + $loanprocessmonth->total_tranfer + $loanprocessmonth->total_payment_other;
                                                        break;
                                                    case "4":
                                                        $Month_Apr_Process = $loanprocessmonth->total_payment_process + $loanprocessmonth->total_tranfer + $loanprocessmonth->total_payment_other;
                                                        break;
                                                    case "5":
                                                        $Month_May_Process = $loanprocessmonth->total_payment_process + $loanprocessmonth->total_tranfer + $loanprocessmonth->total_payment_other;
                                                        break;
                                                    case "6":
                                                        $Month_Jun_Process = $loanprocessmonth->total_payment_process + $loanprocessmonth->total_tranfer + $loanprocessmonth->total_payment_other;
                                                        break;
                                                    case "7":
                                                        $Month_Jul_Process = $loanprocessmonth->total_payment_process + $loanprocessmonth->total_tranfer + $loanprocessmonth->total_payment_other;
                                                        break;
                                                    case "8":
                                                        $Month_Aug_Process = $loanprocessmonth->total_payment_process + $loanprocessmonth->total_tranfer + $loanprocessmonth->total_payment_other;
                                                        break;
                                                    case "9":
                                                        $Month_Sep_Process = $loanprocessmonth->total_payment_process + $loanprocessmonth->total_tranfer + $loanprocessmonth->total_payment_other;
                                                        break;
                                                    case "10":
                                                        $Month_Oct_Process = $loanprocessmonth->total_payment_process + $loanprocessmonth->total_tranfer + $loanprocessmonth->total_payment_other;
                                                        break;
                                                    case "11":
                                                        $Month_Nov_Process = $loanprocessmonth->total_payment_process + $loanprocessmonth->total_tranfer + $loanprocessmonth->total_payment_other;
                                                        break;
                                                    case "12":
                                                        $Month_Dec_Process = $loanprocessmonth->total_payment_process + $loanprocessmonth->total_tranfer + $loanprocessmonth->total_payment_other;
                                                        break;
                                                }
                                            }

                                            $Month_Jan_Receipt_Sum = ($Month_Jan_Process + $Month_Jan_Receipt);
                                            $Month_Feb_Receipt_Sum = ($Month_Feb_Process + $Month_Feb_Receipt);
                                            $Month_Mar_Receipt_Sum = ($Month_Mar_Process + $Month_Mar_Receipt);
                                            $Month_Apr_Receipt_Sum = ($Month_Apr_Process + $Month_Apr_Receipt);
                                            $Month_May_Receipt_Sum = ($Month_May_Process + $Month_May_Receipt);
                                            $Month_Jun_Receipt_Sum = ($Month_Jun_Process + $Month_Jun_Receipt);
                                            $Month_Jul_Receipt_Sum = ($Month_Jul_Process + $Month_Jul_Receipt);
                                            $Month_Aug_Receipt_Sum = ($Month_Aug_Process + $Month_Aug_Receipt);
                                            $Month_Sep_Receipt_Sum = ($Month_Sep_Process + $Month_Sep_Receipt);
                                            $Month_Oct_Receipt_Sum = ($Month_Oct_Process + $Month_Oct_Receipt);
                                            $Month_Nov_Receipt_Sum = ($Month_Nov_Process + $Month_Nov_Receipt);
                                            $Month_Dec_Receipt_Sum = ($Month_Dec_Process + $Month_Dec_Receipt);

                                            // รายรับรวม
                                            $Month_Sum_Receipt = $Month_Jan_Receipt_Sum + $Month_Feb_Receipt_Sum + $Month_Mar_Receipt_Sum + $Month_Apr_Receipt_Sum
                                                + $Month_May_Receipt_Sum + $Month_Jun_Receipt_Sum + $Month_Jul_Receipt_Sum + $Month_Aug_Receipt_Sum + $Month_Sep_Receipt_Sum
                                                + $Month_Oct_Receipt_Sum + $Month_Nov_Receipt_Sum + $Month_Dec_Receipt_Sum;

                                            // รายจ่ายรวม
                                            $Month_Expenses_Sum = 0;
                                            $Month_Expenses_Sum = $Month_Jan_Expenses + $Month_Feb_Expenses + $Month_Mar_Expenses + $Month_Apr_Expenses + $Month_May_Expenses + $Month_Jun_Expenses
                                                + $Month_Jul_Expenses + $Month_Aug_Expenses + $Month_Sep_Expenses + $Month_Oct_Expenses + $Month_Nov_Expenses + $Month_Dec_Expenses;

                                            $Month_Jan_Payment_Month = 0;
                                            $Month_Feb_Payment_Month = 0;
                                            $Month_Mar_Payment_Month = 0;
                                            $Month_Apr_Payment_Month = 0;
                                            $Month_May_Payment_Month = 0;
                                            $Month_Jun_Payment_Month = 0;
                                            $Month_Jul_Payment_Month = 0;
                                            $Month_Aug_Payment_Month = 0;
                                            $Month_Sep_Payment_Month = 0;
                                            $Month_Oct_Payment_Month = 0;
                                            $Month_Nov_Payment_Month = 0;
                                            $Month_Dec_Payment_Month = 0;

                                            foreach ($LoanPaymentMonths as $LoanPaymentMonth) {
                                                if ($LoanPaymentMonth->loan_created_payment <= date('m')) {
                                                    switch ($LoanPaymentMonth->loan_created_payment) {
                                                        case "1":
                                                            $Month_Jan_Payment_Month = $Month_Jan_Payment_Month + $LoanPaymentMonth->setting_land_report_money;
                                                            break;
                                                        case "2":
                                                            $Month_Feb_Payment_Month = $Month_Feb_Payment_Month + $LoanPaymentMonth->setting_land_report_money;
                                                            break;
                                                        case "3":
                                                            $Month_Mar_Payment_Month = $Month_Mar_Payment_Month + $LoanPaymentMonth->setting_land_report_money;
                                                            break;
                                                        case "4":
                                                            $Month_Apr_Payment_Month = $Month_Apr_Payment_Month + $LoanPaymentMonth->setting_land_report_money;
                                                            break;
                                                        case "5":
                                                            $Month_May_Payment_Month = $Month_May_Payment_Month + $LoanPaymentMonth->setting_land_report_money;
                                                            break;
                                                        case "6":
                                                            $Month_Jun_Payment_Month = $Month_Jun_Payment_Month + $LoanPaymentMonth->setting_land_report_money;
                                                            break;
                                                        case "7":
                                                            $Month_Jul_Payment_Month = $Month_Jul_Payment_Month + $LoanPaymentMonth->setting_land_report_money;
                                                            break;
                                                        case "8":
                                                            $Month_Aug_Payment_Month = $Month_Aug_Payment_Month + $LoanPaymentMonth->setting_land_report_money;
                                                            break;
                                                        case "9":
                                                            $Month_Sep_Payment_Month = $Month_Sep_Payment_Month + $LoanPaymentMonth->setting_land_report_money;
                                                            break;
                                                        case "10":
                                                            $Month_Oct_Payment_Month = $Month_Oct_Payment_Month + $LoanPaymentMonth->setting_land_report_money;
                                                            break;
                                                        case "11":
                                                            $Month_Nov_Payment_Month = $Month_Nov_Payment_Month + $LoanPaymentMonth->setting_land_report_money;
                                                            break;
                                                        case "12":
                                                            $Month_Dec_Payment_Month = $Month_Dec_Payment_Month + $LoanPaymentMonth->setting_land_report_money;
                                                            break;
                                                    }
                                                }
                                            }

                                            $Month_Diff_Payment = 0;
                                            $Month_Diff_Payment = $Month_Jan_Payment_Month + $Month_Feb_Payment_Month + $Month_Mar_Payment_Month + $Month_Apr_Payment_Month + $Month_May_Payment_Month + $Month_Jun_Payment_Month
                                                + $Month_Jul_Payment_Month + $Month_Aug_Payment_Month + $Month_Sep_Payment_Month + $Month_Oct_Payment_Month + $Month_Nov_Payment_Month + $Month_Dec_Payment_Month;

                                            $total_month = 0;
                                            switch (date('m')) {
                                                case "1":
                                                    $total_month = ($Month_Jan_Receipt_Sum + $Month_Jan_Payment_Month) - $Month_Jan_Expenses;
                                                    break;
                                                case "2":
                                                    $total_month = ($Month_Feb_Receipt_Sum + $Month_Feb_Payment_Month) - $Month_Feb_Expenses;
                                                    break;
                                                case "3":
                                                    $total_month = ($Month_Mar_Receipt_Sum + $Month_Mar_Payment_Month) - $Month_Mar_Expenses;
                                                    break;
                                                case "4":
                                                    $total_month = ($Month_Apr_Receipt_Sum + $Month_Apr_Payment_Month) - $Month_Apr_Expenses;
                                                    break;
                                                case "5":
                                                    $total_month = ($Month_May_Receipt_Sum + $Month_May_Payment_Month) - $Month_May_Expenses;
                                                    break;
                                                case "6":
                                                    $total_month = ($Month_Jun_Receipt_Sum + $Month_Jun_Payment_Month) - $Month_Jun_Expenses;
                                                    break;
                                                case "7":
                                                    $total_month = ($Month_Jul_Receipt_Sum + $Month_Jul_Payment_Month) - $Month_Jul_Expenses;
                                                    break;
                                                case "8":
                                                    $total_month = ($Month_Aug_Receipt_Sum + $Month_Aug_Payment_Month) - $Month_Aug_Expenses;
                                                    break;
                                                case "9":
                                                    $total_month = ($Month_Sep_Receipt_Sum + $Month_Sep_Payment_Month) - $Month_Sep_Expenses;
                                                    break;
                                                case "10":
                                                    $total_month = ($Month_Oct_Receipt_Sum + $Month_Oct_Payment_Month) - $Month_Oct_Expenses;
                                                    break;
                                                case "11":
                                                    $total_month = ($Month_Nov_Receipt_Sum + $Month_Nov_Payment_Month) - $Month_Nov_Expenses;
                                                    break;
                                                case "12":
                                                    $total_month = ($Month_Dec_Receipt_Sum + $Month_Dec_Payment_Month) - $Month_Dec_Expenses;
                                                    break;
                                            }

                                            $Month_Diff_Payment_Sum = ($Month_Diff_Payment + $Month_Sum_Receipt) - $Month_Expenses_Sum;
                                            ?>

                                            <div class="col-md-12 text-center">
                                                <button type="button" class="btn btn-outline-primary mb-3 float-end btnEditTargetedMonth" data-bs-toggle="modal" data-bs-target="#modalEditTargetedMonth"> <i class="fa-solid fa-plus"></i>เป้าหมาย</button>
                                                <div align="center" class="ms-5">
                                                    <div class="rounded-circle ht-60 wd-60 bg-light bg-light d-flex align-items-center justify-content-center ms-5">
                                                        <div class="ht-50 wd-50 rounded-circle bg-primary d-flex align-items-center justify-content-center"> <i class="ti-money tx-17 text-white"></i> </div>
                                                    </div>
                                                </div>
                                                <h4 class="tx-18 font-weight-semibold my-1">กำไร <?php echo number_format($total_month, 2) ?></h4>
                                                <h4 class="tx-18 font-weight-semibold my-1">(กำไรเดือนปัจจุบัน)</h4>
                                                <?php $Sum_profit_month = $total_month * 100 / $targeteds->desired_goals_month; ?>
                                                <?php if ($Sum_profit_month < 0) {
                                                    $profit_Color = "bg-danger-transparent tx-danger";
                                                    $Sum_profit = number_format($Sum_profit_month, 2) . '%';
                                                } elseif ($Sum_profit_month > 0) {
                                                    $profit_Color = "bg-success-transparent tx-success";
                                                    $Sum_profit = '+' . number_format($Sum_profit_month, 2) . '%';
                                                } else {
                                                    $profit_Color = "";
                                                    $Sum_profit = number_format($Sum_profit_month, 2) . '%';
                                                } ?>
                                                <p class="tx-14 mb-1"><span class="<?php echo $profit_Color ?>"><?php echo $Sum_profit ?></span> of target</p>
                                                <p class="tx-13 text-start" style="margin-bottom: 8px;">เป้าหมาย ( <?php echo number_format($total_month, 2) ?> / <?php echo number_format($targeteds->desired_goals_month, 2) ?>)</p>
                                                <div class="progress progress-style ht-5 mt-2">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="78" style="width:<?php echo number_format($Sum_profit_month, 2) . "%" ?>"></div>
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
                                                <button type="button" class="btn btn-outline-primary mb-3 float-end btnEditTargeted" data-bs-toggle="modal" data-bs-target="#modalEditTargeted"> <i class="fa-solid fa-plus"></i>เป้าหมาย</button>
                                                <div align="center" class="ms-5">
                                                    <div class="rounded-circle ht-60 wd-60 bg-light bg-light d-flex align-items-center justify-content-center ms-5">
                                                        <div class="ht-50 wd-50 rounded-circle bg-primary d-flex align-items-center justify-content-center"> <i class="fas fa-hand-holding-usd tx-17 text-white"></i> </div>
                                                    </div>
                                                </div>
                                                <h4 class="tx-18 font-weight-semibold my-1">กำไร <?php echo number_format($Month_Diff_Payment_Sum, 2) ?></h4>
                                                <h4 class="tx-18 font-weight-semibold my-1">(กำไรปีปัจจุบัน)</h4>
                                                <?php $Sum_price_percent = $Month_Diff_Payment_Sum * 100 / $targeteds->desired_goal; ?>
                                                <?php if ($Sum_price_percent < 0) {
                                                    $profit_Color_year = "bg-danger-transparent tx-danger";
                                                    $Sum_profit_year = number_format($Sum_price_percent, 2) . '%';
                                                } elseif ($Sum_price_percent > 0) {
                                                    $profit_Color_year = "bg-success-transparent tx-success";
                                                    $Sum_profit_year = '+' . number_format($Sum_price_percent, 2) . '%';
                                                } else {
                                                    $profit_Color_year = "";
                                                    $Sum_profit_year = number_format($Sum_price_percent, 2) . '%';
                                                } ?>
                                                <p class="tx-14 mb-1"><span class="<?php echo $profit_Color_year ?>"><?php echo $Sum_profit_year ?></span> of target</p>
                                                <p class="tx-13 text-start" style="margin-bottom: 8px;">เป้าหมาย (<?php echo number_format($Month_Diff_Payment_Sum, 2) ?> / <?php echo number_format($targeteds->desired_goal, 2) ?>)</p>
                                                <div class="progress progress-style ht-5 mt-2">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="78" style="width:<?php echo number_format($Sum_price_percent, 2) . "%" ?>"></div>
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
<div align="center">
    <div class="modal fade" id="modalEditOpenLoanTargetedMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h7 class="modal-title">ตั้งค่าเป้าหมายเปิดสินเชื่อต่อเดือน</h7>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="updateTargetedMonth" name="updateTargetedMonth" action="#">
                        <input type="hidden" name="TargetedId" id="TargetedId" value="<?php echo $targeteds->id ?>" />

                        <div class="form-group">
                            <div align="left">
                                <label for="editTargeted" class="tx-15">เป้าหมาย</label>
                            </div>
                            <input type="text" class="form-control" id="editOpenLoanTargetedMonth" name="editOpenLoanTargetedMonth" placeholder="เป้าหมาย" value="<?php echo $targeteds->open_loan_target ?>">
                        </div>
                        <button type="submit" class="btn btn-primary btnSaveOpenLoanTargetedMonth" role="button">ยืนยัน</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div align="center">
    <div class="modal fade" id="modalEditTargetedMonth" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h7 class="modal-title">ตั้งค่าเป้าหมายต่อเดือน</h7>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="updateTargetedMonth" name="updateTargetedMonth" action="#">
                        <input type="hidden" name="TargetedId" id="TargetedId" value="<?php echo $targeteds->id ?>" />

                        <div class="form-group">
                            <div align="left">
                                <label for="editTargeted" class="tx-15">เป้าหมาย</label>
                            </div>
                            <input type="text" class="form-control" id="editTargetedMonth" name="editTargetedMonth" placeholder="เป้าหมาย" value="<?php echo $targeteds->desired_goals_month ?>">
                        </div>
                        <button type="submit" class="btn btn-primary btnSaveTargetedMonth" role="button">ยืนยัน</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Edit EditTargetedMonth -->
<div align="center">
    <div class="modal fade" id="modalEditTargeted" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h7 class="modal-title">ตั้งค่าเป้าหมาย</h7>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="updateTargeted" name="updateTargeted" action="#">
                        <input type="hidden" name="TargetedId" id="TargetedId" value="<?php echo $targeteds->id ?>" />

                        <div class="form-group">
                            <div align="left">
                                <label for="editTargeted" class="tx-15">เป้าหมาย</label>
                            </div>
                            <input type="text" class="form-control" id="editTargeted" name="editTargeted" placeholder="เป้าหมาย" value="<?php echo $targeteds->desired_goal ?>">
                        </div>
                        <button type="submit" class="btn btn-primary btnSave" role="button">ยืนยัน</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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

<!-- ตารางยอดค้างชำระ -->
<div class="modal fade" id="modalOverduePaymentMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">ตารางยอดค้างชำระ (เดือน)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalOverduePaymentMonth"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_OverduePayment">
                            <thead>
                                <tr>
                                    <th style="width: 22px;">#</th>
                                    <th style="width: 50px;">เลขที่สินเชื่อ</th>
                                    <th style="width: 50px;">ชื่อลูกค้า</th>
                                    <th style="width: 30px;">งวด</th>
                                    <th style="width: 70px;">ยอดค้างชำระ</th>
                                    <th style="width: 90px;">วันครบกำหนดชำระ</th>
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
<!-- จบ ตารางยอดค้างชำระ -->


<!-- ตารางยอดชำระต่อเดือน -->
<div class="modal fade" id="modalDiffPaymentMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">ตารางยอดชำระค่างวด (เดือน)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalDiffPaymentMonth"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_DiffPaymentMonth">
                            <thead>
                                <tr>
                                    <th style="width: 22px;">#</th>
                                    <th style="width: 50px;">เลขที่สินเชื่อ</th>
                                    <th style="width: 50px;">ผู้ชำระ</th>
                                    <th style="width: 50px;">ผู้รับชำระ</th>
                                    <th style="width: 30px;">งวด</th>
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
<!-- จบ ตารางยอดชำระต่อเดือน -->

<!-- ยอดชำระค่างวดจริง -->
<div class="modal fade" id="modalLoanPaymentMonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">ยอดชำระค่างวดจริง (เดือน)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalLoanPaymentMonth"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_LoanPayment">
                            <thead>
                                <tr>
                                    <th style="width: 20px;">#</th>
                                    <th style="width: 100px;">รายการ</th>
                                    <th style="width: 30px;">จำนวนเงิน</th>
                                    <th style="width: 100px;">รายละเอียด</th>
                                    <th style="width: 30px;">ผู้ทำรายการ</th>
                                    <th style="width: 30px;">ชื่อบัญชีรับชำระ</th>
                                    <th style="width: 30px;">จำนวนเงินในบัญชี</th>
                                    <th style="width: 50px;">วันที่ชำระ</th>
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
<!-- จบ ยอดชำระค่างวดจริง -->
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
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_LoanClosePayment">
                            <thead>
                                <tr>
                                    <th style="width: 20px;">#</th>
                                    <th style="width: 100px;">รายการ</th>
                                    <th style="width: 30px;">จำนวนเงิน</th>
                                    <th style="width: 100px;">รายละเอียด</th>
                                    <th style="width: 30px;">ผู้ทำรายการ</th>
                                    <th style="width: 30px;">ชื่อบัญชีรับชำระ</th>
                                    <th style="width: 30px;">จำนวนเงินในบัญชี</th>
                                    <th style="width: 50px;">วันที่ชำระ</th>
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