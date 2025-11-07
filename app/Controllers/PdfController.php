<?php

namespace App\Controllers;

use TCPDF;
use App\Libraries\pdfLoanReceipt as PDFLoanReceipt;
use App\Libraries\pdfDocPay as pdfDocPay;
use App\Libraries\pdfFinxReceipt as pdfFinxReceipt;

class PdfController extends BaseController
{
    public function PDF_Loan($id = null)
    {
        $this->LoanModel = new \App\Models\LoanModel();
        $data['loan'] = $this->LoanModel->getAllDataLoanByCode($id);

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('หนังสือสัญญากู้เงิน');

        // set default header data
        // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        // $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+10, PDF_MARGIN_RIGHT);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM - 10);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.

        $pdf->SetFont('thsarabun', '', 13, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('P', 'A4');

        //view mengarah ke invoice.php
        $html = view('pdf/loan_report.php', $data);

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 1, 0, 0, true, '', true);

        // ---------------------------------------------------------
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('PDF_Loan_' . $id . '.pdf', 'I');
    }

    public function PDF_Installment_Schedule($id = null)
    {
        $this->LoanModel = new \App\Models\LoanModel();
        $data['loan'] = $this->LoanModel->getAllDataLoanByCode($id);

        $data['installments'] = $this->LoanModel->getListPayments($id);

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('ตารางการผ่อนชำระ');

        // set default header data
        // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        // $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+10, PDF_MARGIN_RIGHT);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM - 10);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.

        $pdf->SetFont('thsarabun', '', 13, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('P', 'A4');

        //view mengarah ke invoice.php
        $html = view('pdf/installment_schedule.php', $data);

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 1, 0, 0, true, '', true);

        // ---------------------------------------------------------
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('PDF_Installment_Schedule_' . $id . '.pdf', 'I');
    }
    public function PDF_Loan_Receipt($id = null)
    {
        $this->LoanModel = new \App\Models\LoanModel();
        $data['installments'] = $this->LoanModel->getListPaymentByID($id);

        $data['loan'] = $this->LoanModel->getAllDataLoanByCode($data['installments']->loan_code);


        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('ใบเสร็จรับเงิน');


        // set default header data
        // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        // $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+10, PDF_MARGIN_RIGHT);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM - 10);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.

        $pdf->SetFont('thsarabun', '', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('P', 'A4');

        //view mengarah ke invoice.php
        $html = view('pdf/loan_receipt.php', $data);

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);

        // ---------------------------------------------------------
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('PDF_Loan_Receipt_' . $data['installments']->loan_code . '-' . $data['installments']->loan_payment_installment . '.pdf', 'I');
    }

    public function PDF_Doc_Pay($id = null)
    {
        $this->DocumentModel = new \App\Models\DocumentModel();
        $data['docid'] = $this->DocumentModel->getDocumentID(hashidsDecrypt($id));

        // if($data['docid']->customer_id != 0){
        //     $this->CustomerModel = new \App\Models\CustomerModel();
        //     $data['customer'] = $this->CustomerModel->getCustomerByID($data['docid']->customer_id);
        // }
        function _hex2rgb($color)
        {
            $color = str_replace('#', '', $color);
            if (strlen($color) != 6) {
                return array(0, 0, 0);
            }
            $rgb = array();
            for ($x = 0; $x < 3; $x++) {
                $rgb[$x] = hexdec(substr($color, (2 * $x), 2));
            }
            return $rgb;
        }
        // $data['autoloan'] = $this->AutoloanModel->getAllDataLoanByCode($data['installments']->autoloan_code);
        $docTitle  = 'ใบสำคัญจ่าย';

        // create new PDF document
        $pdf = new pdfDocPay(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle($docTitle);

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT - 11, PDF_MARGIN_TOP + 13, PDF_MARGIN_RIGHT - 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM - 10);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('thsarabun', '', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('P', 'A4');

        //view mengarah ke invoice.php
        $html = view('pdf/pdf_doc_pay.php', $data);

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);

        // ---------------------------------------------------------
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('PDF_Document_' . $data['docid']->doc_number . '.pdf', 'I');
    }

