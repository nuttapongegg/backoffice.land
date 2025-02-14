<?php

namespace App\Controllers\cronjob;

use App\Controllers\BaseController;
use App\Models\LoanModel;
use App\Models\LandlogsModel;
use App\Models\SettingLandModel;

class Landlogs extends BaseController
{


    public function land_logs()
    {
        $LoanModel = new LoanModel();
        $LandlogsModel = new LandlogsModel();
        $SettingLandModel = new SettingLandModel();
        try {
            $loans = $LoanModel->getAllDataLoan();
            $land_accounts = $SettingLandModel->getSettingLandAll();

            $loan_payment_sum_installment = 0;
            $summary_no_vat_ON_STATE = 0;
            foreach ($loans as $loan) {

                if ($loan->loan_payment_sum_installment != 0.00) {
                    $loan_payment_sum_installment = $loan_payment_sum_installment + $loan->loan_payment_sum_installment;
                }
    
                if ($loan->loan_status == 'ON_STATE') {
                    $summary_no_vat_ON_STATE = $summary_no_vat_ON_STATE + $loan->loan_summary_no_vat;
                }
            }
            
            $sum_land_account = 0;
            foreach ($land_accounts as $land_account) {
                $sum_land_account = $sum_land_account + $land_account->land_account_cash;
            }
            $summary_net_assets = 0;
            $summary_net_assets = $summary_no_vat_ON_STATE + $sum_land_account;

            // บันทึกข้อมูลลง DB insertLandlogs
            $LandlogsModel->insertLandlogs([
                'land_logs_loan_amount' => $summary_no_vat_ON_STATE,
                'land_logs_cash_flow' => $sum_land_account,
                'land_logs_interest' => $loan_payment_sum_installment,
                'land_logs_summary_net' => $summary_net_assets
            ]);

        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }
}
