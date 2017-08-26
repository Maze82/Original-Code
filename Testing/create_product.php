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
  add($db,$db1,$db2);
  
}  



 $TOPBAR		  = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html"); 
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/create_product.html");
 $BOTTOMBAR		  = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		  = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function add($db,$db1,$db2)
{      
 global  $SITE_URL,$MEMBER_ID,$PROMPT;
		    
       $product_name          =   $_POST['product_name'];
       $product_weight        =   $_POST['product_weight'];
	   $description           =   $_POST['description'];
	   $code                  =   $_POST['code'];
	   $type  				  =   $_POST['type'];
      
          
      $select ="select id from products where product_name='$product_name'";
      $db->query($select);
      if($db->num_rows()>0)
      {
          $_SESSION['sess_msg'] = "Product Already exist.";
          $_SESSION['sess_class']='notice';
          header('Location: ./create_product.php');
          exit;          
      }
      else
      { 
         
        $query="insert into products set               
                product_name='$product_name',
                product_weight='$product_weight',  
				description=' $description',
				code='$code',
				type='$type',           
                createtime='".mktime()."',
                modifytime='".mktime()."'";
                
                                     
          if ($db->query($query)) 
          {
              
             if(isset($_POST['save']))
            { 
               $slDesc       =   "Product has been added Successfully.";   
               $_SESSION['sess_msg'] = $slDesc;
               $_SESSION['sess_class']='notice';               
              header('Location: ./view_products.php');
              exit;
            }  
          }
    }
} 



?>