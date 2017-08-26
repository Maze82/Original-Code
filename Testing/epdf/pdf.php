<?php
include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/html2fpdf.php");

    $dirName12  = 'pdf1';
    $filename = 'test1.html';
    $handle = fopen($filename, "rb");
    while (!feof($handle)) {  
      $contents .= fread($handle, 8192); 
    } 
     
    if(file_exists ($dirName12))
		  @chmod($dirName12, 0777);
		else
		  @mkdir($dirName12, 0777);
		  
     $newFile   =$dirName12."/test2.html";
		 //$newfile="http://www.greekpropertyexchange.com/pdf/header.jpg";
     $newHandle = fopen($newFile, 'w');

    $contents = str_replace("__headline__", "$headline", $contents);
		$contents = str_replace("__countview__", "$countview", $contents);
		$contents = str_replace("__refernce__", "$refernce", $contents);
		$contents = str_replace("__typename__", "$typename", $contents);
		$contents = str_replace("__currency__", "$currency", $contents);
		$contents = str_replace("__price__", "$price", $contents);
		$contents = str_replace("__size__", "$size", $contents);
		$contents = str_replace("__unit__", "$unit", $contents);
		$contents = str_replace("__town__", "$town", $contents);
		$contents = str_replace("__muncipality__", "$muncipality", $contents);
    $contents = str_replace("__prefacture__", "$prefacture", $contents);
    $contents = str_replace("__region__", "$region", $contents);
    $contents = str_replace("__bedroom__", "$bedroom", $contents);
    $contents = str_replace("__bathroom__", "$bathroom", $contents);
    $contents = str_replace("__otheroption__", "$otheroption", $contents);
    $contents = str_replace("__description__", "$description", $contents);
    $contents = str_replace("__fname__", "$fname", $contents);
    $contents = str_replace("__lname__", "$lname", $contents);
    $contents = str_replace("__name__", "$name", $contents);
    $contents = str_replace("__a_image__", "$a_image", $contents);
    $contents = str_replace("__l_image__", "$l_image", $contents);
    $contents = str_replace("__agency__", "$agency", $contents);
    $contents = str_replace("__phone__", "$phone", $contents);
    $contents = str_replace("__fax__", "$fax", $contents);
    $contents = str_replace("__email__", "$email", $contents);
    $contents = str_replace("__website__", "$website", $contents);
    $contents = str_replace("__residenc_fet__", "$residenc_fet", $contents);
    $contents = str_replace("__residenc_fet1__", "$residenc_fet1", $contents);
    $contents = str_replace("__garage_fet__", "$garage_fet", $contents);
    $contents = str_replace("__threeimagename__", "$threeimagename", $contents);
    $contents = str_replace("__view__", "$view", $contents);
    //$contents = str_replace("__waterfront__", "$waterfront", $contents);  
    //$contents = "hi";
   if (fwrite($newHandle, $contents) === FALSE) {
      $PROMPT =  "Cannot write to file ($filename)";
      $PROMPT_CLASS="error";
    }  
    fclose($handle);
     

    $html_file  = "pdf1/test2.html";
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
      $pdf->Output("pdf1/test2.pdf",'F');
     
    }
    
       $dir      = "pdf1/";
       $file     = $DOCUMENT_ROOT."/pdf1/test2.pdf";
       //@chmod("$file",777);
       
       //@header("location:pdf/property_pdf.pdf");
       echo"<Script language=javascript>
            <!--
             window.location='pdf1/test2.pdf';
             //-->
            </Script>
             ";
           

?>