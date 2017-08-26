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
  
  $select="select product_name from products where id='$products'";
  $db->query($select);
  if($db->num_rows())
  {
  		$res=$db->fetch_assoc();
		$product_range=$res['product_name'];
  }

  $customer=trim($_REQUEST['customer']);
    
   $query2 = "select O.*,W.ward_name,W.ward_category from orders as O left join wards as W on O.ward_id=W.id 
 				left join order_products as P on O.id=P.order_id  where 1";
				
				
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
 if($_REQUEST['status']!='')
 {
 	$query2 .= " and  O.status='".$_REQUEST['status']."' ";	
 }
   			
				
   $query2 .=" group by O.id order by O.ordered_date desc";
   
   $db1->query($query2);
   $number_rows= $db1->num_rows();  
   $display_count = $number_rows;
  
      
	if($db1->num_rows())
	{
	  $loop=1;
	  $count=1;  
	  $EVENTS .=" <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"table table-bordered responsive dataTable product_table\">
                        <thead>
                        	<tr>
                            	<th width=\"30\">#</th>
                                <th width=\"60\">Order Date</th>
                                <th width=\"120\">Ward</th>                                                    
                                <th width=\"60\">Items</th>
								<th width=\"60\">Weight</th>
								<th width=\"60\">Date Filled</th>
								<th width=\"60\">Status</th>                               
                                <th width=\"60\">Action</th>
                            </tr>
                           </thead>";
						   
	while($row = $db1->fetch_array())
  	{  
      
       
       $id1               .=   $row['id'].',';      	   
       $ward_id1               .=   $row['ward_id'].',';  	
	            
           
  } 
  
  	$id=substr($id1,0,-1);
	$ward_id=substr($ward_id1,0,-1);	
	 
	 $sql1="select count(id) as total_item,sum(product_weight) as total_weight from order_products where order_id in ($id)";
	$db->query($sql1);
	if($db->num_rows())
	{
		$rec=$db->fetch_assoc();
		$total_items=$rec[total_item];
		$total_weight=$rec[total_weight];
	}
  
   $sql2="select count(P.id) as total_filled_item,sum(P.product_weight) as total_filled_weight from order_products as P 
  			left join orders as O on O.id=P.order_id where P.order_id in ($id) and O.status='FILLED'";
	$db->query($sql2);
	if($db->num_rows())
	{
		$rec1=$db->fetch_assoc();
		$total_filled_items=$rec1[total_filled_item];
		$total_filled_weight=$rec1[total_filled_weight];
	}
	
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
			
			
			
			 $sql5="select count(P.id) as tot_filled_item,sum(P.product_weight) as tot_filled_weight from ward_products as P 
  			left join orders as O on O.ward_id=P.ward_id where P.ward_id in($wardid) and O.status='FILLED'";
			$db1->query($sql5);
			if($db1->num_rows())
			{
				$rec4=$db1->fetch_assoc();
				$tot_filled_items=$rec4['tot_filled_item'];
				
				$sum_ward_filled +=$tot_filled_items;
				
				$tot_filled_weight=$rec4['tot_filled_weight'];				
				$sum_filled_weight += $tot_filled_weight;
				
				$filled_per=($tot_filled_items/$tot_items)*100;
				
				//$sum_ward_filled_percent += $filled_per;
				
				$sum_ward_filled_percent = number_format((($sum_ward_filled/$sum_items)*100),2);
			}
		
			
			
			
			$ward_report .="<tr>
							<td width=\"15%\" style=\"font-size:14px;text-align:left\">$ward_name</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15%\" style=\"font-size:14px;text-align:center\">$tot_items</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15\" style=\"font-size:14px;text-align:center\">$tot_filled_items</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15\" style=\"font-size:14px;text-align:center\">".number_format($filled_per,2)." %</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15%\" style=\"font-size:14px;text-align:center\">$tot_filled_weight kg</td>
						</tr>";
		}
		
				
		
		  $sql6="select product_id,product_name,product_weight from order_products where order_id in ($id) group by product_id";
			$db1->query($sql6);
			if($db1->num_rows())
			{
				while($rec5=$db1->fetch_assoc()){
				$product_name=$rec5['product_name'];
				$product_weight=$rec5['product_weight'];
				$sum_weight += $product_weight;
				$prid=$rec5[product_id];
				
				 $sql7="select count(id) as tot_pro_items,sum(product_weight) as tot_pro_product_weight from order_products where product_id='$prid' and order_id in ($id) ";
				$db2->query($sql7);
				if($db2->num_rows())
				{
					$rec6=$db2->fetch_assoc();
					$tot_pro_items=$rec6['tot_pro_items'];
					$sum_product_items +=$tot_pro_items;
									}
				
			  $sql8="select count(P.id) as tot_pro_filled_items,sum(product_weight) as tot_pro_product_weight from order_products as P
			   	left join orders as O on O.id=P.order_id where product_id='$prid' and order_id in ($id) and O.status='FILLED'";
				$db2->query($sql8);
				if($db2->num_rows())
				{
					$rec6=$db2->fetch_assoc();
					$tot_pro_product_weight=$rec6['tot_pro_product_weight'];
					$sum_product_filled_weight += $tot_pro_product_weight;

					
					$tot_pro_filled_items =$rec6['tot_pro_filled_items'];
					$sum_product_filled +=$tot_pro_filled_items;
				}

				$filled_pro_per=($tot_pro_filled_items/$tot_pro_items)*100;
				$sum_product_filled_percent =number_format(($sum_product_filled/$sum_product_items)*100,2);
				
				$product_report .="<tr>
							<td width=\"15%\" style=\"font-size:14px;text-align:left\">$product_name</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15%\" style=\"font-size:14px;text-align:center\">$product_weight</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15%\" style=\"font-size:14px;text-align:center\">$tot_pro_items</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15\" style=\"font-size:14px;text-align:center\">$tot_pro_filled_items</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15\" style=\"font-size:14px;text-align:center\">".number_format($filled_pro_per,2)." %</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15%\" style=\"font-size:14px;text-align:center\">$tot_pro_product_weight kg</td>
						</tr>";
				
				}
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