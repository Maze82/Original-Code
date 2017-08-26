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
  
$wid = $_GET['val'];

 $query="select * from ward_products where ward_id=$wid";
  $db->query($query);
  if($db->num_rows())
  {		$i=1;
         while( $row = $db->fetch_array()){
			  $id   = $row['id']; 
			  $product_name   = $row['product_name'];
			  $product_id   = $row['product_id'];	
			  $product_weight=$row['product_weight'];		  
			  
			/*$products .="<tr>
                                <td align=right>$product_name<input type=\"hidden\" name=\"product_id$i\" value=\"$product_id\" /><input type=\"hidden\" name=\"product_name$i\" value=\"$product_name\" /><input type=\"hidden\" name=\"product_weight$i\" value=\"$product_weight\" /></td>
                                <td><input name=\"product_quantity$i\" type=\"text\" class=\"input_txt number\" title='Please enter in number'/></td>
                            </tr>";*/
		$products .="<tr>
                                <td align=right>$product_name<input type=\"hidden\" name=\"product_id$i\" value=\"$product_id\" /><input type=\"hidden\" name=\"product_name$i\" value=\"$product_name\" /><input type=\"hidden\" name=\"product_weight$i\" value=\"$product_weight\" /></td>
                                <td><input name=\"product_quantity$i\" id=\"p_qty_$i\" onchange=\"return getquantity($product_id,'p_qty_$i');\" type=\"text\" class=\"input_txt number\" title='Please enter in number'/></td>
								  <td><input type=\"hidden\" name=\"total_weight_count\" id=\"total_weight_count_$i\" style=\"width:40px\"  value=\"$product_weight\" /></td>
                            </tr>";					
			$i++;  
		}  
		$products .="<input type=\"hidden\" name=\"total_prd_count\" id=\"total_prd_count\" style=\"width:40px\"  value=\"$i\" />";
          
  }
  
 $query="select * from wards where id=$wid";
  $db->query($query);
  if($db->num_rows())
  {
            $row = $db->fetch_array();
			$id   = $row['id']; 
			$ward_name   = $row['ward_name'];			  
			$ordering_person   = $row['ordering_person'];	
			$email   = $row['email'];
			
        
  }
    $arr_ward['ordering_person']  = $ordering_person;
		  $arr_ward['email']		= $email;
		 
	      $arr_ward['products']		= $products;
		 echo json_encode($arr_ward);
                           
      
?>                     