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
  	header ("Location: ./view_users.php"); 		
  }


 
  getData($db,$db1);
  
 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html");  
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/edit_user.html"); 
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function getData($db,$db1)
{
        global $id,$EMP_REGION,$REGION_LIST,$first_name,$last_name,$picture,$mobile_phone,$phone,$fax,$address,$city,$state,$country,$postal_code,$email,$status,$DIR_DISPLAY_USER_FILE,$SITE_URL,$uploaded_user_image,$status_active,$status_inactive,$emp_type,$report_to,$tfn_no;
        
         
        $id = $_REQUEST['id'];
        $select = "select * from users as E  where id='$id'";
        $db->query($select);
        
        if($db->num_rows() > 0)
        {   
             $rec = $db->fetch_assoc();
             $id                  =   $rec['id'];
             $tfn_no              =   $rec['tfn_no'];
             $first_name          =   $rec['first_name'];
             $last_name           =   $rec['last_name'];            
             $REGION_LIST=  getRegionListUSer($db,$db1,$rec['region']);
             $picture             =   $rec['picture'];
             $mobile_phone        =   $rec['mobile_phone'];
             $phone               =   $rec['phone'];
             $fax                 =   $rec['fax'];
             $city                =   $rec['city'];
             $country             =   $rec['country'];            
             $address             =   $rec['address']; 
             $state               =   $rec['state']; 
             $postal_code         =   $rec['postal_code'];
             $status              =   $rec['status'];
             $email               =   $rec['email'];
             $username            =   $rec['username'];
             $recpassword            =   $rec['password']; 
                              
             if($status == 'A')
             $status_active = 'selected';
             else
             $status_inactive ='selected';
        
        if($picture!='')
             {  
             $filenname_path=$DIR_DISPLAY_USER_FILE."/".$picture;
             $uploaded_user_image = "<img src=\"$SITE_URL/image.php/$picture?width=50&amp;height=50&amp;cropratio=50:50&amp;image=$filenname_path\" width=\"50\" height=\"50\" align='absbottom' /><br/>";   
             }
        
        }
        
        
}

function update($db,$db1)
{      
 global $DIR_UPLOAD_USER_FILE, $SITE_URL,$MEMBER_ID;
		    
       $id                    =   $_REQUEST['id'];         
       $tfn_no                =   $_POST['tfn_no'];
       $first_name            =   $_POST['first_name'];
       $last_name             =   $_POST['last_name'];     
       $picture               =   $_POST['picture'];
       $picture_old           =   $_POST['picture_old'];
       $mobile_phone          =   $_POST['mobile_phone'];
       $phone                 =   $_POST['phone'];
       $fax                   =   $_POST['fax'];       
       $address               =   $_POST['address'];
       $state                 =   $_POST['state'];
       $country               =   $_POST['country'];
       $city                  =   $_POST['city']; 
       $postal_code           =   $_POST['postal_code'];
       $email                 =   $_POST['email'];
       $status                =   $_POST['status'];
       $region            =   $_POST['region'];
  
      if($_FILES['picture']['name']!='' && $_FILES['picture']['size']!='')
       {
               $picture      =   $_FILES['picture']['name'];
               $ext          =   getExtension($picture);
               $target       =   $DIR_UPLOAD_USER_FILE."/".$picture;
               if(file_exists($target))
               {
                  $picture = time()."_".rand(100,99999).".".$ext;
                 $target       =   $DIR_UPLOAD_USER_FILE."/".$picture;
               }
              copy($_FILES['picture']['tmp_name'], $target);
              deleteFile($DIR_UPLOAD_USER_FILE."/".$picture_old);  
       }
       else
       {  $picture=$picture_old; }
       
             
        $query="UPDATE users set 
                tfn_no='$tfn_no',
                first_name='$first_name',
                last_name='$last_name',
                region='$region',
                picture='$picture',
                mobile_phone='$mobile_phone',
                fax='$fax',
                phone='$phone',              
                address='$address',
                state='$state',
                country='$country',
                city='$city',
                postal_code='$postal_code',
                email='$email',
                createtime='".mktime()."',
                modifytime='".mktime()."',
                status='$status' where id='$id'";      
                //$db->query($query);                        
          if($db->query($query)) 
          {
         
           $slDesc       =   "<b>".$title."</b> -Profile updated Successfully.";   
           $_SESSION['sess_msg'] = $slDesc;
           $_SESSION['sess_class']='notice';      
           
          }
    
} 
  
?>