<?php

namespace App\Controllers\cronjob;

use App\Controllers\BaseController;
use App\Models\LoanModel;
use App\Models\OverdueStatusModel;
use CURLFile;
use Error;

class LoanStatus extends BaseController
{

    public function run()
    {
        set_time_limit(300);
        $this->line_message_api();
    }

    private function line_message_api()
    {
        $OverdueStatusModel = new OverdueStatusModel();
        $LoanModel = new LoanModel();

        try {

            $nofity_Day = $OverdueStatusModel->getOverdueStatusAll();
            if ($nofity_Day->token_loan_status == 1) {

                // **ดึง Token ล่าสุดจากฐานข้อมูล**
                $token = $nofity_Day->token_loan;

                foreach ($LoanModel->getAllDataLoanMessageAPI() as $dataLoan) {


                    $loan_date_fix = $dataLoan->loan_payment_date_fix;
                    $months = $dataLoan->loan_period - 1;

                    $date = str_replace('-', '/', $loan_date_fix);
                    $tomorrow = date('Y-m-d', strtotime($date . "+$months months"));

                    $now = time(); // or your date as well
                    $your_date = strtotime($tomorrow);

                    $datediff = $now - $your_date;

                    $date_sum = round($datediff / (60 * 60 * 24));

                    $Message_Nofity = '';

                    if ($date_sum == 0) {
                        $Message_Nofity = 'สินเชื่อ ' . $dataLoan->loan_code . "\n" .
                            'ลูกค้า ' . $dataLoan->loan_customer . "\n" .
                            'สถานที่ ' . $dataLoan->loan_address . "\n" .
                            'ยอดชำระเงินประจำงวดที่ ' . $dataLoan->loan_period . "\n" .
                            'วันครบกำหนด ' . dateThaiDM($tomorrow) . "\n" .
                            'ยอดชำระ ' . number_format($dataLoan->loan_payment_month, 2) . ' บาท' . "\n" .
                            'ชำระได้ที่ : ' . base_url('/loan/detail') . '/' . $dataLoan->loan_code;
                    } elseif ($date_sum >= $nofity_Day->token_overdue_loan && $nofity_Day->token_overdue_loan != 0) {
                        $Message_Nofity = 'สินเชื่อ ' . $dataLoan->loan_code . "\n" .
                            'ลูกค้า ' . $dataLoan->loan_customer . "\n" .
                            'สถานที่ ' . $dataLoan->loan_address . "\n" .
                            'ยอดชำระเงินประจำงวดที่ ' . $dataLoan->loan_period . "\n" .
                            'วันครบกำหนด ' . dateThaiDM($tomorrow) . "\n" .
                            'ยอดชำระ ' . number_format($dataLoan->loan_payment_month, 2) . ' บาท' . "\n" .
                            'เกินกำหนดชำระ ' . $date_sum . ' วัน' . "\n" .
                            'ชำระได้ที่ : ' . base_url('/loan/detail') . '/' . $dataLoan->loan_code;
                    }

                    // ส่งข้อความผ่าน LINE API
                    $response = send_line_message($token, $Message_Nofity);
                    if ($response === false) {
                        log_message('info', 'Attempting to refresh LINE Access Token...');
                        $newToken = get_line_access_token();

                        if ($newToken) {
                            $token = $newToken; // อัปเดต Token ใหม่
                            $OverdueStatusModel->updateOverdueStatus([
                                'token_loan' => $newToken
                            ]);

                            // ลองส่งข้อความอีกครั้งด้วย Token ใหม่
                            if (!send_line_message($token, $Message_Nofity)) {
                                log_message('error', 'Failed to send LINE message for loan code: ' . $dataLoan->loan_code);
                            }
                        } else {
                            log_message('error', 'Failed to refresh LINE access token.');
                        }
                    }
                    sleep(1);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }
}
