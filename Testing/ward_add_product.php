<?php session_start(); 

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
$product_display=product_list($db);
display_message(); 


 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html");
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/ward_add_product.html");

ReplaceContent(Array("PAGE_CONTENTS"));

print $PAGE_CONTENTS;
flush();


function product_list($db)
{
	$select="SELECT * FROM products WHERE 1";
	$db->query($select);
	if($db->num_rows())
	{
		
		$product_display ="<table cellpadding=\"10\" cellspacing=\"5\" width=\"100%\" class=\"product_table dataTable \">
                             	<thead><tr>
                            	<th class=\"green_sorting_heading\" style=\"width:5%\">#</th>
                                <th class=\"green_sorting_heading\" style=\"width:20%\">Product Name</th>
                                <th class=\"green_sorting_heading\" style=\"width:20%\">Product Weight</th>                                
                                <td   style=\"background:#91B95B;width:10%\"></td>
                                </tr></thead><tbody>";
		while($row=$db->fetch_assoc())
		{
			$product_display .="<tr>
									<td>$row[id]</td>
									<td>$row[product_name]</td>
									<td>$row[product_weight]</td>
									<td width=\"30\"><input type=\"image\" src=\"$SITE_URL/images/btn_add_product.png\" onclick=\"getProduct($row[id])\"></td>
								</tr>";
		}
		return $product_display;	
		
	}
}



?>