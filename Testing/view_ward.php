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
$ward_category=getWardcategory($db,$id='');
 display_message(); 

 $TOPBAR	      = ReadTemplate("$TEMPLATE_DIR/common/topbar_admin.html");
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR/view_ward.html");

 $BOTTOMBAR		  = ReadTemplate("$TEMPLATE_DIR/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR/common/sub_template.html");
 $TEMPLATE		  = ReadTemplate("$TEMPLATE_DIR/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function getData($db,$db1)
{

  global $SITE_URL,$DIR_DISPLAY_USER_FILE,$PAGE_LIST,$MEMBER_ID,$category;
   global $SITE_URL,$PAGE_LIST,$MEMBER_ID,$pdf_link,$fromdate;
   global $keyword,$srchStatus,$ARR_GLOBAL_STATUS;
 	global $PREV_PAGE_LINK,$NEXT_PAGE_LINK,$TOTAL_RECORDSET,$PAGE_NAVS, $CURRENT_PAGE_NO, $TOTAL_PAGES,$totalAll, $SITE_URL;
  
  

       $query="select * from wards where 1=1"; 
	
	
/*	if(trim($_REQUEST['category'])!='')
  {
 echo "hi". $category=trim($_REQUEST['category']);exit;
  }*/
   
   if(trim($_REQUEST['from'])!='')
     {
		$fromdate=trim($_REQUEST['from']);
		
		$date_parts=explode("/", $fromdate);
		$day = $date_parts[0];
		$month = $date_parts[1];
		$year = $date_parts[2];
		
		$fromdate=mktime(0,0,0,$month,$day,$year);
	   
     }   
 if(trim($_REQUEST['to'])!='')
      {
		$todate=trim($_REQUEST['to']);
		
		$date_parts1=explode("/", $todate);
		$day1 = $date_parts1[0];
		$month1 = $date_parts1[1];
		$year1 = $date_parts1[2];
		//echo $day1."/".$month1."/".$year1;
		$todate=mktime(23,59,59,$month1,$day1,$year1);
       } 
	   
	   
	   
if(trim($_REQUEST['ward'])!='')
     {
  $ward=trim($_REQUEST['ward']);
     }   
	
if(trim($_REQUEST['category'])!='')
     {
  $category=trim($_REQUEST['category']);
     }   
	  
	  
if($fromdate!=0 && $todate==0)
  { 
   $query .= " and  createtime >= $fromdate";
  }	 
  if($fromdate==0 && $todate!=0)
  { 
   $query .= " and  createtime <= $todate";
  }	
   if($fromdate!=0 && $todate!=0)
  { 
   $query .= " and  createtime >= $fromdate and  createtime <= $todate";
  }	
	
if($ward!='')
  { 
   $query .=" and  ward_name ='$ward'";  
   
  }	
	     
	   
 if($category!='')
  { 
   $query .=" and  ward_category ='$category'";  
   
  }
  
  


         $db->query($query);	
		if($db->num_rows())
		{	
      $loop=1;    
      
      while($row=$db->fetch_array())
      {
       $id                  =   $row['id'];
       $ward_name           =   $row['ward_name'];
       $ordering_person     =   $row['ordering_person'];
	   $email               =   $row['email'];
       $ward_category       =  getWradCategoryName($db1,$row['ward_category']);//echo $ward_category;exit;
      
		  $PAGE_LIST.="<tr id=\"ep_$id\">
                      <td>$loop</td>
					  <td>$ward_category</td>
                      <td>$ward_name</td>
                      <td>$ordering_person</td>                 
                       <td>$email</td>            
                       <td><a href=\"$SITE_URL/edit_ward.php?id=$id\"><img src=\"$SITE_URL/images/icon_edit.png\" align='top'  align='top'></a> &nbsp;<input type=\"image\" src=\"$SITE_URL/images/icon_close.png\" style='vertical-align:top;' onclick=\"remove_ward($id);\" /> </td>                      
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