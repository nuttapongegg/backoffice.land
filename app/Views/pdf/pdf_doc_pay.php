<!doctype html>
<html lang="en" data-layout="horizontal" data-hor-style="hor-hover" data-logo="centerlogo">

<body>
    <table>
        <tr style="vertical-align:middle;">
            <td width="45%">
                <br>
                <span style="font-size:25px; font-weight:bold;">ใบเสร็จจ่ายเงิน / ใบกำกับภาษี</span><br>
                <span style="font-size:23px; font-weight:bold;">Receipt / Tax Invoice</span>
            </td>
            <td width="54%" align="right" style="vertical-align:middle;">
                <span>ทะเบียนเลขที่ / Registration No. 0345568003383</span><br>
                <span>เลขประจำตัวผู้เสียภาษี / Tax ID. 0345568003383</span><br>
                <span>สาขาที่ออกใบกำกับภาษี สาขาสำนักงานใหญ่ / Tax Invoice issued at head office</span><br>
                <span>เลขที่สาขา 00000</span>
            </td>
        </tr>
        <tr>
            <th style="font-size: 24px;"></th>
        </tr>
                <style>
            .wrap {
                width: 100%;
            }

            .card {
                border: 1px solid #ddd;
            }

            .grid2 {
                width: 100%;
                border-collapse: collapse;
            }

            .rowgap {
                height: 0;
            }


            .cell {
                border: 1px solid #e3e3e3;
                background-color: #f7f7f7;
                padding: 0;
            }


            .inner {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
                border-spacing: 0;
            }

            /* ส่วนหัวข้อ */
            .label {
                width: 43%;
                background-color: #eeeeee;
                font-weight: bold;
                text-align: left;
                padding: 8px 6px 8px 0;
                /* ตัด padding ซ้ายออก */
                line-height: 1.2;
                border-left: none;
            }

            /* ค่าทางขวา */
            .value {
                width: 57%;
                background: transparent;
                text-align: right;
                padding: 8px 10px 8px 6px;
            }

            .sub {
                font-size: 13px;
                font-weight: normal;
                color: #555;
                display: block;
                line-height: 1.65;
                margin-top: 1px;
            }

            .inL {
                display: block;
                padding: 6px 8px 6px 10px;
                line-height: 0.9;
                text-align: left;
            }

            .inR {
                display: block;
                padding: 6px 12px 6px 8px;
                line-height: 1.05;
                text-align: right;
            }

            .sub_table {
                font-size: 13px;
                font-weight: normal;
                color: #555;
                display: block;
                line-height: 1.5;
                margin-top: 0px;
            }

            .main_table {
                font-weight: normal;
                display: block;
                line-height: 0.8;
            }
        </style>
        <table class="wrap" cellpadding="0" cellspacing="0">
            <tr>
                <td width="34%" valign="top" style="height:140px;">
                </td>

                <td width="1%"></td>

                <!-- ตารางขวา -->
                <td width="64%" valign="top">
                    <table class="grid2" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="49%" class="cell"><table class="inner" cellpadding="0" cellspacing="0">
                                <tr valign="middle" style="height:36px;">
                                    <!-- ใช้ bgcolor เพื่อให้พื้นเทาชิดเส้นกรอบจริง -->
                                    <td class="label" width="43%" bgcolor="#eeeeee" valign="middle" style="border-left: none;">
                                    <div class="inL">&nbsp;&nbsp;เลขที่<br><span class="sub">&nbsp;&nbsp;&nbsp;No.</span></div>
                                    </td>
                                    <td class="value" width="57%" valign="middle" align="right">
                                    <div class="inR"><?php echo $docid->doc_number ?>&nbsp;&nbsp;&nbsp;</div>
                                    </td>
                                </tr>
                                </table>
                            </td>
                            <td width="2%"></td>
                            <td width="49%" class="cell"><table class="inner" cellpadding="0" cellspacing="0">
                                    <tr valign="middle" style="height:36px;">
                                        <td class="label" width="43%" bgcolor="#eeeeee" valign="middle" style="border-left: none;">
                                            <div class="inL">&nbsp;&nbsp;เลขที่ใบกำกับภาษี<br><span class="sub">&nbsp;&nbsp;&nbsp;Tax Invoice No.</span></div>
                                        </td>
                                        <td class="value" width="57%" valign="middle" align="right">
                                            <div class="inR">INV-<?php echo $docid->doc_number?>&nbsp;&nbsp;</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3" style="line-height:15px;">&nbsp;</td>
                        </tr>

                        <tr>
                            <!-- ซ้ายล่าง -->
                            <td width="49%" class="cell"><table class="inner" cellpadding="0" cellspacing="0">
                                    <tr valign="middle" style="height:36px;">
                                        <td class="label" width="43%" bgcolor="#eeeeee" valign="middle">
                                            <div class="inL">&nbsp;&nbsp;วันที่ทำรายการ<br><span class="sub">&nbsp;&nbsp;&nbsp;Effective Date</span></div>
                                        </td>
                                        <td class="value" width="57%" valign="middle" align="right">
                                            <div class="inR"><?php echo $docid->formatted_date_doc ?>&nbsp;&nbsp;&nbsp;</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <td width="2%"></td>

                            <!-- ขวาล่าง -->
                            <td width="49%" class="cell"><table class="inner" cellpadding="0" cellspacing="0">
                                    <tr valign="middle" style="height:36px;">
                                        <td class="label" width="43%" bgcolor="#eeeeee" valign="middle">
                                            <div class="inL">&nbsp;&nbsp;วันออกใบกำกับภาษี<br><span class="sub">&nbsp;&nbsp;&nbsp;Issue Date</span></div>
                                        </td>
                                        <td class="value" width="57%" valign="middle" align="right">
                                            <div class="inR"><?php echo $docid->formatted_date_doc ?>&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <tr>
            <th style="font-size: 15px;"></th>
        </tr>
        <tr>
            <th width="60%"><B style="font-size: 22px; color: #3F51B5;">รายการ / List</B></th>
            <th width="39%"></th>
        </tr>
        <tr>
            <th style="font-size: 2px;"></th>
        </tr>
        <tr>
            <th width="99%" style="font-size: 8px;border-bottom-style: solid;"></th>
        </tr>
        <tr>
            <th style="font-size: 5px;"></th>
        </tr>
        <tr>
            <th width="40%" class="main_table" style="text-align:center;"><b>ชื่อรายการ</b><br><span class="sub_table">Item name</span></th>
            <th width="40%" class="main_table" style="text-align:center;"><b>รายละเอียด</b><br><span class="sub_table">Details</span></th>
             <th width="19%" class="main_table" style="text-align:right;"><b>จำนวนเงิน</b><br><span class="sub_table">Amount</span></th>
        </tr>
        <tr>
            <th width="99%" style="font-size: 2px;border-bottom-style: solid;"></th>
        </tr>
        <tr>
            <th style="font-size: 2px;"></th>
        </tr>
        <tr>
            <th width="40%"><?php echo $docid->title ?></th>
            <th width="40%"><?php echo $docid->note ?></th>
            <th width="19%" style="text-align:right;"><?php echo number_format($docid->price, 2) ?></th>
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
    </table>
</body>

</html>