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
  header("location:./view_ward.php");
  exit;
}  
$CATE_LIST=getWardcategory($db,$id='');


 $TOPBAR		  = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html"); 
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/create_ward.html");
 $BOTTOMBAR		  = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		  = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function add($db,$db1,$db2)
{      
		 $tblCount           =   $_POST['lastid'];
		 
		 $sql="insert into wards set
		 				ward_category='".$_POST['ward_category']."',
		 				ward_name='".$_POST['ward_name']."',
						ordering_person='".$_POST['ordering_person']."',
						email='".$_POST['email']."',
						createtime='".mktime()."'";
						$db->query($sql);
						$inserted_id  =  $db->insert_id();
		 
 				 for($i=1;$i<=$tblCount;$i++){
                  $product_weight=$_POST["product_weight$i"];
                  $product_name= $_POST["product_name$i"];
				  $product_id= $_POST["product_id$i"];
                 
                  if($product_name!='')
                   {
                    $insert="insert into ward_products set 
							ward_id='$inserted_id',
                           product_name='$product_name',
						   product_id='$product_id',
                           product_weight='$product_weight',
						   createtime='".mktime()."'
                           "; 
                     $db1->query($insert); 
                     $slDesc       =   "Ward has been added Successfully.";   
              		 $_SESSION['sess_msg'] = $slDesc;
              		 $_SESSION['sess_class']='notice';               
                   }
                   
                   
				 }    
            
} 



?>