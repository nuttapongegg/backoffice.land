<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override('App\Controllers\Errors::show404');
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Authentication
$routes->get('/', 'Home::index', ['filter' => 'employeeNoAuth']);
$routes->match(['get', 'post'], 'login', 'Authentication::login', ['filter' => 'employeeNoAuth']);
$routes->get('logout', 'Authentication::logout', ['filter' => 'employeeAuth']);

//pdf
$routes->get('pdf_loan/(:any)', 'PdfController::PDF_Loan/$1', ['filter' => 'employeeAuth']);
$routes->get('pdf_installment_schedule/(:any)', 'PdfController::PDF_Installment_Schedule/$1', ['filter' => 'employeeAuth']);
$routes->get('pdf_loan_receipt/(:any)', 'PdfController::PDF_Loan_Receipt/$1');
$routes->get('pdf_doc_pay/(:any)', 'PdfController::PDF_Doc_Pay/$1', ['filter' => 'employeeAuth']);
$routes->get('pdf_loan_pay/(:any)/(:any)', 'PdfController::pdf_Loan_Pay/$1/$2', ['filter' => 'employeeAuth']);
$routes->get('pdf_finx_receipt/(:any)/(:any)', 'PdfController::pdf_Finx_Receipt/$1/$2', ['filter' => 'employeeAuth']);
$routes->get('pdf_finx/(:any)', 'PdfController::PDF_Finx/$1', ['filter' => 'employeeAuth']);
$routes->get('pdf_monthly_statement/(:any)/(:any)', 'PdfController::pdf_MonthlyStatement/$1/$2', ['filter' => 'employeeAuth']);

//สินเชื่อ loan
$routes->group('loan', ['filter' => 'employeeAuth'], function ($routes) {
    $routes->get('list', 'Loan::list');
    $routes->post('tableLoan', 'Loan::FetchAllLoanOn');
    $routes->get('get_emp', 'Loan::GetEmp');
    $routes->post('addLoan', 'Loan::insertDataLoan');
    $routes->post('addPayment', 'Loan::insertDataLoanPayment');
    $routes->get('detail/(:any)', 'Loan::detail/$1');
    $routes->get('detailForm/(:any)', 'Loan::detailForm/$1');
    $routes->post('insertDetailPiture/(:any)', 'Loan::insertDetailPiture/$1');
    $routes->get('fetchOtherPicture/(:any)', 'Loan::fetchOtherPicture/$1');
    $routes->get('delete_other_picture/(:any)', 'Loan::deleteOtherPicture/$1');
    $routes->get('dowloadPictureOther/(:any)', 'Loan::dowloadPictureOther/$1');
    $routes->post('updateLoan', 'Loan::updateLoan');
    $routes->get('cancelLoan/(:any)', 'Loan::offLoan/$1');
    $routes->get('callInstallMent/(:any)', 'Loan::callInstallMent/$1');
    $routes->get('tableListPayment/(:any)', 'Loan::getListPayment/$1');
    $routes->get('list_history', 'Loan::list_history');
    $routes->post('tableLoanHistory', 'Loan::loanHistory');
    $routes->post('ajax-summarizeLoan', 'Loan::ajaxSummarizeLoan');
    $routes->get('report_loan', 'Loan::report_loan');
    $routes->get('ajax-tablesreportloan/(:any)', 'Loan::ajaxTablesReportLoan/$1');
    $routes->get('ajaxdatatablepayment/(:any)', 'Loan::ajaxDataTablePayment/$1');
    $routes->get('ajaxdatatableloan/(:any)', 'Loan::ajaxDataTableLoan/$1');
    $routes->get('ajaxdatatableoverduepayment/(:any)', 'Loan::ajaxDataTableOverduePayment/$1');
    $routes->get('ajaxdatatableprocess/(:any)', 'Loan::ajaxDataTableLoanProcess/$1');
    $routes->get('ajaxdatatablereceipt/(:any)', 'Loan::ajaxDataTableReceipt/$1');
    $routes->get('ajaxdatatableexpenses/(:any)', 'Loan::ajaxDataTableExpenses/$1');
    $routes->get('ajaxdatatablediffpayment/(:any)', 'Loan::ajaxDataTableDiffPayment/$1');
    $routes->get('ajax-graphloan/(:any)', 'Loan::ajaxGraphLoan/$1');
    $routes->get('ajax-summarizereportloan/(:any)', 'Loan::ajaxSummarizeReportLoan/$1');
    $routes->get('ajaxdatareportloanmonth/(:any)', 'Loan::ajaxDataReportLoanMonth/$1');
    $routes->post('update-openloantargetedmonth', 'Loan::updateOpenLoanTargetedMonth');
    $routes->post('update-targetedmonth', 'Loan::updateTargetedMonth');
    $routes->post('update-targeted', 'Loan::updateTargeted');
    $routes->get('tableLoanPayments', 'Loan::FetchAllLoanPayments');
    $routes->get('ajaxdatatableloanpayment/(:any)', 'Loan::ajaxDataTableLoanPayment/$1');
    $routes->get('ajaxdatatableloanclosepayment/(:any)', 'Loan::ajaxDataTableLoanClosePayment/$1');
    $routes->post('save_maplink/(:any)', 'Loan::saveMapLink/$1');
    $routes->post('update_deed_status', 'Loan::updateDeedStatus');

    $routes->get('report_revenues', 'Loan::report_revenues');
    $routes->get('ajax-tablesreportrevenues/(:any)', 'Loan::ajaxTablesReportRevenues/$1');
    $routes->post('ocrCustomer', 'Loan::ocrCustomer');
});

