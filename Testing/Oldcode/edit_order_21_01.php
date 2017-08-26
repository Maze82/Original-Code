<?php session_start(); ?>
<?php
error_reporting (E_ALL ^ E_NOTICE); 
include("config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/mpdf/mpdf.php");
include("$LIB_DIR/phpmailer/class.phpmailer.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
 
$MEMBER_NAME = $_SESSION["SESS_MEMBER_NAME"]; 
$MEMBER_TYPE = $_SESSION["SESS_MEMBER_TYPE"]; 
$MEMBER_ID   = $_SESSION["SESS_MEMBER_ID"];

checkLogin();

 
if($_POST['save'])
  {
  	update($db,$db1);
  	header ("Location: ./view_orders.php"); 		
  }


 
  getData($db,$db1);
  
 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html");  
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/edit_order.html"); 
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function getData($db,$db1)
{
        global $id,$ordered_date,$date_filled,$ward_id,$ordering_person,$email,$total_weight,$item_ordered,$status_unfill,$status_fill,$products,$status,$WARD_LIST,$current_date,$item_order,$delivery_date,$counter,$total_weight,$filled_ordered,$filled_weight,$comments;
        
        $current_date=date('d/m/Y h:i a'); 
        $id = $_REQUEST['id'];
        $select = "select * from orders where id='$id'";
        $db->query($select);
        
        if($db->num_rows() > 0)
        {   
             $rec = $db->fetch_assoc();
             $id                  =   $rec['id'];
			 
			 $date_order=$rec['ordered_date'];
             $ordered_date        =   date('d/m/Y h:i a',$rec['ordered_date']);
			
			 if($rec['date_filled']!=0){
             $date_filled         =    date('d/m/Y h:i a',$rec['date_filled']);
			 }
			 else
			 {
			 	$date_filled=$current_date;
			 }
			 
			  if($rec['delivery_date']!=0){
             $delivery_date         =    date('d/m/Y',$rec['delivery_date']);
			 }
			 else
			 {
			 	$delivery_date='';
			 }
			 $ward_id=getWradListName($db1,$rec['ward_id']);
             //$WARD_LIST             =   getWardlist($db1,$rec['ward_id']);            
             $ordering_person     =   $rec['ordering_person'];
             $email               =   $rec['email'];
             $total_weight        =   $rec['total_weight'];
             $item_ordered        =   $rec['item_ordered'];
		         $filled_ordered      =   $rec['filled_order'];
			       $filled_weight       =   $rec['filled_weight'];
             $status              =   $rec['status'];
             $comments            = stripslashes($rec['comments']);
                                      
             if($status == 'UNFILLED')
             $status_unfill = 'selected';
             else
             $status_fill ='selected';
        
 $select1="select OP.*,O.item_ordered from order_products as OP left join orders as O on O.id=OP.order_id 
		     where OP.order_id='".$_REQUEST['id']."'"; 
				
				
	 /*echo   $select1="select product_name,product_id,product_quantity,dispatched_quantity from order_products where order_id='".$_REQUEST['id']."'"; exit;*/
	  
	 /* echo   $select1="select product_name,product_id,product_quantity,count(product_quantity)as item orders ,dispatched_quantity from order_products where order_id='".$_REQUEST['id']."'";exit;*/
	 $total_weight_val=0;
	  $db->query($select1);
	  if($db->num_rows())
	  {
	  		$products .="<tr>
                                <th align=\"right\">Product Name</th>
                                <th align=\"right\">Ordered Quantity</th>
								<th align=\"right\">Dispatched Quantity</th>
                            </tr>";
							
				$i=1;			
			while($row=$db->fetch_assoc()){
			
				$product_name=$row['product_name'];
				$product_id = $row['product_id'];
		
				$product_quantity      = $row['product_quantity'];
				$dispatched_quantity   = $row['dispatched_quantity'];
				$item_order             =$row['item_ordered'];
				$product_weight          =$row['product_weight'];
				//$total_weight1        = $product_quantity * $product_weight;
				//$total_weight_val=
				$products .="<tr>
                                <td align=\"right\">$product_name<input type=\"hidden\" name=\"pid$product_id-$i\" value=\"$product_id\" ></td>
                               <td align=\"right\"><input type=\"text\" name=\"p_qty$product_id-$i\" id=\"p_qty_$i\" style=\"width:40px\" class=\"input_txt\" value=\"$product_quantity\" onchange=\"return getquantity($product_id,'p_qty_$i');\" /></td>
								<td align=\"right\"><input type=\"text\" name=\"d_qty$product_id-$i\" id=\"d_qty_$i\" style=\"width:40px\" class=\"input_txt\" value=\"$dispatched_quantity\"  onchange=\"return getdispatchquantity($product_id,'d_qty_$i');\" /></td>
                               <td><input type=\"hidden\" name=\"total_weight_count\" id=\"total_weight_count_$i\" style=\"width:40px\"  value=\"$product_weight\" /></td>
							</tr>";
			$i++;
			}
			$products .="<input type=\"hidden\" name=\"total_prd_count\" id=\"total_prd_count\" style=\"width:40px\"  value=\"$i\" />";	
				
			//$counter=$i-1;
	   }
        
     }
       
	
	    
        
}

function update($db,$db1)
{      
 global  $SITE_URL,$MEMBER_ID;
		    
        $date_ordered            =    strtotime(str_replace('/', '-',$_POST['date_ordered']));
	  /* $date_parts=explode("/", $date_ordered);
       $day = $date_parts[0];
       $month = $date_parts[1];
       $year = $date_parts[2];
       $date_ordered=mktime(0,0,0,$month,$day,$year);*/
	   
	    $date_filled          =   strtotime(str_replace('/', '-',$_POST['date_filled']));
	  /* $date_parts1=explode("/", $date_filled);
       $day = $date_parts1[0];
       $month = $date_parts1[1];
       $year = $date_parts1[2];
       $date_filled=mktime(0,0,0,$month,$day,$year);*/
	   
       $ward_name           =   $_POST['ward_name'];
       $ordering_person     =   $_POST['ordering_person'];   
       $email               =   $_POST['email']; 
	     $total_weight        =   $_POST['total_weight'];
	     $item_ordered        =   $_POST['items_ordered'];
	     $filled_weight       =   $_POST['filled_weight'];
	     $filled_ordered      =   $_POST['filled_ordered'];
	     $status              =   $_POST['status'];
	     $comments            =   addslashes($_POST['comments']);
	     $delivery_date       =   strtotime(str_replace('/', '-',$_POST['delivery_date']));
		   $order_id=$_REQUEST['id'];
		
      
       $query="update orders set               
                ordered_date='$date_ordered',
                date_filled='$date_filled',
                ordering_person='$ordering_person',
                email='$email',
                comments='$comments',
                total_weight='$total_weight',
                item_ordered='$item_ordered',
        				filled_order='$filled_ordered',
        				filled_weight='$filled_weight',
        				delivery_date='$delivery_date',
        				status='$status'
                where id='".$_REQUEST['id']."'";
                //$db->query($query);                        
          if ($db->query($query)) 
          {
         	
			 //$select = "select ward_id from orders where id='".$_REQUEST['id']."'";
        	// $db->query($select);
			 //$row=$db->fetch_assoc();				 
			
		 	
			
				 $select1="select product_id,product_weight,total_product_weight from order_products where order_id='".$_REQUEST['id']."'";
				 $db->query($select1);
				 $i=1;
				 while($row1=$db->fetch_assoc()){
				 $product=$row1['product_id'];
			     $product_weight=$row1['product_weight'];
				  $product_quantity=$_POST['p_qty'.$product.'-'.$i];
				  $dispatched_quantity =$_POST['d_qty'.$product.'-'.$i];
				 $tot_product_weight=$product_quantity * $product_weight;
				 
				 
				
				
				
				
			
			 /*	 $update="update order_products set  product_quantity='".$_POST['p_qty'.$product.'-'.$i]."', dispatched_quantity ='".$_POST['d_qty'.$product.'-'.$i]."'
						 	where product_id='".$_POST['pid'.$product.'-'.$i]."'"; */
							
							
 $update="update order_products set  product_quantity='$product_quantity',dispatched_quantity ='$dispatched_quantity ',total_product_weight='$tot_product_weight' where order_id='".$_REQUEST['id']."' and  product_id='".$_POST['pid'.$product.'-'.$i]."'";
							
							$db1->query($update);
				
				$i++;
			 	 }
				 
				
					
			
			
			
           $slDesc       =   " -Order Detail updated Successfully.";   
           $_SESSION['sess_msg'] = $slDesc;
           $_SESSION['sess_class']='notice'; 
		   
		   if(isset($_POST['tickbox']))
			{
			$ResMess = getPDF($db,$order_id,$email);	
			} 
			else
			{
			}      
           
          }
    
	
	
	
} 
  
function getPDF($db,$id,$email)
{
global $DOCUMENT_ROOT;

$username = "fmrawsoftware";
$password = 'fmrawSoftware%2uID#3';
$hostname = "localhost"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) 
  or die("Unable to connect to MySQL");
  
$selected = mysql_select_db("fmrawsoftware",$dbhandle) 
  or die("Could not select database");
  
 /* $username = "fm";
$password = 'fm';
$hostname = "localhost"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) 
  or die("Unable to connect to MySQL");
  
$selected = mysql_select_db("fm",$dbhandle) 
  or die("Could not select database");*/
 
 
    // $to_email="linenorders@freightmastertas.com.au";
     //$to_email="dheeraj@brainworkindia.net";
      //$to_email="paya.sharma@brainworkindia.net";
	  
    $to_email="laundry@freightmastertas.com.au";

     //$orderdate          = $_POST['orderdate'];
/*	 date_default_timezone_set("Australia/Melbourne"); 
     $orderdate          = date('d/m/Y h:i:s a');
     $customer           = $_POST['customer'];
     $cost_centre        = $_POST['cost_centre'];
	 /*$filled_order       = $_POST['filled_ordered'];
	 $filled_weight      = $_POST['filled_weight'];*/
	/* $ward               = $_POST['ward'];
	 $email2=$email;
     $ward_title           = $_REQUEST['wardname'];
	 $name               = $_POST['name'];
	 $delivery_date      = $_POST['dateto'];*/
	 
	 
	   $orderdate            =    $_POST['date_ordered'];
	   $date_filled          =   $_POST['date_filled'];
	   $delivery_date        =   $_POST['delivery_date'];
	  
	  
    $ward_title          =   $_POST['ward_name'];
    $name                =   $_POST['ordering_person'];   
    $email               =   $_POST['email'];
	  $email2=$email; 
	   $total_weight        =   $_POST['total_weight'];
	   $item_ordered        =   $_POST['items_ordered'];
	   $filled_weight       =   $_POST['filled_weight'];
	   $filled_ordered      =   $_POST['filled_ordered'];
	   $status              =   $_POST['status'];
	   $comments            = $_POST['comments'];
	  // $delivery_date       =   strtotime(str_replace('/', '-',$_POST['delivery_date']));
   
  

	
	$result_totWeight = mysql_query("select filled_order,filled_weight from orders where id='$id'");
	
	if( mysql_num_rows($result_totWeight)>0)
	 {
    
    $row4 = mysql_fetch_array($result_totWeight);
    
  $filled_weight     = $row4['filled_weight'];
	 }
	
	
	
	$result_category = mysql_query("select template,email from ward_category where id='$customer'");
	/*	 if( mysql_num_rows($result_category)>0)
	 {
    
    $row3 = mysql_fetch_array($result_category);
    
     $customer_id     = $row3['id'];
   $template        = $row3['template'];
   	// $to_email        =	$row3['email'];	
	 					
if($template=='BLL')
	{
	$logo_img="<img src=\"http://laundrykingtas.com.au/images/blueline.jpg\" width=\"550\" height=\"120\" alt=\"blueline logo\" />";
	 $filename = 'event_orderfilled3.html';
	}
	else
	{
	$logo_img="<img src=\"http://laundrykingtas.com.au/images/LKT_logo.jpg\"  alt=\"LKT logo\" />";
	 $filename = 'event_orderfilled4.html';
	}
	}
	else
	{
	$logo_img="<img src=\"http://laundrykingtas.com.au/images/LKT_logo.jpg\"  alt=\"LKT logo\" />";
	 $filename = 'event_orderfilled4.html';
	}*/
	 $filename = 'epdf/event_orderfilled4.html';
	
    $result_products = mysql_query("select * from order_products where order_id='$id'");
      // $db->query($result_products);
	
	  
	 if( mysql_num_rows($result_products)>0){

    $productList = "";
    $ProdList =""; 
    while($row1 = mysql_fetch_array($result_products))
    { 
     $pid              = $row1['product_id'];
     $product_title   = ucfirst($row1['product_name']);
     $product_limit   = $row1['product_quantity'];
	 $dispatch_quantity=$row1['dispatched_quantity'];
	 $ordernumber  =  $row1['order_id'];

  			$productList.="<tr>
                   <td width=\"300\" height=\"30\">$product_title</td>
                   <td width=\"40\">:</td>
                   <td>$product_limit</td>
				    <td>$dispatch_quantity</td>
                  </tr>";
                  
        $ProdList.="<tr>
        	<td style=\"padding:3px 5px 3px 10px; border-right:1px solid #000; border-bottom:1px solid #000;\">$product_title</td>
        	<td style=\"border-right:1px solid #000; text-align:center; border-bottom:1px solid #000;\">$product_limit</td>
			<td style=\"border-right:1px solid #000; text-align:center; border-bottom:1px solid #000;\">$dispatch_quantity</td>
        	
        </tr>";          
      }
	  
	 } 
     $productList.=""; 
     $ProdList.=""; 
	

   $dirName12  = 'epdf/pdf2';
   
   // $filename = 'event_orderfilled.html';
    $handle = fopen($filename, "rb");
	
    while (!feof($handle)) {
		
      $contents .= fread($handle, 8192); 
    }
		$contents = str_replace("__orderdate__", "$orderdate", $contents);
		$contents = str_replace("__delivery_date__", "$delivery_date", $contents);
	  $contents = str_replace("__name__", "$name", $contents);
		$contents = str_replace("__total_weight__", " $total_weight", $contents);
		$contents = str_replace("__item_ordered__", "$item_ordered", $contents);
		$contents = str_replace("__filled_weight__", "$filled_weight", $contents);
		$contents = str_replace("__filled_ordered__", "$filled_ordered", $contents);
		$contents = str_replace("__comments__", "$comments", $contents);
		$contents = str_replace("__status__", "$status", $contents);
		$contents = str_replace("__email__", "$email2", $contents);
		$contents = str_replace("__ProdList__", "$ProdList", $contents);
		$contents = str_replace("__ward_title__", "$ward_title", $contents);
		$contents = str_replace("__filled_weight__", "$filled_weight", $contents);
		$contents = str_replace("__DOCUMENT_ROOT__", "$DOCUMENT_ROOT", $contents);
		$contents = str_replace("__ordernumber__", "$ordernumber", $contents);
   

  if(file_exists ($dirName12))
     {     
	 @chmod($dirName12, 0777); 
	 }
	else
    { 
		 
		  @mkdir($dirName12, 0777);
    }
		  
   $newFile   =$dirName12."/event_orderfilled_pdf2_".mktime().".html"; 
		
     $newHandle = fopen($newFile, 'w');
     
	
    
    if (fwrite($newHandle, $contents) === FALSE) {
      $PROMPT =  "Cannot write to file ($filename)";
      $PROMPT_CLASS="error";
    }  
    fclose($handle);        
    $html_file  = "epdf/pdf2/event_orderfilled_pdf2_".mktime().".html";
    
    
    
    if(file_exists($html_file)){  
    //$pdf=new HTML2FPDF();
    
    //$pdf->AddPage();
    
    $fp = fopen($html_file,"r");
    $strContent = '';
    while (!feof($fp)) {
      $strContent .= fread($fp, 8192);
    }  
    fclose($fp);
      
      
    $new_file_name =  "epdf/pdf2/orderfilled_detail1-".date('G-i-s-m-d-Y',mktime()).".pdf";
      //echo $new_file_name; exit;
      $mpdf=new mPDF('en-GB','Letter','','',10,10,8,8,16,13); 

      $mpdf->useOnlyCoreFonts = true;
      
      $mpdf->SetDisplayMode('fullwidth');
      
      $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list                                                           
      
      // LOAD a stylesheet
      $stylesheet = file_get_contents('epdf/mpdfstyletables.css');
                      
      $mpdf->useOnlyCoreFonts = true;
     
      //$mpdf->WriteHTML('<page sheet-size="8.5in 11in" />');
      $mpdf->WriteHTML($strContent,0,true,true);
   
      $mpdf->Output($new_file_name,'F');
     
    }
         
       $dir      = "epdf/pdf2/";
      $file     = "$DOCUMENT_ROOT$MAP_VROOT_PATH/$new_file_name";  
      
   $message="<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>Linen Order</title>
<style>
body{font-family:Arial, Helvetica, sans-serif;}
</style>
</head>
<body marginheight=\"0\" marginwidth=\"0\" bgcolor=\"#efeeee\">
<div style=\"background:#1290a6; height:20px; width:100%;\"></div>
<div style=\"margin-left:30px; margin-top:30px;\">
$logo_img
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"padding-top:20px; font-size:13px;\">
 <tr>
    <td width=\"300\" height=\"30\"><strong>Order Number</strong></td>
    <td width=\"40\">:</td>
    <td>$ordernumber</td>
  </tr>
  <tr>
    <td width=\"300\" height=\"30\"><strong>Order Date</strong></td>
    <td width=\"40\">:</td>
    <td>$orderdate</td>
  </tr>
    <tr>
    <td width=\"300\" height=\"30\"><strong>Order Filled</strong></td>
    <td width=\"40\">:</td>
    <td>$date_filled  </td>
  </tr>
   <tr>
    <td width=\"300\" height=\"30\"><strong>Delivery Date:</strong></td>
    <td width=\"40\">:</td>
    <td>$delivery_date</td>
  </tr>
   <tr>
    <td width=\"300\" height=\"30\"><strong>Cost Centre:</strong></td>
    <td width=\"40\">:</td>
    <td>$ward_title</td>
  </tr>
     <tr>
    <td width=\"300\" height=\"30\"><strong>Ordering Person:</strong></td>
    <td width=\"40\">:</td>
    <td>$name</td>
  </tr>
    <tr>
    <td width=\"300\" height=\"30\"><strong>Email Address:</strong></td>
    <td width=\"40\">:</td>
    <td>$email</td>
  </tr>
    <tr>
    <td width=\"300\" height=\"30\"><strong>Ordered Items:</strong></td>
    <td width=\"40\">:</td>
    <td> $item_ordered </td>
  </tr>
  
     <tr>
    <td width=\"300\" height=\"30\"><strong>Ordered Weight:</strong></td>
    <td width=\"40\">:</td>
    <td>$total_weight</td>
  </tr>
  
     <tr>
    <td width=\"300\" height=\"30\"><strong>Filled Items:</strong></td>
    <td width=\"40\">:</td>
    <td> $filled_ordered </td>
  </tr>
  
   <tr>
    <td width=\"300\" height=\"30\"><strong>Filled Weight:	</strong></td>
    <td width=\"40\">:</td>
    <td>$filled_weight </td>
  </tr>
   <tr>
    <td width=\"300\" height=\"30\"><strong>Status:	</strong></td>
    <td width=\"40\">:</td>
    <td>$status</td>
  </tr>
  <tr>
    <td width=\"300\" height=\"30\"><strong>Products</strong></td>
    <td width=\"40\"></td>
    <td><strong>Daily Order</strong></td><td><strong>Dispatch Quantity</strong></td>
  </tr>
     $productList
    <tr>
    <td width=\"300\" height=\"30\"><strong>Comments</strong></td>
    <td width=\"40\">:</td>
    <td>$comments</td>
  </tr>
</table>
</div>
</body>
</html>";

                
         //  $email2="paya.sharma@brainworkindia.net";    
                   
              $email1 = new PHPMailer();
              $email1->From      = $to_email;
              $email1->FromName  = 'LaundryKing';
              $email1->ContentType = 'text/html';   
              $email1->Subject   = 'Linen Order - '.$ward_title;
              $email1->Body      = $message;
              $email1->AddAddress($email2);
              $file_to_attach1 = "$DOCUMENT_ROOT";
			  //$file_to_attach1 = "$DOCUMENT_ROOT/epdf/";
              $email1->AddAttachment("$file_to_attach1/$new_file_name");
                
             
              
			 
			  
              if($email1->Send())
              {
               
            $ResMess="Your Message sent Successfully";
                 //session_unset();
				 
					
              }
                else
                {
				
                 $ResMess="Your Message not sent Successfully";
				
                }				
						
            
              
              
return $ResMess; 
}    
  
?>