<?php session_start(); ?>
<?php 

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
  add($db,$db1,$db2);
  
}  



 $TOPBAR		  = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html"); 
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/create_category.html");
 $BOTTOMBAR		  = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		  = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function add($db,$db1,$db2)
{      
 global  $SITE_URL;
		    
       $category_name          =   $_POST['category_name'];
	   $type			    	=$_POST['type'];
       $pin                    =   $_POST['pin'];
	   $template=$_POST['template'];
	   $email=$_POST['email'];
      
          
      $select ="select id from ward_category where category_name='$category_name'";
      $db->query($select);
      if($db->num_rows()>0)
      {
          $_SESSION['sess_msg'] = "Category Already exist.";
          $_SESSION['sess_class']='notice';
          header('Location: ./create_category.php');
          exit;          
      }
      else
      { 
         
        $query="insert into ward_category set 
		        pin='$pin',              
                category_name='$category_name',
				type='$type',
				template='$template',
				email='$email',
                createtime='".mktime()."'";
                
                                     
          if ($db->query($query)) 
          {
              
             if(isset($_POST['save']))
            { 
               $slDesc       =   "Category has been added Successfully.";   
               $_SESSION['sess_msg'] = $slDesc;
               $_SESSION['sess_class']='notice';               
              header('Location: ./view_category.php');
              exit;
            }  
          }
    }
} 



?>