<?php session_start(); ?>
<?php
error_reporting (E_ALL ^ E_NOTICE); 
include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions_client.library.php");
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
	  $update ="update customer set lastlogin='".mktime()."' where username='$username'";
	  $db->query($update);
    setLogRecord('login');
	  
		header ("Location: ./index.php"); 		
		exit;		
	}
		else
	{	
		$_SESSION['sess_msg'] = "Authentication failed!";
		$_SESSION['sess_class']='err';
		header ("Location: ./login.php"); 		
		exit;		
	
}

}

 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR_CLIENT/login.html");
 //$RIGHT_BAR		    = ReadTemplate("$TEMPLATE_DIR/common/rightbar.html");
 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/topbar_login.html");
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();


/*function Login($db)
	{$_SESSION['MEM_ID']=1;
	$_SESSION['MEM_TYPE']=1;
	$_SESSION['MEM_USERNAME']=$_POST['username']!=''?$_POST['username']:"GavinMace";
	header("Location: index.php"); 
  
 }*/ 
 
function authenticateUser($password, $username, $db)
{	 
	 $query="select * from customer where username = '$username' "; 
	$db->query($query);
	if ($db->num_rows())
	{	
		$row = $db->fetch_array();
		$v_password = $row['password']; 
		if (strcmp ( md5(ltrim($password,'0')), $v_password ) == 0 ) // Binary Comparision for Case Sensitivity
		{	
      
      $_SESSION["SESS_CLIENT_ID"]     = $row['id'];
      $_SESSION["SESS_CLIENT_NAME"]   = $row['username'];
     
       

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