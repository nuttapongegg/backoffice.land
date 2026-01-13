<?php

namespace App\Controllers\api;

date_default_timezone_set('Asia/Jakarta');

use App\Controllers\BaseController;

class Loan extends BaseController
{
    public function __construct()
    {
        /*
        | -------------------------------------------------------------------------
        | SET ENVIRONMENT
        | -------------------------------------------------------------------------
        */

        /*
        | -------------------------------------------------------------------------
        | SET UTILITIES
        | -------------------------------------------------------------------------
        */

        // Model
    }

    public function ajaxDataTableLandPayment($month, $years)
    {
        $allowed_origins = [
            'http://localhost:8080',
            'https://ceo.evxspst.com'
        ];

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        } else {
            header('Access-Control-Allow-Origin: null');
        }
        // header('Access-Control-Allow-Origin: http://localhost:8080');
        // header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit; // Handle preflight request
        }

        $response['code'] = 200;
        $response['message'] = '';

        try {

            // โหลดโมเดล
            $LandlogsModel = new \App\Models\LandlogsModel();
            // ดึงข้อมูลจากโมเดล
            $dataLandLogs = $LandlogsModel->getLandlogsAllCreated($month, $years);

            // กำหนดตัวแปร HTML ที่จะเก็บข้อมูล
            $html = '';
            $html .= '
                <table class="table table-bordered table-striped">
                    <tbody>                   
                        <tr>
                            <td><h6 class="text-center p-2 mb-0">วันที่</h6></td>
                            <td><h6 class="text-center p-2 mb-0">ยอดวงเงินกู้รวม</h6></td>
                            <td><h6 class="text-center p-2 mb-0">เงินสดในบัญชี</h6></td>
                            <td><h6 class="text-center p-2 mb-0">ดอกเบี้ยที่เก็บแล้ว</h6></td>
                            <td><h6 class="text-center p-2 mb-0">ทรัพย์สินสุทธิ</h6></td>
                        </tr>
            ';

            foreach ($dataLandLogs as $dataLandLog) {
                $html .= '          
                        <tr>
                            <td><h6 class="text-center p-2 mb-0">' . $dataLandLog->formatted_date . '</h6></td>
                            <td><h6 class="p-2 mb-0" style="text-align: right;">' . number_format($dataLandLog->land_logs_loan_amount, 2) . '</h6></td>
                            <td><h6 class="p-2 mb-0" style="text-align: right;">' . number_format($dataLandLog->land_logs_cash_flow, 2) . '</h6></td>
                            <td><h6 class="p-2 mb-0" style="text-align: right;">' . number_format($dataLandLog->land_logs_interest, 2) . '</h6></td>
                            <td><h6 class="p-2 mb-0" style="text-align: right;">' . number_format($dataLandLog->land_logs_summary_net, 2) . '</h6></td>
                        </tr>
            ';
            }

            $html .= '</tbody></table>';

            $response['data'] = $html;
            $response['message'] = 'success';

            return $this->response
                ->setStatusCode($response['code'])
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {

            $response['code'] = 500;
            $response['message'] = 'error';

            return $this->response
                ->setStatusCode($response['code'])
                ->setContentType('application/json')
                ->setJSON($response);
        }
    }

    public function ajaxDataTableLandPaymentToDay()
    {
        $allowed_origins = [
            'http://localhost:8080',
            'https://ceo.evxspst.com'
        ];

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        } else {
            header('Access-Control-Allow-Origin: null');
        }
        // header('Access-Control-Allow-Origin: http://localhost:8080');
        // header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit; // Handle preflight request
        }

        $response['code'] = 200;
        $response['message'] = '';

        try {

            // โหลดโมเดล
            $LandlogsModel = new \App\Models\LandlogsModel();
            // ดึงข้อมูลจากโมเดล
            $dataLandLogs = $LandlogsModel->getLandlogsAllCreatedToDay();

            // ตรวจสอบว่ามีค่า land_logs_cash_flow หรือไม่ ถ้าไม่มีให้กำหนดเป็น 0
            $land_logs_cash_flow = !empty($dataLandLogs->land_logs_cash_flow) ? $dataLandLogs->land_logs_cash_flow : 0;

            $json_data = array(
                "LandLord_S" => number_format($land_logs_cash_flow, 2, '.', '')
            );

            return $this->response
                ->setStatusCode($response['code'])
                ->setContentType('application/json')
                ->setJSON($json_data);
        } catch (\Exception $e) {

            $response['code'] = 500;
            $response['message'] = 'error';

            return $this->response
                ->setStatusCode($response['code'])
                ->setContentType('application/json')
                ->setJSON($response);
        }
    }

    public function ajaxDataTableLandDay($days)
    {
        $allowed_origins = [
            'http://localhost:8080',
            'https://ceo.evxspst.com'
        ];

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        } else {
            header('Access-Control-Allow-Origin: null');
        }
        // header('Access-Control-Allow-Origin: http://localhost:8080');
        // header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit; // Handle preflight request
        }

        $response['code'] = 200;
        $response['message'] = '';

        try {

            // โหลดโมเดล
            $LandlogsModel = new \App\Models\LandlogsModel();
            // ดึงข้อมูลจากโมเดล
            $dataLandLogs = $LandlogsModel->getLandlogsAllByDay($days);
            // $land_logs_cash_flow = 0;
            // $land_logs_cash_flow = $dataLandLogs->land_logs_cash_flow;
            // $json_data = array(
            //     "LandLord_S" => number_format($land_logs_cash_flow, 2, '.', '')
            // );
            $dates = [];
            for ($i = $days; $i > 0; $i--) {
                $dates[] = date("d M Y", strtotime("-$i days"));
            }
            $LandLord = [];
            foreach ($dates as $date) {
                $LandLord[$date] = 0;
            }

            // ประมวลผลข้อมูลจากฐานข้อมูล
            foreach ($dataLandLogs as $row) {
                $date = date("d M Y", strtotime($row->formatted_date));

                // กำหนดค่าที่ได้รับจากฐานข้อมูล (ตัวอย่างเช่น กำหนดให้บัญชี EVX)
                $LandLord[$date] = number_format($row->land_logs_summary_net, 2, '.', '');
            }

            // ส่งผลลัพธ์ในรูปแบบ JSON
            $json_data = array(
                "LandLord" => array_values($LandLord)
            );

            return $this->response
                ->setStatusCode($response['code'])
                ->setContentType('application/json')
                ->setJSON($json_data);
        } catch (\Exception $e) {

            $response['code'] = 500;
            $response['message'] = 'error';

            return $this->response
                ->setStatusCode($response['code'])
                ->setContentType('application/json')
                ->setJSON($response);
        }
    }

    public function LandDataDocDay()
    {
        $allowed_origins = [
            'http://localhost:8080',
            'https://ceo.evxspst.com'
        ];

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        } else {
            header('Access-Control-Allow-Origin: null');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit; // Handle preflight request
        }

        $response = [
            'code' => 200,
            'message' => '',
            'data' => []
        ];

        try {
            // โหลดโมเดล
            $LoanModel = new \App\Models\LoanModel();
            // $LoanRevenues = $LoanModel->getLoanRevenuesDay();
            $LoanProcess = $LoanModel->getSumLoanIncomeToday();
            $LoannPayment = $LoanModel->getSumLoanPaymentToday();
            // $LoanExpenses = $LoanModel->getLoanExpensesDay();
            $DocumentModel = new \App\Models\DocumentModel();
            $Docs = $DocumentModel->getDocToDay();

            $LoanProcesstToday =
                ($LoanProcess->total_payment_process ?? 0)
                + ($LoanProcess->total_tranfer ?? 0)
                + ($LoanProcess->total_payment_other ?? 0);

            $loanPaymentToday = $LoannPayment->total_loan_payment_today ?? 0;

            $Revenue = $LoanProcesstToday + $loanPaymentToday;


            $Expense = $Docs->total_expense ?? 0;

            $sum = 0;

            // รวมค่าของ setting_land_report_money จาก LoanRevenues
            // foreach ($LoanRevenues as $LoanRevenue) {
            //     $Revenue += $LoanRevenue->setting_land_report_money;
            // }

            // รวมค่าของ setting_land_report_money จาก LoanExpenses
            // foreach ($LoanExpenses as $LoanExpense) {
            //     $Expense += $LoanExpense->setting_land_report_money;
            // }

            $sum = $Revenue - $Expense;

            $SettingLandModel = new \App\Models\SettingLandModel();
            $land_accounts = $SettingLandModel->getSettingLandAll();
            $sum_land_account = 0;
            foreach ($land_accounts as $land_account) {
                $sum_land_account = $sum_land_account + $land_account->land_account_cash;
            }

            // JSON Response
            $response['data'] = [
                'date' => date('Y-m-d'),
                'income' => $Revenue,     // รายรับ
                'expense' => $Expense,   // รายจ่าย
                'profit' => $sum,   // กำไร
                'cash_flow' => $sum_land_account
            ];

            return $this->response
                ->setStatusCode(200)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            $response['code'] = 500;
            $response['message'] = 'error: ' . $e->getMessage();

            return $this->response
                ->setStatusCode($response['code'])
                ->setContentType('application/json')
                ->setJSON($response);
        }
    }

    public function ajaxLandNetProfitMonthlyByYear($year)
    {
        $allowed_origins = [
            'http://localhost:8080',
            'https://ceo.evxspst.com'
        ];

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        } else {
            header('Access-Control-Allow-Origin: null');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit;
        }

        try {
            $year = (int)$year;
            if ($year < 2000 || $year > 2100) {
                return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setJSON(['success' => 0, 'message' => 'invalid year']);
            }

            $DocumentModel = new \App\Models\DocumentModel();
            $LoanModel = new \App\Models\LoanModel();

            // ✅ ใช้ source เดิมที่คุณเคยคำนวณใน ajaxTablesReportRevenues()
            $documentmonth     = $DocumentModel->getrevenue($year);
            $loanprocessmonths = $LoanModel->getLoanProcessMonths($year);
            $LoanPaymentMonths = $LoanModel->getListLoanPaymentMonths($year);

            $months = range(1, 12);

            // ค่าเริ่มต้นทุกเดือน = 0
            $expenses    = array_fill(1, 12, 0.0); // รายจ่าย (ใบสำคัญจ่าย)
            $process     = array_fill(1, 12, 0.0); // รายรับ(ค่าดำเนินการ)
            $loanPayment = array_fill(1, 12, 0.0); // ชำระค่างวดจริง

            // ----- รายจ่ายรายเดือนจากใบสำคัญ -----
            foreach ($documentmonth as $doc) {
                $m = (int)$doc->doc_month;
                if ($m < 1 || $m > 12) continue;

                if ($doc->doc_type === 'ใบสำคัญจ่าย') {
                    $expenses[$m] = (float)$doc->doc_sum_price;
                }
            }

            // ----- รายรับ(ค่าดำเนินการ) รายเดือน -----
            foreach ($loanprocessmonths as $loan) {
                $m = (int)$loan->loan_created_payment;
                if ($m < 1 || $m > 12) continue;

                $process[$m] = (float)$loan->total_payment_process
                    + (float)$loan->total_tranfer
                    + (float)$loan->total_payment_other;
            }

            // ----- รายรับ(ค่างวดจริง) รายเดือน -----
            foreach ($LoanPaymentMonths as $row) {
                $m = (int)$row->loan_created_payment;
                if ($m < 1 || $m > 12) continue;

                $loanPayment[$m] += (float)$row->setting_land_report_money;
            }

            // ----- คำนวณกำไรสุทธิรายเดือน (เลขจริง) -----
            $netByMonth = [];
            $sumNet = 0.0;

            foreach ($months as $m) {
                $sumReceipt = $process[$m] + $loanPayment[$m];
                $net = $sumReceipt - $expenses[$m]; // ✅ กำไรสุทธิรายเดือน

                $netByMonth[$m] = $net;
                $sumNet += $net;
            }

            // ✅ ส่ง JSON แบบเอาไปใช้เติมคอลัมน์ได้ทันที
            return $this->response
                ->setStatusCode(200)
                ->setContentType('application/json')
                ->setJSON([
                    'success' => 1,
                    'year'    => $year,
                    'months'  => $netByMonth, // 1..12 (ตัวเลขจริง)
                    'total'   => $sumNet      // รวมทั้งปี (ตัวเลขจริง)
                ]);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(500)
                ->setContentType('application/json')
                ->setJSON([
                    'success' => 0,
                    'message' => $e->getMessage()
                ]);
        }
    }

    public function ajaxLandMonthlySummaryByYear($year)
    {
        // ===== CORS (เหมือนของเดิม) =====
        $allowed_origins = [
            'http://localhost:8080',
            'https://ceo.evxspst.com'
        ];

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        } else {
            header('Access-Control-Allow-Origin: null');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

        try {
            $year = (int)$year;
            if ($year < 2000 || $year > 2100) {
                return $this->response
                    ->setStatusCode(400)
                    ->setContentType('application/json')
                    ->setJSON(['success' => 0, 'message' => 'invalid year']);
            }

            $DocumentModel = new \App\Models\DocumentModel();
            $LoanModel     = new \App\Models\LoanModel();

            // ✅ แหล่งข้อมูลเดิมที่คุณใช้คำนวณ
            $documentmonth     = $DocumentModel->getrevenue($year);
            $loanprocessmonths = $LoanModel->getLoanProcessMonths($year);
            $LoanPaymentMonths = $LoanModel->getListLoanPaymentMonths($year);

            // ค่าเริ่มต้นทุกเดือน = 0 (index 1..12)
            $expenseByMonth = array_fill(1, 12, 0.0); // รายจ่าย
            $processByMonth = array_fill(1, 12, 0.0); // รายรับ(ค่าดำเนินการ)
            $loanPayByMonth = array_fill(1, 12, 0.0); // รายรับ(ค่างวดจริง)

            // ----- รายจ่ายรายเดือนจากใบสำคัญ -----
            foreach ($documentmonth as $doc) {
                $m = (int)$doc->doc_month;
                if ($m < 1 || $m > 12) continue;

                if ($doc->doc_type === 'ใบสำคัญจ่าย') {
                    $expenseByMonth[$m] = (float)$doc->doc_sum_price;
                }
            }

            // ----- รายรับ(ค่าดำเนินการ) -----
            foreach ($loanprocessmonths as $loan) {
                $m = (int)$loan->loan_created_payment;
                if ($m < 1 || $m > 12) continue;

                $processByMonth[$m] =
                    (float)$loan->total_payment_process +
                    (float)$loan->total_tranfer +
                    (float)$loan->total_payment_other;
            }

            // ----- รายรับ(ค่างวดจริง) -----
            foreach ($LoanPaymentMonths as $row) {
                $m = (int)$row->loan_created_payment;
                if ($m < 1 || $m > 12) continue;

                $loanPayByMonth[$m] += (float)$row->setting_land_report_money;
            }

            // ===== สรุป income/expense/profit =====
            $incomeByMonth = [];
            $profitByMonth = [];
            $sumIncome = 0.0;
            $sumExpense = 0.0;
            $sumProfit = 0.0;

            for ($m = 1; $m <= 12; $m++) {
                $income = (float)$processByMonth[$m] + (float)$loanPayByMonth[$m];
                $expense = (float)$expenseByMonth[$m];
                $profit = $income - $expense;

                $incomeByMonth[$m] = $income;
                $expenseByMonth[$m] = $expense; // คงไว้
                $profitByMonth[$m] = $profit;

                $sumIncome += $income;
                $sumExpense += $expense;
                $sumProfit += $profit;
            }

            return $this->response
                ->setStatusCode(200)
                ->setContentType('application/json')
                ->setJSON([
                    'success' => 1,
                    'year' => $year,

                    // ✅ ใช้ชื่อให้ชัด: income/expense/profit
                    'income'  => $incomeByMonth,   // 1..12
                    'expense' => $expenseByMonth,  // 1..12
                    'profit'  => $profitByMonth,   // 1..12

                    // ✅ totals
                    'total_income'  => $sumIncome,
                    'total_expense' => $sumExpense,
                    'total_profit'  => $sumProfit,
                ]);
        } catch (\Throwable $e) {
            return $this->response
                ->setStatusCode(500)
                ->setContentType('application/json')
                ->setJSON(['success' => 0, 'message' => $e->getMessage()]);
        }
    }
}