    public function pdf_Loan_Pay($month, $years)
    {
        $this->DocumentModel = new \App\Models\DocumentModel();

        $param['month'] = $month;
        $param['years'] = $years;
        $data['month'] = $years . '-' . $month . '-1';
        $data['revenue_date'] = $month . '-' . $years;
        $data['years'] = $years;
        $data['documentpay'] = $this->DocumentModel->getDocumentsPayMonthAll($param);
        
        // if($data['docid']->customer_id != 0){
        //     $this->CustomerModel = new \App\Models\CustomerModel();
        //     $data['customer'] = $this->CustomerModel->getCustomerByID($data['docid']->customer_id);
        // }
        // function _hex2rgb($color)
        // {
        //     $color = str_replace('#', '', $color);
        //     if (strlen($color) != 6) {
        //         return array(0, 0, 0);
        //     }
        //     $rgb = array();
        //     for ($x = 0; $x < 3; $x++) {
        //         $rgb[$x] = hexdec(substr($color, (2 * $x), 2));
        //     }
        //     return $rgb;
        // }
        // $data['autoloan'] = $this->AutoloanModel->getAllDataLoanByCode($data['installments']->autoloan_code);
        $docTitle  = 'ใบสำคัญจ่าย';

        // create new PDF document
        $pdf = new pdfDocPay(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle($docTitle);

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT - 11, PDF_MARGIN_TOP + 13, PDF_MARGIN_RIGHT - 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM - 10);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('thsarabun', '', 13, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('P', 'A4');

        //view mengarah ke invoice.php
        $html = view('pdf/pdf_loan_pay.php', $data);

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);

        // ---------------------------------------------------------
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('PDF_Loan_Pay_' . $month . '-' . $years . '.pdf', 'I');
    }

    public function pdf_Finx_Receipt($month, $years)
    {
        $this->LoanModel = new \App\Models\LoanModel();

        $param['month'] = $month;
        $param['years'] = $years;
        $data['finxpayments'] = $this->LoanModel->getFinxPaymentMonth($param);
        // if($data['docid']->customer_id != 0){
        //     $this->CustomerModel = new \App\Models\CustomerModel();
        //     $data['customer'] = $this->CustomerModel->getCustomerByID($data['docid']->customer_id);
        // }
        // function _hex2rgb($color)
        // {
        //     $color = str_replace('#', '', $color);
        //     if (strlen($color) != 6) {
        //         return array(0, 0, 0);
        //     }
        //     $rgb = array();
        //     for ($x = 0; $x < 3; $x++) {
        //         $rgb[$x] = hexdec(substr($color, (2 * $x), 2));
        //     }
        //     return $rgb;
        // }
        // $data['autoloan'] = $this->AutoloanModel->getAllDataLoanByCode($data['installments']->autoloan_code);
        $docTitle  = 'ใบสำคัญรับ';

        // create new PDF document
        $pdf = new pdfFinxReceipt(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle($docTitle);

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT - 11, PDF_MARGIN_TOP + 13, PDF_MARGIN_RIGHT - 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM - 10);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('thsarabun', '', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('P', 'A4');

        //view mengarah ke invoice.php
        $html = view('pdf/pdf_finx_receipt.php', $data);

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);

        // ---------------------------------------------------------
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('PDF_Finx_Receipt_' . $month . '-' . $years . '.pdf', 'I');
    }

    public function PDF_Finx($id = null)
    {
        $this->LoanModel = new \App\Models\LoanModel();
        $data['finx'] = $this->LoanModel->getAllDataLoanByCode($id);

        // $data['autoloan'] = $this->AutoloanModel->getAllDataLoanByCode($data['installments']->autoloan_code);
        $docTitle  = 'ใบสำคัญจ่าย';

        // create new PDF document
        $pdf = new pdfFinxReceipt(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle($docTitle);

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT - 11, PDF_MARGIN_TOP + 13, PDF_MARGIN_RIGHT - 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM - 10);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('thsarabun', '', 12, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage('P', 'A4');

        //view mengarah ke invoice.php
        $html = view('pdf/pdf_finx.php', $data);

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);

        // ---------------------------------------------------------
        $this->response->setContentType('application/pdf');
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('PDF_Finx_' . $data['finx']->loan_code . '.pdf', 'I');
    }
}
