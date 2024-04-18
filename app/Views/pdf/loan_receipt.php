<!doctype html>
<html lang="en" data-layout="horizontal" data-hor-style="hor-hover" data-logo="centerlogo">

<body>
    <table>
        <tr>
            <th style="font-size: 10px;"></th>
        </tr>
        <tr>
            <th width="100%" style="font-size: 35px;text-align:center;"><B>ใบเสร็จรับเงิน</B></th>
        </tr>
        <tr>
            <th style="font-size: 4px;"></th>
        </tr>
        <tr>
            <th width="6%">ชื่อผู้กู้ :</th>
            <th width="94%"><?php echo $loan->fullname ?></th>
        </tr>
        <tr>
            <th style="font-size: 4px;"></th>
        </tr>
        <tr>
            <th width="11%">เบอร์โทรศัพท์ :</th>
            <th width="89%"><?php echo $loan->phone ?></th>
        </tr>
        <tr>
            <th style="font-size: 4px;"></th>
        </tr>
        <tr>
            <th width="5%">ที่อยู่ :</th>
            <th width="95%"><?php echo $loan->address ?></th>
        </tr>
    </table>
    <table>
        <tr>
            <th style="font-size: 20px;"></th>
        </tr>
    </table>
    <table border="0.1">
        <thead>
            <tr>
                <th width="5%" style="text-align: center;"><B>ลำดับ</B></th>
                <th width="15%" style="text-align: center;"><B>เลขที่สินเชื่อ</B></th>
                <th width="20%" style="text-align: center;"><B>ผู้ชำระ</B></th>
                <th width="20%" style="text-align: center;"><B>ผู้รับชำระ</B></th>
                <th width="7%" style="text-align: center;"><B>งวด</B></th>
                <th width="18%" style="text-align: center;"><B>วันที่ชำระ</B></th>
                <th width="15%" style="text-align: center;"><B>ยอดชำระ</B></th>
            </tr>
        </thead>
        <!-- <php  $i = 0;
        $price_sum = 0;
        foreach ($detailLists as $detailList) {
            $i++; &nbsp;&nbsp;&nbsp;
            $price_sum += $detailList->kleanx_detail_service_price;
            ?> -->
        <tr>
            <td width="5%" style="text-align: center;">1</td>
            <td width="15%" style="text-align: center;"><?php echo $installments->loan_code ?></td>
            <td width="20%" style="text-align: center;"><?php echo $installments->loan_payment_customer ?></td>
            <td width="20%" style="text-align: center;"><?php echo $installments->loan_employee_response ?></td>
            <td width="7%" style="text-align: center;"><?php echo $installments->loan_payment_installment ?></td>
            <td width="18%" style="text-align: center;"><?php echo dayThai($installments->loan_payment_date).' '.monthThai($installments->loan_payment_date).' '.yearThai($installments->loan_payment_date) ?></td>
            <td width="15%" style="text-align: right;"><?php echo number_format($installments->loan_payment_amount,2) ?>&nbsp;&nbsp;</td>
        </tr>
        <!-- <php } ?> -->
        <tr>
            <td width="85%" style="text-align: right; "><B>รวม&nbsp;&nbsp;</B></td>
            <td width="15%" style="text-align: right; "><B><?php echo number_format($installments->loan_payment_amount,2) ?>&nbsp;&nbsp;</B></td>
        </tr>
    </table>
</body>

</html>