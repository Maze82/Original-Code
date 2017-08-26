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
$quote_id =$_REQUEST['id'];
$PROJECTId =$_REQUEST['id'];


checkClientLogin();
display_message();

$CHECKLISTS=Get_CheckLists($db,$db1);
$TIMESHEET=Get_Timesheet($db,$db1,$_REQUEST['id']);

getData($db,$db1,$db2);
getProduct($db,$db1,$quote_id);
$COMMENT_LIST = getComments($db,$db1,$db2,$db3,$db4);





$TOPBAR			    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/topbar.html");
$PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR_CLIENT/view_project.html");
$RIGHT_BAR		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/rightbar.html");
$BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/bottombar.html");
$SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/sub_template.html");
$TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();

function getData($db,$db1,$db2)
{
	global $ChECK_lIST,$project_id,$phone,$customer_name,$asignees,$business_name,$project_num,$product_name,$status_onhold,$status_live,$status_finished,$project_title,$start_date,$end_date,$uploaded_doc_file,$uploaded_doc_links,$customer_id,$p_id,$EMPLOYEE_LIST,$PROJ_STATUS_DD,$PROJ_STATUS,$quote_id,$p_manager;
	
  $project_id=$_REQUEST['id'];
	$select ="SELECT P.*, S.business_name, S.customer_name, S.phone
				FROM project P 
					LEFT JOIN sales_quote S ON P.quote_id = S.id 
						WHERE P.id='".$_REQUEST['id']."'";
	$db->query($select);
	
	if($db->num_rows()){
		$row=$db->fetch_assoc();
		$quote_id = $row['quote_id'];
		$project_num = sprintf("%06d",$row['project_num']);
		$business_name  = $row['business_name'];
		$customer_name  = $row['customer_name'];
		$cust_id	= $row['cust_id'];
		$phone   = $row['phone'];
		$p_manager = getReportToName($db1,$row['p_manager']);
    $asignees = GET_Asssignees($db1,$row['asignees']);
    $ChECK_lIST=Checklist_List($db1,$db2);
   
		
		$select2 = "SELECT id FROM customer where id='$cust_id'";
		$db2->query($select2);
		$row2=$db2->fetch_assoc();  
		$customer_id=$row2['id'];   
         
        $status=$row['status'];
        
		$PROJ_STATUS_DD = $PROJ_STATUS[$status];
		
        $project_title  = $row['project_title'];
        $start_date     =   $row['start_date'];
            if(($start_date=='0') || ($start_date=='')){
				$start_date ='';
            }else{ 
				$start_date =   date('d/m/Y',$start_date);
			}
      
       $end_date     =   $row['end_date'];
            if(($end_date=='0') || ($end_date=='')){
				$end_date ='';
            }else{ 
				$end_date =   date('d/m/Y',$end_date);
			}
            $allocated_to	=$row['allocated_to']; 
            $EMPLOYEE_LIST 	= getEmployeelist($db,$allocated_to);
            $uploaded_doc_file = getProductDocument($db,$_REQUEST['id']);
            $uploaded_doc_links =getProductJobLinks($db,$project_id);
	}
}

function getProduct($db,$db1,$quote_id){
  global $product_name_list,$sales_quote_id,$description,$price,$discount,$total,$LISTING,$product_id,$total1;
  
  $select1="select * from project_detail where sales_quote_id='$quote_id'";
  $db1->query($select1);
  if($db1->num_rows()){ 
	$i=1;
    while($row1=$db1->fetch_assoc())
    { 
      $id= $row1['id'];
      $product_name = $row1['product_name'];
      $product_number = $row1['product_number'];
      $sales_quote_id = $row1['sales_quote_id'];
     
      $description  = $row1['description'];
      $price = $row1['price'];
      $discount = $row1['discount'];
      $total = $row1['total'];  
      $total1=$total+$total1;
      $product_name_list .="- $product_name<br />";
      $i++;
    }
   $i=$i-1;
  } 
  $LISTING .="<input type=\"hidden\" id=\"tblCount\" name=\"tblCount\" value=\"$i\">";
}

