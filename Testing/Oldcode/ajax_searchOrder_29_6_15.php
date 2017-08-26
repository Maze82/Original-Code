<?php session_start(); ?>
<?php	

include("config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php"); 

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());


$MEMBER_NAME = $_SESSION["SESS_MEMBER_NAME"]; 
$MEMBER_TYPE = $_SESSION["SESS_MEMBER_TYPE"]; 
$MEMBER_ID   = $_SESSION["SESS_MEMBER_ID"];  
getOrderSearchResult($db,$db1,$db2);

function getOrderSearchResult($db,$db1,$db2)
{
  global $pdf_link,$SITE_URL;
  
   $query2 = "select O.*,W.ward_name,W.ward_category,W.type from orders as O left join wards as W on O.ward_id=W.id 
 				left join order_products as P on O.id=P.order_id  where 1";
 //echo date('d/m/Y h:i:s A')."<br>".date('d/m/Y h:i:s A','1427958758');
   
   $orderdate1=trim($_REQUEST['orderdate1']);
   $orderdate2=trim($_REQUEST['orderdate2']);
  $filleddate1=trim($_REQUEST['filleddate1']);
  $filleddate2=trim($_REQUEST['filleddate2']);
  $status=trim($_REQUEST['status']);
  $products=trim($_REQUEST['products']);
  $customer=trim($_REQUEST['customer']);
  $costcentre=trim($_REQUEST['costcentre']);
  $customertype=trim($_REQUEST['type']);
   
  $date_parts=explode("/", $orderdate1);
        $day = $date_parts[0];
       $month = $date_parts[1];
       $year = $date_parts[2];
	   
       $orderdate1=mktime(0,0,0,$month,$day,$year);
	   
  
     $date_parts1=explode("/", $orderdate2);
       $day1 = $date_parts1[0];
       $month1 = $date_parts1[1];
       $year1 = $date_parts1[2];
	   //echo $day1."/".$month1."/".$year1;
       $orderdate2=mktime(23,59,59,$month1,$day1,$year1);
	   
	   $date_parts2=explode("/", $filleddate1);
       $day2 = $date_parts2[0];
       $month2 = $date_parts2[1];
       $year2 = $date_parts2[2];
       $filleddate1=mktime(0,0,0,$month2,$day2,$year2);
  
     $date_parts3=explode("/", $filleddate2);
       $day3 = $date_parts3[0];
       $month3 = $date_parts3[1];
       $year3 = $date_parts3[2];
       $filleddate2=mktime(23,59,59,$month3,$day3,$year3);
 	
  	if($_REQUEST['costcentre']!='')
  {    
    $query2 .=" and  W.ward_name ='$costcentre'";  
  } 
  
  	if($_REQUEST['customer']!='')
  {    
    $query2 .=" and  W.ward_category ='$customer'";  
  } 
  	if($_REQUEST['type']!='')
  {    
    $query2 .=" and  W.type ='$customertype'";  
  } 
  	if($_REQUEST['products']!='')
  {    
    $query2 .=" and  P.product_id = '$products'";  
  }  
  	
	if($_REQUEST['orderdate1']!=0 && $_REQUEST['orderdate2']==0)
  {
    $query2 .= " and  O.ordered_date >= $orderdate1";
    
  }
 if($_REQUEST['orderdate1']==0 && $_REQUEST['orderdate2']!=0)
  {
    $query2 .= " and  O.ordered_date <= $orderdate2";
    
  }
   if($_REQUEST['orderdate1']!=0 && $_REQUEST['orderdate2']!=0)
  {
    $query2 .= " and  O.ordered_date >= $orderdate1 and O.ordered_date <= $orderdate2";
    
  }
  if($_REQUEST['filleddate1']!=0 && $_REQUEST['filleddate2']==0)
  { 
    $query2 .= " and  O.date_filled  >= $filleddate1";
    
  }
 if($_REQUEST['filleddate1']==0 && $_REQUEST['filleddate2']!=0)
  {
    $query2 .= " and  O.date_filled  <= $filleddate2";
    
  }
   if($_REQUEST['filleddate1']!=0 && $_REQUEST['filleddate2']!=0)
  {
    $query2 .= " and  O.date_filled >= $filleddate1 and O.date_filled <= $filleddate2";
    
  }
 if($_REQUEST['status']!='')
 {
 	$query2 .= " and  O.status='".$_REQUEST['status']."' ";	
 }
 
    
   $query2 .=" group by O.id order by O.ordered_date desc";		
 
  //secho $query2;
 $db1->query($query2);
 $number_rows= $db1->num_rows();  
  $display_count = $number_rows;
  
      
 if($db1->num_rows())
 {
  $loop=1;
  $count=1;  
  
    $pdf_link="<a href=\"$SITE_URL/epdf/quotePDF.php?customer=$customer&costcentre=$costcentre&order_date1=$orderdate1&order_date2=$orderdate2&filleddate1=$filleddate1&filleddate2=$filleddate2&status=$status&product=$products\"><img src=\"$SITE_URL/images/icon_pdf.png\" align='top' align='top'></a>";
	
   $SEARCH_LIST="<div align=\"right\" style=\"font-size:14px;font-weight:600;color:#77ca60\">PDF Report- $pdf_link</div><br>";

    $SEARCH_LIST .=" <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"table table-bordered responsive dataTable product_table\">
                        <thead>
                        	<tr>
                            	<th width=\"30\">#</th>
                                <th width=\"60\">Order Date</th>
                                <th width=\"120\">Ward</th>                                                    
                                <th width=\"60\">Ordered Items</th>
								<th width=\"60\">Ordered Weight</th>
								<th width=\"60\">Filled Items</th>
								<th width=\"60\">Filled Weight</th>
								<th width=\"60\">Date Filled</th>
								<th width=\"60\">Status</th>                               
                                <th width=\"60\">Action</th>
                            </tr>
                           </thead>";
						   
						 


  while($row = $db1->fetch_array())
  {  
      //$srNo = $page*$MAX- $MAX + $loop +1;
       
      $id               =   $row['id'];
       $ordered_date           =   date('d.m.Y',$row['ordered_date']);
       $ward_name           =   $row['ward_name'];
       $item_ordered           =   $row['item_ordered'];      
       $total_weight            =   $row['total_weight'];
	   
	   if($row['date_filled']!=0)
       		$date_filled            =   date('d.m.Y',$row['date_filled']);
	   else
	   		$date_filled='-';
	   $status=$row['status'];
       
 	        
       if($loop%2==0)
            $class="class='even_class'";
       else
          $class="";
          
        $select="select  count(id) as total_items,sum(product_weight) as p_weight  from order_products where order_id=$id";	
		$db2->query($select); 
		$rec=$db2->fetch_assoc();
		$item_ordered=$rec['total_items'];
        $total_weight=$rec['p_weight']; 
		
		 $select1="select  count(P.id) as filled_items,sum(P.product_weight) as filled_weight  from order_products as P 
					left join orders as O on O.id=P.order_id where P.order_id=$id and O.status='FILLED'";
		$db2->query($select1); 
		$rec1=$db2->fetch_assoc();
		$item_filled=$rec1['filled_items'];
        $total_filled_weight=$rec1['filled_weight'];
       
    $SEARCH_LIST.="<tr id=\"cus_$id\" $class>
                      <td>$id</td>
                      <td>$ordered_date</td>
                      <td>$ward_name</td>
                       <td>$item_ordered</td>
                       <td>$total_weight</td>
					    <td>$item_filled</td>                      
                       <td>$total_filled_weight</td> 
                       <td>$date_filled</td>
                      <td>$status</td>                    
                     <td><a href=\"$SITE_URL/edit_order.php?id=$id\"><img src=\"$SITE_URL/images/icon_search.png\" align='top' align='top'></a> &nbsp;<input type=\"image\" src=\"$SITE_URL/images/icon_close.png\" style='vertical-align:top;' /> </td>   
                    </tr>";	
      
            $loop++;  
  } 
  
 
   $SEARCH_LIST.="</table>";
   
   
 }
 else
 {
 	$SEARCH_LIST="<div class=\"nav_details2\">
                    	<p>Orders</span></p>
                    </div>
                    <div class=\"block_customer_search\" ><div style=\"margin-left:250px;margin-top:100px;margin-bottom:100px;\"><strong> Sorry , No Data Available !</strong></div></div>";
 		
 } 
 echo   $SEARCH_LIST;                      
}
?>