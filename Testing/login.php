<?php session_start(); ?>
<?php
error_reporting (E_ALL ^ E_NOTICE); 
include("config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php");
//include("$LIB_DIR/include.inc.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
	
 display_message();
if(isset($_POST['password']))
{
   
     $password = $_POST['password'];
     $username = $_POST['username'];
}
 
if(isset($password) && isset($username))
{	
	$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
	
	$db->open() or die($db->error());
	
	if (authenticateUser($password, $username, $db))
	{	
	$update ="update login_info set lastlogin='".mktime()."' where username='$username'";
	  $db->query($update);
 setLogRecord('login');
	  
		header ("Location: ./index.php"); 		
		exit;		
	}
		else
	{	
		$_SESSION['sess_msg'] = "<div align=\"center\" style=\"border-color: #ff0000; border-style: solid; border-width: 0.1em;margin: 0.5em 0;padding: 10px 10px 10px 36px;\">Authentication failed!</div>";
		$_SESSION['sess_class']='err';
		header ("Location: ./login.php"); 		
		exit;		
	
}

}
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/login.html");
 //$RIGHT_BAR		    = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");
// $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR/common/topbar_login.html");
 //$BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 //$SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR/common/template_sign.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $PAGE_CONTENTS;
flush();


 
function authenticateUser($password, $username, $db)
{	 
	 $query="select * from login_info  where username = '$username' and status='A'";    
	$db->query($query);
	if ($db->num_rows())
	{		
		$row = $db->fetch_array();
		$v_password = $row['password'];
		if (strcmp ( md5($password), $v_password ) == 0 ) // Binary Comparision for Case Sensitivity
		{	
      
      
      $_SESSION["SESS_MEMBER_ID"]     = $row['id'];
      $_SESSION["SESS_MEMBER_NAME"]   = $row['username'];
      $_SESSION["SESS_MEMBER_TYPE"]   = $row['type'];
      $_SESSION["SESS_MEMBER_REGION"] = $row['region']; 
       

			//Authenticated
			return true;
		}
		else
		{
			//Not Authenticated
			return false;
		}
	}
	else
	{	
  //echo "Not Authenticated";
		return false;
	}
}	

?>