//สินเชื่อ Finx
$routes->group('finx', ['filter' => 'employeeAuth'], function ($routes) {
    $routes->get('list', 'Finx::list');
    $routes->get('tableFinxOn', 'Finx::fetchAllFinxOn');
    $routes->post('tableFinxHistory', 'Finx::finxHistory');
    $routes->post('ajax-summarizeFinx', 'Finx::ajaxSummarizeFinx');

    $routes->get('detail/(:any)', 'Loan::detail/$1');

    $routes->get('report_finx', 'Finx::report_finx');
    $routes->get('ajax-tablesreportfinx/(:any)', 'Finx::ajaxTablesReportFinx/$1');
    $routes->get('ajaxdatatableopenloanfinx/(:any)', 'Finx::ajaxDataTableOpenLoanFinx/$1');
    $routes->get('ajaxdatatableloanfinxpayment/(:any)', 'Finx::ajaxDataTableLoanFinxPayment/$1');
    $routes->get('ajaxdatatableloanfinxclosepayment/(:any)', 'Finx::ajaxDataTableLoanFinxClosePayment/$1');
});

$routes->get('api/list_ai', 'Loan::list_ai');

$routes->group('loanpayment', function ($routes) {
    $routes->get('detail/(:any)', 'Loan::loanPayment/$1');
    $routes->get('detailForm/(:any)', 'Loan::detailForm/$1');
    $routes->get('callInstallMent/(:any)', 'Loan::callInstallMent/$1');
    $routes->get('tableListPayment/(:any)', 'Loan::getListPayment/$1');
    $routes->post('addPaymentNoLogin', 'Loan::insertDataLoanPaymentNoLogin');
    $routes->post('ocrInvoice', 'Loan::ocrInvoice');
});

// ใบสำคัญ
$routes->group('document', ['filter' => 'employeeAuth'], function ($routes) {
    $routes->post('search', 'Document::search');
    $routes->post('getLists', 'Document::getLists');
    $routes->post('store', 'Document::store');
    $routes->get('edit/(:any)', 'Document::edit/$1');
    $routes->get('docNumber/(:any)', 'Document::docNumber/$1');
    $routes->post('update', 'Document::update');
    $routes->post('delete/(:any)', 'Document::delete/$1');
    $routes->get('listTitle', 'Document::listTitle');
    $routes->get('listDoc', 'Document::listDoc');
    $routes->get('print/(:any)', 'Document::print/$1');
    $routes->post('billHistory', 'Document::billHistory/$1');
    $routes->post('aiscript/vehicle-registration-book', 'Document::aiScriptVehicleRegistrationBook');

    $routes->post('ocrInvoice', 'Document::ocrInvoice');
});

