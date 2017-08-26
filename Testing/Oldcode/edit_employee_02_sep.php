<?php session_start(); ?>
<?php
error_reporting (E_ALL ^ E_NOTICE); 
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

 
if($_POST['save'])
  {
  	update($db);
  	header ("Location: ./view_employee.php"); 		
  }


 
  getData($db);
  
 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html");  
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/edit_employee.html"); 
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function getData($db)
{
        global $id,$EMP_REGION,$REGION_LIST,$first_name,$last_name,$username,$password,$email,$status,$DIR_DISPLAY_USER_FILE,$SITE_URL,$status_active,$status_inactive,$emp_type,$report_to,$oldpassword;
        
         
        $id = $_REQUEST['id'];
	   $select = "select * from  login_info  where id='$id'"; 
        $db->query($select);
        
        if($db->num_rows() > 0)
        {   
             $rec = $db->fetch_assoc();
             $id                  =   $rec['id'];
           
             $first_name          =   $rec['first_name'];
             $last_name           =   $rec['last_name'];            
             $username            =   $rec['username'];
			 $password            =   $rec['password'];
			 $oldpassword         =   $rec['password'];
			 $email               =   $rec['email']; 
             $status              =   $rec['status'];
           
            
            
                              
             if($status == 'A')
             $status_active = 'selected';
             else
             $status_inactive ='selected';
        
        
        }
        
        
}

function update($db)
{      
 global $DIR_UPLOAD_USER_FILE, $SITE_URL,$MEMBER_ID;
		    
       $id                    =   $_REQUEST['id'];         
      
       $first_name            =   $_POST['first_name'];
       $last_name             =   $_POST['last_name']; 
	    $username			 =   $_POST['user_name'];
	   $email                 =   $_POST['email'];
	   $password              =   $_POST['password'];
	   $oldpassword            = $_POST['oldpassword'];
       $status                =   $_POST['status'];
      
    
    if($password==$oldpassword)
	{
	$recpassword=$password;
	}
	else
	{
	$recpassword=md5($password);
	}
         
		    $query="update login_info set 
				first_name='$first_name',
                last_name='$last_name',
                username='$username',
				password='$recpassword',
                email='$email',
                createtime='".mktime()."',
                modifytime='".mktime()."',
                status='$status'              
				where id='$id'";
                            
          if($db->query($query)) 
          {
         
           $slDesc       =   "employee updated Successfully.";   
           $_SESSION['sess_msg'] = $slDesc;
           $_SESSION['sess_class']='notice';      
           
          }
		
    
} 
  
?>