<?php session_start(); 

include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions_client.library.php");


$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
 
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

$db3=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db3->open() or die($db3->error());

$db4=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db4->open() or die($db4->error());

$MEMBER_NAME=$_SESSION["SESS_CLIENT_NAME"];

checkClientLogin();
display_message();


$TOPBAR			    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/topbar.html");

get($db,$db1);

 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR_CLIENT/edit_quote.html");
 $RIGHT_BAR		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/rightbar.html");
 
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function get($db,$db1)
{
  global $SITE_URL,$i,$GST_row,$GST_VAL,$new_tr,$subtotal,$total_price,$quote_gst,$Campaign,$email,$fax,$add_product_btn,$phone,$project_title,$address2,$address1,$customer_name,$business_name,$quot_date,$quoteno,$product_name,$sales_quote_id,$description,$price,$quantity,$discount,$total,$LISTING,$product_id,$quote_status_dd,$QUOTE_STATUS,$cust_id,$ADMIN_ID,$MEMBER_NAME,$slaes_rep_disp;
  $select ="select * from sales_quote where id='".$_REQUEST['id']."'";
  $db->query($select);
  if($db->num_rows())
  {
      $row=$db->fetch_assoc();
      $quoteno = sprintf("%06d",$row['quote_no']);
      $quoteid = $row['id'];
  	  $cust_id = $row['cust_id'];
      $quote_status = $row['quote_status'];
      if($quote_status=='L')
       $quote_status_dd ='Lost';
      else if($quote_status=='W')  
  	   $quote_status_dd ='Won';
      else if($quote_status=='S')  
  	   $quote_status_dd ='Pending';
      else if($quote_status=='A')  
  	   $quote_status_dd ='Active';       
      $Campaign = getCampaignName($db1,$row['marketing']);
	   
      $MEMBER_NAME=get_Employee_Id_Name($db1,$row['sales_rep']);
					   
		  $slaes_rep_disp = "<input type=\"text\" name=\"userid\" value=\"$MEMBER_NAME\" style=\"width:100px;\" class=\"inputField\" />";
	
    
      $quot_date = date('jS F Y',$row['quote_date']);
      $business_name  = $row['business_name'];
      $customer_name  = $row['customer_name'];
      $project_title  = $row['project_title'];
      $address1  = stripslashes($row['address1']);
      $address2  = stripslashes($row['address2']);
      $phone   = $row['phone'];
      $fax   = $row['fax'];
      $email   = $row['email'];
      $quote_gst = $row['gst'];
    
    
   
  }
   $select1="SELECT sqd.*,p.basic_description as pdescription,p.partner_comision FROM sales_quote_detail sqd
  					LEFT JOIN product p ON sqd.product_id=p.id
						WHERE sales_quote_id='".$_REQUEST['id']."'";
   $db1->query($select1);
   if($db1->num_rows())
   { 
      $i=1;$total=0;
      while($row1=$db1->fetch_assoc())
      { 
        $id= $row1['id'];
        $product_name = $row1['product_name'];
  	    $product_id = $row1['product_id'];
        $product_number = $row1['product_number'];
        $sales_quote_id = $row1['sales_quote_id'];
        $description  = $row1['description'];
        $price = $row1['price'];    
        $discount = $row1['discount'];
        $quantity = $row1['quantity'];
        $total = $row1['total'];
        $subtotal = $subtotal+$row1['total'];  
        $partner_comision          = $row1['partner_comision'];
        if($quote_status!='W')
        {
            $del_img ="<a href=\"javascript:void(0);\" onclick=\"delete_quote_products('$id','$sales_quote_id');\"><img  src=\"$SITE_URL/images/icon_delete.gif\"></a>";
             $add_product_btn ="<a href=\"$SITE_URL/quote_add_product.php\" id=\"various\">Add Product</a>";
         }   
         else
         {
            $del_img ="";
            $add_product_btn ="";
          }     
        
        
        $LISTING .="<tr id=\"prd_$id\">
                              	<td><input type=\"text\"  name=\"product_number$i\" id=\"product_number$i\" style=\"width:30px;\" class=\"inputField\" value=\"$product_number\" /><input type='hidden' name='product_id$i' value='$product_id' /></td>
                                  <td>&nbsp;</td>
                                <td><input type=\"text\" value=\"$product_name\" name=\"product_name$i\" id=\"product_name$i\" style=\"width:100px;\" class=\"inputField\" /><input type=\"hidden\" id=\"pid_$i\"  name=\"pid_$i\" value=\"$id\"></td>
                                  <td>&nbsp;</td>
                                  <td><textarea name=\"description$i\" id=\"description$i\" class=\"inputField\" style=\"width:220px; height:50px;\">$description</textarea></td>
                                  <td>&nbsp;</td>
                                  <td><span id=\"price$i\">$</span><input type=\"text\" name=\"price$i\" id=\"price".$i."_hid\" class=\"inputField\" style=\"width:30px;\" value='$price' onchange=\"calculate($i)\"><input type=\"hidden\" name=\"partner_com$i\" id=\"partner_com".$i."_hid\" value='$partner_comision'></td>
                                  <td>&nbsp;</td>
                                   <td><input type=\"text\"  name=\"quantity$i\" id=\"quantity$i\" value=\"$quantity\" style=\"width:30px;\" class=\"inputField\" onchange=\"calculate($i)\" /></td>
                                  <td>&nbsp;</td>
                                  <td><input type=\"text\"  name=\"discount$i\" id=\"discount$i\" value=\"$discount\" style=\"width:30px;\" class=\"inputField\" onchange=\"calculate($i)\" /></td>
                                  <td>&nbsp;</td>
                                  <td><span id=\"total$i\">$".$total."</span><input type=\"hidden\" name=\"total$i\" id=\"total".$i."_hid\" value='$total'></td>
                                  <td>$del_img</td>
                              </tr>";
        
                              
        $i++;
     }
    
        $i=$i-1;
  } 

  
      $gst   = $row['gst'];
      if($gst!=0  && $gst!='')
      {
         $total_price1 = $subtotal*$gst/100; 
         $total_price= $total_price1+ $subtotal;
         $GST_row ="<tr>
                        <td width=\"100\"  class=\"green\">GST ($gst% GST)</td><td><span id=\"subtot\">$total_price1</span></td>
                    </tr>";
      }
      else
        $total_price =  $subtotal;
   
}


