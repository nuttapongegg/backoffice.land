<?php

namespace App\Controllers;

date_default_timezone_set('Asia/Jakarta');

use App\Controllers\BaseController;
use App\Models\RebuildModel;
use Aws\S3\S3Client;
use \GuzzleHttp\Client;

use App\Models\LoanCustomerModel;
use App\Models\EmployeeModel;
use App\Models\EmployeeLogModel;
use App\Models\LoanModel;
use App\Models\RealInvestmentModel;
use App\Models\SettingLandModel;
use App\Models\DocumentModel;
use App\Models\OverdueStatusModel;

use Smalot\PdfParser\Parser;

class Loan extends BaseController
{
    private LoanCustomerModel $LoanCustomerModel;
    private EmployeeModel $EmployeeModel;
    private EmployeeLogModel $EmployeeLogModel;
    private LoanModel $LoanModel;
    private DocumentModel $DocumentModel;
    private SettingLandModel $SettingLandModel;
    private RealInvestmentModel $realInvestmentModel;
    private $http;

    private string $s3_bucket;
    private string $s3_secret_key;
    private string $s3_key;
    private string $s3_endpoint;
    private string $s3_region;
    private string $s3_cdn_img;

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
        $this->LoanCustomerModel = new LoanCustomerModel();
        $this->EmployeeModel = new EmployeeModel();
        $this->EmployeeLogModel = new EmployeeLogModel();
        $this->LoanModel = new LoanModel();
        $this->DocumentModel = new DocumentModel();
        $this->SettingLandModel = new SettingLandModel();
        $this->realInvestmentModel = new RealInvestmentModel();
        $this->http = new Client();

        // Environment Variables
        $this->s3_bucket = getenv('S3_BUCKET') ?: '';
        $this->s3_secret_key = getenv('SECRET_KEY') ?: '';
        $this->s3_key = getenv('KEY') ?: '';
        $this->s3_endpoint = getenv('ENDPOINT') ?: '';
        $this->s3_region = getenv('REGION') ?: '';
        $this->s3_cdn_img = getenv('CDN_IMG') ?: '';

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
            <script src="' . base_url('/assets/app/js/loan/loan.js?v=' . time()) . '"></script> 
            <script src="' . base_url('/assets/app/js/loan/loan_history.js?v=' . time()) . '"></script> 
        ';

        $data['employee'] = $this->EmployeeModel->getEmployeeByID(session()->get('employeeID'));
        $data['land_accounts'] = $this->SettingLandModel->getSettingLandAll();

