<?php session_start(); ?>
<?php
 
include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions_client.library.php");
//include("$LIB_DIR/include.inc.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
 
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

$db3=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db3->open() or die($db3->error());

$MEMBER_NAME=$_SESSION["SESS_CLIENT_NAME"]; 
checkClientLogin();

display_message();

$Supplier_List=Get_Supplier_list($db,$id='');
$REGION_CUST=Invoice_Region($db);
$ACCMANAger_CUST=Invoice_AccountManager($db);


 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/topbar.html");
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR_CLIENT/invoices.html");  
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function Invoice_Region($db)
{
    $select ="SELECT R.name,R.id from invoice as I LEFT JOIN customer as C ON C.id=I.cust_id
                  LEFT JOIN region as R ON R.id=C.region WHERE C.region!=0 GROUP BY C.region";
   $db->query($select);
   if($db->num_rows())
   {
       $REGION_CUST ="";
      while($row=$db->fetch_assoc())
      {
          $REGION_CUST .="<option value=\"$row[id]\">$row[name]</option>";
      }
   }               
   return $REGION_CUST;               
}

function Invoice_AccountManager($db)
{
    $select ="SELECT E.emp_login_id,E.first_name,E.last_name from invoice as I LEFT JOIN customer as C ON C.id=I.cust_id
                  LEFT JOIN employee as E ON E.emp_login_id=C.acc_manager  GROUP BY C.acc_manager";
   $db->query($select);
   if($db->num_rows())
   {
       $ACCMANAger_CUST ="";
      while($row=$db->fetch_assoc())
      {
          
          $ACCMANAger_CUST .="<option value=\"$row[emp_login_id]\">$row[first_name] $row[last_name]</option>";
      }
   }               
   return $ACCMANAger_CUST;               
}
?>                      