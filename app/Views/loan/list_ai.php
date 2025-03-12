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
            <div class="col-xxl-12 col-xl-12" id="summarizeLoan">
            </div>
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="card-title justify-content-between d-flex">
                            <div>
                                <!-- <div id="count_car"></div> -->
                                <!-- <div id="count_loan_on" style="color: #FF8800;">สินเชื่อ 0 ราย</div> -->
                                <div style="color: #FF8800;">รายการสินเชื่อที่ยังไม่ปิด</div>
                            </div>
                            <!-- <div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary Loan_open text-center" data-bs-toggle="modal" data-bs-target="#modalAddLoan"><i class="fa-solid fa-plus text-center" id="addStockCar" name="addStockCar"></i>&nbsp;&nbsp;เพิ่มสินเชื่อ</a>
                            </div> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-wrap">
                            <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
                            </div>
                            <div class="panel tabs-style1">
                                <div class="panel-body">
                                    <div class="table-responsive double-scroll">
                                        <table class="table table-bordered text-nowrap border-bottom">
                                            <thead>
                                                <tr>
                                                    <th class="wd-15p text-center">เลขที่สินเชื่อ</th>
                                                    <th class="wd-40p text-center">ชื่อลูกค้า</th>
                                                    <th class="wd-20p text-center">ชื่อสถานที่</th>
                                                    <th class="wd-20p text-center">เนื้อที่</th>
                                                    <th class="wd-20p text-center">เลขที่ดิน</th>
                                                    <th class="wd-30p text-center">โฉนด</th>
                                                    <th class="wd-20p text-center">วันที่ขอสินเชื่อ</th>
                                                    <th class="wd-40p text-center">วงเงิน</th>
                                                    <th class="wd-20p text-center">ชำระทุกวันที</th>
                                                    <th class="wd-30p text-center">สถานะ</th>
                                                    <th class="wd-30p text-center">เกินกำหนดชำระ</th>
                                                    <th class="wd-30p text-center">ยอดค้างชำระ</th>
                                                    <th class="wd-30p text-center">ชำระแล้วเป็นเงิน</th>
                                                    <th class="wd-30p text-center">เงินที่ต้องชำระคงเหลือ</th>
                                                    <th class="wd-30p text-center">งวดละ</th>
                                                    <th class="wd-20p text-center">เครดิต</th>
                                                    <th class="wd-20p text-center">ประเภทสินเชื่อ</th>
                                                    <th class="wd-30p text-center">ระยะเวลาการผ่อน</th>
                                                    <th class="wd-30p text-center">ชำระแล้ว</th>
                                                    <th class="wd-30p text-center">จำนวนงวด</th>
                                                    <th class="wd-30p text-center">ดอกเบี้ย</th>
                                                    <th class="wd-30p text-center">รายละเอียด</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($loan_on > 0) {
                                                    foreach ($loan_on as $results) {
                                                        echo "<tr><td>" . $results->loan_code . "</td>";
                                                        echo "<td>" . $results->loan_customer . "</td>";
                                                        echo "<td>" . $results->loan_address . "</td>";
                                                        echo "<td>" . $results->loan_area . "</td>";
                                                        echo "<td>" . $results->loan_number . "</td>";
                                                        if ((int)$results->land_deed_status == 1) {
                                                            echo "<td>" . "✔"  . "</td>";
                                                        } else {
                                                            echo "<td>" . " "  . "</td>";
                                                        }

                                                        echo "<td>" . $results->loan_date_promise . "</td>";
                                                        echo "<td>" . $results->loan_summary_no_vat . "</td>";
                                                        echo "<td>" . $results->loan_installment_date . "</td>";
                                                        $text_status = "";
                                                        if ($results->loan_status == 'ON_STATE') {
                                                            try {
                                                                $date1 = $results->loan_payment_date_fix;
                                                                $date = new DateTime($date1);

                                                                $newDate = clone $date;
                                                                $newDate->modify("+" . ($results->loan_period - 1) . " month");

                                                                $currentTimestamp = time();
                                                                $newDateTimestamp = $newDate->getTimestamp();

                                                                $daysPassed = floor(($currentTimestamp - $newDateTimestamp) / (60 * 60 * 24));

                                                                if ($daysPassed > 0) {
                                                                    $text_status = "รอการจ่าย/เลยกำหนด";
                                                                } else {
                                                                    $text_status = "ยังไม่ถึงกำหนด";
                                                                }
                                                            } catch (\Exception $e) {
                                                                $text_status = $e->getMessage();
                                                            }
                                                        } else {
                                                            $text_status = "สินเชื่อชำระเสร็จสิ้น";
                                                        }
                                                        echo "<td>" . $text_status . "</td>";
                                                        $text_days_passed = "";
                                                        if ($results->loan_status == 'ON_STATE') {
                                                            try {
                                                                if ($daysPassed > 0) {
                                                                    $text_days_passed =  $daysPassed . " วัน";
                                                                } else {
                                                                    $text_days_passed = "-";
                                                                }
                                                            } catch (\Exception $e) {
                                                                $text_days_passed = $e->getMessage();
                                                            }
                                                        } else {
                                                            $text_days_passed = "-";
                                                        }
                                                        echo "<td>" . $text_days_passed . "</td>";
                                                        $text_overdude = "";
                                                        $loanOverdueSumPub = 0;
                                                        $installment =  (int)$results->loan_payment_year_counter * 12;
                                                        $remaining_installments = $installment - (int)$results->loan_payment_type;
                                                        if ($daysPassed > 0) {
                                                            // ทำความสะอาดข้อมูล loan_overdue (ลบเครื่องหมายที่ไม่ใช่ตัวเลข)
                                                            $loanOverdue = preg_replace('/[^0-9.-]+/', '', $results->loan_overdue);
                                                            $loanOverdue = (float)$loanOverdue + 1;

                                                            // หาค่าน้อยสุดระหว่าง loan_overdue และ remaining_installments
                                                            $overdueMonths = min($loanOverdue, $remaining_installments);

                                                            // คำนวณยอดรวมที่เกินกำหนด
                                                            $loanOverdueSum = $overdueMonths * $results->loan_payment_month;
                                                            $loanOverdueSumPub = $loanOverdueSum;

                                                            // จัดรูปแบบตัวเลขและแสดงผล
                                                            $formattedLoanOverdueSum = number_format($loanOverdueSum, 2);
                                                            $text_overdude = "<font class='tx-danger'>$formattedLoanOverdueSum</font>";
                                                        } else {
                                                            $text_overdude  = "<font>-</font>";
                                                        }

                                                        echo "<td>" . $text_overdude . "</td>";
                                                        echo "<td>" . $results->loan_payment_sum_installment . "</td>";
                                                        echo "<td>" . (int)$results->loan_sum_interest - (int)$results->loan_payment_sum_installment . "</td>";
                                                        echo "<td>" . $results->loan_payment_month . "</td>";
                                                        $text_risk = "";
                                                        $dayOverdueScore = 0;
                                                        if ($daysPassed <= 30) {
                                                            $dayOverdueScore = 5;
                                                        } elseif ($daysPassed <= 90) {
                                                            $dayOverdueScore = 3;
                                                        } else {
                                                            $dayOverdueScore = 1;
                                                        }

                                                        // คำนวณเปอร์เซ็นต์ของยอดเงินที่เกินกำหนด (overdue_percentage)
                                                        $overduePercentage = ($loanOverdueSumPub / (int)$results->loan_sum_interest) * 100;
                                                        $outstandingAmountScore = 0;
                                                        if ($overduePercentage < 10) {
                                                            $outstandingAmountScore = 5; // น้อยกว่า 10% ได้ 5 คะแนน
                                                        } elseif ($overduePercentage >= 10 && $overduePercentage <= 30) {
                                                            $outstandingAmountScore = 3; // อยู่ระหว่าง 10%-30% ได้ 3 คะแนน
                                                        } else {
                                                            $outstandingAmountScore = 1; // มากกว่า 30% ได้ 1 คะแนน
                                                        }

                                                        // คำนวณเปอร์เซ็นต์ของเงินที่ชำระแล้ว (paid_percentage)
                                                        $paidPercentage = ((int)$results->loan_payment_sum_installment / (int)$results->loan_sum_interest) * 100;
                                                        $paymentScore = 0;
                                                        if ($paidPercentage < 20) {
                                                            $paymentScore = 1; // ชำระน้อยกว่า 20% ได้ 1 คะแนน
                                                        } elseif ($paidPercentage >= 20 && $paidPercentage <= 60) {
                                                            $paymentScore = 3; // ชำระระหว่าง 20%-60% ได้ 3 คะแนน
                                                        } else {
                                                            $paymentScore = 5; // ชำระมากกว่า 60% ได้ 5 คะแนน
                                                        }

                                                        // รวมคะแนนทั้งหมด
                                                        $totalScore = $dayOverdueScore + $outstandingAmountScore + $paymentScore;

                                                        // ประเมินความเสี่ยง
                                                        if ($totalScore >= 12) {
                                                           $text_risk = "<font class='tx-success'>ความเสี่ยงต่ำ</font>";
                                                        } elseif ($totalScore >= 8 && $totalScore <= 11) {
                                                            $text_risk = "<font class='tx-secondary'>ความเสี่ยงปานกลาง</font>";
                                                        } else {
                                                            $text_risk = "<font class='tx-danger'>ความเสี่ยงสูง</font>";
                                                        }
                                                        echo "<td>" . $text_risk . "</td>";
                                                        echo "<td>" . $results->loan_type . "</td>";
                                                        echo "<td>" . $results->loan_payment_year_counter . " ปี</td>";
                                                        echo "<td>" . (int)$results->loan_payment_type . "</td>";
                                                        $month = (int)$results->loan_payment_year_counter * 12;
                                                        echo "<td>" . $month . "</td>";
                                                        echo "<td>" . $results->loan_payment_interest . " %</td>";
                                                        echo "<td>" . $results->loan_remnark . "</td></tr>";
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>

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
        </div>

    </div>
    <!-- main-content closed -->
</div>