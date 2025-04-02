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
        set_time_limit(120);
        $this->line_message_api();
    }

    private function line_message_api()
    {
        $OverdueStatusModel = new OverdueStatusModel();
        $LoanModel = new LoanModel();

        try {

            $nofity_Day = $OverdueStatusModel->getOverdueStatusAll();
            if ($nofity_Day->token_loan_status == 1) {

                foreach ($LoanModel->getAllDataLoanMessageAPI() as $dataLoan) {


                    $loan_date_fix = $dataLoan->loan_payment_date_fix;
                    $months = $dataLoan->loan_period - 1;

                    $date = str_replace('-', '/', $loan_date_fix);
                    $tomorrow = date('Y-m-d', strtotime($date . "+$months months"));

                    $now = time(); // or your date as well
                    $your_date = strtotime($tomorrow);

                    $datediff = $now - $your_date;

                    $date_sum = round($datediff / (60 * 60 * 24));

                    $token = 'DbYhN3QORqGRc2bPDuQOKLgnvfmvqrcrQlx695CqfNavutEfYA0BtVH0cVUrXaLPOALegu81juvvRNd/TRF+teZaIcSkrs8Xprrgafeg7Zs+Ayu/Fg+x0V+/+Pk3DLYQOn4CoxVc2kgkJ2NMqFFKggdB04t89/1O/w1cDnyilFU=';

                    $Message_Nofity = '';

                    if ($date_sum == 0) {
                        $Message_Nofity = 'สินเชื่อ ' . $dataLoan->loan_code . "\n" .
                            'ลูกค้า ' . $dataLoan->loan_customer . "\n" .
                            'สถานที่ ' . $dataLoan->loan_address . "\n" .
                            'ยอดชำระเงินประจำงวดที่ ' . $dataLoan->loan_period . "\n" .
                            'วันครบกำหนด ' . dateThaiDM($tomorrow) . "\n" .
                            'ยอดชำระ ' . number_format($dataLoan->loan_payment_month, 2) . ' บาท' . "\n" .
                            'ชำระได้ที่ : ' . base_url('/loan/detail') . '/' . $dataLoan->loan_code;
                    } elseif ($date_sum >= $nofity_Day->token_overdue_loan and $nofity_Day->token_overdue_loan != 0) {
                        $Message_Nofity = 'สินเชื่อ ' . $dataLoan->loan_code . "\n" .
                            'ลูกค้า ' . $dataLoan->loan_customer . "\n" .
                            'สถานที่ ' . $dataLoan->loan_address . "\n" .
                            'ยอดชำระเงินประจำงวดที่ ' . $dataLoan->loan_period . "\n" .
                            'วันครบกำหนด ' . dateThaiDM($tomorrow) . "\n" .
                            'ยอดชำระ ' . number_format($dataLoan->loan_payment_month, 2) . ' บาท' . "\n" .
                            'เกินกำหนดชำระ ' . $date_sum . ' วัน' . "\n" .
                            'ชำระได้ที่ : ' . base_url('/loan/detail') . '/' . $dataLoan->loan_code;
                    }

                    send_line_message($token, $Message_Nofity);
                    sleep(1);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }
}
