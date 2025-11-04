<!doctype html>
<html lang="en" data-layout="horizontal" data-hor-style="hor-hover" data-logo="centerlogo">

<body>
    <table>
        <tr>
            <th width="60%"></th>
            <th width="39%" style="font-size: 22px; text-align:center;border-bottom-style: solid;border-bottom-color: #BEBEBE"><B style="color: #3F51B5;">ใบสำคัญรับ/ใบกำกับภาษี</B></th>
        </tr>
        <tr>
            <th style="font-size: 8px;"></th>
        </tr>
        <tr>
            <th width="60%"></th>
            <th width="20%"><B style="color: #3F51B5;">เลขที่ใบกำกับภาษี</B></th>
            <th width="29%"></th>
        </tr>
        <tr>
            <th style="font-size: 4px;"></th>
        </tr>
        <tr>
            <th width="60%"></th>
            <th width="20%"><B style="color: #3F51B5;">วันที่ออกใบกำกับภาษี</B></th>
            <th width="29%"><?php echo (date('d/m/Y')) ?></th>
        </tr>
        <tr>
            <th style="font-size: 8px;"></th>
        </tr>

        <tr>
            <th width="16%"></th>
            <th width="44%"> </th>
            <th width="39%" style="border-top-style: solid;"></th>
        </tr>
        <tr>
            <th style="font-size: 5px;"></th>
        </tr>
        <tr>
            <th width="60%"><B style="font-size: 22px; color: #3F51B5;">รายการ</B></th>
            <th width="39%"></th>
        </tr>
        <tr>
            <th style="font-size: 2px;"></th>
        </tr>
        <table>
            <thead>
                <tr>
                    <th width="99%" style="font-size: 8px;border-bottom-style: solid;"></th>
                </tr>
                <tr>
                    <th style="font-size: 5px;"></th>
                </tr>
                <tr>
                    <th width="4%" style="text-align:center;">#</th>
                    <th width="10%" style="text-align:center;">เลขที่สินเชื่อ</th>
                    <th width="13%" style="text-align:center;">วันที่ชำระ</th>
                    <th width="20%" style="text-align:center;">ชื่อลูกค้า</th>
                    <th width="37%" style="text-align:center;">ชื่อสถานที่</th>
                    <th width="15%" style="text-align:right;">จำนวนเงิน</th>
                </tr>
                <tr>
                    <th width="99%" style="font-size: 2px;border-bottom-style: solid;"></th>
                </tr>
                <tr>
                    <th style="font-size: 5px;"></th>
                </tr>
            </thead>
            <?php $i = 0;
            $sumpay = 0;
            foreach ($finxpayments as $finxpayment) {
                $i++;
                $sumpay = $sumpay + $finxpayment->loan_payment_3percent; ?>
                <tr>
                    <th width="4%" style="text-align:center;"><?php echo $i ?></th>
                    <th width="10%" style="text-align:center;"><?php echo $finxpayment->loan_code ?></th>
                    <th width="13%" style="text-align:center;"><?php echo $finxpayment->formatted_date ?></th>
                    <th width="20%" style="text-align:center;"><?php echo !empty($datas->customer_fullname) ? $datas->customer_fullname : '-' ?></th>
                    <th width="37%"><?php echo $finxpayment->loan_address ?></th>
                    <th width="15%" style="text-align:right;"><?php echo number_format($finxpayment->loan_payment_3percent, 2) ?></th>
                </tr>
            <?php } ?>
        </table>
        <tr>
            <th width="99%" style="font-size: 2px;border-bottom-style: solid;"></th>
        </tr>
        <tr>
            <th style="font-size: 10px;"></th>
        </tr>
        <tr>
            <th width="60%">(<?php echo numToThaiBath(number_format($sumpay, 2)); ?>)</th>
            <th width="20%" style="text-align:right;"><B style="color: #3F51B5;">จำนวนเงินรวมทั้งสิ้น</B></th>
            <th width="19%" style="text-align:right;"><?php echo number_format($sumpay, 2) ?> บาท</th>
        </tr>
    </table>
</body>

</html>