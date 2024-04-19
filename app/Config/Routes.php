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
$routes->get('pdf_loan_receipt/(:any)', 'PdfController::PDF_Loan_Receipt/$1', ['filter' => 'employeeAuth']);

//สินเชื่อ loan
$routes->group('loan', ['filter' => 'employeeAuth'], function ($routes) {
    $routes->get('list', 'Loan::list');
    $routes->get('tableLoan', 'Loan::FetchAllLoanOn');
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
    $routes->get('ajax-graphloan/(:any)', 'Loan::ajaxGraphLoan/$1');
    $routes->get('ajax-summarizereportloan/(:any)', 'Loan::ajaxSummarizeReportLoan/$1');
    $routes->get('ajax-ajaxdatareportloanmonth/(:any)', 'Loan::ajaxDataReportLoanMonth/$1');
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
});

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
