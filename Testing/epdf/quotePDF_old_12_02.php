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
$MEMBER_ID   = $_SESSION["SESS_MEMBER_ID"]; 
$id =$_REQUEST['id'];


	
  


if($_REQUEST['order_date1']!='')
  {

 $orderdate1=trim($_REQUEST['order_date1']);
    $date_parts=explode("/", $orderdate1);
        $day = $date_parts[0];
       $month = $date_parts[1];
       $year = $date_parts[2];
	   
// $orderdate12=mktime(0,0,0,$month,$day,$year);
  $orderdate12          =   date("d.m.Y",$orderdate1);
	   
  }
  else
  {
   $orderdate12          ="";
  }
  
  if(trim($_REQUEST['order_date2'])!='')
   {
   $orderdate2=trim($_REQUEST['order_date2']);
 
	$orderdate22= date("d.m.Y",$orderdate2);
   }
   else
   {
   $orderdate22="";
   }
    if(trim($_REQUEST['filleddate1'])!='')
  {
  $filleddate1=trim($_REQUEST['filleddate1']);
  

  $filleddate12  =   date("d.m.Y",$filleddate1);
  }
  else
  {
   $filleddate12  =  "";
   }
  if(trim($_REQUEST['filleddate2'])!='')
  {
  $filleddate2=trim($_REQUEST['filleddate2']);
  $date_parts3=explode("/", $filleddate2);
   
 $filleddate22 =   date("d.m.Y",$filleddate2);
  }
  else
  {
  $filleddate22 ="";
  }
  if(trim($_REQUEST['status'])!='')
  {
 $status1=trim($_REQUEST['status']);
  }
   if(trim($_REQUEST['product'])!='')
  {
  $products1=trim($_REQUEST['product']);
  $select="select product_name from products where id='$products1'";
  $db->query($select);
  if($db->num_rows())
  {
  		$res=$db->fetch_assoc();
		$product_range=$res['product_name'];
  }

  }
  if(trim($_REQUEST['customer'])!='')
  {
  $customer1=trim($_REQUEST['customer']);
  $select="select category_name from ward_category where id='$customer1'";
  $db->query($select);
  if($db->num_rows())
  {
  		$res=$db->fetch_assoc();
		$customer=$res['category_name'];
  }
  }
  if(trim($_REQUEST['costcentre'])!='')
  {
  $costcentre1=trim($_REQUEST['costcentre']);
  }
   if(trim($_REQUEST['type'])!='')
  {
  $customertype1=trim($_REQUEST['type']);
  }
	   
			
		$query2 = "select O.*,W.ward_name,W.ward_category,WC.type from orders as O left join wards as W on O.ward_id=W.id 
				inner join order_products as P on O.id=P.order_id
				left join ward_category as WC on  W.ward_category=WC.id where 1";
				
		
				
 if($status1!='')
 {
  
	$query2 .= " and  O.status='$status1'";	
 }
 
  	if($costcentre1!='')
  { 
  
    $query2 .=" and  W.ward_name ='$costcentre1'";  
  } 
  
  	if($customer1!='')
  {  
   
    $query2 .=" and  W.ward_category ='$customer1'";  
  } 
  	if($customertype1!='')
  {    
   
	$query2 .=" and  WC.type ='$customertype1'";  
  } 
  	if($products1!='')
  { 
   
    $query2 .=" and  P.product_id = '$products1'";  
  }  
  	
	if($orderdate1!=0 && $orderdate2==0)
  { 
  
  $query2 .= " and  O.ordered_date >= $orderdate1";
    
  }
 if($orderdate1==0 && $orderdate2!=0)
  {
    
    $query2 .= " and  O.ordered_date <= $orderdate2";
    
  }
   if($orderdate1!=0 && $orderdate2!=0)
  {
  
   
    $query2 .= " and  O.ordered_date >= $orderdate1 and O.ordered_date <= $orderdate2";
    
  }
  if($filleddate1!=0 && $filleddate2==0)
  {
   
    $query2 .= " and  O.date_filled  >= $filleddate1";
    
  }
 if($filleddate1==0 && $filleddate2!=0)
  { 
    $query2 .= " and  O.date_filled  <= $filleddate2";
    
  }
   if($filleddate1!=0 && $filleddate2!=0)
  {
   
  $query2 .= " and  O.date_filled >= $filleddate1 and O.date_filled <= $filleddate2";
    
  }

 $query2 .=" group by O.id order by O.ordered_date desc";	
 

	   
         $db->query($query2);	
		if($db->num_rows())
		{	
      $loop=1; 
	  

      while($row=$db->fetch_array())
      {
	
       $id                    =   $row['id'];
      
       $ordered_date          =   date("d.m.Y",$row['ordered_date']);
       $ward_name             =   getWradListName($db1,$row['ward_id']); 
    
	   if($row['date_filled']!=0)
      	 $date_filled           =   date("d.m.Y",$row['date_filled']);
	   else
	   	$date_filled ="-";
        $status                 =   $row['status'];
      
	
 $select="select  sum(product_quantity) as total_items,sum(total_product_weight) as p_weight  from order_products where order_id=$id";
		$db1->query($select); 
		$rec=$db1->fetch_assoc();
	
		
		$item_ordered=$rec['total_items'];
        $total_weight=$rec['p_weight'];
		$total_item=$rec[''];
		

		$select1="select order_id, filled_order,filled_weight  from order_products as P 
				left join orders as O on O.id=P.order_id where P.order_id=$id and P.product_quantity>0";				
					
		$db1->query($select1); 
		$rec1=$db1->fetch_assoc();
		$item_filled=$rec1['filled_order'];
		$order_id1  =   $rec1['order_id']; 
	    $array_orderId[]=$order_id1; 	
		//echo $total=count($rec1['filled_order']);
		if($item_filled=='')
		{
		$item_filled=0;
		}
		else
		{$item_filled=$rec1['filled_order'];
		}
        $total_filled_weight=$rec1['filled_weight'];
		if($total_filled_weight=='')
		{
		$total_filled_weight=0;
		}
		else
		{$total_filled_weight=$rec1['filled_weight'];
		}
			$filled_per=($item_filled/$item_ordered)*100;
			$sum_items+=$item_ordered;
			$sum_ward_filled+=$item_filled;
			$sum_filled_weight += $total_filled_weight;
			$sum_total_weight+=$total_weight;
			
			
			$ward_report .="<tr>
							
							<td width=\"15%\" style=\"font-size:14px;text-align:left\">$ward_name</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15%\" style=\"font-size:14px;text-align:center\">$item_ordered</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15\" style=\"font-size:14px;text-align:center\">$item_filled</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15\" style=\"font-size:14px;text-align:center\">".number_format($filled_per,2)." %</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15%\" style=\"font-size:14px;text-align:center\">$total_filled_weight kg</td>
						</tr>";
      
	           $loop++;
			
        }
	    $display_count=$loop-1;
        $sum_ward_filled_percent = number_format((($sum_ward_filled/$sum_items)*100),2);

		foreach ($array_orderId as $valorderId) 
		{
			$select1="select OP.*,O.item_ordered from order_products as OP left join orders as O on O.id=OP.order_id where OP.order_id=$valorderId"; 
			$total_weight_val=0;
			$db->query($select1);
			if($db->num_rows())
			{
	  				
				$i=1;			
			    while($row=$db->fetch_assoc()){
			
				$product_name  = $row['product_name'];
				$product_id    = $row['product_id'];
				$order_id      = $row['order_id'];
		
				$product_quantity      = $row['product_quantity'];
				$dispatched_quantity   = $row['dispatched_quantity'];
				$item_order            = $row['item_ordered'];
				$product_weight        = $row['product_weight'];
			
				$filled_weight   = $dispatched_quantity * $product_weight;
				$unfilled_item   = $product_quantity-$dispatched_quantity;
				$unfilled_weight = $product_weight * $unfilled_item;
	
				$total_filled_item+= $total_filled_item + $dispatched_quantity;
				$sum_weight += $product_weight;
				
				$sum_product_items +=$product_quantity;
				$sum_product_filled +=$dispatched_quantity;
				
	        	$filled_pro_per1      = ($dispatched_quantity/$product_quantity)*100;
				$unfilled_pro_per1    = ($unfilled_item/$product_quantity)*100;
				$sum_product_filled_weight+=$filled_weight;
				$sum_product_unfilled+=$unfilled_weight;
				$sum_product_unfilled_percent = number_format(($sum_product_unfilled/$sum_product_items)*100,2);
				
				$sum_product_filled_percent  = number_format(($sum_product_filled/$sum_product_items)*100,2);
				
				$product_report .="<tr>
				            <td width=\"10%\" style=\"font-size:14px;text-align:left\">$product_name</td>
							<td width=\"3%\">&nbsp;</td>
							<td width=\"10%\" style=\"font-size:14px;text-align:center\">$product_weight</td>
							<td width=\"3%\">&nbsp;</td>
							<td width=\"10%\" style=\"font-size:14px;text-align:center\">$product_quantity</td>
							<td width=\"3%\">&nbsp;</td>
							<td width=\"10\" style=\"font-size:14px;text-align:center\">$dispatched_quantity</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"10\" style=\"font-size:14px;text-align:center\">".number_format($filled_pro_per1,2)." %</td>
							<td width=\"3%\">&nbsp;</td>
							<td width=\"10%\" style=\"font-size:14px;text-align:center\">$filled_weight kg</td>
							<td width=\"3%\">&nbsp;</td>
							<td width=\"10\" style=\"font-size:14px;text-align:center\">$unfilled_item</td>
							<td width=\"3%\">&nbsp;</td>
							<td width=\"10\" style=\"font-size:14px;text-align:center\">".number_format($unfilled_pro_per1,2)." %</td>
							<td width=\"3%\">&nbsp;</td>
							<td width=\"10%\" style=\"font-size:14px;text-align:center\">$unfilled_weight kg</td>
						</tr>";
			
			$i++;
			}
			
	   }
		
		}
		
      }
  


  
    $date = date("m/d/y");
    $dirName12  = 'pdf1';
    $filename = 'qoute_old.html';
    $handle = fopen($filename, "rb");
    while (!feof($handle)) {  
      $contents .= fread($handle, 8192); 
    }
					 
	
	 $contents = str_replace("__EVENTS__", "$EVENTS", "$contents");
	 $contents = str_replace("__SITE_URL__", "$SITE_URL", "$contents");
   	 $contents = str_replace("__order_date_range1__", "$orderdate12", "$contents");
	 $contents = str_replace("__order_date_range2__", "$orderdate22", "$contents");
	 $contents = str_replace("__filled_date_range1__", "$filleddate12", "$contents");
	 $contents = str_replace("__filled_date_range2__", "$filleddate22", "$contents");
     $contents = str_replace("__product_range__", "$product_range", "$contents");
	 $contents = str_replace("__status__", "$status1", "$contents");
	 $contents = str_replace("__customer__", "$customer", "$contents");
	 $contents = str_replace("__SITE_URL__", "$SITE_URL", "$contents");		
	 $contents = str_replace("__total_orders__", "$display_count", "$contents");
	 $contents = str_replace("__total_items__", "$total_items", "$contents");	
	 $contents = str_replace("__total_weight__", "$total_weight", "$contents");
	 $contents = str_replace("__total_filled_items__", "$total_filled_items", "$contents");
	 $contents = str_replace("__total_filled_weight__", "$total_filled_weight", "$contents");
	 $contents = str_replace("__ward_report__", "$ward_report", "$contents");	
	 $contents = str_replace("__product_report__", "$product_report", "$contents");	
	 $contents = str_replace("__sum_items__", "$sum_items", "$contents");
	 $contents = str_replace("__sum_ward_filled__", "$sum_ward_filled", "$contents");
	 $contents = str_replace("__sum_filled_weight__", "$sum_filled_weight", "$contents");
	 $contents = str_replace("__sum_ward_filled_percent__", "$sum_ward_filled_percent", "$contents");
	 $contents = str_replace("__sum_product_items__", "$sum_product_items", "$contents");
	 $contents = str_replace("__sum_product_filled__", "$sum_product_filled", "$contents");
	 $contents = str_replace("__sum_product_filled_weight__", "$sum_product_filled_weight", "$contents");
	 $contents = str_replace("__sum_product_filled_percent__", "$sum_product_filled_percent", "$contents");	
	 $contents = str_replace("__sum_product_unfilled__", "$sum_product_unfilled", "$contents");
	 $contents = str_replace("__sum_product_unfilled_weight__", "$sum_product_unfilled_weight", "$contents");
	 $contents = str_replace("__sum_product_unfilled_percent__", "$sum_product_unfilled_percent", "$contents");	
	 $contents = str_replace("__product_weight__", "$product_weight", "$contents");	
	 $contents = str_replace("__sum_weight__", "$sum_weight", "$contents");	
	 $contents = str_replace("__sum_total_weight__", "$sum_total_weight", "$contents");	
	 $contents = str_replace("__customertype1__", "$customertype1", "$contents"); 
	 $contents = str_replace("__costcentre__", "$costcentre1", "$contents");								
	
 
    
   
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
	  $business_name = str_replace(array('/',',','.','$',' '),"",$business_name);
      $project_title = str_replace(array('/',',','.','$',' '),"_",$project_title);
      $new_file_name =  "pdf1/PDF-Report".".pdf";
      //echo $new_file_name; exit;
      $mpdf=new mPDF('utf-8','A4');    

      $mpdf->useOnlyCoreFonts = true;
      
      $mpdf->SetDisplayMode('fullwidth');
      
      $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list                                                           
    
      // LOAD a stylesheet
      $stylesheet = file_get_contents('mpdfstyletables.css');
                      
      $mpdf->useOnlyCoreFonts = true;     
     
      $mpdf->WriteHTML($strContent,0,true,true);
   
      $mpdf->Output($new_file_name,'F');
     
    }
         
       $dir      = "pdf1/";
  
       $file     = "$DOCUMENT_ROOT$MAP_VROOT_PATH/epdf/$new_file_name"; 
     
      downloadFile($file); 
   
?>