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

 error_reporting(E_ALL);
if($_POST['save'])
  {
  	update($db,$db1);
  	header ("Location: ./view_ward.php"); 		
  }


 
  getData($db,$db1);
  
 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html");  
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/edit_ward.html"); 
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function getData($db,$db1)
{
        global  $i,$id,$ward_name,$ordering_person,$email,$ward_products,$CATE_LIST;
        
         
        $id = $_REQUEST['id'];
        $select = "select * from wards  where id='$id'";
        $db->query($select);
        
        if($db->num_rows() > 0)
        {   
             $rec = $db->fetch_assoc();
             $id                  =   $rec['id'];
             $ward_name                  =   $rec['ward_name'];
        	 $ordering_person            =   $rec['ordering_person'];
       		 $email            =   $rec['email'];
			 $CATE_LIST=getWardcategory($db1, $rec['ward_category']);
        
        }
        
        $select = "select * from ward_products  where ward_id='$id'";
        $db->query($select);
       
        if($db->num_rows() > 0)
        {    $i=1;$total=0;
             while($rec = $db->fetch_assoc()){
             $id                  =   $rec['id'];
             $product_name           =   $rec['product_name'];
        	 $product_id           =   $rec['product_id'];
			 
			 
			 
			$ward_products .= "<tr id=\"prd_$id\">
                                <td width=\"30%\" align=\"right\"></td>
                                <td><input  name=\"product_name$i\" type=\"text\" class=\"input_txt\" value=\"$product_name\" /><input type=\"hidden\" name=\"product_id$i\" value=\"$product_id\" /><input  name=\"product_weight$i\" type=\"hidden\" class=\"input_txt\" value=\"$product_weight\" /><input type=\"hidden\" id=\"pid_$i\"  name=\"pid_$i\" value=\"$i\"><a href=\"javascript:void(0);\" onclick=\"delete_ward_products($id)\"><img src=\"$SITE_URL/images/minus_green.png\"  /></a></td>
                            </tr>";
			 $i++;		 
			 
			 }
			 $i=$i-1;
  } 
  else
  {
      $i=0;
  } 
        
       
}

function update($db,$db1)
{      
       $product_name          =   $_POST['product_name'];
	   $ward_name          =   $_POST['ward_name'];
	   $ordering_person         =   $_POST['ordering_person'];
       $product_weight        =   $_POST['product_weight'];
	  // $tblCount          =   $_POST['lastid'];
	   $tblCount          =   $_POST['lastid'];
		$email          =   $_POST['email'];
      
     
		  
      $select ="select id from wards where ward_name='$ward_name' and id!='".$_REQUEST['id']."'";
      $db->query($select);
      if($db->num_rows()>0)
      {
          $_SESSION['sess_msg'] = "Ward Already exist.";
          $_SESSION['sess_class']='notice';
          header('Location: ./create_ward.php');
          exit;          
      }
      else
      { 
			
			$query="update wards set 
			ward_category='".$_POST['ward_category']."',              
			ward_name='$ward_name',
			ordering_person='$ordering_person',
			email='$email',             
			createtime='".mktime()."'
			where id='".$_REQUEST['id']."'";
                            
			  if($db->query($query)) 
			  {
		  
				for($i=1;$i<=$tblCount;$i++)
				{
				 $pid=$_POST["pid_$i"];
				$product_name= $_POST["product_name$i"];
				$product_id= $_POST["product_id$i"];
				$product_weight= $_POST["product_weight$i"];
				
				if($pid > 0)
				{
					
					$update="update ward_products set 
					product_name='$product_name',
					product_id='$product_id',
					product_weight='$product_weight'                          
					where id=$pid";
					$db->query($update);        
				}
				else
				{  
					$product_name= $_POST["product_name$i"];
					$product_id= $_POST["product_id$i"];
					$product_weight= $_POST["product_weight$i"];
					
					if($product_name!=''){
									
				   $insert="insert into ward_products set 
					ward_id='".$_REQUEST['id']."',
					product_name='$product_name',
					product_id='$product_id',
					product_weight='$product_weight',
					createtime='".mktime()."'"; 
					$db->query($insert); 
					} 
				}
           }
		            $slDesc       =   "<b>".$title."</b> -Ward updated Successfully.";   
           $_SESSION['sess_msg'] = $slDesc;
           $_SESSION['sess_class']='notice';      
           
          }
		//  die();

		}  
    
} 
  
?>