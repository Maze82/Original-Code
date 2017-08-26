<?php session_start(); ?>
<?php
include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/mpdf/mpdf.php");
//include("$LIB_DIR/mpdf/pdfcrowd.php");
//include("$LIB_DIR/html2fpdf.php");     

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

    $id = '6';
    $query = "SELECT * FROM auction WHERE id=$id";
    $db->query($query);
    $rec = $db->fetch_assoc();
    $donor_name = $rec['donor_name'];
    $contact_person = $rec['contact_person'];
    $signature = $rec['signature'];
    $email = $rec['email'];
    $twitter = $rec['twitter'];
    $web_address = $rec['web_address'];
    $address = $rec['address'];
    $city = $rec['city'];
    $state = $rec['state'];
    $zip = $rec['zip'];
    $telephone = $rec['telephone'];
    $fax = $rec['fax'];
    $item = $rec['item'];
    $value = $rec['value'];
    $donation_description = $rec['donation_description'];
    $size = $rec['size'];
    $winery = $rec['winery'];
    $winemaker = $rec['winemaker'];
    $bottoles_count = $rec['bottoles_count'];
    $varietal = $rec['varietal'];
    $vintage = $rec['vintage'];
    $appelation = $rec['appelation']; 
    $retail_value = $rec['retail_value']; 
    $description = $rec['description']; 
    $important = $rec['important']; 
    $festival_auction = $rec['festival_auction']; 
    $inserted_date = $rec['inserted_date']; 
    
    $EVENTS_LIST = '';
    $date = date("m/d/y");
    $dirName12  = 'pdf1';
    $filename = 'test1.html';
    $handle = fopen($filename, "rb");
    while (!feof($handle)) {  
      $contents .= fread($handle, 8192); 
    }
    $contents = str_replace("__donor_name__", "$donor_name", $contents);
    $contents = str_replace("__contact_person__", "$contact_person", $contents);
    $contents = str_replace("__signature__", "$signature", $contents);
    $contents = str_replace("__email__", "$email", $contents);
    $contents = str_replace("__twitter__", "$twitter", $contents);
    $contents = str_replace("__web_address__", "$web_address", $contents);
    $contents = str_replace("__address__", "$address", $contents);
    $contents = str_replace("__city__", "$city", $contents);
    $contents = str_replace("__state__", "$state", $contents);
    $contents = str_replace("__zip__", "$zip", $contents);
    $contents = str_replace("__telephone__", "$telephone", $contents);
    $contents = str_replace("__fax__", "$fax", $contents);
    $contents = str_replace("__item__", "$item", $contents);
    $contents = str_replace("__value__", "$value", $contents);
    $contents = str_replace("__donation_description__", "$donation_description", $contents);
    $contents = str_replace("__size__", "$size", $contents);
    $contents = str_replace("__winery__", "$winery", $contents);
    $contents = str_replace("__winemaker__", "$winemaker", $contents);
    $contents = str_replace("__bottoles_count__", "$bottoles_count", $contents);
    $contents = str_replace("__varietal__", "$varietal", $contents);
    $contents = str_replace("__vintage__", "$vintage", $contents);
    $contents = str_replace("__appelation__", "$appelation", $contents);
    $contents = str_replace("__retail_value__", "$retail_value", $contents);
    $contents = str_replace("__description__", "$description", $contents);
    $contents = str_replace("__important__", "$important", $contents);
    $contents = str_replace("__festival_auction__", "$festival_auction", $contents);
    $contents = str_replace("__inserted_date__", "$inserted_date", $contents);
    
   if(file_exists ($dirName12))
		  @chmod($dirName12, 0777);
		else
		  @mkdir($dirName12, 0777);
		  
     $newFile   =$dirName12."/cal_pdf_".mktime().".html";
		
     $newHandle = fopen($newFile, 'w');
     
	
    
    if (fwrite($newHandle, $contents) === FALSE) {
      $PROMPT =  "Cannot write to file ($filename)";
      $PROMPT_CLASS="error";
    }  
    fclose($handle);        
    $html_file  = "pdf1/cal_pdf_".mktime().".html";
    
    
    
    if(file_exists($html_file)){  
    //$pdf=new HTML2FPDF();
    
    //$pdf->AddPage();
    
    $fp = fopen($html_file,"r");
    $strContent = '';
    while (!feof($fp)) {
      $strContent .= fread($fp, 8192);
    }  
    fclose($fp);
      
      /*$pdf->WriteHTML($strContent,true);
      $pdf->SetFont("Arial","",8);
      $new_file_name =  "pdf1/cal_pdf_".mktime().".pdf";
      $pdf->Output($new_file_name,'F'); */
      
    $new_file_name =  "pdf1/LedLampLocator.com-".date('m-d-Y',mktime()).".pdf";
      //echo $new_file_name; exit;
      $mpdf=new mPDF('en-GB','Letter','','',10,10,8,8,16,13); 

      $mpdf->useOnlyCoreFonts = true;
      
      $mpdf->SetDisplayMode('fullwidth');
      
      $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list                                                           
      
      // LOAD a stylesheet
      $stylesheet = file_get_contents('mpdfstyletables.css');
                      
      $mpdf->useOnlyCoreFonts = true;
     
      //$mpdf->WriteHTML('<page sheet-size="8.5in 11in" />');
      $mpdf->WriteHTML($strContent,0,true,true);
   
      $mpdf->Output($new_file_name,'F');
     
    }
         
       $dir      = "pdf1/";
   //    $file     = $DOCUMENT_ROOT."/pdf/$new_file_name";exit;
       $file     = "$DOCUMENT_ROOT$MAP_VROOT_PATH/pdf/$new_file_name";  
       
       
       //@chmod("$file",777);
      
      //$url="$SITE_URL/download.php?f=$file";
      //header("$url");
      downloadFile($file);  
   
?>