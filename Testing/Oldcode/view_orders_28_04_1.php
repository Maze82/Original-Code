<?php session_start();
 
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

global $SITE_URL;


if($_GET['ord_id']!='')
{

     $id  =  $_GET['ord_id']; 
	 
	 $delete1="delete from order_products where order_id='$id'";
	 $db->query($delete1); 
	   
     $delete2="delete from orders where id=$id";
     $db->query($delete2);
	 header("Location:view_orders.php");
     exit; 
}


if(isset($_GET['saveFilter']))
{

addFilterPreferencevalue($db);
//getData($db,$db1);
}

if(isset($_GET['clearFilter']))
{
clearFilterListing($db);
header("Location:view_orders.php");
exit;
}

/*if(isset($_POST['FilterListing']))
{
getOrderSearchResult($db,$db1,$db2);
}*/
checkLogin();
getData($db,$db1);
$product_LIST=GetOrderProducts($db);
$ward_LIST=GetOrderWards($db);
$customers=getWardcategory($db,$id='');
 display_message(); 

 $TOPBAR	      = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html");
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/view_order.html");

 $BOTTOMBAR		  = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		  = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();


function clearFilterListing($db)
{
$MEMBER_ID   =$_SESSION["SESS_MEMBER_ID"];  



	   $delete="delete from filtering_preferences where user_id='$MEMBER_ID'";
	   
	     $db->query($delete);
       
		//echo 1 ;
}

function addFilterPreferencevalue($db)
{
$MEMBER_ID   =$_SESSION["SESS_MEMBER_ID"];  
$orderdate11=trim($_GET['orderdate1']);
$orderdate21=trim($_GET['orderdate2']);
$filleddate11=trim($_GET['filleddate1']);
$filleddate21=trim($_GET['filleddate2']);
 $status1=trim($_GET['status']);
$products1=trim($_GET['product']);
$customer1=trim($_GET['customer']);
$costcentre1=trim($_GET['costcentre']);
$customertype1=trim($_GET['type']);

$date_parts=explode("/", $orderdate11);
	$day = $date_parts[0];
	$month = $date_parts[1];
	$year = $date_parts[2];
$orderdate11=mktime(0,0,0,$month,$day,$year);
	   
  
     $date_parts1=explode("/", $orderdate21);
       $day1 = $date_parts1[0];
       $month1 = $date_parts1[1];
       $year1 = $date_parts1[2];
	   //echo $day1."/".$month1."/".$year1;
       $orderdate21=mktime(23,59,59,$month1,$day1,$year1);
	   
	   $date_parts2=explode("/", $filleddate11);
       $day2 = $date_parts2[0];
       $month2 = $date_parts2[1];
       $year2 = $date_parts2[2];
       $filleddate11=mktime(0,0,0,$month2,$day2,$year2);
  
     $date_parts3=explode("/", $filleddate21);
       $day3 = $date_parts3[0];
       $month3 = $date_parts3[1];
       $year3 = $date_parts3[2];
       $filleddate21=mktime(23,59,59,$month3,$day3,$year3);
	   $select="select * from filtering_preferences where user_id='$MEMBER_ID'";
	   
	     $db->query($select);
       if($db->num_rows()>0)
        {
		
		 $rec = $db->fetch_assoc();
		 $id=$rec['user_id'];
		
	 $queryupdate="update filtering_preferences set 
	    order_date_from='$orderdate11',
		order_date_to='$orderdate21',
		filled_date_from='$filleddate11',
		filled_date_to='$filleddate21',
		orderstatus='$status1',
		costcentres='$costcentre1',
		productrange='$products1',
		customers='$customer1',
		customertype='$customertype1',
		createtime='".mktime()."' where user_id='$id'";
		
			$db->query($queryupdate);
	
		
		}
		else
		{
      $query="insert into filtering_preferences  set 
		user_id='$MEMBER_ID',
		order_date_from='$orderdate11',
		order_date_to='$orderdate21',
		filled_date_from='$filleddate11',
		filled_date_to='$filleddate21',
		orderstatus='$status1',
		costcentres='$costcentre1',
		productrange='$products1',
		customers='$customer1',
		customertype='$customertype1',
		createtime='".mktime()."'";
		
		$db->query($query);
	
	
	
		}
		
}

