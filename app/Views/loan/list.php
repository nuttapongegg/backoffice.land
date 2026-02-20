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

        /* container หลักของ summary */
        .loan-rows {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* แต่ละแถวใหญ่ (ซ้าย + ขวา) */
        .loan-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* ด้านซ้าย/ขวา แต่ละแถว */
        .loan-row-left,
        .loan-row-right {
            flex: 0 0 100%;
            max-width: 100%;
        }

        @media (min-width: 1200px) {
            .loan-row-left {
                flex: 0 0 calc(55% - 6px);
                /* 6px = ครึ่งของ gap 12px */
                max-width: calc(55% - 6px);
            }

            .loan-row-right {
                flex: 0 0 calc(45% - 6px);
                max-width: calc(45% - 6px);
            }
        }

        /* กริดด้านซ้าย: 3 กล่องต่อแถว (6 กล่อง = 2 แถว) */
        .loan-row-left-grid {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        @media (min-width: 768px) {
            .loan-row-left-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        /* กริดด้านขวา: 4 กล่องต่อแถว (8 กล่อง = 2 แถว) */
        .loan-row-right-grid {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        @media (min-width: 768px) {
            .loan-row-right-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1200px) {
            .loan-row-right-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                /* [%1][%2][%3][%4] */
            }
        }

        /* การ์ดแต่ละ metric – โทนเรียบแบบที่ใช้อยู่ */
        /* DEFAULT (พื้นฐาน = DARK MODE เดิม) */
        .loan-metric-card {
            background: linear-gradient(145deg, #1e232d, #10131b);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 10px 12px;
            text-align: center;
            min-height: 85px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.45);
        }

        .loan-metric-label {
            font-weight: 600;
            font-size: 14px;
            color: #ffffff;
        }

        .loan-metric-value {
            margin-top: 4px;
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
        }

        .loan-metric-sub {
            margin-top: 2px;
            font-size: 11px;
            color: #9ca3af;
        }


        /* VERSION มือถือ: กริด 2 คอลัมน์, การ์ดเตี้ย, ไม่เอา sub text */
        @media (max-width: 575.98px) {

            /* ซ้าย/ขวา ใช้เต็มกว้างทีละ block */
            .loan-row {
                flex-direction: column;
                gap: 8px;
            }

            .loan-row-left,
            .loan-row-right {
                flex: 0 0 100%;
                max-width: 100%;
            }

            /* ให้ทั้งฝั่งซ้ายและขวาเป็นกริด 2 คอลัมน์ */
            .loan-row-left-grid,
            .loan-row-right-grid {
                display: grid;
                gap: 8px;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                /* 2 การ์ดต่อแถว */
            }

            /* การ์ดเตี้ยลง */
            .loan-metric-card {
                /* background: linear-gradient(135deg, #f9fafb, #e5e7eb); */
                border-radius: 12px;
                border: 1px solid rgba(148, 163, 184, 0.35);
                /* ทำให้บางลงนิด */

                padding: 6px 10px;
                /* ↓ ลดระยะห่างบนล่างลง */
                min-height: 70px;
                /* ↓ ลดความสูง */

                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center;

                box-shadow: 0 3px 8px rgba(15, 23, 42, 0.06);
                /* ↓ เงาเบาขึ้น */
            }


            /* label เล็กลงหน่อย */
            .loan-metric-label {
                font-size: 10px;
                line-height: 1.2;
            }

            /* ตัวเลขยังเด่น แต่ไม่ใหญ่เกิน */
            .loan-metric-value {
                margin-top: 2px;
                font-size: 14px;
                font-weight: 700;
            }

            /* มือถือไม่ต้องมี sub text เพื่อให้เตี้ยลง */
            .loan-metric-sub {
                display: none;
            }

            .badge {
                display: none;
            }

            /* ถ้ารู้สึกว่าการ์ดชิดขอบเกินไปค่อยลด/เพิ่ม padding ของ card-body ตรงนี้ได้ */
            .card .card-body {
                padding: 10px 10px;
            }

            /* ใช้ flex-center สำหรับกริดที่มีการ์ด 1 ใบ */
            .loan-row-left-grid:only-child,
            .loan-row-right-grid:only-child {
                display: flex !important;
                justify-content: center;
            }

            /* และให้การ์ดไม่ยืดเต็มแถว */
            .loan-row-left-grid:only-child .loan-metric-card,
            .loan-row-right-grid:only-child .loan-metric-card {
                width: 85%;
                /* หรือ 90% แล้วแต่ชอบ */
                max-width: 280px;
            }
        }

        .roi-inline {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            /* ระยะห่างระหว่าง % กับข้อความ */
        }

        .badge {
            font-size: 11px;
            font-weight: 600;
            opacity: 0.9;
        }

        /* =========================
        DARK THEME OVERRIDE
        ========================= */

        /* การ์ดในโหมดมืด */
        [data-theme-color="dark"] .loan-metric-card {
            background: linear-gradient(145deg, #1e232d, #10131b);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.45);
            color: #e5e7eb;
        }

        [data-theme-color="dark"] .loan-metric-label {
            color: #ffffff;
        }

        [data-theme-color="dark"] .loan-metric-value {
            color: #ffffff;
        }

        [data-theme-color="dark"] .loan-metric-sub {
            color: #9ca3af;
        }

        /* badge สีต่าง ๆ ในโหมดมืด (ใช้ class เดิมที่คุณมีอยู่แล้ว) */
        /* [data-theme-color="dark"] .badge.tx-info {
            color: #38bdf8 !important;
        }

        [data-theme-color="dark"] .badge.tx-success {
            color: #4ade80 !important;
        }

        [data-theme-color="dark"] .badge.tx-warning {
            color: #facc15 !important;
        }

        [data-theme-color="dark"] .badge.tx-secondary {
            color: #94a3b8 !important;
        }

        [data-theme-color="dark"] .badge.tx-danger {
            color: #f87171 !important;
        } */

        /* ถ้าอยากให้มือถือในโหมดมืดก็เป็นกล่องเข้มเหมือนกัน */
        @media (max-width: 575.98px) {
            [data-theme-color="dark"] .loan-metric-card {
                background: linear-gradient(145deg, #1e232d, #10131b);
                border: 1px solid rgba(255, 255, 255, 0.08);
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.5);
            }
        }

        [data-theme-color="light"] .loan-metric-card {
            background: linear-gradient(135deg, #f9fafb, #e5e7eb);
            border: 1px solid rgba(148, 163, 184, 0.5);
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
            color: #111827;
        }

        [data-theme-color="light"] .loan-metric-label {
            color: #111827;
        }

        [data-theme-color="light"] .loan-metric-value {
            color: #0f172a;
        }

        [data-theme-color="light"] .loan-metric-sub {
            color: #6b7280;
        }

        .filter-divider {
            width: 1px;
            height: 22px;
            background: var(--primary-bg-color);
            margin: 0 6px;
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
                                <div id="count_car"></div>
                                <!-- <div id="count_loan_on" style="color: #FF8800;">สินเชื่อ 0 ราย</div> -->
                            </div>
                            <div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary Loan_open text-center" data-bs-toggle="modal" data-bs-target="#modalAddLoan"><i class="fa-solid fa-plus text-center" id="addStockCar" name="addStockCar"></i>&nbsp;&nbsp;เพิ่มสินเชื่อ</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-wrap">
                            <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
                            </div>
                            <div class="panel tabs-style1">
                                <div class="panel-body">
                                    <div class="d-flex flex-wrap gap-2 mb-3 justify-content-end align-items-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary js-range" data-range="today">วันนี้</button>

                                        <button type="button" class="btn btn-sm btn-outline-primary js-range" data-range="tomorrow">พรุ่งนี้</button>
                                        <!-- คั่นแบบเบา ๆ -->
                                        <span class="filter-divider"></span>
                                        <!-- ช่วงเวลา -->
                                        <button type="button" class="btn btn-sm btn-outline-primary js-range" data-range="this_month">เดือนนี้</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary js-range" data-range="last_month">เดือนที่แล้ว</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary js-range" data-range="this_year">ปีนี้</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary js-range" data-range="last_year">ปีที่แล้ว</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary js-range" data-range="all">ทั้งหมด</button>

                                        <!-- คั่นแบบเบา ๆ -->
                                        <span class="filter-divider"></span>

                                        <!-- ประเภทสินเชื่อ -->
                                        <button type="button" class="btn btn-sm btn-outline-primary js-loan-type" data-type="เงินสด">
                                            เงินสด
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary js-loan-type" data-type="เช่าซื้อ">
                                            เช่าซื้อ
                                        </button>
                                    </div>

                                    <div class="row justify-content-end">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="daterange_loan" placeholder="เริ่มหาวันที่ ถึง วันที่ (กรณีว่าง จะดึงข้อมูลทั้งหมด)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive double-scroll">
                                        <table class="table table-bordered text-nowrap border-bottom" id="tableLoanOn">
                                            <thead>
                                                <tr>
                                                    <th class="wd-5p">#</th>
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
                                                    <th class="wd-30p text-center">GAP</th>
                                                    <th class="wd-30p text-center">ชำระแล้วเป็นเงิน</th>
                                                    <th class="wd-30p text-center">ROI</th>
                                                    <th class="wd-30p text-center">งวดละ</th>
                                                    <th class="wd-20p text-center">เครดิต</th>
                                                    <th class="wd-20p text-center">ประเภท</th>
                                                    <th class="wd-30p text-center">เวลา</th>
                                                    <th class="wd-30p text-center">ชำระแล้ว</th>
                                                    <th class="wd-30p text-center">จำนวนงวด</th>
                                                    <th class="wd-30p text-center">ดอกเบี้ย</th>
                                                    <th class="wd-30p text-center">รายละเอียด</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr class="tx-black bg-primary">
                                                    <th colspan="8" style="padding:12px;">
                                                        <h6 class="tx-left mt-2"><b>รวม</b></h6>
                                                    </th>
                                                    <th class="text-right" style="padding:12px;font-size:15px;font-weight:normal;"></th>
                                                    <th colspan="3" style="padding:12px;font-size:15px;font-weight:normal;"></th>
                                                    <th class="text-right" style="padding:12px;font-size:15px;font-weight:normal;"></th>
                                                    <th style="padding:12px;font-size:15px;font-weight:normal;"></th>
                                                    <th class="text-right" style="padding:12px;font-size:15px;font-weight:normal;"></th>
                                                    <th style="padding:12px;font-size:15px;font-weight:normal;"></th>
                                                    <th class="text-right" style="padding:12px;font-size:15px;font-weight:normal;"></th>
                                                    <th colspan="7" style="padding:12px;font-size:15px;font-weight:normal;"></th>
                                                </tr>
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
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="card-title justify-content-between d-flex">
                            <div>
                                <div class="tx-primary tx-18" id="count_car">รายการชำระสินเชื่อ</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-wrap">
                            <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
                            </div>
                            <div class="panel tabs-style1">
                                <div class="panel-body">
                                    <div class="row justify-content-end">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="daterange_loan_payments" placeholder="เริ่มหาวันที่ ถึง วันที่ (กรณีว่าง จะดึงข้อมูลทั้งหมด)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive double-scroll">
                                        <table class="table table-bordered text-nowrap border-bottom" id="tableLoanPayments">
                                            <thead>
                                                <tr>
                                                    <th class="wd-5p text-center">#</th>
                                                    <th class="wd-35p text-center">รายการ</th>
                                                    <th class="wd-15p text-center">จำนวนเงิน</th>
                                                    <th class="wd-45p text-center">รายละเอียด</th>
                                                    <th class="wd-15p text-center">ผู้ทำรายการ</th>
                                                    <th class="wd-15p text-center">ชื่อบัญชีรับชำระ</th>
                                                    <th class="wd-15p text-center">จำนวนเงินในบัญชี</th>
                                                    <th class="wd-25p text-center">วันที่ชำระ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="card-title justify-content-between d-flex">
                            <div>
                                <div id="count_car_history"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-wrap">
                            <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
                            </div>
                            <div class="panel tabs-style1">
                                <div class="panel-body">
                                    <div class="d-flex flex-wrap gap-2 mb-3 justify-content-end align-items-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary js-range-close" data-range="this_month">เดือนนี้</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary js-range-close" data-range="last_month">เดือนที่แล้ว</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary js-range-close" data-range="this_year">ปีนี้</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary js-range-close" data-range="last_year">ปีที่แล้ว</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary js-range-close" data-range="all">ทั้งหมด</button>

                                        <span class="filter-divider"></span>

                                        <!-- ไม่เลือก = ทั้งหมด -->
                                        <button type="button" class="btn btn-sm btn-outline-primary js-loan-type-close" data-type="เงินสด">เงินสด</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary js-loan-type-close" data-type="เช่าซื้อ">เช่าซื้อ</button>
                                    </div>
                                    <div class="row justify-content-end">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-text">
                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="daterange_loan_close" placeholder="เริ่มหาวันที่ ถึง วันที่ (กรณีว่าง จะดึงข้อมูลทั้งหมด)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive double-scroll">
                                        <table class="table table-bordered text-nowrap border-bottom" id="tableLoanClose">
                                            <thead>
                                                <tr>
                                                    <th class="wd-5p">#</th>
                                                    <th class="wd-15p text-center">เลขที่สินเชื่อ</th>
                                                    <th class="wd-40p text-center">ชื่อลูกค้า</th>
                                                    <th class="wd-20p text-center">ชื่อสถานที่</th>
                                                    <th class="wd-20p text-center">เนื้อที่</th>
                                                    <th class="wd-20p text-center">เลขที่ดิน</th>
                                                    <th class="wd-20p text-center">วันที่ปิดสินเชื่อ</th>
                                                    <th class="wd-20p text-center">ประเภท</th>
                                                    <th class="wd-40p text-center">วงเงิน</th>
                                                    <th class="wd-30p text-center">เวลา</th>
                                                    <th class="wd-30p text-center">สถานะ</th>
                                                    <th class="wd-30p text-center">ชำระแล้วเป็นเงิน</th>
                                                    <th class="wd-30p text-center">ยอดชำระปิดสินเชื่อ</th>
                                                    <th class="wd-20p text-center">วันที่ขอสินเชื่อ</th>
                                                    <!-- <th class="wd-20p text-center">วันเริ่มชำระ</th> -->
                                                    <th class="wd-30p text-center">ดอกเบี้ย</th>
                                                    <th class="wd-30p text-center">จำนวนงวด</th>
                                                    <th class="wd-30p text-center">ชำระแล้ว</th>
                                                    <th class="wd-30p text-center">งวดละ</th>
                                                    <th class="wd-30p text-center">ROI</th>
                                                    <th class="wd-30p text-center">รายละเอียด</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr class="tx-black bg-primary">
                                                    <th colspan="8" style="padding: 12px;">
                                                        <h6 class="tx-left mt-2"><b>รวม</b></h6>
                                                    </th>
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                    <th colspan="2" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                    <th colspan="4" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                    <th class="text-right" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                    <th colspan="2" style="padding: 12px;font-size: 15px;font-weight: normal;"></th>
                                                </tr>
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
            <div id="SummarizeLoan" class="card mt-3">
            </div>
        </div>


        <div class="modal fade" id="modalAddLoan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <input type="hidden" name="carStockDetailBuySaleNoVat" id="carStockDetailBuySaleNoVat" value="" />
            <input type="hidden" name="carStockDetailBuySaleDow" id="carStockDetailBuySaleDow" value="" />
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">เปิดสินเชื่อ</h5>
                        <button type="button" class="btn-close modalAddLoanClose"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="panel tabs-style5 w-fit-content mx-auto">
                            <div class="panel-head">
                                <ul class="nav nav-tabs bg-white">
                                    <li class="nav-item tabPaymentType"><a class="nav-link tx-14 font-weight-semibold tabPaymentType1 active" data-bs-toggle="tab" href="javascript:void(0);" data-type="เงินสด">เงินสด</a></li>
                                    <li class="nav-item tabPaymentType"><a class="nav-link tx-14 font-weight-semibold tabPaymentType2" data-bs-toggle="tab" href="javascript:void(0);" data-type="เช่าซื้อ">เช่าซื้อ</a></li>
                                </ul>
                            </div>
                        </div>
                        <?php $FORM_KEY = 'FORM_KEY_' . strtotime('now') . '_' . rand(10, 100); ?>
                        <form method="POST" enctype="multipart/form-data" name="formAddLoan" id="<?php echo $FORM_KEY; ?>" data-form-key="<?php echo $FORM_KEY; ?>" novalidate>
                            <input type="hidden" name="loan_type" value="เงินสด">
                            <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary mt-2">ข้อมูลพื้นฐาน</p>
                            <div class="row mb-3">
                                <div class="col-6">
                                </div>
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">วันที่ออกสินเชื่อ</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                </div>
                                                <input type="text" class="form-control dateToBooking" name="date_to_loan" id="date_to_loan" placeholder="เลือกวันที่" value="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">

                                </div>
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">กำหนดชำระสินเชื่อ</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                </div>
                                                <input type="text" class="form-control dateToBooking" name="date_to_loan_pay_date" id="date_to_loan_pay_date" placeholder="เลือกวันที่" value="<?php echo date('Y-m-d', strtotime('+1 month')); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-2">ชื่อลูกค้า <span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input name="customer_name" id="customer_name" class="form-control" type="text" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">เจ้าหน้าที่ <span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="employee_name" id="employee_name" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 tx-right">
                                            <label class="form-label mt-0">ชื่อสถานที่</label>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="loan_address" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">เลขที่ดิน<span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="loan_number" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">เนื้อที่</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="loan_area" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0" for="account_id">บัญชีสินเชื่อ<span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <select name="account_id" id='account_id' class="form-control custom-select" data-bs-placeholder="Select ..." required>
                                                    <?php if ($land_accounts) : ?>
                                                        <?php foreach ($land_accounts as $land_account) { ?>
                                                            <option value="<?php echo $land_account->id; ?>"><?php echo $land_account->land_account_name; ?></option>
                                                        <?php } ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 tx-right">
                                            <label class="form-label mt-0">ยอดสินเชื่อ(ไม่รวม Vat) <span class="tx-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group mb-3">
                                                <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" value="0" name="loan_without_vat" id="loan_without_vat" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==10) return false;" type="number" required>
                                                <span class="input-group-text" id="basic-addon2">บาท</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="customerSection">
                                <p class="font-weight-semibold tx-17 pb-2 border-bottom-dashed mt-2 tx-primary">ข้อมูลลูกค้า</p>

                                <!-- ปุ่ม AI Auto Input -->
                                <div class="mt-2" style="text-align: right;">
                                    <a href="javascript:void(0);" class="btn btn-outline-primary" id="btnAiAutoInputCapture" style="display:none;">
                                        📷 ถ่ายรูป
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-outline-primary" id="btnAiAutoInput">
                                        📂 เลือกไฟล์
                                    </a>
                                </div>

                                <!-- ฟอร์ม OCR -->
                                <div id="detectImageForm" style="display:none;">
                                    <div class="row">
                                        <div class="col text-center">
                                            <img id="imagePreview" width="32%" class="img-thumbnail" /><br>
                                            <button type="button" class="btn btn-outline-danger btn-rounded mt-3" id="btnAiAutoInputClear">ยกเลิก</button>
                                            <button type="button" class="btn btn-success btn-rounded mt-3" id="btnAiAutoInputSubmit">บันทึก</button>
                                        </div>
                                    </div>
                                    <div style="display:none;">
                                        <input type="file" id="imageFile" accept="image/*" />
                                    </div>
                                    <hr>
                                </div>

                                <!-- ฟอร์มข้อมูลลูกค้า -->
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ชื่อ-นามสกุล <span class="tx-danger">*</span></label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="fullname" name="fullname">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">เบอร์ติดต่อ <span class="tx-danger">*</span></label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="phone" name="phone">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">เลขบัตรประชาชน <span class="tx-danger">*</span></label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input class="form-control cardIDMask" placeholder="_-____-_____-__-_" type="text" id="card_id" name="card_id">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">อีเมล</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="อีเมล" type="text" id="customer_email" name="customer_email">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">วัน/เดือน/ปีเกิด<span class="tx-danger">*</span></label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input class="form-control dateMask" placeholder="__/__/____" type="text" id="birthday" name="birthday">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">เพศ<span class="tx-danger">*</span></label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <select name="gender" id="gender" class="form-control form-select">
                                                        <option value="">-- เลือกเพศ --</option>
                                                        <option value="ชาย">ชาย</option>
                                                        <option value="หญิง">หญิง</option>
                                                        <option value="เพศทางเลือก">เพศทางเลือก</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row align-items-center">
                                            <div class="col-md-2 tx-right">
                                                <label class="form-label mt-0">ที่อยู่<span class="tx-danger">*</span></label>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <textarea class="form-control" rows="2" name="address" id="address"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="bookingWrapperFormPaymentType">
                                <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary mt-2">ข้อมูลการคำนวนรายการสินเชื่อ</p>
                                <div class="row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ยอดสินเชื่อ</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="money_loan" id="money_loan" type="text" readonly>
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">จำนวนปี</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input class="form-control" name="payment_year_counter" id="payment_year_counter" type="number" value="4" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==3) return false;" required>
                                                    <span class="input-group-text" id="basic-addon2">ปี</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ยอดดอกเบี้ยรวม</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="total_loan_interest" id="total_loan_interest" type="text" value="" readonly>
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ดอกเบี้ย/ปี</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input name="payment_interest" id="payment_interest" class="form-control" type="flot" value="1" step="0.01" pattern="/^-?\d+\.?\d*$/" onkeypress="if(this.value.length==5) return false;" required>
                                                    <span class=" input-group-text" id="basic-addon2">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0" for="car_name_update">ยอดสินเชื่อรวม</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="total_loan" id="total_loan" type="text" value="" readonly>
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">งวดละ</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="pricePerMonth" id="pricePerMonth" type="text" readonly>
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6"></div>
                                </div>
                            </div>
                            <div id="other_cash">
                                <p class="font-weight-semibold tx-15 pb-2 border-bottom-dashed tx-primary mt-2">ค่าใช้จ่ายอื่น ๆ</p>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ค่าดำเนินการ</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="charges_process" id="charges_process" type="text" value="0">
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ยอดจ่ายจริง</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="really_pay_loan" id="really_pay_loan" type="text" readonly>
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ค่าโอน</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="charges_transfer" id="charges_transfer" type="text" value="0">
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 tx-right">
                                                <label class="form-label mt-0">ค่าใช้จ่ายอื่น ๆ</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input aria-describedby="basic-addon2" aria-label="" class="form-control price" placeholder="" name="charges_etc" id="charges_etc" type="text" value="0">
                                                    <span class="input-group-text" id="basic-addon2">บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row align-items-center">
                                            <div class="col-md-2 tx-right">
                                                <label class="form-label mt-0" for="remark">หมายเหตุ</label>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="หมายเหตุ..." id="remark" name="remark"></ร>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div style="display: flex; justify-content: center;">
                                <button class="btn btn-primary btn-block btn-add-loan" type="button">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- main-content closed -->
</div>