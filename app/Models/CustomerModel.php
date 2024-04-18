<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class CustomerModel
{
    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getCustomerAll()
    {
        $builder = $this->db->table('customers');

        return $builder
            ->where('deleted_at', NULL)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getCustomerByID($id)
    {
        $builder = $this->db->table('customers');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertCustomer($data)
    {
        $builder = $this->db->table('customers');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateCustomerByID($id, $data)
    {
        $builder = $this->db->table('customers');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteCustomerByID($id)
    {
        $builder = $this->db->table('customers');

        return $builder->where('id', $id)->delete();
    }

    // ข้อมูลเฉพราะวันเกิด
    public function getCustomerHBD()
    {
        $sql = "
        SELECT customers.* FROM customers
        WHERE MONTH(birthday) = MONTH(CURDATE()) AND DAY(birthday) =  DAY(CURDATE())";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getCustomerAllANDInterest()
    {
        $sql = "";

        if(session()->get('status_customer_seller') == '1')
        {
            $sql = "
            SELECT customers.*,car_types.title as car_types_title,car_brands.title as car_brands_title ,employees.name as empName FROM customers
            LEFT JOIN car_types ON customers.types_interest = car_types.id
            LEFT JOIN car_brands ON customers.brand_interest = car_brands.id
            LEFT JOIN employees ON customers.employee_id = employees.id
            WHERE customers.deleted_at IS NULL
            ORDER BY customers.created_at DESC";
        }
        else
        {
            $full_name = session()->get('employee_fullname');
            $sql = "
            SELECT customers.*,car_types.title as car_types_title,car_brands.title as car_brands_title, employees.name as empName FROM customers
            LEFT JOIN car_types ON customers.types_interest = car_types.id
            LEFT JOIN car_brands ON customers.brand_interest = car_brands.id
            LEFT JOIN employees ON customers.employee_id = employees.id
            WHERE (customers.deleted_at IS NULL) and (employees.name = '$full_name')
            ORDER BY customers.created_at DESC";
        }

    
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getCustomerByCustomerTypeID($customerTypeID)
    {
        $builder = $this->db->table('customers');

        return $builder
            ->where('customer_type_id', $customerTypeID)
            ->orderBy('fullname', 'ASC')
            ->get()
            ->getResult();
    }

    public function getCustomerByCustomerTypeIDAndKeyword($customerTypeID, $keyword)
    {
        $sql = "
            SELECT * FROM customers
            WHERE customer_type_id = $customerTypeID AND (fullname LIKE '%$keyword%' OR phone LIKE '%$keyword%' OR card_id LIKE '%$keyword%')
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getCustomerByCustomerTypeIDANDEmployeeID($customerTypeID, $id)
    {
        $sql = "
        SELECT * FROM customers
        WHERE customer_type_id = $customerTypeID AND employee_id = $id
    ";

    $builder = $this->db->query($sql);

    return $builder->getResult();
    }

    public function getCustomerByCustomerTypeIDAndKeywordANDEmployeeID($customerTypeID, $keyword, $id)
    {
        $sql = "
            SELECT * FROM customers
            WHERE customer_type_id = $customerTypeID AND employee_id = $id AND (fullname LIKE '%$keyword%' OR phone LIKE '%$keyword%' OR card_id LIKE '%$keyword%')
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getCarTypesByID($id)
    {
        $builder = $this->db->table('car_types');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function getCarBrandsByID($id)
    {
        $builder = $this->db->table('car_brands');

        return $builder->where('id', $id)->get()->getRow();
    }
}