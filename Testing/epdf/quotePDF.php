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
 
$id =$_REQUEST['id'];

  $orderdate1=trim($_REQUEST['order_date1']);
  $order_date_range1=date('d.m.Y',$orderdate1);
  $orderdate2=trim($_REQUEST['order_date2']);
  $order_date_range2=date('d.m.Y',$orderdate2);

  $filleddate1=trim($_REQUEST['filleddate1']);
  $filled_date_range1= date('d.m.Y',$filleddate1);
  $filleddate2=trim($_REQUEST['filleddate2']);
  $filled_date_range2=date('d.m.Y',$filleddate2);
  $status=trim($_REQUEST['status']);
  $products=trim($_REQUEST['product']);
  $customer=trim($_REQUEST['customer']);
  $costcentre=trim($_REQUEST['costcentre']);
  $customertype=trim($_REQUEST['type']);
  $select="select product_name from products where id='$products'";
  $db->query($select);
  if($db->num_rows())
  {
  		$res=$db->fetch_assoc();
		$product_range=$res['product_name'];
  }

  $customer=trim($_REQUEST['customer']);
  
  
    $select="select category_name from ward_category where id='$customer'";
  $db->query($select);
  if($db->num_rows())
  {
  		$res1=$db->fetch_assoc();
    	$customer_name=$res1['category_name'];
  }
    
	
	 /*$query2 = "select O.*,W.ward_name,W.ward_category from orders as O left join wards as W on O.ward_id=W.id 
 				left join order_products as P on O.id=P.order_id  where 1";*/
 
   $query2 = "select O.*,W.ward_name,W.ward_category,WC.type,WC.category_name from orders as O 
     left join wards as W on O.ward_id=W.id 
     left join order_products as P on O.id=P.order_id 
	 left join ward_category as WC on  W.ward_category=WC.id where 1";
	 
				
				
	if($_REQUEST['costcentre']!='')
  {    
    $query2 .=" and  W.ward_name ='$costcentre'";  
  } 
  
  	if($_REQUEST['customer']!='')
  {    
    $query2 .=" and  W.ward_category ='$customer'";  
  } 
  	if($_REQUEST['product']!='')
  {    
    $query2 .=" and  P.product_id = '$products'";  
  }  
  	
	if($_REQUEST['order_date1']!=0 && $_REQUEST['order_date2']==0)
  {
    $query2 .= " and  O.ordered_date > $orderdate1";
    
  }
 if($_REQUEST['order_date1']==0 && $_REQUEST['order_date2']!=0)
  {
    $query2 .= " and  O.ordered_date < $orderdate2";
    
  }
   if($_REQUEST['order_date1']!=0 && $_REQUEST['order_date2']!=0)
  {
    $query2 .= " and  O.ordered_date between $orderdate1 and $orderdate2";
    
  }
  if($_REQUEST['filleddate1']!=0 && $_REQUEST['filleddate2']==0)
  { 
    $query2 .= " and  O.date_filled  > $filleddate1";
    
  }
 if($_REQUEST['filleddate1']==0 && $_REQUEST['filleddate2']!=0)
  {
    $query2 .= " and  O.date_filled  < $filleddate2";
    
  }
   if($_REQUEST['filleddate1']!=0 && $_REQUEST['filleddate2']!=0)
  {
    $query2 .= " and  O.date_filled  between $filleddate1 and $filleddate2";
    
  }
  
   if($customertype1!='')
  {    
   
	$query2 .=" and  WC.type ='$customertype1'";  
  } 
 if($_REQUEST['status']!='')
 {
 	$query2 .= " and  O.status='".$_REQUEST['status']."' ";	
 }
   			
				
  $query2 .=" group by O.id order by O.ordered_date desc";
 // $query2 .=" group by O.id order by WC.category_name asc";
   $db1->query($query2);
   $number_rows= $db1->num_rows();  
   $display_count = $number_rows;
  
      
	if($db1->num_rows())
	{
	  $loop=1;
	  $count=1;  
	
						   
	while($row = $db1->fetch_array())
  	{  
      
       
       $id1               .=   $row['id'].',';      	   
       $ward_id1          .=   $row['ward_id'].','; 
	   $ordered_id         =   $row['id'];
	  $order_date          .=   $row['ordered_date'];
	  $ward_cat             .=  $row['ward_category'];
	  
	  $customer_name1=$row['category_name'];
	 
		/*	$product_report ="
							<td width=\"10%\" style=\"font-size:14px;text-align:left\">$customer_name1</td>
							<td width=\"3%\">&nbsp;</td>"; 
							   
        	  $product_report1.=$product_report;*/
  } 
  
  	$id=substr($id1,0,-1);
	$ward_id=substr($ward_id1,0,-1);
	$ward_cat1=substr($ward_cat,0,-1);	
	 

 
	
	$sql3="select id,ward_name from wards where id in ($ward_id)";
	$db->query($sql3);
	if($db->num_rows())
	{
		while($rec2=$db->fetch_assoc()){
			$ward_name=$rec2[ward_name];
			$wardid=$rec2[id];
			
			//$sql4="select count(id) as tot_items from ward_products where ward_id='$wardid'";
			$sql4="select count(P.id) as tot_items,sum(P.product_weight) as tot_filled_weight from ward_products as P 
  			left join orders as O on O.ward_id=P.ward_id where P.ward_id in($wardid)";

			$db1->query($sql4);
			if($db1->num_rows())
			{
				$rec3=$db1->fetch_assoc();
				$tot_items=$rec3['tot_items'];
				$sum_items += $tot_items;
				
				
			}
			
			
			
		
		}
		
				
		
	  //$sql6="select order_id,product_id,product_name,product_weight from order_products where order_id in ($id)";
	  
	  if($customer=='')
	  {
	  
	  $sql6 = "select O.ordered_date,O.date_filled,W.ward_category,Wc.category_name,Wc.type,W.id,P.order_id,P.product_id,P.product_name,P.product_weight from order_products as P
	 left join orders as O on O.id=P.order_id
	 left join wards as W on O.ward_id=W.id 
	 left join ward_category as Wc on Wc.id=W.ward_category 
	  where order_id in ($id)";
	  
	  
if($_REQUEST['costcentre']!='')
  {    
    $sql6 .=" and  W.ward_name ='$costcentre'";  
  } 	  
	  
if($_REQUEST['order_date1']!=0 && $_REQUEST['order_date2']==0)
  {
    $sql6 .= " and  O.ordered_date > $orderdate1";
    
  }
 if($_REQUEST['order_date1']==0 && $_REQUEST['order_date2']!=0)
  {
    $sql6 .= " and  O.ordered_date < $orderdate2";
    
  }
	  
  if($_REQUEST['order_date1']!=0 && $_REQUEST['order_date2']!=0)
  {
    $sql6 .= " and  O.ordered_date between $orderdate1 and $orderdate2";
    
  }	  
  
   if($_REQUEST['filleddate1']!=0 && $_REQUEST['filleddate2']==0)
  { 
    $sql6 .= " and  O.date_filled  > $filleddate1";
    
  }
 if($_REQUEST['filleddate1']==0 && $_REQUEST['filleddate2']!=0)
  {
    $sql6 .= " and  O.date_filled  < $filleddate2 ";
    
  }
   if($_REQUEST['filleddate1']!=0 && $_REQUEST['filleddate2']!=0)
  {
   $sql6 .= " and  O.date_filled  between $filleddate1 and $filleddate2";
    
  }
  
  
  
  if($_REQUEST['status']!='')
 {

 	$sql6 .= " and  O.status='".$_REQUEST['status']."' ";	
 }
  
  if($customertype=='')
   {
  
 	//$sql6 .= " and  Wc.type='$customertype' ";	
 } else {
 


    $sql6 .= " and  Wc.type='$customertype' ";	
 }

	  
 $sql6 .= " order by Wc.category_name, P.order_id asc"; 
	
	}
	  
	  
	  
	  else
	  {
	 
  $sql6="select O.ordered_date,O.date_filled,W.ward_category,Wc.category_name,P.order_id,P.product_id,P.product_name,P.product_weight from order_products as P
	 left join orders as O on O.id=P.order_id
	 left join wards as W on O.ward_id=W.id 
	 left join ward_category as Wc on Wc.id=W.ward_category 
	  where order_id in ($id)"; 
	  }
			
			$db1->query($sql6);
			if($db1->num_rows())
			{ $loop1=1;
			
			   $match_order_id = '';
			   
			   
			   
			
				while($rec5=$db1->fetch_assoc()){
				//$category_name1.=$rec5['category_name'].",";	
				$category_name1=$rec5['category_name'];				
			    $category_name=$rec5['category_name'];
				$order_date1=   date("d.m.Y",$rec5['ordered_date']);
				$order_id1=   $rec5['order_id'];
				$product_name=  $rec5['product_name'];
				//$filled_item=  $rec5['dispatched_quantity'];
				$product_weight=$rec5['product_weight'];
				//$sum_weight += $product_weight;
				$prid=$rec5['product_id'];
				
				
				
			 if($customer=='')
	         {
	  	
	
				
	    $sql8="select W.id,P.id,P.dispatched_quantity from order_products as P
			   	left join orders as O on O.id=P.order_id
				left join wards as W on O.ward_id=W.id  where order_id='$order_id1' and product_id='$prid' and order_id in ($id) ";
				
			}
			else
			{
			
		$sql8="	select W.id,P.id,P.dispatched_quantity from order_products as P
			   	left join orders as O on O.id=P.order_id
				left join wards as W on O.ward_id=W.id  where order_id='$order_id1' and product_id='$prid' and order_id in ($id) ";
			}
				
				$db2->query($sql8);
				if($db2->num_rows())
				{
					$rec6=$db2->fetch_assoc();
					
					$tot_pro_filled_items1 =$rec6['dispatched_quantity'];
				
				}
				if($tot_pro_filled_items1!='')
				{
				$tot_pro_filled_items =$tot_pro_filled_items1;
				}
				else
				{
				$tot_pro_filled_items =0;
				}
				
				      $category_name3=$category_name1;	
				      $order_id2 = $order_id1;
						
						if(end($match_order_id)!=$order_id1 && $loop1!=1)
						{
						
						    $product_report .= "<tr>
							<td style=\"border-bottom:1px solid #333;padding-top:5px;\"></td>
							<td style=\"border-bottom:1px solid #333;padding-top:5px;\">&nbsp;</td>
							<td style=\"border-bottom:1px solid #333;padding-top:5px;\">&nbsp;</td>
							<td style=\"border-bottom:1px solid #333;padding-top:5px;\">&nbsp;</td>
							<td style=\"border-bottom:1px solid #333;padding-top:5px;\">&nbsp;</td>
							<td style=\"border-bottom:1px solid #333;padding-top:5px;\">&nbsp;</td>
							<td style=\"border-bottom:1px solid #333;padding-top:5px;\">&nbsp;</td>
							<td style=\"border-bottom:1px solid #333;padding-top:5px;\">&nbsp;</td>
							</tr>";				
						}
						else if(end($match_order_id)==$order_id1 && $loop1!=1)
						{
						    $order_id2 = '';
						    $order_date1 = '';
							//$category_name1='';
						}
						
						
						if($loop1==1) {
						
						 $product_report .= "<tr><td colspan='8' width='100%' style='padding:20px 0;'><div style=\"font-size:18px;color:#0099FF\"><strong>CUSTOMER NAME: $category_name1</strong></div></td><tr>";
						
						
							$product_report .= "
							 
								<tr>
								<td width=\"10%\" style=\"font-size:14px;font-weight:bold;text-align:left; border-bottom:1px solid #333;\">Order Date</td>
								<td width=\"3%\" style=\"border-bottom:1px solid #333;\">&nbsp;</td>
								<td width=\"10%\" style=\"font-size:14px;font-weight:bold;text-align:left; border-bottom:1px solid #333;\">Order no</td>
								
								<td width=\"3%\" style=\"border-bottom:1px solid #333;\">&nbsp;</td>
								<td width=\"15%\" style=\"font-size:14px;font-weight:bold;text-align:left; border-bottom:1px solid #333;\">Product Ordered</td>
								<td width=\"8%\" style=\"border-bottom:1px solid #333;\">&nbsp;</td>
							 
								
								<td width=\"10%\" style=\"font-size:14px;font-weight:bold;text-align:center;border-bottom:1px solid #333;\">Qty Filled</td>
								<td width=\"3%\" style=\"border-bottom:1px solid #333;\">&nbsp;</td>
								</tr>";
						}
						
						
						
						
						
						if(end($match_category_name)!=$category_name && $loop1!=1)
						{
						
					     $product_report .= "<tr><td colspan='8' width='100%' style='padding:20px 0;'><div style=\"font-size:18px;color:#0099FF\"><strong>CUSTOMER NAME: $category_name1</strong></div></td><tr>";
						
						
						 $product_report .= "
						 
							 <tr>
							<td width=\"10%\" style=\"font-size:14px;font-weight:bold;text-align:left; border-bottom:1px solid #333;\">Order Date</td>
							<td width=\"3%\" style=\"border-bottom:1px solid #333;\">&nbsp;</td>
							<td width=\"10%\" style=\"font-size:14px;font-weight:bold;text-align:left; border-bottom:1px solid #333;\">Order no</td>
							<td width=\"3%\" style=\"border-bottom:1px solid #333;\">&nbsp;</td>
							<td width=\"15%\" style=\"font-size:14px;font-weight:bold;text-align:left; border-bottom:1px solid #333;\">Product Ordered</td>
							<td width=\"8%\" style=\"border-bottom:1px solid #333;\">&nbsp;</td>
						    <td width=\"10%\" style=\"font-size:14px;font-weight:bold;text-align:center;border-bottom:1px solid #333;\">Qty Filled</td>
							<td width=\"3%\" style=\"border-bottom:1px solid #333;\">&nbsp;</td>
							</tr>";
						
				       }
						
							
				$product_report .="<tr><td width=\"10%\" style=\"font-size:14px;text-align:left;padding-top:5px;\">$order_date1</td>
							<td width=\"3%\" style='padding-top:5px;'>&nbsp;</td>
							<td width=\"10%\" style=\"font-size:14px;text-align:left;padding-top:5px;\">$order_id2</td>
							<td width=\"3%\" style='padding-top:5px;'>&nbsp;</td>
							
							<td width=\"10%\" style=\"font-size:14px;text-align:left;padding-top:5px;\">$product_name</td>
							<td width=\"3%\" style='padding-top:5px;'>&nbsp;</td>
							
							<td width=\"10\" style=\"font-size:14px;text-align:center;padding-top:5px;\">$tot_pro_filled_items</td>
						   </tr>";
				
				$match_order_id[] = $order_id1;
				$match_category_name[] = $category_name;
				
				 $loop1++;
				}
		
		/*	echo "<br>";
			print_r($match_order_id);
			exit;*/			
				
			}
	}
		  
    }        
    
		
   
  
    $date = date("m/d/y");
    $dirName12  = 'pdf1';
    $filename = 'qoute.html';
    $handle = fopen($filename, "rb");
    while (!feof($handle)) {  
      $contents .= fread($handle, 8192); 
    }
					 
	
	 $contents = str_replace("__EVENTS__", "$EVENTS", "$contents");
	 $contents = str_replace("__SITE_URL__", "$SITE_URL", "$contents");
   	 $contents = str_replace("__order_date_range1__", "$order_date_range1", "$contents");
	 $contents = str_replace("__order_date_range2__", "$order_date_range2", "$contents");
	 $contents = str_replace("__filled_date_range1__", "$filled_date_range1", "$contents");
	 $contents = str_replace("__filled_date_range2__", "$filled_date_range2", "$contents");
	 $contents = str_replace("__product_range__", "$product_range", "$contents");
	 $contents = str_replace("__status__", "$status", "$contents");
	 $contents = str_replace("__order_id1__", "$order_id1", "$contents");
	 $contents = str_replace("__order_id1__", "$order_id1", "$contents");
	  $contents = str_replace("__category_name1__", "$category_name1", "$contents");
	 $contents = str_replace("__customer__", "$customer", "$contents");
	 $contents = str_replace("__customertype__", "$customertype", "$contents");
	  $contents = str_replace("__costcentre__", "$costcentre", "$contents");
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