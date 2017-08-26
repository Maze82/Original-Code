<?php session_start(); ?>
<?php	

include("config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions.library.php"); 

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());


$MEMBER_NAME = $_SESSION["SESS_MEMBER_NAME"]; 
$MEMBER_TYPE = $_SESSION["SESS_MEMBER_TYPE"]; 
$MEMBER_ID   =$_SESSION["SESS_MEMBER_ID"];  


addFilterPreferencevalue($db);
function addFilterPreferencevalue($db)
{
$MEMBER_ID   =$_SESSION["SESS_MEMBER_ID"];  
$orderdate11=trim($_REQUEST['orderdate11']);
$orderdate21=trim($_REQUEST['orderdate21']);
$filleddate11=trim($_REQUEST['filleddate11']);
$filleddate21=trim($_REQUEST['filleddate21']);
$status1=trim($_REQUEST['status1']);
$products1=trim($_REQUEST['products1']);
$customer1=trim($_REQUEST['customer1']);
$costcentre1=trim($_REQUEST['costcentre1']);
$customertype1=trim($_REQUEST['type1']);

$date_parts=explode("/", $orderdate11);
	$day = $date_parts[0];
	$month = $date_parts[1];
	$year = $date_parts[2];
$orderdate11=mktime(0,0,0,$month,$day,$year);
	   
  
     $date_parts1=explode("/", $orderdate21);
       $day1 = $date_parts1[0];
       $month1 = $date_parts1[1];
       $year1 = $date_parts1[2];
	   //echo $day1."/".$month1."/".$year1;
       $orderdate21=mktime(23,59,59,$month1,$day1,$year1);
	   
	   $date_parts2=explode("/", $filleddate11);
       $day2 = $date_parts2[0];
       $month2 = $date_parts2[1];
       $year2 = $date_parts2[2];
       $filleddate11=mktime(0,0,0,$month2,$day2,$year2);
  
     $date_parts3=explode("/", $filleddate21);
       $day3 = $date_parts3[0];
       $month3 = $date_parts3[1];
       $year3 = $date_parts3[2];
       $filleddate21=mktime(23,59,59,$month3,$day3,$year3);
	   $select="select * from filtering_preferences where user_id='$MEMBER_ID'";
	   
	     $db->query($select);
       if($db->num_rows()>0)
        {
		
		 $rec = $db->fetch_assoc();
		 $id=$rec['user_id'];
		
		$queryupdate="update filtering_preferences set 
	    order_date_from='$orderdate11',
		order_date_to='$orderdate21',
		filled_date_from='$filleddate11',
		filled_date_to='$filleddate21',
		orderstatus='$status1',
		costcentres='$costcentre1',
		productrange='$products1',
		customers='$customer1',
		customertype='$customertype1',
		createtime='".mktime()."' where user_id='$id'";
		
			$db->query($queryupdate);
	
		
		}
		else
		{
      $query="insert into filtering_preferences  set 
		user_id='$MEMBER_ID',
		order_date_from='$orderdate11',
		order_date_to='$orderdate21',
		filled_date_from='$filleddate11',
		filled_date_to='$filleddate21',
		orderstatus='$status1',
		costcentres='$costcentre1',
		productrange='$products1',
		customers='$customer1',
		customertype='$customertype1',
		createtime='".mktime()."'";
		
		$db->query($query);
	
	
	
		}
		echo 1 ;
}


?>