function update($db,$db1,$db2,$db3,$db4)
{      
		global $ADMIN_ID;
		$_POST = array_map('addslashes',$_POST);
       $id =$_REQUEST['id'];
       $status             =   $_POST['status'];
       $buss_no            =   $_POST['buss_no'];
	   $cust_id			   =   $_POST['cust_id'];
       $cust_no            =   $_POST['cust_name'];
       $address1           =   addslashes($_POST['address1']);
       $address2           =   addslashes($_POST['address2']);      
       $email              =   addslashes($_POST['email']);
       $phone              =   $_POST['phone'];
       $fax                =   $_POST['fax'];
       $quote_no           =   $_POST['quote_no'];
        $tblCount          =   $_POST['lastid'];
        $project_title     =   $_POST['project_title'];
        $marketing         =   $_POST['marketing'];
       
	   if($_SESSION["SESS_MEMBER_ID"]==$ADMIN_ID || $_SESSION["SESS_MEMBER_ID"]=='5'){
			$sales_rep = $_POST['salesrep'];   
	   }else{
		   $sales_rep = $_SESSION["SESS_MEMBER_ID"];
	   }
	   
      $query="UPDATE sales_quote set 
                quote_no='$quote_no',
				cust_id='$cust_id',
                quote_status='$status',
                quote_date='".strtotime($_POST['quote_date'])."',
                sales_rep='$sales_rep',
                marketing='$marketing',
                business_name='$buss_no',
                address1='$address1',
                address2='$address2',
                phone='$phone',
                fax='$fax',
                customer_name='$cust_no',
                project_title='$project_title', 
                subtotal='".$_POST['subtotal']."',
                gst='".$_POST['gst']."',
                total='".$_POST['total_hid']."',               
                email='$email' where id= $id";
                if ($db->query($query)) 
                {    $inserted_id  =  $id;
                   if($status=='W')
                    { 
                    
                         // copy to partner commision table
                       $partner=Get_Partner_From_Customer($db1,$cust_id);
                       
                       if($partner!='')
                       {
                      
                          $select_p_com ="select id from partner_comission where quote_id=$id";
                          $db1->query($select_p_com);
                          if(!$db1->num_rows())
                          {
                            $insert_partner ="insert into partner_comission set quote_id='$id',customer_id='$cust_id',partner_id='$partner',add_date='".mktime()."'" ;
                           $db1->query($insert_partner);
                           $partner_inserted_id  =  $db3->insert_id();  
                          }
                          else
                          {
                              $reslt = $db1->fetch_assoc();
                              $partner_inserted_id = $reslt['id'];
                          } 
                   
                      }
                      // copy to partner commision table 
                        
            						$sql_chk = "SELECT * FROM project WHERE quote_id='$id'";
            						$db1->query($sql_chk)or die(mysql_error());
            						if(!$db1->num_rows()){                    
            							 $insert="INSERT INTO project SET 
            											quote_id='$id',
            											cust_id='$cust_id',
                                  sales_rep='$sales_rep',
                                  marketing='$marketing',
            											project_num='$quote_no',
                                  project_title='$project_title',
                                  subtotal='".$_POST['subtotal']."',
                                  gst='".$_POST['gst']."',
                                  total='".$_POST['total_hid']."',
            											start_date='".mktime()."'";  
            						
            						
              
              if($db1->query($insert))
                     {   
                      	 $project_id = mysql_insert_id();   
                    
                         for($i=1;$i<=$tblCount;$i++)
                          {
                          $product_number=$_POST["product_number$i"];
                          $product_name= $_POST["product_name$i"];
        				          $product_id= $_POST["product_id$i"];
                          $description=$_POST["description$i"];
                          $price=$_POST["price$i"];
                          $quantity=$_POST["quantity$i"];
                          $discount=$_POST["discount$i"];
                          $total =$_POST["total$i"];
                          
                          $select_chk ="select id from project_prod_chkList where prod_id='$product_id' and project_id='$project_id'" ;
                          $db4->query($select_chk);
                          if(!$db4->num_rows())
                          {
                              $proj_cklst ="INSERT INTO project_prod_chkList(id,prod_id,chk_list_name,hours,add_date,status,project_id) SELECT id,prod_id, chk_list_name,hours,add_date,status, $project_id as project_id FROM prod_chkList where status='1' and prod_id=$product_id";
                              $db4->query($proj_cklst);
                              
                              $proj_cklst_head ="INSERT INTO proj_prod_chkList_Head(id,prod_id,chk_list_id,heading,add_date,project_id) SELECT id,prod_id,chk_list_id,heading,add_date, $project_id as project_id FROM prod_chkList_Head where prod_id=$product_id";  
                              $db4->query($proj_cklst_head);
                              
                              $proj_cklst_shead ="INSERT INTO proj_prod_chkList_subhead(id,prod_id,chk_list_id,head_id,subheading,add_date,project_id) SELECT id,prod_id,chk_list_id,head_id,subheading,add_date, $project_id as project_id FROM prod_chkList_subhead where prod_id=$product_id";  
                              $db4->query($proj_cklst_shead);
                          }
                          if($product_name!='')
                           {
                           $insert_pdetail="insert into project_detail set 
                                   product_name='$product_name',
        						               product_id='$product_id',
                                   product_number='$product_number',
                                   sales_quote_id='$inserted_id',
                                   project_id='$project_id',
                                   description='$description',
                                   price='$price',
                                   quantity='$quantity',
                                   discount='$discount',
                                   total='$total'";
                             $db4->query($insert_pdetail);  
                             
                             
                           }
                          } 
                              
                     }
						}
						
						/****Code for copy the job folder from product to project*/
						$sql_prod_jf = "SELECT * FROM sales_quote_detail WHERE sales_quote_id='$id'";
						$db1->query($sql_prod_jf);
						while($line_prod_jf = $db1->fetch_assoc()){
							$prod_id = $line_prod_jf['product_id'];
							$sql_copy = "INSERT INTO project_jobfile (project_id, name, size, createtime) 
												SELECT concat('".$project_id."') as proj_id, name, size, createtime from tbl_jobfile WHERE parent_id='$prod_id'";
							$db2->query($sql_copy);
						}
						    
                
                $sql_chk_invoice = "SELECT * FROM invoice WHERE quote_id='$id'";
						$db1->query($sql_chk_invoice)or die(mysql_error());
						if(!$db1->num_rows()){                 
						//start copy data to invoice table for unpaid invoice
                     $insert1="insert into invoice set job_number='$quote_no',quote_id='$id',project_title='$project_title',business_name='$buss_no',cust_id='$cust_id',sales_rep='$sales_rep',marketing='$marketing', subtotal='".$_POST['subtotal']."',
                  gst='".$_POST['gst']."',price='".$_POST['total_hid']."',status='U',createtime='".mktime()."'";  
                     if($db3->query($insert1))
                     {   $invoice_inserted_id  =  $db3->insert_id();
                         for($i=1;$i<=$tblCount;$i++)
                          {  
                          $product_number=$_POST["product_number$i"];
                          $product_name= $_POST["product_name$i"];
        				          $product_id= $_POST["product_id$i"];
                          $description=$_POST["description$i"];
                          $quantity=$_POST["quantity$i"];
                          $price=$_POST["price$i"];
                          $discount=$_POST["discount$i"];
                          $total =$_POST["total$i"];
                          $total_price +=  $total ;
                          if($product_name!='')
                           {
                           $insert_detail="insert into invoice_detail set 
                                   product_name='$product_name',
        						               product_id='$product_id',
                                   product_number='$product_number',
                                   sales_quote_id='$inserted_id',
                                   invoice_id='$invoice_inserted_id',
                                   description='$description',
                                   price='$price',
                                   quantity='$quantity',
                                   discount='$discount',
                                   total='$total'";
                             $db4->query($insert_detail);  
                             
                             
                           }
                          }
                              
                     }
                     //End copy data to invoice table for unpaid invoice
						}
                    }  
                  // Change status 
                           if($status=='L' || $status=='S')
                    {
					
                         $select2="select id  from sales_quote where id=$id";  
                         $db1->query($select2); 
                           if($db1->num_rows())
                           {
                              $row2=$db1->fetch_assoc();
                              
                              $update="update sales_quote set quote_status='$status' where id=$id";
                              $db1->query($update);
                             
                            }
                           $select1="select id  from invoice where quote_id=$id";
                           $db1->query($select1); 
                           if($db1->num_rows())
                           {
                             $row1=$db1->fetch_assoc();
                             
                              $delete4="delete from invoice_detail where invoice_id=$row1[id]";
                              $db1->query($delete4);
                              $delete2="delete from invoice where quote_id=$id";
                             $db1->query($delete2);
                            }
                           $select="select id  from project where quote_id=$id";
                           $db1->query($select);
                           if($db1->num_rows())
                           {
                               $row=$db1->fetch_assoc();
                              
                               $delete6="delete from project_detail where sales_quote_id=$id";
                                $db1->query($delete6);
                                
                               $delete7="delete from project_comment where comment_for=$row[id]";
                               $db1->query($delete7);
                               
                               $delete8="delete from project_jobfile where project_id=$row[id]";
                               $db1->query($delete8);
                               
                               $delete9="delete from project_prod_chkList where project_id=$row[id]";
                               $db1->query($delete9);
                               
                               $delete10="delete from proj_prod_chkList_Head where project_id=$row[id]";
                               $db1->query($delete10);
                               
                               $delete11="delete from proj_prod_chkList_subhead where project_id=$row[id]";
                               $db1->query($delete11);
                               
                                
                               $delete3="delete from project where quote_id=$id";
                               $db1->query($delete3); 
                               
                             
                          }
                          
                            $delete12="delete from partner_comission where quote_id=$id";
                            $db1->query($delete12); 
                               
                            $delete13="delete from partner_comision_detail where quote_id=$id"; 
                            $db1->query($delete13);      
                    } 
                  //Change status 
                   for($i=1;$i<=$tblCount;$i++)
                  {
                    $pid=$_POST["pid_$i"];
                   $product_name= $_POST["product_name$i"];
                   $product_id= $_POST["product_id$i"];
                   $description=$_POST["description$i"];
                   $price=$_POST["price$i"];
                   $quantity=$_POST["quantity$i"];
                   $discount=$_POST["discount$i"];
                   $total =$_POST["total$i"];
                    if($pid > 0)
                     {
                       $update="update sales_quote_detail set 
                           product_name='$product_name',
                           description='$description',
                           price='$price',
                           quantity='$quantity',
                           discount='$discount',
                           total='$total' where id=$pid"; 
                          $db1->query($update);        
                     }
                   else
                   { 
                      $product_number=$_POST["product_number$i"];
                      $product_name= $_POST["product_name$i"];
					            $product_id= $_POST["product_id$i"];
                      $description=$_POST["description$i"];
                      $price=$_POST["price$i"];
                      $quantity=$_POST["quantity$i"];
                      $discount=$_POST["discount$i"];
                      $total =$_POST["total$i"];
                   
                      if($quantity!='')
                      {                      
                      
                    $insert="insert into sales_quote_detail set 
                               sales_quote_id='$id',
                               product_name='$product_name',
							                 product_id='$product_id',
                               product_number='$product_number',
                               description='$description',
                               price='$price',
                               quantity='$quantity',
                               discount='$discount',
                               total='$total'"; 
                         $db2->query($insert);  
                      }                            
                            
                   }
                        if($partner_inserted_id!='')
                       {   
                               //// copy to partner commision detail table
                           if($status=='W'){
                             
                        
                          $partner_com= $_POST["partner_com$tblCount"];              
                         
                          $total_comission =$total*  $partner_com/100;
                          
                        
                           $insert_part_det="insert into partner_comision_detail set 
                                   partner_com_id='$partner_inserted_id',                          
        						               product_id='$product_id',
                                   quote_id='$inserted_id',
                                   quote_detail_id='$pid',                           
                                   comision='$partner_com',
                                   total_price='$total',
                                   total_comission='$total_comission'"; 
                             $db2->query($insert_part_det);
                          
                           }
                           
                            //// copy to partner commision detail table 
                     }  
                   }  
                  }
                   
                $_SESSION['sess_msg'] = "Quote updated successfully.";
                $_SESSION['sess_class']='notice'; 
} 

?>