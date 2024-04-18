<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class EmployeeLogModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;

        // Set orderable column fields
        $this->column_order = array(null, 'detail', 'username', 'created_at');
        // Set searchable column fields
        $this->column_search = array('detail', 'username', 'created_at');
        // Set default order
        $this->order = array('created_at' => 'DESC');
    }

    public function getEmployeeLogAll()
    {
        $sql = "
            SELECT employee_logs.* FROM employee_logs 
            JOIN employees ON employee_logs.employee_id = employees.id
            ORDER BY employee_logs.created_at DESC
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getEmployeeLogByID($id)
    {
        $builder = $this->db->table('employee_logs');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function getEmployeeLogByEmployeeID($employeeID)
    {
        $sql = "
            SELECT * FROM employee_logs 
            WHERE employee_id = $employeeID AND created_at BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()
            ORDER BY created_at DESC
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function insertEmployeeLog($data)
    {
        $builder = $this->db->table('employee_logs');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateEmployeeLogByID($id, $data)
    {
        $builder = $this->db->table('employee_logs');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteEmployeeLogByID($id)
    {
        $builder = $this->db->table('employee_logs');

        return;
    }
    public function getEmployeeLogdata()
    {
        $sql = "
            SELECT employee_logs.detail,employee_logs.created_at FROM employee_logs 
            JOIN employees ON employee_logs.employee_id = employees.id
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }
    public function getEmployeeLogByEmployeeIDAll($employeeID)
    {

        $sql = "
                SELECT * FROM employee_logs 
                WHERE employee_id = $employeeID
                ORDER BY created_at DESC
            ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getEmployeeLogLasted()
    {
        $sql = "
            SELECT employee_logs.*,employees.thumbnail FROM employee_logs
            JOIN employees ON employee_logs.employee_id = employees.id
            WHERE employee_logs.created_at 
            BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()
            ORDER BY employee_logs.created_at DESC
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getEmployeeLogTodayAllday()
    {
        $sql = "
            SELECT employee_logs.*,employees.thumbnail FROM employee_logs 
            JOIN employees ON employee_logs.employee_id = employees.id
            WHERE DATE(employee_logs.created_at) = CURDATE()
            ORDER BY employee_logs.created_at DESC
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getEmployeeLogToday()
    {
        $sql = "
            SELECT employee_logs.* FROM employee_logs 
            JOIN employees ON employee_logs.employee_id = employees.id
            WHERE DATE(employee_logs.created_at) = CURDATE()
            ORDER BY employee_logs.created_at DESC LIMIT 4
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    /*
     * Fetch admin_deposit_transfers data from the database
     * @param $_POST filter data based on the posted parameters
     */
    public function getRows($postData)
    {
        $builder = $this->_get_datatables_query($postData);
        if ($postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start']);
        }

        return $builder->get()->getResult();
    }

    /*
     * Count all records
     */
    public function countAll($postData = null)
    {
        $builder = $this->db->table('employee_logs');
        if ($postData != null) {
            if (array_key_exists('employeeID', $postData['data'])) {
                $builder->where('employee_logs.employee_id =', hashidsDecrypt($postData['data']['employeeID']));
            }
        }

        return  $builder->countAllResults();
    }

    /*
     * Count records based on the filter params
     * @param $_POST filter data based on the posted parameters
     */
    public function countFiltered($postData = null)
    {
        $builder = $this->_get_datatables_query($postData);
        if ($postData != null) {
            if (array_key_exists('employeeID', $postData['data'])) {
                $builder->where('employee_logs.employee_id =', hashidsDecrypt($postData['data']['employeeID']));
            }
        }

        return $builder->countAllResults();
    }

    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    private function _get_datatables_query($postData = null)
    {
        $builder = $this->db->table('employee_logs');
        if ($postData != null) {
            if (array_key_exists('employeeID', $postData['data'])) {
                $builder->where('employee_logs.employee_id =', hashidsDecrypt($postData['data']['employeeID']));
            }
        }

        if (isset($postData['data']['date'])) {

            if (is_array($postData['data']['date'])) {
                $dateStart = $postData['data']['date'][0];
                $dateEnd = $postData['data']['date'][1];

                $builder->where('employee_logs.created_at >=', $dateStart);
                $builder->where('employee_logs.created_at <=', $dateEnd);
            }
        }

        $i = 0;
        // loop searchable columns
        foreach ($this->column_search as $item) {

            // if datatable send POST for search
            if ($postData['search']['value']) {
                // first loop
                if ($i === 0) {
                    // open bracket
                    $builder->groupStart();
                    $builder->like($item, $postData['search']['value']);
                } else {
                    $builder->orLike($item, $postData['search']['value']);
                }

                // last loop
                if (count($this->column_search) - 1 == $i) {
                    $builder->like($item, $postData['search']['value']);
                    // close bracket
                    $builder->groupEnd();
                }
            }
            $i++;
        }

        //        if (isset($postData['order'])) {
        //            $builder->orderBy($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        //        } else if (isset($this->order)) {
        //            $order = $this->order;
        //            $builder->orderBy(key($order), $order[key($order)]);
        //        }

        if (isset($postData['order'])) {
            $builder->orderBy($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }

        return $builder;
    }


    public function getEmployeeLog7lasted()
    {
        $sql = "
            SELECT employee_logs.*, employees.img AS employee_img 
            FROM employee_logs 
            JOIN employees ON employee_logs.employee_id = employees.id
            WHERE employee_logs.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()
            ORDER BY employee_logs.created_at DESC
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }
}
