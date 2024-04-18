<!doctype html>
<html lang="en" data-layout="horizontal" data-hor-style="hor-hover" data-logo="centerlogo">

<body>
    <table>
        <tr>
            <th style="font-size: 25px;"></th>
        </tr>
        <tr>
            <th width="100%" style="font-size: 28px;text-align:center;"><B>ตารางการผ่อนชำระ</B></th>
        </tr>
        <tr>
            <th style="font-size: 10px;"></th>
        </tr>
        <tr>
            <th width="4%">ชื่อผู้กู้</th>
            <th width="46%" style="text-align:center; border-bottom-style: dotted; "><?php echo $loan->loan_customer ?></th>
            <th width="3%"></th>
            <th width="7%">เจ้าหน้าที่</th>
            <th width="40%" style="text-align:center; border-bottom-style: dotted; "><?php echo $loan->loan_employee ?></th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="3%">ที่อยู่</th>
            <th width="47%" style="text-align:center; border-bottom-style: dotted; "></th>
            <th width="3%"></th>
            <th width="4%">สาขา</th>
            <th width="43%" style="text-align:center; border-bottom-style: dotted; "></th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="9%">เบอร์โทรศัพท์</th>
            <th width="41%" style="text-align:center; border-bottom-style: dotted; "></th>
            <th width="3%"></th>
            <th width="11%">วันที่ออกสินเชื่อ</th>
            <th width="36%" style="text-align:center; border-bottom-style: dotted; "><?php echo dayThai($loan->loan_date_promise).' '.monthThai($loan->loan_date_promise).' '.yearThai($loan->loan_date_promise) ?></th>
        </tr>
        <tr>
            <th style="font-size: 13px;"></th>
        </tr>
        <tr>
            <th width="5%">วงเงินกู้</th>
            <th width="11%" style="text-align:center; border-bottom-style: dotted; "><?php echo number_format($loan->loan_summary_no_vat,2) ?></th>
            <th width="3%">บาท</th>
            <th width="1%"></th>
            <th width="9%">อัตราดอกเบี้ย</th>
            <th width="8%" style="text-align:center; border-bottom-style: dotted; "><?php echo $loan->loan_payment_interest.' %' ?></th>
            <th width="3%">ต่อปี</th>
            <th width="1%"></th>
            <th width="10%">ระยะเวลาชำระ</th>
            <?php $installment = $loan->loan_payment_year_counter * 12;?>
            <th width="5%" style="text-align:center; border-bottom-style: dotted; "><?php echo $installment?></th>
            <th width="3%">งวด</th>
            <th width="1%"></th>
            <th width="5%">งวดละ</th>
            <th width="10%" style="text-align:center; border-bottom-style: dotted; "><?php echo number_format($loan->loan_payment_month,2)?></th>
            <th width="3%">บาท</th>
            <th width="1%"></th>
            <th width="9%">ชำระทุกวันที่</th>
            <th width="4%" style="text-align:center; border-bottom-style: dotted; "><?php echo dayThai($loan->loan_installment_date) ?></th>
            <th width="9%">ของทุกเดือน</th>
        </tr>
        <tr>
            <th style="font-size: 13px;"></th>
        </tr>
    </table>
    <table border="0.1">
        <thead>
            <tr>
                <th width="5%" style="text-align: center;"><B>งวด</B></th>
                <th width="17%" style="text-align: center;"><B>วันที่ชำระ</B></th>
                <th width="21%" style="text-align: center;"><B>ผู้ชำระ</B></th>
                <th width="21%" style="text-align: center;"><B>ผู้รับชำระ</B></th>
                <th width="18%" style="text-align: center;"><B>ยอดชำระ (ต่อเดือน)</B></th>
                <th width="17%" style="text-align: center;"><B>ยอดค้างชำระคงเหลือ</B></th>
            </tr>
        </thead>
        <?php $sum_installment = 0;
        foreach ($installments as $installment) {?>
            <tr>
                <td width="5%" style="text-align: center;"><?php echo $installment->loan_payment_installment?></td>
                <td width="17%" style="text-align: center;"><?php echo $installment->loan_payment_date?></td>
                <td width="21%" style="text-align: center;"><?php echo $installment->loan_payment_customer?></td>
                <td width="21%" style="text-align: center;"><?php echo $installment->loan_employee ?></td>
                <td width="18%" style="text-align: right;"><?php echo number_format($installment->loan_payment_amount,2) ?>&nbsp;&nbsp;</td>
                <td width="17%" style="text-align: right;"><?php echo number_format($installment->loan_balance,2) ?>&nbsp;&nbsp;</td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>