// ตั้งค่า
$routes->group('setting_land', ['filter' => 'employeeAuth'], function ($routes) {
    //index
    $routes->get('index', 'Setting::index');
    //land
    $routes->get('land', 'Setting::listLand');
    $routes->post('update-RealInvestment', 'Setting::updateRealInvestment');
    $routes->post('add-land-account', 'Setting::addLandAccount');
    $routes->get('edit-land-account/(:num)', 'Setting::editLandAccount/$1');
    $routes->post('update-land-account', 'Setting::updateLandAccount');
    $routes->get('delete-land-account/(:num)', 'Setting::deleteLandAccount/$1');
    $routes->post('add-land-account-plus', 'Setting::addLandAccountPlus');
    $routes->post('add-land-account-minus', 'Setting::addLandAccountMinus');
    $routes->get('get-land-account', 'Setting::getLandAccount');
    $routes->post('transfer-land-account', 'Setting::TransferLandAccount');
    $routes->post('ajax-tableslandaccountlogs', 'Setting::ajaxTablesLandAccountLogs');
    $routes->post('ajax-tableslandaccountreport/(:num)', 'Setting::ajaxTablesLandAccounReport/$1');

    //notify
    $routes->get('edit-overdue-status', 'Setting::editSettingOverdueStatus');
    $routes->post('update-overdue-status', 'Setting::updateSettingOverdueStatus');
});

$routes->get('Maps', 'Map::index', ['filter' => 'employeeAuth']);

// API
$routes->group('api', ['namespace' => 'App\Controllers\api'], function ($routes) {
    $routes->get('ajaxdatatablelandpayment/(:any)/(:any)', 'Loan::ajaxDataTableLandPayment/$1/$2');
    $routes->get('ajaxdatatablelandpaymenttoday', 'Loan::ajaxDataTableLandPaymentToDay');
    $routes->get('ajaxdatatablelandday/(:any)', 'Loan::ajaxDataTableLandDay/$1');

    $routes->get('landdatadocday', 'Loan::LandDataDocDay');
    $routes->get('ajaxlandnetprofitbyyear/(:any)', 'Loan::ajaxLandNetProfitMonthlyByYear/$1');
    $routes->get('ajaxlandmonthsummarybyyear/(:num)', 'Loan::ajaxLandMonthlySummaryByYear/$1');
});

// $routes->group('cronjob', ['namespace' => 'App\Controllers\cronjob'], function ($routes) {
//     $routes->get('loanstatus', 'LoanStatus::run');
//     $routes->get('loansenddailysummary', 'LoanSendDailySummary::run');
//     $routes->get('land_logs', 'Landlogs::land_logs');
// });

/*
 * --------------------------------------------------------------------
 * Helper
 * --------------------------------------------------------------------
 */

// log in footer
$routes->post('footer/ajax-datatable', 'EmployeeLog::ajaxDataTablesInFooter', ['filter' => 'employeeAuth']);

/*
 * --------------------------------------------------------------------
 * CRONJOB
 * --------------------------------------------------------------------
 */
// บันทึก logs
$routes->cli('cronjob/land_logs', 'Landlogs::land_logs', ['namespace' => 'App\Controllers\cronjob']);


// ส่งแจ้งเตือนสินเชื่อ
$routes->cli('cronjob/loanstatus', 'LoanStatus::run', ['namespace' => 'App\Controllers\cronjob']);

// ส่งแจ้งเตือนรายการสินเชื่อประจำวัน
$routes->cli('cronjob/loansenddailysummary', 'LoanSendDailySummary::run', ['namespace' => 'App\Controllers\cronjob']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
