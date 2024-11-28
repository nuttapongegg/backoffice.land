<?php

namespace App\Controllers;

date_default_timezone_set('Asia/Jakarta');

use App\Controllers\BaseController;
use App\Models\RebuildModel;
use Aws\S3\S3Client;

class Loan extends BaseController
{
    public function __construct()
    {
        /*
        | -------------------------------------------------------------------------
        | SET ENVIRONMENT
        | -------------------------------------------------------------------------
        */

        /*
        | -------------------------------------------------------------------------
        | SET UTILITIES
        | -------------------------------------------------------------------------
        */

        // Model
        $this->CustomerModel = new \App\Models\CustomerModel();
        $this->EmployeeModel = new \App\Models\EmployeeModel();
        $this->EmployeeLogModel = new \App\Models\EmployeeLogModel();
        $this->LoanModel = new \App\Models\LoanModel();
        $this->SettingLandModel = new  \App\Models\SettingLandModel();

        $this->s3_bucket = getenv('S3_BUCKET');
        $this->s3_secret_key = getenv('SECRET_KEY');
        $this->s3_key = getenv('KEY');
        $this->s3_endpoint = getenv('ENDPOINT');
        $this->s3_region = getenv('REGION');
        $this->s3_cdn_img = getenv('CDN_IMG');

        function reArrayFiles($file)
        {
            $file_ary = array();
            $file_count = count($file['name']);
            $file_key = array_keys($file);

            for ($i = 0; $i < $file_count; $i++) {
                foreach ($file_key as $val) {
                    $file_ary[$i][$val] = $file[$val][$i];
                }
            }
            return $file_ary;
        }

        function generateRandomString($length = 7)
        {
            $characters = '0123456789';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
    }

    public function list()
    {

        $data['employee_logs'] = $this->EmployeeLogModel->getEmployeeLogToday();

        $data['content'] = 'loan/list';
        $data['title'] = 'เปิดสินเชื่อ';
        $data['css_critical'] = '';
        $data['js_critical'] = ' 
            <script src="' . base_url('/assets/plugins/notify/js/notifIt.js') . '"></script>
            <script src="' . base_url('/assets/plugins/jquery.maskedinput/jquery.maskedinput.js') . '"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="' . base_url('/assets/plugins/jquery-steps/jquery.steps.min.js') . '"></script>
            <script src="' . base_url('/assets/plugins/parsleyjs/parsley.min.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/jquery.ui.widget.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/jquery.fileupload.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/jquery.iframe-transport.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/jquery.fancy-fileupload.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/fancy-uploader.js') . '"></script>
            <script src="' . base_url('/assets/app/js/loan/loan_car.js?v=' . time()) . '"></script> 
            <script src="' . base_url('/assets/app/js/loan/loan_history.js?v=' . time()) . '"></script> 
        ';

        $data['employee'] = $this->EmployeeModel->getEmployeeByID(session()->get('employeeID'));
        $data['land_accounts'] = $this->SettingLandModel->getSettingLandAll();

        echo view('/app', $data);
    }

    // Get Data Loan กำลังจ่าย
    public function FetchAllLoanOn()
    {
        $data_loanOn = $this->LoanModel->getAllDataLoanOn();

        return $this->response->setJSON([
            'status' => 200,
            'error' => false,
            'message' => json_encode($data_loanOn)
        ]);
    }

    // Get Data Loan ทั้งหมด
    public function FetchAllLoan()
    {
    }

    public function GetEmp()
    {
        $emp_datas = $this->LoanModel->getEmps();

        return $this->response->setJSON([
            'status' => 200,
            'error' => false,
            'message' => $emp_datas
        ]);
    }

    public function GetCarName()
    {
        $car_name_datas = $this->LoanModel->getStockName();

        return $this->response->setJSON([
            'status' => 200,
            'error' => false,
            'message' => $car_name_datas
        ]);
    }


    public function insertDataLoan()
    {
        $buffer_datetime = date("Y-m-d H:i:s");
        $customer_name = $this->request->getPost('customer_name');
        $employee_name = $this->request->getPost('employee_name');
        $loan_address = $this->request->getPost('loan_address');
        $loan_number = $this->request->getPost('loan_number');
        $loan_area = $this->request->getPost('loan_area');
        $account_id = $this->request->getPost('account_id');
        $date_to_loan = $this->request->getPost('date_to_loan');
        $date_to_loan_pay_date = $this->request->getPost('date_to_loan_pay_date');
        $loan_without_vat = $this->request->getPost('loan_without_vat');
        $payment_year_counter = $this->request->getPost('payment_year_counter');
        $pricePerMonth = $this->request->getPost('pricePerMonth');
        $total_loan = $this->request->getPost('total_loan');
        $payment_interest = $this->request->getPost('payment_interest');
        $charges_process = $this->request->getPost('charges_process');
        $charges_transfer = $this->request->getPost('charges_transfer');
        $charges_etc = $this->request->getPost('charges_etc');
        $remark = $this->request->getPost('remark');
        $really_pay_loan = $this->request->getPost('really_pay_loan');
        $total_loan_interest = $this->request->getPost('total_loan_interest');
        $loan_type = $this->request->getVar('loan_type');

        $loan_running_code = '';
        $buffer_loan_code = 0;
        $loan_running_codes = $this->LoanModel->getCodeLoank();

        $land_account_name = $this->SettingLandModel->getSettingLandByID($account_id);

        foreach ($loan_running_codes as $loan_running_code) {
            $buffer_loan_code = (int)$loan_running_code->substr_loan_code;
        }

        $sum_loan_code = $buffer_loan_code + 1;
        $sprintf_loan_code = sprintf("%06d", $sum_loan_code);
        $loan_running_code = "LOA" . $sprintf_loan_code;

        $loan_list = [
            'loan_code'  => $loan_running_code,
            'loan_customer' => $customer_name,
            'loan_address' => $loan_address,
            'loan_number'   => $loan_number,
            'loan_area' => $loan_area,
            'loan_employee' => $employee_name,
            'loan_date_promise' => $date_to_loan,
            'loan_installment_date' => $date_to_loan_pay_date,
            'loan_summary_no_vat' => $loan_without_vat,
            'loan_sum_interest' => $total_loan_interest,
            'loan_payment_year_counter' => $payment_year_counter,
            'loan_payment_interest' => $payment_interest,
            'loan_summary_all' => $total_loan,
            'loan_payment_month' => $pricePerMonth,
            'loan_payment_process' => $charges_process,
            'loan_type' => $loan_type,
            'loan_tranfer' => $charges_transfer,
            'loan_payment_other' => $charges_etc,
            'loan_status'  => 'ON_STATE',
            'loan_remnark' => $remark,
            'loan_really_pay' => $really_pay_loan,
            'land_account_id' => $account_id,
            'land_account_name' => $land_account_name->land_account_name,
            'created_at'  => $buffer_datetime
        ];

        $loan_running = [
            'loan_running_code' =>  $loan_running_code
        ];


        $create_loan = $this->LoanModel->insertLoanList($loan_list, $loan_running);

        $count_installment = 0;
        $installment = 0;
        $installment =  $payment_year_counter * 12;
        $loan_without_vat_for_balance = 0;
        $date_pay_loan = date("Y-m-d");

        for ($index_installment = 1; $index_installment <= $installment; $index_installment++) {

            // $pricePerMonth ค่า่งวดแต่ละเดือน
            // $payment_interest  ดอกเบี้ยต่อปี
            // $loan_without_vat ยอดรวม
            // $pay_count = ($loanPrice + $dok_total) / $numYear;

            if ($index_installment == 1) {
                $loan_without_vat_for_balance = $total_loan_interest;
            } else {
                $loan_without_vat_for_balance = $loan_without_vat_for_balance -  $pricePerMonth;
            }

            $loan_balance = $loan_without_vat_for_balance;

            $load_payment_data = [
                'loan_code' => $loan_running_code,
                'loan_payment_amount' => $pricePerMonth,
                'loan_payment_installment' =>  $index_installment,
                'loan_payment_date_fix' =>  $date_to_loan_pay_date,
                'loan_balance' => $loan_balance,
                // 'loan_payment_date' => $date_pay_loan,
                'created_at' => $buffer_datetime
            ];

            $create_loan = $this->LoanModel->insertpayment($load_payment_data);

            $count_installment++;
        }

        if ($land_account_name != '') {
            $land_account_cash_receipt = $land_account_name->land_account_cash - $loan_without_vat;

            $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                'land_account_cash' => $land_account_cash_receipt,
                // 'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $detail = 'เปิดสินเชื่อ' . '(' . $loan_running_code . ')';
            $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $account_id,
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $loan_without_vat,
                'setting_land_report_note' => $remark,
                'setting_land_report_account_balance' => $land_account_cash_receipt,
                'employee_id' => session()->get('employeeID'),
                'employee_name' => session()->get('employee_fullname')
            ]);
        }

