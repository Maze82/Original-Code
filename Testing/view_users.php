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
getData($db,$db1);

 display_message(); 

 $TOPBAR	      = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html");
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/view_user.html");

 $BOTTOMBAR		  = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		  = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function getData($db,$db1)
{

  global $SITE_URL,$DIR_DISPLAY_USER_FILE,$PAGE_LIST,$MEMBER_ID;
   
       $query="select * from users  order by first_name asc "; 
         $db->query($query);	
		if($db->num_rows())
		{	
      $loop=1;    
      
      while($row=$db->fetch_array())
      {
       $id                    =   $row['id'];
      
       $first_name            =   $row['first_name'];
       $last_name             =   $row['last_name'];
       $name                  =   $first_name." ".$last_name;
     
       $picture               =   $row['picture'];
       $office_phone          =   $row['office_phone'];
       $phone                 =   $row['phone'];
       $fax                   =   $row['fax'];
      
          $region                   =   $row['region'];
        if($region!='')
          $region = getRegionListName($db1,$region);
        else
           $region ='-'; 
           
    
       $address               =   $row['address'];
       $state                 =   $row['state'];
       $country               =   $row['country'];
       $city                  =   $row['city']; 
       $postal_code           =   $row['postal_code'];
       $email                 =   $row['email'];
    
        if( $row['status']=='A')
			$status='Active';
	    else
			$status='Inactive';		
    
		  $PAGE_LIST.="<tr id=\"ep_$id\">
                      <td>$loop</td>
                      <td>$name</td>
                      <td>$region</td>                      
                       <td>$status</td>                    
                       <td><a href=\"javascript:void(0);\"><img src=\"$SITE_URL/images/icon_search.png\" align='top' onclick=\"window.location.href='$SITE_URL/user_detail.php?id=$id'\" align='top'></a> &nbsp;<input type=\"image\" src=\"$SITE_URL/images/icon_close.png\" style='vertical-align:top;' onclick=\"remove_emp($id);\" /> </td>                      
                    </tr>";	
      
            $loop++;
        }
        
      }
  	else
		{
			$PAGE_LIST="<tr><td colspan='5' align='center'>---- E M P T Y ----</td></tr>";	
		}
}


?>