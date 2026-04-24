<?php

namespace App\Controllers\cronjob;

use App\Controllers\BaseController;
use App\Models\OwnerLoanModel;
use App\Models\OwnerLoanLedgerModel;
use App\Models\OwnerSettingModel;

date_default_timezone_set('Asia/Bangkok');

class OwnerLoanInterest extends BaseController
{
    public function run()
    {
        $loanModel = new OwnerLoanModel();
        $ledger = new OwnerLoanLedgerModel();
        $setting = new OwnerSettingModel();

        $today = date('Y-m-d');

        $rateDefault = (float)$setting->getOwnerSettingAll()->default_interest_rate;

        $loans = $loanModel->getAllOpenOwnerLoans();

        foreach ($loans as $loan) {

            // ❗ กันซ้ำ
            if ($ledger->hasInterestToday($loan->id, $today)) continue;

            $balance = (float)$ledger->getBalance($loan->id);
            if ($balance <= 0) continue;

            $rate = (float)($loan->interest_rate ?? $rateDefault);
            $ratePerDay = ($rate / 100) / 365;

            $interest = round($balance * $ratePerDay, 2);

            if ($interest <= 0) continue;

            try {
                $ledger->insert([
                    'owner_loan_id' => $loan->id,
                    'log_date'      => $today,
                    'type'          => 'INTEREST',
                    'amount'        => $interest,
                ]);
            } catch (\Throwable $e) {
                // กัน duplicate จาก cron ซ้ำ
            }
        }

        echo "CRON OK";
    }
}
