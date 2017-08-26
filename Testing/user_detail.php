<?php session_start(); 
error_reporting (E_ALL ^ E_NOTICE); 
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

$db3=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db3->open() or die($db3->error());

$db4=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db4->open() or die($db4->error());

$db5=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db5->open() or die($db5->error());
$db6=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db6->open() or die($db6->error());

$MEMBER_NAME = $_SESSION["SESS_MEMBER_NAME"]; 
$MEMBER_TYPE = $_SESSION["SESS_MEMBER_TYPE"]; 
$MEMBER_ID   = $_SESSION["SESS_MEMBER_ID"];
checkLogin();
getData($db,$db1);



 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html");
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/user_detail.html");
 $RIGHT_BAR		    = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function getData($db,$db1)
{
  global $SITE_URL,$DIR_DISPLAY_USER_FILE;
  global $id,$first_name,$last_name,$name,$username,$password,$office_phone,$phone,$fax,$department,$report_to_name,$emp_type_name,$address,$state,$country,$city,$postal_code,$email,$status,$lastlogin,$picture_file,$PASS,$USER,$emp_id,$tfn_no;
          $id =$_REQUEST['id'];
    $query="select * from users where id='$id'"; 
         $db->query($query);	 
      $row=$db->fetch_array();
      
        $id                    =   $row['id'];     
       $tfn_no                =   $row['tfn_no'];
       $first_name            =   $row['first_name'];
       $last_name             =   $row['last_name'];
       $name                  =   $first_name." ".$last_name;
       $username              =   $row['username'];
       $picture               =   $row['picture'];
       $office_phone          =   $row['office_phone'];
       $phone                 =   $row['phone'];
       $fax                   =   $row['fax'];  
       $address               =   $row['address'];
       $state                 =   $row['state'];
       $country               =   $row['country'];
       $city                  =   $row['city']; 
       $postal_code           =   $row['postal_code'];
       $email                 =   $row['email'];
       $status                =   $row['status'];
      
       $filenname_path        =   $DIR_DISPLAY_USER_FILE."/".$picture;
     
       if($picture!='')
       { $picture_file="<img src=\"$SITE_URL/image.php/$picture?width=125&amp;height=121&amp;cropratio=125:121&amp;image=$filenname_path\" width=\"125\" height=\"121\" />"; }
       else{
        $picture_file="<img src=\"images/img_profile.gif\" alt=\"\" title=\"\" width=\"125\" height=\"121\" />";
       }

       if($status=='A')
       {
        $status="Active";
       }
			else{$status="Inactive";}
			
			
 }





?>