        echo view('/app', $data);
    }

    // Get Data Loan กำลังจ่าย
    public function FetchAllLoanOn()
    {
        $date = $this->request->getPost('date');
        $loan_types = $this->request->getPost('loan_types'); // <-- array แน่นอน

        $data_loanOn = $this->LoanModel->getAllDataLoanOn($date, $loan_types);

        return $this->response->setJSON([
            'status' => 200,
            'error' => false,
            'message' => json_encode($data_loanOn)
        ]);
    }

    // Get Data Loan ทั้งหมด
    public function FetchAllLoan() {}

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

        $customer_fullname = $this->request->getPost('fullname') ?? '';
        $customer_phone = $this->request->getPost('phone') ?? '';
        $customer_card_id = $this->request->getPost('card_id') ?? '';
        $customer_email = $this->request->getPost('customer_email') ?? '';
        $customer_birthday = $this->request->getPost('birthday') ?? '';
        $customer_gender = $this->request->getPost('gender') ?? '';
        $customer_address = $this->request->getPost('address') ?? '';

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

        // รับไฟล์จาก request
        $imageFile = $this->request->getFile('imageFile');
        $nameImageFile = null;

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newFileName = $loan_running_code . "_" . $imageFile->getRandomName();
            $imageFile->move('uploads/loan_customer_img', $newFileName);
            $file_Path = 'uploads/loan_customer_img/' . $newFileName;

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
                    'Key'    => 'uploads/loan_customer_img/' . $newFileName,
                    'Body'   => fopen($file_Path, 'r'),
                    'ACL'    => 'public-read',
                ]);

                if (!empty($result['ObjectURL'])) {
                    unlink($file_Path);
                    $nameImageFile = $newFileName; // เก็บชื่อไฟล์ไว้บันทึกใน DB
                }
            } catch (Aws\S3\Exception\S3Exception $e) {
                log_message('error', 'S3 upload error: ' . $e->getMessage());
            }
        }

        if ($customer_card_id != '') {
            $customer_card_id = str_replace('-', '', $customer_card_id);
        }

        if ($customer_birthday != '') {
            $replaceBirthday = str_replace('/', '-', $customer_birthday);
            if ($replaceBirthday !== false) {
                $date = strtotime($replaceBirthday);
                $customer_birthday = date('Y-m-d', $date);
            }
        }

        // เตรียมข้อมูลลูกค้า
        $loan_customer = [
            'loan_code'          => $loan_running_code,
            'customer_fullname'  => $customer_fullname,
            'customer_phone'     => $customer_phone,
            'customer_birthday'  => $customer_birthday,
            'customer_card_id'   => $customer_card_id,
            'customer_email'     => $customer_email,
            'customer_gender'    => $customer_gender,
            'customer_address'   => $customer_address,
            'img'                => $nameImageFile,
        ];

        $this->LoanCustomerModel->insertLoanCustomer($loan_customer);

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
            sleep(1);
        }

        if ($land_account_name != '') {
            $land_account_name = $this->SettingLandModel->getSettingLandByID($account_id);
            if ($charges_process != 0) {
                $price = str_replace(',', '', $charges_process);
                $land_account_cash_charges_process = $land_account_name->land_account_cash + floatval($price);

                $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                    'land_account_cash' => $land_account_cash_charges_process,
                    // 'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $detail = 'ค่าดำเนินการ' . '(' . $loan_running_code . ')';
                $this->SettingLandModel->insertSettingLandReport([
                    'setting_land_id' => $account_id,
                    'setting_land_report_detail' => $detail,
                    'setting_land_report_money' => $charges_process,
                    'setting_land_report_note' => $remark,
                    'setting_land_report_account_balance' => $land_account_cash_charges_process,
                    'employee_id' => session()->get('employeeID'),
                    'employee_name' => session()->get('employee_fullname')
                ]);
            }
            sleep(1);
        }

        if ($land_account_name != '') {
            $land_account_name = $this->SettingLandModel->getSettingLandByID($account_id);
            if ($charges_transfer != 0) {
                $price = str_replace(',', '', $charges_transfer);
                $land_account_cash_charges_transfer = $land_account_name->land_account_cash + floatval($price);

                $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                    'land_account_cash' => $land_account_cash_charges_transfer,
                    // 'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $detail = 'ค่าโอน' . '(' . $loan_running_code . ')';
                $this->SettingLandModel->insertSettingLandReport([
                    'setting_land_id' => $account_id,
                    'setting_land_report_detail' => $detail,
                    'setting_land_report_money' => $charges_transfer,
                    'setting_land_report_note' => $remark,
                    'setting_land_report_account_balance' => $land_account_cash_charges_transfer,
                    'employee_id' => session()->get('employeeID'),
                    'employee_name' => session()->get('employee_fullname')
                ]);
            }
            sleep(1);
        }

        if ($land_account_name != '') {
            $land_account_name = $this->SettingLandModel->getSettingLandByID($account_id);
            if ($charges_etc != 0) {
                $price = str_replace(',', '', $charges_etc);
                $land_account_cash_charges_etc = $land_account_name->land_account_cash + floatval($price);

                $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                    'land_account_cash' => $land_account_cash_charges_etc,
                    // 'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $detail = 'ค่าใช้จ่ายอื่นๆ ' . '(' . $loan_running_code . ')';
                $this->SettingLandModel->insertSettingLandReport([
                    'setting_land_id' => $account_id,
                    'setting_land_report_detail' => $detail,
                    'setting_land_report_money' => $charges_etc,
                    'setting_land_report_note' => $remark,
                    'setting_land_report_account_balance' => $land_account_cash_charges_etc,
                    'employee_id' => session()->get('employeeID'),
                    'employee_name' => session()->get('employee_fullname')
                ]);
            }
        }

        if ($create_loan && ($count_installment == $installment)) {

            return $this->response->setJSON([
                'status' => 200,
                'error' => false,
                'message' => 'เพิ่มรายการสำเร็จ',
                'loan_code' => $loan_running_code,  // ส่ง loan_code
                'latitude' => '',  // เพิ่มข้อมูลที่ต้องการ
                'longitude' => '',  // เพิ่มข้อมูลที่ต้องการ
                'customer_name' => $customer_name,
                'loan_number' => $loan_number,
                'loan_area' => $loan_area,
                'loan_without_vat' => $loan_without_vat
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

        $customer_fullname = $this->request->getPost('fullname') ?? '';
        $customer_phone = $this->request->getPost('phone') ?? '';
        $customer_card_id = $this->request->getPost('card_id') ?? '';
        $customer_email = $this->request->getPost('customer_email') ?? '';
        $customer_birthday = $this->request->getPost('birthday') ?? '';
        $customer_gender = $this->request->getPost('gender') ?? '';
        $customer_address = $this->request->getPost('address') ?? '';

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

        $data = $this->LoanModel->getAllDataLoanByCode($loan_code);

        // ดึงข้อมูลลูกค้าปัจจุบัน
        $dataLoanCustomer = $this->LoanCustomerModel->getCustomerByLoanCode($loan_code);

        // รับไฟล์จาก request
        $imageFile = $this->request->getFile('imageFile');
        $nameImageFile = $dataLoanCustomer->img ?? null; // ค่าเริ่มต้นใช้รูปเก่า

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // ถ้ามีรูปใหม่อัพโหลดเข้ามา
            $newFileName = $loan_code . "_" . $imageFile->getRandomName();
            $imageFile->move('uploads/loan_customer_img', $newFileName);
            $file_Path = 'uploads/loan_customer_img/' . $newFileName;

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
                    'Key'    => 'uploads/loan_customer_img/' . $newFileName,
                    'Body'   => fopen($file_Path, 'r'),
                    'ACL'    => 'public-read',
                ]);

                if (!empty($result['ObjectURL'])) {
                    // ลบไฟล์ local หลังอัพโหลดเสร็จ
                    unlink($file_Path);

                    // ถ้ามีรูปเก่าแล้วอัพเดท → ลบรูปเก่าออกจาก S3 ด้วย
                    if (!empty($dataLoanCustomer->img)) {
                        try {
                            $s3Client->deleteObject([
                                'Bucket' => $this->s3_bucket,
                                'Key'    => 'uploads/loan_customer_img/' . $dataLoanCustomer->img,
                            ]);
                        } catch (Aws\S3\Exception\S3Exception $e) {
                            log_message('error', 'S3 delete error: ' . $e->getMessage());
                        }
                    }

                    // อัพเดทรูปใหม่ในตัวแปร
                    $nameImageFile = $newFileName;
                }
            } catch (Aws\S3\Exception\S3Exception $e) {
                log_message('error', 'S3 upload error: ' . $e->getMessage());
            }
        }

        if ($customer_card_id != '') {
            $customer_card_id = str_replace('-', '', $customer_card_id);
        }

        if ($customer_birthday != '') {
            $replaceBirthday = str_replace('/', '-', $customer_birthday);
            if ($replaceBirthday !== false) {
                $date = strtotime($replaceBirthday);
                $customer_birthday = date('Y-m-d', $date);
            }
        }

        // เตรียมข้อมูลลูกค้า
        $loan_customer = [
            'loan_code'          => $loan_code,
            'customer_fullname'  => $customer_fullname,
            'customer_phone'     => $customer_phone,
            'customer_birthday'  => $customer_birthday,
            'customer_card_id'   => $customer_card_id,
            'customer_email'     => $customer_email,
            'customer_gender'    => $customer_gender,
            'customer_address'   => $customer_address,
            'img'                => $nameImageFile,
        ];

        // ถ้ามีข้อมูลแล้ว → update
        if ($dataLoanCustomer) {
            $loan_customer['updated_at'] = $buffer_datetime;
            $this->LoanCustomerModel->updateLoanCustomerByLoanCode($loan_code, $loan_customer);
        } else {
            // ถ้าไม่มีข้อมูล → insert
            $this->LoanCustomerModel->insertLoanCustomer($loan_customer);
        }

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

        if ($date_to_loan_pay_date) {
            $loan_payment = [
                'loan_payment_date_fix' => $date_to_loan_pay_date,
                'updated_at'  => $buffer_datetime
            ];

            $update_loan_payment = $this->LoanModel->updateLoanPaymentDateFix($loan_code, $loan_payment);
        }

        $land_account_name_old = $this->SettingLandModel->getSettingLandByID($data->land_account_id);
        $charges_process_old = str_replace(',', '', $data->loan_payment_process);
        if ($charges_process_old != $charges_process) {
            if ($charges_process_old != 0) {
                $land_account_cash_charges_process_old = $land_account_name_old->land_account_cash - floatval($charges_process_old);

                $this->SettingLandModel->updateSettingLandByID($land_account_name_old->id, [
                    'land_account_cash' => $land_account_cash_charges_process_old,
                    // 'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $detail = 'แก้ไขค่าดำเนินการ(ลบ)' . '(' . $loan_code . ')';
                $this->SettingLandModel->insertSettingLandReport([
                    'setting_land_id' => $data->land_account_id,
                    'setting_land_report_detail' => $detail,
                    'setting_land_report_money' => $charges_process_old,
                    'setting_land_report_note' => $remark,
                    'setting_land_report_account_balance' => $land_account_cash_charges_process_old,
                    'employee_id' => session()->get('employeeID'),
                    'employee_name' => session()->get('employee_fullname')
                ]);
                sleep(1);
            }

            $land_account_name = $this->SettingLandModel->getSettingLandByID($data->land_account_id);
            $price = str_replace(',', '', $charges_process);
            $land_account_cash_charges_process = $land_account_name->land_account_cash + floatval($price);

            $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                'land_account_cash' => $land_account_cash_charges_process,
                // 'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $detail = 'แก้ไขค่าดำเนินการ(เพิ่มใหม่)' . '(' . $loan_code . ')';
            $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $data->land_account_id,
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $charges_process,
                'setting_land_report_note' => $remark,
                'setting_land_report_account_balance' => $land_account_cash_charges_process,
                'employee_id' => session()->get('employeeID'),
                'employee_name' => session()->get('employee_fullname')
            ]);
            sleep(1);
        }


        $charges_transfer_old = str_replace(',', '', $data->loan_tranfer);
        if ($charges_transfer_old != $charges_transfer) {
            if ($charges_transfer_old != 0) {
                $land_account_name = $this->SettingLandModel->getSettingLandByID($data->land_account_id);
                $land_account_cash_charges_transfer_old = $land_account_name->land_account_cash - floatval($charges_transfer_old);

                $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                    'land_account_cash' => $land_account_cash_charges_transfer_old,
                    // 'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $detail = 'แก้ไขค่าโอน(ลบ)' . '(' . $loan_code . ')';
                $this->SettingLandModel->insertSettingLandReport([
                    'setting_land_id' => $data->land_account_id,
                    'setting_land_report_detail' => $detail,
                    'setting_land_report_money' => $charges_transfer_old,
                    'setting_land_report_note' => $remark,
                    'setting_land_report_account_balance' => $land_account_cash_charges_transfer_old,
                    'employee_id' => session()->get('employeeID'),
                    'employee_name' => session()->get('employee_fullname')
                ]);
                sleep(1);
            }

            $land_account_name = $this->SettingLandModel->getSettingLandByID($data->land_account_id);
            $price = str_replace(',', '', $charges_transfer);
            $land_account_cash_charges_transfer = $land_account_name->land_account_cash + floatval($price);

            $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                'land_account_cash' => $land_account_cash_charges_transfer,
                // 'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $detail = 'แก้ไขค่าโอน(เพิ่มใหม่)' . '(' . $loan_code . ')';
            $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $data->land_account_id,
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $charges_transfer,
                'setting_land_report_note' => $remark,
                'setting_land_report_account_balance' => $land_account_cash_charges_transfer,
                'employee_id' => session()->get('employeeID'),
                'employee_name' => session()->get('employee_fullname')
            ]);
            sleep(1);
        }

        $loan_payment_other_old = str_replace(',', '', $data->loan_payment_other);
        if ($loan_payment_other_old != $charges_etc) {
            if ($loan_payment_other_old != 0) {
                $land_account_name = $this->SettingLandModel->getSettingLandByID($data->land_account_id);
                $land_account_cash_loan_payment_other_old = $land_account_name->land_account_cash - floatval($loan_payment_other_old);

                $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                    'land_account_cash' => $land_account_cash_loan_payment_other_old,
                    // 'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $detail = 'แก้ไขค่าใช้จ่ายอื่น ๆ(ลบ)' . '(' . $loan_code . ')';
                $this->SettingLandModel->insertSettingLandReport([
                    'setting_land_id' => $data->land_account_id,
                    'setting_land_report_detail' => $detail,
                    'setting_land_report_money' => $loan_payment_other_old,
                    'setting_land_report_note' => $remark,
                    'setting_land_report_account_balance' => $land_account_cash_loan_payment_other_old,
                    'employee_id' => session()->get('employeeID'),
                    'employee_name' => session()->get('employee_fullname')
                ]);
                sleep(1);
            }

            $land_account_name = $this->SettingLandModel->getSettingLandByID($data->land_account_id);
            $price = str_replace(',', '', $charges_etc);
            $land_account_cash_charges_etc = $land_account_name->land_account_cash + floatval($price);

            $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                'land_account_cash' => $land_account_cash_charges_etc,
                // 'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $detail = 'แก้ไขค่าใช้จ่ายอื่น ๆ(เพิ่มใหม่)' . '(' . $loan_code . ')';
            $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $data->land_account_id,
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $charges_etc,
                'setting_land_report_note' => $remark,
                'setting_land_report_account_balance' => $land_account_cash_charges_etc,
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
            sleep(1);
        }

        if ($dataLoanByCode->loan_payment_process != 0) {
            $land_account_name = $this->SettingLandModel->getSettingLandByID($dataLoanByCode->land_account_id);
            $charges_process = str_replace(',', '', $dataLoanByCode->loan_payment_process);

            $land_account_cash_charges_process = $land_account_name->land_account_cash - floatval($charges_process);

            $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                'land_account_cash' => $land_account_cash_charges_process,
                // 'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $detail = 'ลบสินเชื่อ ' . $code . '(ค่าดำเนินการ)';
            $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $dataLoanByCode->land_account_id,
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $charges_process,
                'setting_land_report_note' => '',
                'setting_land_report_account_balance' => $land_account_cash_charges_process,
                'employee_id' => session()->get('employeeID'),
                'employee_name' => session()->get('employee_fullname')
            ]);
            sleep(1);
        }

        if ($dataLoanByCode->loan_tranfer != 0) {
            $charges_transfer = str_replace(',', '', $dataLoanByCode->loan_tranfer);

            $land_account_name = $this->SettingLandModel->getSettingLandByID($dataLoanByCode->land_account_id);
            $land_account_cash_charges_transfer = $land_account_name->land_account_cash - floatval($charges_transfer);

            $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                'land_account_cash' => $land_account_cash_charges_transfer,
                // 'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $detail = 'ลบสินเชื่อ ' . $code . '(ค่าโอน)';
            $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $dataLoanByCode->land_account_id,
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $charges_transfer,
                'setting_land_report_note' => '',
                'setting_land_report_account_balance' => $land_account_cash_charges_transfer,
                'employee_id' => session()->get('employeeID'),
                'employee_name' => session()->get('employee_fullname')
            ]);
            sleep(1);
        }

        if ($dataLoanByCode->loan_payment_other != 0) {
            $loan_payment_other = str_replace(',', '', $dataLoanByCode->loan_payment_other);

            $land_account_name = $this->SettingLandModel->getSettingLandByID($dataLoanByCode->land_account_id);
            $land_account_cash_loan_payment_other = $land_account_name->land_account_cash - floatval($loan_payment_other);

            $this->SettingLandModel->updateSettingLandByID($land_account_name->id, [
                'land_account_cash' => $land_account_cash_loan_payment_other,
                // 'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $detail = 'ลบสินเชื่อ ' . $code . '(ค่าใช้จ่ายอื่น ๆ)';
            $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $dataLoanByCode->land_account_id,
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $loan_payment_other,
                'setting_land_report_note' => '',
                'setting_land_report_account_balance' => $land_account_cash_loan_payment_other,
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

        // $status_payment = $this->request->getPost('status_payment');

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

        $data = $this->LoanModel->getAllDataLoanByCode($codeloan_hidden);
        // $data_loan จำนวนงวด
        // $data_loan_installment  งวดแรกแรกที่ทำการเพิ่มใหม่
        // $add_year จำนวนปีใหม่
        // $loan_installments จำนวนงวดทั้งหมดที่ทำการเพิ่มใหม่จนครบ
        $data_loan =  $data->loan_payment_year_counter;
        $data_loan =  $data_loan * 12;
        $data_loan_installment =  $data_loan + 1;
        $add_year = $data->loan_payment_year_counter + 1;
        $loan_installments = $add_year * 12;

        $create_payment = false;

        if (($data_loan == $installment_count && $payment_type != 'CloseLoan')) {

            $data_loan = [
                'loan_payment_year_counter' => $add_year,
                'loan_payment_sum_installment' => $pay_sum
            ];

            for ($index_installment = $data_loan_installment; $index_installment <= $loan_installments; $index_installment++) {

                $add_load_payment_data = [
                    'loan_code' => $codeloan_hidden,
                    'loan_payment_amount' => $data->loan_payment_month,
                    'loan_payment_installment' =>  $index_installment,
                    'loan_payment_date_fix' =>  $data->loan_installment_date,
                    // 'loan_payment_date' => $date_pay_loan,
                    'created_at' => $buffer_datetime
                ];

                $this->LoanModel->insertpayment($add_load_payment_data);
            }

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
        } elseif (($payment_type == 'CloseLoan')) {

            $inv_number = null;

            if ($data && $data->loan_type === 'เงินสด') {

                $today = date('Y-m-d');
                $year  = (int)date('Y', strtotime($today));
                $month = (int)date('m', strtotime($today));

                $nextRunning = $this->LoanModel->getNextInvRunningNumber($year, $month);
                $runningStr  = str_pad($nextRunning, 3, '0', STR_PAD_LEFT); // 1 -> 001

                $inv_number = $codeloan_hidden . date('Ymd', strtotime($today)) . $runningStr;
            }

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
                'loan_date_close' => date("Y-m-d"),
                'updated_at' => $buffer_datetime
            ];

            if ($inv_number !== null) {
                $data_loan['inv_number'] = $inv_number;
            }

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

            if ($create_close_payment) {
                $data_payment = [
                    'loan_payment_type' => 'Close',
                    'loan_balance'  => 0
                ];

                $create_payment = $this->LoanModel->updateLoanPaymentClose($data_payment, $codeloan_hidden);
            }

            $Loan_Staus = 'ชำระปิดสินเชื่อ';
        } else {

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
                'message' => 'เพิ่มรายการสำเร็จ',
                'payment_type' => $payment_type
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
        $post = $this->request->getPost();

        $loanTypes = $this->request->getPost('loan_types');
        if (!empty($loanTypes) && !is_array($loanTypes)) $loanTypes = [$loanTypes];
        $post['loan_types'] = $loanTypes ?: [];

        $datas_loan = $this->LoanModel->_getAllDataLoanHistory($post);

        $recordsTotal = $this->LoanModel->countAllDataLoanHistory();

        $recordsFiltered = $this->LoanModel->getAllDataLoanHistoryFilter($post);

        return $this->response->setJSON([
            'draw' => $_POST['draw'],
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
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

            $DocumentModel = new \App\Models\DocumentModel();
            $document = $DocumentModel->getDocSumAll();

            $RealInvestmentModel = new \App\Models\RealInvestmentModel();
            $real_investment = $RealInvestmentModel->getRealInvestmentAll();

            $SettingLandModel = new \App\Models\SettingLandModel();
            $land_accounts = $SettingLandModel->getSettingLandAll();

            $datas = $this->LoanModel->getAllDataLoan();

            $end   = new \DateTime('first day of this month');   // ต้นเดือนปัจจุบัน
            $start = (clone $end)->modify('-12 months');         // ย้อนกลับไป 12 เดือน

            $startDate = $start->format('Y-m-d'); // เช่น 2024-12-01
            $endDate   = $end->format('Y-m-d');   // เช่น 2025-12-01

            $rolling12m = $this->LoanModel->getRolling12mSummary($startDate, $endDate);

            $fee_income_12m      = (float)($rolling12m->fee_income_12m      ?? 0); // ค่าดำเนินการ 12 เดือน
            $interest_income_12m = (float)($rolling12m->interest_income_12m ?? 0); // ดอกเบี้ยรับ 12 เดือน
            $expense_12m         = (float)($rolling12m->expense_12m         ?? 0); // ใบสำคัญจ่าย 12 เดือน

            // กำไรสุทธิจากพอร์ตสินเชื่อ 12 เดือน (ดอกเบี้ย + ค่าดำเนินการ - ค่าใช้จ่าย)
            $interest_net_12m = $fee_income_12m + $interest_income_12m - $expense_12m;

            $loan_summary_no_vat = 0;
            $loan_payment_sum_installment = 0;
            $loan_summary_all = 0;
            // $summary_all = 0;
            $loan_payment_month = 0;
            // $principle = 0;
            // $investment = 0;
            // $return_funds = 0;
            $loan_summary_process = 0;

            // $receipt  = $document->receipt;   // ยอดใบสำคัญรับทั้งหมด
            $expenses = $document->expenses;  // ยอดใบสำคัญจ่ายทั้งหมด

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

                $loan_payment_sum_installment = $loan_payment_sum_installment + $data->loan_payment_sum_installment;

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

                $loan_summary_process = $loan_summary_process + $data->loan_payment_process + $data->loan_tranfer + $data->loan_payment_other;
            }

            // $summary_all = $loan_summary_all - $loan_payment_sum_installment;
            // if ($summary_all != 0) {
            //     $return_funds = ($loan_payment_month / $summary_all) * 100;
            // }

            $summary_net_assets = $summary_no_vat_ON_STATE + $sum_land_account;

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
                                <div>วงเงินที่ปิดบัญชีแล้ว</div>
                                <div class="font-weight-semibold mb-1 tx-secondary">' . number_format($summary_no_vat_CLOSE_STATE, 2) . '</div>
                            </div>
                        </div>
                    </div>
                    <div class="col" style="flex-grow: 1;">
                        <div class="card text-center">
                            <div class="card-body">
                                <div>ค่างวดต่อเดือน</div>
                                <div class="font-weight-semibold mb-1 tx-secondary">' . number_format($loan_payment_month, 2) . '</div>
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

            // ตัวเลขหลัก
            $paid_up_capital      = $real_investment->investment;   // ทุนตั้งต้น
            $retained_earnings    = ($loan_summary_process + $loan_payment_sum_installment) - $expenses;    // กำไรสะสม
            $equity_real          = $paid_up_capital + $retained_earnings;   // ทรัพย์สินสุทธิ (Equity จริง)

            // KPI %
            $true_leverage        = ($summary_no_vat_ON_STATE / $equity_real);   // x
            $leverage_vs_capital  = ($summary_no_vat_ON_STATE / $paid_up_capital);   // x
            $turnover_times       = ($loan_summary_no_vat / $summary_no_vat_ON_STATE);   // x
            $roi_total            = ($retained_earnings / $paid_up_capital) * 100;   // %
            $portfolio_yield      = ($interest_net_12m / $summary_no_vat_ON_STATE) * 100;   // %
            $cash_buffer_percent  = ($sum_land_account / $summary_no_vat_ON_STATE) * 100;    // %
            $cash_equity_percent  = ($sum_land_account / $equity_real) * 100;    // %
            $equity_gain_percent  = (($equity_real - $paid_up_capital) / $paid_up_capital) * 100;   // %

            $roi = $roi_total; // เช่น 57.9

            // ROI
            if ($roi >= 40) {
                $roi_text = 'ดีมาก';
                $roi_class = 'tx-info';
            } elseif ($roi >= 20) {
                $roi_text = 'ดี';
                $roi_class = 'tx-success';
            } elseif ($roi >= 0) {
                $roi_text = 'ปานกลาง';
                $roi_class = 'tx-secondary';
            } else {
                $roi_text = 'เสี่ยง';
                $roi_class = 'tx-danger';
            }

            // True Leverage
            if ($true_leverage < 1.0) {
                $tl_text = "ดีมาก";
                $tl_class = "tx-info";
            } elseif ($true_leverage < 2.0) {
                $tl_text = "ดี";
                $tl_class = "tx-success";
            } elseif ($true_leverage < 3.0) {
                $tl_text = "ปานกลาง";
                $tl_class = "tx-secondary";
            } else {
                $tl_text = "เสี่ยง";
                $tl_class = "tx-danger";
            }

            if ($leverage_vs_capital < 1.0) {
                $lvc_text  = "ดีมาก";
                $lvc_class = "tx-info";       // ฟ้า
            } elseif ($leverage_vs_capital < 2.0) {
                $lvc_text  = "ดี";
                $lvc_class = "tx-success";    // เขียว
            } elseif ($leverage_vs_capital < 3.0) {
                $lvc_text  = "พอเหมาะ";
                $lvc_class = "tx-secondary";    // เหลือง
            } else {
                $lvc_text  = "ขยายตัวสูง";
                $lvc_class = "tx-danger";     // แดง แต่ดูไม่รุนแรงเกินไป
            }

            // Turnover Times
            if ($turnover_times < 1.0) {
                $to_text  = "ช้า";
                $to_class = "tx-danger";     // แดง (แสดงว่าหมุนช้า)
            } elseif ($turnover_times < 2.0) {
                $to_text  = "พอเหมาะ";
                $to_class = "tx-secondary";    // เหลือง
            } elseif ($turnover_times < 3.0) {
                $to_text  = "ดี";
                $to_class = "tx-success";    // เขียว
            } else {
                $to_text  = "ดีมาก";
                $to_class = "tx-info";       // ฟ้า = ดีสุด
            }

            // Cash Buffer %
            if ($cash_buffer_percent >= 15) {
                $cb_text  = "ดีมาก";
                $cb_class = "tx-info";       // ฟ้า (ดีที่สุด)
            } elseif ($cash_buffer_percent >= 10) {
                $cb_text  = "ดี";
                $cb_class = "tx-success";    // เขียว
            } elseif ($cash_buffer_percent >= 5) {
                $cb_text  = "พอใช้";
                $cb_class = "tx-secondary";    // เหลือง
            } else {
                $cb_text  = "ต่ำ";
                $cb_class = "tx-danger";     // แดง
            }

            if ($cash_equity_percent >= 15) {
                $ce_text  = "ดีมาก";
                $ce_class = "tx-info";       // ฟ้า
            } elseif ($cash_equity_percent >= 8) {
                $ce_text  = "ดี";
                $ce_class = "tx-success";    // เขียว
            } elseif ($cash_equity_percent >= 5) {
                $ce_text  = "พอใช้";
                $ce_class = "tx-secondary";    // เหลือง
            } else {
                $ce_text  = "ต่ำ";
                $ce_class = "tx-danger";     // แดง
            }

            // Equity Gain vs Paid-Up Capital
            if ($equity_gain_percent >= 50) {
                $eg_text  = "ดีมาก";
                $eg_class = "tx-info";        // ฟ้า
            } elseif ($equity_gain_percent >= 20) {
                $eg_text  = "ดี";
                $eg_class = "tx-success";     // เขียว
            } elseif ($equity_gain_percent >= 0) {
                $eg_text  = "ปานกลาง";
                $eg_class = "tx-secondary";   // เทา
            } else {
                $eg_text  = "ต่ำ";
                $eg_class = "tx-danger";      // แดง
            }

            if ($portfolio_yield < 10) {
                $py_text  = "ต่ำ";
                $py_class = "tx-danger";     // แดง
            } elseif ($portfolio_yield < 20) {
                $py_text  = "พอใช้";
                $py_class = "tx-secondary";    // เหลือง
            } elseif ($portfolio_yield < 30) {
                $py_text  = "ดี";
                $py_class = "tx-success";    // เขียว
            } else {
                $py_text  = "ดีมาก";
                $py_class = "tx-info";       // ฟ้า
            }

            // format ตัวเลขไว้ก่อน
            $fmt_paid_up_capital   = number_format($paid_up_capital, 2);
            $fmt_retained_earnings = number_format($retained_earnings, 2);
            $fmt_equity_real       = number_format($equity_real, 2);
            $fmt_loan_book         = number_format($summary_no_vat_ON_STATE, 2);
            $fmt_cash_account      = number_format($sum_land_account, 2);
            $fmt_total_assets      = number_format($summary_net_assets, 2);

            $fmt_roi_total         = number_format($roi_total, 1) . '%';
            $fmt_true_leverage     = number_format($true_leverage, 2) . 'x';
            $fmt_lev_vs_capital    = number_format($leverage_vs_capital, 2) . 'x';
            $fmt_turnover          = number_format($turnover_times, 2) . 'x';
            $fmt_port_yield        = number_format($portfolio_yield, 1) . '%';
            $fmt_cash_buffer       = number_format($cash_buffer_percent, 1) . '%';
            $fmt_cash_equity       = number_format($cash_equity_percent, 1) . '%';
            $fmt_equity_gain       = number_format($equity_gain_percent, 1) . '%';

            $roi_display             = $fmt_roi_total       . '<span class="badge ' . $roi_class . '">(' . $roi_text . ')</span>';
            $true_leverage_display   = $fmt_true_leverage   . '<span class="badge ' . $tl_class  . '">(' . $tl_text  . ')</span>';
            $lev_vs_capital_display  = $fmt_lev_vs_capital  . '<span class="badge ' . $lvc_class . '">(' . $lvc_text . ')</span>';
            $turnover_display        = $fmt_turnover        . '<span class="badge ' . $to_class  . '">(' . $to_text  . ')</span>';
            $cash_buffer_display     = $fmt_cash_buffer     . '<span class="badge ' . $cb_class  . '">(' . $cb_text  . ')</span>';
            $cash_equity_display     = $fmt_cash_equity     . '<span class="badge ' . $ce_class  . '">(' . $ce_text  . ')</span>';
            $equity_gain_display     = $fmt_equity_gain     . '<span class="badge ' . $eg_class  . '">(' . $eg_text  . ')</span>';
            $portfolio_yield_display = $fmt_port_yield      . '<span class="badge ' . $py_class  . '">(' . $py_text  . ')</span>';


            $html_summarizeLoan = '
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body mt-2 mb-2">
                                <div class="loan-rows">
                                    <div class="loan-row">
                                        <div class="loan-row-left">
                                            <div class="loan-row-left-grid">
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">ทุนตั้งต้น</div>
                                                    <div class="loan-metric-value">' . $fmt_paid_up_capital . '</div>
                                                    <div class="loan-metric-sub">เงินลงทุนตั้งต้นของ InfiniteX</div>
                                                </div>
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">กำไรสะสม</div>
                                                    <div class="loan-metric-value">' . $fmt_retained_earnings . '</div>
                                                    <div class="loan-metric-sub">กำไรที่ได้รับจริง – ค่าใช้จ่าย</div>
                                                </div>
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">ทรัพย์สินสุทธิ</div>
                                                    <div class="loan-metric-value">' . $fmt_equity_real . '</div>
                                                    <div class="loan-metric-sub">ทุนตั้งต้น + กำไรสะสมทั้งหมด</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="loan-row-right">
                                            <div class="loan-row-right-grid">
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">ROI</div>
                                                    <div class="loan-metric-value">' . $roi_display . '</div>
                                                    <div class="loan-metric-sub">ผลตอบแทนรวมเทียบกับทุน</div>
                                                </div>
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">True Leverage</div>
                                                    <div class="loan-metric-value">' . $true_leverage_display . '</div>
                                                    <div class="loan-metric-sub">สัดส่วนเงินปล่อยกู้ต่อทุน</div>
                                                </div>
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">ปล่อยกู้ต่อทุน</div>
                                                    <div class="loan-metric-value">' . $lev_vs_capital_display . '</div>
                                                    <div class="loan-metric-sub">ปล่อยกู้ได้กี่เท่าของทุนตั้งต้น</div>
                                                </div>
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">Turnover</div>
                                                    <div class="loan-metric-value">' . $turnover_display . '</div>
                                                    <div class="loan-metric-sub">อัตราการหมุนพอร์ต</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="loan-row">
                                        <div class="loan-row-left">
                                            <div class="loan-row-left-grid">
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">พอร์ตสินเชื่อปัจจุบัน</div>
                                                    <div class="loan-metric-value">' . $fmt_loan_book . '</div>
                                                    <div class="loan-metric-sub">ยอดปล่อยกู้ที่กำลังหมุนอยู่</div>
                                                </div>
                                                <div class="loan-metric-card">
                                                    <a href="' . base_url('/setting_land/land') . '" style="color:inherit;text-decoration:none;">
                                                        <div class="loan-metric-label">เงินสดบัญชี</div>
                                                        <div class="loan-metric-value">' . $fmt_cash_account . '</div>
                                                        <div class="loan-metric-sub">ยอดเงินสดที่พร้อมใช้</div>
                                                    </a>
                                                </div>
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">สินทรัพย์รวม</div>
                                                    <div class="loan-metric-value">' . $fmt_total_assets . '</div>
                                                    <div class="loan-metric-sub">เงินปล่อยกู้ + เงินสดในระบบ</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="loan-row-right">
                                            <div class="loan-row-right-grid">
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">Portfolio Yield</div>
                                                    <div class="loan-metric-value">' . $portfolio_yield_display . '</div>
                                                    <div class="loan-metric-sub">ผลตอบแทนต่อพอร์ต (12m)</div>
                                                </div>
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">เงินสดต่อพอร์ต</div>
                                                    <div class="loan-metric-value">' . $cash_buffer_display . '</div>
                                                    <div class="loan-metric-sub">ระดับกันชนสภาพคล่อง</div>
                                                </div>
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">เงินสด / Equity</div>
                                                    <div class="loan-metric-value">' . $cash_equity_display . '</div>
                                                    <div class="loan-metric-sub">สัดส่วนเงินสดเทียบ Equity</div>
                                                </div>
                                                <div class="loan-metric-card">
                                                    <div class="loan-metric-label">การเติบโตรวม</div>
                                                    <div class="loan-metric-value">' . $equity_gain_display . '</div>
                                                    <div class="loan-metric-sub">โตขึ้นจากทุนเริ่มต้น</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

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

        $data['LoanPaymentMonths'] = $this->LoanModel->getListLoanPaymentMonths(date('Y'));
        $data['DocumentsMonths'] = $this->DocumentModel->getrevenue(date('Y'));
        $data['LoanProcessMonths'] = $this->LoanModel->getLoanProcessMonths(date('Y'));
        $data['OpenLoanMonths'] = $this->LoanModel->getOpenLoan(date('Y'));

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
            $response = [
                'success' => 0,
                'message' => '',
            ];

            $ListPayments          = $this->LoanModel->getRevenueListPayments($data);
            $OpenLoans             = $this->LoanModel->getOpenLoan($data);
            $OverduePayments       = $this->LoanModel->getOverdueListPayments($data);
            $PaymentMonths         = $this->LoanModel->getListPaymentMonths($data);
            $LoanPaymentMonths     = $this->LoanModel->getListLoanPaymentMonths($data);
            $LoanClosePaymentMonths = $this->LoanModel->getListLoanClosePaymentMonths($data);

            $months = range(1, 12);

            $monthNames = [
                1  => 'มกราคม',
                2  => 'กุมภาพันธ์',
                3  => 'มีนาคม',
                4  => 'เมษายน',
                5  => 'พฤษภาคม',
                6  => 'มิถุนายน',
                7  => 'กรกฏาคม',   // เขียนให้ตรงกับของเดิม
                8  => 'สิงหาคม',
                9  => 'กันยายน',
                10 => 'ตุลาคม',
                11 => 'พฤศจิกายน',
                12 => 'ธันวาคม',
            ];

            // series ต่อเดือน (ค่าเริ่มต้น 0)
            $payment          = array_fill(1, 12, 0); // รับชำระ
            $loan             = array_fill(1, 12, 0); // เปิดสินเชื่อ
            $overduePayment   = array_fill(1, 12, 0); // ค้างชำระ
            $paymentMonth     = array_fill(1, 12, 0); // ชำระค่างวด
            $loanPayment      = array_fill(1, 12, 0); // ชำระค่างวดจริง
            $loanClosePayment = array_fill(1, 12, 0); // ชำระปิดบัญชี

            $classRow         = array_fill(1, 12, ''); // ไฮไลท์เดือนปัจจุบัน

            // ----- รับชำระ (ListPayments) -----
            foreach ($ListPayments as $row) {
                $m = (int) $row->payment_month;
                if ($m < 1 || $m > 12) {
                    continue;
                }
                // เดิมใช้ "+"
                $payment[$m] += $row->loan_payment_amount;
            }

            // ----- เปิดสินเชื่อ (OpenLoans) -----
            foreach ($OpenLoans as $row) {
                $m = (int) $row->loan_month;
                if ($m < 1 || $m > 12) {
                    continue;
                }
                $loan[$m] += $row->loan_summary_no_vat;
            }

            $currentYear  = date('Y');
            $currentMonth = (int) date('m');

            // ----- ค้างชำระ (OverduePayments) -----
            foreach ($OverduePayments as $row) {
                $m = (int) $row->overdue_payment;
                if ($m < 1 || $m > 12) {
                    continue;
                }

                if ($data === $currentYear) {
                    if ($m <= $currentMonth) {
                        $overduePayment[$m] += $row->loan_payment_amount;
                    }
                } elseif ($data < $currentYear) {
                    $overduePayment[$m] += $row->loan_payment_amount;
                }
                // ปีอนาคต ($data > $currentYear) จะไม่บวกอะไร ตามโค้ดเดิม
            }

            // ----- ชำระค่างวด (PaymentMonths) -----
            foreach ($PaymentMonths as $row) {
                $m = (int) $row->overdue_payment; // ใช้ field นี้ตามโค้ดเดิม
                if ($m < 1 || $m > 12) {
                    continue;
                }

                if ($data === $currentYear) {
                    if ($m <= $currentMonth) {
                        $paymentMonth[$m] += $row->loan_payment_amount;
                    }
                } elseif ($data < $currentYear) {
                    $paymentMonth[$m] += $row->loan_payment_amount;
                }
            }

            // ----- ชำระค่างวดจริง (LoanPaymentMonths) -----
            foreach ($LoanPaymentMonths as $row) {
                $m = (int) $row->loan_created_payment;
                if ($m < 1 || $m > 12) {
                    continue;
                }
                $loanPayment[$m] += $row->setting_land_report_money;
            }

            // ----- ชำระปิดบัญชี (LoanClosePaymentMonths) -----
            foreach ($LoanClosePaymentMonths as $row) {
                $m = (int) $row->loan_created_close_payment;
                if ($m < 1 || $m > 12) {
                    continue;
                }
                $loanClosePayment[$m] += $row->setting_land_report_money;
            }

            // ----- ไฮไลท์แถวเดือนปัจจุบัน ถ้าปีตรงกับปีปัจจุบัน -----
            if ($data === $currentYear && isset($classRow[$currentMonth])) {
                $classRow[$currentMonth] = 'bg-primary-transparent';
            }

            // ----- ชำระค่างวด - ค้างชำระ (Diff Payment) -----
            $diffPaymentMonth = [];
            foreach ($months as $m) {
                $diffPaymentMonth[$m] = $paymentMonth[$m] - $overduePayment[$m];
            }

            // ----- ยอดรวมทั้งปี -----
            $Month_Payment_Sum          = array_sum($payment);
            $Month_Loan_Sum             = array_sum($loan);
            $Month_Overdue_Payment_Sum  = array_sum($overduePayment);
            $Month_Diff_Payment_Sum     = array_sum($diffPaymentMonth);
            $Month_Loan_Payment_Sum     = array_sum($loanPayment);
            $Month_Loan_Close_Payment_Sum = array_sum($loanClosePayment);

            // ----- สร้าง HTML -----
            $html = '
        <div class="card-body">
            <div class="table-responsive">
                <table class="table border-0 mb-0">
                    <tbody>
                        <tr>
                            <th class="border-top-0 bg-black-03 br-bs-5 br-ts-5 tx-15 wd-6p">เดือน</th>
                            <th class="border-top-0 bg-black-03 tx-15 wd-14p tx-center">เปิดสินเชื่อ</th>
                            <th class="border-top-0 bg-black-03 tx-15 wd-13p tx-center">รับชำระ</th>
                            <th class="border-top-0 bg-black-03 tx-15 wd-14p tx-center">ชำระค่างวดจริง</th>
                            <th class="border-top-0 bg-black-03 tx-15 wd-14p tx-center">ชำระปิดบัญชี</th>
                            <th class="border-top-0 bg-black-03 tx-15 wd-13p tx-center">ชำระค่างวด</th>
                            <th class="border-top-0 bg-black-03 tx-15 wd-13p tx-center">ค้างชำระ</th>
                        </tr>';

            foreach ($months as $m) {
                $name       = $monthNames[$m];
                $idNoPad    = (string) $m;
                $idPad      = str_pad($m, 2, '0', STR_PAD_LEFT);

                $html .= '
                        <tr class="' . $classRow[$m] . '">
                            <td class="border-top-0 pt-4"><a>' . $name . '</a></td>
                            <td class="border-top-0" style="text-align: right;">
                                <a href="javascript:void(0);" data-id="' . $idNoPad . '" id="Month_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanMonth">'
                    . number_format($loan[$m], 2) .
                    '</a>
                            </td>
                            <td class="border-top-0" style="text-align: right;">
                                <a href="javascript:void(0);" data-id="' . $idNoPad . '" id="Month_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalPaymentMonth">'
                    . number_format($payment[$m], 2) .
                    '</a>
                            </td>
                            <td class="border-top-0" style="text-align: right;">
                                <a href="javascript:void(0);" data-id="' . $idNoPad . '" id="Month_Loan_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanPaymentMonth">'
                    . number_format($loanPayment[$m], 2) .
                    '</a>
                            </td>
                            <td class="border-top-0" style="text-align: right;">
                                <a href="javascript:void(0);" data-id="' . $idNoPad . '" id="Month_Loan_Close_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanClosePaymentMonth">'
                    . number_format($loanClosePayment[$m], 2) .
                    '</a>
                            </td>
                            <td class="border-top-0" style="text-align: right;">
                                <a href="javascript:void(0);" data-id="' . $idPad . '" id="Month_Diff_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDiffPaymentMonth">'
                    . number_format($diffPaymentMonth[$m], 2) .
                    '</a>
                            </td>
                            <td class="border-top-0" style="text-align: right;">
                                <a href="javascript:void(0);" data-id="' . $idPad . '" id="Month_OverduePayment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOverduePaymentMonth">'
                    . number_format($overduePayment[$m], 2) .
                    '</a>
                            </td>
                        </tr>';
            }

            $html .= '
                        <tr class="bg-primary">
                            <td class="border-top-0 pt-4">
                                <p class="tx-left mb-0">ยอดรวม</p>
                            </td>
                            <td class="border-top-0" style="text-align: right;">
                                <p class="mb-0">' . number_format($Month_Loan_Sum, 2) . '</p>
                            </td>
                            <td class="border-top-0" style="text-align: right;">
                                <p class="mb-0">' . number_format($Month_Payment_Sum, 2) . '</p>
                            </td>
                            <td class="border-top-0" style="text-align: right;">
                                <p class="mb-0">' . number_format($Month_Loan_Payment_Sum, 2) . '</p>
                            </td>
                            <td class="border-top-0" style="text-align: right;">
                                <p class="mb-0">' . number_format($Month_Loan_Close_Payment_Sum, 2) . '</p>
                            </td>
                            <td class="border-top-0" style="text-align: right;">
                                <p class="mb-0">' . number_format($Month_Diff_Payment_Sum, 2) . '</p>
                            </td>
                            <td class="border-top-0" style="text-align: right;">
                                <p class="mb-0">' . number_format($Month_Overdue_Payment_Sum, 2) . '</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>';

            $response['data']    = $html;
            $response['success'] = 1;
            $response['message'] = '';
            $status              = 200;

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

    public function ajaxDataTableLoanProcess($id)
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
            $total_count = $LoanModel->getDataTableLoanProcessMonthSearchCount($param);

            $data_month = $LoanModel->getDataTableLoanProcessMonthSearch($param);
        } else {
            // count all data
            $total_count = $LoanModel->getDataTableLoanProcessMonthCount($param);

            // get per page data
            $data_month = $LoanModel->getDataTableLoanProcessMonth($param);
        }

        $i = 0;
        $data = [];
        foreach ($data_month as $datas) {
            $sum = $datas->loan_payment_process + $datas->loan_tranfer + $datas->loan_tranfer;
            $i++;
            $data[] = array(
                $i,
                $datas->loan_code,
                $datas->loan_employee,
                number_format($datas->loan_payment_process, 2),
                number_format($datas->loan_tranfer, 2),
                number_format($datas->loan_payment_other, 2),
                number_format($sum, 2),
                $datas->created_at,
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

    public function ajaxDataTableReceipt($id)
    {
        $DocumentModel = new \App\Models\DocumentModel();
        $param['search_value'] = $_REQUEST['search']['value'];
        $param['draw'] = $_REQUEST['draw'];
        $param['start'] = $_REQUEST['start'];
        $param['length'] = $_REQUEST['length'];
        $param['month'] = $id;
        $param['years'] = $_REQUEST['years'];

        if (!empty($param['search_value'])) {
            // count all data
            $total_count = $DocumentModel->getDataTableDocumentsReceiptMonthSearchCount($param);

            $data_month = $DocumentModel->getDataTableDocumentsReceiptMonthSearch($param);
        } else {
            // count all data
            $total_count = $DocumentModel->getDataTableDocumentsReceiptMonthCount($param);

            // get per page data
            $data_month = $DocumentModel->getDataTableDocumentsReceiptMonth($param);
        }

        $i = 0;
        $data = [];
        foreach ($data_month as $datas) {
            $i++;
            $data[] = array(
                $i,
                $datas->doc_number,
                $datas->doc_date,
                $datas->title,
                $datas->note,
                $datas->cash_flow_name,
                number_format($datas->price, 2),
                $datas->username,
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

    public function ajaxDataTableExpenses($id)
    {
        $DocumentModel = new \App\Models\DocumentModel();

        $param = [
            'draw'         => (int)($_REQUEST['draw'] ?? 0),
            'start'        => (int)($_REQUEST['start'] ?? 0),
            'length'       => (int)($_REQUEST['length'] ?? 10),
            'search_value' => $_REQUEST['search']['value'] ?? '',
            'order'        => $_REQUEST['order'] ?? [],
            'month'        => (int)$id,
            'years'        => (int)($_REQUEST['years'] ?? 0),
        ];

        // ✅ total ทั้งหมด (ไม่สน search)
        $paramTotal = $param;
        $paramTotal['search_value'] = '';
        $recordsTotal = $DocumentModel->getDataTableDocumentsPayMonth($paramTotal, true);

        // ✅ filtered (สน search)
        $recordsFiltered = $DocumentModel->getDataTableDocumentsPayMonth($param, true);

        // ✅ data ตามหน้า + order + search
        $rows = $DocumentModel->getDataTableDocumentsPayMonth($param, false);

        $data = [];
        $no = $param['start']; // ให้เลขรันตามหน้า
        foreach ($rows as $r) {
            $no++;
            $data[] = [
                $no,
                $r->doc_number,
                $r->doc_date,
                $r->title,
                $r->note,
                $r->cash_flow_name,
                number_format((float)$r->price, 2),
                $r->username,
                $r->created_at,
            ];
        }

        return $this->response->setJSON([
            "draw" => $param['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ]);
    }

    // public function ajaxDataTableExpenses($id)
    // {
    //     $DocumentModel = new \App\Models\DocumentModel();
    //     $param['search_value'] = $_REQUEST['search']['value'];
    //     $param['draw'] = $_REQUEST['draw'];
    //     $param['start'] = $_REQUEST['start'];
    //     $param['length'] = $_REQUEST['length'];
    //     $param['month'] = $id;
    //     $param['years'] = $_REQUEST['years'];

    //     if (!empty($param['search_value'])) {
    //         // count all data
    //         $total_count = $DocumentModel->getDataTableDocumentsPayMonthSearchCount($param);

    //         $data_month = $DocumentModel->getDataTableDocumentsPayMonthSearch($param);
    //     } else {
    //         // count all data
    //         $total_count = $DocumentModel->getDataTableDocumentsPayMonthCount($param);

    //         // get per page data
    //         $data_month = $DocumentModel->getDataTableDocumentsPayMonth($param);
    //     }

    //     $i = 0;
    //     $data = [];
    //     foreach ($data_month as $datas) {
    //         $i++;
    //         $data[] = array(
    //             $i,
    //             $datas->doc_number,
    //             $datas->doc_date,
    //             $datas->title,
    //             $datas->note,
    //             $datas->cash_flow_name,
    //             number_format($datas->price, 2),
    //             $datas->username,
    //             $datas->created_at,
    //         );
    //     }

    //     $json_data = array(
    //         "draw" => intval($param['draw']),
    //         "recordsTotal" => count($total_count),
    //         "recordsFiltered" => count($total_count),
    //         "data" => $data   // total data array
    //     );

    //     echo json_encode($json_data);
    // }


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
                            <p class="mb-0 font-weight-semibold tx-18">ยอดรับชำระสูงสุด</p>
                            <div class="mt-2">
                                <span class="mb-0 font-weight-semibold tx-15">' . "เดือน" . $Month_max . " " . number_format($MAX, 2) . '</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-4 col-12 p-0">
                        <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                            <p class="mb-0 font-weight-semibold tx-18">ยอดรับชำระน้อยสุด</p>
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
                            <p class="mb-0 font-weight-semibold tx-18">ยอดรับชำระทั้งหมด</p>
                            <div class="mt-2">
                                <span class="mb-0 font-weight-semibold tx-15">' . number_format($Sum_price, 2) . '</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-4 col-12 p-0">
                        <div class="tx-center pd-y-7 pd-sm-y-0-f">
                            <p class="mb-0 font-weight-semibold tx-18">เปอร์เซ็นต์ยอดรับชำระ</p>
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
            round($Month_Revenue_Jan, 2),
            round($Month_Revenue_Feb, 2),
            round($Month_Revenue_Mar, 2),
            round($Month_Revenue_Apr, 2),
            round($Month_Revenue_May, 2),
            round($Month_Revenue_Jun, 2),
            round($Month_Revenue_Jul, 2),
            round($Month_Revenue_Aug, 2),
            round($Month_Revenue_Sep, 2),
            round($Month_Revenue_Oct, 2),
            round($Month_Revenue_Nov, 2),
            round($Month_Revenue_Dec, 2)
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
        $customer_picture_datas = $this->LoanModel->getCustomerImgByCode($code);

        // รวมผลลัพธ์ทั้ง 2
        $all_pictures = array_merge($other_picture_datas, $customer_picture_datas);

        $data = '';

        if ($all_pictures) {
            foreach ($all_pictures as $pic) {
                $deleteBtn = '';

                // ถ้า path เป็น loan_payment_img (มาจาก picture_loan_other) ให้มีปุ่มลบ
                if ($pic->path === 'loan_payment_img') {
                    $deleteBtn = '
                    <a id="' . $pic->id . '===' . $pic->src . '" 
                       href="javascript:;" 
                       onclick="deleteOtherPicture(this.id);" 
                       class="btn btn-circle-sm btn-primary flex-center me-0 mb-0">
                        <i class="fe fe-trash tx-12"></i>
                    </a>';
                }

                $data .= '
            <div class="col-sm-12 col-md-2" style="text-align: center;">
             <div class="brick">
               <div class="file-attach file-attach-lg">
                    <div class="mb-1 border br-5 pos-relative overflow-hidden">
                        <img src="' . $this->s3_cdn_img . "/uploads/" . $pic->path . "/" . $pic->src . '" class="br-5" alt="doc">
                        <div class="btn-list attach-options v-center d-flex flex-column">'
                    . $deleteBtn . '
                            <a href="' . $this->s3_cdn_img . "/uploads/" . $pic->path . "/" . $pic->src . '" 
                               class="btn btn-circle-sm btn-success flex-center me-0 mb-0 mg-t-3 js-img-viewer-other" 
                               data-caption="รูปอื่นๆ" data-id="other">
                                <i class="fe fe-eye tx-12" style="z-index: 9999; position: fixed;"></i>
                                <img src="' . $this->s3_cdn_img . "/uploads/" . $pic->path . "/" . $pic->src . '" 
                                     alt=""  
                                     style="z-index: 1; filter: blur(10px); position: fixed;" />
                            </a>
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
        $datas1 = $this->LoanModel->getPictureLoanOther($code);
        $datas2 = $this->LoanModel->getLoanCustomerImg($code);
        $datas = array_merge($datas1, $datas2);

        return $this->response->setJSON([
            'status' => 200,
            'error' => false,
            'message' => $datas
        ]);
    }

    //updateOpenLoanTargetedMonth
    public function updateOpenLoanTargetedMonth()
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
                'open_loan_target' => $this->request->getVar('editOpenLoanTargetedMonth'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($update) {

                // pusherEdit
                $pusher = getPusher();
                $pusher->trigger('color_Status', 'event', [
                    'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                    'event' => 'status_Yellow',
                    'title' => session()->get('username') . " : " . 'ทำการแก้ไขเป้าหมายเปิดสินเชื่อต่อเดือน'
                ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'อัพเดท',
                    'detail' => '[อัพเดท] เป้าหมายเปิดสินเชื่อต่อเดือน',
                    'ip' => $this->request->getIPAddress()
                ]);
                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'แก้ไข เป้าหมายเปิดสินเชื่อต่อเดือน สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'แก้ไข เป้าหมายเปิดสินเชื่อต่อเดือน ไม่สำเร็จ';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
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

    // Get Data Loan ยอดชำระต่อเดือน
    public function FetchAllLoanPayments()
    {
        $date = $this->request->getGet('date') ?? '';

        $data_loan_payments = $this->LoanModel->getAllDataLoanPayments($date);
        return $this->response->setJSON([
            'status' => 200,
            'error' => false,
            'message' => json_encode($data_loan_payments)
        ]);
    }

    public function ajaxDataTableLoanPayment($id)
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
            $total_count = $LoanModel->getDataTableLoanPaymentMonthSearchCount($param);

            $data_month = $LoanModel->getDataTableLoanPaymentMonthSearch($param);
        } else {
            // count all data
            $total_count = $LoanModel->getDataTableLoanPaymentMonthCount($param);

            // get per page data
            $data_month = $LoanModel->getDataTableLoanPaymentMonth($param);
        }

        $i = 0;
        $data = [];
        foreach ($data_month as $datas) {
            $i++;
            $data[] = array(
                $i,
                $datas->setting_land_report_detail,
                number_format($datas->setting_land_report_money, 2),
                $datas->setting_land_report_note,
                $datas->employee_name,
                $datas->land_account_name,
                number_format($datas->setting_land_report_account_balance, 2),
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

    public function ajaxDataTableLoanClosePayment($id)
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
            $total_count = $LoanModel->getDataTableLoanClosePaymentMonthSearchCount($param);

            $data_month = $LoanModel->getDataTableLoanClosePaymentMonthSearch($param);
        } else {
            // count all data
            $total_count = $LoanModel->getDataTableLoanClosePaymentMonthCount($param);

            // get per page data
            $data_month = $LoanModel->getDataTableLoanClosePaymentMonth($param);
        }

        $i = 0;
        $data = [];
        foreach ($data_month as $datas) {
            $i++;
            $data[] = array(
                $i,
                $datas->setting_land_report_detail,
                number_format($datas->setting_land_report_money, 2),
                $datas->setting_land_report_note,
                $datas->employee_name,
                $datas->land_account_name,
                number_format($datas->setting_land_report_account_balance, 2),
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

    // saveMapLink data 
    public function saveMapLink($loanCode = null)
    {
        $LoanModel = new \App\Models\LoanModel();
        $buffer_datetime = date("Y-m-d H:i:s");
        $param['mapLink'] = $_REQUEST['mapLink'];
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            // HANDLE REQUEST
            $update = $LoanModel->updateLoan($loanCode, [
                'link_map' => $param['mapLink'],
                'updated_at' => $buffer_datetime
            ]);

            if ($update) {

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'อัพเดท',
                    'detail' => '[อัพเดท] Link Map',
                    'ip' => $this->request->getIPAddress()
                ]);

                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'แก้ไข Link Map สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'แก้ไข Link Map ไม่สำเร็จ';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    //updateDeedStatus
    public function updateDeedStatus()
    {
        $LoanModel = new \App\Models\LoanModel();
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            $loan_code = $this->request->getPost('loan_code');
            $status = $this->request->getPost('status');

            // HANDLE REQUEST
            $update = $LoanModel->updateLoan($loan_code, [
                'land_deed_status' => $status
            ]);

            if ($update) {
                $status = 200;
                $response['success'] = 1;
            } else {
                $status = 200;
                $response['success'] = 0;
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }


    public function list_ai()
    {
        $date = $this->request->getPost('date');
        $loan_types = $this->request->getPost('loan_types'); // <-- array แน่นอน

        $message_back = $this->LoanModel->getAllDataLoanOn($date, $loan_types);
        $loan_close = $this->LoanModel->DataLoanHistoryQueryAI();
        $real_investment = $this->realInvestmentModel->getRealInvestmentAll();
        $land_accounts = $this->SettingLandModel->getSettingLandAll();
        $datas = $this->LoanModel->getAllDataLoan();


        $loan_summary_no_vat = 0;
        $loan_payment_sum_installment = 0;
        $loan_summary_all = 0;
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

        $summary_net_assets = $summary_no_vat_ON_STATE + $sum_land_account;

        $datas_loan = [
            'loan_summary_no_vat'   => $loan_summary_no_vat,
            'loan_payment_sum_installment'   => $loan_payment_sum_installment,
            'loan_payment_month'   => $loan_payment_month,
            'sum_installment'   => $sum_installment
        ];

        $data_load_ON_STATE = [
            'summary_no_vat_ON_STATE' => $summary_no_vat_ON_STATE,
            'summary_no_vat_CLOSE_STATE' => $summary_no_vat_CLOSE_STATE,
            'summary_net_assets' => $summary_net_assets
        ];


        $status = 200;
        $response = [
            'code' => $status,
            'message' => "",
            'data' => $message_back,
            'data_close_loan' => $loan_close,
            'data_loan_all' => $datas_loan,
            'data_loan_summary_on_state' => $data_load_ON_STATE,
            'real_investment_real' => $real_investment,
            'land_accounts' => $land_accounts
        ];

        return $this->response
            ->setStatusCode($status)
            ->setContentType('application/json')
            ->setJSON($response);
    }

    public function loanPayment($loanCode = null)
    {
        // $data['content'] = 'loan/loan_payment';
        $data['title'] = 'ชำระสินเชื่อ';
        $data['css_critical'] = '';
        $data['js_critical'] = '';

        $data['loanData'] = $this->LoanModel->getAllDataLoanByCode($loanCode);
        $data['land_accounts'] = $this->SettingLandModel->getSettingLandAll();

        echo view('loan/loan_payment', $data);
    }

    public function insertDataLoanPaymentNoLogin()
    {
        $OverdueStatusModel = new OverdueStatusModel();
        $nofity_Day = $OverdueStatusModel->getOverdueStatusAll();

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
        $customer_payment_type = 'โอน';
        $file_payment = $this->request->getFile('file_payment');
        $total_loan_payment = $this->request->getPost('total_loan_payment');
        $account_id = $this->request->getPost('account_name');
        $close_loan_payment = $this->request->getPost('close_loan_payment');

        $imgFile = $this->request->getFile('imageFileInvoice');

        $paymentFileDate = $this->request->getVar('payment_file_date');
        $paymentFileTime = $this->request->getVar('payment_file_time');
        $paymentFilePrice = $this->request->getVar('payment_file_price');
        $paymentFileRefNo = $this->request->getVar('payment_file_ref_no');
        // $status_payment = $this->request->getPost('status_payment');
        $fileName_img = '';

        $land_account_name = $this->SettingLandModel->getSettingLandByID($account_id);
        if ($imgFile != '') {
            $fileName_img = $imgFile->getFilename();
        }
        if ($fileName_img !== "") {
            $fileName_img = $codeloan_hidden . "_" . $imgFile->getRandomName();
            $imgFile->move('uploads/loan_payment_img', $fileName_img);
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

        $data = $this->LoanModel->getAllDataLoanByCode($codeloan_hidden);

        // $data_loan จำนวนงวด
        // $data_loan_installment  งวดแรกแรกที่ทำการเพิ่มใหม่
        // $add_year จำนวนปีใหม่
        // $loan_installments จำนวนงวดทั้งหมดที่ทำการเพิ่มใหม่จนครบ
        $data_loan =  $data->loan_payment_year_counter;
        $data_loan =  $data_loan * 12;
        $data_loan_installment =  $data_loan + 1;
        $add_year = $data->loan_payment_year_counter + 1;
        $loan_installments = $add_year * 12;

        $create_payment = false;

        if (($data_loan == $installment_count && $payment_type != 'CloseLoan')) {

            $data_loan = [
                'loan_payment_year_counter' => $add_year,
                'loan_payment_sum_installment' => $pay_sum
            ];

            for ($index_installment = $data_loan_installment; $index_installment <= $loan_installments; $index_installment++) {

                $add_load_payment_data = [
                    'loan_code' => $codeloan_hidden,
                    'loan_payment_amount' => $data->loan_payment_month,
                    'loan_payment_installment' =>  $index_installment,
                    'loan_payment_date_fix' =>  $data->loan_installment_date,
                    // 'loan_payment_date' => $date_pay_loan,
                    'created_at' => $buffer_datetime
                ];

                $this->LoanModel->insertpayment($add_load_payment_data);
            }

            $data_payment = [
                // 'loan_code' => $codeloan_hidden,
                'loan_payment_amount'  => $payment_now,
                'loan_employee' => $employee_name,
                'loan_payment_type' => $payment_type,
                'loan_payment_pay_type' => $customer_payment_type,
                // 'loan_payment_installment' =>  $installment_count,
                'loan_payment_customer' => $payment_name,
                'loan_payment_src' => $fileName_img,
                'payment_file_date' => $paymentFileDate,
                'payment_file_time' => $paymentFileTime,
                'payment_file_ref_no' => $paymentFileRefNo,
                'payment_file_price' => $paymentFilePrice,
                'land_account_id' => $account_id,
                'land_account_name' => $land_account_name->land_account_name,
                'loan_payment_date' => $date_to_payment,
                'updated_at' => $buffer_datetime
            ];

            $create_payment = $this->LoanModel->updateLoanPayment($data_payment, $payment_id);

            $Loan_Staus = 'งวดที่ ' . $installment_count;
        } elseif (($payment_type == 'CloseLoan')) {

            $inv_number = null;

            if ($data && $data->loan_type === 'เงินสด') {

                $today = date('Y-m-d');
                $year  = (int)date('Y', strtotime($today));
                $month = (int)date('m', strtotime($today));

                $nextRunning = $this->LoanModel->getNextInvRunningNumber($year, $month);
                $runningStr  = str_pad($nextRunning, 3, '0', STR_PAD_LEFT); // 1 -> 001

                $inv_number = $codeloan_hidden . date('Ymd', strtotime($today)) . $runningStr;
            }

            $loan_payment = [
                // 'loan_code' => $codeloan_hidden,
                'loan_payment_amount'  => $payment_now,
                'loan_employee' => $employee_name,
                'loan_payment_type' => 'Installment',
                'loan_payment_pay_type' => $customer_payment_type,
                // 'loan_payment_installment' =>  $installment_count,
                'loan_payment_customer' => $payment_name,
                'loan_payment_src' => $fileName_img,
                'payment_file_date' => $paymentFileDate,
                'payment_file_time' => $paymentFileTime,
                'payment_file_ref_no' => $paymentFileRefNo,
                'payment_file_price' => $paymentFilePrice,
                'land_account_id' => $account_id,
                'land_account_name' => $land_account_name->land_account_name,
                'loan_payment_date' => $date_to_payment,
                'updated_at' => $buffer_datetime
            ];

            $close_payment = $this->LoanModel->updateLoanPayment($loan_payment, $payment_id);

            $data_loan = [
                'loan_close_payment' => $close_loan_payment,
                'loan_status' => 'CLOSE_STATE',
                'loan_date_close' => date("Y-m-d"),
                'updated_at' => $buffer_datetime
            ];

            if ($inv_number !== null) {
                $data_loan['inv_number'] = $inv_number;
            }

            $data_close_payment = [
                'loan_payment_amount'  => 0,
                'loan_employee' => $employee_name,
                'loan_payment_pay_type' => $customer_payment_type,
                'loan_payment_customer' => $payment_name,
                'loan_payment_src' => $fileName_img,
                'payment_file_date' => $paymentFileDate,
                'payment_file_time' => $paymentFileTime,
                'payment_file_ref_no' => $paymentFileRefNo,
                'payment_file_price' => $paymentFilePrice,
                'loan_payment_date' => $date_to_payment,
                'land_account_id' => $account_id,
                'land_account_name' => $land_account_name->land_account_name,
                'updated_at' => $buffer_datetime
            ];

            $create_close_payment = $this->LoanModel->updateLoanClosePayment($data_close_payment, $codeloan_hidden);

            if ($create_close_payment) {
                $data_payment = [
                    'loan_payment_type' => 'Close',
                    'loan_balance'  => 0
                ];

                $create_payment = $this->LoanModel->updateLoanPaymentClose($data_payment, $codeloan_hidden);
            }

            $Loan_Staus = 'ชำระปิดสินเชื่อ';
        } else {

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
                'payment_file_date' => $paymentFileDate,
                'payment_file_time' => $paymentFileTime,
                'payment_file_ref_no' => $paymentFileRefNo,
                'payment_file_price' => $paymentFilePrice,
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
                'employee_id' => 1,
            ]);
        }

        $update_loan = $this->LoanModel->updateLoanSumPayment($data_loan, $codeloan_hidden);

        if ($create_payment && $update_loan) {
            if ($nofity_Day->token_loan_status == 1) {
                $paymentData = [
                    "loan_code" => $codeloan_hidden, // รหัสสินเชื่อ
                    "customer" => $payment_name, // ชื่อลูกค้า
                    "address" => $data->loan_address, // ที่อยู่ลูกค้า
                    "amount" => $payment_now, // ยอดชำระ (จำนวนเงิน)
                    "payment_date" => $date_to_payment, // วันที่ชำระ (ปัจจุบัน)
                    "installment_count" => $installment_count,
                    "image_url" =>  getenv('CDN_IMG') . '/uploads/loan_payment_img/' . $fileName_img, // URL ของรูปภาพ
                ];

                $token = $nofity_Day->token_loan;

                // สร้างข้อมูลการชำระเงิน
                $messagePayload = $this->createSinglePaymentMessage($paymentData);

                // ส่งข้อความผ่าน LINE API
                $response = send_line_message($token, $messagePayload);

                // ตรวจสอบกรณี Token หมดอายุ
                if ($response['status'] === 401) {
                    log_message('info', 'Refreshing LINE Token...');
                    $newToken = get_line_access_token();
                    if ($newToken) {
                        $token = $newToken;
                        $OverdueStatusModel->updateOverdueStatus(['token_loan' => $newToken]);

                        // พยายามส่งข้อความใหม่ด้วย Token ใหม่
                        $retryResponse = send_line_message($token, $messagePayload);
                        if ($retryResponse['status'] !== 200) {
                            log_message('error', 'Failed to send LINE message after refreshing token.');
                        }
                    } else {
                        log_message('error', 'Failed to refresh LINE token.');
                    }
                } elseif ($response['status'] !== 200) {
                    log_message('error', 'Failed to send LINE message.');
                }
            }
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

    private function createSinglePaymentMessage($paymentData)
    {
        return [
            "type" => "flex",
            "altText" => "📢 แจ้งเตือนการชำระเงิน",
            "contents" => [
                "type" => "bubble",
                "hero" => [
                    "type" => "image", // ส่วนของรูปภาพ
                    "url" => $paymentData['image_url'], // URL ของรูปภาพ
                    "size" => "full", // ปรับขนาดเป็น full
                    "aspectRatio" => "16:9", // เปลี่ยนอัตราส่วนเป็น 16:9
                    "aspectMode" => "fit", // เปลี่ยนวิธีการแสดงเป็น fit
                    "margin" => "md" // เพิ่มระยะห่างด้านบนและล่าง
                ],
                "body" => [
                    "type" => "box",
                    "layout" => "vertical",
                    "contents" => [
                        [
                            "type" => "text",
                            "text" => "ชำระสินเชื่อสำเร็จ ✅",
                            "weight" => "bold",
                            "size" => "xl",
                            "color" => "#333333",
                            "align" => "center" // จัดกึ่งกลาง
                        ],
                        [
                            "type" => "separator",
                            "margin" => "md",
                            "color" => "#AAAAAA"
                        ],
                        [
                            "type" => "box",
                            "layout" => "horizontal",
                            "margin" => "md",
                            "contents" => [
                                [
                                    "type" => "text",
                                    "text" => "📝 สินเชื่อ:",
                                    "weight" => "bold",
                                    "size" => "sm",
                                    "color" => "#444444",
                                    "flex" => 1
                                ],
                                [
                                    "type" => "text",
                                    "text" => $paymentData['loan_code'],
                                    "size" => "sm",
                                    "color" => "#444444",
                                    "align" => "end",
                                    "flex" => 2
                                ]
                            ]
                        ],
                        [
                            "type" => "box",
                            "layout" => "horizontal",
                            "margin" => "md",
                            "contents" => [
                                [
                                    "type" => "text",
                                    "text" => "🔢 งวดที่:",
                                    "weight" => "bold",
                                    "size" => "sm",
                                    "color" => "#444444",
                                    "flex" => 1
                                ],
                                [
                                    "type" => "text",
                                    "text" => $paymentData['installment_count'],
                                    "size" => "sm",
                                    "color" => "#444444",
                                    "align" => "end",
                                    "flex" => 2
                                ]
                            ]
                        ],
                        [
                            "type" => "box",
                            "layout" => "horizontal",
                            "margin" => "md",
                            "contents" => [
                                [
                                    "type" => "text",
                                    "text" => "👤 ลูกค้า:",
                                    "weight" => "bold",
                                    "size" => "sm",
                                    "color" => "#444444",
                                    "flex" => 1
                                ],
                                [
                                    "type" => "text",
                                    "text" => $paymentData['customer'],
                                    "size" => "sm",
                                    "color" => "#444444",
                                    "align" => "end",
                                    "flex" => 2
                                ]
                            ]
                        ],
                        [
                            "type" => "box",
                            "layout" => "horizontal",
                            "margin" => "md",
                            "contents" => [
                                [
                                    "type" => "text",
                                    "text" => "📍 สถานที่:",
                                    "weight" => "bold",
                                    "size" => "sm",
                                    "color" => "#444444",
                                    "flex" => 1
                                ],
                                [
                                    "type" => "text",
                                    "text" => $paymentData['address'],
                                    "size" => "sm",
                                    "color" => "#444444",
                                    "align" => "end",
                                    "flex" => 2
                                ]
                            ]
                        ],
                        [
                            "type" => "box",
                            "layout" => "horizontal",
                            "margin" => "md",
                            "contents" => [
                                [
                                    "type" => "text",
                                    "text" => "📅 วันที่ชำระ:",
                                    "weight" => "bold",
                                    "size" => "sm",
                                    "color" => "#444444",
                                    "flex" => 1
                                ],
                                [
                                    "type" => "text",
                                    "text" => dateThaiDM($paymentData['payment_date']),
                                    "size" => "sm",
                                    "color" => "#444444",
                                    "align" => "end",
                                    "flex" => 2
                                ]
                            ]
                        ],
                        [
                            "type" => "box",
                            "layout" => "horizontal",
                            "margin" => "md",
                            "contents" => [
                                [
                                    "type" => "text",
                                    "text" => "💰 ยอดชำระ:",
                                    "weight" => "bold",
                                    "size" => "sm",
                                    "color" => "#444444",
                                    "flex" => 1
                                ],
                                [
                                    "type" => "text",
                                    "text" => number_format($paymentData['amount'], 2) . " บาท",
                                    "size" => "sm",
                                    "color" => "#444444",
                                    "align" => "end",
                                    "flex" => 2
                                ]
                            ]
                        ]
                    ],
                    "paddingAll" => "10px",
                    "backgroundColor" => "#F5F5F5"
                ],
            ]
        ];
    }

    public function ocrInvoice()
    {
        // ตรวจสอบไฟล์ที่อัปโหลด
        $uploadedFile = $this->request->getFile('image');
        if (!$uploadedFile->isValid() || !in_array($uploadedFile->getClientMimeType(), ['image/jpeg', 'image/png', 'application/pdf'])) {
            return $this->response->setJSON([
                'status' => 'fail',
                'message' => 'กรุณาอัปโหลดไฟล์ที่เป็น JPEG, PNG หรือ PDF เท่านั้น'
            ]);
        }

        // เช็ค Google API Key
        $vision_api_key = getenv('GOOGLE_CLOUD_API_KEY');
        if (!$vision_api_key) {
            return $this->response->setJSON([
                'status' => 'fail',
                'message' => 'ไม่พบ API Key ในไฟล์ .env โปรดตรวจสอบการตั้งค่าของคุณ'
            ]);
        }

        // กำหนดตำแหน่งไฟล์
        $filePath = $uploadedFile->getTempName();
        $ocrResults = [];

        // ตรวจสอบและดำเนินการตามชนิดของไฟล์ (PDF หรือ รูปภาพ)
        if ($uploadedFile->getClientMimeType() === 'application/pdf') {
            // ใช้ฟังก์ชัน extractTextFromPdf ถ้าเป็น PDF
            $ocrResults[] = $this->extractTextFromPdf($filePath);
        } else {
            // ถ้าเป็นไฟล์รูปภาพ ให้แปลงเป็น base64 และประมวลผลด้วย Google Vision API
            $imageContent = base64_encode(file_get_contents($filePath));

            // ประมวลผลข้อความ OCR จากแต่ละภาพ
            $url = "https://vision.googleapis.com/v1/images:annotate?key=$vision_api_key";
            $data = [
                'requests' => [
                    [
                        'image' => ['content' => $imageContent],
                        'features' => [['type' => 'DOCUMENT_TEXT_DETECTION']]
                    ]
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200) {
                $result = json_decode($response, true);
                $ocrResults[] = $result['responses'][0]['fullTextAnnotation']['text'] ?? '';
            }
        }

        if (empty($ocrResults)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบข้อความในไฟล์ หรือ API ไม่สามารถประมวลผลได้'
            ]);
        }

        // รวมข้อความจากทุกหน้าของ PDF
        $text = implode("\n", $ocrResults);

        // เชื่อมต่อกับ OpenAI API
        $openai_api_key = getenv('OPENAI_API_KEY');
        if (!$openai_api_key) {
            return $this->response->setJSON([
                'status' => 'fail',
                'message' => 'ไม่พบ OpenAI API Key ในไฟล์ .env โปรดตรวจสอบการตั้งค่าของคุณ'
            ]);
        }
        // ตรงจำนวนเงินให้แปลงค่าเงินเป็น usd โดยอิงจากค่าเงินวันนี้

        $openai_url = "https://api.openai.com/v1/chat/completions";
        $prompt = "Input $text จาก Input จงแยกแยะข้อมูลชุดนี้โดยข้อมูลที่ต้องการออกมาคือ จำนวนเงิน, สกุลเงิน, วันที่, เวลา, และเลขที่รายการ (ref_no)
                        เสร็จแล้วทำข้อมูลให้อยู่ในรูปแบบ json เท่านั้น โดยไม่ต้องเพิ่มคำอธิบายเพิ่มเติม
                        นี่คือรูปแบบที่ฉันต้องการ {\"amount\":__,\"type\":__,\"date\":__,\"time\":__,\"ref_no\":__}
                        ในส่วนสกุลเงิน ตรวจสอบจำนวนเงินว่าเป็นเงินบาท เงินกีบ หรือ ดอลล่า หากเป็น บาท หรือ กีบ  เงินกีบ = LAK, เงินบาท = THB, เงินดอลล่า = USD
                        ตรงจำนวนเงินให้ส่งกลับมาแค่จำนวนเงินเท่านั้น 
                        **กติกา:** 
                        - ref_no ให้ค้นหาคำว่า เลขที่รายการ
                        - time ให้อยู่ในรูปแบบ H:i ถ้าไม่พบเวลาในข้อมูล ให้ส่งค่าว่าง ''
                        - date ให้เลือกวันที่แรกที่พบในข้อมูล และแปลงเป็นฟอร์แมต YYYY-MM-DD
                        - หากปีที่ระบุเป็น **พ.ศ.** (มากกว่า 2500) ให้แปลงเป็น **ค.ศ.** (โดยการลบ 543)
                        - หากปีมีเพียงเลขสองหลัก:  
                        - ถ้าปีนั้น **มากกว่าปีปัจจุบัน + 35 ปี** ให้ถือว่าเป็น **พ.ศ.** และแปลงเป็น **ค.ศ.** (ลบ 543) เช่น 68 → 2025  
                        - มิฉะนั้น ให้ถือว่าเป็น **ค.ศ.** เช่น 40 → 2040
                        - หากวันที่อยู่ในรูปแบบที่ไม่ชัดเจน เช่น 11-3.25 หรือ 11.3.25 ให้พิจารณาว่าเป็นปี 2025 เดือน 3 วันที่ 11 
                        - รองรับหลายรูปแบบของวันที่ เช่น 11/03/2025, March 11, 2025, 2025-03-11 ฯลฯ
                        - เลือก **วันที่แรกที่พบ** ในข้อมูลเท่านั้น
                        - คืนค่าข้อมูลเป็น JSON เท่านั้น ห้ามมีคำอธิบายเพิ่มเติม";

        $openai_data = [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'คุณต้องส่งคืนเฉพาะ JSON เท่านั้น ห้ามเพิ่มคำอธิบายหรือข้อความใดๆ เพิ่มเติม'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $openai_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $openai_api_key,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($openai_data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // เพิ่ม Timeout
        $openai_response = curl_exec($ch);
        $openai_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($openai_http_code == 200) {
            $openai_result = json_decode($openai_response, true);
            $json_output = $openai_result['choices'][0]['message']['content'] ?? 'ไม่พบผลลัพธ์';

            $jsonArr = json_decode($json_output, true);
            $amount = $jsonArr['amount'] ?? null;
            $date   = $jsonArr['date'] ?? null;
            $time   = $jsonArr['time'] ?? null;
            $ref_no = trim($jsonArr['ref_no'] ?? '');

            // normalize amount → บังคับให้มีทศนิยม 2 หลัก
            if ($amount !== null) {
                $amount = number_format((float)$amount, 2, '.', ''); // 75000 → 75000.00
            }

            // normalize time → ถ้าเป็น H:i ให้เติม :00
            if ($time && strlen($time) === 5) {
                $time .= ':00'; // 11:41 → 11:41:00
            }

            $exists = $this->LoanModel->checkDuplicate($amount, $date, $time, $ref_no);

            if ($exists) {
                return $this->response->setJSON([
                    'status' => 'duplicate',
                    'message' => 'ไฟล์นี้เคยถูกบันทึกแล้ว กรุณาเปลี่ยนไฟล์ใหม่'
                ]);
            }

            // ส่งข้อมูล JSON ที่พร้อมใช้งานไปยัง JavaScript
            return $this->response->setJSON([
                'status' => 'success',
                'ocr_text' => $text,
                'output' => $json_output,
                'json_output' => json_decode($json_output, true) // แปลงเป็น array เพื่อใช้งานใน JS ได้ทันที
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่สามารถติดต่อ OpenAI API ได้',
                'error' => json_decode($openai_response, true)
            ]);
        }
    }

    private function extractTextFromPdf($pdfFilePath)
    {
        // ใช้ Parser เพื่อดึงข้อความจาก PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($pdfFilePath);
        return $pdf->getText();
    }

    // index
    public function report_revenues()
    {
        // $DocumentModel = new \App\Models\DocumentModel();
        // $data['profits'] = $DocumentModel->getrevenue(2022);
        $data['content'] = 'loan/report_revenues';
        $data['title'] = 'รายงานรายรับ/รายจ่าย';
        $data['js_critical'] = '<script src="' . base_url('assets/app/js/loan/report_revenues.js') . '"></script>';
        return view('app', $data);
    }

    public function ajaxTablesReportRevenues($data)
    {
        try {
            // SET CONFIG
            $status = 500;
            $response = [
                'success' => 0,
                'message' => '',
            ];

            $DocumentModel = new \App\Models\DocumentModel();

            $documentmonth      = $DocumentModel->getrevenue($data);
            $loanprocessmonths  = $this->LoanModel->getLoanProcessMonths($data);

            $LoanPaymentMonths  = $this->LoanModel->getListLoanPaymentMonths($data);

            // เตรียม array เดือน 1-12
            $months = range(1, 12);

            $monthNames = [
                1  => 'มกราคม',
                2  => 'กุมภาพันธ์',
                3  => 'มีนาคม',
                4  => 'เมษายน',
                5  => 'พฤษภาคม',
                6  => 'มิถุนายน',
                7  => 'กรกฎาคม',
                8  => 'สิงหาคม',
                9  => 'กันยายน',
                10 => 'ตุลาคม',
                11 => 'พฤศจิกายน',
                12 => 'ธันวาคม',
            ];

            // ค่าเริ่มต้นทุกเดือน = 0
            $receipt     = array_fill(1, 12, 0); // รายรับ(ใบสำคัญรับ) - ตอนนี้จะไม่ใช้แสดงแล้ว แต่เก็บไว้ก่อน
            $expenses    = array_fill(1, 12, 0); // รายจ่าย
            $process     = array_fill(1, 12, 0); // รายรับ(ค่าดำเนินการ)
            $loanPayment = array_fill(1, 12, 0); // ชำระค่างวดจริง
            $classRow    = array_fill(1, 12, ''); // class ไว้ไฮไลท์เดือนปัจจุบัน

            // ----- คำนวณรายเดือนจากใบสำคัญ -----
            foreach ($documentmonth as $doc) {
                $m = (int) $doc->doc_month;
                if (!in_array($m, $months, true)) {
                    continue;
                }

                if ($doc->doc_type === 'ใบสำคัญรับ') {
                    $receipt[$m] = $doc->doc_sum_price;
                } elseif ($doc->doc_type === 'ใบสำคัญจ่าย') {
                    $expenses[$m] = $doc->doc_sum_price;
                }
            }

            // ----- คำนวณรายเดือนจากค่าดำเนินการ -----
            foreach ($loanprocessmonths as $loan) {
                $m = (int) $loan->loan_created_payment;
                if (!in_array($m, $months, true)) {
                    continue;
                }

                $process[$m] = $loan->total_payment_process
                    + $loan->total_tranfer
                    + $loan->total_payment_other;
            }

            // ----- คำนวณรายเดือนจากชำระค่างวดจริง -----
            foreach ($LoanPaymentMonths as $row) {
                $m = (int) $row->loan_created_payment;
                if ($m < 1 || $m > 12) {
                    continue;
                }

                $loanPayment[$m] += $row->setting_land_report_money;
            }

            // ----- ไฮไลท์เดือนปัจจุบัน ถ้าปีตรงกับปีปัจจุบัน -----
            if ($data === date('Y')) {
                $currentMonth = (int) date('m');
                if (isset($classRow[$currentMonth])) {
                    $classRow[$currentMonth] = 'bg-primary-transparent';
                }
            }

            // ----- สร้าง array สำหรับเก็บผลลัพธ์ต่อเดือน -----
            $sumReceiptByMonth       = []; // รายรับรวมต่อเดือน (ค่าดำเนินการ + ชำระค่างวด)
            $netByMonth              = []; // กำไรสุทธิ (รายรับรวม - รายจ่าย)
            $colorNetByMonth         = []; // class สีของกำไรสุทธิ

            $processDiffByMonth      = []; // ส่วนต่างค่าดำเนินการ - รายจ่าย
            $colorProcessDiffByMonth = []; // class สีของส่วนต่างค่าดำเนินการ

            // สำหรับรวมทั้งปีของส่วนต่างค่าดำเนินการ
            $sumProcessDiffRaw = 0;

            foreach ($months as $m) {
                // รายรับรวม = ค่าดำเนินการ + ค่างวด
                $sumReceiptByMonth[$m] = $process[$m] + $loanPayment[$m];

                // กำไรสุทธิ = รายรับรวม - รายจ่าย
                $net            = $sumReceiptByMonth[$m] - $expenses[$m];
                $netByMonth[$m] = $net;

                if ($net < 0) {
                    $colorNetByMonth[$m] = 'tx-danger';
                    $netByMonth[$m]      = number_format($net, 2);
                } elseif ($net > 0) {
                    $colorNetByMonth[$m] = 'tx-success';
                    $netByMonth[$m]      = '+' . number_format($net, 2);
                } else {
                    $colorNetByMonth[$m] = '';
                    $netByMonth[$m]      = number_format($net, 2);
                }

                // ส่วนต่างค่าดำเนินการ = รายรับ(ค่าดำเนินการ) - รายจ่าย
                $processDiff = $process[$m] - $expenses[$m];
                $processDiffByMonth[$m] = $processDiff;
                $sumProcessDiffRaw     += $processDiff;

                if ($processDiff < 0) {
                    $colorProcessDiffByMonth[$m] = 'tx-danger';
                    $processDiffByMonth[$m]      = number_format($processDiff, 2);
                } elseif ($processDiff > 0) {
                    $colorProcessDiffByMonth[$m] = 'tx-success';
                    $processDiffByMonth[$m]      = '+' . number_format($processDiff, 2);
                } else {
                    $colorProcessDiffByMonth[$m] = '';
                    $processDiffByMonth[$m]      = number_format($processDiff, 2);
                }
            }

            // ----- รวมทั้งปี -----
            $sumProcess        = array_sum($process);           // รายรับ(ค่าดำเนินการ)
            $sumLoanPayment    = array_sum($loanPayment);       // รายรับ(ค่างวด)
            $sumReceipt        = array_sum($sumReceiptByMonth); // รายรับรวม
            $sumExpenses       = array_sum($expenses);          // รายจ่ายรวม

            $sumNetRaw         = array_sum(array_map(function ($m) use ($sumReceiptByMonth, $expenses) {
                return $sumReceiptByMonth[$m] - $expenses[$m];
            }, $months));                                       // กำไรรวมสุทธิ (ค่าเลขจริง)

            // ฟอร์แมตกำไรรวมสุทธิ
            if ($sumNetRaw < 0) {
                $sumNet = number_format($sumNetRaw, 2);
            } elseif ($sumNetRaw > 0) {
                $sumNet = ' +' . number_format($sumNetRaw, 2);
            } else {
                $sumNet = number_format($sumNetRaw, 2);
            }

            // ฟอร์แมตส่วนต่างค่าดำเนินการรวมทั้งปี
            if ($sumProcessDiffRaw < 0) {
                $sumProcessDiff = number_format($sumProcessDiffRaw, 2);
            } elseif ($sumProcessDiffRaw > 0) {
                $sumProcessDiff = ' +' . number_format($sumProcessDiffRaw, 2);
            } else {
                $sumProcessDiff = number_format($sumProcessDiffRaw, 2);
            }

            // ----- สร้าง HTML -----
            $html = '
                <div class="card-body">
                    <div class="table-responsive border radius-4 mg-t-5">
                        <table class="table mb-0 border-0">
                            <thead>
                                <tr>
                                    <th class="wd-10p">เดือน</th>
                                    <th class="tx-right wd-15p">รายรับ(ค่าดำเนินการ)</th>
                                    <th class="tx-right wd-15p">รายรับ(ค่างวด)</th>
                                    <th class="tx-right wd-15p">รายรับ(รวม)</th>
                                    <th class="tx-right wd-15p">รายจ่าย</th>
                                    <th class="tx-right wd-15p">ดุลดำเนินการ</th>
                                    <th class="tx-right wd-15p">กำไรสุทธิ</th>
                                </tr>
                            </thead>
                            <tbody>';

            foreach ($months as $m) {
                $monthName = $monthNames[$m];
                $monthId   = str_pad($m, 2, '0', STR_PAD_LEFT);
                $idNoPad   = (string) $m;

                $html .= '
                                <tr class="' . $classRow[$m] . '">
                                    <td>' . $monthName . '</td>
                                    <td class="tx-right" style="text-align: right;">
                                        <a href="javascript:void(0);" data-id="' . $monthId . '" id="Month_Process" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalProcessMonth">'
                    . number_format($process[$m], 2) .
                    '</a>
                                    </td>
                                    <td class="tx-right" style="text-align: right;">
                                        <a href="javascript:void(0);" data-id="' . $idNoPad . '" id="Month_Loan_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanPaymentMonth">'
                    . number_format($loanPayment[$m], 2) .
                    '</a>
                                    </td>
                                    <td class="tx-right">' . number_format($sumReceiptByMonth[$m], 2) . '</td>
                                    <td class="tx-right" style="text-align: right;">
                                        <a href="javascript:void(0);" data-id="' . $monthId . '" id="Month_Expenses" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalExpensesMonth">'
                    . number_format($expenses[$m], 2) .
                    '</a>
                                    </td>
                                    <td class="tx-right">
                                        <span class="' . $colorProcessDiffByMonth[$m] . '">' . $processDiffByMonth[$m] . '</span>
                                    </td>
                                    <td class="tx-right">
                                        <span class="' . $colorNetByMonth[$m] . '">' . $netByMonth[$m] . '</span>
                                    </td>
                                </tr>';
            }

            $html .= '
                                <tr class="bg-primary">
                                    <td colspan="1"></td>
                                    <td class="tx-right" colspan="1">
                                        <h6 class="tx-uppercase mb-0"><b>รายรับ(ค่าดำเนินการ)&nbsp;&nbsp;'
                . number_format($sumProcess, 2) .
                '</b></h6>
                                    </td>
                                    <td class="tx-right" colspan="1">
                                        <h6 class="tx-uppercase mb-0"><b>รายรับ(ค่างวด)&nbsp;&nbsp;'
                . number_format($sumLoanPayment, 2) .
                '</b></h6>
                                    </td>
                                    <td class="tx-right" colspan="1">
                                        <h6 class="tx-uppercase mb-0"><b>รายรับรวม&nbsp;&nbsp;'
                . number_format($sumReceipt, 2) .
                '</b></h6>
                                    </td>
                                    <td class="tx-right" colspan="1">
                                        <h6 class="tx-uppercase mb-0"><b>รายจ่ายรวม&nbsp;&nbsp;'
                . number_format($sumExpenses, 2) .
                '</b></h6>
                                    </td>
                                    <td class="tx-right" colspan="1">
                                        <h6 class="tx-uppercase mb-0"><b>ดุลดำเนินการรวม&nbsp;&nbsp;'
                . $sumProcessDiff .
                '</b></h6>
                                    </td>
                                    <td class="tx-right" colspan="1">
                                        <h6 class="tx-uppercase mb-0"><b>กำไรรวมสุทธิ&nbsp;&nbsp;'
                . $sumNet .
                '</b></h6>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>';

            $response['data']    = $html;
            $response['success'] = 1;
            $response['message'] = '';
            $status              = 200;

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    public function ocrCustomer()
    {
        $json = $this->request->getJSON(true);
        $imageBase64 = $json['image_base64'] ?? null;

        if (empty($imageBase64)) {
            return $this->response->setJSON([
                'status'  => 'fail',
                'message' => 'Missing image_base64'
            ]);
        }

        $vision_api_key = getenv('GOOGLE_CLOUD_API_KEY');
        if (!$vision_api_key) {
            return $this->response->setJSON([
                'status'  => 'fail',
                'message' => 'ไม่พบ API Key ในไฟล์ .env (GOOGLE_CLOUD_API_KEY)'
            ]);
        }

        $url  = "https://vision.googleapis.com/v1/images:annotate?key={$vision_api_key}";
        $data = [
            'requests' => [[
                'image' => ['content' => $imageBase64],
                'features' => [['type' => 'TEXT_DETECTION']],
            ]]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_TIMEOUT        => 30,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ไม่สามารถติดต่อ Google Vision API ได้',
                'error'   => json_decode($response, true)
            ]);
        }

        $result = json_decode($response, true);
        $text   = $result['responses'][0]['fullTextAnnotation']['text'] ?? '';

        if ($text === '') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'OCR ไม่พบบทความ'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'text'   => $text
        ]);
    }
}
