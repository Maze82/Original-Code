<?php session_start(); 

error_reporting (0); 
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
  
$id = $_GET['id'];
$number =$_GET['nCount_lastid'];

$query="select * from products where id=$id";
  $db->query($query);
  if($db->num_rows())
  {
          $row = $db->fetch_array();
          $id   = $row['id']; 
          $product_name   = $row['product_name'];
          $product_weight   = $row['product_weight'];
          
  }
  
 
 echo $PRODUCT_DISPLAY="<tr id=\"prods\">
                                <td width=\"30%\" align=\"right\"></td>
                                <td><input  name=\"product_name$number\" type=\"text\" class=\"input_txt\" value=\"$product_name\" /><input  name=\"product_weight$number\" type=\"hidden\" class=\"input_txt\" value=\"$product_weight\" /><input type=\"hidden\" id=\"pid_$number\"  name=\"pid_$number\" value=\"\"><input type=\"hidden\" name=\"product_id$number\" value=\"$id\" /><a href=\"javascript:void(0);\" onclick=\"delete_qproducts($number)\"><img src=\"$SITE_URL/images/minus_green.png\"  /></a></td>
                            </tr> 
                              ";
                           
      
?>                     