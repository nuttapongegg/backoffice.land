<?php

namespace App\Controllers\cronjob;

use App\Controllers\BaseController;
use App\Models\OwnerLoanModel;
use App\Models\OwnerLoanLedgerModel;
use App\Models\OwnerSettingModel;

date_default_timezone_set('Asia/Bangkok');

class MigrateOwnerLoan extends BaseController
{
    public function run()
    {
        $loanModel = new OwnerLoanModel();
        $ledger    = new OwnerLoanLedgerModel();
        $setting   = new OwnerSettingModel();

        $today = date('Y-m-d');

        $defaultRate = (float)($setting->getOwnerSettingAll()->default_interest_rate ?? 0);

        $loans = $loanModel->getAllOpenOwnerLoans();

        $count = 0;

        foreach ($loans as $loan) {

            // ❗ ถ้ามี ledger แล้ว = ข้าม (กันรันซ้ำ)
            $check = (float)$ledger->getBalance($loan->id);
            if ($check > 0) continue;

            // -------------------------
            // 1) เงินต้นคงเหลือจริง
            // -------------------------
            $paidPrincipal   = (float)$loanModel->getPaidPrincipalTotalActive($loan->id);
            $principalRemain = max(0, (float)$loan->amount - $paidPrincipal);

            if ($principalRemain <= 0) continue;

            // -------------------------
            // 2) วันเริ่มคิดดอก (งวดล่าสุด)
            // -------------------------
            $lastPay  = $loanModel->getLastPaymentActive($loan->id);
            $startDate = $lastPay->pay_date ?? $loan->owner_loan_date;

            // normalize วันที่
            $dStart = new \DateTime($startDate);
            $dEnd   = new \DateTime($today);

            // -------------------------
            // 3) INIT = เงินต้นคงเหลือ ณ startDate
            // -------------------------
            $ledger->insert([
                'owner_loan_id' => $loan->id,
                'log_date'      => $dStart->format('Y-m-d'),
                'type'          => 'INIT',
                'amount'        => $principalRemain,
                'note'          => 'migrate init (principal remain)',
            ]);

            // -------------------------
            // 4) คิดดอกรายวัน (compound)
            // -------------------------
            // ดอกต่อปี %
            $rate = (float)($loan->interest_rate ?? $defaultRate);
            $ratePerDay = ($rate / 100) / 365;

            // loop ตั้งแต่วันถัดจาก startDate ถึงก่อน today
            $current = (clone $dStart)->modify('+1 day');

            while ($current <= $dEnd) {

                // กันกรณี principal หมด
                if ($principalRemain <= 0) break;

                // ดอกของวันนั้น
                $interest = round($principalRemain * $ratePerDay, 2);

                if ($interest > 0) {
                    $ledger->insert([
                        'owner_loan_id' => $loan->id,
                        'log_date'      => $current->format('Y-m-d'),
                        'type'          => 'INTEREST',
                        'amount'        => $interest,
                        'note'          => 'migrate interest',
                    ]);

                    // 🔥 ดอกทบ
                    $principalRemain += $interest;
                }

                $current->modify('+1 day');
            }

            $count++;
        }

        echo "MIGRATE LEDGER SUCCESS: {$count}";
    }
}