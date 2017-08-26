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
  	header ("Location: ./view_orders.php"); 		
  }


 
  getData($db,$db1);
  
 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html");  
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/edit_order.html"); 
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function getData($db,$db1)
{
        global $id,$ordered_date,$date_filled,$ward_id,$ordering_person,$email,$total_weight,$item_ordered,$status_unfill,$status_fill,$products,$status,$WARD_LIST,$current_date,$item_order,$delivery_date,$counter,$total_weight,$filled_ordered,$filled_weight;
        
        $current_date=date('d/m/Y h:i a'); 
        $id = $_REQUEST['id'];
        $select = "select * from orders where id='$id'";
        $db->query($select);
        
        if($db->num_rows() > 0)
        {   
             $rec = $db->fetch_assoc();
             $id                  =   $rec['id'];
			 
			 $date_order=$rec['ordered_date'];
             $ordered_date        =   date('d/m/Y h:i a',$rec['ordered_date']);
			
			 if($rec['date_filled']!=0){
             $date_filled         =    date('d/m/Y h:i a',$rec['date_filled']);
			 }
			 else
			 {
			 	$date_filled=$current_date;
			 }
			 
			  if($rec['delivery_date']!=0){
             $delivery_date         =    date('d/m/Y',$rec['delivery_date']);
			 }
			 else
			 {
			 	$delivery_date='';
			 }
			 $ward_id=getWradListName($db1,$rec['ward_id']);
             //$WARD_LIST             =   getWardlist($db1,$rec['ward_id']);            
             $ordering_person     =   $rec['ordering_person'];
             $email                =   $rec['email'];
             $total_weight        =   $rec['total_weight'];
             $item_ordered               =   $rec['item_ordered'];
		    $filled_ordered               =   $rec['filled_order'];
			$filled_weight               =   $rec['filled_weight'];
             $status                 =   $rec['status'];
                                      
             if($status == 'UNFILLED')
             $status_unfill = 'selected';
             else
             $status_fill ='selected';
        
 $select1="select OP.*,O.item_ordered from order_products as OP left join orders as O on O.id=OP.order_id 
		     where OP.order_id='".$_REQUEST['id']."'"; 
				
				
	 /*echo   $select1="select product_name,product_id,product_quantity,dispatched_quantity from order_products where order_id='".$_REQUEST['id']."'"; exit;*/
	  
	 /* echo   $select1="select product_name,product_id,product_quantity,count(product_quantity)as item orders ,dispatched_quantity from order_products where order_id='".$_REQUEST['id']."'";exit;*/
	 $total_weight_val=0;
	  $db->query($select1);
	  if($db->num_rows())
	  {
	  		$products .="<tr>
                                <th align=\"right\">Product Name</th>
                                <th align=\"right\">Ordered Quantity</th>
								<th align=\"right\">Dispatched Quantity</th>
                            </tr>";
							
				$i=1;			
			while($row=$db->fetch_assoc()){
			
				$product_name=$row['product_name'];
				$product_id = $row['product_id'];
		
				$product_quantity      = $row['product_quantity'];
				$dispatched_quantity   = $row['dispatched_quantity'];
				$item_order             =$row['item_ordered'];
				$product_weight          =$row['product_weight'];
				//$total_weight1        = $product_quantity * $product_weight;
				//$total_weight_val=
				$products .="<tr>
                                <td align=\"right\">$product_name<input type=\"hidden\" name=\"pid$product_id-$i\" value=\"$product_id\" ></td>
                               <td align=\"right\"><input type=\"text\" name=\"p_qty$product_id-$i\" id=\"p_qty_$i\" style=\"width:40px\" class=\"input_txt\" value=\"$product_quantity\" onchange=\"return getquantity($product_id,'p_qty_$i');\" /></td>
								<td align=\"right\"><input type=\"text\" name=\"d_qty$product_id-$i\" id=\"d_qty_$i\" style=\"width:40px\" class=\"input_txt\" value=\"$dispatched_quantity\"  onchange=\"return getdispatchquantity($product_id,'d_qty_$i');\" /></td>
                               <td><input type=\"hidden\" name=\"total_weight_count\" id=\"total_weight_count_$i\" style=\"width:40px\"  value=\"$product_weight\" /></td>
							</tr>";
			$i++;
			}
			$products .="<input type=\"hidden\" name=\"total_prd_count\" id=\"total_prd_count\" style=\"width:40px\"  value=\"$i\" />";	
				
			//$counter=$i-1;
	   }
        
     }
       
	
	    
        
}

