<?php
include("../config/data.config.php");
include("../$LIB_DIR/class.database.php");
include("../$LIB_DIR/data.constant.php");
include("../$LIB_DIR/functions.library.php");
//include("html2fpdf1.php"); 
include("../phplib/html2fpdf.php");

    //echo $SITE_URL; exit;
  global $SITE_URL;
  $c_wattage = $_REQUEST['c_wattage'];
  $c_lamplife = $_REQUEST['c_lamplife'];
  $c_laborcost = $_REQUEST['c_laborcost'];
  $c_lampcost = $_REQUEST['c_lampcost'];

  
  $dirName      = 'pdf';
    $filename = 'pdf/property_pdf1.html';
    $handle = fopen($filename, "rb");
    while (!feof($handle)) {
      $contents .= fread($handle, 8192);
    }
    
    if(file_exists ($dirName))
		  @chmod($dirName, 0777);
		else
		  @mkdir($dirName, 0777);
		  
     $newFile   =$dirName."/".$refernce.".html";
		 //$newfile="http://www.greekpropertyexchange.com/pdf/header.jpg";
     $newHandle = fopen($newFile, 'w');

    $contents = str_replace("__c_wattage__", "$c_wattage", $contents);
		$contents = str_replace("__c_lamplife__", "$c_lamplife", $contents);
		$contents = str_replace("__c_laborcost__", "$c_laborcost", $contents);
		$contents = str_replace("__c_lampcost__", "$c_lampcost", $contents);
    echo $contents;exit;
    
   if (fwrite($newHandle, $contents) === FALSE) {
      $PROMPT =  "Cannot write to file ($filename)";
      $PROMPT_CLASS="error";
    }
    fclose($handle);


    $html_file  = "pdf/$refernce.html";
    //$pdf_file   = "pdf/property_pdf.pdf";
   //echo $contents;
   // exit;

    if(file_exists($html_file)){
    $pdf=new HTML2FPDF();
    //$pdf=new PDF();
    $pdf->AddPage();
    //$pdf->Header();
    $fp = fopen($html_file,"r");
    $strContent = '';
    while (!feof($fp)) {
      $strContent .= fread($fp, 8192);
    }
    fclose($fp);
    
      $pdf->WriteHTML($strContent,true);
      $pdf->Output("pdf/$refernce.pdf",'F');
     
    }
    
       $dir      = "pdf/";
       $file     = $DOCUMENT_ROOT."/pdf/$refernce.pdf";
       //@chmod("$file",777);
       
       //@header("location:pdf/property_pdf.pdf");
       echo"<Script language=javascript>
            <!--
             window.location='pdf/$refernce.pdf';
             //-->
            </Script>
             ";
?>
