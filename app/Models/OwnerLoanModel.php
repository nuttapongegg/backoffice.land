<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class OwnerLoanModel
{
    protected $db;
    protected $column_order;
    protected $column_search;
    protected $order;

    protected $column_order_finx;
    protected $column_search_finx;
    protected $order_finx;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;

        // Set orderable column fields
        $this->column_order = [
            'loan.loan_code',
            'loan.loan_code',
            'loan_customer',
            'loan_address',
            'loan.loan_area',
            'loan.loan_number',
            'loan.loan_date_close',
            'loan.loan_type',
            'loan.loan_summary_no_vat',
            'loan.loan_payment_year_counter',
            'loan.loan_status',
            'loan.loan_payment_sum_installment',
            'loan.loan_close_payment',
            'loan.loan_date_promise',
            'loan.loan_payment_interest',
            'loan.loan_payment_year_counter',
            null,
            'loan.loan_payment_month',
            null,
            'loan.loan_remnark'
        ];

        // Set searchable column fields
        $this->column_search = [
            'loan.loan_code',
            'loan_customer',
            'loan_address',
            'loan.loan_area',
            'loan.loan_number',
            'loan.loan_date_close',
            'loan.loan_type',
            'loan.loan_summary_no_vat',
            'loan.loan_payment_year_counter',
            'loan.loan_payment_sum_installment',
            'loan.loan_close_payment',
            'loan.loan_date_promise',
            'loan.loan_payment_interest',
            'loan.loan_payment_month',
            'loan.loan_remnark'
        ];

        // Set default order
        $this->order = array('loan.loan_code' => 'DESC');

        // ให้ index ตรงกับ DataTables columns เดิมของคุณ
        $this->column_order_finx = [
            null,
            'loan.loan_code',
            'loan_customer',
            'loan_address',
            'loan.loan_area',
            'loan.loan_number',
            'loan.loan_date_close',
            'loan.loan_type',
            'loan.loan_summary_no_vat',
            'loan.loan_status',
            'installment_3pct',
            'loan.loan_close_payment',
            'loan.loan_date_promise',
            'loan.loan_remnark'
        ];

        $this->column_search_finx = [
            'loan.loan_code',
            'loan_customer',
            'loan_address',
            'loan.loan_area',
            'loan.loan_number',
            'loan.loan_date_close',
            'loan.loan_type',
            'loan.loan_summary_no_vat',
            'installment_3pct',
            'loan.loan_close_payment',
            'loan.loan_date_promise',
            'loan.loan_remnark'
        ];

        $this->order_finx = array('loan.loan_code' => 'DESC');
    }

    // public function getAllDataOwnerLoanOn($date)
    // {
    //     $whereDate = '';
    //     $params = [];

    //     if (!empty($date)) {
    //         $dateExplode = explode(" to ", $date);
    //         $dateStart = trim($dateExplode[0]);
    //         $dateEnd   = isset($dateExplode[1]) ? trim($dateExplode[1]) : $dateStart;

    //         $whereDate = " 
    //         AND owner_loan.owner_loan_date >= ? 
    //         AND owner_loan.owner_loan_date <= ? 
    //     ";
    //         $params[] = $dateStart;
    //         $params[] = $dateEnd;
    //     }

    //     $sql = "
    //     SELECT 
    //         owner_loan.id,
    //         owner_loan.owner_code,
    //         owner_loan.owner_loan_date,
    //         owner_loan.amount,
    //         owner_loan.note,
    //         owner_loan.status,
    //         owner_loan.land_account_id,
    //         setting_land.land_account_name,
    //         owner_loan.employee_id,
    //         owner_loan.username,
    //         owner_loan.created_at,
    //         owner_loan.updated_at,

    //         -- รวมที่จ่ายจริง (ต้น+ดอก)
    //         COALESCE(SUM(
    //             CASE 
    //                 WHEN owner_loan_payment.status = 'ACTIVE'
    //                 AND owner_loan_payment.deleted_at IS NULL
    //                 THEN owner_loan_payment.pay_amount
    //                 ELSE 0
    //             END
    //         ), 0) AS paid_total,

    //         -- ✅ รวมเงินต้นที่ตัดจริง
    //         COALESCE(SUM(
    //             CASE 
    //                 WHEN owner_loan_payment.status = 'ACTIVE'
    //                 AND owner_loan_payment.deleted_at IS NULL
    //                 THEN owner_loan_payment.principal_amount
    //                 ELSE 0
    //             END
    //         ), 0) AS paid_principal_total,

    //         -- รวมดอก
    //         COALESCE(SUM(
    //             CASE 
    //                 WHEN owner_loan_payment.status = 'ACTIVE'
    //                 AND owner_loan_payment.deleted_at IS NULL
    //                 THEN owner_loan_payment.interest_amount
    //                 ELSE 0
    //             END
    //         ), 0) AS paid_interest_total,

    //         -- ✅ คงเหลือเงินต้น
    //         (
    //             owner_loan.amount - COALESCE(SUM(
    //                 CASE 
    //                     WHEN owner_loan_payment.status = 'ACTIVE'
    //                     AND owner_loan_payment.deleted_at IS NULL
    //                     THEN owner_loan_payment.principal_amount
    //                     ELSE 0
    //                 END
    //             ), 0)
    //         ) AS outstanding

    //     FROM owner_loan
    //     LEFT JOIN owner_loan_payment
    //         ON owner_loan_payment.owner_loan_id = owner_loan.id
    //     LEFT JOIN setting_land
    //         ON setting_land.id = owner_loan.land_account_id

    //     WHERE owner_loan.deleted_at IS NULL
    //     AND owner_loan.status = 'OPEN'
    //     {$whereDate}

    //     GROUP BY owner_loan.id
    //     ORDER BY owner_loan.id DESC
    // ";

    //     return $this->db->query($sql, $params)->getResult();
    // }

    public function getAllDataOwnerLoanOn($date)
    {
        $whereDate = '';
        $params = [];

        if (!empty($date)) {
            $dateExplode = explode(" to ", $date);
            $dateStart = trim($dateExplode[0]);
            $dateEnd   = isset($dateExplode[1]) ? trim($dateExplode[1]) : $dateStart;

            $whereDate = " 
            AND owner_loan.owner_loan_date >= ? 
            AND owner_loan.owner_loan_date <= ? 
        ";
            $params[] = $dateStart;
            $params[] = $dateEnd;
        }

        $sql = "
        SELECT 
            owner_loan.id,
            owner_loan.owner_code,
            owner_loan.owner_loan_date,
            owner_loan.amount,
            owner_loan.note,
            owner_loan.status,
            owner_loan.land_account_id,
            setting_land.land_account_name,
            owner_loan.employee_id,
            owner_loan.username,
            owner_loan.created_at,
            owner_loan.updated_at,

            -- รวมที่จ่าย (ACTIVE)
            COALESCE(SUM(
                CASE 
                    WHEN owner_loan_payment.status = 'ACTIVE'
                     AND owner_loan_payment.deleted_at IS NULL
                    THEN owner_loan_payment.pay_amount
                    ELSE 0
                END
            ), 0) AS paid_total,

            COALESCE(SUM(
                CASE 
                    WHEN owner_loan_payment.status = 'ACTIVE'
                     AND owner_loan_payment.deleted_at IS NULL
                    THEN owner_loan_payment.principal_amount
                    ELSE 0
                END
            ), 0) AS paid_principal_total,

            COALESCE(SUM(
                CASE 
                    WHEN owner_loan_payment.status = 'ACTIVE'
                     AND owner_loan_payment.deleted_at IS NULL
                    THEN owner_loan_payment.interest_amount
                    ELSE 0
                END
            ), 0) AS paid_interest_total,

            -- วันชำระล่าสุด
            MAX(
                CASE 
                    WHEN owner_loan_payment.status = 'ACTIVE'
                     AND owner_loan_payment.deleted_at IS NULL
                    THEN owner_loan_payment.pay_date
                    ELSE NULL
                END
            ) AS last_pay_date,

            -- คงเหลือเงินต้น
            (
                owner_loan.amount - COALESCE(SUM(
                    CASE 
                        WHEN owner_loan_payment.status = 'ACTIVE'
                         AND owner_loan_payment.deleted_at IS NULL
                        THEN owner_loan_payment.principal_amount
                        ELSE 0
                    END
                ), 0)
            ) AS outstanding,

            -- วันยืมมาแล้วกี่วัน
            DATEDIFF(CURDATE(), owner_loan.owner_loan_date) AS days_from_loan,

            -- เงินต้นตั้งต้นสำหรับคิดดอกวันนี้
            COALESCE(
                (
                    SELECT owner_loan_payment.principal_balance
                    FROM owner_loan_payment
                    WHERE owner_loan_payment.owner_loan_id = owner_loan.id
                      AND owner_loan_payment.status = 'ACTIVE'
                      AND owner_loan_payment.deleted_at IS NULL
                    ORDER BY owner_loan_payment.pay_date DESC,
                             owner_loan_payment.id DESC
                    LIMIT 1
                ),
                owner_loan.amount
            ) AS principal_start_today,

            -- วันจากงวดล่าสุดถึงวันนี้
            DATEDIFF(
                CURDATE(),
                COALESCE(
                    (
                        SELECT owner_loan_payment.pay_date
                        FROM owner_loan_payment
                        WHERE owner_loan_payment.owner_loan_id = owner_loan.id
                          AND owner_loan_payment.status = 'ACTIVE'
                          AND owner_loan_payment.deleted_at IS NULL
                        ORDER BY owner_loan_payment.pay_date DESC,
                                 owner_loan_payment.id DESC
                        LIMIT 1
                    ),
                    owner_loan.owner_loan_date
                )
            ) AS days_since_last_pay,

            -- ดอกกี่เปอร์เซ็น
            COALESCE(owner_loan.interest_rate, owner_setting.default_interest_rate) AS interest_rate_used,

            -- ดอกวันละเท่าไหร่
            ROUND(
                COALESCE(
                    (
                        SELECT owner_loan_payment.principal_balance
                        FROM owner_loan_payment
                        WHERE owner_loan_payment.owner_loan_id = owner_loan.id
                        AND owner_loan_payment.status = 'ACTIVE'
                        AND owner_loan_payment.deleted_at IS NULL
                        ORDER BY owner_loan_payment.pay_date DESC,
                                owner_loan_payment.id DESC
                        LIMIT 1
                    ),
                    owner_loan.amount
                )
                *
                (COALESCE(owner_loan.interest_rate, owner_setting.default_interest_rate) / 100)
                / 365
            , 2) AS interest_per_day,

            -- ดอกถึงวันนี้
            ROUND(
                COALESCE(
                    (
                        SELECT owner_loan_payment.principal_balance
                        FROM owner_loan_payment
                        WHERE owner_loan_payment.owner_loan_id = owner_loan.id
                          AND owner_loan_payment.status = 'ACTIVE'
                          AND owner_loan_payment.deleted_at IS NULL
                        ORDER BY owner_loan_payment.pay_date DESC,
                                 owner_loan_payment.id DESC
                        LIMIT 1
                    ),
                    owner_loan.amount
                ) * (COALESCE(owner_loan.interest_rate, owner_setting.default_interest_rate) / 100) *
                DATEDIFF(
                    CURDATE(),
                    COALESCE(
                        (
                            SELECT owner_loan_payment.pay_date
                            FROM owner_loan_payment
                            WHERE owner_loan_payment.owner_loan_id = owner_loan.id
                              AND owner_loan_payment.status = 'ACTIVE'
                              AND owner_loan_payment.deleted_at IS NULL
                            ORDER BY owner_loan_payment.pay_date DESC,
                                     owner_loan_payment.id DESC
                            LIMIT 1
                        ),
                        owner_loan.owner_loan_date
                    )
                ) / 365
            , 2) AS interest_due_today,

            -- ยอดปิดวันนี้
            ROUND(
                COALESCE(
                    (
                        SELECT owner_loan_payment.principal_balance
                        FROM owner_loan_payment
                        WHERE owner_loan_payment.owner_loan_id = owner_loan.id
                          AND owner_loan_payment.status = 'ACTIVE'
                          AND owner_loan_payment.deleted_at IS NULL
                        ORDER BY owner_loan_payment.pay_date DESC,
                                 owner_loan_payment.id DESC
                        LIMIT 1
                    ),
                    owner_loan.amount
                )
                +
                (
                    COALESCE(
                        (
                            SELECT owner_loan_payment.principal_balance
                            FROM owner_loan_payment
                            WHERE owner_loan_payment.owner_loan_id = owner_loan.id
                              AND owner_loan_payment.status = 'ACTIVE'
                              AND owner_loan_payment.deleted_at IS NULL
                            ORDER BY owner_loan_payment.pay_date DESC,
                                     owner_loan_payment.id DESC
                            LIMIT 1
                        ),
                        owner_loan.amount
                    ) * (COALESCE(owner_loan.interest_rate, owner_setting.default_interest_rate) / 100) *
                    DATEDIFF(
                        CURDATE(),
                        COALESCE(
                            (
                                SELECT owner_loan_payment.pay_date
                                FROM owner_loan_payment
                                WHERE owner_loan_payment.owner_loan_id = owner_loan.id
                                  AND owner_loan_payment.status = 'ACTIVE'
                                  AND owner_loan_payment.deleted_at IS NULL
                                ORDER BY owner_loan_payment.pay_date DESC,
                                         owner_loan_payment.id DESC
                                LIMIT 1
                            ),
                            owner_loan.owner_loan_date
                        )
                    ) / 365
                )
            , 2) AS total_due_today,

            -- % ตัดต้นแล้ว
            ROUND(
                (
                    COALESCE(SUM(
                        CASE 
                            WHEN owner_loan_payment.status = 'ACTIVE'
                             AND owner_loan_payment.deleted_at IS NULL
                            THEN owner_loan_payment.principal_amount
                            ELSE 0
                        END
                    ), 0) / NULLIF(owner_loan.amount, 0)
                ) * 100
            , 2) AS principal_progress_pct

        FROM owner_loan
        LEFT JOIN owner_loan_payment
            ON owner_loan_payment.owner_loan_id = owner_loan.id
        LEFT JOIN setting_land
            ON setting_land.id = owner_loan.land_account_id
        LEFT JOIN owner_setting ON owner_setting.id = 1
        WHERE owner_loan.deleted_at IS NULL
          AND owner_loan.status = 'OPEN'
          {$whereDate}

        GROUP BY owner_loan.id
        ORDER BY owner_loan.id DESC
        ";
        return $this->db->query($sql, $params)->getResult();
    }

    public function getAllDataOwnerLoan()
    {
        $sql = "
        SELECT *
        FROM loan
        WHERE loan.loan_status != 'CANCEL_STATE'
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getEmps()
    {
        $sql = "SELECT * FROM employees";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function createOwnerLoan(array $data): array
    {
        // 1) insert ก่อน
        $this->db->table('owner_loan')->insert($data);
        $id = $this->db->insertID();

        // 2) generate code จาก id จริง
        $code = 'OWN' . sprintf('%06d', $id);

        // 3) update owner_code
        $this->db->table('owner_loan')
            ->where('id', $id)
            ->update(['owner_code' => $code]);

        return ['id' => $id, 'owner_code' => $code];
    }

    public function getAllDataOwnerLoanByCode($owner_code)
    {
        $sql = "SELECT owner_loan.*,
            setting_land.land_account_name
            FROM owner_loan
            LEFT JOIN setting_land 
            ON setting_land.id = owner_loan.land_account_id
            WHERE owner_loan.owner_code = '$owner_code' ORDER BY owner_loan.id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getRow();
    }

    public function cancelOwnerLoanById($owner_loan_id)
    {
        return $this->db->table('owner_loan')
            ->where('id', (int)$owner_loan_id)
            ->update([
                'status'     => 'CANCEL',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    // ======================
    // PAYMENTS
    // ======================

    public function getPaymentsByOwnerLoanId(int $ownerLoanId): array
    {
        return $this->db->table('owner_loan_payment')
            ->select('
            id,
            pay_date,
            pay_amount,
            pay_amount,
            interest_rate_used,
            days_diff,
            interest_amount,
            principal_amount,
            principal_balance,
            days_diff,
            note,
            username,
            owner_loan_pay_file,
            status,
            created_at
        ')
            ->where('owner_loan_id', $ownerLoanId)
            ->orderBy('pay_date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResult();
    }

    public function getPaymentSummaryNew(int $ownerLoanId)
    {
        return $this->db->query("
        SELECT
            COALESCE(SUM(CASE WHEN status='ACTIVE' THEN pay_amount ELSE 0 END), 0)           AS paid_total,
            COALESCE(SUM(CASE WHEN status='ACTIVE' THEN principal_amount ELSE 0 END), 0)     AS paid_principal_total,
            COALESCE(SUM(CASE WHEN status='ACTIVE' THEN interest_amount ELSE 0 END), 0)      AS paid_interest_total,
            MAX(CASE WHEN status='ACTIVE' THEN pay_date ELSE NULL END)                       AS last_pay_date
        FROM owner_loan_payment
        WHERE owner_loan_id = ?
    ", [$ownerLoanId])->getRow();
    }

    public function getPaymentSummary($owner_loan_id)
    {
        $sql = "
            SELECT 
                IFNULL(
                    SUM(
                        CASE 
                            WHEN owner_loan_payment.status = 'ACTIVE' 
                            THEN owner_loan_payment.pay_amount 
                            ELSE 0 
                        END
                    ), 
                0) AS paid_total,
                MAX(
                    CASE 
                        WHEN owner_loan_payment.status = 'ACTIVE' 
                        THEN owner_loan_payment.pay_date 
                        ELSE NULL 
                    END
                ) AS last_pay_date
            FROM owner_loan_payment
            WHERE owner_loan_payment.owner_loan_id = ?
        ";

        return $this->db->query($sql, [(int)$owner_loan_id])->getRow();
    }

    public function getPaymentById($id)
    {
        return $this->db->table('owner_loan_payment')
            ->where('id', (int)$id)
            ->get()
            ->getRow();
    }

    public function insertPayment(array $data)
    {
        return $this->db->table('owner_loan_payment')->insert($data);
    }

    public function cancelPaymentById($id)
    {
        return $this->db->table('owner_loan_payment')
            ->where('id', (int)$id)
            ->update([
                'status'     => 'CANCEL',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public function getLastPaymentActive(int $ownerLoanId)
    {
        return $this->db->table('owner_loan_payment')
            ->where('owner_loan_id', $ownerLoanId)
            ->where('status', 'ACTIVE')
            ->orderBy('pay_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->getRow();
    }

    public function closeOwnerLoanById(int $ownerLoanId, int $employeeId): bool
    {
        $now = date('Y-m-d H:i:s');

        return (bool)$this->db->table('owner_loan')
            ->where('id', $ownerLoanId)
            ->whereIn('status', ['OPEN', 'ACTIVE', ''])
            ->update([
                'status'     => 'CLOSED',
                'closed_at'  => $now,
                'closed_by'  => $employeeId,
                'updated_at' => $now,
            ]);
    }

    public function getOwnerLoanById(int $id)
    {
        return $this->db->table('owner_loan')->where('id', $id)->get()->getRow();
    }

    public function reopenOwnerLoanById(int $ownerLoanId): bool
    {
        $now = date('Y-m-d H:i:s');

        return (bool)$this->db->table('owner_loan')
            ->where('id', $ownerLoanId)
            ->update([
                'status'     => 'OPEN',
                'closed_at'  => null,
                'closed_by'  => null,
                'updated_at' => $now,
            ]);
    }
    public function getPaidPrincipalTotalActive(int $ownerLoanId): float
    {
        $row = $this->db->query("
        SELECT COALESCE(SUM(principal_amount),0) AS s
        FROM owner_loan_payment
        WHERE owner_loan_id = ?
          AND status = 'ACTIVE'
          AND deleted_at IS NULL
        ", [$ownerLoanId])->getRow();

        return (float)($row->s ?? 0);
    }

    public function getLastActivePayDate(int $ownerLoanId): ?string
    {
        $row = $this->db->query("
        SELECT MAX(pay_date) AS d
        FROM owner_loan_payment
        WHERE owner_loan_id = ?
          AND status = 'ACTIVE'
          AND deleted_at IS NULL
        ", [$ownerLoanId])->getRow();

        $d = $row->d ?? null;
        return $d ?: null;
    }

    public function getLastActivePaymentId(int $ownerLoanId): int
    {
        $row = $this->db->query("
        SELECT id
        FROM owner_loan_payment
        WHERE owner_loan_id = ?
          AND status = 'ACTIVE'
          AND deleted_at IS NULL
        ORDER BY pay_date DESC, id DESC
        LIMIT 1
        ", [$ownerLoanId])->getRow();

        return (int)($row->id ?? 0);
    }

    public function countActivePayments(int $ownerLoanId): int
    {
        $row = $this->db->query("
        SELECT COUNT(*) AS c
        FROM owner_loan_payment
        WHERE owner_loan_id = ?
          AND status = 'ACTIVE'
        ", [$ownerLoanId])->getRow();

        return (int)($row->c ?? 0);
    }

    protected $column_order_owner_loan_history = [
        null,
        'owner_loan.owner_code',
        'owner_loan.owner_loan_date',
        'owner_loan.closed_at',
        'owner_loan.amount',
        null, // paid_total
        null, // paid_principal_total
        null, // paid_interest_total
        null, // last_pay_date
        null, // days_from_loan
        null, // outstanding
        'setting_land.land_account_name',
        'owner_loan.username',
        'owner_loan.note',
    ];

    protected $column_search_owner_loan_history = [
        'owner_loan.owner_code',
        'owner_loan.note',
        'owner_loan.username',
        'setting_land.land_account_name',
    ];

    private function DataOwnerLoanHistoryQuery(array $post_data)
    {
        $date = $post_data['date'] ?? '';

        $builder = $this->db->table('owner_loan');

        $builder->select("
        owner_loan.id,
        owner_loan.owner_code,
        owner_loan.owner_loan_date,
        owner_loan.closed_at,
        owner_loan.amount,
        owner_loan.note,
        owner_loan.land_account_id,
        setting_land.land_account_name,
        owner_loan.employee_id,
        owner_loan.username,
        owner_loan.created_at,
        owner_loan.updated_at,

        COALESCE(SUM(
            CASE 
                WHEN owner_loan_payment.status='ACTIVE'
                 AND owner_loan_payment.deleted_at IS NULL
                THEN owner_loan_payment.pay_amount
                ELSE 0
            END
        ),0) AS paid_total,

        COALESCE(SUM(
            CASE 
                WHEN owner_loan_payment.status='ACTIVE'
                 AND owner_loan_payment.deleted_at IS NULL
                THEN owner_loan_payment.principal_amount
                ELSE 0
            END
        ),0) AS paid_principal_total,

        COALESCE(SUM(
            CASE 
                WHEN owner_loan_payment.status='ACTIVE'
                 AND owner_loan_payment.deleted_at IS NULL
                THEN owner_loan_payment.interest_amount
                ELSE 0
            END
        ),0) AS paid_interest_total,

        MAX(
            CASE 
                WHEN owner_loan_payment.status='ACTIVE'
                 AND owner_loan_payment.deleted_at IS NULL
                THEN owner_loan_payment.pay_date
                ELSE NULL
            END
        ) AS last_pay_date,

        (owner_loan.amount - COALESCE(SUM(
            CASE 
                WHEN owner_loan_payment.status='ACTIVE'
                 AND owner_loan_payment.deleted_at IS NULL
                THEN owner_loan_payment.principal_amount
                ELSE 0
            END
        ),0)) AS outstanding,

        DATEDIFF(CURDATE(), owner_loan.owner_loan_date) AS days_from_loan
        ");

        $builder->join('owner_loan_payment', 'owner_loan_payment.owner_loan_id = owner_loan.id', 'left');
        $builder->join('setting_land', 'setting_land.id = owner_loan.land_account_id', 'left');

        $builder->where('owner_loan.deleted_at IS NULL', null, false);
        $builder->whereIn('owner_loan.status', ['CLOSED', 'PAID']);

        if (!empty($date)) {
            $dateExplode = explode(" to ", $date);
            $dateStart = trim($dateExplode[0]);
            $dateEnd   = isset($dateExplode[1]) ? trim($dateExplode[1]) : $dateStart;

            // filter by closed_at date
            $builder->where("DATE(owner_loan.closed_at) >=", $dateStart);
            $builder->where("DATE(owner_loan.closed_at) <=", $dateEnd);
        }

        $builder->groupBy('owner_loan.id');

        // search
        $search = trim($post_data['search']['value'] ?? '');
        if ($search !== '') {
            $kw = $this->db->escapeLikeString($search);

            $builder->groupStart();
            foreach ($this->column_search_owner_loan_history as $item) {
                $builder->orLike($item, $kw);
            }
            $builder->groupEnd();
        }

        // order
        if (isset($post_data['order'][0]['column'])) {
            $colIdx = (int)$post_data['order'][0]['column'];
            $dir    = $post_data['order'][0]['dir'] ?? 'desc';

            $colName = $this->column_order_owner_loan_history[$colIdx] ?? null;
            if ($colName) {
                $builder->orderBy($colName, $dir);
            } else {
                $builder->orderBy('owner_loan.id', 'DESC');
            }
        } else {
            $builder->orderBy('owner_loan.id', 'DESC');
        }

        return $builder;
    }

    public function getAllDataOwnerLoanHistory(array $post_data)
    {
        $builder = $this->DataOwnerLoanHistoryQuery($post_data);

        if (($post_data['length'] ?? 10) != -1) {
            $builder->limit((int)$post_data['length'], (int)$post_data['start']);
        }

        return $builder->get()->getResult();
    }

    public function countAllDataOwnerLoanHistory(): int
    {
        // นับแบบ base เฉย ๆ (ไม่ join payment)
        return (int)$this->db->table('owner_loan')
            ->where('owner_loan.deleted_at IS NULL', null, false)
            ->whereIn('owner_loan.status', ['CLOSED', 'PAID'])
            ->countAllResults();
    }

    public function countFilteredDataOwnerLoanHistory(array $post_data): int
    {
        // นับ filtered ต้องใช้ subquery เพราะมี groupBy
        $builder = $this->DataOwnerLoanHistoryQuery($post_data);

        // ทำเป็น subquery เพื่อ count แถวหลัง group
        $sql = $builder->getCompiledSelect(false);
        $query = $this->db->query("SELECT COUNT(*) AS cnt FROM ({$sql}) x");
        $row = $query->getRow();

        return (int)($row->cnt ?? 0);
    }

    public function getOwnerLoanSummary($date = '')
    {
        $whereDate = '';
        $params = [];

        if (!empty($date)) {
            $dateExplode = explode(" to ", $date);
            $dateStart = trim($dateExplode[0]);
            $dateEnd   = isset($dateExplode[1]) ? trim($dateExplode[1]) : $dateStart;

            $whereDate = " AND owner_loan.owner_loan_date >= ? AND owner_loan.owner_loan_date <= ? ";
            $params[] = $dateStart;
            $params[] = $dateEnd;
        }

        $sql = "
        SELECT
            COALESCE(l.loan_amount_all, 0)        AS loan_amount_all,
            COALESCE(l.loan_amount_open, 0)       AS loan_amount_open,
            COALESCE(l.loan_amount_closed, 0)     AS loan_amount_closed,

            COALESCE(l.count_all, 0)              AS count_all,
            COALESCE(l.count_open, 0)             AS count_open,
            COALESCE(l.count_closed, 0)           AS count_closed,

            COALESCE(p.paid_total, 0)             AS paid_total,
            COALESCE(p.paid_principal, 0)         AS paid_principal,
            COALESCE(p.paid_interest, 0)          AS paid_interest,

            (COALESCE(l.loan_amount_open,0) - COALESCE(p.paid_principal_open,0)) AS outstanding_principal_open

        FROM
        (
            SELECT
                SUM(CASE WHEN owner_loan.status IN ('OPEN','CLOSED') THEN owner_loan.amount ELSE 0 END) AS loan_amount_all,
                SUM(CASE WHEN owner_loan.status = 'OPEN' THEN owner_loan.amount ELSE 0 END)            AS loan_amount_open,
                SUM(CASE WHEN owner_loan.status = 'CLOSED' THEN owner_loan.amount ELSE 0 END)          AS loan_amount_closed,

                COUNT(CASE WHEN owner_loan.status IN ('OPEN','CLOSED') THEN 1 END)                     AS count_all,
                COUNT(CASE WHEN owner_loan.status = 'OPEN' THEN 1 END)                                 AS count_open,
                COUNT(CASE WHEN owner_loan.status = 'CLOSED' THEN 1 END)                               AS count_closed
            FROM owner_loan
            WHERE owner_loan.deleted_at IS NULL
              AND owner_loan.status <> 'CANCEL'
              {$whereDate}
        ) l
        LEFT JOIN
        (
            SELECT
                SUM(CASE
                    WHEN owner_loan.status IN ('OPEN','CLOSED')
                     AND owner_loan_payment.status='ACTIVE'
                     AND owner_loan_payment.deleted_at IS NULL
                    THEN owner_loan_payment.pay_amount ELSE 0 END) AS paid_total,

                SUM(CASE
                    WHEN owner_loan.status IN ('OPEN','CLOSED')
                     AND owner_loan_payment.status='ACTIVE'
                     AND owner_loan_payment.deleted_at IS NULL
                    THEN owner_loan_payment.principal_amount ELSE 0 END) AS paid_principal,

                SUM(CASE
                    WHEN owner_loan.status IN ('OPEN','CLOSED')
                     AND owner_loan_payment.status='ACTIVE'
                     AND owner_loan_payment.deleted_at IS NULL
                    THEN owner_loan_payment.interest_amount ELSE 0 END) AS paid_interest,

                SUM(CASE
                    WHEN owner_loan.status='OPEN'
                     AND owner_loan_payment.status='ACTIVE'
                     AND owner_loan_payment.deleted_at IS NULL
                    THEN owner_loan_payment.principal_amount ELSE 0 END) AS paid_principal_open

            FROM owner_loan_payment
            INNER JOIN owner_loan ON owner_loan.id = owner_loan_payment.owner_loan_id
            WHERE owner_loan.deleted_at IS NULL
              AND owner_loan.status <> 'CANCEL'
              {$whereDate}
        ) p ON 1=1
        ";

        return $this->db->query($sql, $params)->getRow();
    }

    public function getTotalPrincipalOutstandingOpenLoan()
    {
        $sql = "
        SELECT
            COALESCE(SUM(owner_loan.amount), 0)
            - COALESCE(SUM(owner_loan_payment.principal_amount), 0)
            AS total_principal_outstanding
        FROM owner_loan
        LEFT JOIN owner_loan_payment
            ON owner_loan_payment.owner_loan_id = owner_loan.id
            AND owner_loan_payment.status = 'ACTIVE'
            AND owner_loan_payment.deleted_at IS NULL
        WHERE owner_loan.status = 'OPEN'
          AND owner_loan.deleted_at IS NULL
        ";

        $row = $this->db->query($sql)->getRow();
        return (float)($row->total_principal_outstanding ?? 0);
    }

    public function updateOwnerLoanByID($id, $data)
    {
        $builder = $this->db->table('owner_loan');

        return $builder->where('id', $id)->update($data);
    }
}
