<?php

namespace App\Models;

class OwnerLoanLedgerModel
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // -------------------------
    // insert ledger
    // -------------------------
    public function insert($data)
    {
        return $this->db->table('owner_loan_ledger')->insert($data);
    }

    // -------------------------
    // ยอดปัจจุบัน
    // -------------------------
    public function getBalance($loanId)
    {
        return (float)$this->db->table('owner_loan_ledger')
            ->selectSum('amount')
            ->where('owner_loan_id', $loanId)
            ->get()
            ->getRow()
            ->amount ?? 0;
    }

    // -------------------------
    // ดอกสะสม (เฉพาะ INTEREST)
    // -------------------------
    public function getTotalInterest($loanId)
    {
        $row = $this->db->table('owner_loan_ledger')
            ->select('COALESCE(SUM(amount),0) as interest')
            ->where('owner_loan_id', $loanId)
            ->where('type', 'INTEREST')
            ->get()->getRow();

        return (float)($row->interest ?? 0);
    }

    // -------------------------
    // ยอดจ่ายทั้งหมด
    // -------------------------
    public function getTotalPaid($loanId)
    {
        $row = $this->db->table('owner_loan_ledger')
            ->select('COALESCE(SUM(amount),0) as paid')
            ->where('owner_loan_id', $loanId)
            ->where('type', 'PAY')
            ->get()->getRow();

        return abs((float)($row->paid ?? 0));
    }

    // -------------------------
    // มี INTEREST วันนี้ยัง
    // -------------------------
    public function hasInterestToday($loanId, $date)
    {
        return $this->db->table('owner_loan_ledger')
            ->where('owner_loan_id', $loanId)
            ->where('log_date', $date)
            ->where('type', 'INTEREST')
            ->countAllResults() > 0;
    }

    public function deleteLedgerByLoanAndDate($loanId, $date)
    {
        $builder = $this->db->table('owner_loan_ledger');

        return $builder
            ->where('owner_loan_id', $loanId)
            ->where('log_date', $date)
            ->where('type', 'PAY')
            ->delete();
    }

    public function deleteLedgerByPaymentId($paymentId)
    {
        return $this->db->table('owner_loan_ledger')
            ->where('ref_id', $paymentId)
            ->where('type', 'PAY')
            ->delete();
    }

    public function getLastInterestDate($loanId)
    {
        return $this->db->table('owner_loan_ledger')
            ->select('log_date')
            ->where('owner_loan_id', $loanId)
            ->where('type', 'INTEREST')
            ->orderBy('log_date', 'DESC')
            ->limit(1)
            ->get()
            ->getRow()
            ->log_date ?? null;
    }
}
