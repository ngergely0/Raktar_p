<?php
require('tfpdf.php');
require('CsvTools.php');
require('ShelvesDbtools.php');



class PDF extends tFPDF
{



// Colored table
function Header()
{
    // Logo
    $this->Image('img/warehouse-logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('DejaVu','',26);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,'Raktár',0,0,'C');
    // Line break
    $this->Ln(30);
}
function FancyTable($header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    // Header
    $w = array(50, 25, 55, 30, 15);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row['name'],'LR',0,'C',$fill);
        $this->Cell($w[1],6,$row['shelf_line'],'LR',0,'C',$fill);
        $this->Cell($w[2],6,$row['item_name'],'LR',0,'C',$fill);
        $this->Cell($w[3],6,number_format($row['id']),'LR',0,'C',$fill);
        $this->Cell($w[4],6,number_format($row['quantity']),'LR',0,'C',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}
}

$pdf = new PDF();

$shelvesDbTool = new ShelvesDbTools();

$data = $shelvesDbTool->getAll();
// Column headings
$header = array('Raktár', 'Polc', 'Termék', 'Mennyiség', 'id');


// Data loading
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->SetFont('DejaVu','',14);
$pdf->AddPage();
$pdf->SetLeftMargin(18);
$pdf->FancyTable($header,$data);
$pdf->Output('F', 'pdf/raktar.pdf');
?>