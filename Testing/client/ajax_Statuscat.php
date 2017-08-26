<?php session_start(); ?>
<?php

include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions_client.library.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
checkClientLogin();

$MEMBER_NAME=$_SESSION["SESS_CLIENT_NAME"];

global $SALES_QUOTES,$SITE_URL,$MEMBER_NAME,$section_perm;
$SALES_QUOTES .="<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"table table-bordered responsive dataTable view_profile\">
                          <thead><tr>
                            	<th width=\"78\">Date</th>
                                <th width=\"58\">Job #</th>
                                <th width=\"120\">Project Title</th>
                                <th width=\"190\">Customer</th>
                                <th width=\"85\">Price</th>
                                <th width=\"100\">Sales Rep.</th>                                
                                <th width=\"25\">Action</th>                              
                            </tr></thead>";
$type = $_REQUEST['type'];
if($type)
$select="select * from sales_quote where quote_status='$type' and cust_id='".$_SESSION["SESS_CLIENT_ID"]."'"; 
else
$select="select * from sales_quote where quote_status in('A','S') and cust_id='".$_SESSION["SESS_CLIENT_ID"]."'";

 $setting_permission=Get_Permission($db,'4',$_SESSION['SESS_MEMBER_ID']);
if($setting_permission==1 )
{   
   $select .=" and sales_rep='".$_SESSION["SESS_MEMBER_ID"]."'"; 
}

$select .=" ORDER BY quote_date desc";
 //echo  $select;
  $db->query($select);
  if($db->num_rows())
  {
    while($row=$db->fetch_assoc())
    {
      $row = array_map('stripslashes',$row);
	  $id=  $row['id'];
      $custid =  $row['cust_id'];
      
      $query="select business_name from customer where id='$custid'";
      $db1->query($query);
      $rec1 =$db1->fetch_assoc();
      $rec1 = array_map('stripslashes',$rec1);
      
       $select1="select sales_quote_id,total,SUM(total) as total1  from sales_quote_detail where sales_quote_id=$id GROUP BY sales_quote_id ";
      $db1->query($select1);
      if($db1->num_rows())
      {
       while($row1=$db1->fetch_assoc())
       {
		   $row1 = array_map('stripslashes',$row1);
          $select1="select username from login_info where id='$row[sales_rep]'";
          $db1->query($select1);
          if($db1->num_rows())
          {                                       
              $rec=$db1->fetch_assoc();
			  $rec = array_map('stripslashes',$rec);
            
          }
          
          
        
          
         $SALES_QUOTES .="<tr id=\"quote_$id\">
                         <td>".date('m/d/Y',$row['quote_date'])."</td>
                                <td>".sprintf("%06d",$row['quote_no'])."</td>
                                 <td>$row[project_title]</td>
                                <td>$rec1[business_name]</td>
                                <td align='right' style='padding-right:20px;'>$ ".number_format($row['total'],2)."</td>
                                <td>$rec[username]</td>
                                <td><a href=\"$SITE_URL/epdf/quotePDF.php?id=$row[id]\" title='PDF'><img src=\"$SITE_URL/images/icon_pdf.png\" width='20'/></a>&nbsp; <a href=\"$SITE_URL_CLIENT/edit_quote.php?id=$id\"><img src=\"$SITE_URL/images/icon_search.png\" title='View' /></a></td>
                               
                            </tr>";
       }
     }
    } 
    $SALES_QUOTES .="</table>";   
  } 

 else
 {
  $SALES_QUOTES="<div style=\"margin-left:250px;margin-top:100px;margin-bottom:100px;\"><strong> Sorry , No Data Available !</strong></div>";
 }
 
echo $SALES_QUOTES;
 

?>