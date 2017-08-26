<?php session_start(); 
include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/mpdf/mpdf.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
 
 //error_reporting(E_ALL);
//ini_set('display_errors','On');
 
$id =$_REQUEST['id'];


    
    $select="SELECT I.createtime,I.job_number,I.project_title,E.first_name,E.last_name FROM employee As E
               LEFT JOIN invoice As I ON E.emp_login_id=I.sales_rep 
               WHERE I.id='$id'";  
    
     $db->query($select);
    if($db->num_rows())
    {
        $row =$db->fetch_assoc();
        
         $quote_date = date('F d, Y',$row['createtime']);
        $quote_no = sprintf("%06d",$row['job_number']);
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $project_title = $row['project_title'];
         
    }
    
    $select1="SELECT C.business_name,C.contact_person,C.billing_street,C.billing_state,C.billing_code,C.billing_city FROM customer as C
               LEFT JOIN invoice As I ON C.id=I.cust_id
               WHERE I.id='$id'";
    
     $db->query($select1);
    if($db->num_rows())
    {
        $row1 =$db->fetch_assoc();
        
        $business_name = $row1['business_name'];
        $contact_person = $row1['contact_person'];
        $street_address = $row1['billing_street'];
        $state = $row1['billing_state'];
        $postcode = $row1['billing_code'];
        $city= $row1['billing_city'];
        if($city!='')
        $ADDr = $city;
        if($state!='')
        $ADDr .=", $state";
        if($postcode!='')
        $ADDr .=", $postcode";
        

         
    }
    
       $select2="SELECT I.price as total_gst,I.gst,ID.quantity,ID.product_name,ID.description,ID.price,ID.discount,ID.total,ID.variation FROM invoice as I
               LEFT JOIN invoice_detail As ID ON I.id=ID.invoice_id                
               WHERE I.id='$id'"; 
    
     $db->query($select2);
    if($db->num_rows())
    {
        while($row1 =$db->fetch_assoc())
        {
        $product_name = $row1['product_name'];
        $quantity = $row1['quantity'];  
        $total_gst = $row1['total_gst'];
          if($row1['gst']!='' || $row1['gst']!=0)
             $gst = $row1['gst'];
          else
             $gst = '0';
        $basic_description = nl2br($row1['description']);
        if(trim($row1['discount'])!='' || $row1['discount']>0)
        {
            $price = "<span style=\"text-decoration:line-through;\">$ $row1[price]</span>";
            $discounted_price = "<span style=\"color:red;\">$ $row1[total]</span>";
         }   
        else
          {
             $price = $row1['price'];
             $discounted_price='';
          }      
        $state = $row1['state'];
           
        //finding variation values start
      	$variation_value=Get_variation_id($db1,$row1['variation']);    
        //finding variation values end  */
        
        $total_price += $row1['total'];
        $product_desc .= "<tr>
            	<td style=\"background:#f7f8f8; width:500px; padding:8px 14px 6px; border-right:1px solid #000; border-left:1px solid #000;font-family:Arial; font-size:12px; color:#000; \" align=\"left\">
                	<div style=\"font-weight:bold; color:#000; font-family:Arial; text-decoration:underline;\">$product_name</div>
                   <p style=\"font-size:8px;color:#666666\">$variation_value</p>
                   $basic_description
                </td>
                <td style=\"background:#f7f8f8;padding:8px 14px 6px; vertical-align:top; text-align:center; font-family:Arial; font-size:12px; color:#000; text-transform:uppercase; border-right:1px solid #000;\">$quantity</td>
                <td style=\"background:#f7f8f8; padding:8px 14px 6px; vertical-align:top; text-align:center; font-family:Arial; font-size:12px; color:#000; text-transform:uppercase;  border-right:1px solid #000;\">$price <br />$discounted_price</td>
            </tr>";
        }

        $gst_amt = $total_price*$gst/100;
    } 
     
 
			  	 $EVENTS .= "
						<div style=\"width:1000px; margin:0 auto; padding:20px;\">
	<div style=\"width:960px; margin:0 auto; clear:both;\">
        <div style=\"width:400px; float:left;\">
			<div style=\"float:left; margin-top:14px; width:165px;\"> 
        		<img src=\"$SITE_URL/epdf/logo.jpg\" alt=\"\" />
			</div>
			<p style='float:left; padding:0 10px 0 0; line-height:16.5px; width:50px; font-family:arial; margin:0; font-size:9px; color:#609A12; text-align:right;'>
				address<br /><br />
				mobile<br />
				phone<br />
				fax<br />
				email<br />
				web<br />
			</p>
			<p style='float:left; padding:0; line-height:16.5px; font-family:arial; margin:0; font-size:9px;'>
			293 Wellington Street<br />
			Launceston TAS 7250<br />
			0439 428 466<br />
			(03) 6334 0108<br />
			(03) 6343 5732<br />
			accounts@rawmarketing.com.au<br />
			www.rawmarketing.com.au
			</p>
        </div>
        <div style=\"width:200px; float:right; font-family:Arial; font-size:10px;\">
        	<p style=\"font-family:Arial; font-size:24px; color:#a6a6a6; text-align:center; font-weight:bold; margin:0 0 10px 50px;\">TAX<br />INVOICE</p>
            <table cellpadding=\"0\" cellspacing=\"0\" width='100%' style=' line-height:17px;'>
				<tr>
					<td align='right' style=\"font-family:Arial; font-size:10px; font-weight:bold; color:#000;\">DATE:</td>
					<td align='right' style=\"font-family:Arial; font-size:10px; color:#190019;\">$quote_date</td>
				</tr>
				<tr>
					<td align='right' style=\"font-family:Arial; font-size:10px; font-weight:bold; color:#000;\">INVOICE #</td>
					<td align='right' style=\"font-family:Arial; font-size:10px; color:#190019;\">$quote_no</td>
				</tr>
				<tr>
					<td align='right' style=\"font-family:Arial; font-size:10px; font-weight:bold; color:#000;\">FOR:</td>
					<td align='right' width='100' style=\"font-family:Arial; font-size:10px; color:#190019;\">$business_name</td>
				</tr>
			</table>
        </div>
  	</div>
		  <p style=\"font-family:Arial; font-size:10px; clear:both; color:#000; padding:20px 0 20px 150px;\">ABN 32 159 004 529</p>
    <div style=\"width:760px; padding:20px 0 10px; margin:0 auto; clear:both;\">
		 
      <div style='clear:both; overflow:hidden;'>
        <div style='float:left; width:320px;'>
          <p style=\"font-family:Arial; font-size:11px; color:#000; padding-bottom:20px;\"><strong>Bill To:</strong><br />
          $contact_person<br />
          $business_name<br />
          $street_address<br />
          $ADDr.</p>
        </div>
        <div style='float:left; width:320px;'>
          <p style=\"font-family:Arial; font-size:11px; color:#000; padding-bottom:20px;\">
            <strong>Preferred Payment Method</strong><br />
            <strong>NOTE – NEW BANK DETAILS EFFECTIVE 01/01/2015</strong><br />
             <em>Direct Deposit</strong></em><br />
            <strong>ACC #: 1041 2037</strong><br />
            <strong>BSB #: 067 603</strong><br /> 
            <strong>Name:</strong> Raw Marketing Group Pty Ltd<br />
            <em style='font-size:9px'>Please quote invoice number in reference.</em>
          </p>
        </div>
      </div>    	
    </div>
    
    <div style=\"width:700px; clear:both; margin:0 auto;\">
		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">
			<tr>
				<td style=\"width:500px; background:#d2d3d3; padding:8px 14px 6px 12px; font-family:Arial; font-size:12px; color:#323232; text-transform:uppercase; text-align:center; border-top:1px solid #000; border-bottom:1px solid #000; border-left:1px solid #000;\">DESCRIPTION</td>
                <td style=\"width:50px; background:#d2d3d3; padding:8px 14px 6px; font-family:Arial; font-size:12px; color:#323232; text-transform:uppercase; text-align:center; border-top:1px solid #000; border-bottom:1px solid #000; border-left:1px solid #000;\">QTY</td>
        				<td style=\"width:150px; background:#d2d3d3; padding:8px 14px 6px; font-family:Arial; font-size:12px; color:#323232; text-transform:uppercase; text-align:center; border-top:1px solid #000; border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;\">PRICE</td>
			</tr>
        
        
            $product_desc
            <tr>
                <td colspan='2' style=\"padding:8px 14px 6px;font-weight:bold; font-family:Arial; font-size:12px; color:#000; text-align:right; border-top:1px solid #000;\">Sub Total</td>
                <td style=\"background:#f7f8f8; padding:8px 14px 6px; vertical-align:top; text-align:center; font-family:Arial;border-top:1px solid #000; font-size:12px; color:#000; text-transform:uppercase; border-width:1px 1px 1px 1px; border-style:solid; border-color:#000;background-color:#D8E4BC\">$ $total_price</td>
            </tr>
             <tr>
            	   <td colspan='2' style=\"padding:8px 14px 6px;font-weight:bold; font-family:Arial; font-size:12px; color:#000; text-align:right;\">GST($gst%)</td>
                 <td style=\"background:#f7f8f8; padding:8px 14px 6px; vertical-align:top; text-align:center; font-family:Arial; font-size:12px; color:#000; text-transform:uppercase; border-width:0 1px 1px 1px; border-style:solid; border-color:#000;background-color:#D8E4BC;border-top:1px solid #000;4\">$$gst_amt</td>
            </tr>
            <tr>
            	<td colspan='2' style=\"padding:8px 14px 6px;font-weight:bold; font-family:Arial; font-size:12px; color:#000; text-align:right;\">TOTAL (Inc. GST)</td>
                <td style=\"background:#f7f8f8; padding:8px 14px 6px; vertical-align:top; text-align:center; font-family:Arial; font-size:12px; color:#000; text-transform:uppercase; border-width:0 1px 1px 1px; border-style:solid; border-color:#000;background-color:#D8E4BC\">$ $total_gst</td>
            </tr>
            
        </table>
    </div>
    <div style=\"padding:10px 0; overflow:hidden; font-family:Arial; border-bottom:2px solid #000; margin:20px 0;\">
    	<p style=\"font-size:13px; color:#000; text-align:left;\"><strong>Invoices must be paid within 30 days of issue.</strong><br />
			After 30 days a $40 dollar late fee will be charged, or 5% of the total cost, whichever is higher.
All credit card payments incur a 2% payment processing fee.<br /><br />Make all cheques payable to RAW Marketing Group Pty Ltd<br /><br />If you have any questions concerning this invoice<br />Please contact Gavin during business hours, within 7 days of issue.<br /></p><br /><p style=\"font-size:14px; color:#000;text-align:center;\"><strong>Thankyou for Your Business</strong> </p>
    </div>



</div>	";
          
   
  
    $date = date("m/d/y");
    $dirName12  = 'pdf1';
    $filename = 'qoute.html';
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
      
    $new_file_name =  "pdf1/Invoice - ".$quote_no." - ".$business_name." - $project_title".".pdf";
      //echo $new_file_name; exit;
      $mpdf=new mPDF('utf-8','A4'); 

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