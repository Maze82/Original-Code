<?php session_start(); ?>
<?php
include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/mpdf/mpdf.php");
//include("$LIB_DIR/mpdf/pdfcrowd.php");
//include("$LIB_DIR/html2fpdf.php");     
global $SITE_URL,$DOCUMENT_ROOT,$GALLERY_IMG_DIR;
global $product_name,$product_number,$description,$category,$sub_category,$price,$CATEGORY_LIST,$subcat_list,$image,$pdf_file,$uploaded_image,$uploaded_pdf_file,$SITE_URL,$DIR_UPLOAD_PRODUCT_FILE,$DIR_DISPLAY_PRODUCT_FILE,$uploaded_gallery_images,$basic_description;
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
 
 $id =$_REQUEST['id'];
  $query="select * from product where id='$id'"; 
  $db->query($query);	
     if($db->num_rows() > 0)
      {  
        $row =   $db->fetch_array();
        $id  =   $row['id'];
        $product_name = $row['product_name'];
        $product_number=$row['product_number'];
        $description  = $row['description'];
        $basic_description = nl2br($row['basic_description']);
        $category     = $row['category'];
        $sub_category = $row['sub_category'];
        $price        = $row['price'];
        //$pdf_file     = $row['pdf_file'];
        //$CATEGORY_LIST= getCategory($db,$category);
        //$subcat_list  =   getSubCatList($db,$category,$sub_category);
        
        //$uploaded_gallery_images   = galleryImages($db1,$id);
       
        $select1 = "select * from tbl_photogallery where parent_id='$id'"; 
        $db1->query($select1);
        if($db1->num_rows())
        {
         $uploaded_images ="<div style='width:100%; padding-bottom:20px;'><div style='width:47%; float:left;'>";
         $count =1;
         while($row1 = $db1->fetch_array())
         {
           $images     =     $row1['name'];
           if($images!='')
            {  
                   $filenname_path=$GALLERY_IMG_DIR."/".$images;
                   $uploaded_images.= "<img src=\"$SITE_URL/$filenname_path\" style=\"width:200px;height:200px; border:1px solid #000; margin:5px;\"/>";
                   if($count==1)
                    $uploaded_images.= "</div><div style='width:52%; float:right;'><div style='width:45%; padding-left:3%; float:left;'>";
                     if($count==2)
                    $uploaded_images.= "</div><div style='width:50%; float:left;'>";  
                   $count++; 
           }                                                      
           
         }
        $uploaded_images.= "</div></div>";
       }   
     
     }


    
 
			  	 $EVENTS .= "
						<div style='width:50%; float:left;'>
							<img src='$SITE_URL/epdf/logo_pdf.jpg' alt='' />
							<div style='font-size:12px; font-weight:bold; color:#1d1d1b; font-family:Arial; line-height:24px;'>
								<span style='color:#8bbf3a; font-family:Arial;'>address</span> 293 Wellington Street, Launceston TAS 7250<br />
								<span style='color:#8bbf3a; font-family:Arial;'>phone</span> (03) 6334 0108  <span style='color:#8bbf3a;'>fax</span> (03) 6343 5732<br />
								<span style='color:#8bbf3a; font-family:Arial;'>email</span> sales@rawonline.com.au <span style='color:#8bbf3a;'>web</span> www.rawonline.com.au
							</div>
						</div>
						<div style='width:50%; float:right;'>
							<p style='font-family:Arial; color:#8bbf3a; font-size:45px; font-weight:bold; text-transform:uppercase; padding:0; margin:0; text-align:right;'>Product<br />Information</p>
						</div>
						<div style='clear:both; width:100%; padding-bottom:20px;'></div>
						<div style='width:100%; padding-bottom:20px;'>
						$uploaded_images
						<div style='width:100%; clear:both; overflow:hidden; padding-bottom:10px;'>
							<div style='font-size:14px; width:200px; float:left; text-align:left; font-family:Arial; font-weight:bold;'>Product Name:</div>
							<div style='font-size:14px; width:500px; float:left; font-family:Arial; text-align:left;'>$product_name</div>
						</div>
						<div style='width:100%; clear:both; overflow:hidden; padding-bottom:10px;'>
							<div style='font-size:14px; width:200px; float:left; font-family:Arial; text-align:left; font-weight:bold;'>Product Number:</div>
							<div style='font-size:14px; width:500px; float:left; font-family:Arial; text-align:left;'>$product_number</div>
						</div>
						<div style='width:100%; clear:both; overflow:hidden; padding-bottom:10px;'>
							<div style='font-size:14px; width:200px; float:left; font-family:Arial; text-align:left; font-weight:bold;'>Product Category:</div>
							<div style='font-size:14px; width:500px; float:left; font-family:Arial; text-align:left;'>Websites</div>
						</div>						
						<div style='width:100%; clear:both; overflow:hidden; padding-bottom:10px;'>
							<div style='font-size:14px; width:200px; float:left; font-family:Arial; text-align:left; font-weight:bold;'>Product Sub-Category:</div>
							<div style='font-size:14px; width:500px; float:left; font-family:Arial; text-align:left;'>-</div>
						</div>					
						<div style='width:100%; clear:both; overflow:hidden; padding-bottom:30px;'>
							<div style='font-size:14px; width:200px; float:left; font-family:Arial; font-weight:bold; text-align:left;'>Price:</div>
							<div style='font-size:14px; width:500px; float:left; font-family:Arial; text-align:left;'>$".$price."</div>
						</div>
						<div style='width:100%; clear:both; overflow:hidden; padding-bottom:30px;'>
							<p style='font-size:15px; font-family:Arial; color:#000; font-weight:bold; text-align:left;'>Description</p>
							<p style='font-size:14px; font-family:Arial; color:#000; text-align:left;'>$description</p>
						</div>
						<p style='font-size:15px; font-family:Arial; color:#000; font-weight:bold; text-align:left;'>Basic Description</p>
						<p style='font-size:14px; font-family:Arial; color:#000; text-align:left;'>$basic_description</p>
						<div style='background:#76b82a; padding:30px 0; margin:0 -40px;'>
              <p style='font-size:15px; text-align:center; color:#1d1d1b; font-family:Arial;'>This document is to be viewed by its intended recipient only and may contain private information.<br />If you have received this document by error, please return it to its intended recipient or destroy this document.</p>
            </div>";
          
   
  
    $date = date("m/d/y");
    $dirName12  = 'pdf1';
    $filename = 'event.html';
    $handle = fopen($filename, "rb");
    while (!feof($handle)) {  
      $contents .= fread($handle, 8192); 
    }
					 
	
	 $contents = str_replace("__EVENTS__", "$EVENTS", "$contents");
	  $contents = str_replace("__SITE_URL__", "$SITE_URL", "$contents");						
 
    
   
   if(file_exists ($dirName12))
		  @chmod($dirName12, 0777);
		else
		  @mkdir($dirName12, 0777);
		  
     $newFile   =$dirName12."/event_pdf_".mktime().".html";
		
     $newHandle = fopen($newFile, 'w');
     
	
    
    if (fwrite($newHandle, $contents) === FALSE) {
      $PROMPT =  "Cannot write to file ($filename)";
      $PROMPT_CLASS="error";
    }  
    fclose($handle);        
    $html_file  = "pdf1/event_pdf_".mktime().".html";
    
    
    
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
      
    $new_file_name =  "pdf1/Product-".date('m-d-Y',mktime()).".pdf";
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
       $file     = "$DOCUMENT_ROOT$MAP_VROOT_PATH/epdf/$new_file_name";  
       
     
       //@chmod("$file",777);
      
      //$url="$SITE_URL/download.php?f=$file";
      //header("$url");
      downloadFile($file);  
   
?>