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
$WARD_LIST=getWardlist($db,$id='');
 
if(isset($_POST['save']))
{ 
  add($db,$db1,$db2);
   header('Location: ./view_orders.php');
              exit; 
}  



 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html"); 
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/create_order.html");
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function add($db,$db1,$db2)
{      
 global  $SITE_URL,$MEMBER_ID,$PROMPT;
		    
       $date_ordered            =   $_POST['date_ordered'];
	   $date_parts=explode("/", $date_ordered);
       $day = $date_parts[0];
       $month = $date_parts[1];
       $year = $date_parts[2];
       $date_ordered=mktime(0,0,0,$month,$day,$year);
	   
	   $date_filled          =   $_POST['date_filled'];
	   $date_parts1=explode("/", $date_filled);
       $day1 = $date_parts1[0];
       $month1 = $date_parts1[1];
       $year1 = $date_parts1[2];
       $date_filled=mktime(0,0,0,$month1,$day1,$year1);
	   
       $ward_name           =   $_POST['ward_name'];
       $ordering_person     =   $_POST['ordering_person'];   
       $email               =   $_POST['email']; 
	   $total_weight        =   $_POST['total_weight'];
	   $item_ordered        =   $_POST['items_ordered'];
	   $status        =   $_POST['status'];
      
        $query="insert into orders set               
                ordered_date='$date_ordered',
                date_filled='$date_filled',
                ward_id='$ward_name',
				ordering_person='$ordering_person',
                email='$email',
                total_weight='$total_weight',
                item_ordered='$item_ordered',
				status='$status',
                createtime='".mktime()."'               
              "; 
                //$db->query($query);                        
          if ($db->query($query)) 
          {			   

              $lastid= $db->insert_id();
          
              $select="select id from ward_products where ward_id='$ward_name'";
			  $db->query($select);
			  $total_products =$db->num_rows();
			
			  for($i=1;$i<=$total_products;$i++){
			  $sql="insert into order_products set
			  				order_id='$lastid',
							product_id='".$_POST['product_id'.$i]."',
							product_name='".$_POST['product_name'.$i]."',
							product_weight='".$_POST['product_weight'.$i]."',
							product_quantity='".$_POST['product_quantity'.$i]."',
							createtime='".mktime()."'";
							$db1->query($sql);
			  }
				
               $slDesc       =   "Order has been added Successfully.";   
              $_SESSION['sess_msg'] = $slDesc;
               $_SESSION['sess_class']='notice';               
            
             
          }
    
} 



?>