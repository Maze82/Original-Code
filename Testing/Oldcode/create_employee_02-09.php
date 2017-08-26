<?php session_start(); ?>
<?php echo $PROMPT;
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
    
display_message(); 
$MEMBER_NAME = $_SESSION["SESS_MEMBER_NAME"]; 
$MEMBER_TYPE = $_SESSION["SESS_MEMBER_TYPE"]; 
$MEMBER_ID   = $_SESSION["SESS_MEMBER_ID"];

checkLogin();


if(isset($_POST['save']))
{ 
  add($db);
  
}  



 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html"); 
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/create_employee.html");
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function add($db)
{      
 global $DIR_UPLOAD_USER_FILE, $SITE_URL,$MEMBER_ID,$PROMPT;
		    
       $first_name            =   $_POST['first_name'];
       $last_name             =   $_POST['last_name'];
       $username			 =$_POST['user_name'];
       $email                 =   $_POST['email'];
	   $password              =   $_POST['password'];
       $status                =   $_POST['status'];
     
        
         
          
      $select ="select id from login_info where username='$username'";
      $db->query($select);
      if($db->num_rows()>0)
      {
          $_SESSION['sess_msg'] = "Emolpyee Already exist.";
          $_SESSION['sess_class']='notice';
          header('Location: ./create_employee.php');
          exit;          
      }
      else
      { 
         
        
       
   
        $query="insert into login_info set               
                first_name='$first_name',
                last_name='$last_name',
                username='$username',
				password=md5($password),
                email='$email',
                createtime='".mktime()."',
                modifytime='".mktime()."',
                status='$status'"; 
                //$db->query($query);                        
          if ($db->query($query)) 
          {
              
          
             if(isset($_POST['save']))
            { 
               $slDesc       =   "Employee has been added Successfully.";   
               $_SESSION['sess_msg'] = $slDesc;
               $_SESSION['sess_class']='notice';               
              header('Location: ./view_employee.php');
              exit;
            }  
          }
    }
} 



?>