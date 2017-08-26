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

 display_message(); 
 $customers=getWardcategory($db,$id='');

 $TOPBAR	      = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html");
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/view_products.html");

 $BOTTOMBAR		  = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		  = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function getData($db,$db1)
{

  global $SITE_URL,$DIR_DISPLAY_USER_FILE,$PAGE_LIST,$MEMBER_ID;
  
  
   global $SITE_URL,$PAGE_LIST,$MEMBER_ID,$pdf_link,$customer1;
   global $keyword,$srchStatus,$ARR_GLOBAL_STATUS;
 	global $PREV_PAGE_LINK,$NEXT_PAGE_LINK,$TOTAL_RECORDSET,$PAGE_NAVS, $CURRENT_PAGE_NO, $TOTAL_PAGES,$totalAll, $SITE_URL;

	$CURRENT_PAGE_NO=0;
	$TOTAL_PAGES=1;
	$TOTAL_RECORDSET=0;

	$MAX=100;
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
	
	
	if(trim($_REQUEST['customer'])!='')
  {
  $customer1=trim($_REQUEST['customer']);
  }
   
     
	   
	 /* $query1="select count(*) as total from products where 1=1 ";
	   $query2="select * from products where 1=1 ";*/
	   
	
   $query1="select distinct(P.id), P.product_name,P.product_weight,P.description,P.code,WP.ward_id from products as P left join ward_products as WP on P.id=WP.product_id left join wards as W on WP.ward_id=W.ward_category where 1=1 ";
		
		
	   $query2="select distinct(P.id), P.product_name,P.product_weight,P.description,P.code,WP.ward_id from products as P left join ward_products as WP on P.id=WP.product_id left join wards as W on WP.ward_id=W.ward_category where 1=1";
	  
 
	
 

	 
	
	
	if($customer1!='')
  {  
     $query1 .=" and  W.ward_category ='$customer1'";   
   $query2 .=" and  W.ward_category ='$customer1'";  
  } 
	
	if($_REQUEST['keyword']!='' && $_REQUEST['keyword']!='Enter Title')
	{
	$keyword	= $_REQUEST['keyword'];
	$query1.=" and P.product_name like '%".$keyword."%' or P.code like '%".$keyword."%' ";
	$query2.=" and P.product_name like '%".$keyword."%' or P.code like '%".$keyword."%' "; 
	}
	
		
	 $query1.="group by P.id order by P.product_name asc";
	 $query2.=" group by P.id order by P.product_name asc";

	$query2.=" limit  $start, $MAX ";

	
	$db->query($query1);
	if($db->num_rows()) 
	{	
		$row = $db->fetch_array();
		$TOT=$db->num_rows();
		$TOTAL_RECORDSET = $TOT;
		$PAGE_NAVS = paginate($MAX, $TOTAL_RECORDSET);
		$CURRENT_PAGE_NO=$page;
	}
	if($PAGE_NAVS == '') $PAGE_NAVS = '';
	$totalAll='0';		
	   
	$db->query($query2);	
	if ($db->num_rows())
	{	
		$loop=0;
		
		while($row=$db->fetch_array())
		{
			$srNo = $page*$MAX- $MAX + $loop +1;
			($loop%2 == 0)? $css ='altClr':$css='altClrSecond';
	
	
       
       $id                    =   $row['id'];
      
       $product_name            =   $row['product_name'];
       $product_weight          =   $row['product_weight'];
	   $product_description     =   $row['description'];
	   $product_code            = $row['code'];
     
      
		  $PAGE_LIST.="<tr id=\"ep_$id\">
                      <td>$srNo</td>
                      <td>$product_name</td>
                      <td>$product_weight</td>  
					  <!-- <td>$product_description</td> -->   
					  
					  <td>$product_code</td>             
                                
                       <td><a href=\"$SITE_URL/edit_product.php?id=$id\"><img src=\"$SITE_URL/images/icon_edit.png\" align='top'  align='top'></a> &nbsp;<input type=\"image\" src=\"$SITE_URL/images/icon_close.png\" style='vertical-align:top;' onclick=\"remove_product($id);\" /> </td>                      
                    </tr>";	
		
            $loop++;
	
        }
        
      }
  	else
		{
			$PAGE_LIST="<tr><td colspan='5' align='center'>---- E M P T Y ----</td></tr>";	
		}
		
}


?>