function getData($db,$db1)
{

  global $SITE_URL,$PAGE_LIST,$MEMBER_ID,$pdf_link,$pdf_link1;
   global $keyword,$srchStatus,$ARR_GLOBAL_STATUS;
 	global $PREV_PAGE_LINK,$NEXT_PAGE_LINK,$TOTAL_RECORDSET,$PAGE_NAVS, $CURRENT_PAGE_NO, $TOTAL_PAGES,$totalAll, $SITE_URL;

	$CURRENT_PAGE_NO=0;
	$TOTAL_PAGES=1;
	$TOTAL_RECORDSET=0;

	$MAX=300;
	$page=$_REQUEST['page'];
	  if(!empty($page))
    {
	  $page = (int)$page;
	  }
    else
    {
	  $page = 1;
	  }
	$start = ($page - 1) * $MAX;
	$next_links .= $pagination;
  
$queryfilter="select * from filtering_preferences where user_id='$MEMBER_ID'";
 $db->query($queryfilter);
 $db->num_rows() ;
		
 if($db->num_rows() > 0)
  {
   $rec = $db->fetch_assoc();

$id   =$rec["user_id"];  
$orderdate1=$rec['order_date_from'];
$orderdate2=$rec['order_date_to'];
$filleddate1=$rec['filled_date_from'];
$filleddate2=$rec['filled_date_to'];
$status1=$rec['orderstatus'];
$costcentre1=$rec['costcentres'];
$products1=$rec['productrange'];
$customer1=$rec['customers'];

$customertype1=$rec['customertype'];

}

if(trim($_REQUEST['orderdate1'])!='')
  {
  $orderdate1=trim($_REQUEST['orderdate1']);
   
    $date_parts=explode("/", $orderdate1);
        $day = $date_parts[0];
       $month = $date_parts[1];
       $year = $date_parts[2];
	   
   $orderdate1=mktime(0,0,0,$month,$day,$year);
	   
  }
  if(trim($_REQUEST['orderdate2'])!='')
   {
   $orderdate2=trim($_REQUEST['orderdate2']);
   
    $date_parts1=explode("/", $orderdate2);
       $day1 = $date_parts1[0];
       $month1 = $date_parts1[1];
       $year1 = $date_parts1[2];
	   //echo $day1."/".$month1."/".$year1;
    $orderdate2=mktime(23,59,59,$month1,$day1,$year1);
   }
    if(trim($_REQUEST['filleddate1'])!='')
  {
  $filleddate1=trim($_REQUEST['filleddate1']);
  
   $date_parts2=explode("/", $filleddate1);
       $day2 = $date_parts2[0];
       $month2 = $date_parts2[1];
       $year2 = $date_parts2[2];
       $filleddate1=mktime(0,0,0,$month2,$day2,$year2);
  }
   if(trim($_REQUEST['filleddate2'])!='')
  {
  $filleddate2=trim($_REQUEST['filleddate2']);
   $date_parts3=explode("/", $filleddate2);
       $day3 = $date_parts3[0];
       $month3 = $date_parts3[1];
       $year3 = $date_parts3[2];
       $filleddate2=mktime(23,59,59,$month3,$day3,$year3);
  }
  if(trim($_REQUEST['status'])!='')
  {
 $status1=trim($_REQUEST['status']);
  }
   if(trim($_REQUEST['product'])!='')
  {
  $products1=trim($_REQUEST['product']);
  }
  if(trim($_REQUEST['customer'])!='')
  {
  $customer1=trim($_REQUEST['customer']);
  }
  if(trim($_REQUEST['costcentre'])!='')
  {
  $costcentre1=trim($_REQUEST['costcentre']);
  }
   if(trim($_REQUEST['type'])!='')
  {
  $customertype1=trim($_REQUEST['type']);
  }
	   

       //$query="select * from orders  where 1 order by ordered_date desc"; 
    /*   $query1 = "select O.*,W.ward_name,W.ward_category,WC.type from orders as O left join wards as W on O.ward_id=W.id 
 				left join order_products as P on O.id=P.order_id 
				left join ward_category as WC on  W.ward_category=WC.id where 1";
				
	   $query2 = "select O.*,W.ward_name,W.ward_category,WC.type from orders as O left join wards as W on O.ward_id=W.id 
 				left join order_products as P on O.id=P.order_id 
				left join ward_category as WC on  W.ward_category=WC.id where 1";*/
				
				
		$query1 = "select O.*,W.ward_name,W.ward_category,WC.type from orders as O left join wards as W on O.ward_id=W.id 
		     inner join order_products as P on O.id=P.order_id
 				left join ward_category as WC on  W.ward_category=WC.id where 1";
				
				
				
	   $query2 = "select O.*,W.ward_name,W.ward_category,WC.type from orders as O left join wards as W on O.ward_id=W.id 
	            inner join order_products as P on O.id=P.order_id
 				left join ward_category as WC on  W.ward_category=WC.id where 1";
				
		
				
 if($status1!='')
 {
    $query1 .= " and  O.status='$status1'";	     
	$query2 .= " and  O.status='$status1'";	
 }
 
  	if($costcentre1!='')
  { 
   $query1 .=" and  W.ward_name ='$costcentre1'";    
    $query2 .=" and  W.ward_name ='$costcentre1'";  
  } 
  
  	if($customer1!='')
  {  
     $query1 .=" and  W.ward_category ='$customer1'";   
    $query2 .=" and  W.ward_category ='$customer1'";  
  } 
  	if($customertype1!='')
  {    
    $query1 .=" and  WC.type ='$customertype1'";  
	$query2 .=" and  WC.type ='$customertype1'";  
  } 
  	if($products1!='')
  { 
    $query1 .=" and  P.product_id = '$products1'";     
    $query2 .=" and  P.product_id = '$products1'";  
  }  
  	
	if($orderdate1!=0 && $orderdate2==0)
  { 
   $query1 .= " and  O.ordered_date >= $orderdate1";
  $query2 .= " and  O.ordered_date >= $orderdate1";
    
  }
 if($orderdate1==0 && $orderdate2!=0)
  {
    $query1 .= " and  O.ordered_date <= $orderdate2";
    $query2 .= " and  O.ordered_date <= $orderdate2";
    
  }
   if($orderdate1!=0 && $orderdate2!=0)
  {
  
    $query1 .= " and  O.ordered_date >= $orderdate1 and O.ordered_date <= $orderdate2";
    $query2 .= " and  O.ordered_date >= $orderdate1 and O.ordered_date <= $orderdate2";
    
  }
  if($filleddate1!=0 && $filleddate2==0)
  {
    $query1 .= " and  O.date_filled  >= $filleddate1"; 
    $query2 .= " and  O.date_filled  >= $filleddate1";
    
  }
 if($filleddate1==0 && $filleddate2!=0)
  { $query1 .= " and  O.date_filled  <= $filleddate2";
    $query2 .= " and  O.date_filled  <= $filleddate2";
    
  }
   if($filleddate1!=0 && $filleddate2!=0)
  {
   $query1 .= " and  O.date_filled >= $filleddate1 and O.date_filled <= $filleddate2";
  $query2 .= " and  O.date_filled >= $filleddate1 and O.date_filled <= $filleddate2";
    
  }
$query1 .=" group by O.id order by O.ordered_date desc";	
 $query2 .=" group by O.id order by O.ordered_date desc";	
    $query2.=" limit  $start, $MAX ";
 
  $db->query($query1);
	if($db->num_rows()) 
	{	
        $row = $db->fetch_array();
    	 $TOTAL_RECORDSET = $db->num_rows();
    	  $PAGE_NAVS = paginate($MAX, $TOTAL_RECORDSET);
    		$CURRENT_PAGE_NO=$page;
	}
	
	if($PAGE_NAVS == '') $PAGE_NAVS = '';
	   $totalAll='0';		
	   	
	   
         $db->query($query2);	
		if($db->num_rows())
		{	
      $loop=1; 
	  
	 if($customer1!=''||$costcentre1!=''||$orderdate1!=''||$orderdate2!=''||$filleddate1!=''||$filleddate2!=''||$status1!=''||$products1!=''||$customertype1!='')
	  {
	
	   // echo "hi";exit;
	   
	    $pdf_link1="PDF-Report<a href=\"$SITE_URL/epdf/quotePDF_old.php?customer=$customer1&costcentre=$costcentre1&order_date1=$orderdate1&order_date2=$orderdate2&filleddate1=$filleddate1&filleddate2=$filleddate2&status=$status1&product=$products1&type=$customertype1\"><img src=\"$SITE_URL/images/icon_pdf.png\" align='top' align='top'></a>";
	   
      $pdf_link="PDF-Report<a href=\"$SITE_URL/epdf/quotePDF.php?customer=$customer1&costcentre=$costcentre1&order_date1=$orderdate1&order_date2=$orderdate2&filleddate1=$filleddate1&filleddate2=$filleddate2&status=$status1&product=$products1&type=$customertype1\"><img src=\"$SITE_URL/images/icon_pdf.png\" align='top' align='top'></a>";
	  }
	  else
	  {
	 // $pdf_link='';
	    $pdf_link1="PDF-Report<a href=\"$SITE_URL/epdf/quotePDF_old.php?customer=$customer1&costcentre=$costcentre1&order_date1=$orderdate1&order_date2=$orderdate2&filleddate1=$filleddate1&filleddate2=$filleddate2&status=$status1&product=$products1&type=$customertype1\"><img src=\"$SITE_URL/images/icon_pdf.png\" align='top' align='top'></a>";
	   
	   $pdf_link="PDF-Report<a href=\"$SITE_URL/epdf/quotePDF.php?customer=$customer1&costcentre=$costcentre1&order_date1=$orderdate1&order_date2=$orderdate2&filleddate1=$filleddate1&filleddate2=$filleddate2&status=$status1&product=$products1&type=$customertype1\"><img src=\"$SITE_URL/images/icon_pdf.png\" align='top' align='top'></a>";
	  }
      while($row=$db->fetch_array())
      {
	
       $id                    =   $row['id'];
      
       $ordered_date          =   date("d.m.Y",$row['ordered_date']);
       $ward_name             =   getWradListName($db1,$row['ward_id']); 
       //$total_weight          =   $row['total_weight'];
       //$item_ordered          =   $row['item_ordered'];
	   if($row['date_filled']!=0)
      	 $date_filled           =   date("d.m.Y",$row['date_filled']);
	   else
	   	$date_filled ="-";
       $status                   =   $row['status'];
      
	  
	  

 //$select="select  count(id) as total_items,sum(product_weight) as p_weight  from order_products where order_id=$id";	
 $select="select  sum(product_quantity) as total_items,sum(total_product_weight) as p_weight  from order_products where order_id=$id";
		$db1->query($select); 
		$rec=$db1->fetch_assoc();
		/*$item_ordered=$rec['item_ordered'];
        $total_weight=$rec['total_weight'];*/
		
		$item_ordered=$rec['total_items'];
        $total_weight=$rec['p_weight'];
		
		/*$select1="select  count(P.id) as filled_items,sum(P.product_weight) as filled_weight  from order_products as P 
					left join orders as O on O.id=P.order_id where P.order_id=$id and O.status='FILLED'";*/
					
		/*$select1="select  count(P.product_quantity) as filled_items,sum(P.total_product_weight) as filled_weight  from order_products as P 
					left join orders as O on O.id=P.order_id where P.order_id=$id and O.status='FILLED' and P.product_quantity>0";	*/	
		$select1="select  filled_order,filled_weight  from order_products as P 
					left join orders as O on O.id=P.order_id where P.order_id=$id and O.status='FILLED' and P.product_quantity>0";				
					
		$db1->query($select1); 
		$rec1=$db1->fetch_assoc();
		$item_filled=$rec1['filled_order'];
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
		
		  $PAGE_LIST.="<tr id=\"ep_$id\">
		         
                      <td>$id</td>
                       <td>$ordered_date</td>
					   <td>$ward_name</td>
                       <td>$item_ordered</td>                      
					   <td>$total_weight</td>
					   <td>$item_filled</td>                      
					   <td>$total_filled_weight</td>  
					   <td>$date_filled</td> 
					   <td>$status</td>                     
                       <td><a href=\"$SITE_URL/edit_order.php?id=$id\"><img src=\"$SITE_URL/images/icon_search.png\" align='top' style=\"height:20px\"></a>&nbsp;<!--<input type=\"image\" src=\"$SITE_URL/images/icon_close.png\" style='vertical-align:top;height:20px;' onclick=\"remove_order($id);\" />-->&nbsp;<a href=\"$SITE_URL/view_orders.php?ord_id=$id\"><img src=\"$SITE_URL/images/icon_close.png\" style='vertical-align:top;height:20px;'/></a>&nbsp;<a href=\"$SITE_URL/epdf/quotePDF1.php?id=$id\"><img src=\"$SITE_URL/images/icon_pdf.png\" align='top' style=\"height:20px\"></a>&nbsp;<a href=\"javascript:void(0);\" onclick=\"popup2('$SITE_URL/epdf/quoteHTML.php?id=$id');\"><img src=\"$SITE_URL/images/printer.png\" align='top' style=\"height:20px\"></a></td>                      
                    </tr>";	
      
            $loop++;
        }
         $PAGE_LIST.="<tr><td> $loop</td></tr>";
      }
  	else
		{
			$PAGE_LIST="<tr><td colspan='5' align='center'>---- E M P T Y ----</td></tr>";	
		}
}

function GetOrderProducts($db)
{
	$select="select product_id,product_name from order_products where 1 group by product_id";
	$db->query($select);
	if($db->num_rows())
	{
		while($row=$db->fetch_assoc())
  		{  
		   
			$product_LIST .="<option value=\"$row[product_id]\">$row[product_name]</option>"; 
            
  		}
  		
		return  $product_LIST;
	}
}

function GetOrderWards($db)
{
	$select="select id,ward_name from wards  where 1 group by id";
	$db->query($select);
	if($db->num_rows())
	{
		while($row=$db->fetch_assoc())
  		{  
		   
		  
			$ward_LIST .="<option value=\"$row[ward_name]\">$row[ward_name]</option>"; 
            
  		}
  		
		return  $ward_LIST;
	}
}



?>