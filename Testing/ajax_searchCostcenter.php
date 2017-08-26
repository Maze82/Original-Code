<?php session_start(); ?>
<?php	

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


$MEMBER_NAME = $_SESSION["SESS_MEMBER_NAME"]; 
$MEMBER_TYPE = $_SESSION["SESS_MEMBER_TYPE"]; 
$MEMBER_ID   = $_SESSION["SESS_MEMBER_ID"];  
getOrderSearchResult($db,$db1,$db2);

function getOrderSearchResult($db,$db1,$db2)
{
  global $pdf_link,$SITE_URL;
  
   $query2 = "select id,ward_name,ward_category,ordering_person,email from wards where 1 ";
 				
 
  
  $from=trim($_REQUEST['from']);
  $to=trim($_REQUEST['to']);  
  $ward=trim($_REQUEST['ward']);
  $category=trim($_REQUEST['category']);
  
  $date_parts=explode("/", $from);
       $day = $date_parts[1];
       $month = $date_parts[0];
       $year = $date_parts[2];
       $from1=mktime(0,0,0,$month,$day,$year);
  
     $date_parts1=explode("/", $to);
       $day1 = $date_parts1[1];
       $month1 = $date_parts1[0];
       $year1 = $date_parts1[2];
       $to1=mktime(0,0,0,$month1,$day1,$year1);
	   
 	
  	if($_REQUEST['ward']!='')
  {    
    $query2 .=" and  ward_name like '%$ward%'";  
  } 
  	if($_REQUEST['category']!='')
  {    
     $query2 .=" and  ward_category ='$category'";  
  } 
  	 
   if($_REQUEST['from']!=0 && $_REQUEST['to']==0)
  {
    $query2 .= " and  createtime >= '".$_REQUEST['from']."'";
    
  }	
	
 if($_REQUEST['from']==0 && $_REQUEST['to']!=0)
  {
    $query2 .= " and  createtime <= '".$_REQUEST['to']."'";
    
  }
  
   if($_REQUEST['from']!=0 && $_REQUEST['to']!=0)
  {
    $query2 .= " and  createtime >= '".$_REQUEST['from']."' and createtime <= '".$_REQUEST['to']."'";
    
  }
  
 
 
    
   $query2 .=" order by id asc";		
 
  //echo  $query2; 
 $db1->query($query2);
 $number_rows= $db1->num_rows();  
  $display_count = $number_rows;
  
      
 if($db1->num_rows())
 {
  $loop=1;
  $count=1;  
  
  
  $SEARCH_LIST .=" <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"table table-bordered responsive dataTable product_table\">
                        <thead>
                        	<tr>
                            	<th width=\"30\">#</th>
								 <th width=\"180\">Ward Category</th>
                                <th width=\"180\">Ward Name</th>
                                <th width=\"150\">Ordering Person</th>
								<th width=\"150\">Email</th>                                               
                                <th width=\"150\"></th>
                            </tr>
                           </thead>";
						   
						 


  while($row = $db1->fetch_array())
  {  
      //$srNo = $page*$MAX- $MAX + $loop +1;
       
      $id               =   $row['id'];
      
       $ward_name            =   $row['ward_name'];
	   $ordering_person            =   $row['ordering_person'];
	   $email            =   $row['email'];
       $category_name            =   getWradCategoryName($db1,$row['ward_category']);
	   
	   if($row['date_filled']!=0)
       		$date_filled            =   date('d.m.Y',$row['date_filled']);
	   else
	   		$date_filled='-';
	   $status=$row['status'];
       
 	        
       if($loop%2==0)
            $class="class='even_class'";
       else
          $class="";
          
        
       
    $SEARCH_LIST.="<tr id=\"ep_$id\">
                      <td>$loop</td>
					  <td>$category_name</td>
                      <td>$ward_name</td>
                      <td>$ordering_person</td>                 
                       <td>$email</td>            
                       <td><a href=\"$SITE_URL/edit_ward.php?id=$id\"><img src=\"$SITE_URL/images/icon_edit.png\" align='top'  align='top'></a> &nbsp;<input type=\"image\" src=\"$SITE_URL/images/icon_close.png\" style='vertical-align:top;' onclick=\"remove_ward($id);\" /> </td>                      
                    </tr>";	
      
            $loop++;  
  } 
  
 
   $SEARCH_LIST.="</table>";
   
   
 }
 else
 {
 	$SEARCH_LIST="<div class=\"nav_details2\">
                    	<p>Orders</span></p>
                    </div>
                    <div class=\"block_customer_search\" ><div style=\"margin-left:250px;margin-top:100px;margin-bottom:100px;\"><strong> Sorry , No Data Available !</strong></div></div>";
 		
 } 
 echo   $SEARCH_LIST;                      
}
?>