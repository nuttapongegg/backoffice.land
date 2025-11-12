<?php

namespace App\Libraries;

use TCPDF;

class pdfDocPay extends TCPDF
{

    //Page header
    public function Header()
    {
        // Logo
        // $image_file = getenv('CDN_IMG').'/uploads/img/' . $data['website']->logo;
        // $image_file = 'https://stock.psnkp.co/assets/img/up2cars_dark.jpg';
        $image_file = 'https://evxspst.sgp1.cdn.digitaloceanspaces.com/uploads/img/logo_infinitex.jpg';
        /**
         * width : 50
         */
        $this->Image($image_file, 8, 11, 45);
        // Set font
        // $this->SetFont('thsarabun', 'B', 11);
        // $this->SetX(70);
        // $this->Cell(0, 2, 'sobatcoding.com', 0, 1, '', 0, '', 0);
        // Title
        try {
            // ฟอนต์ thsarabun ขนาด 22 (ใหญ่ขึ้น)
            $this->SetFont('thsarabun', 'B', 22);
            $this->Cell(199, 5, 'บริษัท อินฟินิตเอ็กซ์ ไทย จํากัด', 0, 1, 'R');

            // ขนาดปกติ 15 สำหรับบรรทัดต่อ ๆ ไป
            $this->SetFont('thsarabun', '', 13);
            $this->Cell(199, 3, 'ที่อยู่ 11/2 ซอย เลี่ยงเมือง1 ถนนเลี่ยงเมือง ตําบลในเมือง', 0, 1, 'R');
            $this->Cell(199, 3, 'อําเภอเมืองอุบลราชธานี จังหวัดอุบลราชธานี 34000', 0, 1, 'R');

        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }

        // QRCODE,H : QR-CODE Best error correction
        // $this->write2DBarcode('https://sobatcdoing.com', 'QRCODE,H', 0, 3, 20, 20, ['position' => 'R'], 'N');

        // $style = array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        // $this->Line(15, 25, 195, 25, $style);

    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        // $this->SetY(-15);
        // Set font
        // $this->SetFont('helvetica', 'I', 8);
        // Page number
        // $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
