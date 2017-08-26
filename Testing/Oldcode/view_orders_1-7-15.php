<?php session_start();
 
include("config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$MEMBER_NAME = $_SESSION["SESS_MEMBER_NAME"]; 
$MEMBER_TYPE = $_SESSION["SESS_MEMBER_TYPE"]; 
$MEMBER_ID   = $_SESSION["SESS_MEMBER_ID"];

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

function getData($db,$db1)
{

  global $SITE_URL,$PAGE_LIST,$MEMBER_ID;
  
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
	   

       //$query="select * from orders  where 1 order by ordered_date desc"; 
	   $query2 = "select O.*,W.ward_name,W.ward_category,WC.type from orders as O left join wards as W on O.ward_id=W.id 
 				left join order_products as P on O.id=P.order_id 
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
       //$total_weight          =   $row['total_weight'];
       //$item_ordered          =   $row['item_ordered'];
	   if($row['date_filled']!=0)
      	 $date_filled           =   date("d.m.Y",$row['date_filled']);
	   else
	   	$date_filled ="-";
       $status                   =   $row['status'];
      
	  
        $select="select  count(id) as total_items,sum(product_weight) as p_weight  from order_products where order_id=$id";	
		$db1->query($select); 
		$rec=$db1->fetch_assoc();
		$item_ordered=$rec['total_items'];
        $total_weight=$rec['p_weight'];
		
		$select1="select  count(P.id) as filled_items,sum(P.product_weight) as filled_weight  from order_products as P 
					left join orders as O on O.id=P.order_id where P.order_id=$id and O.status='FILLED'";	
		$db1->query($select1); 
		$rec1=$db1->fetch_assoc();
		$item_filled=$rec1['filled_items'];
        $total_filled_weight=$rec1['filled_weight'];
		
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
                       <td><a href=\"$SITE_URL/edit_order.php?id=$id\"><img src=\"$SITE_URL/images/icon_search.png\" align='top' style=\"height:20px\"></a>&nbsp;<input type=\"image\" src=\"$SITE_URL/images/icon_close.png\" style='vertical-align:top;height:20px;' onclick=\"remove_order($id);\" />&nbsp;<a href=\"$SITE_URL/epdf/quotePDF1.php?id=$id\"><img src=\"$SITE_URL/images/icon_pdf.png\" align='top' style=\"height:20px\"></a></td>                      
                    </tr>";	
      
            $loop++;
        }
        
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