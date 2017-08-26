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

$P_Sales_Rep=Get_Project_salesRep($db);
$Asigneees=getAssigneeslist($db,$asignees);
$Region_List=getRegionListUSer($db,$db1,$id='');
$ACC_manager_List=Account_Manager_Dropdown($db);

checkClientLogin();
display_message();


$TOPBAR			    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/topbar.html");
$PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR_CLIENT/project.html");
$RIGHT_BAR		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/rightbar.html");  
$BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/bottombar.html");
$SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/sub_template.html");
$TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();


 function Get_Project_salesRep($db)
 {
    $select ="SELECT E.emp_login_id,E.first_name,E.last_name FROM employee as E
                LEFT JOIN project as P ON P.sales_rep=E.emp_login_id
                  WHERE P.sales_rep!='' GROUP BY P.sales_rep";
    $db->query($select);
    if($db->num_rows())
    {
        while($row=$db->fetch_assoc())
        {
            $P_Sales_Rep .="<option value=\"$row[emp_login_id]\">$row[first_name] $row[last_name]</option>";
        }
    } 
    return  $P_Sales_Rep;            
 }
 function Account_Manager_Dropdown($db)
{
    $select ="SELECT E.first_name,E.last_name,C.acc_manager FROM customer AS C 
                LEFT JOIN  employee AS E ON E.emp_login_id=C.acc_manager 
                  LEFT JOIN  project AS P ON P.cust_id=C.id  
                    WHERE C.acc_manager!='' GROUP BY C.acc_manager";
    $db->query($select);
    if($db->num_rows())
    {
        $ACC_manager_List ="";
        while($row=$db->fetch_assoc())
        {
            $ACC_manager_List .="<option value=\"$row[acc_manager]\">$row[first_name] $row[last_name]</option>";
        }
    }
    return $ACC_manager_List;
}


?> 