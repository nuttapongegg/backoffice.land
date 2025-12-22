<?php

namespace App\Controllers\cronjob;

use App\Controllers\BaseController;
use App\Models\LoanModel;
use App\Models\LandlogsModel;
use App\Models\SettingLandModel;
use App\Models\RealInvestmentModel;
use App\Models\DocumentModel;

class Landlogs extends BaseController
{
    public function land_logs()
    {
        $LoanModel = new LoanModel();
        $LandlogsModel = new LandlogsModel();
        $SettingLandModel = new SettingLandModel();
        $RealInvestmentModel = new RealInvestmentModel();
        $DocumentModel = new DocumentModel();

        try {
            $loans = $LoanModel->getAllDataLoan();
            $land_accounts = $SettingLandModel->getSettingLandAll();
            $real_investment = $RealInvestmentModel->getRealInvestmentAll();
            $document = $DocumentModel->getDocSumAll();

            $loan_payment_sum_installment = 0;
            $summary_no_vat_ON_STATE = 0;
            $loan_summary_process = 0;
            foreach ($loans as $loan) {

                $loan_payment_sum_installment = $loan_payment_sum_installment + $loan->loan_payment_sum_installment;

                if ($loan->loan_status == 'ON_STATE') {
                    $summary_no_vat_ON_STATE = $summary_no_vat_ON_STATE + $loan->loan_summary_no_vat;
                }

                $loan_summary_process = $loan_summary_process + $loan->loan_payment_process + $loan->loan_tranfer + $loan->loan_payment_other;
            }

            $sum_land_account = 0;
            foreach ($land_accounts as $land_account) {
                $sum_land_account = $sum_land_account + $land_account->land_account_cash;
            }

            $expenses = $document->expenses;

            // $summary_net_assets = 0;
            // $summary_net_assets = $summary_no_vat_ON_STATE + $sum_land_account;

            $paid_up_capital      = $real_investment->investment;   // ทุนตั้งต้น
            $retained_earnings    = ($loan_summary_process + $loan_payment_sum_installment) - $expenses;    // กำไรสะสม
            $equity_real          = $paid_up_capital + $retained_earnings;   // ทรัพย์สินสุทธิ (Equity จริง)

            // บันทึกข้อมูลลง DB insertLandlogs
            $LandlogsModel->insertLandlogs([
                'land_logs_loan_amount' => $summary_no_vat_ON_STATE,
                'land_logs_cash_flow' => $sum_land_account,
                'land_logs_interest' => $loan_payment_sum_installment,
                'land_logs_summary_net' => $equity_real
            ]);
            
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }
}
