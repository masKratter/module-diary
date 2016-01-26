<?php
/* -------------------------------------------------------------------------- *\
|* -[ moule-diary - Training Export ]---------------------------------- *|
\* -------------------------------------------------------------------------- */
require_once("../core/api.inc.php");
//api_loadModule(array("materials","registries"));
api_loadModule();

// get objects
global $training;
 $training=api_moduleDiary_training($_GET['idTraining']);
// check if project exist
if(!$training->id){die(api_text("trainingNotFound"));}
// include the TCPDF library
require_once('../core/tcpdf/tcpdf.php');
// extend the TCPDF class to create custom header and footer
class MYPDF extends TCPDF{
 // header
 public function Header(){
  $company=api_company(1);  // <<<----- eventualmente per il multi-società sostituire con società emittente
  $name=stripslashes($company->name);
  //
  //$name=stripslashes($company->fiscal_name);
  $address=stripslashes($company->address_address)." - ".stripslashes($company->address_zip)." ".stripslashes($company->address_city)." (".stripslashes($company->address_district).") ".stripslashes($company->address_country);
  if($company->phone_office){$contacts="Tel: ".stripslashes($company->phone_office);}
  if($company->phone_fax){$contacts.=" - Fax: ".stripslashes($company->phone_fax);}
  //$fiscalData="P.IVA: ".stripslashes($company->fiscal_vat);
  if($company->fiscal_code){$fiscalData.=" - C.F: ".stripslashes($company->fiscal_code);}
  if($company->fiscal_rea){$fiscalData.=" - R.E.A: ".stripslashes($company->fiscal_rea);}
  // logo
  if(file_exists("../uploads/uploads/core/logo.png")){
   $logo_size=getimagesize("../uploads/uploads/core/logo.png");
   $logo_x=$logo_size[0];
   $logo_y=$logo_size[1];
   $x_padding=round($logo_x*12/$logo_y)+13;
   $this->Image("../uploads/uploads/core/logo.png",10,10,'',12,'PNG',api_getOption('owner_url'),'T',false,300,'',false,false,0,false,false,false);
  }else{
   $x_padding=10;
  }
  // header style
  $this->SetTextColor(0);
  $this->SetFillColor(245);
  $fill=FALSE;
  $border='';
  // build header
  $this->SetFont('freesans','B',15);
  $this->MultiCell(160,0,$name,$border,'L',false,0,$x_padding,9);
  $this->MultiCell(75,0,"Coordinator",$border,'R',false,0,200,9);
  $this->Image("../core/images/logos/logo.png",276,10,'',12,'PNG',"http://www.coordinator.it",'T',false,300,'',false,false,0,false,false,false);
  $this->SetFont('freesans','',9);
  $this->MultiCell(160,0,$address." - ".$contacts,$border,'L',false,0,$x_padding,15);
  $this->SetFont('freesans','B',9);
  $this->MultiCell(75,0,api_text("module-title"),$border,'R',false,0,200,15);
  $this->SetFont('freesans','',7);
  $this->MultiCell(160,0,$fiscalData,$border,'L',false,0,$x_padding,19);
  $this->MultiCell(75,0,api_text("module-diary_export-title"),$border,'R',false,0,200,19);
 }
 // footer
 public function Footer(){
  $this->setY(-12);
  $this->SetFont('freesans','',6,'',true);
  $this->Cell(0,3,mb_strtoupper(api_text("module-diary_export-page"),'UTF-8')." ".$this->getAliasNumPage()." ".mb_strtoupper(api_text("module-diary_export-pageOf"),'UTF-8')." ".$this->getAliasNbPages(),0,0,'L',0);
  $this->Cell(0,3,mb_strtoupper(api_text("module-diary_export-footer"),'UTF-8')." ".$training->datetraining,0,0,'R',0);
  //$this->Cell(0,3,mb_strtoupper(api_text("module-diary_export-footer"),'UTF-8')." ".$GLOBALS['module-diary']->number,0,0,'R',0);
 }
}
// create new pdf document
$pdf=new MYPDF('L','mm','A4',true,'UTF-8',false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
//$pdf->SetAuthor(api_account($training->addIdAccount)->name);
$pdf->SetTitle("Dettaglio allenamento svolto");
//$pdf->SetSubject("Training nr. ".$training->number." - ".api_timestampFormat($request->timestamp,api_text("date")));
// header and footer
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);
// set margins
$pdf->SetMargins(10,30,10);
$pdf->SetHeaderMargin(30);
$pdf->SetFooterMargin(10);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE,15);
// set font
$pdf->SetFont('freesans','',12,'',true);
// add a page
$pdf->AddPage();
// page styles
$pdf->SetTextColor(0);
$pdf->SetFillColor(245);
$fill=FALSE;
// border
$border='';
// header
$pdf->SetFont('freesans','',7,'',true);
$pdf->Cell(25,3,mb_strtoupper(api_text("module-diary_export-sport"),'UTF-8'),$border,0,'L',false);
$pdf->Cell(25,3,mb_strtoupper(api_text("module-diary_export-sort"),'UTF-8'),$border,0,'L',false);
$pdf->Cell(35,3,mb_strtoupper(api_text("module-diary_export-time"),'UTF-8'),$border,0,'L',false);
$pdf->Cell(25,3,mb_strtoupper(api_text("module-diary_export-distance"),'UTF-8'),$border,0,'L',false);
$pdf->Cell(130,3,mb_strtoupper(api_text("module-diary_export-description"),'UTF-8'),$border,0,'L',false);
$pdf->Cell(0,3,mb_strtoupper(api_text("module-diary_export-datetraining"),'UTF-8'),$border,0,'L',false);
$pdf->Ln();
$pdf->SetFont('freesans','B',10,'',true);
$pdf->Cell(25,5,$training->sportText,$border,0,'L',$fill,'',1);
$pdf->Cell(25,5,$training->sortText,$border,0,'L',$fill,'',1);
$pdf->Cell(35,5,$training->time,$border,0,'L',$fill,'',1);
$pdf->Cell(25,5,$training->distance,$border,0,'L',$fill,'',1);
$pdf->Cell(130,5,$training->description,$border,0,'L',$fill,'',1);
$pdf->Cell(0,5,$training->datetraining,$border,0,'L',$fill,'',1);
$pdf->Ln(10);

  // reset style
  $pdf->SetFont('freesans','',9,'',true);
  $pdf->Cell(5,5,$td_feasible,$border,0,'C',$fill,'',1);
  // set style
  $pdf->SetFont('freesans',$style,9,'',true);
  $pdf->Cell(20,5,$card->card,$border,0,'L',$fill,'',1);
  // reset style
  $pdf->SetFont('freesans','',9,'',true);
  $pdf->Cell(0,5,$card->note,$border,0,'L',$fill,'',1);
  $pdf->Ln();
 

$pdf->Ln(6);
// page styles
$pdf->SetTextColor(0);
$pdf->SetFillColor(245);
$fill=FALSE;

// close and output pdf document
if(strlen($file_path)>0){
 $pdf->Output($file_path,'F');
}else{
 $pdf->Output("module-diary_training_".$training->datetraining.".pdf",'I');
}
?>