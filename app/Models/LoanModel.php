<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class LoanModel
{
    protected $db;
    protected $column_order;
    protected $column_search;
    protected $order;

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
    }

    public function getAllDataLoanOn()
    {
        $sql = "SELECT * ,
        (SELECT COUNT(loan_payment_type) FROM loan_payment WHERE loan_payment.loan_code = loan.loan_code) AS loan_payment_type,
        (SELECT loan_payment.loan_payment_date FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code) AS loan_payment_date,
        (SELECT loan_payment.loan_payment_date_fix FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code) AS loan_payment_date_fix,
        (SELECT loan_payment.loan_payment_installment FROM loan_payment WHERE loan_payment.loan_code = loan.loan_code AND loan_payment.loan_payment_type IS NULL LIMIT 1) AS loan_period,
        TIMESTAMPDIFF(MONTH,(SELECT DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_period - 1) MONTH)  FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code),CURDATE()) AS loan_overdue
        FROM loan
        WHERE loan.loan_status = 'ON_STATE' ORDER BY loan.id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getAllDataLoanMessageAPI()
    {
        $sql = "SELECT  loan_code, loan_customer, loan_address, loan_payment_month,
        (SELECT loan_payment.loan_payment_date_fix FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code) AS loan_payment_date_fix,
        (SELECT loan_payment.loan_payment_installment FROM loan_payment WHERE loan_payment.loan_code = loan.loan_code AND loan_payment.loan_payment_type IS NULL LIMIT 1) AS loan_period
        FROM loan
        WHERE loan.loan_status = 'ON_STATE' ORDER BY loan.id DESC;
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }


    public function getAllDataLoanByCode($loan_code)
    {
        $sql = "SELECT loan.* , loan_customer.customer_fullname,loan_customer.customer_phone,loan_customer.customer_birthday,loan_customer.customer_card_id,loan_customer.customer_email,loan_customer.customer_gender,loan_customer.customer_address,
        (SELECT loan_payment.loan_payment_installment FROM loan_payment WHERE loan_payment.loan_code = loan.loan_code AND loan_payment.loan_payment_type IS NULL LIMIT 1) AS loan_period 
        FROM loan
        left join loan_customer on  loan.loan_code = loan_customer.loan_code
        WHERE loan.loan_code = '$loan_code' ORDER BY loan.id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getRow();
    }

    public function getEmps()
    {
        $sql = "SELECT * FROM employees";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getStockName()
    {
        $sql = "SELECT car_stock_name FROM car_stock GROUP BY car_stock_name";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getCodeLoank()
    {
        $sql = "SELECT SUBSTRING(loan_running_code, 4,6) as substr_loan_code  FROM loan_running order by id desc LIMIT 1";
        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function insertLoanList($data, $running)
    {
        $builder_loan = $this->db->table('loan');
        $builder_loan_status = $builder_loan->insert($data);

        $builder_loan_running = $this->db->table('loan_running');
        $builder_loan_running_status = $builder_loan_running->insert($running);

        return ($builder_loan_status && $builder_loan_running_status) ? true : false;
    }

    public function updateLoan($code, $data_loan)
    {
        $builder_loan = $this->db->table('loan');
        $builder_loan_status = $builder_loan->where('loan_code', $code)->update($data_loan);

        return ($builder_loan_status) ? true : false;
    }

    public function updateLoanPaymentDateFix($code, $data_loan)
    {
        $builder_loan = $this->db->table('loan_payment');
        $builder_loan_status = $builder_loan->where('loan_code', $code)->update($data_loan);

        return ($builder_loan_status) ? true : false;
    }

    public function updateLoanPayment($data, $id)
    {
        $builder_payment = $this->db->table('loan_payment');
        $builder_payment_status = $builder_payment->where('id', $id)->update($data);

        return $builder_payment_status ? true : false;
    }


    public function updateLoanPaymentClose($data, $code)
    {
        $builder_payment = $this->db->table('loan_payment');
        $builder_payment_status = $builder_payment->where('loan_code', $code)->update($data);

        return $builder_payment_status ? true : false;
    }

    public function updateLoanClosePayment($data, $code)
    {
        $builder_payment = $this->db->table('loan_payment');

        $where = "loan_code = '$code' AND loan_payment_type IS NULL";

        $builder_payment_status = $builder_payment->where($where)->update($data);

        return $builder_payment_status ? true : false;
    }

    public function callInstallMent($id)
    {
        $sql = "SELECT id,loan_payment_installment FROM loan_payment WHERE id = '$id'";
        $builder = $this->db->query($sql);
        return $builder->getRow();
    }

    public function updateLoanSumPayment($data, $code)
    {
        $builder_loan = $this->db->table('loan');
        $builder_loan_status = $builder_loan->where('loan_code', $code)->update($data);

        return ($builder_loan_status) ? true : false;
    }

    public function getListPayment($code)
    {
        $sql = "
        SELECT 
        *, loan_payment.id as id_loan
        FROM loan_payment
        left join loan
        on  loan_payment.loan_code = loan.loan_code
        WHERE loan_payment.loan_code = '$code' ORDER BY loan_payment.id ASC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }
    public function getListPayments($code)
    {
        $sql = "
        SELECT * FROM loan_payment 
        WHERE loan_code = '$code' ORDER BY id ASC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getListPaymentByID($id)
    {
        $sql = "
        SELECT * FROM loan_payment 
        WHERE id = '$id'
        ";

        $builder = $this->db->query($sql);
        return $builder->getRow();
    }

    public function getYearCount($code)
    {
        $sql = "SELECT loan_payment_year_counter  FROM loan WHERE loan_code = '$code'";
        $builder = $this->db->query($sql);
        return $builder->getRow();
    }

    public function _getAllDataLoanHistory($post_data)
    {
        // ***************************
        // ดึงข้อมูลตามเงื่อนไข
        // - Search
        // - OrderBy

        $builder = $this->DataLoanHistoryQuery($post_data);

        // นำ Builder ที่ได้มาลิมิต จาก Length ของ Datable ที่ส่งมา
        if ($post_data['length'] != -1) {
            $builder->limit($post_data['length'], $post_data['start']);
        }

        // ส่งข้อมูลออกไป
        return $builder->get()->getResult();
    }

    private function DataLoanHistoryQuery($post_data)
    {

        // Builder นี้ดัดแปลงจากคิวรี่ข้างบน
        $builder = $this->db->table('loan');
        $builder->select(" 
         loan.loan_code,
         loan.loan_customer,
         loan.loan_address, 
         loan.loan_employee, 
         loan.loan_number,
         loan.loan_area,
         loan.loan_date_close,
         loan.loan_date_promise,
         loan.loan_summary_no_vat,
         loan.loan_payment_sum_installment,
         loan.loan_payment_year_counter,
         loan.loan_payment_interest,
         loan.loan_payment_month,
         loan.loan_payment_process,
         loan.loan_type,
         loan.loan_tranfer,
         loan.loan_payment_other,
         loan.loan_status,
         loan.loan_remnark,
         loan.loan_summary_all,
         loan.loan_sum_interest,
         loan.loan_close_payment,
         (SELECT COUNT(loan_payment_type) FROM loan_payment WHERE loan_payment.loan_code = loan.loan_code) AS loan_payment_type,
         (SELECT loan_payment.loan_payment_date_fix FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code) AS loan_payment_date_fix,
         (SELECT loan_payment.loan_payment_date FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code) AS loan_payment_date
        ");
        $builder->where("loan_status = 'CLOSE_STATE'");

        $i = 0;
        // loop searchable columns
        foreach ($this->column_search as $item) {

            // if datatable send POST for search
            if ($post_data['search']['value']) {

                // first loop
                if ($i === 0) {
                    // open bracket
                    $builder->groupStart();
                    $builder->like($item, $post_data['search']['value']);
                } else {
                    $builder->orLike($item, $post_data['search']['value']);
                }

                // last loop
                if (count($this->column_search) - 1 == $i) {
                    $builder->like($item, $post_data['search']['value']);
                    // close bracket
                    $builder->groupEnd();
                }
            }

            $i++;
        }

        // มีการ order เข้ามา
        if (isset($post_data['order'])) {
            $builder->orderBy($this->column_order[$post_data['order']['0']['column']], $post_data['order']['0']['dir']);
        }

        // Default
        else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }

        // Debug คิวรี่ที่ได้
        // px($builder->getCompiledSelect());

        return $builder;
    }

    public function getAllDataLoanHistoryFilter()
    {

        $sql = "
        SELECT * FROM loan
        WHERE loan.loan_status = 'CLOSE_STATE' ORDER BY loan.id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function countAllDataLoanHistory()
    {

        $sql = "
        SELECT count(loan.id) AS count_data FROM loan
        WHERE loan.loan_status = 'CLOSE_STATE' ORDER BY loan.id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function insertpayment($payment_data)
    {
        $builder_payment = $this->db->table('loan_payment');
        $builder_payment_status = $builder_payment->insert($payment_data);

        return $builder_payment_status ? true : false;
    }

    public function getAllDataLoan()
    {
        $sql = "
        SELECT *
        FROM loan
        WHERE loan.loan_status != 'CANCEL_STATE'
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getLoanPaymentByCode($loan_code)
    {
        $sql = "
        SELECT * FROM loan_payment
        WHERE loan_payment.loan_code = '$loan_code' AND loan_payment.loan_payment_type != '' AND loan_payment.land_account_id != ''
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getRevenueListPayments($year)
    {
        $sql = "SELECT * , MONTH(loan_payment.loan_payment_date) as payment_month FROM loan_payment 
        WHERE YEAR(loan_payment.loan_payment_date) = $year ORDER BY id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getOpenLoan($year)
    {
        $sql = "SELECT * , MONTH(loan.loan_date_promise) as loan_month FROM loan
        WHERE YEAR(loan.loan_date_promise) = $year AND loan.loan_status != 'CANCEL_STATE' ORDER BY id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getOpenLoanMonthlySummary()
    {
        $sql = "SELECT SUM(loan_summary_no_vat) AS totalOpenLoan FROM loan
        WHERE YEAR(loan.loan_date_promise) = YEAR(CURDATE()) AND MONTH(loan.loan_date_promise) = MONTH(CURDATE()) AND loan.loan_status != 'CANCEL_STATE' ORDER BY id DESC;
        ";

        $builder = $this->db->query($sql);
        return $builder->getRow();
    }

    public function getDataTablePaymentMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT * FROM loan_payment 
        WHERE YEAR(loan_payment.loan_payment_date) = $years AND MONTH(loan_payment.loan_payment_date) = $month
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTablePaymentMonth($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT * , DATE_FORMAT(loan_payment.loan_payment_date , '%Y-%m-%d') as payment_date FROM loan_payment 
        WHERE YEAR(loan_payment.loan_payment_date) = $years AND MONTH(loan_payment.loan_payment_date) = $month ORDER BY loan_payment.loan_payment_date ASC
        LIMIT $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTablePaymentMonthSearch($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT * , DATE_FORMAT(loan_payment.loan_payment_date , '%Y-%m-%d') as payment_date FROM loan_payment
        WHERE YEAR(loan_payment.loan_payment_date) = $years AND MONTH(loan_payment.loan_payment_date) = $month
        and ((loan_code like '%" . $search_value . "%') OR (loan_payment_customer like '%" . $search_value . "%') OR (loan_employee like '%" . $search_value . "%') 
           OR (loan_payment_installment like '%" . $search_value . "%') OR (land_account_name like '%" . $search_value . "%') OR (loan_payment_pay_type like '%" . $search_value . "%')
           OR (loan_payment_amount like '%" . $search_value . "%') OR (loan_payment_date like '%" . $search_value . "%')) ORDER BY loan_payment.loan_payment_date ASC
        LIMIT $start, $length
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTablePaymentMonthSearchCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $sql = "SELECT * FROM loan_payment
        WHERE YEAR(loan_payment.loan_payment_date) = $years AND MONTH(loan_payment.loan_payment_date) = $month
        and ((loan_code like '%" . $search_value . "%') OR (loan_payment_customer like '%" . $search_value . "%') OR (loan_employee like '%" . $search_value . "%') 
           OR (loan_payment_installment like '%" . $search_value . "%') OR (land_account_name like '%" . $search_value . "%') OR (loan_payment_pay_type like '%" . $search_value . "%')
           OR (loan_payment_amount like '%" . $search_value . "%') OR (loan_payment_date like '%" . $search_value . "%'))
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT * FROM loan
            WHERE YEAR(loan.loan_date_promise) = $years AND MONTH(loan.loan_date_promise) = $month AND loan.loan_status != 'CANCEL_STATE'
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanMonth($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT * , DATE_FORMAT(loan.loan_date_promise , '%Y-%m-%d') as loan_date  FROM loan
            WHERE YEAR(loan.loan_date_promise) = $years AND MONTH(loan.loan_date_promise) = $month AND loan.loan_status != 'CANCEL_STATE' ORDER BY loan.loan_date_promise ASC
            LIMIT $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanMonthSearch($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT * , DATE_FORMAT(loan.loan_date_promise , '%Y-%m-%d') as loan_date  FROM loan
        WHERE YEAR(loan.loan_date_promise) = $years AND MONTH(loan.loan_date_promise) = $month AND loan.loan_status != 'CANCEL_STATE'
        and ((loan_code like '%" . $search_value . "%') OR (loan_employee like '%" . $search_value . "%')
           OR (loan_customer like '%" . $search_value . "%') OR (land_account_name like '%" . $search_value . "%') OR (loan_summary_no_vat like '%" . $search_value . "%')
           OR (loan_date_promise like '%" . $search_value . "%')) ORDER BY loan.loan_date_promise ASC
        LIMIT $start, $length
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanMonthSearchCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $sql = "SELECT * FROM loan
        WHERE YEAR(loan.loan_date_promise) = $years AND MONTH(loan.loan_date_promise) = $month AND loan.loan_status != 'CANCEL_STATE'
        and ((loan_code like '%" . $search_value . "%') OR (loan_employee like '%" . $search_value . "%')
           OR (loan_customer like '%" . $search_value . "%') OR (land_account_name like '%" . $search_value . "%') OR (loan_summary_no_vat like '%" . $search_value . "%')
           OR (loan_date_promise like '%" . $search_value . "%'))
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function insertOtherPiture($dataOthet_picture)
    {
        $builder_otherPicture = $this->db->table('picture_loan_other');
        $builder_otherPicture_status = $builder_otherPicture->insert($dataOthet_picture);

        return ($builder_otherPicture_status) ? true : false;
    }

    public function getOtherByCode($code)
    {
        $sql = "SELECT * FROM  picture_loan_other WHERE loan_code = '$code'
        ORDER BY id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function deleteOtherPiture($id)
    {
        $sql = "DELETE FROM picture_loan_other WHERE id = $id";
        $builder = $this->db->query($sql);
        return $builder;
    }

    public function dowloadPictureOther($code)
    {
        $sql = "SELECT picture_loan_src
         FROM `picture_loan_other` WHERE loan_code = '$code'";
        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getOverdueListPayments($year)
    {
        $sql = "SELECT * , DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH) as payment_date, 
        LPAD(MONTH(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)), 2, '0') as overdue_payment
        FROM loan_payment 
        JOIN loan ON loan_payment.loan_code = loan.loan_code
        WHERE YEAR(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)) = $year AND loan.loan_status != 'CANCEL_STATE' AND loan_payment.loan_payment_type IS NULL
        ORDER BY loan_payment.id ASC;
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getListPaymentMonths($year)
    {
        $sql = "SELECT * , DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH) as payment_date , 
        LPAD(MONTH(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)), 2, '0') as overdue_payment
        FROM loan_payment 
        JOIN loan ON loan_payment.loan_code = loan.loan_code
        WHERE YEAR(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)) = $year AND loan.loan_status != 'CANCEL_STATE'
        AND (
        (loan_payment.loan_payment_amount != loan.loan_close_payment
        AND loan_payment.loan_payment_amount != '0'
        AND loan_payment.loan_code = loan.loan_code
        AND loan.loan_status = 'CLOSE_STATE'
        ) OR (loan.loan_status != 'CLOSE_STATE'))
        ORDER BY loan_payment.id ASC;
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }


    public function getDataTableOverduePaymentMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT *
        FROM loan_payment 
        JOIN loan ON loan_payment.loan_code = loan.loan_code
        WHERE YEAR(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)) = $years AND loan.loan_status != 'CANCEL_STATE' AND loan_payment.loan_payment_type IS NULL
        AND LPAD(MONTH(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)), 2, '0') = $month
        ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableOverduePaymentMonth($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT * , DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH) as payment_date
        FROM loan_payment 
        JOIN loan ON loan_payment.loan_code = loan.loan_code
        WHERE YEAR(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)) = $years AND loan.loan_status != 'CANCEL_STATE' AND loan_payment.loan_payment_type IS NULL
        AND LPAD(MONTH(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)), 2, '0') = $month
        ORDER BY loan_payment.id ASC LIMIT $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableOverduePaymentMonthSearch($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT * , DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH) as payment_date
        FROM loan_payment 
        JOIN loan ON loan_payment.loan_code = loan.loan_code
        WHERE YEAR(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)) = $years AND loan.loan_status != 'CANCEL_STATE' AND loan_payment.loan_payment_type IS NULL
        AND LPAD(MONTH(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)), 2, '0') = $month
        and ((loan_payment.loan_code like '%" . $search_value . "%') OR (loan.loan_customer like '%" . $search_value . "%')
           OR (loan_payment.loan_payment_installment like '%" . $search_value . "%') OR (loan_payment.loan_payment_amount like '%" . $search_value . "%')) ORDER BY loan_payment.id ASC LIMIT $start, $length
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableOverduePaymentMonthSearchCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $sql = "SELECT * , DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH) as payment_date
        FROM loan_payment 
        JOIN loan ON loan_payment.loan_code = loan.loan_code
        WHERE YEAR(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)) = $years AND loan.loan_status != 'CANCEL_STATE' AND loan_payment.loan_payment_type IS NULL
        AND LPAD(MONTH(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)), 2, '0') = $month
        and ((loan_payment.loan_code like '%" . $search_value . "%') OR (loan.loan_customer like '%" . $search_value . "%')
           OR (loan_payment.loan_payment_installment like '%" . $search_value . "%') OR (loan_payment.loan_payment_amount like '%" . $search_value . "%'))
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableDiffPaymentMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT * , loan_payment.loan_employee as employee
        FROM loan_payment 
        JOIN loan ON loan_payment.loan_code = loan.loan_code
        WHERE YEAR(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)) = $years AND loan.loan_status != 'CANCEL_STATE'         
        AND (
        (loan_payment.loan_payment_amount != loan.loan_close_payment
        AND loan_payment.loan_payment_amount != '0'
        AND loan_payment.loan_code = loan.loan_code
        AND loan.loan_status = 'CLOSE_STATE'
        ) OR (loan.loan_status != 'CLOSE_STATE'))
        AND LPAD(MONTH(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)), 2, '0') = $month AND loan_payment.loan_payment_type IS NOT NULL
        ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableDiffPaymentMonth($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT * , loan_payment.loan_employee as employee , DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH) as payment_date
        FROM loan_payment 
        JOIN loan ON loan_payment.loan_code = loan.loan_code
        WHERE YEAR(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)) = $years AND loan.loan_status != 'CANCEL_STATE' 
        AND (
        (loan_payment.loan_payment_amount != loan.loan_close_payment
        AND loan_payment.loan_payment_amount != '0'
        AND loan_payment.loan_code = loan.loan_code
        AND loan.loan_status = 'CLOSE_STATE'
        ) OR (loan.loan_status != 'CLOSE_STATE'))
        AND LPAD(MONTH(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)), 2, '0') = $month AND loan_payment.loan_payment_type IS NOT NULL
        ORDER BY loan_payment.id ASC LIMIT $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableDiffPaymentMonthSearch($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT * , loan_payment.loan_employee as employee, DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH) as payment_date
        FROM loan_payment 
        JOIN loan ON loan_payment.loan_code = loan.loan_code
        WHERE YEAR(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)) = $years AND loan.loan_status != 'CANCEL_STATE'
        AND (
        (loan_payment.loan_payment_amount != loan.loan_close_payment
        AND loan_payment.loan_payment_amount != '0'
        AND loan_payment.loan_code = loan.loan_code
        AND loan.loan_status = 'CLOSE_STATE'
        ) OR (loan.loan_status != 'CLOSE_STATE'))
        AND LPAD(MONTH(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)), 2, '0') = $month AND loan_payment.loan_payment_type IS NOT NULL
        and ((loan_payment.loan_code like '%" . $search_value . "%') OR (loan_payment_customer like '%" . $search_value . "%') OR (loan_payment.loan_employee like '%" . $search_value . "%') OR (loan_payment.loan_payment_date like '%" . $search_value . "%')
           OR (loan_payment.loan_payment_installment like '%" . $search_value . "%') OR (loan_payment.loan_payment_amount like '%" . $search_value . "%')) ORDER BY loan_payment.id ASC LIMIT $start, $length
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableDiffPaymentMonthSearchCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $sql = "SELECT * , loan_payment.loan_employee as employee, DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH) as payment_date
        FROM loan_payment 
        JOIN loan ON loan_payment.loan_code = loan.loan_code
        WHERE YEAR(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)) = $years AND loan.loan_status != 'CANCEL_STATE'
        AND (
        (loan_payment.loan_payment_amount != loan.loan_close_payment
        AND loan_payment.loan_payment_amount != '0'
        AND loan_payment.loan_code = loan.loan_code
        AND loan.loan_status = 'CLOSE_STATE'
        ) OR (loan.loan_status != 'CLOSE_STATE'))
        AND LPAD(MONTH(DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_payment.loan_payment_installment - 1) MONTH)), 2, '0') = $month AND loan_payment.loan_payment_type IS NOT NULL
        and ((loan_payment.loan_code like '%" . $search_value . "%') OR (loan_payment_customer like '%" . $search_value . "%') OR (loan_payment.loan_employee like '%" . $search_value . "%') OR (loan_payment.loan_payment_date like '%" . $search_value . "%')
           OR (loan_payment.loan_payment_installment like '%" . $search_value . "%') OR (loan_payment.loan_payment_amount like '%" . $search_value . "%'))
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getAllDataLoanPayments()
    {
        $sql = "SELECT setting_land_report.*, setting_land.land_account_name
                FROM setting_land_report
                JOIN setting_land ON setting_land.id = setting_land_report.setting_land_id
                WHERE setting_land_report_detail LIKE 'ชำระสินเชื่อ%' OR setting_land_report_detail LIKE 'เปิดสินเชื่อ%' OR setting_land_report_detail LIKE 'ลบสินเชื่อ%'
                ORDER BY setting_land_report.id DESC;
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getDataLoanPaymentsDaily()
    {
        $sql = "SELECT setting_land_report.*, setting_land.land_account_name
                FROM setting_land_report
                JOIN setting_land ON setting_land.id = setting_land_report.setting_land_id
                WHERE DATE(setting_land_report.created_at) = CURDATE()
                ORDER BY setting_land_report.id ASC;
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getListLoanPaymentMonths($year)
    {
        $sql = "SELECT * , MONTH(created_at) as loan_created_payment
        FROM setting_land_report
        WHERE YEAR(created_at) = $year AND setting_land_report_detail LIKE 'ชำระสินเชื่อ%' AND setting_land_report_detail LIKE '%งวดที่%'
        ORDER BY id DESC;
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getLoanProcessMonths($year)
    {
        $sql = "SELECT MONTH(loan_date_promise) as loan_created_payment ,
        SUM(loan_payment_process) AS total_payment_process,
        SUM(loan_tranfer) AS total_tranfer,
        SUM(loan_payment_other) AS total_payment_other
        FROM loan
        WHERE YEAR(loan_date_promise) = $year AND loan.loan_status != 'CANCEL_STATE'
        GROUP BY MONTH(loan_date_promise)
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }


    public function getListLoanClosePaymentMonths($year)
    {
        $sql = "SELECT * , MONTH(created_at) as loan_created_close_payment
        FROM setting_land_report
        WHERE YEAR(created_at) = $year AND setting_land_report_detail LIKE '%ชำระปิดสินเชื่อ%' 
        ORDER BY id DESC;
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getDataTableLoanPaymentMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT * FROM setting_land_report 
        WHERE YEAR(setting_land_report.created_at) = $years AND MONTH(setting_land_report.created_at) = $month AND setting_land_report_detail LIKE 'ชำระสินเชื่อ%' AND setting_land_report_detail LIKE '%งวดที่%'
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanPaymentMonth($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT setting_land_report.*, setting_land.land_account_name, DATE_FORMAT(setting_land_report.created_at , '%Y-%m-%d') as payment_date FROM setting_land_report 
        JOIN setting_land ON setting_land.id = setting_land_report.setting_land_id
        WHERE YEAR(setting_land_report.created_at) = $years AND MONTH(setting_land_report.created_at) = $month AND setting_land_report_detail LIKE 'ชำระสินเชื่อ%' AND setting_land_report_detail LIKE '%งวดที่%'
        ORDER BY setting_land_report.created_at DESC
        LIMIT $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanPaymentMonthSearch($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT setting_land_report.*, setting_land.land_account_name, DATE_FORMAT(setting_land_report.created_at , '%Y-%m-%d') as payment_date FROM setting_land_report
        JOIN setting_land ON setting_land.id = setting_land_report.setting_land_id
        WHERE YEAR(setting_land_report.created_at) = $years AND MONTH(setting_land_report.created_at) = $month AND setting_land_report_detail LIKE 'ชำระสินเชื่อ%' AND setting_land_report_detail LIKE '%งวดที่%'
        and ((setting_land.land_account_name like '%" . $search_value . "%') OR (setting_land_report_detail like '%" . $search_value . "%') OR (setting_land_report_money like '%" . $search_value . "%') 
           OR (setting_land_report_note like '%" . $search_value . "%') OR (setting_land_report_account_balance like '%" . $search_value . "%') OR (setting_land_report.employee_name like '%" . $search_value . "%')
           OR (setting_land_report.created_at like '%" . $search_value . "%')) ORDER BY setting_land_report.created_at DESC
        LIMIT $start, $length
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanPaymentMonthSearchCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $sql = "SELECT setting_land_report.*, setting_land.land_account_name, DATE_FORMAT(setting_land_report.created_at , '%Y-%m-%d') as payment_date FROM setting_land_report
        JOIN setting_land ON setting_land.id = setting_land_report.setting_land_id
        WHERE YEAR(setting_land_report.created_at) = $years AND MONTH(setting_land_report.created_at) = $month AND setting_land_report_detail LIKE 'ชำระสินเชื่อ%' AND setting_land_report_detail LIKE '%งวดที่%'
        and ((setting_land.land_account_name like '%" . $search_value . "%') OR (setting_land_report_detail like '%" . $search_value . "%') OR (setting_land_report_money like '%" . $search_value . "%') 
           OR (setting_land_report_note like '%" . $search_value . "%') OR (setting_land_report_account_balance like '%" . $search_value . "%') OR (setting_land_report.employee_name like '%" . $search_value . "%')
           OR (setting_land_report.created_at like '%" . $search_value . "%'))
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanClosePaymentMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT * FROM setting_land_report 
        WHERE YEAR(setting_land_report.created_at) = $years AND MONTH(setting_land_report.created_at) = $month AND setting_land_report_detail LIKE '%ชำระปิดสินเชื่อ%'
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanClosePaymentMonth($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT setting_land_report.*, setting_land.land_account_name, DATE_FORMAT(setting_land_report.created_at , '%Y-%m-%d') as payment_date FROM setting_land_report 
        JOIN setting_land ON setting_land.id = setting_land_report.setting_land_id
        WHERE YEAR(setting_land_report.created_at) = $years AND MONTH(setting_land_report.created_at) = $month AND setting_land_report_detail LIKE '%ชำระปิดสินเชื่อ%'
        ORDER BY setting_land_report.created_at DESC
        LIMIT $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanClosePaymentMonthSearch($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT setting_land_report.*, setting_land.land_account_name, DATE_FORMAT(setting_land_report.created_at , '%Y-%m-%d') as payment_date FROM setting_land_report
        JOIN setting_land ON setting_land.id = setting_land_report.setting_land_id
        WHERE YEAR(setting_land_report.created_at) = $years AND MONTH(setting_land_report.created_at) = $month AND setting_land_report_detail LIKE '%ชำระปิดสินเชื่อ%'
        and ((setting_land.land_account_name like '%" . $search_value . "%') OR (setting_land_report_detail like '%" . $search_value . "%') OR (setting_land_report_money like '%" . $search_value . "%') 
           OR (setting_land_report_note like '%" . $search_value . "%') OR (setting_land_report_account_balance like '%" . $search_value . "%') OR (setting_land_report.employee_name like '%" . $search_value . "%')
           OR (setting_land_report.created_at like '%" . $search_value . "%')) ORDER BY setting_land_report.created_at DESC
        LIMIT $start, $length
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanClosePaymentMonthSearchCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $sql = "SELECT setting_land_report.*, setting_land.land_account_name, DATE_FORMAT(setting_land_report.created_at , '%Y-%m-%d') as payment_date FROM setting_land_report
        JOIN setting_land ON setting_land.id = setting_land_report.setting_land_id
        WHERE YEAR(setting_land_report.created_at) = $years AND MONTH(setting_land_report.created_at) = $month AND setting_land_report_detail LIKE '%ชำระปิดสินเชื่อ%'
        and ((setting_land.land_account_name like '%" . $search_value . "%') OR (setting_land_report_detail like '%" . $search_value . "%') OR (setting_land_report_money like '%" . $search_value . "%') 
           OR (setting_land_report_note like '%" . $search_value . "%') OR (setting_land_report_account_balance like '%" . $search_value . "%') OR (setting_land_report.employee_name like '%" . $search_value . "%')
           OR (setting_land_report.created_at like '%" . $search_value . "%'))
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getAllLoan()
    {
        $sql = "
        SELECT *
        FROM loan
        WHERE loan.loan_status = 'ON_STATE'
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getLoanRevenuesDay()
    {
        $sql = "SELECT * FROM setting_land_report 
            WHERE DATE(created_at) = CURDATE() AND setting_land_report_detail NOT LIKE '%เปิดสินเชื่อ%' AND setting_land_report_detail NOT LIKE '%ลบสินเชื่อ %'
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getLoanExpensesDay()
    {
        $sql = "SELECT * FROM setting_land_report 
            WHERE DATE(created_at) = CURDATE() AND (setting_land_report_detail LIKE '%เปิดสินเชื่อ%' OR setting_land_report_detail LIKE '%ลบสินเชื่อ %')
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function DataLoanHistoryQueryAI()
    {
        $builder = $this->db->table('loan');
        $builder->select(" 
         loan.loan_code,
         loan.loan_customer,
         loan.loan_address, 
         loan.loan_employee, 
         loan.loan_number,
         loan.loan_area,
         loan.loan_date_close,
         loan.loan_date_promise,
         loan.loan_summary_no_vat,
         loan.loan_payment_sum_installment,
         loan.loan_payment_year_counter,
         loan.loan_payment_interest,
         loan.loan_payment_month,
         loan.loan_payment_process,
         loan.loan_type,
         loan.loan_tranfer,
         loan.loan_payment_other,
         loan.loan_status,
         loan.loan_remnark,
         loan.loan_summary_all,
         loan.loan_sum_interest,
         loan.loan_close_payment,
         (SELECT COUNT(loan_payment_type) FROM loan_payment WHERE loan_payment.loan_code = loan.loan_code) AS loan_payment_type,
         (SELECT loan_payment.loan_payment_date_fix FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code) AS loan_payment_date_fix,
         (SELECT loan_payment.loan_payment_date FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code) AS loan_payment_date
        ");
        $builder->where("loan_status = 'CLOSE_STATE'");
        $builder->orderBy("loan_code", "DESC");
        return $builder->get()->getResult();
    }

    public function getDataTableLoanProcessMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT loan_code , loan_employee , loan_payment_process , loan_tranfer , loan_payment_other , DATE(created_at) AS created_at
        FROM loan
        WHERE YEAR(created_at) = $years AND MONTH(created_at) = $month AND loan.loan_status != 'CANCEL_STATE'
        AND (loan_payment_process != 0 OR loan_tranfer != 0 OR loan_payment_other != 0)
        ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanProcessMonth($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT loan_code , loan_employee , loan_payment_process , loan_tranfer , loan_payment_other , DATE(created_at) AS created_at
        FROM loan
        WHERE YEAR(created_at) = $years AND MONTH(created_at) = $month AND loan.loan_status != 'CANCEL_STATE' 
        AND (loan_payment_process != 0 OR loan_tranfer != 0 OR loan_payment_other != 0)
        ORDER BY created_at ASC LIMIT $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanProcessMonthSearch($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT loan_code , loan_employee , loan_payment_process , loan_tranfer , loan_payment_other , DATE(created_at) AS created_at
        FROM loan
        WHERE YEAR(created_at) = $years AND MONTH(created_at) = $month AND loan.loan_status != 'CANCEL_STATE' 
        AND (loan_payment_process != 0 OR loan_tranfer != 0 OR loan_payment_other != 0)
        AND ((loan_code like '%" . $search_value . "%') OR (loan_employee like '%" . $search_value . "%') OR (loan_payment_process like '%" . $search_value . "%')
           OR (loan_tranfer like '%" . $search_value . "%') OR (loan_payment_other like '%" . $search_value . "%') OR (created_at like '%" . $search_value . "%')) 
           ORDER BY created_at ASC LIMIT $start, $length
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableLoanProcessMonthSearchCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $sql = "SELECT loan_code , loan_employee , loan_payment_process , loan_tranfer , loan_payment_other , DATE(created_at) AS created_at
        FROM loan
        WHERE YEAR(created_at) = $years AND MONTH(created_at) = $month AND loan.loan_status != 'CANCEL_STATE' 
        AND (loan_payment_process != 0 OR loan_tranfer != 0 OR loan_payment_other != 0)
        AND ((loan_code like '%" . $search_value . "%') OR (loan_employee like '%" . $search_value . "%') OR (loan_payment_process like '%" . $search_value . "%')
           OR (loan_tranfer like '%" . $search_value . "%') OR (loan_payment_other like '%" . $search_value . "%') OR (created_at like '%" . $search_value . "%'))
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function checkDuplicate($amount, $date, $time)
    {
        $sql = "
        SELECT id
        FROM loan_payment
        WHERE payment_file_price = ?
          AND payment_file_date = ?
          AND payment_file_time = ?
        LIMIT 1
    ";

        $builder = $this->db->query($sql, [$amount, $date, $time]);
        return $builder->getRow();
    }

    public function getAllDataFinxOn()
    {
        $sql = "SELECT * ,
        (SELECT COUNT(loan_payment_type) FROM loan_payment WHERE loan_payment.loan_code = loan.loan_code) AS loan_payment_type,
        (SELECT loan_payment.loan_payment_date FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code) AS loan_payment_date,
        (SELECT loan_payment.loan_payment_date_fix FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code) AS loan_payment_date_fix,
        (SELECT loan_payment.loan_payment_installment FROM loan_payment WHERE loan_payment.loan_code = loan.loan_code AND loan_payment.loan_payment_type IS NULL LIMIT 1) AS loan_period,
        TIMESTAMPDIFF(MONTH,(SELECT DATE_ADD(loan_payment.loan_payment_date_fix, INTERVAL (loan_period - 1) MONTH)  FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code),CURDATE()) AS loan_overdue
        FROM loan
        WHERE loan.loan_status = 'ON_STATE' AND loan.loan_type = 'เงินสด'
        ORDER BY loan.id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }


    public function _getAllDataFinxHistory($post_data)
    {
        $builder = $this->DataFinxHistoryQuery($post_data);

        // นำ Builder ที่ได้มาลิมิต จาก Length ของ Datable ที่ส่งมา
        if ($post_data['length'] != -1) {
            $builder->limit($post_data['length'], $post_data['start']);
        }

        // ส่งข้อมูลออกไป
        return $builder->get()->getResult();
    }

    private function DataFinxHistoryQuery($post_data)
    {

        // Builder นี้ดัดแปลงจากคิวรี่ข้างบน
        $builder = $this->db->table('loan');
        $builder->select(" 
         loan.loan_code,
         loan.loan_customer,
         loan.loan_address, 
         loan.loan_employee, 
         loan.loan_number,
         loan.loan_area,
         loan.loan_date_close,
         loan.loan_date_promise,
         loan.loan_summary_no_vat,
         loan.loan_payment_sum_installment,
         loan.loan_payment_year_counter,
         loan.loan_payment_interest,
         loan.loan_payment_month,
         loan.loan_payment_process,
         loan.loan_type,
         loan.loan_tranfer,
         loan.loan_payment_other,
         loan.loan_status,
         loan.loan_remnark,
         loan.loan_summary_all,
         loan.loan_sum_interest,
         loan.loan_close_payment,
         (SELECT COUNT(loan_payment_type) FROM loan_payment WHERE loan_payment.loan_code = loan.loan_code) AS loan_payment_type,
         (SELECT loan_payment.loan_payment_date_fix FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code) AS loan_payment_date_fix,
         (SELECT loan_payment.loan_payment_date FROM loan_payment WHERE loan_payment_installment = 1 AND loan_payment.loan_code = loan.loan_code) AS loan_payment_date
        ");
        $builder->where("loan_status = 'CLOSE_STATE'");
        $builder->where("loan_type = 'เงินสด'");

        $i = 0;
        // loop searchable columns
        foreach ($this->column_search as $item) {

            // if datatable send POST for search
            if ($post_data['search']['value']) {

                // first loop
                if ($i === 0) {
                    // open bracket
                    $builder->groupStart();
                    $builder->like($item, $post_data['search']['value']);
                } else {
                    $builder->orLike($item, $post_data['search']['value']);
                }

                // last loop
                if (count($this->column_search) - 1 == $i) {
                    $builder->like($item, $post_data['search']['value']);
                    // close bracket
                    $builder->groupEnd();
                }
            }

            $i++;
        }

        // มีการ order เข้ามา
        if (isset($post_data['order'])) {
            $builder->orderBy($this->column_order[$post_data['order']['0']['column']], $post_data['order']['0']['dir']);
        }

        // Default
        else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }

        // Debug คิวรี่ที่ได้
        // px($builder->getCompiledSelect());

        return $builder;
    }

    public function getAllDataFinxHistoryFilter()
    {

        $sql = "
        SELECT * FROM loan
        WHERE loan.loan_status = 'CLOSE_STATE' AND loan.loan_type = 'เงินสด'
        ORDER BY loan.id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function countAllDataFinxHistory()
    {

        $sql = "
        SELECT count(loan.id) AS count_data FROM loan
        WHERE loan.loan_status = 'CLOSE_STATE' AND loan.loan_type = 'เงินสด'
        ORDER BY loan.id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getAllDataFinx()
    {
        $sql = "
        SELECT *
        FROM loan
        WHERE loan.loan_status != 'CANCEL_STATE' AND loan.loan_type = 'เงินสด'
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getOpenLoanFinx($year)
    {
        $sql = "SELECT * , MONTH(loan.loan_date_promise) as loan_month FROM loan
        WHERE YEAR(loan.loan_date_promise) = $year AND loan.loan_status != 'CANCEL_STATE' AND loan.loan_type = 'เงินสด'
        ORDER BY id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getListLoanFinxClosePaymentMonths($year)
    {
        $sql = "
        SELECT 
            loan.*,
            MONTH(loan.loan_date_close) AS loan_date_close_month
        FROM loan
        WHERE YEAR(loan.loan_date_close) = $year 
          AND loan.loan_type = 'เงินสด'
        ORDER BY loan.id DESC
    ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getDataTableLoaFinxClosePaymentMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT COUNT(*) as total
            FROM loan
            WHERE YEAR(loan.loan_date_close) = $years 
              AND MONTH(loan.loan_date_close) = $month 
              AND loan.loan_type = 'เงินสด'";

        $builder = $this->db->query($sql);
        return $builder->getRow()->total; // ✅ return count จริง
    }

    public function getDataTableLoanFinxClosePaymentMonth($param)
    {
        $month        = $param['month'];
        $years        = $param['years'];
        $start        = $param['start'];
        $length       = $param['length'];
        $order_column = $param['order_column'] ?? 'loan.loan_date_close';
        $order_dir    = $param['order_dir'] ?? 'DESC';

        $sql = "SELECT loan.*, loan_customer.customer_fullname
            FROM loan
            LEFT JOIN loan_customer ON loan.loan_code = loan_customer.loan_code
            WHERE YEAR(loan.loan_date_close) = $years 
              AND MONTH(loan.loan_date_close) = $month 
              AND loan.loan_type = 'เงินสด'
            ORDER BY $order_column $order_dir
            LIMIT $start, $length";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getDataTableLoanFinxClosePaymentMonthSearch($param)
    {
        $month        = $param['month'];
        $years        = $param['years'];
        $search_value = $param['search_value'];
        $start        = $param['start'];
        $length       = $param['length'];
        $order_column = $param['order_column'] ?? 'loan.loan_date_close';
        $order_dir    = $param['order_dir'] ?? 'DESC';

        $sql = "SELECT loan.*, loan_customer.customer_fullname
            FROM loan
            LEFT JOIN loan_customer ON loan.loan_code = loan_customer.loan_code
            WHERE YEAR(loan.loan_date_close) = $years 
              AND MONTH(loan.loan_date_close) = $month 
              AND loan.loan_type = 'เงินสด'
              AND (
                loan.loan_code LIKE '%$search_value%'
                OR loan_customer.customer_fullname LIKE '%$search_value%'
                OR loan.loan_employee LIKE '%$search_value%'
                OR loan.loan_close_payment LIKE '%$search_value%'
                OR loan.loan_date_close LIKE '%$search_value%'
              )
            ORDER BY $order_column $order_dir
            LIMIT $start, $length";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getDataTableLoanFinxClosePaymentMonthSearchCount($param)
    {
        $month        = $param['month'];
        $years        = $param['years'];
        $search_value = $param['search_value'];

        $sql = "SELECT COUNT(*) as total
            FROM loan
            LEFT JOIN loan_customer ON loan.loan_code = loan_customer.loan_code
            WHERE YEAR(loan.loan_date_close) = $years 
              AND MONTH(loan.loan_date_close) = $month 
              AND loan.loan_type = 'เงินสด'
              AND (
                loan.loan_code LIKE '%$search_value%'
                OR loan_customer.customer_fullname LIKE '%$search_value%'
                OR loan.loan_employee LIKE '%$search_value%'
                OR loan.loan_close_payment LIKE '%$search_value%'
                OR loan.loan_date_close LIKE '%$search_value%'
              )";

        $builder = $this->db->query($sql);
        return $builder->getRow()->total; // ✅ return count จริง
    }

    public function getDataTableLoanFinxPaymentMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT COUNT(*) as total
            FROM loan
            LEFT JOIN loan_customer ON loan.loan_code = loan_customer.loan_code
            WHERE YEAR(loan.loan_date_close) = $years 
              AND MONTH(loan.loan_date_close) = $month 
              AND loan.loan_type = 'เงินสด'";

        $builder = $this->db->query($sql);
        return $builder->getRow()->total; // ✅ return count จริง
    }

    public function getDataTableLoanFinxPaymentMonth($param)
    {
        $month        = $param['month'];
        $years        = $param['years'];
        $start        = $param['start'];
        $length       = $param['length'];
        $order_column = $param['order_column'] ?? 'loan.loan_date_close';
        $order_dir    = $param['order_dir'] ?? 'DESC';

        $sql = "SELECT loan.*, loan_customer.customer_fullname, 
                   (loan.loan_close_payment * 0.03) AS loan_payment_3percent
            FROM loan
            LEFT JOIN loan_customer ON loan.loan_code = loan_customer.loan_code
            WHERE YEAR(loan.loan_date_close) = $years 
              AND MONTH(loan.loan_date_close) = $month 
              AND loan.loan_type = 'เงินสด'
            ORDER BY $order_column $order_dir
            LIMIT $start, $length";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getDataTableLoanFinxPaymentMonthSearch($param)
    {
        $month        = $param['month'];
        $years        = $param['years'];
        $search_value = $param['search_value'];
        $start        = $param['start'];
        $length       = $param['length'];
        $order_column = $param['order_column'] ?? 'loan.loan_date_close';
        $order_dir    = $param['order_dir'] ?? 'DESC';

        $sql = "SELECT loan.*, loan_customer.customer_fullname, 
                   (loan.loan_close_payment * 0.03) AS loan_payment_3percent
            FROM loan
            LEFT JOIN loan_customer ON loan.loan_code = loan_customer.loan_code
            WHERE YEAR(loan.loan_date_close) = $years 
              AND MONTH(loan.loan_date_close) = $month 
              AND loan.loan_type = 'เงินสด'
              AND (
                loan.loan_code LIKE '%$search_value%' 
                OR loan_customer.customer_fullname LIKE '%$search_value%' 
                OR loan.loan_employee LIKE '%$search_value%' 
                OR (loan.loan_close_payment * 0.03) LIKE '%$search_value%'
                OR loan.loan_date_close LIKE '%$search_value%'
              )
            ORDER BY $order_column $order_dir
            LIMIT $start, $length";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getDataTableLoanFinxPaymentMonthSearchCount($param)
    {
        $month        = $param['month'];
        $years        = $param['years'];
        $search_value = $param['search_value'];

        $sql = "SELECT COUNT(*) as total
            FROM loan
            LEFT JOIN loan_customer ON loan.loan_code = loan_customer.loan_code
            WHERE YEAR(loan.loan_date_close) = $years 
              AND MONTH(loan.loan_date_close) = $month 
              AND loan.loan_type = 'เงินสด'
              AND (
                loan.loan_code LIKE '%$search_value%' 
                OR loan_customer.customer_fullname LIKE '%$search_value%' 
                OR loan.loan_employee LIKE '%$search_value%' 
                OR (loan.loan_close_payment * 0.03) LIKE '%$search_value%'
                OR loan.loan_date_close LIKE '%$search_value%'
              )";

        $builder = $this->db->query($sql);
        return $builder->getRow()->total; // ✅ return count จริง
    }

    public function getDataTableOpenLoanFinxMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT COUNT(*) as total
            FROM loan
            LEFT JOIN loan_customer ON loan.loan_code = loan_customer.loan_code
            WHERE YEAR(loan.loan_date_promise) = $years 
              AND MONTH(loan.loan_date_promise) = $month 
              AND loan.loan_status != 'CANCEL_STATE' 
              AND loan.loan_type = 'เงินสด'";

        $builder = $this->db->query($sql);
        return $builder->getRow()->total ?? 0;
    }

    public function getDataTableOpenLoanFinxMonth($param)
    {
        $month       = $param['month'];
        $years       = $param['years'];
        $start       = $param['start'];
        $length      = $param['length'];
        $order_col   = $param['order_column'];
        $order_dir   = $param['order_dir'];

        $sql = "SELECT loan.*, DATE_FORMAT(loan.loan_date_promise , '%Y-%m-%d') as loan_date  
            FROM loan
            LEFT JOIN loan_customer ON loan.loan_code = loan_customer.loan_code
            WHERE YEAR(loan.loan_date_promise) = $years 
              AND MONTH(loan.loan_date_promise) = $month 
              AND loan.loan_status != 'CANCEL_STATE' 
              AND loan.loan_type = 'เงินสด'
            ORDER BY $order_col $order_dir
            LIMIT $start, $length";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getDataTableOpenLoanFinxMonthSearch($param)
    {
        $month        = $param['month'];
        $years        = $param['years'];
        $search_value = $param['search_value'];
        $start        = $param['start'];
        $length       = $param['length'];
        $order_col    = $param['order_column'];
        $order_dir    = $param['order_dir'];

        $sql = "SELECT loan.*, DATE_FORMAT(loan.loan_date_promise , '%Y-%m-%d') as loan_date  
            FROM loan
            LEFT JOIN loan_customer ON loan.loan_code = loan_customer.loan_code
            WHERE YEAR(loan.loan_date_promise) = $years 
              AND MONTH(loan.loan_date_promise) = $month 
              AND loan.loan_status != 'CANCEL_STATE' 
              AND loan.loan_type = 'เงินสด'
              AND (
                loan.loan_code LIKE '%$search_value%' 
                OR loan.loan_employee LIKE '%$search_value%'
                OR loan_customer.customer_fullname LIKE '%$search_value%'
                OR loan.loan_summary_no_vat LIKE '%$search_value%'
                OR loan.loan_date_promise LIKE '%$search_value%'
              )
            ORDER BY $order_col $order_dir
            LIMIT $start, $length";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getDataTableOpenLoanFinxMonthSearchCount($param)
    {
        $month        = $param['month'];
        $years        = $param['years'];
        $search_value = $param['search_value'];

        $sql = "SELECT COUNT(*) as total
            FROM loan
            LEFT JOIN loan_customer ON loan.loan_code = loan_customer.loan_code
            WHERE YEAR(loan.loan_date_promise) = $years 
              AND MONTH(loan.loan_date_promise) = $month 
              AND loan.loan_status != 'CANCEL_STATE' 
              AND loan.loan_type = 'เงินสด'
              AND (
                loan.loan_code LIKE '%$search_value%' 
                OR loan.loan_employee LIKE '%$search_value%'
                OR loan_customer.customer_fullname LIKE '%$search_value%'
                OR loan.loan_summary_no_vat LIKE '%$search_value%'
                OR loan.loan_date_promise LIKE '%$search_value%'
              )";

        $builder = $this->db->query($sql);
        return $builder->getRow()->total ?? 0;
    }
}