function update($db,$db1,$db2)
{
  global $key_val;
  $key_val            = generateInvoiceNo($db1);
  $job_number         =   $_POST['job_number'];
  $business_name      =   $_POST['business_name'];
  $total1             =   $_POST['total1'];
  $proj_id           =   $_REQUEST['id'];
  $status             =   $_POST['status'];
  $project_title      =   $_POST['project_title'];
  $allocated_to       =   $_POST['allocatedto'];
  $p_manager          =   $_POST['p_manager'];
  $asignees1          =   $_POST['asignees'];
  $asignees           =   implode("#,#",$asignees1);
  $date               =   date('dmY',mktime());
  $invoice_num        =   $date.$key_val;
  $start_date         =   $_POST['start_date'];
  if(($start_date!='0') && ($start_date!=''))
   {
       $start_date  =   explode("/",$start_date);
       $start_date  =   mktime(0,0,0,$start_date[0],$start_date[1],$start_date[2]);
   }
    $end_date         =   $_POST['end_date'];
  if(($end_date!='0') && ($end_date!=''))
   {
       $end_date  =   explode("/",$end_date);
       $end_date  =   mktime(0,0,0,$end_date[0],$end_date[1],$end_date[2]);
   }
	 $query="UPDATE project SET 
					   status='$status',
					   project_title='$project_title',
					   allocated_to='$allocated_to',
					   p_manager='$p_manager',
             asignees='#$asignees#',
					   start_date='$start_date',end_date='$end_date' WHERE id=$proj_id"; 
	if ($db->query($query)){
		if($status=='F'){
			$sql_chk = "SELECT * FROM invoice WHERE job_number='$job_number'";
			$db1->query($sql_chk)or die(mysql_error());
			
			if(!$db1->num_rows()){                   
				$insert="INSERT INTO invoice SET
							job_number='$job_number',
							project_title='$project_title',
							business_name='$business_name',
							price='$total1',
							invoice_num='$invoice_num',
							status='U',
							createtime='".mktime()."'";
				$db2->query($insert);
			
				//Updating Invoice Value
				$update="update setting set key_value=key_value+1 where key_name='last_invoice_no'";
				$db1->query($update);
			}
		}                                  
	}
               
}                                    

function setProductDocument($db,$project_id)
{ 
  global $GALLERY_IMG;
 
 $total_doc          =  count($_FILES['doc_file']['name']); 
 for($count = 0; $total_doc > $count; $count++)
 {   
 if($_FILES['doc_file']['name'][$count]!='' && $_FILES['doc_file']['size'][$count]!='')
       {
               $doc_file   =   $_FILES['doc_file']['name'][$count]; 
               $doc_file_size = $_FILES['doc_file']['size'][$count];
               $ext          =   getExtension($doc_file);
               $target       =   $GALLERY_IMG."/".$doc_file;
               if(file_exists($target))
               {
                 $doc_file = time()."_".rand(100,99999).".".$ext;
                 $target       =   $GALLERY_IMG."/".$doc_file;
               }
              copy($_FILES['doc_file']['tmp_name'][$count],$target);  
       }
       else
       { $doc_file=''; }  
        
    if($doc_file)
    {
     $insert = "Insert into project_jobfile set
                                            project_id   = '$project_id',
                                            name        = '$doc_file',
											size='$doc_file_size', 
											createtime='".mktime()."',modifytime='".mktime()."'";
    $db->query($insert);                                        
    }                                            
  }     
}

function Get_CheckLists($db,$db1)
{
    global $SITE_URL,$fancy_script;
    $select1 ="select sales_rep from project where id='".$_REQUEST['id']."'";
    $db->query($select1);
    if($db->num_rows())
     {
        $row1 =$db->fetch_assoc();
       $salesrep= getEmployeeListName($db1,$row1['sales_rep']);
     }
   $select="select id,chk_list_name,hours,add_date from project_prod_chkList where project_id='".$_REQUEST['id']."'";
   $db->query($select);
   if($db->num_rows())
   {  $loop=1;
      while($row =$db->fetch_assoc())
      {
        $chk_id = $row['id'];
         $add_date =date('m-d-Y',strtotime($row['add_date']));
        $CHECKLISTS .="<tr><td> $row[chk_list_name]</td>                                         
                                          <td>$salesrep</td>                                         
                                          <td>$row[hours]</td>                                          
                                          <td>$add_date</td>                                          
                                          <td><a href=\"$SITE_URL/client/Project_CheckList.php?id=$chk_id&project=$_REQUEST[id]\" class=\"detail_checklist\">Detail</a></td>
                                          
                                      </tr>";
                                      
                                      
      
                                      
        $loop++;                               
      }
        $fancy_script .="$(\".detail_checklist\").fancybox({
        'width'				: 590,
				'height'			: 400,
				'autoScale'			: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe'
			});";
   }
   
   return $CHECKLISTS;
}

