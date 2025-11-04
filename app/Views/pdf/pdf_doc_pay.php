<!doctype html>
<html lang="en" data-layout="horizontal" data-hor-style="hor-hover" data-logo="centerlogo">

<body>
    <table>
        <tr>
            <th width="60%"></th>
            <th width="39%" style="font-size: 22px; text-align:center;border-bottom-style: solid;border-bottom-color: #BEBEBE"><B style="color: #3F51B5;">ใบสำคัญจ่าย/ใบกำกับภาษี</B></th>
        </tr>
        <tr>
            <th style="font-size: 8px;"></th>
        </tr>
        <tr>
            <th width="60%"></th>
            <th width="20%"><B style="color: #3F51B5;">เลขที่ใบกำกับภาษี</B></th>
            <th width="29%"><?php echo $docid->doc_number ?></th>
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
        <tr>
            <th width="99%" style="font-size: 8px;border-bottom-style: solid;"></th>
        </tr>
        <tr>
            <th width="5%" style="text-align:center;">#</th>
            <th width="17%" style="text-align:center;">วันที่</th>
            <th width="57%" style="text-align:center;">รายการ</th>
            <th width="20%" style="text-align:right;">จำนวนเงิน</th>
        </tr>
        <tr>
            <th width="99%" style="font-size: 2px;border-bottom-style: solid;"></th>
        </tr>
        <tr>
            <th style="font-size: 2px;"></th>
        </tr>
        <tr>
            <th width="5%" style="text-align:center;">1</th>
            <th width="17%" style="text-align:center;"><?php echo $docid->formatted_date_doc ?></th>
            <th width="57%"><?php echo $docid->title ?></th>
            <th width="20%" style="text-align:right;"><?php echo number_format($docid->price, 2) ?></th>
        </tr>
        <tr>
            <th width="99%" style="font-size: 2px;border-bottom-style: solid;"></th>
        </tr>
        <tr>
            <th style="font-size: 10px;"></th>
        </tr>
        <tr>
            <th width="60%">(<?php echo numToThaiBath(number_format($docid->price, 2)); ?>)</th>
            <th width="20%" style="text-align:right;"><B style="color: #3F51B5;">จำนวนเงินรวมทั้งสิ้น</B></th>
            <th width="19%" style="text-align:right;"><?php echo number_format($docid->price, 2) ?> บาท</th>
        </tr>
        <tr>
            <th style="font-size: 9px;"></th>
        </tr>
        <tr>
            <th width="60%" style="font-size: 2px;"></th>
            <th width="39%" style="font-size: 2px;border-top-style: solid;"></th>
        </tr>
        <tr>
            <th style="font-size: 6px;"></th>
        </tr>
    </table>
    <?php if ($docid->note != '') {
        $note = '
            <table>
                <tr>
                    <th width="100%"><B style="color: #3F51B5;">หมายเหตุ</B></th>
                </tr>
                <tr>
                    <th style="font-size: 2px;"></th>
                </tr>
                <tr>
                    <th width="100%">' . $docid->note . '</th>
                </tr>
            </table>
            ';
    } else {
        $note = '<br><br>';
    } ?>
    <?php echo $note; ?>
    <br><br><br><br><br><br>
</body>

</html>