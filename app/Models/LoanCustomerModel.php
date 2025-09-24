<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class LoanCustomerModel
{
    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getLoanCustomerAll()
    {
        $builder = $this->db->table('loan_customer');

        return $builder
            ->where('deleted_at', NULL)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getLoanCustomerByID($id)
    {
        $builder = $this->db->table('loan_customer');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertLoanCustomer($data)
    {
        $builder = $this->db->table('loan_customer');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateLoanCustomerByID($id, $data)
    {
        $builder = $this->db->table('loan_customer');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteLoanCustomerByID($id)
    {
        $builder = $this->db->table('loan_customer');

        return $builder->where('id', $id)->delete();
    }

    public function getCustomerByLoanCode($code)
    {
        $sql = "SELECT * FROM  loan_customer WHERE loan_code = '$code'";

        $builder = $this->db->query($sql);
        return $builder->getRow();
    }

    public function updateLoanCustomerByLoanCode($code, $data)
    {
        $builder = $this->db->table('loan_customer');

        return $builder->where('loan_code', $code)->update($data);
    }
}
