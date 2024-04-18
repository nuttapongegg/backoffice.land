<!doctype html>
<html lang="en" data-layout="horizontal" data-hor-style="hor-hover" data-logo="centerlogo">

<body>
    <table>
        <tr>
            <th style="font-size: 25px;"></th>
        </tr>
        <tr>
            <th width="100%" style="font-size: 28px;text-align:center;"><B>หนังสือสัญญากู้เงิน</B></th>
        </tr>
        <tr>
            <th style="font-size: 10px;"></th>
        </tr>
        <tr>
            <th width="55%"></th>
            <th width="10%">สัญญาทำขึ้นที่</th>
            <th width="35%" style="text-align:center; border-bottom-style: dotted;"></th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="55%"></th>
            <th width="2%">วัน</th>
            <th width="8%" style="text-align:center; border-bottom-style: dotted; "><?php echo dayThai($loan->loan_date_promise) ?></th>
            <th width="4%">เดือน</th>
            <th width="19%" style="text-align:center; border-bottom-style: dotted; "><?php echo monthThai($loan->loan_date_promise) ?></th>
            <th width="3%">พ.ศ.</th>
            <th width="9%" style="text-align:center; border-bottom-style: dotted; "><?php echo yearThai($loan->loan_date_promise) ?></th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="23%">สัญญากู้ยืมเงินฉบับนี้ ทำขึ้นระหว่าง</th>
            <th width="62%" style="text-align:center; border-bottom-style: dotted; "><?php echo $loan->loan_customer?></th>
            <th width="3%">อายุ</th>
            <th width="10%" style="text-align:center; border-bottom-style: dotted; "></th>
            <th width="2%">ปี</th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="3%">ที่อยู่</th>
            <th width="97%" style="text-align:center; border-bottom-style: dotted; "></th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="100%">ซึ่งต่อไปในสัญญานี้ จะเรียกว่า "ผู้กู้"</th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="3%">กับ</th>
            <th width="47%" style="text-align:center; border-bottom-style: dotted; "></th>
            <th width="50%">ซึ่งต่อไปในสัญญานี้ จะเรียกว่า "ผู้ให้กู้"</th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="100%">โดยที่คู่สัญญาทั้งสองฝ่ายได้ตกลงกันดังต่อไปนี้</th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="2%"></th>
            <th width="3%"><B>ข้อ</B></th>
            <th width="41%">1.ผู้ให้กู้ตกลงให้ยืม และผู้กู้ตกลงยืมเงินจากผู้ให้กู้เป็นจำนวนเงิน</th>
            <th width="17%" style="text-align:center; border-bottom-style: dotted;"><?php echo number_format($loan->loan_summary_no_vat, 2) ?></th>
            <th width="4%">บาท</th>
            <th width="1%">(</th>
            <th width="30%" style="text-align:center; border-bottom-style: dotted; "><?php echo numToThaiBath(number_format($loan->loan_summary_no_vat, 2)); ?></th>
            <th width="1%">)</th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="100%">โดยผู้กู้ได้รับเงินกู้จำนวนดังกล่าวจากผู้ให้กู้ถูกต้องครบถ้วนในวันทำสัญญานี้แล้ว</th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="2%"></th>
            <th width="3%"><B>ข้อ</B></th>
            <th width="33%">2.ผู้กู้ตกลงชำระดอกเบี้ยให้แก่ผู้ให้กู้ในอัตราร้อยละ</th>
            <th width="8%" style="text-align:center; border-bottom-style: dotted;"><?php echo $loan->loan_payment_interest.' %' ?></th>
            <th width="55%">ต่อปี และต่อไปหากผู้ให้กู้ประสงค์จะเพิ่มอัตราดอกเบี้ยซึ่งไม่เกินไปกว่าอัตรากฎหมาย</th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="100%">กำหนดแล้วผู้กู้ยินยอมให้ผู้ให้กู้เพิ่มอัตราดอกเบี้ยดังกล่าวได้โดยจะไม่โต้แย้งประการใดทั้งสิ้น และจะมีผลบังคับทันทีเมื่อผู้ให้กู้แจ้งอัตราดอกเบี้ยที่กำหนดขึ้น</th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <?php $month = date('Y-m-d',strtotime($loan->loan_installment_date . "+0 months"));?>
        <tr>
            <th width="48%">ใหม่ให้ผู้กู้ทราบเป็นที่เรียบร้อย ซึ่งผู้กู้ตกลงชำระดอกเบี้ยเป็นรายเดือนทุกๆ</th>
            <th width="3%">วันที่</th>
            <th width="6%" style="text-align:center; border-bottom-style: dotted; "><?php echo dayThai($loan->loan_installment_date) ?></th>
            <th width="22%">ของเดือน เริ่มงวดแรกภายในวันที่</th>
            <th width="21%" style="text-align:center; border-bottom-style: dotted; "><?php echo dayThai($month).' '.monthThai($month).' '.yearThai($month) ?></th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <?php $months = $loan->loan_payment_year_counter * 12;
            $month = $months - 1;
         $year = date('Y-m-d',strtotime($loan->loan_installment_date . "+".$month." months"));?>
        <tr>
            <th width="2%"></th>
            <th width="3%"><B>ข้อ</B></th>
            <th width="65%">3.ผู้กู้ตกลงจะชำระเงินต้นและดอกเบี้ยดังกล่าวในข้อ 1 และ 2 คืนให้แก่ผู้ให้กู้จนครบถ้วนภายใน วันที่</th>
            <th width="29%" style="text-align:center; border-bottom-style: dotted;"><?php echo dayThai($year).' '.monthThai($year).' '.yearThai($year) ?></th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="100%">ซึ่งต่อไปในสัญญานี้จะเรียกว่า "กำหนดชำระหนี้"</th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="2%"></th>
            <th width="3%"><B>ข้อ</B></th>
            <th width="94%">4.หากผู้กู้ปฎิบัติผิดกำหนดชำระหนี้หรือผิดสัญญาในข้อหนึ่งข้อใดแห่งสัญญานี้ผู้กู้ยินยอมรับผิด และชำระหนี้เงินกู้และดอกเบี้ย พร้อมค่าเสียหายอื่นๆ</th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="100%">ที่ผู้ให้กู้จะพึงได้รับอันเนื่องมาจากการบังคับให้ผู้กู้ชำระหนี้ตามสัญญานี้</th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="7%">หมายเหตุ</th>
            <th width="93%" style="text-align:center; border-bottom-style: dotted; "></th>
        </tr>
        <tr>
            <th width="100%" style="text-align:center; border-bottom-style: dotted; "></th>
        </tr>
        <tr>
            <th width="100%" style="text-align:center; border-bottom-style: dotted;"></th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="100%">ผู้กู้ได้เข้าใจข้อความในหนังสือสัญญานี้โดยตลอดแล้ว จึงได้ลงลายมือชื่อไว้สำคัญต่อหน้าพยาน</th>
        </tr>
        <tr>
            <th style="font-size: 10px;"></th>
        </tr>
        <tr>
            <th width="12%"></th>
            <th width="4%">ลงชื่อ</th>
            <th width="25%" style="text-align:center; border-bottom-style: dotted; "></th>
            <th width="4%">ผู้กู้</th>
            <th width="12%"></th>
            <th width="4%">ลงชื่อ</th>
            <th width="25%" style="text-align:center; border-bottom-style: dotted; "></th>
            <th width="4%">ผู้ให้กู้</th>
            <th width="10%"></th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="15%"></th>
            <th width="1%">(</th>
            <th width="25%">..............................................................</th>
            <th width="1%">)</th>
            <th width="18%"></th>
            <th width="1%">(</th>
            <th width="25%">..............................................................</th>
            <th width="1%">)</th>
            <th width="13%"></th>
        </tr>
        <tr>
            <th style="font-size: 10px;"></th>
        </tr>
        <tr>
            <th width="12%"></th>
            <th width="4%">ลงชื่อ</th>
            <th width="25%" style="text-align:center; border-bottom-style: dotted; "></th>
            <th width="4%">พยาน</th>
            <th width="12%"></th>
            <th width="4%">ลงชื่อ</th>
            <th width="25%" style="text-align:center; border-bottom-style: dotted; "></th>
            <th width="4%">พยาน</th>
            <th width="10%"></th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
        <tr>
            <th width="15%"></th>
            <th width="1%">(</th>
            <th width="25%">..............................................................</th>
            <th width="1%">)</th>
            <th width="18%"></th>
            <th width="1%">(</th>
            <th width="25%">..............................................................</th>
            <th width="1%">)</th>
            <th width="13%"></th>
        </tr>
    </table>
</body>

</html>