function getComments($db,$db1,$db2,$db3,$db4)
{   
    global $SITE_URL,$DIR_DISPLAY_EMPLOYEE_FILE,$MEMBER_ID;
		    
    $query="select * from project_comment where (comment_for='".$_REQUEST["id"]."') order by date asc";   
    $db->query($query);	
		if($db->num_rows())
		{	             
      while($row=$db->fetch_array())
      {  
         $id                    =   $row['id'];
         $comment               =   nl2br($row['comment']);
         $comment_by            =   $row['comment_by'];
         $comment_for            =   $row['comment_for'];         
         $c_type                =   $row['c_type'];
         $date1                 =   $row['date'];
         $date                  =  date('d.m.Y @ h:i a',$date1);
         
         $select="select emp_login_id,picture from employee where emp_login_id='$comment_by'";
         $db1->query($select);
         $rec=$db1->fetch_assoc();       
         $picture = $rec['picture'];
         $filenname_path        =   $DIR_DISPLAY_EMPLOYEE_FILE."/".$picture;
         
        if($picture!='')
         { $picture_file="<img src=\"$SITE_URL/image.php/$picture?width=33&amp;height=50&amp;cropratio=33:50&amp;image=$filenname_path\"  />"; }
         else
         {
          $picture_file="<img src=\"$SITE_URL/images/img_profile.gif\" alt=\"\" width=\"70\" />";
         }
         
         $select2="select username from login_info where id='$comment_by'";
         $db2->query($select2);
         $rec2=$db2->fetch_assoc();
         $username = $rec2['username'];
               
         if($comment_by == $MEMBER_ID || $comment_for == $MEMBER_ID)
         {                                                                                         
           $delete_image="<input type=\"image\" src=\"$SITE_URL/images/icon_delete.gif\" onclick=\"deleteProjectComment($id);\" style='border:0; width:15px;' />";
         }
         else
           $delete_image="";
              
           $COMMENT_LIST .="<div class=\"block_comment\" id=\"sn_disp_$id\">
                          	<div class=\"comment_left\">
                          		$picture_file
                          	</div>
                              <div class=\"comment_right\">
                              	<div class=\"heading_comment\">$username</div>
                                  <p>$comment<span style='float:right; border:0; margin-right:100px;'>$delete_image</span></p>
                                  <ul>
                                      <li>$date</li>
                                      <li><img src=\"images/icon_friend.gif\" alt=\"\" align=\"absmiddle\" /></li>
                                  </ul><div id =\"sn_doc\">$UPLOADED_DOCUMENT</div>
                                   </div></div>";
               } 
      return $COMMENT_LIST;      
    }   
}
function generateInvoiceNo($db)
{      
 $select="select key_value from setting where key_name='last_invoice_no'" ;
$db->query($select);
        
        if($db->num_rows() > 0)
        {  
        $rec = $db->fetch_assoc();
        $key_value     =   $rec['key_value'];  
        }
   return  $key_value;
}

function Get_Timesheet($db,$db1,$id)
{
    global $total_time;
    $select ="SELECT add_date,StartTime,added_by,Description,duration FROM jqcalendar WHERE project_id='$id'";
    $db->query($select);
    if($db->num_rows())
    {
         $TIMESHEET="<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                        <tr>
                          <td class=\"green\" width=\"55\" height=\"20\">Date</td>
                          <td class=\"green\" width=\"50\">Start</td>
                          <td class=\"green\" width=\"65\">User</td>
                          <td class=\"green\" width=\"90\">Comment</td>
                          <td class=\"green\" width=\"30\">Time</td>
                        </tr>";
        while($row=$db->fetch_assoc())
        {
            $starttime =  date('H:i a',strtotime($row['StartTime']));
            $user      =  getEmployeeListName($db1,$row['added_by']) ;
            
            $duration1  = secondsToTime($row['duration']);
            $duration   = $duration1['h'].":".$duration1['m'];
            $dura +=  $row['duration'];
            $TIMESHEET .="<tr>
                           <td height=\"20\">".date('m/d/Y',$row['add_date'])."</td>
                           <td>$starttime</td>
                           <td>$user</td>
                           <td>$row[Description]</td>
                           <td>$duration</td>
                         </tr>";
        }
        
          $TIMESHEET .="</table>";
            $dura1  = secondsToTime($dura);
            $total_dura   = $dura1['h'].":".$dura1['m'];
        $total_time="<div align=\"right\"><strong> Total Time: &nbsp; $total_dura</strong>&nbsp;</div>";  
    }
    else
    {
       $TIMESHEET ="<div style=\"margin-left:104px;padding-top:38px;\"><strong> Sorry , No Data Available !</strong></div>"; 
    }
    return $TIMESHEET;
    
}


?>                      