<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class AutoloanModel
{
    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;

        // Set orderable column fields
        $this->column_order = [
            'autoloan.autoloan_code',
            'autoloan.autoloan_code',
            null,
            null,
            'autoloan.autoloan_date_promise',
            'autoloan.autoloan_payment_process',
            'autoloan.autoloan_summary_no_vat',
            'autoloan.autoloan_payment_year_counter',
            'autoloan.autoloan_status',
            null
        ];

        // Set searchable column fields
        $this->column_search = [
            'autoloan.autoloan_code',
            'customers.fullname',
            'customers.phone',
            'autoloan.autoloan_date_promise',
            'autoloan.autoloan_payment_process',
            'autoloan.autoloan_summary_no_vat'
        ];

        // Set default order
        $this->order = array('autoloan.autoloan_code' => 'DESC');
    }

    public function getAllDataLoanOn()
    {
        $sql = "SELECT * ,
        (SELECT autoloan_payment.loan_interest FROM autoloan_payment WHERE autoloan_payment.autoloan_code = auto.autoloan_code LIMIT 1) AS loan_interest,
        (SELECT COUNT(autoloan_payment_type) FROM autoloan_payment WHERE autoloan_payment.autoloan_code = auto.autoloan_code) AS autoloan_payment_type,
        (SELECT autoloan_payment.autoloan_payment_date FROM autoloan_payment WHERE autoloan_payment_installment = 1 AND autoloan_payment.autoloan_code = auto.autoloan_code) AS autoloan_payment_date,
        (SELECT autoloan_payment.autoloan_payment_date_fix FROM autoloan_payment WHERE autoloan_payment_installment = 1 AND autoloan_payment.autoloan_code = auto.autoloan_code) AS autoloan_payment_date_fix,
        (SELECT autoloan_payment.autoloan_payment_installment FROM autoloan_payment WHERE autoloan_payment.autoloan_code = auto.autoloan_code AND autoloan_payment.autoloan_payment_type IS NULL LIMIT 1) AS loan_period
        FROM autoloan auto
        left join customers cus
        on auto.autoloan_customer_id = cus.id
        WHERE auto.autoloan_status = 'ON_STATE' ORDER BY auto.id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getAllDataLoanByCode($loan_code)
    {
        $sql = "
        SELECT * FROM autoloan auto 
        left join customers cus
        on auto.autoloan_customer_id = cus.id
        WHERE auto.autoloan_code = '$loan_code' ORDER BY auto.id DESC
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
        $sql = "SELECT SUBSTRING(autoloan_running_code, 4,6) as substr_loan_code  FROM autoloan_running order by id desc LIMIT 1";
        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function insertLoanList($data, $running)
    {
        $builder_autoloan = $this->db->table('autoloan');
        $builder_autoloan_status = $builder_autoloan->insert($data);

        $builder_autoloan_running = $this->db->table('autoloan_running');
        $builder_autoloan_running_status = $builder_autoloan_running->insert($running);

        return ($builder_autoloan_status && $builder_autoloan_running_status) ? true : false;
    }

    public function updateLoan($code, $data_loan)
    {
        $builder_loan = $this->db->table('autoloan');
        $builder_loan_status = $builder_loan->where('autoloan_code', $code)->update($data_loan);

        return ($builder_loan_status) ? true : false;
    }

    public function getCus($cus)
    {
        $sql = "SELECT * FROM customers WHERE id = '$cus'";
        $builder = $this->db->query($sql);
        return $builder->getRow();
    }

    public function updateLoanPayment($data, $id)
    {
        $builder_payment = $this->db->table('autoloan_payment');
        $builder_payment_status = $builder_payment->where('id', $id)->update($data);

        return $builder_payment_status ? true : false;
    }


    public function updateLoanPaymentClose($data,$code)
    {
        $builder_payment = $this->db->table('autoloan_payment');
        $builder_payment_status = $builder_payment->where('autoloan_code', $code)->update($data);

        return $builder_payment_status ? true : false;
    }

    public function callInstallMent($id)
    {
        $sql = "SELECT id,autoloan_payment_installment FROM autoloan_payment WHERE id = '$id'";
        $builder = $this->db->query($sql);
        return $builder->getRow();
    }

    public function updateLoanSumPayment($data, $code)
    {
        $builder_loan = $this->db->table('autoloan');
        $builder_loan_status = $builder_loan->where('autoloan_code', $code)->update($data);

        return ($builder_loan_status) ? true : false;
    }

    public function getListPayment($code)
    {
        $sql = "
        SELECT 
        *, autoloan_payment.id as id_loan
        FROM autoloan_payment 
        left join autoloan 
        on  autoloan_payment.autoloan_code = autoloan.autoloan_code
        WHERE autoloan_payment.autoloan_code = '$code' ORDER BY autoloan_payment.id ASC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }
    public function getListPayments($code)
    {
        $sql = "
        SELECT * FROM autoloan_payment 
        WHERE autoloan_code = '$code' ORDER BY id ASC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getListPaymentByID($id)
    {
        $sql = "
        SELECT * FROM autoloan_payment 
        WHERE id = '$id'
        ";

        $builder = $this->db->query($sql);
        return $builder->getRow();
    }

    public function getYearCount($code)
    {
        $sql = "SELECT autoloan_payment_year_counter  FROM autoloan WHERE autoloan_code = '$code'";
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
        $builder = $this->db->table('autoloan');
        $builder->select(" 
         autoloan.autoloan_code, 
         autoloan.autoloan_stock_name,
         autoloan.autoloan_customer_id, 
         autoloan.autoloan_branch, 
         autoloan.autoloan_employee_response, 
         autoloan.autoloan_customer_grade,
         autoloan.autoloan_date_promise,
         autoloan.autoloan_summary_no_vat,
         autoloan.autoloan_payment_sum_installment,
         autoloan.autoloan_payment_year_counter,
         autoloan.autoloan_payment_interest,
         autoloan.autoloan_payment_month,
         autoloan.autoloan_payment_process,
         autoloan.autoloan_tranfer,
         autoloan.autoloan_payment_other,
         autoloan.autoloan_status,
         autoloan.autoloan_remnark,
         customers.fullname,
         customers.phone,
         customers.customer_grade,
         autoloan.autoloan_summary_all,
         (SELECT autoloan_payment.loan_interest FROM autoloan_payment WHERE autoloan_payment.autoloan_code = autoloan.autoloan_code LIMIT 1) AS loan_interest,
         (SELECT COUNT(autoloan_payment_type) FROM autoloan_payment WHERE autoloan_payment.autoloan_code = autoloan.autoloan_code) AS autoloan_payment_type,
         (SELECT autoloan_payment.autoloan_payment_date_fix FROM autoloan_payment WHERE autoloan_payment_installment = 1 AND autoloan_payment.autoloan_code = autoloan.autoloan_code) AS autoloan_payment_date_fix,
         (SELECT autoloan_payment.autoloan_payment_date FROM autoloan_payment WHERE autoloan_payment_installment = 1 AND autoloan_payment.autoloan_code = autoloan.autoloan_code) AS autoloan_payment_date
        ");

        $builder->join('customers', 'autoloan.autoloan_customer_id = customers.id', 'left');
        $builder->where("autoloan_status = 'CLOSE_STATE'");

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
        SELECT * FROM autoloan auto 
        left join customers cus
        on auto.autoloan_customer_id = cus.id
        WHERE auto.autoloan_status = 'CLOSE_STATE' ORDER BY auto.id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function countAllDataLoanHistory()
    {

        $sql = "
        SELECT count(auto.id) AS count_data FROM autoloan auto 
        left join customers cus
        on auto.autoloan_customer_id = cus.id
        WHERE auto.autoloan_status = 'CLOSE_STATE' ORDER BY auto.id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function insertpayment($payment_data)
    {
        $builder_payment = $this->db->table('autoloan_payment');
        $builder_payment_status = $builder_payment->insert($payment_data);

        return $builder_payment_status ? true : false;
    }

    public function getAllDataAutoloan()
    {
        $sql = "
        SELECT *,
        (SELECT COUNT(autoloan_payment_type) FROM autoloan_payment WHERE autoloan_payment.autoloan_code = autoloan.autoloan_code) AS autoloan_payment_type,
        (SELECT autoloan_payment.loan_interest FROM autoloan_payment WHERE autoloan_payment.autoloan_code = autoloan.autoloan_code LIMIT 1) AS loan_interest
        FROM autoloan
        WHERE autoloan.autoloan_status != 'CANCEL_STATE'
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getAutoloanPaymentByCode($loan_code)
    {
        $sql = "
        SELECT * FROM autoloan_payment
        WHERE autoloan_payment.autoloan_code = '$loan_code' AND autoloan_payment.autoloan_payment_type != '' AND autoloan_payment.autoloan_account_id != ''
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getRevenueListPayments($year)
    {
        $sql = "SELECT * , MONTH(autoloan_payment.autoloan_payment_date) as payment_month FROM autoloan_payment 
        WHERE YEAR(autoloan_payment.autoloan_payment_date) = $year ORDER BY id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getOpenAutoloan($year)
    {
        $sql = "SELECT * , MONTH(autoloan.autoloan_date_promise) as autoloan_month FROM autoloan
        WHERE YEAR(autoloan.autoloan_date_promise) = $year AND autoloan.autoloan_status != 'CANCEL_STATE' ORDER BY id DESC
        ";

        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getDataTablePaymentMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT * FROM autoloan_payment 
        WHERE YEAR(autoloan_payment.autoloan_payment_date) = $years AND MONTH(autoloan_payment.autoloan_payment_date) = $month
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

        $sql = "SELECT * , DATE_FORMAT(autoloan_payment.autoloan_payment_date , '%Y-%m-%d') as payment_date FROM autoloan_payment 
        WHERE YEAR(autoloan_payment.autoloan_payment_date) = $years AND MONTH(autoloan_payment.autoloan_payment_date) = $month ORDER BY autoloan_payment.autoloan_payment_date ASC
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

        $sql = "SELECT * , DATE_FORMAT(autoloan_payment.autoloan_payment_date , '%Y-%m-%d') as payment_date FROM autoloan_payment
        WHERE YEAR(autoloan_payment.autoloan_payment_date) = $years AND MONTH(autoloan_payment.autoloan_payment_date) = $month
        and ((autoloan_code like '%" . $search_value . "%') OR (autoloan_payment_customer like '%" . $search_value . "%') OR (autoloan_employee_response like '%" . $search_value . "%') 
           OR (autoloan_payment_installment like '%" . $search_value . "%') OR (autoloan_account_name like '%" . $search_value . "%') OR (autoloan_payment_pay_type like '%" . $search_value . "%')
           OR (autoloan_payment_amount like '%" . $search_value . "%') OR (autoloan_payment_date like '%" . $search_value . "%')) ORDER BY autoloan_payment.autoloan_payment_date ASC
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
        $sql = "SELECT * FROM autoloan_payment
        WHERE YEAR(autoloan_payment.autoloan_payment_date) = $years AND MONTH(autoloan_payment.autoloan_payment_date) = $month
        and ((autoloan_code like '%" . $search_value . "%') OR (autoloan_payment_customer like '%" . $search_value . "%') OR (autoloan_employee_response like '%" . $search_value . "%') 
           OR (autoloan_payment_installment like '%" . $search_value . "%') OR (autoloan_account_name like '%" . $search_value . "%') OR (autoloan_payment_pay_type like '%" . $search_value . "%')
           OR (autoloan_payment_amount like '%" . $search_value . "%') OR (autoloan_payment_date like '%" . $search_value . "%'))
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableAutoloanMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT * FROM autoloan
            left join customers on autoloan.autoloan_customer_id = customers.id
            WHERE YEAR(autoloan.autoloan_date_promise) = $years AND MONTH(autoloan.autoloan_date_promise) = $month AND autoloan.autoloan_status != 'CANCEL_STATE'
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableAutoloanMonth($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT * , DATE_FORMAT(autoloan.autoloan_date_promise , '%Y-%m-%d') as autoloan_date  FROM autoloan
            left join customers on autoloan.autoloan_customer_id = customers.id
            WHERE YEAR(autoloan.autoloan_date_promise) = $years AND MONTH(autoloan.autoloan_date_promise) = $month AND autoloan.autoloan_status != 'CANCEL_STATE' ORDER BY autoloan.autoloan_date_promise ASC
            LIMIT $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableAutoloanMonthSearch($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT * , DATE_FORMAT(autoloan.autoloan_date_promise , '%Y-%m-%d') as autoloan_date  FROM autoloan
        left join customers on autoloan.autoloan_customer_id = customers.id
        WHERE YEAR(autoloan.autoloan_date_promise) = $years AND MONTH(autoloan.autoloan_date_promise) = $month AND autoloan.autoloan_status != 'CANCEL_STATE'
        and ((autoloan_code like '%" . $search_value . "%') OR (autoloan_employee_response like '%" . $search_value . "%') OR (fullname like '%" . $search_value . "%') 
           OR (autoloan_branch like '%" . $search_value . "%') OR (autoloan_account_name like '%" . $search_value . "%') OR (autoloan_summary_no_vat like '%" . $search_value . "%')
           OR (autoloan_date_promise like '%" . $search_value . "%')) ORDER BY autoloan.autoloan_date_promise ASC
        LIMIT $start, $length
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableAutoloanMonthSearchCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $sql = "SELECT * FROM autoloan
        left join customers on autoloan.autoloan_customer_id = customers.id
        WHERE YEAR(autoloan.autoloan_date_promise) = $years AND MONTH(autoloan.autoloan_date_promise) = $month AND autoloan.autoloan_status != 'CANCEL_STATE'
        and ((autoloan_code like '%" . $search_value . "%') OR (autoloan_employee_response like '%" . $search_value . "%') OR (fullname like '%" . $search_value . "%') 
           OR (autoloan_branch like '%" . $search_value . "%') OR (autoloan_account_name like '%" . $search_value . "%') OR (autoloan_summary_no_vat like '%" . $search_value . "%')
           OR (autoloan_date_promise like '%" . $search_value . "%'))
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }
}
