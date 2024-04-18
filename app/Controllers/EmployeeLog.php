<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\EmployeeNamesModel;

class EmployeeLog extends BaseController
{
    public function employeeLog($id = null)
    {
        $data['url'] = service('uri')->getSegment(3);
        $EmployeeNamesModel = new EmployeeNamesModel();
        
        $data['employee'] = $EmployeeNamesModel->getEmployeeByID($id);

        $EmployeeLogModel = new \App\Models\EmployeeLogModel();
        $data['employee_log'] = $EmployeeLogModel->getEmployeeLogAll();
        $data['employee_log_otday'] = $EmployeeLogModel->getEmployeeLogTodayAllday();
        $data['js_critical'] = '<script src="' . base_url('/assets/app/js/employee/index.js') . '"></script>';

        helper('url');
        $data['content'] = 'employee/employeeLog';
        $data['title'] = 'ประวัติการใช้งาน';

        return view('app', $data);
    }

    //getdatatable Log Employeelog
    public function ajaxDataTables()
    {
        $EmployeeLogDataTableModel = new \App\Models\EmployeeLogDataTableModel();
        $param['id'] = $_POST['id'];
        $param['search_value'] = $_REQUEST['search']['value'];
        $param['draw'] = $_REQUEST['draw'];
        $param['start'] = $_REQUEST['start'];
        $param['length'] = $_REQUEST['length'];

        if (!empty($param['search_value'])) {
            // count all data
            $total_count = $EmployeeLogDataTableModel->getEmployeeLogByEmployeeIDSearchcount($param);
            $data = $EmployeeLogDataTableModel->getEmployeeLogByEmployeeIDSearch($param);
        } else {
            // count all data
            $total_count = $EmployeeLogDataTableModel->getEmployeeLogByEmployeeIDcount($param);
            // get per page data
            $data = $EmployeeLogDataTableModel->getEmployeeLogByEmployeeIDAll($param);
        }

        $json_data = array(
            "draw" => intval($param['draw']),
            "recordsTotal" => count($total_count),
            "recordsFiltered" => count($total_count),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }
    //getdatatable Log Footer
    public function ajaxDataTablesInFooter()
    {
        $EmployeeLogDataTableModel = new \App\Models\EmployeeLogDataTableModel();
        $param['search_value'] = $_REQUEST['search']['value'];
        $param['draw'] = $_REQUEST['draw'];
        $param['start'] = $_REQUEST['start'];
        $param['length'] = $_REQUEST['length'];

        if (!empty($param['search_value'])) {
            // count all data
            $total_count = $EmployeeLogDataTableModel->getEmployeeLogByEmployeeIDSearchcountInFooter($param);

            $data = $EmployeeLogDataTableModel->getEmployeeLogByEmployeeIDSearchInFooter($param);
        } else {
            // count all data
            $total_count = $EmployeeLogDataTableModel->getEmployeeLogByEmployeeIDCountInFooter();

            // get per page data
            $data = $EmployeeLogDataTableModel->getEmployeeLogByEmployeeIDAllInFooter($param);
        }

        $json_data = array(
            "draw" => intval($param['draw']),
            "recordsTotal" => count($total_count),
            "recordsFiltered" => count($total_count),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }
}
