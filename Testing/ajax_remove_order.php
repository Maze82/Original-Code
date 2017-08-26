<?php session_start(); ?>
<?php	
error_reporting (0); 

include("config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php"); 
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());  

     //$user_id  =  $_GET['u_id'];
     $id      =  $_GET['id']; 
	 
	 $delete1="delete from order_products where order_id='$id'";
	 $db->query( $delete1); 
	   
     $delete2="delete from orders where id=$id";
     $db->query($delete2);
   
        
?>
