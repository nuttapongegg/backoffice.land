<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        .w100 {
            width: 100%;
        }

        .t {
            width: 100%;
            border-collapse: collapse;

        }

        .th,
        .td {
            border: 1px solid #e3e3e3;
            padding: 4px 6px;
            vertical-align: middle;
        }

        .thh {
            background-color: #3F51B5;
            color: #ffffff;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }

        .mt4 {
            margin-top: 4px;
        }

        .mt8 {
            margin-top: 8px;
        }

        .mb4 {
            margin-bottom: 4px;
        }

        .mb8 {
            margin-bottom: 8px;
        }

        .small {
            font-size: 12px;
        }

        .bold {
            font-weight: bold;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #3F51B5;
            margin: 6px 0 3px 0;
        }

        .pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            border: 1px solid #3F51B5;
            font-size: 12px;
            color: #3F51B5;
        }

        .box {
            border: 1px solid #dddddd;
            padding: 8px 10px;
        }

        .label-en {
            font-size: 12px;
            color: #666;
            display: block;
            margin-top: 1px;
        }

        .summary-label {
            font-size: 13px;
        }

        .summary-value {
            font-size: 13px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <?php
    // กัน NULL
    $total_recv = isset($total_recv) ? (float)$total_recv : 0;
    $total_pay  = isset($total_pay)  ? (float)$total_pay  : 0;
    $net_total  = isset($net_total)  ? (float)$net_total  : ($total_recv - $total_pay);
    ?>

    <!-- Header / หัวเอกสาร -->
    <table class="w100">
        <tr>
            <!-- ฝั่งซ้าย: ข้อมูลบริษัท + หัวเรื่อง -->
            <td width="60%" valign="top" class="box">

                <!-- หัวข้อ -->
                <div style="font-size:18px; font-weight:bold; margin:0 0 6px 0; padding:0; line-height:1.1;">
                    ใบสรุปยอดรับ–จ่ายประจำเดือน
                    <span class="label-en" style="font-size: 13px; font-weight:normal;">
                        &nbsp;Monthly Receipt & Payment Statement
                    </span>
                </div>

                <!-- เดือน + วันที่พิมพ์ -->
                <div style="font-size:13px; margin:0 0 6px 0; padding:0; line-height:1.15;">
                    ประจำเดือน: <b><?= sprintf('%02d', $month) . '/' . $year ?></b><br>
                    วันที่พิมพ์รายงาน: <?= date('d/m/Y') ?>
                </div>

                <!-- ข้อมูลบริษัท -->
                <div style="font-size:13px; margin:0 0 6px 0; padding:0; line-height:1.15;">
                    ทะเบียนเลขที่ / Registration No. 0345568003383<br>
                    เลขประจำตัวผู้เสียภาษี / Tax ID. 0345568003383<br>
                    เลขที่สาขา 00000
                </div>

            </td>

            <td width="3%"></td>

            <!-- ฝั่งขวา: สรุปยอดรวม -->
            <td width="37%" valign="top" class="box">
                <div class="section-title" style="margin-top:0;">&nbsp;&nbsp;&nbsp;สรุปภาพรวม / Summary</div>
                <table class="w100" style="font-size:13px;">
                    <tr>
                        <td class="summary-label left" width="55%">&nbsp;&nbsp;&nbsp;รวมยอดรับทั้งเดือน<br /><span class="label-en">&nbsp;&nbsp;&nbsp;Total Receipts</span>
                        </td>
                        <td class="summary-value right" width="45%">
                            <?= number_format($total_recv, 2) ?> บาท
                        </td>
                    </tr>
                    <tr>
                        <td class="summary-label left">&nbsp;&nbsp;&nbsp;รวมยอดจ่ายทั้งเดือน<br /><span class="label-en">&nbsp;&nbsp;&nbsp;Total Payments</span>
                        </td>
                        <td class="summary-value right">
                            <?= number_format($total_pay, 2) ?> บาท
                        </td>
                    </tr>
                    <tr>
                        <td class="summary-label left">&nbsp;&nbsp;&nbsp;ยอดสุทธิ (รับ - จ่าย)<br /><span class="label-en">&nbsp;&nbsp;&nbsp;Net (Receipts - Payments)</span>
                        </td>
                        <td class="summary-value right" style="color:<?= $net_total >= 0 ? '#2e7d32' : '#c62828' ?>;">
                            <?= number_format($net_total, 2) ?> บาท
                        </td>
                    </tr>
                </table>

                <div style="margin-top:6px; font-size:12px; color:#555;line-height:1.95;">
                    <span class="pill">&nbsp;&nbsp;&nbsp;<?= $net_total >= 0 ? 'กำไรสุทธิ / Net Profit' : 'ขาดทุนสุทธิ / Net Loss' ?>
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <div class="mt8 mb4"></div>

    <!-- ตารางฝั่ง "รับ" -->
    <div class="section-title">
        รายการรับ / Receipts
    </div>

    <table class="t">
        <thead>
            <tr>
                <th class="th thh center mt-5" width="5%">#</th>
                <th class="th thh center" width="13%">เลขที่<br /><span class="small">Finx No.</span></th>
                <th class="th thh center" width="12%">วันที่<br /><span class="small">Date</span></th>
                <th class="th thh center" width="55%">รายละเอียด<br /><span class="small">Details</span></th>
                <th class="th thh right" width="15%">จำนวนเงิน (บาท)<br /><span class="small">Amount (THB)</span></th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($recvs)): ?>
                <tr>
                    <td class="td center" colspan="5">- ไม่มีรายการรับในเดือนนี้ -</td>
                </tr>
            <?php else: ?>
                <?php $i = 1;
                foreach ($recvs as $r): ?>
                    <tr>
                        <td class="td center" width="5%"><?= $i++ ?></td>
                        <td class="td center" width="13%">
                            <?= isset($r->doc_no) ? $r->doc_no : (isset($r->loan_code) ? $r->loan_code : '-') ?>
                        </td>
                        <td class="td center" width="12%">
                            <?= isset($r->doc_date) ? $r->doc_date : (isset($r->formatted_date) ? $r->formatted_date : '') ?>
                        </td>
                        <td class="td" width="55%">
                            <?php
                            if (!empty($r->title)) {
                                echo $r->title;
                            } elseif (!empty($r->loan_address)) {
                                echo $r->loan_address;
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td class="td right" width="15%">
                            <?= number_format((float) (!empty($r->amount) ? $r->amount : (!empty($r->loan_payment_3percent) ? $r->loan_payment_3percent : 0)), 2) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td class="td right" colspan="4"><b>รวมรายการรับทั้งหมด / Total Receipts</b></td>
                    <td class="td right"><b><?= number_format($total_recv, 2) ?></b></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>


    <div class="mt8"></div>

    <!-- ตารางฝั่ง "จ่าย" -->
    <div class="section-title">
        รายการจ่าย / Payments
    </div>

    <table class="t">
        <thead>
            <tr>
                <th class="th thh center" width="5%">#</th>
                <th class="th thh center" width="13%">เลขที่<br /><span class="small">Document No.</span></th>
                <th class="th thh center" width="12%">วันที่<br /><span class="small">Date</span></th>
                <th class="th thh center" width="55%">รายละเอียด<br /><span class="small">Details</span></th>
                <th class="th thh right" width="15%">จำนวนเงิน (บาท)<br /><span class="small">Amount (THB)</span></th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($pays)): ?>
                <tr>
                    <td class="td center" colspan="5">- ไม่มีรายการจ่ายในเดือนนี้ -</td>
                </tr>
            <?php else: ?>
                <?php $j = 1;
                foreach ($pays as $p): ?>
                    <tr>
                        <td class="td center" width="5%"><?= $j++ ?></td>
                        <td class="td" width="13%">
                            <?= isset($p->doc_no) ? $p->doc_no : (isset($p->doc_number) ? $p->doc_number : '-') ?>
                        </td>
                        <td class="td center" width="12%">
                            <?= isset($p->doc_date) ? $p->doc_date : (isset($p->formatted_date_doc) ? $p->formatted_date_doc : '') ?>
                        </td>
                        <td class="td" width="55%">
                            <?php
                            if (!empty($p->title)) {
                                echo $p->title;
                            } elseif (!empty($p->note)) {
                                echo $p->note;
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td class="td right" width="15%">
                            <?= number_format((float) (!empty($p->amount) ? $p->amount : (!empty($p->price) ? $p->price : 0)), 2) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td class="td right" colspan="4"><b>รวมรายการจ่ายทั้งหมด / Total Payments</b></td>
                    <td class="td right"><b><?= number_format($total_pay, 2) ?></b></td>
                </tr>
                <tr>
                    <td class="td right" colspan="4"><b>ยอดสุทธิ (รับ - จ่าย) / Net</b></td>
                    <td class="td right">
                        <b style="color:<?= $net_total >= 0 ? '#2e7d32' : '#c62828' ?>;">
                            <?= number_format($net_total, 2) ?>
                        </b>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>


</body>

</html>