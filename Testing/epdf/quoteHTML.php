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
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());
 $db3=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db3->open() or die($db3->error());

//error_reporting(E_ALL);
//ini_set('display_errors','On');
 
$ordernumber =$_REQUEST['id'];


  
 
				
  $query ="select * from orders where id=$ordernumber";
   
   $db->query($query);
   $number_rows= $db->num_rows(); 
   $rec=$db->fetch_assoc(); 
   $display_count = $number_rows;
    if($number_rows>0)
    {
      $orderdate1=$rec['ordered_date'];
      $orderdate=date('d/m/Y h:i:s a',$orderdate1);
      
      $date_filled=$rec['date_filled'];
      $ward_id=$rec['ward_id'];
      $name=$rec['ordering_person'];
	  if($name!='')
	  {
	  $name=$rec['ordering_person'];
	  }
	  else
	  {
	  $name=" ______________________________";
	  }
      $email=$rec['email'];
	   if($email!='')
	  {
	 $email=$rec['email'];
	  }
	  else
	  {
	  $email=" ______________________________";
	  }
      $total_weight=$rec['total_weight'];
	  $filled_weight=$rec['filled_weight'];
      $item_ordered=$rec['item_ordered'];
	  if($rec['delivery_date']!='' && $rec['delivery_date']!=0 )
	  {
	  $delivery_date1=$rec['delivery_date'];
	  }
	  else
	  {
	   $delivery_date1="";
	  }
	  $comments      = stripslashes($rec['comments']);
	  if($comments!='')
	  {
	  $comments      = stripslashes($rec['comments']);
	  }
	  else
	  {
	   $comments='______________________________';
	  }
	  if($delivery_date1!='')
	  {
	  $delivery_date=date('d/m/Y',$delivery_date1);
	  }
	  else
	  {
	  $delivery_date='______________';
	  }
      $status=$rec['status'];
    
    
    }
  
    
  $sql=mysql_query("SELECT ward_name,ward_category FROM wards WHERE id=$ward_id");
	 $row2 = mysql_fetch_array($sql);
	 
     
    $ward_title = $row2['ward_name'];
	$ward_category = $row2['ward_category'];
	
	$result_category = mysql_query("select template,email from ward_category where id='$ward_category'");
	 if( mysql_num_rows($result_category)>0)
	 {
   
    $row3 = mysql_fetch_array($result_category);
    
     $customer_id     = $row3['id'];
     $template        = $row3['template'];
   	 $to_email  =	$row3['email'];	
	 					
	if($template=='BLL')
	{
	 $customer_name="BlueLine Laundry Inc";
	//$logo_img="<img src=\"$SITE_URL/images/blueline.jpg\" width=\"550\" height=\"120\" alt=\"blueline logo\" />"; 
	 $filename = 'quoteHTML.html';
	}
	else
	{
	 $customer_name="Laundry King Tas";
	//$logo_img="<img src=\"$SITE_URL/images/LKT_logo.jpg\"  alt=\"LKT logo\" />";
	 $filename = 'quoteHTML.html';
	}
	}
	else
	{
	 $customer_name="Laundry King Tas";
	//$logo_img="<img src=\"$SITE_URL/images/LKT_logo.jpg\"  alt=\"LKT logo\" />";
	 $filename = 'quoteHTML.html';
	}
	
	
    $result_products = mysql_query("select * from order_products where order_id in('$ordernumber')");
      // $db->query($result_products);
	  
	 if( mysql_num_rows($result_products)>0){
    $ProdList =""; 
    while($row1 = mysql_fetch_array($result_products))
    { 
     $pid                   = $row1['product_id'];
     $product_title         = ucfirst($row1['product_name']);
     $product_limit         = $row1['product_quantity'];
     $dispatched_quantity   = $row1['dispatched_quantity'];
                  
        $ProdList.="<tr>
        	<td>$product_title</td>
        	<td>$product_limit</td>
        	<td>$dispatched_quantity</td>
        </tr>";          
      }
	 }  
     $ProdList.=""; 
  
  
    $date = date("m/d/y");
    $dirName12  = 'pdf3';
   // $filename = 'quotePDF1.html';
    $handle = fopen($filename, "rb");
    while (!feof($handle)) {  
      $contents .= fread($handle, 8192); 
    }
	 $contents = str_replace("__filled_weight__", "$filled_weight", "$contents");	
	 $contents = str_replace("__delivery_date__", "$delivery_date", "$contents");			 
	 $contents = str_replace("__customer_name__", "$customer_name", "$contents");
	 $contents = str_replace("__ward_title__", "$ward_title", "$contents");
	 $contents = str_replace("__SITE_URL__", "$SITE_URL", "$contents");
     $contents = str_replace("__ordernumber__", "$ordernumber", "$contents");
	 $contents = str_replace("__orderdate__", "$orderdate", "$contents");
	 $contents = str_replace("__delivery_date__", "$delivery_date", "$contents");
	 $contents = str_replace("__name__", "$name", "$contents");
	 $contents = str_replace("__email__", "$email", "$contents");
	 $contents = str_replace("__ProdList__", "$ProdList", $contents);
     $contents = str_replace("__comments__", "$comments", $contents);						
	 $contents = str_replace("__DOCUMENT_ROOT__", "$DOCUMENT_ROOT", $contents);
 
    
   
   if(file_exists ($dirName12))
		  @chmod($dirName12, 0777);
		else
		  @mkdir($dirName12, 0777);
		  
    // $newFile   =$dirName12."/event_pdf_".mktime().".html";
	  $newFile   =$dirName12."/REPORT1_".mktime().".html";
		
     $newHandle = fopen($newFile, 'w');
     
	
    
    if (fwrite($newHandle, $contents) === FALSE) {
      $PROMPT =  "Cannot write to file ($filename)";
      $PROMPT_CLASS="error";
    }  
    fclose($handle);        
   // $html_file  = "pdf3/event_pdf_".mktime().".html";
	   $html_file  = "pdf3/REPORT1_".mktime().".html";
    
  
    
    if(file_exists($html_file)){  
    //$pdf=new HTML2FPDF();
    
    //$pdf->AddPage();
    
    $fp = fopen($html_file,"r");
    $strContent = '';
    while (!feof($fp)) {
      $strContent .= fread($fp, 8192);
    }  
    fclose($fp);
	  $business_name = str_replace(array('/',',','.','$',' '),"",$business_name);
      $project_title = str_replace(array('/',',','.','$',' '),"_",$project_title);
      $new_file_name = "pdf3/REPORT1_".mktime().".html";
	
     
    }
         
       $dir      = "pdf3/";
  
       $file     = "$DOCUMENT_ROOT$MAP_VROOT_PATH/epdf/$new_file_name";
       
       echo $contents;
      //downloadFile($file); 
   
?>