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
  	update($db,$db1);
  	header ("Location: ./view_category.php"); 		
  }


 
  getData($db,$db1);
  
 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html");  
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/edit_category.html"); 
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function getData($db,$db1)
{
        global $id,$category_name,$pin,$email,$selectedCOG,$selectedHire,$selectedBLL,$selectedLKT;
        
         
        $id = $_REQUEST['id'];
        $select = "select * from ward_category  where id='$id'";
        $db->query($select);
        
        if($db->num_rows() > 0)
        {   
             $rec = $db->fetch_assoc();
             $id                  =   $rec['id'];
             $category_name       =   $rec['category_name'];
			 $type				  =   $rec['type'];
			 $template            =   $rec['template'];
			 $email               =   $rec['email'];
			 $pin                 =   $rec['pin'];
        	
       if($type=="COG")
	   {
	   $selectedCOG="selected";
	   }
	    if($type=="Hire")
	   {
	   $selectedHire="selected";
	   }
	 
       if($template=="LaundryKingTas")
	   {
	   $selectedLKT="selected";
	   }
	   
        }
        
        
}

function update($db,$db1)
{      
       $category_name          =   $_POST['category_name'];
       $pin                    =   $_POST['pin']; 
	   $type			    	=$_POST['type']; 
	   $template          =$_POST['template'];
	   $email           =$_POST['email'];    
          
      $select ="select id from ward_category where category_name='$category_name' and id!='".$_REQUEST['id']."'";
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
         
        $query="update ward_category set 
				pin='$pin',
                category_name='$category_name' ,
				type='$type',
				template='$template',
				email='$email'             
				where id='".$_REQUEST['id']."'";
                            
          if($db->query($query)) 
          {
         
           $slDesc       =   "<b>".$title."</b> -Category updated Successfully.";   
           $_SESSION['sess_msg'] = $slDesc;
           $_SESSION['sess_class']='notice';      
           
          }
		}  
    
} 
  
?>