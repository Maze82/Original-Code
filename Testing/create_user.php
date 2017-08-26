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

 if(isset($_POST['saven']))
{ 
   add($db,$db1,$db2);
  
}
if(isset($_POST['save']))
{ 
  add($db,$db1,$db2);
  
}  
$REGION= getRegionListUSer($db,$db1,$id='');


 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html"); 
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/create_user.html");
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function add($db,$db1,$db2)
{      
 global $DIR_UPLOAD_USER_FILE, $SITE_URL,$MEMBER_ID,$PROMPT;
		    
       $first_name            =   $_POST['first_name'];
       $last_name             =   $_POST['last_name'];
       $region                =   $_POST['region'];   
       $picture               =   $_POST['picture'];
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
       $tfn_no                =   $_POST['tfn_no'];
        
         
          
      $select ="select id from users where email='$email'";
      $db->query($select);
      if($db->num_rows()>0)
      {
          $_SESSION['sess_msg'] = "User Already exist.";
          $_SESSION['sess_class']='notice';
          header('Location: ./create_user.php');
          exit;          
      }
      else
      { 
         
            
          if($_FILES['picture']['name']!='')
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
           }
           else
           { $picture=''; }
       
   
        $query="insert into users set               
                first_name='$first_name',
                last_name='$last_name',
                region='$region',
				tfn_no='$tfn_no',
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
                status='$status'"; 
                //$db->query($query);                        
          if ($db->query($query)) 
          {
              
           if(isset($_POST['saven']))
            { 
               $slDesc       =   "User has been added Successfully.";   
               $_SESSION['sess_msg'] = $slDesc;
               $_SESSION['sess_class']='notice';             
               header('Location: ./create_user.php');
               exit;
            }
             if(isset($_POST['save']))
            { 
               $slDesc       =   "User has been added Successfully.";   
               $_SESSION['sess_msg'] = $slDesc;
               $_SESSION['sess_class']='notice';               
              header('Location: ./view_users.php');
              exit;
            }  
          }
    }
} 



?>