        if ($create_loan && ($count_installment == $installment)) {

            return $this->response->setJSON([
                'status' => 200,
                'error' => false,
                'message' => 'เพิ่มรายการสำเร็จ'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 200,
                'error' => true,
                'message' => 'เพิ่มรายการไม่สำเร็จ'
            ]);
        }
    }

    public function detail($loanCode = null)
    {
        $data['employee_logs'] = $this->EmployeeLogModel->getEmployeeLogToday();

        $data['content'] = 'loan/loandetail';
        $data['title'] = 'รายละเอียดสินเชื่อ';
        $data['css_critical'] = '';
        $data['js_critical'] = ' 
        <script src="' . base_url('/assets/plugins/notify/js/notifIt.js') . '"></script>
        <script src="' . base_url('/assets/plugins/jquery.maskedinput/jquery.maskedinput.js') . '"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="' . base_url('/assets/plugins/jquery-steps/jquery.steps.min.js') . '"></script>
        <script src="' . base_url('/assets/plugins/parsleyjs/parsley.min.js') . '"></script>
        <script src="' . base_url('/assets/plugins/fancyuploder/jquery.ui.widget.js') . '"></script>
        <script src="' . base_url('/assets/plugins/fancyuploder/jquery.fileupload.js') . '"></script>
        <script src="' . base_url('/assets/plugins/fancyuploder/jquery.iframe-transport.js') . '"></script>
        <script src="' . base_url('/assets/plugins/fancyuploder/jquery.fancy-fileupload.js') . '"></script>
        <script src="' . base_url('/assets/plugins/fancyuploder/fancy-uploader.js') . '"></script>
        <script src="' . base_url('/assets/plugins/chart.js/Chart.bundle.min.js') . '"></script>
        <script src="' . base_url('/assets/js/image-uploader.min.js') . '"></script>
        <script src="' . base_url('/assets/app/js/loan/loan_detail.js?v=' . time()) . '"></script> 
        ';

        $data['employee'] = $this->EmployeeModel->getEmployeeByID(session()->get('employeeID'));
        $data['loanData'] = $this->LoanModel->getAllDataLoanByCode($loanCode);
        $data['employees'] = $this->EmployeeModel->getEmployeeAllNoSpAdmin();
        $data['land_accounts'] = $this->SettingLandModel->getSettingLandAll();

        echo view('/app', $data);
    }

    public function detailForm($loanCode = null)
    {
        $loan = $this->LoanModel->getAllDataLoanByCode($loanCode);

        return $this->response->setJSON([
            'status' => 200,
            'error' => false,
            'message' => $loan
        ]);
    }

    public function updateLoan()
    {

        $buffer_datetime = date("Y-m-d H:i:s");
        $loan_code = $this->request->getPost('loan_code');
        $customer_name = $this->request->getPost('customer_name');
        $loan_address = $this->request->getPost('loan_address');
        $loan_number = $this->request->getPost('loan_number');
        $employee_name = $this->request->getPost('employee_name');
        $loan_area = $this->request->getPost('loan_area');
        $date_to_loan = $this->request->getPost('date_to_loan');
        $date_to_loan_pay_date = $this->request->getPost('date_to_loan_pay_date');
        $loan_without_vat = $this->request->getPost('loan_without_vat');
        $payment_year_counter = $this->request->getPost('payment_year_counter');
        $payment_interest = $this->request->getPost('payment_interest');
        $pricePerMonth = $this->request->getPost('pricePerMonth');
        $total_loan = $this->request->getPost('total_loan');
        $charges_process = $this->request->getPost('charges_process');
        $charges_transfer = $this->request->getPost('charges_transfer');
        $charges_etc = $this->request->getPost('charges_etc');
        $remark = $this->request->getPost('remark');
        $really_pay_loan = $this->request->getPost('really_pay_loan');


        $loan_list = [
            'loan_customer' => $customer_name,
            'loan_address' => $loan_address,
            'loan_number'   => $loan_number,
            'loan_area' => $loan_area,
            'loan_employee' => $employee_name,
            'loan_date_promise' => $date_to_loan,
            'loan_installment_date' => $date_to_loan_pay_date,
            'loan_summary_no_vat' => $loan_without_vat,
            'loan_payment_year_counter' => $payment_year_counter,
            'loan_payment_interest' => $payment_interest,
            'loan_summary_all' => $total_loan,
            'loan_payment_month' => $pricePerMonth,
            'loan_payment_process' => $charges_process,
            'loan_tranfer' => $charges_transfer,
            'loan_payment_other' => $charges_etc,
            'loan_status'  => 'ON_STATE',
            'loan_remnark' => $remark,
            'loan_really_pay' => $really_pay_loan,
            'updated_at'  => $buffer_datetime
        ];

        $update_loan = $this->LoanModel->updateLoan($loan_code, $loan_list);

        if($date_to_loan_pay_date){
            $loan_payment = [
                'loan_payment_date_fix' => $date_to_loan_pay_date,
                'updated_at'  => $buffer_datetime
            ];

            $update_loan_payment = $this->LoanModel->updateLoanPaymentDateFix($loan_code, $loan_payment);

        }

        if ($update_loan) {

            return $this->response->setJSON([
                'status' => 200,
                'error' => false,
                'message' => 'แก้ไขรายการสำเร็จ'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 200,
                'error' => true,
                'message' => 'แก้ไขรายการไม่สำเร็จ'
            ]);
        }
    }

    public function offLoan($code = null)
    {
        $buffer_datetime = date("Y-m-d H:i:s");
        $loan_list = [
            'loan_status'  => 'CANCEL_STATE',
            'updated_at'  => $buffer_datetime
        ];

        $update_loan = $this->LoanModel->updateLoan($code, $loan_list);
        $dataLoanPaymentByCode = $this->LoanModel->getLoanPaymentByCode($code);
        $dataLoanByCode = $this->LoanModel->getAllDataLoanByCode($code);

        if ($dataLoanPaymentByCode != '') {
            foreach ($dataLoanPaymentByCode as $LoanPayment) {
                $land_accounts =  $this->SettingLandModel->getSettingLandAll();
                $land_account_id = $LoanPayment->land_account_id;
                foreach ($land_accounts as $land_account) {
                    if ($land_account_id == $land_account->id) {

                        $land_account_cash_receipt = $land_account->land_account_cash - $LoanPayment->loan_payment_amount;

                        $this->SettingLandModel->updateSettingLandByID($land_account->id, [
                            'land_account_cash' => $land_account_cash_receipt,
                            // 'updated_at' => date('Y-m-d H:i:s'),
                        ]);


                        $detail = 'ลบสินเชื่อ ' . $code . '(งวดที่' . $LoanPayment->loan_payment_installment . ')';
                        $this->SettingLandModel->insertSettingLandReport([
                            'setting_land_id' => $land_account->id,
                            'setting_land_report_detail' => $detail,
                            'setting_land_report_money' => $LoanPayment->loan_payment_amount,
                            'setting_land_report_note' => '',
                            'setting_land_report_account_balance' => $land_account_cash_receipt,
                            'employee_id' => session()->get('employeeID'),
                            'employee_name' => session()->get('employee_fullname')
                        ]);
                    }
                }
                sleep(1);
            }
        }

        if ($dataLoanByCode != '') {
            $land_account_name = $this->SettingLandModel->getSettingLandByID($dataLoanByCode->land_account_id);
            $land_account_cash_receipt = $land_account_name->land_account_cash + $dataLoanByCode->loan_summary_no_vat;

            $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                'land_account_cash' => $land_account_cash_receipt,
                // 'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $detail = 'ลบสินเชื่อ' . '(' . $code . ')';
            $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $land_account_name->id,
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $dataLoanByCode->loan_summary_no_vat,
                'setting_land_report_note' => '',
                'setting_land_report_account_balance' => $land_account_cash_receipt,
                'employee_id' => session()->get('employeeID'),
                'employee_name' => session()->get('employee_fullname')
            ]);
        }

        if ($update_loan) {

            return $this->response->setJSON([
                'status' => 200,
                'error' => false,
                'message' => 'แก้ไขรายการสำเร็จ'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 200,
                'error' => true,
                'message' => 'แก้ไขรายการไม่สำเร็จ'
            ]);
        }
    }

    public function callInstallMent($id)
    {
        $num = $this->LoanModel->callInstallMent($id);

        return $this->response->setJSON([
            'status' => 200,
            'error' => false,
            'message' => $num
        ]);
    }

    public function insertDataLoanPayment()
    {
        $buffer_datetime = date("Y-m-d H:i:s");
        $payment_id = $this->request->getPost('payment_id');
        $codeloan_hidden = $this->request->getPost('codeloan_hidden');
        $payment_name = $this->request->getPost('payment_name');
        $employee_name = $this->request->getPost('payment_employee_name');
        $date_to_payment = $this->request->getPost('date_to_payment');
        $payment_now = $this->request->getPost('payment_now');
        $payment_type = $this->request->getPost('payment_type');
        $installment_count = $this->request->getPost('installment_count');
        $pay_sum = $this->request->getPost('pay_sum');
        $customer_payment_type = $this->request->getPost('customer_payment_type');
        $file_payment = $this->request->getFile('file_payment');
        $total_loan_payment = $this->request->getPost('total_loan_payment');
        $account_id = $this->request->getPost('account_name');
        $close_loan_payment = $this->request->getPost('close_loan_payment');

        $land_account_name = $this->SettingLandModel->getSettingLandByID($account_id);
        $fileName_img = $file_payment->getFilename();
        if ($fileName_img !== "") {
            $fileName_img = $codeloan_hidden . "_" . $file_payment->getRandomName();
            $file_payment->move('uploads/loan_payment_img', $fileName_img);

            // if ($this->request->getPost('old_img_car') != '') {
            //     unlink('uploads/loan_payment_img/' . $this->request->getPost('old_img_car'));
            // }

            $file_Path = 'uploads/loan_payment_img/' . $fileName_img;

            try {

                $s3Client = new S3Client([
                    'version' => 'latest',
                    'region'  => $this->s3_region,
                    'endpoint' => $this->s3_endpoint,
                    'use_path_style_endpoint' => false,
                    'credentials' => [
                        'key'    => $this->s3_key,
                        'secret' => $this->s3_secret_key
                    ]
                ]);


                $result = $s3Client->putObject([
                    'Bucket' => $this->s3_bucket,
                    'Key'    => 'uploads/loan_payment_img/' . $fileName_img,
                    'Body'   => fopen($file_Path, 'r'),
                    'ACL'    => 'public-read', // make file 'public'
                ]);


                if ($result['ObjectURL'] != "") {
                    unlink('uploads/loan_payment_img/' . $fileName_img);
                }
            } catch (Aws\S3\Exception\S3Exception $e) {
                echo $e->getMessage();
            }
        }

        $year = $this->LoanModel->getYearCount($codeloan_hidden);
        $year_loan =  $year->loan_payment_year_counter;
        $year_loan =  $year_loan * 12;

        $create_payment = false;

        if (($year_loan == $installment_count) || ($payment_type == 'Close')) {

            $data_loan = [
                'loan_payment_sum_installment' => $pay_sum,
                'loan_status' => 'CLOSE_STATE',
                'updated_at' => $buffer_datetime
            ];

            $data_payment = [
                // 'loan_code' => $codeloan_hidden,
                // 'loan_payment_amount'  => $payment_now,
                'loan_employee' => $employee_name,
                'loan_payment_type' => 'Close',
                'loan_payment_pay_type' => $customer_payment_type,
                // 'loan_payment_installment' =>  $installment_count,
                'loan_payment_customer' => $payment_name,
                'loan_payment_src' => $fileName_img,
                'land_account_id' => $account_id,
                'land_account_name' => $land_account_name->land_account_name,
                'loan_payment_date' => $date_to_payment,
                'updated_at' => $buffer_datetime
            ];

            $create_payment = $this->LoanModel->updateLoanPaymentClose($data_payment, $codeloan_hidden);

            $Loan_Staus = 'ชำระทั้งหมด';
        }elseif(($payment_type == 'CloseLoan')){

            $loan_payment = [
                // 'loan_code' => $codeloan_hidden,
                'loan_payment_amount'  => $payment_now,
                'loan_employee' => $employee_name,
                'loan_payment_type' => 'Installment',
                'loan_payment_pay_type' => $customer_payment_type,
                // 'loan_payment_installment' =>  $installment_count,
                'loan_payment_customer' => $payment_name,
                'loan_payment_src' => $fileName_img,
                'land_account_id' => $account_id,
                'land_account_name' => $land_account_name->land_account_name,
                'loan_payment_date' => $date_to_payment,
                'updated_at' => $buffer_datetime
            ];

            $close_payment = $this->LoanModel->updateLoanPayment($loan_payment, $payment_id);

            $data_loan = [
                'loan_close_payment' => $close_loan_payment,
                'loan_status' => 'CLOSE_STATE',
                'updated_at' => $buffer_datetime
            ];

            $data_close_payment = [
                'loan_payment_amount'  => 0,
                'loan_employee' => $employee_name,
                'loan_payment_pay_type' => $customer_payment_type,
                'loan_payment_customer' => $payment_name,
                'loan_payment_src' => $fileName_img,
                'loan_payment_date' => $date_to_payment,
                'land_account_id' => $account_id,
                'land_account_name' => $land_account_name->land_account_name,
                'updated_at' => $buffer_datetime
            ];

            $create_close_payment = $this->LoanModel->updateLoanClosePayment($data_close_payment, $codeloan_hidden);

            if($create_close_payment){
                $data_payment = [
                    'loan_payment_type' => 'Close',
                    'loan_balance'  => 0
                ];
    
                $create_payment = $this->LoanModel->updateLoanPaymentClose($data_payment, $codeloan_hidden);
            }

            $Loan_Staus = 'ชำระปิดสินเชื่อ';
        }
         else {

            $data_loan = [
                'loan_payment_sum_installment' => $pay_sum
            ];

            $data_payment = [
                // 'loan_code' => $codeloan_hidden,
                'loan_payment_amount'  => $payment_now,
                'loan_employee' => $employee_name,
                'loan_payment_type' => $payment_type,
                'loan_payment_pay_type' => $customer_payment_type,
                // 'loan_payment_installment' =>  $installment_count,
                'loan_payment_customer' => $payment_name,
                'loan_payment_src' => $fileName_img,
                'land_account_id' => $account_id,
                'land_account_name' => $land_account_name->land_account_name,
                'loan_payment_date' => $date_to_payment,
                'updated_at' => $buffer_datetime
            ];

            $create_payment = $this->LoanModel->updateLoanPayment($data_payment, $payment_id);

            $Loan_Staus = 'งวดที่ ' . $installment_count;
        }

        if ($land_account_name != '') {
            $land_account_cash_receipt = $land_account_name->land_account_cash + $payment_now;

            $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                'land_account_cash' => $land_account_cash_receipt,
                // 'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $detail = 'ชำระสินเชื่อ ' . $codeloan_hidden . '(' . $Loan_Staus . ')';
            $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $account_id,
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $payment_now,
                'setting_land_report_note' => '',
                'setting_land_report_account_balance' => $land_account_cash_receipt,
                'employee_id' => session()->get('employeeID'),
                'employee_name' => session()->get('employee_fullname')
            ]);
        }

        $update_loan = $this->LoanModel->updateLoanSumPayment($data_loan, $codeloan_hidden);

        if ($create_payment && $update_loan) {

            return $this->response->setJSON([
                'status' => 200,
                'error' => false,
                'message' => 'เพิ่มรายการสำเร็จ'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 200,
                'error' => true,
                'message' => 'เพิ่มรายการไม่สำเร็จ'
            ]);
        }
    }

    public function getListPayment($code)
    {
        $data_paymentList = $this->LoanModel->getListPayment($code);

        return $this->response->setJSON([
            'status' => 200,
            'error' => false,
            'message' => json_encode($data_paymentList)
        ]);
    }

    public function loanHistory()
    {
        $datas_loan = $this->LoanModel->_getAllDataLoanHistory($_POST);

        $datas_count = $this->LoanModel->countAllDataLoanHistory();

        $filter = $this->LoanModel->getAllDataLoanHistoryFilter();

        return $this->response->setJSON([
            'draw' => $_POST['draw'],
            'recordsTotal' => $datas_count,
            'recordsFiltered' => count($filter),
            "data" => $datas_loan,
        ]);
    }

    public function list_history()
    {
        $data['employee_logs'] = $this->EmployeeLogModel->getEmployeeLogToday();

        $data['content'] = 'loan/list_history';
        $data['title'] = 'เปิดสินเชื่อ';
        $data['css_critical'] = '';
        $data['js_critical'] = ' 
            <script src="' . base_url('/assets/plugins/notify/js/notifIt.js') . '"></script>
            <script src="' . base_url('/assets/plugins/jquery.maskedinput/jquery.maskedinput.js') . '"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="' . base_url('/assets/plugins/jquery-steps/jquery.steps.min.js') . '"></script>
            <script src="' . base_url('/assets/plugins/parsleyjs/parsley.min.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/jquery.ui.widget.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/jquery.fileupload.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/jquery.iframe-transport.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/jquery.fancy-fileupload.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/fancy-uploader.js') . '"></script>
            <script src="' . base_url('/assets/app/js/loan/loan_history.js') . '"></script>
        ';

        $data['employee'] = $this->EmployeeModel->getEmployeeByID(session()->get('employeeID'));

        echo view('/app', $data);
    }

    public function ajaxSummarizeLoan()
    {
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            $RealInvestmentModel = new \App\Models\RealInvestmentModel();
            $real_investment = $RealInvestmentModel->getRealInvestmentAll();

            $SettingLandModel = new \App\Models\SettingLandModel();
            $land_accounts = $SettingLandModel->getSettingLandAll();

            $datas = $this->LoanModel->getAllDataLoan();

            $loan_summary_no_vat = 0;
            $loan_payment_sum_installment = 0;
            $loan_summary_all = 0;
            $summary_all = 0;
            $loan_payment_month = 0;
            $principle = 0;
            $investment = 0;
            $return_funds = 0;

            $sum_installment = 0;
            $summary_no_vat_ON_STATE = 0;
            $summary_no_vat_CLOSE_STATE = 0;

            $sum_land_account = 0;
            foreach ($land_accounts as $land_account) {
                $sum_land_account = $sum_land_account + $land_account->land_account_cash;
            }

            foreach ($datas as $data) {
                if ($data->loan_summary_no_vat != '') {
                    $loan_summary_no_vat = $loan_summary_no_vat + $data->loan_summary_no_vat;
                }

                if ($data->loan_payment_sum_installment != '') {
                    $loan_payment_sum_installment = $loan_payment_sum_installment + $data->loan_payment_sum_installment;
                }

                if ($data->loan_summary_all != '') {
                    $loan_summary_all = $loan_summary_all + $data->loan_summary_all;
                }

                if ($data->loan_status == 'ON_STATE') {
                    $loan_payment_month = $loan_payment_month + $data->loan_payment_month;
                    $sum_installment = $sum_installment + $data->loan_payment_sum_installment;
                    $summary_no_vat_ON_STATE = $summary_no_vat_ON_STATE + $data->loan_summary_no_vat;
                }

                if ($data->loan_status == 'CLOSE_STATE') {
                    $summary_no_vat_CLOSE_STATE = $summary_no_vat_CLOSE_STATE + $data->loan_summary_no_vat;
                }
            }

            $summary_all = $loan_summary_all - $loan_payment_sum_installment;
            if ($summary_all != 0) {
                $return_funds = ($loan_payment_month / $summary_all) * 100;
            }

            $html_SummarizeLoan =
                '<div class="row my-3 mx-1">
                    <div class="col" style="flex-grow: 1;">
                        <div class="card text-center">
                            <div class="card-body">
                                <div>วงเงินกู้รวม</div>
                                <div class="font-weight-semibold mb-1 tx-success">' . number_format($loan_summary_no_vat, 2) . '</div>
                            </div>
                        </div>
                    </div>
                    <div class="col" style="flex-grow: 1;">
                        <div class="card text-center">
                            <div class="card-body">
                                <div>ชำระแล้ว</div>
                                <div class="font-weight-semibold mb-1 tx-secondary">' . number_format($loan_payment_sum_installment, 2) . '</div>
                            </div>
                        </div>
                    </div>
                    <div class="col" style="flex-grow: 1;">
                        <div class="card text-center">
                            <div class="card-body">
                                <div>ค่างวดต่อเดือน</div>
                                <div class="font-weight-semibold mb-1 tx-warning">' . number_format($loan_payment_month, 2) . '</div>
                            </div>
                        </div>
                    </div>
                </div>
            ';

            //     <div class="col" style="flex-grow: 1;">
            //     <div class="card text-center">
            //         <div class="card-body">
            //             <div>เงินต้นคงเหลือ</div>
            //             <div class="font-weight-semibold mb-1 tx-primary">' . number_format($summary_all, 2) . '</div>
            //         </div>
            //     </div>
            // </div>
            // <div class="col" style="flex-grow: 1;">
            //     <div class="card text-center">
            //         <div class="card-body">
            //             <div>Y/เงินต้น</div>
            //             <div class="font-weight-semibold mb-1 tx-purple">' . number_format($principle, 2) . '%</div>
            //         </div>
            //     </div>
            // </div>
            // <div class="col" style="flex-grow: 1;">
            //     <div class="card text-center">
            //         <div class="card-body">
            //             <div>Y/เงินลงุทน</div>
            //             <div class="font-weight-semibold mb-1 tx-pink">' . number_format($investment, 2) . '%</div>
            //         </div>
            //     </div>
            // </div>
            // <div class="col" style="flex-grow: 1;">
            //     <div class="card text-center">
            //         <div class="card-body">
            //             <div>คืนเงินต้น/ทุน</div>
            //             <div class="font-weight-semibold mb-1 tx-teal">' . number_format($return_funds, 2) . '%</div>
            //         </div>
            //     </div>
            // </div>

            $html_summarizeLoan =
                '<div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body mt-2 mb-3">
                            <div class="row">
                                <div class="col" style="flex-grow: 1;">
                                    <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                                        <p class="mb-0 font-weight-semibold tx-18">เงินลงทุนจริง</p>
                                        <div class="mt-2">
                                            <span class="mb-0 font-weight-semibold tx-15">' . number_format($real_investment->investment, 2) . '</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col" style="flex-grow: 1;">
                                    <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                                        <p class="mb-0 font-weight-semibold tx-18">ยอดวงเงินกู้รวม</p>
                                        <div class="mt-2">
                                            <span class="mb-0 font-weight-semibold tx-15">' . number_format($summary_no_vat_ON_STATE, 2) . '</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col" style="flex-grow: 1;">
                                    <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                                        <p class="mb-0 font-weight-semibold tx-18">เงินสดบัญชี</p>
                                        <div class="mt-2">
                                            <span class="mb-0 font-weight-semibold tx-15">' . number_format($sum_land_account, 2) . '</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col" style="flex-grow: 1;">
                                    <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                                        <p class="mb-0 font-weight-semibold tx-18">วงเงินที่ปิดบัญชีแล้ว</p>
                                        <div class="mt-2">
                                            <span class="mb-0 font-weight-semibold tx-15">' . number_format($summary_no_vat_CLOSE_STATE, 2) . '</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col" style="flex-grow: 1;">
                                    <div class="tx-center pd-y-7 pd-sm-y-0-f">
                                        <p class="mb-0 font-weight-semibold tx-18">ยอดเก็บเงินรวม</p>
                                        <div class="mt-2">
                                            <span class="mb-0 font-weight-semibold tx-15">' . number_format($loan_payment_sum_installment, 2) . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                ';

            $response['data_summarizeLoan'] = $html_summarizeLoan;

            $response['data_SummarizeLoan'] = $html_SummarizeLoan;

            $status = 200;
            $response['success'] = 1;
            $response['message'] = '';

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    public function report_loan()
    {
        $TargetedModel = new \App\Models\TargetedModel();
        $data['targeteds'] = $TargetedModel->getTargetedAll();

        $data['OverduePayments'] = $this->LoanModel->getOverdueListPayments(date('Y'));
        $data['PaymentMonths'] = $this->LoanModel->getListPaymentMonths(date('Y'));

        $data['content'] = 'loan/report_loan';
        $data['title'] = 'รายงานสินเชื่อ';
        $data['css_critical'] = '';
        $data['js_critical'] = '
            <script src="' . base_url('/assets/js/apexcharts.js') . '"></script>
            <script src="' . base_url('/assets/js/report-loan-index-5.js') . '"></script>
            <script src="' . base_url('/assets/app/js/loan/report_loan.js?v=' . time()) . '"></script> 
        ';

        return view('app', $data);
    }

    public function ajaxTablesReportLoan($data)
    {
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            $ListPayments = $this->LoanModel->getRevenueListPayments($data);
            $OpenLoans = $this->LoanModel->getOpenLoan($data);
            $OverduePayments = $this->LoanModel->getOverdueListPayments($data);
            $PaymentMonths = $this->LoanModel->getListPaymentMonths($data);

            $Month_Jan_Payment = 0;
            $Month_Feb_Payment = 0;
            $Month_Mar_Payment = 0;
            $Month_Apr_Payment = 0;
            $Month_May_Payment = 0;
            $Month_Jun_Payment = 0;
            $Month_Jul_Payment = 0;
            $Month_Aug_Payment = 0;
            $Month_Sep_Payment = 0;
            $Month_Oct_Payment = 0;
            $Month_Nov_Payment = 0;
            $Month_Dec_Payment = 0;

            foreach ($ListPayments as $ListPayment) {
                switch ($ListPayment->payment_month) {
                    case "1":
                        $Month_Jan_Payment = $Month_Jan_Payment + $ListPayment->loan_payment_amount;
                        break;
                    case "2":
                        $Month_Feb_Payment = $Month_Feb_Payment + $ListPayment->loan_payment_amount;
                        break;
                    case "3":
                        $Month_Mar_Payment = $Month_Mar_Payment + $ListPayment->loan_payment_amount;
                        break;
                    case "4":
                        $Month_Apr_Payment = $Month_Apr_Payment + $ListPayment->loan_payment_amount;
                        break;
                    case "5":
                        $Month_May_Payment = $Month_May_Payment + $ListPayment->loan_payment_amount;
                        break;
                    case "6":
                        $Month_Jun_Payment = $Month_Jun_Payment + $ListPayment->loan_payment_amount;
                        break;
                    case "7":
                        $Month_Jul_Payment = $Month_Jul_Payment + $ListPayment->loan_payment_amount;
                        break;
                    case "8":
                        $Month_Aug_Payment = $Month_Aug_Payment + $ListPayment->loan_payment_amount;
                        break;
                    case "9":
                        $Month_Sep_Payment = $Month_Sep_Payment + $ListPayment->loan_payment_amount;
                        break;
                    case "10":
                        $Month_Oct_Payment = $Month_Oct_Payment + $ListPayment->loan_payment_amount;
                        break;
                    case "11":
                        $Month_Nov_Payment = $Month_Nov_Payment + $ListPayment->loan_payment_amount;
                        break;
                    case "12":
                        $Month_Dec_Payment = $Month_Dec_Payment + $ListPayment->loan_payment_amount;
                        break;
                }
            }

            $Month_Jan_Loan = 0;
            $Month_Feb_Loan = 0;
            $Month_Mar_Loan = 0;
            $Month_Apr_Loan = 0;
            $Month_May_Loan = 0;
            $Month_Jun_Loan = 0;
            $Month_Jul_Loan = 0;
            $Month_Aug_Loan = 0;
            $Month_Sep_Loan = 0;
            $Month_Oct_Loan = 0;
            $Month_Nov_Loan = 0;
            $Month_Dec_Loan = 0;

            foreach ($OpenLoans as $OpenLoan) {
                switch ($OpenLoan->loan_month) {
                    case "1":
                        $Month_Jan_Loan = $Month_Jan_Loan + $OpenLoan->loan_summary_no_vat;
                        break;
                    case "2":
                        $Month_Feb_Loan = $Month_Feb_Loan + $OpenLoan->loan_summary_no_vat;
                        break;
                    case "3":
                        $Month_Mar_Loan = $Month_Mar_Loan + $OpenLoan->loan_summary_no_vat;
                        break;
                    case "4":
                        $Month_Apr_Loan = $Month_Apr_Loan + $OpenLoan->loan_summary_no_vat;
                        break;
                    case "5":
                        $Month_May_Loan = $Month_May_Loan + $OpenLoan->loan_summary_no_vat;
                        break;
                    case "6":
                        $Month_Jun_Loan = $Month_Jun_Loan + $OpenLoan->loan_summary_no_vat;
                        break;
                    case "7":
                        $Month_Jul_Loan = $Month_Jul_Loan + $OpenLoan->loan_summary_no_vat;
                        break;
                    case "8":
                        $Month_Aug_Loan = $Month_Aug_Loan + $OpenLoan->loan_summary_no_vat;
                        break;
                    case "9":
                        $Month_Sep_Loan = $Month_Sep_Loan + $OpenLoan->loan_summary_no_vat;
                        break;
                    case "10":
                        $Month_Oct_Loan = $Month_Oct_Loan + $OpenLoan->loan_summary_no_vat;
                        break;
                    case "11":
                        $Month_Nov_Loan = $Month_Nov_Loan + $OpenLoan->loan_summary_no_vat;
                        break;
                    case "12":
                        $Month_Dec_Loan = $Month_Dec_Loan + $OpenLoan->loan_summary_no_vat;
                        break;
                }
            }

            $Month_Jan_Overdue_Payment = 0;
            $Month_Feb_Overdue_Payment = 0;
            $Month_Mar_Overdue_Payment = 0;
            $Month_Apr_Overdue_Payment = 0;
            $Month_May_Overdue_Payment = 0;
            $Month_Jun_Overdue_Payment = 0;
            $Month_Jul_Overdue_Payment = 0;
            $Month_Aug_Overdue_Payment = 0;
            $Month_Sep_Overdue_Payment = 0;
            $Month_Oct_Overdue_Payment = 0;
            $Month_Nov_Overdue_Payment = 0;
            $Month_Dec_Overdue_Payment = 0;

            foreach ($OverduePayments as $OverduePayment) {
                if ($data === date('Y')) {
                    if ($OverduePayment->overdue_payment <= date('m')) {
                        switch ($OverduePayment->overdue_payment) {
                            case "1":
                                $Month_Jan_Overdue_Payment = $Month_Jan_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "2":
                                $Month_Feb_Overdue_Payment = $Month_Feb_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "3":
                                $Month_Mar_Overdue_Payment = $Month_Mar_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "4":
                                $Month_Apr_Overdue_Payment = $Month_Apr_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "5":
                                $Month_May_Overdue_Payment = $Month_May_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "6":
                                $Month_Jun_Overdue_Payment = $Month_Jun_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "7":
                                $Month_Jul_Overdue_Payment = $Month_Jul_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "8":
                                $Month_Aug_Overdue_Payment = $Month_Aug_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "9":
                                $Month_Sep_Overdue_Payment = $Month_Sep_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "10":
                                $Month_Oct_Overdue_Payment = $Month_Oct_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "11":
                                $Month_Nov_Overdue_Payment = $Month_Nov_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "12":
                                $Month_Dec_Overdue_Payment = $Month_Dec_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                        }
                    }
                } elseif ($data < date('Y')) {
                    switch ($OverduePayment->overdue_payment) {
                        case "1":
                            $Month_Jan_Overdue_Payment = $Month_Jan_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "2":
                            $Month_Feb_Overdue_Payment = $Month_Feb_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "3":
                            $Month_Mar_Overdue_Payment = $Month_Mar_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "4":
                            $Month_Apr_Overdue_Payment = $Month_Apr_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "5":
                            $Month_May_Overdue_Payment = $Month_May_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "6":
                            $Month_Jun_Overdue_Payment = $Month_Jun_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "7":
                            $Month_Jul_Overdue_Payment = $Month_Jul_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "8":
                            $Month_Aug_Overdue_Payment = $Month_Aug_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "9":
                            $Month_Sep_Overdue_Payment = $Month_Sep_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "10":
                            $Month_Oct_Overdue_Payment = $Month_Oct_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "11":
                            $Month_Nov_Overdue_Payment = $Month_Nov_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "12":
                            $Month_Dec_Overdue_Payment = $Month_Dec_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                    }
                }
            }

            $Month_Jan_Payment_Month = 0;
            $Month_Feb_Payment_Month = 0;
            $Month_Mar_Payment_Month = 0;
            $Month_Apr_Payment_Month = 0;
            $Month_May_Payment_Month = 0;
            $Month_Jun_Payment_Month = 0;
            $Month_Jul_Payment_Month = 0;
            $Month_Aug_Payment_Month = 0;
            $Month_Sep_Payment_Month = 0;
            $Month_Oct_Payment_Month = 0;
            $Month_Nov_Payment_Month = 0;
            $Month_Dec_Payment_Month = 0;

            foreach ($PaymentMonths as $PaymentMonth) {
                if ($data === date('Y')) {
                    if ($PaymentMonth->overdue_payment <= date('m')) {
                        switch ($PaymentMonth->overdue_payment) {
                            case "1":
                                $Month_Jan_Payment_Month = $Month_Jan_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "2":
                                $Month_Feb_Payment_Month = $Month_Feb_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "3":
                                $Month_Mar_Payment_Month = $Month_Mar_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "4":
                                $Month_Apr_Payment_Month = $Month_Apr_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "5":
                                $Month_May_Payment_Month = $Month_May_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "6":
                                $Month_Jun_Payment_Month = $Month_Jun_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "7":
                                $Month_Jul_Payment_Month = $Month_Jul_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "8":
                                $Month_Aug_Payment_Month = $Month_Aug_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "9":
                                $Month_Sep_Payment_Month = $Month_Sep_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "10":
                                $Month_Oct_Payment_Month = $Month_Oct_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "11":
                                $Month_Nov_Payment_Month = $Month_Nov_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "12":
                                $Month_Dec_Payment_Month = $Month_Dec_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                        }
                    }
                } elseif ($data < date('Y')) {
                    switch ($PaymentMonth->overdue_payment) {
                        case "1":
                            $Month_Jan_Payment_Month = $Month_Jan_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "2":
                            $Month_Feb_Payment_Month = $Month_Feb_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "3":
                            $Month_Mar_Payment_Month = $Month_Mar_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "4":
                            $Month_Apr_Payment_Month = $Month_Apr_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "5":
                            $Month_May_Payment_Month = $Month_May_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "6":
                            $Month_Jun_Payment_Month = $Month_Jun_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "7":
                            $Month_Jul_Payment_Month = $Month_Jul_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "8":
                            $Month_Aug_Payment_Month = $Month_Aug_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "9":
                            $Month_Sep_Payment_Month = $Month_Sep_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "10":
                            $Month_Oct_Payment_Month = $Month_Oct_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "11":
                            $Month_Nov_Payment_Month = $Month_Nov_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "12":
                            $Month_Dec_Payment_Month = $Month_Dec_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                    }
                }
            }

            $Month_Class_Jan = '';
            $Month_Class_Feb = '';
            $Month_Class_Mar = '';
            $Month_Class_Apr = '';
            $Month_Class_May = '';
            $Month_Class_Jun = '';
            $Month_Class_Jul = '';
            $Month_Class_Aug = '';
            $Month_Class_Sep = '';
            $Month_Class_Oct = '';
            $Month_Class_Nov = '';
            $Month_Class_Dec = '';

            if ($data === date('Y')) {
                switch (date('m')) {
                    case "1":
                        $Month_Class_Jan = "bg-primary-transparent";
                        break;
                    case "2":
                        $Month_Class_Feb = "bg-primary-transparent";
                        break;
                    case "3":
                        $Month_Class_Mar = "bg-primary-transparent";
                        break;
                    case "4":
                        $Month_Class_Apr = "bg-primary-transparent";
                        break;
                    case "5":
                        $Month_Class_May = "bg-primary-transparent";
                        break;
                    case "6":
                        $Month_Class_Jun = "bg-primary-transparent";
                        break;
                    case "7":
                        $Month_Class_Jul = "bg-primary-transparent";
                        break;
                    case "8":
                        $Month_Class_Aug = "bg-primary-transparent";
                        break;
                    case "9":
                        $Month_Class_Sep = "bg-primary-transparent";
                        break;
                    case "10":
                        $Month_Class_Oct = "bg-primary-transparent";
                        break;
                    case "11":
                        $Month_Class_Nov = "bg-primary-transparent";
                        break;
                    case "12":
                        $Month_Class_Dec = "bg-primary-transparent";
                        break;
                }
            }

            $Month_Jan_Diff_Payment_Month = $Month_Jan_Payment_Month - $Month_Jan_Overdue_Payment;
            $Month_Feb_Diff_Payment_Month = $Month_Feb_Payment_Month - $Month_Feb_Overdue_Payment;
            $Month_Mar_Diff_Payment_Month = $Month_Mar_Payment_Month - $Month_Mar_Overdue_Payment;
            $Month_Apr_Diff_Payment_Month = $Month_Apr_Payment_Month - $Month_Apr_Overdue_Payment;
            $Month_May_Diff_Payment_Month = $Month_May_Payment_Month - $Month_May_Overdue_Payment;
            $Month_Jun_Diff_Payment_Month = $Month_Jun_Payment_Month - $Month_Jun_Overdue_Payment;
            $Month_Jul_Diff_Payment_Month = $Month_Jul_Payment_Month - $Month_Jul_Overdue_Payment;
            $Month_Aug_Diff_Payment_Month = $Month_Aug_Payment_Month - $Month_Aug_Overdue_Payment;
            $Month_Sep_Diff_Payment_Month = $Month_Sep_Payment_Month - $Month_Sep_Overdue_Payment;
            $Month_Oct_Diff_Payment_Month = $Month_Oct_Payment_Month - $Month_Oct_Overdue_Payment;
            $Month_Nov_Diff_Payment_Month = $Month_Nov_Payment_Month - $Month_Nov_Overdue_Payment;
            $Month_Dec_Diff_Payment_Month = $Month_Dec_Payment_Month - $Month_Dec_Overdue_Payment;

            $Month_Payment_Sum = 0;
            $Month_Payment_Sum = $Month_Jan_Payment + $Month_Feb_Payment + $Month_Mar_Payment + $Month_Apr_Payment + $Month_May_Payment + $Month_Jun_Payment
                + $Month_Jul_Payment + $Month_Aug_Payment + $Month_Sep_Payment + $Month_Oct_Payment + $Month_Nov_Payment + $Month_Dec_Payment;

            $Month_Loan_Sum = 0;
            $Month_Loan_Sum = $Month_Jan_Loan + $Month_Feb_Loan + $Month_Mar_Loan + $Month_Apr_Loan + $Month_May_Loan + $Month_Jun_Loan
                + $Month_Jul_Loan + $Month_Aug_Loan + $Month_Sep_Loan + $Month_Oct_Loan + $Month_Nov_Loan + $Month_Dec_Loan;

            $Month_Overdue_Payment_Sum = 0;
            $Month_Overdue_Payment_Sum = $Month_Jan_Overdue_Payment + $Month_Feb_Overdue_Payment + $Month_Mar_Overdue_Payment + $Month_Apr_Overdue_Payment + $Month_May_Overdue_Payment + $Month_Jun_Overdue_Payment
                + $Month_Jul_Overdue_Payment + $Month_Aug_Overdue_Payment + $Month_Sep_Overdue_Payment + $Month_Oct_Overdue_Payment + $Month_Nov_Overdue_Payment + $Month_Dec_Overdue_Payment;

            $Month_Diff_Payment_Sum = 0;
            $Month_Diff_Payment_Sum = $Month_Jan_Diff_Payment_Month + $Month_Feb_Diff_Payment_Month + $Month_Mar_Diff_Payment_Month + $Month_Apr_Diff_Payment_Month + $Month_May_Diff_Payment_Month + $Month_Jun_Diff_Payment_Month
                + $Month_Jul_Diff_Payment_Month + $Month_Aug_Diff_Payment_Month + $Month_Sep_Diff_Payment_Month + $Month_Oct_Diff_Payment_Month + $Month_Nov_Diff_Payment_Month + $Month_Dec_Diff_Payment_Month;

            $html =
                '<div class="card-body">
                <div class="table-responsive">
                    <table class="table border-0 mb-0">
                        <tbody>
                            <tr>
                                <th class="border-top-0 bg-black-03 br-bs-5 br-ts-5 tx-15 wd-20p">เดือน</th>
                                <th class="border-top-0 bg-black-03 tx-15 wd-20p">ยอดรับชำระต่อเดือน</th>
                                <th class="border-top-0 bg-black-03 tx-15 wd-20p">ยอดเปิดสินเชื่อต่อเดือน</th>
                                <th class="border-top-0 bg-black-03 tx-15 wd-20p">ยอดค้างชำระต่อเดือน</th>
                                <th class="border-top-0 bg-black-03 tx-15 wd-20p">ยอดชำระค่างวดต่อเดือน</th>
                            </tr>
                            <tr class="' . $Month_Class_Jan . '">
                                <td class="border-top-0 pt-4"><a>มกราคม</a></td>
                                <td class="border-top-0"><a href="javascript:void(0);" data-id="1" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">' . number_format($Month_Jan_Payment, 2) . '</a></td>
                                <td class="border-top-0"><a href="javascript:void(0);" data-id="1" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">' . number_format($Month_Jan_Loan, 2) . '</a></td>
                                <td class="border-top-0"><a href="javascript:void(0);" data-id="01" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">' . number_format($Month_Jan_Overdue_Payment, 2) . '</a></td>
                                <td class="border-top-0"><a href="javascript:void(0);" data-id="01" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">' . number_format($Month_Jan_Diff_Payment_Month, 2) . '</a></td>
                            </tr>
                            <tr class="' . $Month_Class_Feb . '">
                                <td class="border-top-0 pt-4"><a>กุมภาพันธ์</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="2" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">' . number_format($Month_Feb_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="2" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">' . number_format($Month_Feb_Loan, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="02" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">' . number_format($Month_Feb_Overdue_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="02" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">' . number_format($Month_Feb_Diff_Payment_Month, 2) . '</a></td>
                            </tr>
                            <tr class="' . $Month_Class_Mar . '">
                                <td class="border-top-0 pt-4"><a>มีนาคม</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="3" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">' . number_format($Month_Mar_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="3" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">' . number_format($Month_Mar_Loan, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="03" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">' . number_format($Month_Mar_Overdue_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="03" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">' . number_format($Month_Mar_Diff_Payment_Month, 2) . '</a></td>
                            </tr>
                            <tr class="' . $Month_Class_Apr . '">
                                <td class="border-top-0 pt-4"><a>เมษายน</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="4" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">' . number_format($Month_Apr_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="4" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">' . number_format($Month_Apr_Loan, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="04" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">' . number_format($Month_Apr_Overdue_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="04" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">' . number_format($Month_Apr_Diff_Payment_Month, 2) . '</a></td>
                            </tr>
                            <tr class="' . $Month_Class_May . '">
                                <td class="border-top-0 pt-4"><a>พฤษภาคม</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="5" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">' . number_format($Month_May_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="5" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">' . number_format($Month_May_Loan, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="05" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">' . number_format($Month_May_Overdue_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="05" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">' . number_format($Month_May_Diff_Payment_Month, 2) . '</a></td>
                            </tr>
                            <tr class="' . $Month_Class_Jun . '">
                                <td class="border-top-0 pt-4"><a>มิถุนายน</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="6" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">' . number_format($Month_Jun_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="6" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">' . number_format($Month_Jun_Loan, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="06" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">' . number_format($Month_Jun_Overdue_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="06" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">' . number_format($Month_Jun_Diff_Payment_Month, 2) . '</a></td>
                            </tr>
                            <tr class="' . $Month_Class_Jul . '">
                                <td class="border-top-0 pt-4"><a>กรกฏาคม</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="7" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">' . number_format($Month_Jul_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="7" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">' . number_format($Month_Jul_Loan, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="07" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">' . number_format($Month_Jul_Overdue_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="07" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">' . number_format($Month_Jul_Diff_Payment_Month, 2) . '</a></td>
                            </tr>
                            <tr class="' . $Month_Class_Aug . '">
                                <td class="border-top-0 pt-4"><a>สิงหาคม</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="8" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">' . number_format($Month_Aug_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="8" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">' . number_format($Month_Aug_Loan, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="08" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">' . number_format($Month_Aug_Overdue_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="08" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">' . number_format($Month_Aug_Diff_Payment_Month, 2) . '</a></td>
                            </tr>
                            <tr class="' . $Month_Class_Sep . '">
                                <td class="border-top-0 pt-4"><a>กันยายน</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="9" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">' . number_format($Month_Sep_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="9" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">' . number_format($Month_Sep_Loan, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="09" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">' . number_format($Month_Sep_Overdue_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="09" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">' . number_format($Month_Sep_Diff_Payment_Month, 2) . '</a></td>
                            </tr>
                            <tr class="' . $Month_Class_Oct . '">
                                <td class="border-top-0 pt-4"><a>ตุลาคม</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="10" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">' . number_format($Month_Oct_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="10" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">' . number_format($Month_Oct_Loan, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="10" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">' . number_format($Month_Oct_Overdue_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="10" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">' . number_format($Month_Oct_Diff_Payment_Month, 2) . '</a></td>
                            </tr>
                            <tr class="' . $Month_Class_Nov . '">
                                <td class="border-top-0 pt-4"><a>พฤศจิกายน</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="11" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">' . number_format($Month_Nov_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="11" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">' . number_format($Month_Nov_Loan, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="11" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">' . number_format($Month_Nov_Overdue_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="11" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">' . number_format($Month_Nov_Diff_Payment_Month, 2) . '</a></td>
                            </tr>
                            <tr class="' . $Month_Class_Dec . '">
                                <td class="border-top-0 pt-4"><a>ธันวาคม</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="12" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">' . number_format($Month_Dec_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="12" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">' . number_format($Month_Dec_Loan, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="12" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">' . number_format($Month_Dec_Overdue_Payment, 2) . '</a></td>
                                <td class="border-top-0 "><a href="javascript:void(0);" data-id="12" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">' . number_format($Month_Dec_Diff_Payment_Month, 2) . '</a></td>
                            </tr>
                            <tr class="bg-primary">
                                <td class="border-top-0 pt-4">
                                    <p class="tx-left mb-0">ยอดรวม</p>
                                </td>      
                                <td class="border-top-0">
                                    <p class="mb-0">' . number_format($Month_Payment_Sum, 2) . '</p>
                                </td>
                                <td class="border-top-0">
                                    <p class="mb-0">' . number_format($Month_Loan_Sum, 2) . '</p>
                                </td>
                                <td class="border-top-0">
                                    <p class="mb-0">' . number_format($Month_Overdue_Payment_Sum, 2) . '</p>
                                </td>
                                <td class="border-top-0">
                                    <p class="mb-0">' . number_format($Month_Diff_Payment_Sum, 2) . '</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
                    ';



            $response['data'] = $html;

            $status = 200;
            $response['success'] = 1;
            $response['message'] = '';

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    public function ajaxDataTablePayment($id)
    {
        $LoanModel = new \App\Models\LoanModel();
        $param['search_value'] = $_REQUEST['search']['value'];
        $param['draw'] = $_REQUEST['draw'];
        $param['start'] = $_REQUEST['start'];
        $param['length'] = $_REQUEST['length'];
        $param['month'] = $id;
        $param['years'] = $_REQUEST['years'];

        if (!empty($param['search_value'])) {
            // count all data
            $total_count = $LoanModel->getDataTablePaymentMonthSearchCount($param);

            $data_month = $LoanModel->getDataTablePaymentMonthSearch($param);
        } else {
            // count all data
            $total_count = $LoanModel->getDataTablePaymentMonthCount($param);

            // get per page data
            $data_month = $LoanModel->getDataTablePaymentMonth($param);
        }

        $i = 0;
        $data = [];
        foreach ($data_month as $datas) {
            $i++;
            $data[] = array(
                $i,
                $datas->loan_code,
                $datas->loan_payment_customer,
                $datas->loan_employee,
                $datas->loan_payment_installment,
                $datas->land_account_name,
                $datas->loan_payment_pay_type,
                number_format($datas->loan_payment_amount, 2),
                $datas->payment_date,
            );
        }

        $json_data = array(
            "draw" => intval($param['draw']),
            "recordsTotal" => count($total_count),
            "recordsFiltered" => count($total_count),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }

    public function ajaxDataTableLoan($id)
    {
        $LoanModel = new \App\Models\LoanModel();
        $param['search_value'] = $_REQUEST['search']['value'];
        $param['draw'] = $_REQUEST['draw'];
        $param['start'] = $_REQUEST['start'];
        $param['length'] = $_REQUEST['length'];
        $param['month'] = $id;
        $param['years'] = $_REQUEST['years'];

        if (!empty($param['search_value'])) {
            // count all data
            $total_count = $LoanModel->getDataTableLoanMonthSearchCount($param);

            $data_month = $LoanModel->getDataTableLoanMonthSearch($param);
        } else {
            // count all data
            $total_count = $LoanModel->getDataTableLoanMonthCount($param);

            // get per page data
            $data_month = $LoanModel->getDataTableLoanMonth($param);
        }

        $i = 0;
        $data = [];
        foreach ($data_month as $datas) {
            $i++;
            $data[] = array(
                $i,
                $datas->loan_code,
                $datas->loan_customer,
                $datas->loan_employee,
                $datas->land_account_name,
                number_format($datas->loan_summary_no_vat, 2),
                $datas->loan_date,
            );
        }

        $json_data = array(
            "draw" => intval($param['draw']),
            "recordsTotal" => count($total_count),
            "recordsFiltered" => count($total_count),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }

    public function ajaxDataTableOverduePayment($id)
    {
        $LoanModel = new \App\Models\LoanModel();
        $param['search_value'] = $_REQUEST['search']['value'];
        $param['draw'] = $_REQUEST['draw'];
        $param['start'] = $_REQUEST['start'];
        $param['length'] = $_REQUEST['length'];
        $param['month'] = $id;
        $param['years'] = $_REQUEST['years'];

        if (!empty($param['search_value'])) {
            // count all data
            $total_count = $LoanModel->getDataTableOverduePaymentMonthSearchCount($param);

            $data_month = $LoanModel->getDataTableOverduePaymentMonthSearch($param);
        } else {
            // count all data
            $total_count = $LoanModel->getDataTableOverduePaymentMonthCount($param);

            // get per page data
            $data_month = $LoanModel->getDataTableOverduePaymentMonth($param);
        }

        $i = 0;
        $data = [];
        foreach ($data_month as $datas) {
            $i++;
            $data[] = array(
                $i,
                $datas->loan_code,
                $datas->loan_customer,
                $datas->loan_payment_installment,
                number_format($datas->loan_payment_amount, 2),
                $datas->payment_date,
            );
        }

        $json_data = array(
            "draw" => intval($param['draw']),
            "recordsTotal" => count($total_count),
            "recordsFiltered" => count($total_count),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }

    
    public function ajaxDataTableDiffPayment($id)
    {
        $LoanModel = new \App\Models\LoanModel();
        $param['search_value'] = $_REQUEST['search']['value'];
        $param['draw'] = $_REQUEST['draw'];
        $param['start'] = $_REQUEST['start'];
        $param['length'] = $_REQUEST['length'];
        $param['month'] = $id;
        $param['years'] = $_REQUEST['years'];

        if (!empty($param['search_value'])) {
            // count all data
            $total_count = $LoanModel->getDataTableDiffPaymentMonthSearchCount($param);

            $data_month = $LoanModel->getDataTableDiffPaymentMonthSearch($param);
        } else {
            // count all data
            $total_count = $LoanModel->getDataTableDiffPaymentMonthCount($param);

            // get per page data
            $data_month = $LoanModel->getDataTableDiffPaymentMonth($param);
        }

        $i = 0;
        $data = [];
        foreach ($data_month as $datas) {
            $i++;
            $data[] = array(
                $i,
                $datas->loan_code,
                $datas->loan_payment_customer,
                $datas->employee,
                $datas->loan_payment_installment,
                number_format($datas->loan_payment_amount, 2),
                $datas->loan_payment_date,
            );
        }

        $json_data = array(
            "draw" => intval($param['draw']),
            "recordsTotal" => count($total_count),
            "recordsFiltered" => count($total_count),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }

    public function ajaxGraphLoan($data)
    {
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            // $DocumentModel = new \App\Models\DocumentModel();
            // $Profit = $DocumentModel->getprofit($data);
            $OverduePayments = $this->LoanModel->getOverdueListPayments($data);
            $PaymentMonths = $this->LoanModel->getListPaymentMonths($data);
            // $Month = "";
            $Month_Jan_Payment_Month = 0;
            $Month_Feb_Payment_Month = 0;
            $Month_Mar_Payment_Month = 0;
            $Month_Apr_Payment_Month = 0;
            $Month_May_Payment_Month = 0;
            $Month_Jun_Payment_Month = 0;
            $Month_Jul_Payment_Month = 0;
            $Month_Aug_Payment_Month = 0;
            $Month_Sep_Payment_Month = 0;
            $Month_Oct_Payment_Month = 0;
            $Month_Nov_Payment_Month = 0;
            $Month_Dec_Payment_Month = 0;

            foreach ($PaymentMonths as $PaymentMonth) {
                if ($data === date('Y')) {
                    if ($PaymentMonth->overdue_payment <= date('m')) {
                        switch ($PaymentMonth->overdue_payment) {
                            case "1":
                                $Month_Jan_Payment_Month = $Month_Jan_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "2":
                                $Month_Feb_Payment_Month = $Month_Feb_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "3":
                                $Month_Mar_Payment_Month = $Month_Mar_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "4":
                                $Month_Apr_Payment_Month = $Month_Apr_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "5":
                                $Month_May_Payment_Month = $Month_May_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "6":
                                $Month_Jun_Payment_Month = $Month_Jun_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "7":
                                $Month_Jul_Payment_Month = $Month_Jul_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "8":
                                $Month_Aug_Payment_Month = $Month_Aug_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "9":
                                $Month_Sep_Payment_Month = $Month_Sep_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "10":
                                $Month_Oct_Payment_Month = $Month_Oct_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "11":
                                $Month_Nov_Payment_Month = $Month_Nov_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "12":
                                $Month_Dec_Payment_Month = $Month_Dec_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                        }
                    }
                } elseif ($data < date('Y')) {
                    switch ($PaymentMonth->overdue_payment) {
                        case "1":
                            $Month_Jan_Payment_Month = $Month_Jan_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "2":
                            $Month_Feb_Payment_Month = $Month_Feb_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "3":
                            $Month_Mar_Payment_Month = $Month_Mar_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "4":
                            $Month_Apr_Payment_Month = $Month_Apr_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "5":
                            $Month_May_Payment_Month = $Month_May_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "6":
                            $Month_Jun_Payment_Month = $Month_Jun_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "7":
                            $Month_Jul_Payment_Month = $Month_Jul_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "8":
                            $Month_Aug_Payment_Month = $Month_Aug_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "9":
                            $Month_Sep_Payment_Month = $Month_Sep_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "10":
                            $Month_Oct_Payment_Month = $Month_Oct_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "11":
                            $Month_Nov_Payment_Month = $Month_Nov_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "12":
                            $Month_Dec_Payment_Month = $Month_Dec_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                    }
                }
            }

            $Month_Jan_Overdue_Payment = 0;
            $Month_Feb_Overdue_Payment = 0;
            $Month_Mar_Overdue_Payment = 0;
            $Month_Apr_Overdue_Payment = 0;
            $Month_May_Overdue_Payment = 0;
            $Month_Jun_Overdue_Payment = 0;
            $Month_Jul_Overdue_Payment = 0;
            $Month_Aug_Overdue_Payment = 0;
            $Month_Sep_Overdue_Payment = 0;
            $Month_Oct_Overdue_Payment = 0;
            $Month_Nov_Overdue_Payment = 0;
            $Month_Dec_Overdue_Payment = 0;

            foreach ($OverduePayments as $OverduePayment) {
                if ($data === date('Y')) {
                    if ($OverduePayment->overdue_payment <= date('m')) {
                        switch ($OverduePayment->overdue_payment) {
                            case "1":
                                $Month_Jan_Overdue_Payment = $Month_Jan_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "2":

                                $Month_Feb_Overdue_Payment = $Month_Feb_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "3":
                                $Month_Mar_Overdue_Payment = $Month_Mar_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "4":
                                $Month_Apr_Overdue_Payment = $Month_Apr_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "5":
                                $Month_May_Overdue_Payment = $Month_May_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "6":
                                $Month_Jun_Overdue_Payment = $Month_Jun_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "7":
                                $Month_Jul_Overdue_Payment = $Month_Jul_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "8":
                                $Month_Aug_Overdue_Payment = $Month_Aug_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "9":
                                $Month_Sep_Overdue_Payment = $Month_Sep_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "10":
                                $Month_Oct_Overdue_Payment = $Month_Oct_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "11":
                                $Month_Nov_Overdue_Payment = $Month_Nov_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "12":
                                $Month_Dec_Overdue_Payment = $Month_Dec_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                        }
                    }
                } elseif ($data < date('Y')) {
                    switch ($OverduePayment->overdue_payment) {
                        case "1":
                            $Month_Jan_Overdue_Payment = $Month_Jan_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "2":
                            $Month_Feb_Overdue_Payment = $Month_Feb_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "3":
                            $Month_Mar_Overdue_Payment = $Month_Mar_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "4":
                            $Month_Apr_Overdue_Payment = $Month_Apr_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "5":
                            $Month_May_Overdue_Payment = $Month_May_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "6":
                            $Month_Jun_Overdue_Payment = $Month_Jun_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "7":
                            $Month_Jul_Overdue_Payment = $Month_Jul_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "8":
                            $Month_Aug_Overdue_Payment = $Month_Aug_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "9":
                            $Month_Sep_Overdue_Payment = $Month_Sep_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "10":
                            $Month_Oct_Overdue_Payment = $Month_Oct_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "11":
                            $Month_Nov_Overdue_Payment = $Month_Nov_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "12":
                            $Month_Dec_Overdue_Payment = $Month_Dec_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                    }
                }
            }

            $Month_Revenue_Jan_Sum = 0;
            $Month_Revenue_Feb_Sum = 0;
            $Month_Revenue_Mar_Sum = 0;
            $Month_Revenue_Apr_Sum = 0;
            $Month_Revenue_May_Sum = 0;
            $Month_Revenue_Jun_Sum = 0;
            $Month_Revenue_Jul_Sum = 0;
            $Month_Revenue_Aug_Sum = 0;
            $Month_Revenue_Sep_Sum = 0;
            $Month_Revenue_Oct_Sum = 0;
            $Month_Revenue_Nov_Sum = 0;
            $Month_Revenue_Dec_Sum = 0;

            $Month_Jan_Diff_Payment_Month = $Month_Jan_Payment_Month - $Month_Jan_Overdue_Payment;
            $Month_Feb_Diff_Payment_Month = $Month_Feb_Payment_Month - $Month_Feb_Overdue_Payment;
            $Month_Mar_Diff_Payment_Month = $Month_Mar_Payment_Month - $Month_Mar_Overdue_Payment;
            $Month_Apr_Diff_Payment_Month = $Month_Apr_Payment_Month - $Month_Apr_Overdue_Payment;
            $Month_May_Diff_Payment_Month = $Month_May_Payment_Month - $Month_May_Overdue_Payment;
            $Month_Jun_Diff_Payment_Month = $Month_Jun_Payment_Month - $Month_Jun_Overdue_Payment;
            $Month_Jul_Diff_Payment_Month = $Month_Jul_Payment_Month - $Month_Jul_Overdue_Payment;
            $Month_Aug_Diff_Payment_Month = $Month_Aug_Payment_Month - $Month_Aug_Overdue_Payment;
            $Month_Sep_Diff_Payment_Month = $Month_Sep_Payment_Month - $Month_Sep_Overdue_Payment;
            $Month_Oct_Diff_Payment_Month = $Month_Oct_Payment_Month - $Month_Oct_Overdue_Payment;
            $Month_Nov_Diff_Payment_Month = $Month_Nov_Payment_Month - $Month_Nov_Overdue_Payment;
            $Month_Dec_Diff_Payment_Month = $Month_Dec_Payment_Month - $Month_Dec_Overdue_Payment;

            $Month_Revenue_Jan_Sum = $Month_Jan_Diff_Payment_Month + $Month_Jan_Overdue_Payment;
            $Month_Revenue_Feb_Sum = $Month_Feb_Diff_Payment_Month + $Month_Feb_Overdue_Payment;
            $Month_Revenue_Mar_Sum = $Month_Mar_Diff_Payment_Month + $Month_Mar_Overdue_Payment;
            $Month_Revenue_Apr_Sum = $Month_Apr_Diff_Payment_Month + $Month_Apr_Overdue_Payment;
            $Month_Revenue_May_Sum = $Month_May_Diff_Payment_Month + $Month_May_Overdue_Payment;
            $Month_Revenue_Jun_Sum = $Month_Jun_Diff_Payment_Month + $Month_Jun_Overdue_Payment;
            $Month_Revenue_Jul_Sum = $Month_Jul_Diff_Payment_Month + $Month_Jul_Overdue_Payment;
            $Month_Revenue_Aug_Sum = $Month_Aug_Diff_Payment_Month + $Month_Aug_Overdue_Payment;
            $Month_Revenue_Sep_Sum = $Month_Sep_Diff_Payment_Month + $Month_Sep_Overdue_Payment;
            $Month_Revenue_Oct_Sum = $Month_Oct_Diff_Payment_Month + $Month_Oct_Overdue_Payment;
            $Month_Revenue_Nov_Sum = $Month_Nov_Diff_Payment_Month + $Month_Nov_Overdue_Payment;
            $Month_Revenue_Dec_Sum = $Month_Dec_Diff_Payment_Month + $Month_Dec_Overdue_Payment;

            $Month_Revenue_Jan = 0;
            $Month_Revenue_Feb = 0;
            $Month_Revenue_Mar = 0;
            $Month_Revenue_Apr = 0;
            $Month_Revenue_May = 0;
            $Month_Revenue_Jun = 0;
            $Month_Revenue_Jul = 0;
            $Month_Revenue_Aug = 0;
            $Month_Revenue_Sep = 0;
            $Month_Revenue_Oct = 0;
            $Month_Revenue_Nov = 0;
            $Month_Revenue_Dec = 0;

            if ($Month_Revenue_Jan_Sum != 0 && $Month_Jan_Diff_Payment_Month != 0) {
                $Month_Revenue_Jan = $Month_Jan_Diff_Payment_Month * 100 / $Month_Revenue_Jan_Sum;
            } elseif ($Month_Revenue_Jan_Sum == 0) {
                $Month_Revenue_Jan = 0;
            } elseif ($Month_Jan_Diff_Payment_Month == 0 && $Month_Revenue_Jan_Sum != 0) {
                $Month_Revenue_Jan = 0;
            }

            if ($Month_Revenue_Feb_Sum != 0 && $Month_Feb_Diff_Payment_Month != 0) {
                $Month_Revenue_Feb = $Month_Feb_Diff_Payment_Month * 100 / $Month_Revenue_Feb_Sum;
            } elseif ($Month_Revenue_Feb_Sum == 0) {
                $Month_Revenue_Feb = 0;
            } elseif ($Month_Feb_Diff_Payment_Month == 0 && $Month_Revenue_Feb_Sum != 0) {
                $Month_Revenue_Feb = 0;
            }

            if ($Month_Revenue_Mar_Sum != 0 && $Month_Mar_Diff_Payment_Month != 0) {
                $Month_Revenue_Mar = $Month_Mar_Diff_Payment_Month * 100 / $Month_Revenue_Mar_Sum;
            } elseif ($Month_Revenue_Mar_Sum == 0) {
                $Month_Revenue_Mar = 0;
            } elseif ($Month_Mar_Diff_Payment_Month == 0 && $Month_Revenue_Mar_Sum != 0) {
                $Month_Revenue_Mar = 0;
            }

            if ($Month_Revenue_Apr_Sum != 0 && $Month_Apr_Diff_Payment_Month != 0) {
                $Month_Revenue_Apr = $Month_Apr_Diff_Payment_Month * 100 / $Month_Revenue_Apr_Sum;
            } elseif ($Month_Revenue_Apr_Sum == 0) {
                $Month_Revenue_Apr = 0;
            } elseif ($Month_Apr_Diff_Payment_Month == 0 && $Month_Revenue_Apr_Sum != 0) {
                $Month_Revenue_Apr = 0;
            }

            if ($Month_Revenue_May_Sum != 0 && $Month_May_Diff_Payment_Month != 0) {
                $Month_Revenue_May = $Month_May_Diff_Payment_Month * 100 / $Month_Revenue_May_Sum;
            } elseif ($Month_Revenue_May_Sum == 0) {
                $Month_Revenue_May = 0;
            } elseif ($Month_May_Diff_Payment_Month == 0 && $Month_Revenue_May_Sum != 0) {
                $Month_Revenue_May = 0;
            }

            if ($Month_Revenue_Jun_Sum != 0 && $Month_Jun_Diff_Payment_Month != 0) {
                $Month_Revenue_Jun = $Month_Jun_Diff_Payment_Month * 100 / $Month_Revenue_Jun_Sum;
            } elseif ($Month_Revenue_Jun_Sum == 0) {
                $Month_Revenue_Jun = 0;
            } elseif ($Month_Jun_Diff_Payment_Month == 0 && $Month_Revenue_Jun_Sum != 0) {
                $Month_Revenue_Jun = 0;
            }

            if ($Month_Revenue_Jul_Sum != 0 && $Month_Jul_Diff_Payment_Month != 0) {
                $Month_Revenue_Jul = $Month_Jul_Diff_Payment_Month * 100 / $Month_Revenue_Jul_Sum;
            } elseif ($Month_Revenue_Jul_Sum == 0) {
                $Month_Revenue_Jul = 0;
            } elseif ($Month_Jul_Diff_Payment_Month == 0 && $Month_Revenue_Jul_Sum != 0) {
                $Month_Revenue_Jul = 0;
            }

            if ($Month_Revenue_Aug_Sum != 0 && $Month_Aug_Diff_Payment_Month != 0) {
                $Month_Revenue_Aug = $Month_Aug_Diff_Payment_Month * 100 / $Month_Revenue_Aug_Sum;
            } elseif ($Month_Revenue_Aug_Sum == 0) {
                $Month_Revenue_Aug = 0;
            } elseif ($Month_Aug_Diff_Payment_Month == 0 && $Month_Revenue_Aug_Sum != 0) {
                $Month_Revenue_Aug = 0;
            }

            if ($Month_Revenue_Sep_Sum != 0 && $Month_Sep_Diff_Payment_Month != 0) {
                $Month_Revenue_Sep = $Month_Sep_Diff_Payment_Month * 100 / $Month_Revenue_Sep_Sum;
            } elseif ($Month_Revenue_Sep_Sum == 0) {
                $Month_Revenue_Sep = 0;
            } elseif ($Month_Sep_Diff_Payment_Month == 0 && $Month_Revenue_Sep_Sum != 0) {
                $Month_Revenue_Sep = 0;
            }

            if ($Month_Revenue_Oct_Sum != 0 && $Month_Oct_Diff_Payment_Month != 0) {
                $Month_Revenue_Oct = $Month_Oct_Diff_Payment_Month * 100 / $Month_Revenue_Oct_Sum;
            } elseif ($Month_Revenue_Oct_Sum == 0) {
                $Month_Revenue_Oct = 0;
            } elseif ($Month_Oct_Diff_Payment_Month == 0 && $Month_Revenue_Oct_Sum != 0) {
                $Month_Revenue_Oct = 0;
            }

            if ($Month_Revenue_Nov_Sum != 0 && $Month_Nov_Diff_Payment_Month != 0) {
                $Month_Revenue_Nov = $Month_Nov_Diff_Payment_Month * 100 / $Month_Revenue_Nov_Sum;
            } elseif ($Month_Revenue_Nov_Sum == 0) {
                $Month_Revenue_Nov = 0;
            } elseif ($Month_Nov_Diff_Payment_Month == 0 && $Month_Revenue_Nov_Sum != 0) {
                $Month_Revenue_Nov = 0;
            }

            if ($Month_Revenue_Dec_Sum != 0 && $Month_Dec_Diff_Payment_Month != 0) {
                $Month_Revenue_Dec = $Month_Dec_Diff_Payment_Month * 100 / $Month_Revenue_Dec_Sum;
            } elseif ($Month_Revenue_Dec_Sum == 0) {
                $Month_Revenue_Dec = 0;
            } elseif ($Month_Dec_Diff_Payment_Month == 0 && $Month_Revenue_Dec_Sum != 0) {
                $Month_Revenue_Dec = 0;
            }

            $html =
                '<div class="d-flex mg-b-15">
                <div class="me-2">
                    <span class="avatar avatar-sm radius-4 " style=" background-color:#ADFF2F2e;  color: #ADFF2F;"><i class="fas fa-sack-dollar"></i></span>
                </div>
                <div class="flex-1">
                    <div class="flex-between mb-2">
                        <p class="mb-0"><span class="pe-2 border-end">มกราคม</span><span class="ps-2 tx-muted">' . number_format($Month_Revenue_Jan, 2) . "%" . '</span></p>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated ht-5 bg-success" style="width: ' . number_format($Month_Revenue_Jan, 2) . "%" . '"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex mg-b-15">
                <div class="me-2">
                    <span class="avatar avatar-sm radius-4 " style=" background-color:#00CCFF2e;  color: #00CCFF;"><i class="fas fa-sack-dollar"></i></span>
                </div>
                <div class="flex-1">
                    <div class="flex-between mb-2">
                        <p class="mb-0"><span class="pe-2 border-end">กุมภาพันธ์</span><span class="ps-2 tx-muted">' . number_format($Month_Revenue_Feb, 2) . "%" . '</span></p>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated ht-5 bg-success" style="width: ' . number_format($Month_Revenue_Feb, 2) . "%" . '"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex mg-b-15">
                <div class="me-2">
                    <span class="avatar avatar-sm radius-4 " style=" background-color:#9900002e;  color: #990000;"><i class="fas fa-sack-dollar"></i></span>
                </div>
                <div class="flex-1">
                    <div class="flex-between mb-2">
                        <p class="mb-0"><span class="pe-2 border-end">มีนาคม</span><span class="ps-2 tx-muted">' . number_format($Month_Revenue_Mar, 2) . "%" . '</span></p>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated ht-5 bg-success" style="width: ' . number_format($Month_Revenue_Mar, 2) . "%" . '"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex mg-b-15">
                <div class="me-2">
                    <span class="avatar avatar-sm radius-4 " style=" background-color:#9900992e;  color: #990099;"><i class="fas fa-sack-dollar"></i></span>
                </div>
                <div class="flex-1">
                    <div class="flex-between mb-2">
                        <p class="mb-0"><span class="pe-2 border-end">เมษายน</span><span class="ps-2 tx-muted">' . number_format($Month_Revenue_Apr, 2) . "%" . '</span></p>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated ht-5 bg-success" style="width: ' . number_format($Month_Revenue_Apr, 2) . "%" . '"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex mg-b-15">
                <div class="me-2">
                    <span class="avatar avatar-sm radius-4 " style=" background-color:#FFFF332e;  color: #FFFF33;"><i class="fas fa-sack-dollar"></i></span>
                </div>
                <div class="flex-1">
                    <div class="flex-between mb-2">
                        <p class="mb-0"><span class="pe-2 border-end">พฤษภาคม</span><span class="ps-2 tx-muted">' . number_format($Month_Revenue_May, 2) . "%" . '</span></p>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated ht-5 bg-success" style="width: ' . number_format($Month_Revenue_May, 2) . "%" . '"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex mg-b-15">
                <div class="me-2">
                    <span class="avatar avatar-sm radius-4 " style=" background-color:#FF66002e;  color: #FF6600;"><i class="fas fa-sack-dollar"></i></span>
                </div>
                <div class="flex-1">
                    <div class="flex-between mb-2">
                        <p class="mb-0"><span class="pe-2 border-end">มิถุนายน</span><span class="ps-2 tx-muted">' . number_format($Month_Revenue_Jun, 2) . "%" . '</span></p>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated ht-5 bg-success" style="width: ' . number_format($Month_Revenue_Jun, 2) . "%" . '"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex mg-b-15">
                <div class="me-2">
                    <span class="avatar avatar-sm radius-4 " style=" background-color:#FF14932e;  color:#FF1493;"><i class="fas fa-sack-dollar"></i></span>
                </div>
                <div class="flex-1">
                    <div class="flex-between mb-2">
                        <p class="mb-0"><span class="pe-2 border-end">กรกฏาคม</span><span class="ps-2 tx-muted">' . number_format($Month_Revenue_Jul, 2) . "%" . '</span></p>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated ht-5 bg-success" style="width: ' . number_format($Month_Revenue_Jul, 2) . "%" . '"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex mg-b-15">
                <div class="me-2">
                    <span class="avatar avatar-sm radius-4 " style=" background-color:#8B45132e;  color:#8B4513;"><i class="fas fa-sack-dollar"></i></span>
                </div>
                <div class="flex-1">
                    <div class="flex-between mb-2">
                        <p class="mb-0"><span class="pe-2 border-end">สิงหาคม</span><span class="ps-2 tx-muted">' . number_format($Month_Revenue_Aug, 2) . "%" . '</span></p>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated ht-5 bg-success" style="width: ' . number_format($Month_Revenue_Aug, 2) . "%" . '"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex mg-b-15">
                <div class="me-2">
                    <span class="avatar avatar-sm radius-4 " style=" background-color:#1919702e;  color: #191970;"><i class="fas fa-sack-dollar"></i></span>
                </div>
                <div class="flex-1">
                    <div class="flex-between mb-2">
                        <p class="mb-0"><span class="pe-2 border-end">กันยายน</span><span class="ps-2 tx-muted">' . number_format($Month_Revenue_Sep, 2) . "%" . '</span></p>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated ht-5 bg-success" style="width: ' . number_format($Month_Revenue_Sep, 2) . "%" . '"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex mg-b-15">
                <div class="me-2">
                    <span class="avatar avatar-sm radius-4 " style=" background-color:#2F4F4F2e;  color: #2F4F4F;"><i class="fas fa-sack-dollar"></i></span>
                </div>
                <div class="flex-1">
                    <div class="flex-between mb-2">
                        <p class="mb-0"><span class="pe-2 border-end">ตุลาคม</span><span class="ps-2 tx-muted">' . number_format($Month_Revenue_Oct, 2) . "%" . '</span></p>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated ht-5 bg-success" style="width: ' . number_format($Month_Revenue_Oct, 2) . "%" . '"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex mg-b-15">
                <div class="me-2">
                    <span class="avatar avatar-sm radius-4 " style=" background-color:#FFD7002e;  color: #FFD700;"><i class="fas fa-sack-dollar"></i></span>
                </div>
                <div class="flex-1">
                    <div class="flex-between mb-2">
                        <p class="mb-0"><span class="pe-2 border-end">พฤศจิกายน</span><span class="ps-2 tx-muted">' . number_format($Month_Revenue_Nov, 2) . "%" . '</span></p>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated ht-5 bg-success" style="width: ' . number_format($Month_Revenue_Nov, 2) . "%" . '"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex mg-b-15">
                <div class="me-2">
                    <span class="avatar avatar-sm radius-4 " style=" background-color:#DA70D62e;  color: #DA70D6;"><i class="fas fa-sack-dollar"></i></span>
                </div>
                <div class="flex-1">
                    <div class="flex-between mb-2">
                        <p class="mb-0"><span class="pe-2 border-end">ธันวาคม</span><span class="ps-2 tx-muted">' . number_format($Month_Revenue_Dec, 2) . "%" . '</span></p>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-striped progress-bar-animated ht-5 bg-success" style="width: ' . number_format($Month_Revenue_Dec, 2) . "%" . '"></div>
                    </div>
                </div>
            </div>
                    ';

            $response['data'] = $html;

            $status = 200;
            $response['success'] = 1;
            $response['message'] = '';

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    public function ajaxSummarizeReportLoan($data)
    {
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            $OverduePayments = $this->LoanModel->getOverdueListPayments($data);
            $PaymentMonths = $this->LoanModel->getListPaymentMonths($data);

            $datas = $this->LoanModel->getAllDataLoan();

            $Month_Jan_Overdue_Payment = 0;
            $Month_Feb_Overdue_Payment = 0;
            $Month_Mar_Overdue_Payment = 0;
            $Month_Apr_Overdue_Payment = 0;
            $Month_May_Overdue_Payment = 0;
            $Month_Jun_Overdue_Payment = 0;
            $Month_Jul_Overdue_Payment = 0;
            $Month_Aug_Overdue_Payment = 0;
            $Month_Sep_Overdue_Payment = 0;
            $Month_Oct_Overdue_Payment = 0;
            $Month_Nov_Overdue_Payment = 0;
            $Month_Dec_Overdue_Payment = 0;

            foreach ($OverduePayments as $OverduePayment) {
                if ($data === date('Y')) {
                    if ($OverduePayment->overdue_payment <= date('m')) {
                        switch ($OverduePayment->overdue_payment) {
                            case "1":
                                $Month_Jan_Overdue_Payment = $Month_Jan_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "2":

                                $Month_Feb_Overdue_Payment = $Month_Feb_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "3":
                                $Month_Mar_Overdue_Payment = $Month_Mar_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "4":
                                $Month_Apr_Overdue_Payment = $Month_Apr_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "5":
                                $Month_May_Overdue_Payment = $Month_May_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "6":
                                $Month_Jun_Overdue_Payment = $Month_Jun_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "7":
                                $Month_Jul_Overdue_Payment = $Month_Jul_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "8":
                                $Month_Aug_Overdue_Payment = $Month_Aug_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "9":
                                $Month_Sep_Overdue_Payment = $Month_Sep_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "10":
                                $Month_Oct_Overdue_Payment = $Month_Oct_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "11":
                                $Month_Nov_Overdue_Payment = $Month_Nov_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                            case "12":
                                $Month_Dec_Overdue_Payment = $Month_Dec_Overdue_Payment + $OverduePayment->loan_payment_amount;
                                break;
                        }
                    }
                } elseif ($data < date('Y')) {
                    switch ($OverduePayment->overdue_payment) {
                        case "1":
                            $Month_Jan_Overdue_Payment = $Month_Jan_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "2":
                            $Month_Feb_Overdue_Payment = $Month_Feb_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "3":
                            $Month_Mar_Overdue_Payment = $Month_Mar_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "4":
                            $Month_Apr_Overdue_Payment = $Month_Apr_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "5":
                            $Month_May_Overdue_Payment = $Month_May_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "6":
                            $Month_Jun_Overdue_Payment = $Month_Jun_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "7":
                            $Month_Jul_Overdue_Payment = $Month_Jul_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "8":
                            $Month_Aug_Overdue_Payment = $Month_Aug_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "9":
                            $Month_Sep_Overdue_Payment = $Month_Sep_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "10":
                            $Month_Oct_Overdue_Payment = $Month_Oct_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "11":
                            $Month_Nov_Overdue_Payment = $Month_Nov_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "12":
                            $Month_Dec_Overdue_Payment = $Month_Dec_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                    }
                }
            }

            $Month_Jan_Payment_Month = 0;
            $Month_Feb_Payment_Month = 0;
            $Month_Mar_Payment_Month = 0;
            $Month_Apr_Payment_Month = 0;
            $Month_May_Payment_Month = 0;
            $Month_Jun_Payment_Month = 0;
            $Month_Jul_Payment_Month = 0;
            $Month_Aug_Payment_Month = 0;
            $Month_Sep_Payment_Month = 0;
            $Month_Oct_Payment_Month = 0;
            $Month_Nov_Payment_Month = 0;
            $Month_Dec_Payment_Month = 0;

            foreach ($PaymentMonths as $PaymentMonth) {
                if ($data === date('Y')) {
                    if ($PaymentMonth->overdue_payment <= date('m')) {
                        switch ($PaymentMonth->overdue_payment) {
                            case "1":
                                $Month_Jan_Payment_Month = $Month_Jan_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "2":
                                $Month_Feb_Payment_Month = $Month_Feb_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "3":
                                $Month_Mar_Payment_Month = $Month_Mar_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "4":
                                $Month_Apr_Payment_Month = $Month_Apr_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "5":
                                $Month_May_Payment_Month = $Month_May_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "6":
                                $Month_Jun_Payment_Month = $Month_Jun_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "7":
                                $Month_Jul_Payment_Month = $Month_Jul_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "8":
                                $Month_Aug_Payment_Month = $Month_Aug_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "9":
                                $Month_Sep_Payment_Month = $Month_Sep_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "10":
                                $Month_Oct_Payment_Month = $Month_Oct_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "11":
                                $Month_Nov_Payment_Month = $Month_Nov_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                            case "12":
                                $Month_Dec_Payment_Month = $Month_Dec_Payment_Month + $PaymentMonth->loan_payment_amount;
                                break;
                        }
                    }
                } elseif ($data < date('Y')) {
                    switch ($PaymentMonth->overdue_payment) {
                        case "1":
                            $Month_Jan_Payment_Month = $Month_Jan_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "2":
                            $Month_Feb_Payment_Month = $Month_Feb_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "3":
                            $Month_Mar_Payment_Month = $Month_Mar_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "4":
                            $Month_Apr_Payment_Month = $Month_Apr_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "5":
                            $Month_May_Payment_Month = $Month_May_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "6":
                            $Month_Jun_Payment_Month = $Month_Jun_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "7":
                            $Month_Jul_Payment_Month = $Month_Jul_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "8":
                            $Month_Aug_Payment_Month = $Month_Aug_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "9":
                            $Month_Sep_Payment_Month = $Month_Sep_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "10":
                            $Month_Oct_Payment_Month = $Month_Oct_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "11":
                            $Month_Nov_Payment_Month = $Month_Nov_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "12":
                            $Month_Dec_Payment_Month = $Month_Dec_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                    }
                }
            }
            $Month_Jan_Diff_Payment_Month = 0;
            $Month_Feb_Diff_Payment_Month = 0;
            $Month_Mar_Diff_Payment_Month = 0;
            $Month_Apr_Diff_Payment_Month = 0;
            $Month_May_Diff_Payment_Month = 0;
            $Month_Jun_Diff_Payment_Month = 0;
            $Month_Jul_Diff_Payment_Month = 0;
            $Month_Aug_Diff_Payment_Month = 0;
            $Month_Sep_Diff_Payment_Month = 0;
            $Month_Oct_Diff_Payment_Month = 0;
            $Month_Nov_Diff_Payment_Month = 0;
            $Month_Dec_Diff_Payment_Month = 0;

            $Month_Jan_Diff_Payment_Month = $Month_Jan_Payment_Month - $Month_Jan_Overdue_Payment;
            $Month_Feb_Diff_Payment_Month = $Month_Feb_Payment_Month - $Month_Feb_Overdue_Payment;
            $Month_Mar_Diff_Payment_Month = $Month_Mar_Payment_Month - $Month_Mar_Overdue_Payment;
            $Month_Apr_Diff_Payment_Month = $Month_Apr_Payment_Month - $Month_Apr_Overdue_Payment;
            $Month_May_Diff_Payment_Month = $Month_May_Payment_Month - $Month_May_Overdue_Payment;
            $Month_Jun_Diff_Payment_Month = $Month_Jun_Payment_Month - $Month_Jun_Overdue_Payment;
            $Month_Jul_Diff_Payment_Month = $Month_Jul_Payment_Month - $Month_Jul_Overdue_Payment;
            $Month_Aug_Diff_Payment_Month = $Month_Aug_Payment_Month - $Month_Aug_Overdue_Payment;
            $Month_Sep_Diff_Payment_Month = $Month_Sep_Payment_Month - $Month_Sep_Overdue_Payment;
            $Month_Oct_Diff_Payment_Month = $Month_Oct_Payment_Month - $Month_Oct_Overdue_Payment;
            $Month_Nov_Diff_Payment_Month = $Month_Nov_Payment_Month - $Month_Nov_Overdue_Payment;
            $Month_Dec_Diff_Payment_Month = $Month_Dec_Payment_Month - $Month_Dec_Overdue_Payment;

            $Month_max = "";
            $Month_min = "";
            $MAX = 0;
            $i = 0;

            if ($Month_Jan_Diff_Payment_Month >= $MAX and $Month_Jan_Diff_Payment_Month != 0) {
                $i++;
                $MAX = $Month_Jan_Diff_Payment_Month;
                $Month_max = "มกราคม";
            }
            if ($Month_Feb_Diff_Payment_Month >= $MAX and $Month_Feb_Diff_Payment_Month != 0) {
                $i++;
                $MAX = $Month_Feb_Diff_Payment_Month;
                $Month_max = "กุมภาพันธ์";
            }
            if ($Month_Mar_Diff_Payment_Month >= $MAX and $Month_Mar_Diff_Payment_Month != 0) {
                $i++;
                $MAX = $Month_Mar_Diff_Payment_Month;
                $Month_max = "มีนาคม";
            }
            if ($Month_Apr_Diff_Payment_Month >= $MAX and $Month_Apr_Diff_Payment_Month != 0) {
                $i++;
                $MAX = $Month_Apr_Diff_Payment_Month;
                $Month_max = "เมษายน";
            }
            if ($Month_May_Diff_Payment_Month >= $MAX and $Month_May_Diff_Payment_Month != 0) {
                $i++;
                $MAX = $Month_May_Diff_Payment_Month;
                $Month_max = "พฤษภาคม";
            }
            if ($Month_Jun_Diff_Payment_Month >= $MAX and $Month_Jun_Diff_Payment_Month != 0) {
                $i++;
                $MAX = $Month_Jun_Diff_Payment_Month;
                $Month_max = "มิถุนายน";
            }
            if ($Month_Jul_Diff_Payment_Month >= $MAX and $Month_Jul_Diff_Payment_Month != 0) {
                $i++;
                $MAX = $Month_Jul_Diff_Payment_Month;
                $Month_max = "กรกฎาคม";
            }
            if ($Month_Aug_Diff_Payment_Month >= $MAX and $Month_Aug_Diff_Payment_Month != 0) {
                $i++;
                $MAX = $Month_Aug_Diff_Payment_Month;
                $Month_max = "สิงหาคม";
            }
            if ($Month_Sep_Diff_Payment_Month >= $MAX and $Month_Sep_Diff_Payment_Month != 0) {
                $i++;
                $MAX = $Month_Sep_Diff_Payment_Month;
                $Month_max = "กันยายน";
            }
            if ($Month_Oct_Diff_Payment_Month >= $MAX and $Month_Oct_Diff_Payment_Month != 0) {
                $i++;
                $MAX = $Month_Oct_Diff_Payment_Month;
                $Month_max = "ตุลาคม";
            }
            if ($Month_Nov_Diff_Payment_Month >= $MAX and $Month_Nov_Diff_Payment_Month != 0) {
                $i++;
                $MAX = $Month_Nov_Diff_Payment_Month;
                $Month_max = "พฤศจิกายน";
            }
            if ($Month_Dec_Diff_Payment_Month >= $MAX and $Month_Dec_Diff_Payment_Month != 0) {
                $i++;
                $MAX = $Month_Dec_Diff_Payment_Month;
                $Month_max = "ธันวาคม";
            }

            $MIN = $MAX;

            if ($Month_Jan_Diff_Payment_Month <= $MIN and $Month_Jan_Diff_Payment_Month != 0) {
                $MIN = $Month_Jan_Diff_Payment_Month;
                $Month_min = "มกราคม";
            }
            if ($Month_Feb_Diff_Payment_Month <= $MIN and $Month_Feb_Diff_Payment_Month != 0) {
                $MIN = $Month_Feb_Diff_Payment_Month;
                $Month_min = "กุมภาพันธ์";
            }
            if ($Month_Mar_Diff_Payment_Month <= $MIN and $Month_Mar_Diff_Payment_Month != 0) {
                $MIN = $Month_Mar_Diff_Payment_Month;
                $Month_min = "มีนาคม";
            }
            if ($Month_Apr_Diff_Payment_Month <= $MIN and $Month_Apr_Diff_Payment_Month != 0) {
                $MIN = $Month_Apr_Diff_Payment_Month;
                $Month_min = "เมษายน";
            }
            if ($Month_May_Diff_Payment_Month <= $MIN and $Month_May_Diff_Payment_Month != 0) {
                $MIN = $Month_May_Diff_Payment_Month;
                $Month_min = "พฤษภาคม";
            }
            if ($Month_Jun_Diff_Payment_Month <= $MIN and $Month_Jun_Diff_Payment_Month != 0) {
                $MIN = $Month_Jun_Diff_Payment_Month;
                $Month_min = "มิถุนายน";
            }
            if ($Month_Jul_Diff_Payment_Month <= $MIN and $Month_Jul_Diff_Payment_Month != 0) {
                $MIN = $Month_Jul_Diff_Payment_Month;
                $Month_min = "กรกฎาคม";
            }
            if ($Month_Aug_Diff_Payment_Month <= $MIN and $Month_Aug_Diff_Payment_Month != 0) {
                $MIN = $Month_Aug_Diff_Payment_Month;
                $Month_min = "สิงหาคม";
            }
            if ($Month_Sep_Diff_Payment_Month <= $MIN and $Month_Sep_Diff_Payment_Month != 0) {
                $MIN = $Month_Sep_Diff_Payment_Month;
                $Month_min = "กันยายน";
            }
            if ($Month_Oct_Diff_Payment_Month <= $MIN and $Month_Oct_Diff_Payment_Month != 0) {
                $MIN = $Month_Oct_Diff_Payment_Month;
                $Month_min = "ตุลาคม";
            }
            if ($Month_Nov_Diff_Payment_Month <= $MIN and $Month_Nov_Diff_Payment_Month != 0) {
                $MIN = $Month_Nov_Diff_Payment_Month;
                $Month_min = "พฤศจิกายน";
            }
            if ($Month_Dec_Diff_Payment_Month <= $MIN and $Month_Dec_Diff_Payment_Month != 0) {
                $MIN = $Month_Dec_Diff_Payment_Month;
                $Month_min = "ธันวาคม";
            }

            $AVERAGE = 0;
            $Sum_price = 0;

            $Sum_price =  $Month_Jan_Diff_Payment_Month + $Month_Feb_Diff_Payment_Month + $Month_Mar_Diff_Payment_Month + $Month_Apr_Diff_Payment_Month + $Month_May_Diff_Payment_Month +
                $Month_Jun_Diff_Payment_Month + $Month_Jul_Diff_Payment_Month + $Month_Aug_Diff_Payment_Month + $Month_Sep_Diff_Payment_Month + $Month_Oct_Diff_Payment_Month + $Month_Nov_Diff_Payment_Month + $Month_Dec_Diff_Payment_Month;

            if ($data != date('Y')) {
                $AVERAGE = $Sum_price / 12;
            } elseif ($data == date('Y')) {
                $AVERAGE = $Sum_price / date('m');
            } else {
                $AVERAGE = 0;
            }

            $loan_summary_no_vat = 0;
            foreach ($datas as $data) {
                if ($data->loan_summary_no_vat != '') {
                    $loan_summary_no_vat = $loan_summary_no_vat + $data->loan_summary_no_vat;
                }
            }
            $percent_profit = 0;
            $percent_profit = $AVERAGE / $loan_summary_no_vat * 100;

            if ($percent_profit < 0) {
                $percent_Color = "tx-danger";
                $percent_profit = number_format($percent_profit, 2) . '%';
            } elseif ($percent_profit > 0) {
                $percent_Color = "tx-success";
                $percent_profit = '+' . number_format($percent_profit, 2) . '%';
            } else {
                $percent_Color = "";
                $percent_profit = number_format($percent_profit, 2) . '%';
            }


            $html =
                '<div class="row">
                    <div class="col-xl-3 col-sm-4 col-12 p-0">
                        <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                            <p class="mb-0 font-weight-semibold tx-18">กำไรสูงสุด</p>
                            <div class="mt-2">
                                <span class="mb-0 font-weight-semibold tx-15">' . "เดือน" . $Month_max . " " . number_format($MAX, 2) . '</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-4 col-12 p-0">
                        <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                            <p class="mb-0 font-weight-semibold tx-18">กำไรน้อยสุด</p>
                            <div class="mt-2">
                                <span class="mb-0 font-weight-semibold tx-15">' . "เดือน" . $Month_min . " " . number_format($MIN, 2) . '</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 col-12 p-0">
                        <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                            <p class="mb-0 font-weight-semibold tx-18">เฉลี่ยต่อเดือน</p>
                            <div class="mt-2">
                                <span class="mb-0 font-weight-semibold tx-15">' . number_format($AVERAGE, 2) . '</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 col-12 p-0">
                        <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                            <p class="mb-0 font-weight-semibold tx-18">กำไรทั้งหมด</p>
                            <div class="mt-2">
                                <span class="mb-0 font-weight-semibold tx-15">' . number_format($Sum_price, 2) . '</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 col-12 p-0">
                        <div class="tx-center pd-y-7 pd-sm-y-0-f">
                            <p class="mb-0 font-weight-semibold tx-18">เปอร์เซ็นต์กำไร</p>
                            <div class="mt-2">
                                <span class="mb-0 font-weight-semibold tx-15 ' . $percent_Color . '">' . $percent_profit . '</span>
                            </div>
                        </div>
                    </div>
                </div>
                ';


            $response['data'] = $html;

            $status = 200;
            $response['success'] = 1;
            $response['message'] = '';

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    public function ajaxDataReportLoanMonth($data)
    {

        $OverduePayments = $this->LoanModel->getOverdueListPayments($data);
        $PaymentMonths = $this->LoanModel->getListPaymentMonths($data);

        // เดือน1
        $Month_Revenue_Jan = 0;
        // เดือน2
        $Month_Revenue_Feb = 0;
        // เดือน3
        $Month_Revenue_Mar = 0;
        // เดือน4
        $Month_Revenue_Apr = 0;
        // เดือน5
        $Month_Revenue_May = 0;
        // เดือน6
        $Month_Revenue_Jun = 0;
        // เดือน7
        $Month_Revenue_Jul = 0;
        // เดือน8
        $Month_Revenue_Aug = 0;
        // เดือน9
        $Month_Revenue_Sep = 0;
        // เดือน10
        $Month_Revenue_Oct = 0;
        // เดือน11
        $Month_Revenue_Nov = 0;
        // เดือน12
        $Month_Revenue_Dec = 0;

        $Month_Jan_Overdue_Payment = 0;
        $Month_Feb_Overdue_Payment = 0;
        $Month_Mar_Overdue_Payment = 0;
        $Month_Apr_Overdue_Payment = 0;
        $Month_May_Overdue_Payment = 0;
        $Month_Jun_Overdue_Payment = 0;
        $Month_Jul_Overdue_Payment = 0;
        $Month_Aug_Overdue_Payment = 0;
        $Month_Sep_Overdue_Payment = 0;
        $Month_Oct_Overdue_Payment = 0;
        $Month_Nov_Overdue_Payment = 0;
        $Month_Dec_Overdue_Payment = 0;

        foreach ($OverduePayments as $OverduePayment) {
            if ($data === date('Y')) {
                if ($OverduePayment->overdue_payment <= date('m')) {
                    switch ($OverduePayment->overdue_payment) {
                        case "1":
                            $Month_Jan_Overdue_Payment = $Month_Jan_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "2":

                            $Month_Feb_Overdue_Payment = $Month_Feb_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "3":
                            $Month_Mar_Overdue_Payment = $Month_Mar_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "4":
                            $Month_Apr_Overdue_Payment = $Month_Apr_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "5":
                            $Month_May_Overdue_Payment = $Month_May_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "6":
                            $Month_Jun_Overdue_Payment = $Month_Jun_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "7":
                            $Month_Jul_Overdue_Payment = $Month_Jul_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "8":
                            $Month_Aug_Overdue_Payment = $Month_Aug_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "9":
                            $Month_Sep_Overdue_Payment = $Month_Sep_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "10":
                            $Month_Oct_Overdue_Payment = $Month_Oct_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "11":
                            $Month_Nov_Overdue_Payment = $Month_Nov_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                        case "12":
                            $Month_Dec_Overdue_Payment = $Month_Dec_Overdue_Payment + $OverduePayment->loan_payment_amount;
                            break;
                    }
                }
            } elseif ($data < date('Y')) {
                switch ($OverduePayment->overdue_payment) {
                    case "1":
                        $Month_Jan_Overdue_Payment = $Month_Jan_Overdue_Payment + $OverduePayment->loan_payment_amount;
                        break;
                    case "2":
                        $Month_Feb_Overdue_Payment = $Month_Feb_Overdue_Payment + $OverduePayment->loan_payment_amount;
                        break;
                    case "3":
                        $Month_Mar_Overdue_Payment = $Month_Mar_Overdue_Payment + $OverduePayment->loan_payment_amount;
                        break;
                    case "4":
                        $Month_Apr_Overdue_Payment = $Month_Apr_Overdue_Payment + $OverduePayment->loan_payment_amount;
                        break;
                    case "5":
                        $Month_May_Overdue_Payment = $Month_May_Overdue_Payment + $OverduePayment->loan_payment_amount;
                        break;
                    case "6":
                        $Month_Jun_Overdue_Payment = $Month_Jun_Overdue_Payment + $OverduePayment->loan_payment_amount;
                        break;
                    case "7":
                        $Month_Jul_Overdue_Payment = $Month_Jul_Overdue_Payment + $OverduePayment->loan_payment_amount;
                        break;
                    case "8":
                        $Month_Aug_Overdue_Payment = $Month_Aug_Overdue_Payment + $OverduePayment->loan_payment_amount;
                        break;
                    case "9":
                        $Month_Sep_Overdue_Payment = $Month_Sep_Overdue_Payment + $OverduePayment->loan_payment_amount;
                        break;
                    case "10":
                        $Month_Oct_Overdue_Payment = $Month_Oct_Overdue_Payment + $OverduePayment->loan_payment_amount;
                        break;
                    case "11":
                        $Month_Nov_Overdue_Payment = $Month_Nov_Overdue_Payment + $OverduePayment->loan_payment_amount;
                        break;
                    case "12":
                        $Month_Dec_Overdue_Payment = $Month_Dec_Overdue_Payment + $OverduePayment->loan_payment_amount;
                        break;
                }
            }
        }

        $Month_Jan_Payment_Month = 0;
        $Month_Feb_Payment_Month = 0;
        $Month_Mar_Payment_Month = 0;
        $Month_Apr_Payment_Month = 0;
        $Month_May_Payment_Month = 0;
        $Month_Jun_Payment_Month = 0;
        $Month_Jul_Payment_Month = 0;
        $Month_Aug_Payment_Month = 0;
        $Month_Sep_Payment_Month = 0;
        $Month_Oct_Payment_Month = 0;
        $Month_Nov_Payment_Month = 0;
        $Month_Dec_Payment_Month = 0;

        foreach ($PaymentMonths as $PaymentMonth) {
            if ($data === date('Y')) {
                if ($PaymentMonth->overdue_payment <= date('m')) {
                    switch ($PaymentMonth->overdue_payment) {
                        case "1":
                            $Month_Jan_Payment_Month = $Month_Jan_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "2":
                            $Month_Feb_Payment_Month = $Month_Feb_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "3":
                            $Month_Mar_Payment_Month = $Month_Mar_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "4":
                            $Month_Apr_Payment_Month = $Month_Apr_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "5":
                            $Month_May_Payment_Month = $Month_May_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "6":
                            $Month_Jun_Payment_Month = $Month_Jun_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "7":
                            $Month_Jul_Payment_Month = $Month_Jul_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "8":
                            $Month_Aug_Payment_Month = $Month_Aug_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "9":
                            $Month_Sep_Payment_Month = $Month_Sep_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "10":
                            $Month_Oct_Payment_Month = $Month_Oct_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "11":
                            $Month_Nov_Payment_Month = $Month_Nov_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                        case "12":
                            $Month_Dec_Payment_Month = $Month_Dec_Payment_Month + $PaymentMonth->loan_payment_amount;
                            break;
                    }
                }
            } elseif ($data < date('Y')) {
                switch ($PaymentMonth->overdue_payment) {
                    case "1":
                        $Month_Jan_Payment_Month = $Month_Jan_Payment_Month + $PaymentMonth->loan_payment_amount;
                        break;
                    case "2":
                        $Month_Feb_Payment_Month = $Month_Feb_Payment_Month + $PaymentMonth->loan_payment_amount;
                        break;
                    case "3":
                        $Month_Mar_Payment_Month = $Month_Mar_Payment_Month + $PaymentMonth->loan_payment_amount;
                        break;
                    case "4":
                        $Month_Apr_Payment_Month = $Month_Apr_Payment_Month + $PaymentMonth->loan_payment_amount;
                        break;
                    case "5":
                        $Month_May_Payment_Month = $Month_May_Payment_Month + $PaymentMonth->loan_payment_amount;
                        break;
                    case "6":
                        $Month_Jun_Payment_Month = $Month_Jun_Payment_Month + $PaymentMonth->loan_payment_amount;
                        break;
                    case "7":
                        $Month_Jul_Payment_Month = $Month_Jul_Payment_Month + $PaymentMonth->loan_payment_amount;
                        break;
                    case "8":
                        $Month_Aug_Payment_Month = $Month_Aug_Payment_Month + $PaymentMonth->loan_payment_amount;
                        break;
                    case "9":
                        $Month_Sep_Payment_Month = $Month_Sep_Payment_Month + $PaymentMonth->loan_payment_amount;
                        break;
                    case "10":
                        $Month_Oct_Payment_Month = $Month_Oct_Payment_Month + $PaymentMonth->loan_payment_amount;
                        break;
                    case "11":
                        $Month_Nov_Payment_Month = $Month_Nov_Payment_Month + $PaymentMonth->loan_payment_amount;
                        break;
                    case "12":
                        $Month_Dec_Payment_Month = $Month_Dec_Payment_Month + $PaymentMonth->loan_payment_amount;
                        break;
                }
            }
        }
        $Month_Jan_Diff_Payment_Month = 0;
        $Month_Feb_Diff_Payment_Month = 0;
        $Month_Mar_Diff_Payment_Month = 0;
        $Month_Apr_Diff_Payment_Month = 0;
        $Month_May_Diff_Payment_Month = 0;
        $Month_Jun_Diff_Payment_Month = 0;
        $Month_Jul_Diff_Payment_Month = 0;
        $Month_Aug_Diff_Payment_Month = 0;
        $Month_Sep_Diff_Payment_Month = 0;
        $Month_Oct_Diff_Payment_Month = 0;
        $Month_Nov_Diff_Payment_Month = 0;
        $Month_Dec_Diff_Payment_Month = 0;

        $Month_Jan_Diff_Payment_Month = $Month_Jan_Payment_Month - $Month_Jan_Overdue_Payment;
        $Month_Feb_Diff_Payment_Month = $Month_Feb_Payment_Month - $Month_Feb_Overdue_Payment;
        $Month_Mar_Diff_Payment_Month = $Month_Mar_Payment_Month - $Month_Mar_Overdue_Payment;
        $Month_Apr_Diff_Payment_Month = $Month_Apr_Payment_Month - $Month_Apr_Overdue_Payment;
        $Month_May_Diff_Payment_Month = $Month_May_Payment_Month - $Month_May_Overdue_Payment;
        $Month_Jun_Diff_Payment_Month = $Month_Jun_Payment_Month - $Month_Jun_Overdue_Payment;
        $Month_Jul_Diff_Payment_Month = $Month_Jul_Payment_Month - $Month_Jul_Overdue_Payment;
        $Month_Aug_Diff_Payment_Month = $Month_Aug_Payment_Month - $Month_Aug_Overdue_Payment;
        $Month_Sep_Diff_Payment_Month = $Month_Sep_Payment_Month - $Month_Sep_Overdue_Payment;
        $Month_Oct_Diff_Payment_Month = $Month_Oct_Payment_Month - $Month_Oct_Overdue_Payment;
        $Month_Nov_Diff_Payment_Month = $Month_Nov_Payment_Month - $Month_Nov_Overdue_Payment;
        $Month_Dec_Diff_Payment_Month = $Month_Dec_Payment_Month - $Month_Dec_Overdue_Payment;

        $Sum_price =  $Month_Jan_Diff_Payment_Month + $Month_Feb_Diff_Payment_Month + $Month_Mar_Diff_Payment_Month + $Month_Apr_Diff_Payment_Month + $Month_May_Diff_Payment_Month +
        $Month_Jun_Diff_Payment_Month + $Month_Jul_Diff_Payment_Month + $Month_Aug_Diff_Payment_Month + $Month_Sep_Diff_Payment_Month + $Month_Oct_Diff_Payment_Month + $Month_Nov_Diff_Payment_Month + $Month_Dec_Diff_Payment_Month;

        $Month_Revenue_Jan = ($Month_Jan_Diff_Payment_Month / $Sum_price) * 100;
        $Month_Revenue_Feb = ($Month_Feb_Diff_Payment_Month / $Sum_price) * 100;
        $Month_Revenue_Mar = ($Month_Mar_Diff_Payment_Month / $Sum_price) * 100;
        $Month_Revenue_Apr = ($Month_Apr_Diff_Payment_Month / $Sum_price) * 100;
        $Month_Revenue_May = ($Month_May_Diff_Payment_Month / $Sum_price) * 100;
        $Month_Revenue_Jun = ($Month_Jun_Diff_Payment_Month / $Sum_price) * 100;
        $Month_Revenue_Jul = ($Month_Jul_Diff_Payment_Month / $Sum_price) * 100;
        $Month_Revenue_Aug = ($Month_Aug_Diff_Payment_Month / $Sum_price) * 100;
        $Month_Revenue_Sep = ($Month_Sep_Diff_Payment_Month / $Sum_price) * 100;
        $Month_Revenue_Oct = ($Month_Oct_Diff_Payment_Month / $Sum_price) * 100;
        $Month_Revenue_Nov = ($Month_Nov_Diff_Payment_Month / $Sum_price) * 100;
        $Month_Revenue_Dec = ($Month_Dec_Diff_Payment_Month / $Sum_price) * 100;
        // $Month_Revenue = [
        //     10,0,0,0,0,90,0,0,0,0,0,0
        // ];
        $Month_Revenue = [
            round($Month_Revenue_Jan, 2), round($Month_Revenue_Feb, 2), round($Month_Revenue_Mar, 2), round($Month_Revenue_Apr, 2), round($Month_Revenue_May, 2), round($Month_Revenue_Jun, 2),
            round($Month_Revenue_Jul, 2), round($Month_Revenue_Aug, 2), round($Month_Revenue_Sep, 2), round($Month_Revenue_Oct, 2), round($Month_Revenue_Nov, 2), round($Month_Revenue_Dec, 2)
        ];

        $json_data = array(
            "Month_Revenue" => $Month_Revenue   // total data array
        );

        return $this->response
            ->setStatusCode(200)
            ->setContentType('application/json')
            ->setJSON($json_data);
    }

    public function insertDetailPiture($loanCode = null)
    {
        $buffer_datetime = date("Y-m-d H:i:s");

        $s3Client = new S3Client([
            'version' => 'latest',
            'region'  => $this->s3_region,
            'endpoint' => $this->s3_endpoint,
            'use_path_style_endpoint' => false,
            'credentials' => [
                'key'    => $this->s3_key,
                'secret' => $this->s3_secret_key
            ]
        ]);

        //other file 
        $other_files = $_FILES['file_picture_other_update'];
        if ($other_files["name"][0] != null) {
            $other_img_desc = reArrayFiles($other_files);
            foreach ($other_img_desc as $val) {
                $fileName_other = $loanCode . "_OTHER_" . generateRandomString() . "." . pathinfo($val['name'], PATHINFO_EXTENSION);
                move_uploaded_file($val['tmp_name'], './uploads/loan_payment_img/' . $fileName_other);
                $file_Path_other = 'uploads/loan_payment_img/' . $fileName_other;

                $result_other = $s3Client->putObject([
                    'Bucket' => $this->s3_bucket,
                    'Key'    => 'uploads/loan_payment_img/' . $fileName_other,
                    'Body'   => fopen($file_Path_other, 'r'),
                    'ACL'    => 'public-read', // make file 'public'
                ]);

                if ($result_other['ObjectURL'] != "") {
                    unlink('uploads/loan_payment_img/' . $fileName_other);
                }

                $data_other_picture = [
                    'loan_code' => $loanCode,
                    'picture_loan_src' => $fileName_other,
                    'created_at' => $buffer_datetime
                ];

                $add_other_picture = $this->LoanModel->insertOtherPiture($data_other_picture);
            }
        }

        if ($add_other_picture) {
            return $this->response->setJSON([
                'status' => 200,
                'error' => false,
                'message' => 'เพิ่มสำเร็จ'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 200,
                'error' => true,
                'message' => 'เพิ่มไม่สำเร็จ !'
            ]);
        }
    }

    public function fetchOtherPicture($code = null)
    {
        $other_picture_datas = $this->LoanModel->getOtherByCode($code);
        $data = '';

        if ($other_picture_datas) {

            foreach ($other_picture_datas as $other_picture_data) {

                $data .= '
            <div class="col-sm-12 col-md-2" style="text-align: center;">
             <div class="brick">
               <div class="file-attach file-attach-lg">
                    <div class="mb-1 border br-5 pos-relative overflow-hidden">
                    <img src="' . $this->s3_cdn_img . "/uploads/loan_payment_img/" . $other_picture_data->picture_loan_src . '" class="br-5" alt="doc">
                        <div class="btn-list attach-options v-center d-flex flex-column">
                            <a id="' . $other_picture_data->id . '===' . $other_picture_data->picture_loan_src . '" href="javascript:;" onclick="deleteOtherPicture(this.id);" class="btn btn-circle-sm btn-primary flex-center me-0 mb-0"><i class="fe fe-trash tx-12"></i></a>
                            <a href="' . $this->s3_cdn_img . "/uploads/loan_payment_img/" . $other_picture_data->picture_loan_src . '" class="btn btn-circle-sm btn-success flex-center me-0 mb-0 mg-t-3 js-img-viewer-other" data-caption="รูปอื่นๆ" data-id="other"><i class="fe fe-eye tx-12" style=" z-index: 9999;position: fixed;"></i><img src="' . $this->s3_cdn_img . "/uploads/loan_payment_img/" . $other_picture_data->picture_loan_src . '" alt=""  style="z-index: 1;  filter: blur(10px);position: fixed;" /></a>
                        </div>
                    </div> 
                </div>
            </div>
            </div>
                ';
            }

            return $this->response->setJSON([
                'status' => 200,
                'error' => false,
                'message' => $data,
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 200,
                'error' => false,
                'message' => '<a href="' . base_url("/assets/img/no_image.png") . '" class="js-img-viewer" data-caption="รูปอื่นๆ" data-id="other"><img src="' . base_url("/assets/img/no_image.png") . '" alt="" /></a>'

            ]);
        }
    }

    public function deleteOtherPicture($data = null)
    {

        $data_split = explode('===', $data);
        $delete_other_picture = $this->LoanModel->deleteOtherPiture($data_split[0]);

        $s3Client = new S3Client([
            'version' => 'latest',
            'region'  => $this->s3_region,
            'endpoint' => $this->s3_endpoint,
            'use_path_style_endpoint' => false,
            'credentials' => [
                'key'    => $this->s3_key,
                'secret' => $this->s3_secret_key
            ]
        ]);

        if ($delete_other_picture) {
            $result_img_old = $s3Client->deleteObject([
                'Bucket' => $this->s3_bucket,
                'Key'    => 'uploads/loan_payment_img/' . $data_split[1],
            ]);

            return $this->response->setJSON([
                'status' => 200,
                'error' => false,
                'message' => 'แก้ไขสำเร็จ'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 200,
                'error' => true,
                'message' => 'แก้ไขไม่สำเร็จ !'
            ]);
        }
    }

    public function dowloadPictureOther($code)
    {
        $datas = $this->LoanModel->dowloadPictureOther($code);

        return $this->response->setJSON([
            'status' => 200,
            'error' => false,
            'message' => $datas
        ]);
    }

    //updateTargetedMonth
    public function updateTargetedMonth()
    {
        $TargetedModel = new \App\Models\TargetedModel();

        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';
            $id = $this->request->getVar('TargetedId');

            // HANDLE REQUEST
            $update = $TargetedModel->updateTargetedByID($id, [
                'desired_goals_month' => $this->request->getVar('editTargetedMonth'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($update) {

                // pusherEdit
                $pusher = getPusher();
                $pusher->trigger('color_Status', 'event', [
                    'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                    'event' => 'status_Yellow',
                    'title' => session()->get('username') . " : " . 'ทำการแก้ไขเป้าหมายต่อเดือน'
                ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'อัพเดท',
                    'detail' => '[อัพเดท] เป้าหมายต่อเดือน',
                    'ip' => $this->request->getIPAddress()
                ]);
                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'แก้ไข เป้าหมายต่อเดือน สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'แก้ไข เป้าหมายต่อเดือน ไม่สำเร็จ';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    //updateTargeted
    public function updateTargeted()
    {
        $TargetedModel = new \App\Models\TargetedModel();

        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';
            $id = $this->request->getVar('TargetedId');

            // HANDLE REQUEST
            $update = $TargetedModel->updateTargetedByID($id, [
                'desired_goal' => $this->request->getVar('editTargeted'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($update) {

                // pusherEdit
                $pusher = getPusher();
                $pusher->trigger('color_Status', 'event', [
                    'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                    'event' => 'status_Yellow',
                    'title' => session()->get('username') . " : " . 'ทำการแก้ไขเป้าหมาย'
                ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'อัพเดท',
                    'detail' => '[อัพเดท] เป้าหมาย',
                    'ip' => $this->request->getIPAddress()
                ]);
                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'แก้ไข เป้าหมาย สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'แก้ไข เป้าหมาย ไม่สำเร็จ';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }
}