function update($db,$db1)
{      
 global  $SITE_URL,$MEMBER_ID;
		    
        $date_ordered            =    strtotime(str_replace('/', '-',$_POST['date_ordered']));
	  /* $date_parts=explode("/", $date_ordered);
       $day = $date_parts[0];
       $month = $date_parts[1];
       $year = $date_parts[2];
       $date_ordered=mktime(0,0,0,$month,$day,$year);*/
	   
	   $date_filled          =   strtotime(str_replace('/', '-',$_POST['date_filled']));
	  /* $date_parts1=explode("/", $date_filled);
       $day = $date_parts1[0];
       $month = $date_parts1[1];
       $year = $date_parts1[2];
       $date_filled=mktime(0,0,0,$month,$day,$year);*/
	   
       $ward_name           =   $_POST['ward_name'];
       $ordering_person     =   $_POST['ordering_person'];   
       $email               =   $_POST['email']; 
	   $total_weight        =   $_POST['total_weight'];
	   $item_ordered        =   $_POST['items_ordered'];
	    $filled_weight        =   $_POST['filled_weight'];
	   $filled_ordered        =   $_POST['filled_ordered'];
	   $status              =   $_POST['status'];
	   $delivery_date       =   strtotime(str_replace('/', '-',$_POST['delivery_date']));
		
		
      
       $query="update orders set               
                ordered_date='$date_ordered',
                date_filled='$date_filled',
                ordering_person='$ordering_person',
                email='$email',
                total_weight='$total_weight',
                item_ordered='$item_ordered',
				filled_order='$filled_ordered',
				filled_weight='$filled_weight',
				delivery_date='$delivery_date',
				status='$status'
               where id='".$_REQUEST['id']."'               
              ";
                //$db->query($query);                        
          if ($db->query($query)) 
          {
         	
			 //$select = "select ward_id from orders where id='".$_REQUEST['id']."'";
        	// $db->query($select);
			 //$row=$db->fetch_assoc();				 
			
		 	
			
				 $select1="select product_id,product_weight,total_product_weight from order_products where order_id='".$_REQUEST['id']."'";
				 $db->query($select1);
				 $i=1;
				 while($row1=$db->fetch_assoc()){
				 $product=$row1['product_id'];
			     $product_weight=$row1['product_weight'];
				  $product_quantity=$_POST['p_qty'.$product.'-'.$i];
				  $dispatched_quantity =$_POST['d_qty'.$product.'-'.$i];
				 $tot_product_weight=$product_quantity * $product_weight;
				
				
				
			
			 /*	 $update="update order_products set  product_quantity='".$_POST['p_qty'.$product.'-'.$i]."', dispatched_quantity ='".$_POST['d_qty'.$product.'-'.$i]."'
						 	where product_id='".$_POST['pid'.$product.'-'.$i]."'"; */
							
							
 $update="update order_products set  product_quantity='$product_quantity',dispatched_quantity ='$dispatched_quantity ',total_product_weight='$tot_product_weight' where order_id='".$_REQUEST['id']."' and  product_id='".$_POST['pid'.$product.'-'.$i]."'";
							
							$db1->query($update);
				
				$i++;
			 	 }
				
					
			
			
			
           $slDesc       =   " -Order Detail updated Successfully.";   
           $_SESSION['sess_msg'] = $slDesc;
           $_SESSION['sess_class']='notice';      
           
          }
    
	
	
	
} 
  
?>