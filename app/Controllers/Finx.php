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

class Finx extends BaseController
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

        $data['content'] = 'finx/list';
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
            <script src="' . base_url('/assets/app/js/finx/list.js?v=' . time()) . '"></script> 
            <script src="' . base_url('/assets/app/js/finx/list_history.js?v=' . time()) . '"></script> 
        ';

        $data['employee'] = $this->EmployeeModel->getEmployeeByID(session()->get('employeeID'));
        $data['land_accounts'] = $this->SettingLandModel->getSettingLandAll();

        echo view('/app', $data);
    }

    public function fetchAllFinxOn()
    {
        $date = $this->request->getGet('date') ?? '';

        $data_loanOn = $this->LoanModel->getAllDataFinxOn($date);

        return $this->response->setJSON([
            'status' => 200,
            'error' => false,
            'message' => json_encode($data_loanOn)
        ]);
    }

    public function finxHistory()
    {
        $post = $this->request->getPost();
        // $param['data'] = $_REQUEST['date'] ?? '';
        // px($post);
        $rows            = $this->LoanModel->_getAllDataFinxHistory($post);
        $recordsTotal    = (int) $this->LoanModel->countAllDataFinxHistory();
        $recordsFiltered = (int) $this->LoanModel->countFilteredDataFinxHistory($post);

        return $this->response->setJSON([
            'draw'            => (int)($post['draw'] ?? 1),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $rows,
        ]);
    }


    public function ajaxSummarizeFinx()
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

            $datas = $this->LoanModel->getAllDataFinx();

            $loan_summary_no_vat = 0;
            $loan_payment_sum_installment = 0;
            $loan_summary_all = 0;
            $summary_all = 0;
            $loan_payment_month = 0;

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

                // if ($data->loan_payment_sum_installment != '') {
                //     $loan_payment_sum_installment = $loan_payment_sum_installment + $data->loan_payment_sum_installment;
                // }

                if ($data->loan_summary_all != '') {
                    $loan_summary_all = $loan_summary_all + $data->loan_summary_all;
                }

                if ($data->loan_status == 'ON_STATE') {
                    $sum_installment = $sum_installment + $data->loan_payment_sum_installment;
                    $summary_no_vat_ON_STATE = $summary_no_vat_ON_STATE + $data->loan_summary_no_vat;
                }

                if ($data->loan_status == 'CLOSE_STATE') {
                    $summary_no_vat_CLOSE_STATE = $summary_no_vat_CLOSE_STATE + $data->loan_summary_no_vat;
                }
            }

            $loan_payment_month = $summary_no_vat_ON_STATE * 0.03;

            $installment_3pct = $loan_summary_no_vat * 0.03;

            $summary_net_assets = $summary_no_vat_ON_STATE + $sum_land_account;

            $html_SummarizeFinx =
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
                                <div class="font-weight-semibold mb-1 tx-secondary">' . number_format($installment_3pct, 2) . '</div>
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

            $html_summarizeFinx =
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
                                        <p class="mb-0 font-weight-semibold tx-18">ทรัพย์สินสุทธิ</p>
                                        <div class="mt-2">
                                            <span class="mb-0 font-weight-semibold tx-15">' . number_format($summary_net_assets, 2) . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                ';

            $response['data_summarizeFinx'] = $html_summarizeFinx;

            $response['data_SummarizeFinx'] = $html_SummarizeFinx;

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

    public function report_finx()
    {
        $TargetedModel = new \App\Models\TargetedModel();
        $data['targeteds'] = $TargetedModel->getTargetedAll();

        $data['LoanClosePaymentMonths'] = $this->LoanModel->getListLoanFinxClosePaymentMonths(date('Y'));
        $data['OpenLoanMonths'] = $this->LoanModel->getOpenLoanFinx(date('Y'));

        $data['content'] = 'finx/report_finx';
        $data['title'] = 'รายงานสินเชื่อ';
        $data['css_critical'] = '';
        $data['js_critical'] = '
            <script src="' . base_url('/assets/js/apexcharts.js') . '"></script>
            <script src="' . base_url('/assets/js/report-loan-index-5.js') . '"></script>
            <script src="' . base_url('/assets/app/js/finx/report_finx.js?v=' . time()) . '"></script> 
        ';

        return view('app', $data);
    }

    public function ajaxTablesReportFinx($data)
    {
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            $OpenLoans = $this->LoanModel->getOpenLoanFinx($data);
            $LoanClosePaymentMonths = $this->LoanModel->getListLoanFinxClosePaymentMonths($data);
            $DocumentsPayYears = $this->DocumentModel->getDocumentsPayYear($data);

            // กำหนดค่าเริ่มต้น 0 ทั้ง 12 เดือน
            $Month_Open_Loan = array_fill(1, 12, 0);

            // รวมยอดเปิดสินเชื่อตามเดือน
            foreach ($OpenLoans as $OpenLoan) {
                $month = (int)$OpenLoan->loan_month;
                if ($month >= 1 && $month <= 12) {
                    $Month_Open_Loan[$month] += $OpenLoan->loan_summary_no_vat;
                }
            }

            // สร้าง array เก็บค่าเริ่มต้น 0 ทุกเดือน
            $Month_Loan_Close_Payment = array_fill(1, 12, 0);
            $Month_Loan_Payment       = array_fill(1, 12, 0); // 3%
            $Month_Payment            = array_fill(1, 12, 0); // รวม

            // รวมยอดปิดสินเชื่อตามเดือน
            foreach ($LoanClosePaymentMonths as $LoanClosePaymentMonth) {
                $month = (int)$LoanClosePaymentMonth->loan_date_close_month;

                if ($month >= 1 && $month <= 12) {
                    $Month_Loan_Close_Payment[$month] += $LoanClosePaymentMonth->loan_summary_no_vat;
                }
            }

            // คำนวณ 3% และรวมยอด
            foreach ($Month_Loan_Close_Payment as $m => $amount) {
                if ($amount > 0) {
                    $Month_Loan_Payment[$m] = $amount * 0.03; // 3%
                    $Month_Payment[$m]      = $amount + $Month_Loan_Payment[$m]; // รวม
                }
            }

            // กำหนดค่าเริ่มต้น 0 ทั้ง 12 เดือน
            $Month_Doc_Pay_Years = array_fill(1, 12, 0);

            // รวมยอดเปิดสินเชื่อตามเดือน
            foreach ($DocumentsPayYears as $DocumentsPayYear) {
                $month = (int)$DocumentsPayYear->doc_date_month;
                if ($month >= 1 && $month <= 12) {
                    $Month_Doc_Pay_Years[$month] += $DocumentsPayYear->price;
                }
            }

            // กำหนดค่าเริ่มต้น class ของแต่ละเดือน
            $Month_Class = array_fill(1, 12, '');

            // ถ้าปีที่เลือกเป็นปีปัจจุบัน ให้ใส่ class ของเดือนปัจจุบัน
            if ($data === date('Y')) {
                $currentMonth = (int)date('m');
                $Month_Class[$currentMonth] = "bg-primary-transparent";
            }

            // รวมค่าแบบใช้ array_sum
            $Month_Payment_Sum = array_sum($Month_Payment);
            $Month_Open_Loan_Sum = array_sum($Month_Open_Loan);
            $Month_Loan_Payment_Sum = array_sum($Month_Loan_Payment);
            $Month_Loan_Close_Payment_Sum = array_sum($Month_Loan_Close_Payment);
            $Month_Doc_Pay_Years_Sum = array_sum($Month_Doc_Pay_Years);
            // ---------------- HTML ----------------
            $html =
                '<div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-0 mb-0">
                            <tbody>
                                <tr>
                                    <th class="border-top-0 bg-black-03 br-bs-5 br-ts-5 tx-15 wd-18p">เดือน</th>
                                    <th class="border-top-0 bg-black-03 tx-15 wd-12p tx-right">เปิดสินเชื่อ</th>
                                    <th class="border-top-0 bg-black-03 tx-15 wd-12p tx-right">ยอดรวมสินเชื่อ</th>
                                    <th class="border-top-0 bg-black-03 tx-15 wd-12p tx-right">ชำระปิดบัญชี</th>
                                    <th class="border-top-0 bg-black-03 tx-15 wd-12p tx-right">ชำระค่าธรรมเนียม</th>
                                    <th class="border-top-0 bg-black-03 tx-15 wd-5p tx-right"></th>
                                    <th class="border-top-0 bg-black-03 tx-15 wd-12p tx-right">รายจ่าย</th>
                                    <th class="border-top-0 bg-black-03 tx-15 wd-5p tx-right"></th>
                                    <th class="border-top-0 bg-black-03 tx-15 wd-12p tx-right">รวมรับ/จ่าย</th>
                                    <th class="border-top-0 bg-black-03 tx-15 wd-5p tx-right"></th>
                                </tr>';

            // loop เดือนทั้งหมด
            $months = [
                1 => "มกราคม",
                2 => "กุมภาพันธ์",
                3 => "มีนาคม",
                4 => "เมษายน",
                5 => "พฤษภาคม",
                6 => "มิถุนายน",
                7 => "กรกฏาคม",
                8 => "สิงหาคม",
                9 => "กันยายน",
                10 => "ตุลาคม",
                11 => "พฤศจิกายน",
                12 => "ธันวาคม"
            ];

            foreach ($months as $m => $monthName) {
                $html .= '
                <tr class="' . $Month_Class[$m] . '">
                    <td class="border-top-0 pt-4"><a>' . $monthName . '</a></td>
                    <td class="border-top-0" style="text-align: right;">
                        <a href="javascript:void(0);" data-id="' . $m . '" id="Month_Open_Loan" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalOpenLoanMonth">'
                    . number_format($Month_Open_Loan[$m], 2) . '</a>
                    </td>
                    <td class="border-top-0" style="text-align: right;">' . number_format($Month_Payment[$m], 2) . '</td>
                    <td class="border-top-0" style="text-align: right;">
                        <a href="javascript:void(0);" data-id="' . $m . '" id="Month_Loan_Close_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanClosePaymentMonth">'
                    . number_format($Month_Loan_Close_Payment[$m], 2) . '</a>
                    </td>
                    <td class="border-top-0" style="text-align: right;">
                        <a href="javascript:void(0);" data-id="' . $m . '" id="Month_Loan_Payment" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalLoanPaymentMonth">'
                    . number_format($Month_Loan_Payment[$m], 2) . '</a>
                    </td>
                    <td class="border-top-0 p-0">
                        <div class="d-flex justify-content-center align-items-center" style="height:100%;">
                            <button type="button" class="btn btn-primary-light btn-icon pdf_finx_receipt"
                                data-month="' . $m . '" data-year="' . $data . '">
                                <i class="fe fe-printer"></i>
                            </button>
                        </div>
                    </td>
                    <td class="border-top-0" style="text-align: right;">
                        <a href="javascript:void(0);" data-id="' . $m . '" id="Month_Doc_Pay_Month" name="' . $data . '" data-bs-toggle="modal" data-bs-target="#modalDocumentsPayMonth">'
                    . number_format($Month_Doc_Pay_Years[$m], 2) . '</a>
                    </td>
                    <td class="border-top-0 p-0">
                        <div class="d-flex justify-content-center align-items-center" style="height:100%;">
                            <button type="button" class="btn btn-primary-light btn-icon pdf_loan_pay"
                                data-month="' . $m . '" data-year="' . $data . '">
                                <i class="fe fe-printer"></i>
                            </button>
                        </div>
                    </td>
                    <td class="border-top-0" style="text-align: right;">' . number_format($Month_Loan_Payment[$m] - $Month_Doc_Pay_Years[$m], 2) . '</td>
                    <td class="border-top-0 p-0">
                        <div class="d-flex justify-content-center align-items-center" style="height:100%;">
                            <button type="button" class="btn btn-primary-light btn-icon pdf_monthly_statement"
                                data-month="' . $m . '" data-year="' . $data . '">
                                <i class="fe fe-printer"></i>
                            </button>
                        </div>
                    </td>
                </tr>';
            }

            // แถวสรุปรวม
            $html .= '
                            <tr class="bg-primary">
                                <td class="border-top-0 pt-4">
                                    <p class="tx-left mb-0">ยอดรวม</p>
                                </td>
                                <td class="border-top-0" style="text-align: right;">
                                    <p class="mb-0">' . number_format($Month_Open_Loan_Sum, 2) . '</p>
                                </td>
                                <td class="border-top-0" style="text-align: right;">
                                    <p class="mb-0">' . number_format($Month_Payment_Sum, 2) . '</p>
                                </td>
                                <td class="border-top-0" style="text-align: right;">
                                    <p class="mb-0">' . number_format($Month_Loan_Close_Payment_Sum, 2) . '</p>
                                </td>
                                <td class="border-top-0" style="text-align: right;">
                                    <p class="mb-0">' . number_format($Month_Loan_Payment_Sum, 2) . '</p>
                                </td>
                                <td class="border-top-0">
                                    <p class="mb-0"></p>
                                </td>
                                <td class="border-top-0" style="text-align: right;">
                                    <p class="mb-0">' . number_format($Month_Doc_Pay_Years_Sum, 2) . '</p>
                                </td>
                                <td class="border-top-0">
                                    <p class="mb-0"></p>
                                </td>
                                <td class="border-top-0" style="text-align: right;">
                                    <p class="mb-0">' . number_format($Month_Loan_Payment_Sum - $Month_Doc_Pay_Years_Sum, 2) . '</p>
                                </td>
                                <td class="border-top-0">
                                    <p class="mb-0"></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>';

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

    public function ajaxDataTableLoanFinxClosePayment($id)
    {
        $LoanModel = new \App\Models\LoanModel();

        $param['search_value'] = $_REQUEST['search']['value'] ?? '';
        $param['draw']         = $_REQUEST['draw'] ?? 1;
        $param['start']        = $_REQUEST['start'] ?? 0;
        $param['length']       = $_REQUEST['length'] ?? 10;
        $param['month']        = $id;
        $param['years']        = $_REQUEST['years'] ?? date('Y');

        // ✅ mapping คอลัมน์จริงกับ index ของ DataTables
        // โครงตาราง: [0]=ลำดับ, [1]=loan_code, [2]=customer_fullname, [3]=loan_employee, [4]=loan_close_payment, [5]=loan_date_close
        $columns = [
            0 => null, // ลำดับ ไม่ต้อง sort
            1 => 'loan.loan_code',
            2 => 'loan_customer.customer_fullname',
            3 => 'loan.loan_employee',
            4 => 'loan.loan_close_payment',
            5 => 'loan.loan_date_close'
        ];

        $order_col_index = $_REQUEST['order'][0]['column'] ?? 5;
        $order_dir_req   = $_REQUEST['order'][0]['dir'] ?? 'DESC';
        $order_dir       = strtoupper($order_dir_req) === 'ASC' ? 'ASC' : 'DESC';

        if (!isset($columns[$order_col_index]) || $columns[$order_col_index] === null) {
            $param['order_column'] = 'loan.loan_date_close';
        } else {
            $param['order_column'] = $columns[$order_col_index];
        }
        $param['order_dir'] = $order_dir;

        // ✅ เลือก query ให้เหมาะกับการ search / ไม่ search
        if (!empty($param['search_value'])) {
            $total_count = $LoanModel->getDataTableLoanFinxClosePaymentMonthSearchCount($param);
            $data_month  = $LoanModel->getDataTableLoanFinxClosePaymentMonthSearch($param);
        } else {
            $total_count = $LoanModel->getDataTableLoaFinxClosePaymentMonthCount($param);
            $data_month  = $LoanModel->getDataTableLoanFinxClosePaymentMonth($param);
        }

        // ✅ total_count ควร return แค่จำนวน ไม่ใช่ result set
        $recordsTotal = is_array($total_count) ? count($total_count) : (int)$total_count;

        // ✅ สร้างข้อมูลสำหรับ DataTables
        $i = $param['start'];
        $data = [];
        foreach ($data_month as $datas) {
            $i++;
            $data[] = [
                $i,
                $datas->loan_code,
                !empty($datas->customer_fullname) ? $datas->customer_fullname : '-',
                $datas->loan_employee,
                number_format(!empty($datas->loan_close_payment) ? $datas->loan_close_payment : 0, 2),
                dateThaiDM($datas->loan_date_close),
            ];
        }

        $json_data = [
            "draw"            => intval($param['draw']),
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
            "data"            => $data
        ];

        echo json_encode($json_data);
    }

    public function ajaxDataTableLoanFinxPayment($id)
    {
        $LoanModel = new \App\Models\LoanModel();

        $param['search_value'] = $_REQUEST['search']['value'] ?? '';
        $param['draw']         = $_REQUEST['draw'] ?? 1;
        $param['start']        = $_REQUEST['start'] ?? 0;
        $param['length']       = $_REQUEST['length'] ?? 10;
        $param['month']        = $id;
        $param['years']        = $_REQUEST['years'] ?? date('Y');

        // ✅ mapping คอลัมน์จริงกับ index ของ DataTables
        // โครงตาราง: [0]=ลำดับ, [1]=loan_code, [2]=customer_fullname, [3]=loan_employee, [4]=loan_payment_3percent, [5]=loan_date_close
        $columns = [
            0 => null, // ลำดับ (ไม่ต้อง sort)
            1 => 'loan.loan_code',
            2 => 'loan_customer.customer_fullname',
            3 => 'loan.loan_employee',
            4 => 'loan_payment_3percent',   // alias จาก SQL
            5 => 'loan.loan_date_close'
        ];

        $order_col_index = $_REQUEST['order'][0]['column'] ?? 5;
        $order_dir_req   = $_REQUEST['order'][0]['dir'] ?? 'DESC';
        $order_dir       = strtoupper($order_dir_req) === 'ASC' ? 'ASC' : 'DESC';

        if (!isset($columns[$order_col_index]) || $columns[$order_col_index] === null) {
            $param['order_column'] = 'loan.loan_date_close';
        } else {
            $param['order_column'] = $columns[$order_col_index];
        }
        $param['order_dir'] = $order_dir;

        // ✅ เลือก query ให้เหมาะกับ search / no search
        if (!empty($param['search_value'])) {
            $total_count = $LoanModel->getDataTableLoanFinxPaymentMonthSearchCount($param);
            $data_month  = $LoanModel->getDataTableLoanFinxPaymentMonthSearch($param);
        } else {
            $total_count = $LoanModel->getDataTableLoanFinxPaymentMonthCount($param);
            $data_month  = $LoanModel->getDataTableLoanFinxPaymentMonth($param);
        }

        // ✅ total_count ควร return แค่จำนวน
        $recordsTotal = is_array($total_count) ? count($total_count) : (int)$total_count;

        // ✅ เตรียมข้อมูลสำหรับ DataTables
        $i = $param['start'];
        $data = [];
        foreach ($data_month as $datas) {
            $i++;
            $data[] = [
                $i,
                $datas->loan_code,
                !empty($datas->customer_fullname) ? $datas->customer_fullname : '-',
                $datas->loan_employee,
                number_format(!empty($datas->loan_payment_3percent) ? $datas->loan_payment_3percent : 0, 2),
                dateThaiDM($datas->loan_date_close),
            ];
        }

        $json_data = [
            "draw"            => intval($param['draw']),
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
            "data"            => $data
        ];

        echo json_encode($json_data);
    }

    public function ajaxDataTableOpenLoanFinx($id)
    {
        $LoanModel = new \App\Models\LoanModel();

        $param['search_value'] = $_REQUEST['search']['value'] ?? '';
        $param['draw']         = $_REQUEST['draw'] ?? 1;
        $param['start']        = $_REQUEST['start'] ?? 0;
        $param['length']       = $_REQUEST['length'] ?? 10;
        $param['month']        = $id;
        $param['years']        = $_REQUEST['years'] ?? date('Y');

        // ✅ mapping คอลัมน์จริงกับ index ของ DataTables
        // โครงตาราง: [0]=ลำดับ, [1]=loan_code, [2]=loan_customer, [3]=loan_employee, [4]=land_account_name, [5]=loan_summary_no_vat, [6]=loan_date
        $columns = [
            0 => null, // index ไม่ sort
            1 => 'loan.loan_code',
            2 => 'loan_customer.customer_fullname',
            3 => 'loan.loan_employee',
            4 => 'loan.loan_summary_no_vat',
            5 => 'loan.loan_date_promise'
        ];

        $order_col_index = $_REQUEST['order'][0]['column'] ?? 5;
        $order_dir_req   = $_REQUEST['order'][0]['dir'] ?? 'DESC';
        $order_dir       = strtoupper($order_dir_req) === 'ASC' ? 'ASC' : 'DESC';

        if (!isset($columns[$order_col_index]) || $columns[$order_col_index] === null) {
            $param['order_column'] = 'loan.loan_date_promise';
        } else {
            $param['order_column'] = $columns[$order_col_index];
        }
        $param['order_dir'] = $order_dir;

        // ✅ query
        if (!empty($param['search_value'])) {
            $total_count = $LoanModel->getDataTableOpenLoanFinxMonthSearchCount($param);
            $data_month  = $LoanModel->getDataTableOpenLoanFinxMonthSearch($param);
        } else {
            $total_count = $LoanModel->getDataTableOpenLoanFinxMonthCount($param);
            $data_month  = $LoanModel->getDataTableOpenLoanFinxMonth($param);
        }

        $i = $param['start'];
        $data = [];
        foreach ($data_month as $datas) {
            $i++;
            $data[] = [
                $i,
                $datas->loan_code,
                !empty($datas->customer_fullname) ? $datas->customer_fullname : '-',
                $datas->loan_employee,
                number_format($datas->loan_summary_no_vat ?? 0, 2),
                $datas->loan_date,
            ];
        }

        $json_data = [
            "draw"            => intval($param['draw']),
            "recordsTotal"    => $total_count,
            "recordsFiltered" => $total_count,
            "data"            => $data
        ];

        echo json_encode($json_data);
    }
}
