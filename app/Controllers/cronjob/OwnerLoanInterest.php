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
        $ledger    = new OwnerLoanLedgerModel();
        $setting   = new OwnerSettingModel();

        $today = date('Y-m-d');

        $rateDefault = (float)$setting->getOwnerSettingAll()->default_interest_rate;

        $loans = $loanModel->getAllOpenOwnerLoans();

        foreach ($loans as $loan) {

            // -------------------------
            // 🔥 หา “วันล่าสุดที่มีดอก”
            // -------------------------
            $lastDate = $ledger->getLastInterestDate($loan->id);

            if (!$lastDate) {
                $lastDate = $loan->owner_loan_date;
            }

            $dStart = new \DateTime($lastDate);
            $dEnd   = new \DateTime($today);

            // เริ่มวันถัดไป
            $current = (clone $dStart)->modify('+1 day');

            while ($current <= $dEnd) {

                $dateStr = $current->format('Y-m-d');

                // -------------------------
                // ❗ กันซ้ำ
                // -------------------------
                if ($ledger->hasInterestToday($loan->id, $dateStr)) {
                    $current->modify('+1 day');
                    continue;
                }

                // -------------------------
                // balance ล่าสุด
                // -------------------------
                $balance = (float)$ledger->getBalance($loan->id);
                if ($balance <= 0) break;

                // -------------------------
                // คิดดอก
                // -------------------------
                $rate = (float)($loan->interest_rate ?? $rateDefault);
                $ratePerDay = ($rate / 100) / 365;

                $interest = round($balance * $ratePerDay, 2);

                if ($interest > 0) {
                    try {
                        $ledger->insert([
                            'owner_loan_id' => $loan->id,
                            'log_date'      => $dateStr,
                            'type'          => 'INTEREST',
                            'amount'        => $interest,
                        ]);
                    } catch (\Throwable $e) {
                        // กันซ้ำ
                    }
                }

                $current->modify('+1 day');
            }
        }

        echo "CRON OK";
    }
}
