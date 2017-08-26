<?php 

session_start(); 

include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions_client.library.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());

$MEMBER_NAME=$_SESSION["SESS_CLIENT_NAME"];


$PROJECT_LIST .="<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"table table-bordered responsive dataTable view_profile\">
                <thead><tr>
                            	<th width=\"100\">Job #</th>
                              <th width=\"110\">Start Date</th>
                              <th width=\"150\">Finish Date</th>
                              <th width=\"280\">Project Title</th>
                              <th width=\"280\">Customer</th>
                              <th width =\"75\">Time</th>
                              <th width=\"50\">Status</th>
                              <th width=\"50\">Action</th>                              
                            </tr></thead>";
if($_REQUEST['type']!='' ){
	$type = $_REQUEST['type'];
	$query = " AND P.status='$type'";
}
else
{
  $query = " AND P.status='A'";  
}
 



	  $sql = "SELECT P.*, S.customer_name,S.cust_id 
				        FROM project P 
					       LEFT JOIN sales_quote S ON P.quote_id = S.id
                  LEFT JOIN customer C ON P.cust_id = C.id 
						      WHERE 1 AND P.status='A' AND P.cust_id='".$_SESSION["SESS_CLIENT_ID"]."'  ";
  $db->query($sql);
  $num_current=number_format($db->num_rows());  
  
    $sql1 = "SELECT P.*, S.customer_name,S.cust_id 
				        FROM project P 
					       LEFT JOIN sales_quote S ON P.quote_id = S.id
                  LEFT JOIN customer C ON P.cust_id = C.id 
						      WHERE 1 AND P.status='O' AND P.cust_id='".$_SESSION["SESS_CLIENT_ID"]."' ";
  $db->query($sql1);
  $num_ongoing=number_format($db->num_rows());
  
    $sql2 = "SELECT P.*, S.customer_name,S.cust_id 
				        FROM project P 
					       LEFT JOIN sales_quote S ON P.quote_id = S.id
                  LEFT JOIN customer C ON P.cust_id = C.id 
						      WHERE 1 AND P.status='U' AND P.cust_id='".$_SESSION["SESS_CLIENT_ID"]."' ";
  $db->query($sql2);
  $num_upcoming=number_format($db->num_rows()); 
  
  $sql3 = "SELECT P.*, S.customer_name,S.cust_id 
				        FROM project P 
					       LEFT JOIN sales_quote S ON P.quote_id = S.id
                  LEFT JOIN customer C ON P.cust_id = C.id 
						      WHERE 1 AND P.status='F' AND P.cust_id='".$_SESSION["SESS_CLIENT_ID"]."' ";
  $db->query($sql3);
  $num_finish=number_format($db->num_rows());         
  

  $sql = "SELECT P.*, S.customer_name,C.business_name,S.cust_id 
				        FROM project P 
					       LEFT JOIN sales_quote S ON P.quote_id = S.id
                  LEFT JOIN customer C ON P.cust_id = C.id 
						      WHERE 1 AND P.cust_id='".$_SESSION["SESS_CLIENT_ID"]."' $query ";
  
   
	$db->query($sql);
    
	if($db->num_rows()){
  
     $PROJECT_LIST .="<div class=\"nav_details marbot\">
                    	<ul> 
                        	<li id = \"A\" class=\"active\"><a href=\"javascript: void(0)\" onclick=\"ajaxProjectCat('A')\">Current Projects ($num_current)</a></li>
                          <li id = \"O\"><a href=\"javascript: void(0)\" onclick=\"ajaxProjectCat('O')\">Ongoing Projects ($num_ongoing)</a></li>
                          <li id = \"U\"><a href=\"javascript: void(0)\" onclick=\"ajaxProjectCat('U')\">Upcoming Projects ($num_upcoming)</a></li>
                          <li id = \"F\"><a href=\"javascript: void(0)\" onclick=\"ajaxProjectCat('F')\">Finished Projects ($num_finish)</a></li>
                         
                        </ul>
                    </div>
                    <div class=\"block_customer_search\" >";
                  
		while($row1=$db->fetch_assoc()){
			
			$id = $row1['id'];   
			$cust_id = $row1['cust_id'];
			$status = $PROJ_STATUS[$row1['status']];
			$allo_to = getReportToName($db1,$row1['allocated_to']);
			
      if($row1['end_date']!='0')
      {
          $end_date = date('d/m/Y',$row1['end_date']);
      }
      else
        $end_date ="-----"  ;
			
			$PROJECT_LIST .="<tr>
			        <td><a href=\"$SITE_URL/client/view_project.php?id=$id\">#".sprintf("%06d",$row1['project_num'])."</a></td>
							 <td>".date('d/m/Y',$row1['start_date'])."</td>
               <td>$end_date</td>
								<td>$row1[project_title]</td>
									<td>$row1[business_name]</td>
									<td>-----</td>
									<td>$status</td>
									<td><a href=\"$SITE_URL/client/view_project.php?id=$id\"><img src=\"$SITE_URL/images/view.png\" title='View' /></a></td>
								</tr>";
		}
	}else{
		$PROJECT_LIST="<div class=\"nav_details marbot\">
                    	<ul> 
                        	<li id = \"A\" class=\"active\"><a href=\"javascript: void(0)\" onclick=\"ajaxProjectCat('A')\">Current Projects ($num_current)</a></li>
                          <li id = \"O\"><a href=\"javascript: void(0)\" onclick=\"ajaxProjectCat('O')\">Ongoing Projects ($num_ongoing)</a></li>
                          <li id = \"U\"><a href=\"javascript: void(0)\" onclick=\"ajaxProjectCat('U')\">Upcoming Projects ($num_upcoming)</a></li>
                          <li id = \"F\"><a href=\"javascript: void(0)\" onclick=\"ajaxProjectCat('F')\">Finished Projects ($num_finish)</a></li>
                        </ul>
                    </div>
                    <div class=\"block_customer_search\" >
                    	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"table table-bordered responsive dataTable view_profile\">
                       <div style=\"margin-left:250px;margin-top:100px;margin-bottom:100px;\"><strong> Sorry , No Data Available !</strong></div>";
	}
     
    $PROJECT_LIST .="</table></div>";   
   

 
 
echo $PROJECT_LIST;
 


?>