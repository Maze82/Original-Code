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
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

$MEMBER_ID  =$_SESSION["SESS_CLIENT_ID"];
$MEMBER_NAME=$_SESSION["SESS_CLIENT_NAME"]; 
checkClientLogin();

$type = $_REQUEST['type'];
if($type=='U' || $type=='P')
{

$INVOICE_LIST .=" <p align=\"right\"><a href=\"ExportInvoice.php?type=$type&bus_name=$_REQUEST[bus_name]&supplier=$_REQUEST[supplier]&region=$_REQUEST[region]&acc_manager=$_REQUEST[acc_manager]&startdate=$_REQUEST[startdate]&enddate=$_REQUEST[enddate]\">Export</a><a href=\"ExportInvoice.php?type=$type&bus_name=$_REQUEST[bus_name]&supplier=$_REQUEST[supplier]&region=$_REQUEST[region]&acc_manager=$_REQUEST[acc_manager]&startdate=$_REQUEST[startdate]&enddate=$_REQUEST[enddate]\"><img src=\"$SITE_URL/images/images.jpeg\" height=\"35\" /></a></p><table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"table table-bordered responsive dataTable view_profile\">
                        <thead><tr>
                            	<th width=\"88\">Date Issued</th>
                                <th width=\"90\">Job Number</th>
                                <th width=\"175\">Project Title</th>
                                <th width=\"145\">Client</th>
                                <th width=\"58\">PDF</th>
                                <th width=\"48\">Status</th>
                                <th width=\"48\">Action</th>
                            </tr></thead>";
if($_REQUEST['bus_name']!='')
 {   
     $JOIN ="  LEFT JOIN customer as C ON C.id=I.cust_id";
 } 
 if($_REQUEST['supplier']!='')
 {
     $JOIN ="  LEFT JOIN customer as C ON C.id=I.cust_id";
 } 
 if($_REQUEST['region']!='')
 {
     $JOIN ="  LEFT JOIN customer as C ON C.id=I.cust_id";
 } 
 if($_REQUEST['acc_manager']!='')
 {
    $JOIN ="  LEFT JOIN customer as C ON C.id=I.cust_id";
 }
 if($_REQUEST['startdate']!='' || $_REQUEST['enddate']!='')
 {
     $JOIN ="  LEFT JOIN customer as C ON C.id=I.cust_id LEFT JOIN project as P ON P.quote_id=I.quote_id";
 }
                              

 $select="select I.* from invoice as I  $JOIN  where I.status='$type' and cust_id='$MEMBER_ID'";

 
 
 if($_REQUEST['bus_name']!='')
 {   
     $select .=" and C.business_name Like '%$_REQUEST[bus_name]%'";
 } 
 if($_REQUEST['supplier']!='')
 {
     $select .=" and I.supplier='".$_REQUEST['supplier']."'";
 } 
 if($_REQUEST['region']!='')
 {
     $select .=" and C.region='".$_REQUEST['region']."'";
 } 
 if($_REQUEST['acc_manager']!='')
 {
     $select .=" and C.acc_manager='".$_REQUEST['acc_manager']."'";
 }
 if($_REQUEST['startdate']!='')
 {  
     $select .=" and DATE_FORMAT(FROM_UNIXTIME(P.start_date), '%m/%d/%Y')  >='".$_REQUEST['startdate']."'";
 }
 if($_REQUEST['enddate']!='')
 {  
     $select .=" and DATE_FORMAT(FROM_UNIXTIME(P.start_date), '%m/%d/%Y')  <='".$_REQUEST['enddate']."'";
 }
   //echo $select  ;
$db->query($select);
  if($db->num_rows())
  {
      $total_invoices =$db->num_rows();
    while($row=$db->fetch_assoc())
    {
      $id             =  $row['id'];
      $job_number     =  $row['job_number'];
      $project_title  =  $row['project_title'];
      $business_name  =  $row['business_name'];
      $price          =  $row['price'];
      $status         =  $row['status'];
      $date           =  $row['createtime'];
      $date1          =   date('d/m/Y',$date);
      
      $total_outstanding += $price;
      if($status=='U')
         {
           $status ='Unpaid';
         }
         else
         {
          $status ='Paid';
         }
         $INVOICE_LIST .="<tr>
                                <td>$date1</td>
                                <td>$job_number</td>
                                <td>$project_title</td>
                                <td>$business_name</td>
                                <td>$price</td>
                                <td>$status</td>
                                <td><a href=\"$SITE_URL/epdf/InvoicePDF.php?id=$id\"><img width=\"20\" src=\"$SITE_URL/images/icon_pdf.png\"></a>                                
                                </td>
                                </tr>";
        }

       

    $INVOICE_LIST .="</table><hr /><p style=\"padding:18px\"><strong>Total Invoices</strong>- $total_invoices<br /><strong>Total Outstanding-</strong> ".round($total_outstanding,2)." </p>";   
  } 

 else
 {
  $INVOICE_LIST="<div style=\"margin-left:250px;margin-top:100px;margin-bottom:100px;\"><strong> Sorry , No Data Available !</strong></div>";
 }
 
echo $INVOICE_LIST;
}



function getEmployeeName($db,$e_id)
{
 global $EMP_TYPE_NAME;
 $query = "select username from login_info where id='$e_id'";
 $db->query($query);
 if($db->num_rows())
 {
 $rec = $db->fetch_assoc();
 $EMP_TYPE_NAME    = $rec['username'];
 }
 else
 {
  $EMP_TYPE_NAME ="";
 }  
 return $EMP_TYPE_NAME;  
}
?>
