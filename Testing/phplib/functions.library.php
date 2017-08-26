<?php     
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
 $db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
    $session_user= $_SESSION["SESS_MEMBER_ID"];
// Function		: copyImage 
function copyImage($fileLocation,$userfile){
  if($userfile['name']){

	$filename			= $userfile['name'];
	$filename_splits	= explode(".", $filename);
	$extension_element	= count($filename_splits) - 1;
	$extension			= strtolower($filename_splits[$extension_element]);
	$filename			= time()."_".rand(100, 99999).".".$extension;

	$target				= "$fileLocation/$filename";
	$filename2			= $filename;

	while (file_exists("$target"))
	{
		$random_number = time()."_".rand(10000, 99999);
		$filename = $random_number."_".$filename2;
		$target = "$fileLocation/$filename";
	}

    @chmod($fileLocation, 0777);
    move_uploaded_file($userfile['tmp_name'], $target); 
    @chmod($target, 0777);
    
    } 
    return $filename;
}


#-------------------------------------------------------------
// Function		: isUserLoggedOut
// Description	: this is to check that user is logged in or not if not logged in then send to login page .
function isUserLoggedOut(){
	global $SITE_URL;
	if($_SESSION['SESS_v_userLoggedIn']	!= '1' || $_SESSION['SESS_i_id']	== '' || $_SESSION['SESS_v_email']	== ''){      
      header("Location: $SITE_URL/signup/login.php");
			exit;
  }else{
    global $SESS_v_slogin;
    $SESS_v_slogin  =    $_SESSION['SESS_v_slogin'];
  }
}








function encode($val){
$val = base64_encode($val);
return $val;

}

function decode($val){
$val = base64_decode($val);
return $val;
}


#-------------------------------------------------------------
// Function	: ReadTemplate
// Description	: Reads Template File and return the Content of the File 

function ReadTemplate($fileName) {
	$fd = fopen($fileName, "r");
	return fread($fd, filesize($fileName));
}

#-------------------------------------------------------------
// Function	: RestoreData
// Description	: Restore Posted Data to its Origianl Form. Must be called before sending back posted data to the browser
function RestoreData() {
	global $HTTP_POST_VARS, $HTTP_GET_VARS;
	foreach($HTTP_POST_VARS as $var=>$value) {
	 	global $$var;
		$$var = stripslashes($value);
	}
	foreach($HTTP_GET_VARS as $var=>$value) {
		global $$var;
		$$var = stripslashes($value);
	}

}

#-------------------------------------------------------------
// Function	: ReplaceContent
// Description	: Replace Content in Templates with Equivalent Variables

function ReplaceContent($VarList) {
	for($i=0; $i<count($VarList); $i++) {
		global $$VarList[$i];
		$$VarList[$i] = preg_replace("/__(\w+)__/e","\$GLOBALS['$1']",$$VarList[$i]);
	}
	return 1;
	// For Future Refrence :  $RIGHT_HOME_CONTENT=preg_replace("/__(\w+)__/e","$$1",$RIGHT_HOME_CONTENT);
}

#-------------------------------------------------------------
// Function	: placeScripts
// Description	: Replace Content in Templates with Equivalent Variables

function placeScripts($ScriptList) {
	global $SCRIPTS;
	$SCRIPTS = "";
	for($i=0; $i<count($ScriptList); $i++) {
		$SCRIPTS .= "<script language=JavaScript src=\"".$ScriptList[$i]."\"></script>\n";
	}
	return 1;
}









function getHandyFilesize($fsize) {	
	$file_size = "0 Byte";
	if ($fsize < 1024) {
		$file_size = "$fsize Bytes";
	}
	elseif ($fsize < 1048576) { //1048576 = 1024*1024
		$file_size = ($fsize / 1024);
		$file_size = number_format($file_size, 2, '.', '') . " KB";
	}
	else {
		$file_size = ($fsize / (1024*1024));
		$file_size = number_format($file_size, 2, '.', '') . " MB";
	}
	return $file_size;
}



function prepareDateLists($year, $month, $day, $start_offset = 100, $end_offset = 18) {
	global $YEAR_LIST, $MONTH_LIST, $DATE_LIST;
	$YEAR_LIST = $MONTH_LIST = $DATE_LIST = "";
	$start_year = date("Y") - $start_offset;
	$end_year = date("Y")- $end_offset;

	$ARR_MONTH = array(
		"01"=>"Jan",
		"02"=>"Feb",
		"03"=>"Mar",
		"04"=>"Apr",
		"05"=>"May",
		"06"=>"Jun",
		"07"=>"Jul",
		"08"=>"Aug",
		"09"=>"Sep",
		"10"=>"Oct",
		"11"=>"Nov",
		"12"=>"Dec"
		);

	while($start_year <= $end_year)	{
		if ($start_year == $year) {
			$YEAR_LIST.= "<OPTION VALUE='$start_year' SELECTED>$start_year</OPTION>\n";
		}
		else {
			$YEAR_LIST.= "<OPTION VALUE='$start_year'>$start_year</OPTION>\n";
		}
		$start_year++;
	}
	
	$i = 1;
	
	while($i <= 31) {
		if ($i == $day)	{
			$DATE_LIST.= "<OPTION VALUE='$i' SELECTED>$i</OPTION>\n";
		}
		else {
			$DATE_LIST.= "<OPTION VALUE='$i'>$i</OPTION>\n";
		}
		$i++;
	}

	foreach($ARR_MONTH as $key=>$value) {
		if ($key == $month) {
			$MONTH_LIST.="<OPTION VALUE = '$key' SELECTED>$value</OPTION>\n";
		}
		else {
			$MONTH_LIST.="<OPTION VALUE = '$key'>$value</OPTION>\n";
		}
	}
}
function getStatus($status)
{
    $statusArr= array(
    'A'=>'Active'
    ,'L'=>'Live'
    ,'P'=>'Pending'
    ,'S'=>'Suspend'
    ,'D'=>'Draft'
    ,'DN'=>'Denied'
    ,'AP'=>'Approved'
    ,'F'=>'Featured'
    );
    
    return $statusArr[$status];
}

function createUserPassword()
{	$PASSWORD_LENGTH = 8;
	$ALLOWED_CHARS = "abcdefghijklmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ123456789";

	$CHARS_LEN = strlen($ALLOWED_CHARS);

	$PASSWORD = "";
	for($i = 0; $i < $PASSWORD_LENGTH; $i++)
	{	$random_pos = rand(0, $CHARS_LEN - 1);
		$PASSWORD .= substr($ALLOWED_CHARS, $random_pos, 1);
	}

	return $PASSWORD;
}


function paginate($limit=10, $tot_rows){
	global $TOTAL_PAGES, $pagination;
	$numrows = $tot_rows;
	$darkpink = "<div class=\"pagination\">";
	if($numrows > $limit){
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}else{
			$page = 1;
		}
		
		$currpage = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'].$pagination;
		$currpage = str_replace("&page=".$page,"",$currpage);

		if($page == 1){
			//$darkpink .= "<span class=\"darkpink\">&lt; PREV</span>";
		}else{
			$pageprev = $page - 1;
			$darkpink .= "<a class=\"paging\" href=\"" . $currpage . "&page=". $pageprev . "\"><</a>";
		}

		$numofpages = ceil($numrows / $limit);
		$TOTAL_PAGES = $numofpages;
		$range = 4;
	  $lrange = max(1, $page-(($range-1)/2));
		$rrange = min($numofpages, $page+(($range-1)/2));
		if(($rrange - $lrange) < ($range - 1)){
			if($lrange == 1){
				$rrange = min($lrange + ($range-1), $numofpages);
			}else{
			$lrange = max($rrange - ($range-1), 0);
			}
		}
		
		if($lrange > 4){   
		  $secfirst = 2;
			$darkpink .= "&nbsp;<a class=\"paging-active\" href='$currpage&page=1'>1</a>&nbsp;";
			$darkpink .= "<a class='darkpink' href='$currpage&page=$secfirst'>$secfirst</a>&nbsp;";
			$darkpink .= " .. ";
		}else{
			$darkpink .= " &nbsp;";
		}
		for($i = 1; $i <= $numofpages; $i++){
			if($i == $page){
				$darkpink .= "<a href=\"javascript:void();\" class=\"paging-active\">$i</a> ";
			}else{
				if($lrange <= $i && $i <= $rrange){
					$darkpink .= " <a  href=\"".$currpage."&page=".$i."\" class=\"paging\">$i</a> ";
				}
			}
		}
		
		if($rrange < $numofpages-3){
		$seclast = $numofpages -1;
			$darkpink .= " .. ";
			$darkpink .= "<a  href='$currpage&page=$seclast' class=\"paging\">$seclast</a>&nbsp;";
			$darkpink .= "<a  href='$currpage&page=$numofpages' class=\"paging\">$numofpages</a>&nbsp;";
		}else{
			$darkpink .= " &nbsp;&nbsp;";
		}

		if(($numrows - ($limit * $page)) > 0){
			$pagenext = $page + 1;
			$darkpink .= "<a href=\"". $currpage . "&page=" . $pagenext . "\" class=\"paging\" >></a>";
		}else{
		//	$darkpink .= "<span class=\"txt\">NEXT &gt;</span>";
		} 
	}
  $darkpink .="</div>";
return $darkpink;
}

function paginateFront($limit=10, $tot_rows,$p_type='',$search=''){
global $TOTAL_PAGES, $pagination;
	$numrows = $tot_rows;
	//$darkpink = "<div class=\"txt\">";
	if($numrows > $limit){
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}else{
			$page = 1;
		}
		 
    $currpage = $_SERVER['REQUEST_URI'] . "?" . $_SERVER['QUERY_STRING'].$pagination;   
		$currpage = str_replace("&page=".$page,"",$currpage);
    
		if($page == 1){
			//$darkpink .= "<span class=\"darkpink\">&lt; PREV</span>";
		}else{
			$pageprev = $page - 1;
			$darkpink .= "<a class=\"paging\" href=\"" . $currpage . "&page=". $pageprev . "\">&laquo;</a>";
		}

		$numofpages = ceil($numrows / $limit);
		$TOTAL_PAGES = $numofpages;
		$range = 4;
	  $lrange = max(1, $page-(($range-1)/2));
		$rrange = min($numofpages, $page+(($range-1)/2));
		if(($rrange - $lrange) < ($range - 1)){
			if($lrange == 1){
				$rrange = min($lrange + ($range-1), $numofpages);
			}else{
			$lrange = max($rrange - ($range-1), 0);
			}
		}
		
		if($lrange > 4){   
		  $secfirst = 2;
			$darkpink .= "&nbsp;<a class=\"paging-active\" href='$currpage&page=1'>1</a>&nbsp;";
			$darkpink .= "<a class='darkpink' href='$currpage&page=$secfirst'>$secfirst</a>&nbsp;";
			$darkpink .= " .. ";
		}else{
			$darkpink .= " &nbsp;";
		}
		for($i = 1; $i <= $numofpages; $i++){
			if($i == $page){
				$darkpink .= "<a href=\"javascript:void();\" class=\"paging-active\">$i</a> ";
			}else{
				if($lrange <= $i && $i <= $rrange){
					$darkpink .= " <a  href=\"".$currpage."&page=".$i."\" class=\"paging\">$i</a> ";
				}
			}
		}
		
		if($rrange < $numofpages-3){
		$seclast = $numofpages -1;
			$darkpink .= " .. ";
			$darkpink .= "<a  href='$currpage&page=$seclast' class=\"paging\">$seclast</a>&nbsp;";
			$darkpink .= "<a  href='$currpage&page=$numofpages' class=\"paging\">$numofpages</a>&nbsp;";
		}else{
			$darkpink .= " &nbsp;&nbsp; ";
		}

		if(($numrows - ($limit * $page)) > 0){
			$pagenext = $page + 1;
			$darkpink .= "<a href=\"". $currpage . "&page=" . $pagenext . "\" class=\"paging\" >&raquo;</a>";
		}else{
		//$darkpink .= "<span class=\"txt\">NEXT &gt;</span>";
		}
	}
return $darkpink;
	}
 
function display_message()
{ 
  global $PROMPT,$PROMPT_CLASS;
  if($_SESSION['sess_msg']!='')
  {
    $PROMPT=$_SESSION['sess_msg'];
    $PROMPT_CLASS=$_SESSION['sess_class'];
  }
  $_SESSION['sess_msg']=""; 
  $_SESSION['sess_class']=""; 
}


function checkLogin()
{
	if(!isset($_SESSION['SESS_MEMBER_ID']))
	{
	  	header("Location:./login.php");
		exit;
	}
	
}

function checkAdminId($db)
{
global $displayEmp,$displayOrder,$displayCustomer,$displayCost,$displayProduct; 

	if($_SESSION['SESS_MEMBER_ID']==1)
	{
	  	$displayEmp='';
		}
	else
	{
	$displayEmp='style="display:none"';
	 $select = "select * from  login_info  where id='$_SESSION[SESS_MEMBER_ID]'"; 
	  $db->query($select);
        
        if($db->num_rows() > 0)
        {   
		  $rec = $db->fetch_assoc();
               $id                  =   $rec['id'];
			  $access_perm          =   $rec['permitted_area'];
		
		  $options = array(1,2,3,4);
//print_r($options);
    $selectedOption = explode(",",$rec['permitted_area']);
	 $value1=$selectedOption[0];
     $value2=$selectedOption[1];
	 $value3=$selectedOption[2];
	 $value4=$selectedOption[3];
	
	if($value1!='1' && $value2!='1' && $value3!='1' && $value4!='1')
	{
	$displayOrder='style="display:none"';
	}
	 if($value1!='2' && $value2!='2' && $value3!='2' && $value4!='2')
	{
	$displayCustomer='style="display:none"';
	}
 if($value1!='3' && $value2!='3' && $value3!='3' && $value4!='3')
	{
	$displayCost='style="display:none"';
	}
	 if($value1!='4' && $value2!='4' && $value3!='4' && $value4!='4')
	{
	$displayProduct='style="display:none"';
	}
	/*else
	{
	$displayOrder='';
	$displayCustomer='';
	$displayCost='';
	$displayProduct='';
	}
	*/
	
		
		
		}
	 
	}
}
checkAdminId($db);



function checkLoginRoot(){
	if(isset($_SESSION['SESS_MEMBER_ID'])){
	  	header("Location: dashboard.php");
	}	
}


function showLogin(){
	global $logout,$SITE_URL,$login;
	if(isset($_SESSION['SESS_MEMBER_ID'])){
		$userDetail = getUserDetail($_SESSION['SESS_MEMBER_ID']);
		$login  = "<li><a href=\"#\">Manage Account</a>
                    	<ul>";
		if($_SESSION["SESS_ACCESS_LEVEL"]==0){				
                $login  .= "<li><a href=\"$SITE_URL/add_user.php\">Add User</a></li>
                            <li><a href=\"$SITE_URL/manage_user.php\">Manage User</a></li>
							<li><a href=\"$SITE_URL/manage_co_profile.php\">Company Profile</a></li>";
		}		
				$login  .= "<li><a href=\"$SITE_URL/update_profile.php\">Update Profile</a></li>
                            <li><a href=\"$SITE_URL/change_password.php\">Change Password</a></li>
                        </ul>
                    </li>";
		$logout = "<li><a href='logout.php'>Logout</a></li>
				   <li class=\"homeIcon\"><a href=\"$SITE_URL/dashboard.php\">Home</a></li>";
	}else{
		$logout = " <li><a href='register.php'>Create Profile</a></li>
					<li><a href='login.php'>Login</a></li>
					<li class=\"homeIcon\"><a href=\"$SITE_URL/login.php\">Home</a></li>";
	}
}


function getAlphaSorting($selword='',$p_id='')
{      
      if($p_id)
      $p_id="&p_id=$p_id";
      
      $SORTING_ALPHA="";
      $PHP_SELF = $_SERVER['PHP_SELF'];
      if($selword=='')
          $SORTING_ALPHA ="<a href=\"$PHP_SELF\" class=\"alpha-list-active\">All</a>";
      else
          $SORTING_ALPHA ="<a href=\"$PHP_SELF\" class=\"alpha-list\">All</a>";      
      foreach (range('a', 'z') as $char)
       {
            $word = trim($char . PHP_EOL);
            
            if($selword==$word)
            {
                  $SORTING_ALPHA .="<a href=\"$PHP_SELF?char=$word$p_id\" class=\"alpha-list-active\">".strtoupper($word)."</a>";
            }
            else
            {
                  $SORTING_ALPHA .="<a href=\"$PHP_SELF?char=$word$p_id\" class=\"alpha-list\">".strtoupper($word)."</a>";
            }
            $SORTING_ALPHA .="\n";
            
      }
      return $SORTING_ALPHA;
}

//fUNCTION GET SEARCH FORM

function getSearchForm()
{
      global $SITE_URL;
      $SEARCH_FORM ="<h2>Search</h2>
          		<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"grayborder gray-module-content\">
          			<tr>
              			<td>&nbsp;</td>
              		</tr>
              		<tr>
              			<td valign=\"top\" align=\"center\"><input type=\"text\" name=\"keyword\" class=\"s-istyle\" size=\"50\" value=\"Enter Title\" onclick=\"this.value=''\" style=\"width:300px;\" /><input type=\"image\" name=\"SEARCH\" src=\"$SITE_URL/images/bt-search.gif\"  align=\"absmiddle\" /></td>
              		</tr>
              		<tr>
              			<td>&nbsp;</td>
              		</tr>
           		</table>";
           		
   return  $SEARCH_FORM;       		
}

//Function Get Filtering Form 

function getFilterForm()
{
   GLOBAL $SITE_URL;
    $FILTER_FORM = "<h2>Filtering</h2>
          		<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"grayborder gray-module-content\">
          			<tr>
              			<td>&nbsp;</td>
              		</tr>
              		<tr>
              			<td align=\"center\"><span class=\"search-head\">Status</span> <select gtbfieldid=\"21\"  name=\"status\" class=\"s-istyle\">
                              <option value=\"\">-- Show All --</option>
                              <option value=\"L\">Live</option><option value=\"S\">Suspend</option>
		</select>
                          <span class=\"search-head\">Display Order</span> <select gtbfieldid=\"22\" class=\"s-istyle\" name=\"order\">
                          	<option value=\"asc\">Ascending</option>
                              <option value=\"desc\" >Descending</option>
		</select>
                          <input type=\"image\" name=\"FILTER\" src=\"$SITE_URL/images/bt-sort.gif\" align=\"absmiddle\" /></td>
              		</tr>
              		<tr>
              			<td>&nbsp;</td>
              		</tr>
           		</table>
                  ";
    return $FILTER_FORM;              
}


function getSeoUrl($title)
{
      $char_arr     = array("@","'","%"," ","/","!","#","$","^","&","*","(",")","?",">","<"," ","  ", "   "); 
      $title        = trim($title);
      $title        = strtolower(strip_tags(stripslashes($title)));
    
      $url          = str_replace($char_arr,'_',$title);
      return $url;
}
function deleteFile($file)
{
	if(file_exists($file))
		unlink($file);
}






function sendMail($toEmail,$subject,$mail_body,$from_email='',$from_name='')
{
	require_once("phpmailer/class.phpmailer.php");
  $mail =new PHPMailer();
	$mail->From     = $from_email;
	$mail->FromName     = $from_name;
	$mail->Subject     = $subject;
	//$mail->WordWrap  = 80;

	$message =$mail_body;
	$mail->Body = $mail_body;
	$mail->IsHTML(true);

	if (!empty($toEmail))
	{
		$mail->AddAddress($toEmail);
		if(!$mail->Send())
		{
		}
  }
}







 




function getExtension($filename)
{
          $filename = strtolower($filename) ;
          $exts = explode('.',$filename) ;    
          $n = count($exts)-1;
          $exts = $exts[$n];
          
          return  $exts;
}
/****************************************New Functions Start Here**********************/
 

function validateLogin()
{
	global $SITE_URL;
	if(empty($_SESSION["SESS_v_adminLoggedIn"]))
	{

		$_SESSION['sess_msg'] = "You are not authorized to access that location!";
		$_SESSION['sess_class']='error';
		header ("Location: $SITE_URL/cms/index.php"); 		
		exit;
	}
}

function format_phone($phone)
{
 $phone = preg_replace("/[^0-9]/", "", $phone);
 if(strlen($phone) == 7)
 return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
 elseif(strlen($phone) == 10)
 return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
 else
 return $phone;
}
 


function getPageMetaTag($id)
{
 global $HEADER_TILE,$HEADER_SUBTILE, $FRONT_HEADER_TILE;
 global $DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN;
 $db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
 $db1->open() or die($db1->error());
 
 global $meta_title,$meta_desc,$meta_keyword,$meta_author;
 $select = "select * from pages where id='$id'";
 $db1->query($select);
 if($db1->num_rows())
 {
  $rec = $db1->fetch_assoc();
  $meta_title    = $rec['meta_title'];
  $meta_desc     = $rec['meta_desc'];
  $meta_keyword  = $rec['meta_keyword'];
  $meta_author   = $rec['meta_author'];
 }
 else
 $meta_title     = "Raw Online";
}
function getPageContent($db,$id)
{
 global $SITE_URL,$PAGE_DESCRIPTION,$TITLE;
 $query = "select * from pages where id='$id' and status='L'";
 $db->query($query);
 if($db->num_rows())
 {
  while($rec = $db->fetch_array())
  {
   $id    = $rec['id'];
   $PAGE_DESCRIPTION=stripslashes($rec['description']);
   $TITLE = stripslashes($rec['title']);
  }                    
 }
 else
 {$PAGE_DESCRIPTION="Coming Soon...";
  }                      
}
function cutText($value, $length)
{   
    if(is_array($value)) list($string, $match_to) = $value;
    else { $string = $value; $match_to = $value{0}; }

    $match_start = stristr($string, $match_to);
    $match_compute = strlen($string) - strlen($match_start);

    if (strlen($string) > $length)
    {
        if ($match_compute < ($length - strlen($match_to)))
        {
            $pre_string = substr($string, 0, $length);
            $pos_end = strrpos($pre_string, " ");
            if($pos_end === false) $string = $pre_string."...";
            else $string = substr($pre_string, 0, $pos_end)."...";
        }
        else if ($match_compute > (strlen($string) - ($length - strlen($match_to))))
        {
            $pre_string = substr($string, (strlen($string) - ($length - strlen($match_to))));
            $pos_start = strpos($pre_string, " ");
            $string = "...".substr($pre_string, $pos_start);
            if($pos_start === false) $string = "...".$pre_string;
            else $string = "...".substr($pre_string, $pos_start);
        }
        else
        {       
            $pre_string = substr($string, ($match_compute - round(($length / 3))), $length);
            $pos_start = strpos($pre_string, " "); $pos_end = strrpos($pre_string, " ");
            $string = "...".substr($pre_string, $pos_start, $pos_end)."...";
            if($pos_start === false && $pos_end === false) $string = "...".$pre_string."...";
            else $string = "...".substr($pre_string, $pos_start, $pos_end)."...";
        }

        $match_start = stristr($string, $match_to);
        $match_compute = strlen($string) - strlen($match_start);
    }
   
    return $string;
} 





function getLastLogin($db,$pid)
{
 global $SITE_URL;
    //echo $pid;
  $query ="select lastlogin from admins where parent_id='$pid'";
 $db->query($query);
 if($db->num_rows())
 {
 $rec = $db->fetch_array();
 $lastlogin1    =  $rec['lastlogin'];
 $lastlogin     =  date('d.m.Y @ h:i a',$lastlogin1);
 if($lastlogin1=='0')
 {
 $lastlogin= "-";
 }
 return $lastlogin;
 } 
 else 
 {
  $lastlogin= "-";
 return $lastlogin;        
 }
} 


function setLogRecord($action="")
{
  global  $DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME,$DB_REPORT_ERROR,$DB_PERSISTENT_CONN;
  $db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
  $db->open() or die($db->error());

     $sess_id=session_id();
     $userid=$_SESSION['SESS_MEMBER_ID'];
     $query="insert into log set
                user_id='$userid',
                action_time=NOW(),
                session='$sess_id',
                action='$action'";

         $db->query($query); 


}

/*function updateLogRecord($db)
{
     $sess_id=session_id();
     $query="update log set action_close=NOW() where session_id=$sess_id";

         $db->query($query); 


} */


  
  
function sendMail2($toEmail,$subject,$mail_body,$from_email='',$from_name='')
{	require_once("phpmailer/class.phpmailer.php");
  $mail = new PHPMailer();
  $mail->IsSMTP();                           // set mailer to use SMTP
  $mail->Host = "mail.brainworkindia.net";  // specify main and backup server
  $mail->SMTPAuth = true;     // turn on SMTP authentication
  $mail->Username = "dheeraj@brainworkindia.net";  // SMTP username
  $mail->Password = "12dheeraj34"; // SMTP password
  //$mail->AddAddress($EMAILID);
  $mail->From        = $from_email;
  $mail->FromName    = "RAW CRM";
  $mail->Subject = $subject;
  $mail->IsHTML(true);
  $mail->Body =$mail_body;   
  //$mail->Send(); 
	if (!empty($toEmail))
	{
		$mail->AddAddress($toEmail);
		 if($mail->Send())
      { $PROMPT = "Your Account Password has been forwarded to your Email Account."; }
  }
} 





function downloadFile($file) 
{
	$filename=basename($file);
	$filename_splits = explode(".", $filename);
	$extension_element = count($filename_splits) - 1;
	$file_extension = strtolower($filename_splits[$extension_element]);
	switch( $file_extension )
	{ 
		case "pdf": $ctype="application/pdf"; break;
		case "exe": $ctype="application/octet-stream"; break;
		case "zip": $ctype="application/zip"; break;
		case "doc": $ctype="application/msword"; break;
		case "xls": $ctype="application/vnd.ms-excel"; break;
		case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
		case "gif": $ctype="image/gif"; break;
		case "png": $ctype="image/png"; break;
		case "jpeg":
		case "jpg": $ctype="image/jpg"; break;
		default: $ctype="application/force-download";
	}
	
	ob_end_clean(); 
	ini_set('zlib.output_compression','Off');
	$baseName=basename($file);
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers 
	header("Content-Type: $ctype");
	header('Content-Disposition: attachment; filename="'.$baseName.'"' );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($file));
	readfile($file);
	exit();
}
 // 1=> comment ,2 => call , 3=>meeting , 4 =>sales ,5=>project ,6=>invoices
$ARR_COMMENT_TYPE_DROPLIST= array(
  '1' => '#84a9fa',
  '2' => '#eeece9',
  '3' => '#d9dee8',
  '4' => '#c8d0f1',
  '5' => '#dcebd5',
  '6' => '#eda457'
);

/* Function for creating Dropdoen option*/
function createDropDown($arr,$select_opt="",$options=""){
	$dd_var='';
	foreach($arr as $key=>$value){
		if($key==$select_opt){
			$dd_var .= "<option value='$key' selected='selected' $options>$value</option>";	
		}else{
			$dd_var .= "<option value='$key' $options>$value</option>";	
		}
	}
	return $dd_var;
}










function GetFileIcon($file_name,$wi_s=50,$hi_s=50){
	global $ICON_ARR,$SITE_URL;

	$file_detail = pathinfo($file_name);
	if(array_key_exists($file_detail['extension'],$ICON_ARR)){
		$file = $ICON_ARR[$file_detail['extension']];
		$uploaded_page_image_aa = "<img src=\"$SITE_URL/image.php/$file?width=$wi_s&amp;height=$hi_s&amp;image=$SITE_URL/images/$file\" align='absmiddle' />";
	}
	
	return $uploaded_page_image_aa;	
}

function refreshPage(){
	$cur_page = $_SERVER['REQUEST_URI'];
	header("Location: $cur_page");	
}




function js2PhpTime($jsdate){
  if(preg_match('@(\d+)/(\d+)/(\d+)\s+(\d+):(\d+)@', $jsdate, $matches)==1){
    $ret = mktime($matches[4], $matches[5], 0, $matches[1], $matches[2], $matches[3]);
    //echo $matches[4] ."-". $matches[5] ."-". 0  ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
  }else if(preg_match('@(\d+)/(\d+)/(\d+)@', $jsdate, $matches)==1){
    $ret = mktime(0, 0, 0, $matches[1], $matches[2], $matches[3]);
    //echo 0 ."-". 0 ."-". 0 ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
  }
  return $ret;
}

function php2JsTime($phpDate){
    //echo $phpDate;
    //return "/Date(" . $phpDate*1000 . ")/";
    return date("m/d/Y H:i", $phpDate);
}

function php2MySqlTime($phpDate){
    return date("Y-m-d H:i:s", $phpDate);
}

function mySql2PhpTime($sqlDate){
    $arr = date_parse($sqlDate);
    return mktime($arr["hour"],$arr["minute"],$arr["second"],$arr["month"],$arr["day"],$arr["year"]);

}





function secondsToTime($seconds)

{
    // extract hours

    $hours = floor($seconds / (60 * 60));

 

    // extract minutes

    $divisor_for_minutes = $seconds % (60 * 60);

    $minutes = floor($divisor_for_minutes / 60);

 

    // extract the remaining seconds

    $divisor_for_seconds = $divisor_for_minutes % 60;

    $seconds = ceil($divisor_for_seconds);

 
    if($hours >= 0 && $hours < 10)
    $hours="0".$hours;
    if($minutes >= 0 && $minutes < 10)
    $minutes="0".$minutes;
    if($seconds >= 0 && $seconds < 10)
    $seconds="0".$seconds;
    // return the final array

    $obj = array(

        "h" =>  $hours,

        "m" =>  $minutes,

        "s" =>  $seconds,

    );
     
    return $obj;

}



function breadcrumbs($separator = ' &raquo; ', $home = 'Home'){
    
    global $db,$DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME,$DB_REPORT_ERROR,$DB_PERSISTENT_CONN;
    $db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
    $db->open() or die($db->error());

    $path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    $base_url = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    $breadcrumbs = array("<a href=\"$base_url\"><i class=\"iconfa-home\"></i> $home</a>");
 
    $last = end(array_keys($path));
 
    foreach ($path AS $x => $crumb) { 
        if($crumb!='index.php'){
        $title = ucwords(str_replace(array('.php', '_'), Array('', ' '), $crumb));
        if ($x != $last){
            $breadcrumbs[] = "<a href=\"$base_url$crumb\">$title</a>";
        }else{
            $breadcrumbs[] = $title;
         }
       } 
    }
 
    $breadcrumb=implode($separator, $breadcrumbs);
    
    $breadcrumb ="<ul class=\"breadcrumbs\">
                  <li><a href=\"dashboard.html\"></a> <span class=\"separator\"></span></li>
                  <li>$breadcrumb</li>
                  </ul>";
    return $breadcrumb;              
}
 $BREADCRUMBS=breadcrumbs($separator = ' &raquo; ', $home = 'Home');
function getRegionListUSer($db,$db1,$id=''){
	
	$sql = "SELECT * FROM region WHERE parent=0";
	$db->query($sql);
  while($row=$db->fetch_assoc())
  {  
       if($row['id']==$id)
         $EMP_REGION_parent .="<option value=\"$row[id]\" selected>$row[name]</option>";
      else
        $EMP_REGION_parent .="<option value=\"$row[id]\">$row[name]</option>"; 
        
       $sql1 = "SELECT * FROM region WHERE parent!=0 and parent='$row[id]'";
	     $db1->query($sql1);
        while($row1=$db1->fetch_assoc())
       {  
            if($row1['id']==$id)
               $EMP_REGION_parent .="<option value=\"$row1[id]\" selected>&nbsp;&nbsp;&nbsp;$row1[name]</option>";
            else
              $EMP_REGION_parent .="<option value=\"$row1[id]\">&nbsp;&nbsp;&nbsp;$row1[name]</option>"; 
           
       }
            
  }
  return  $EMP_REGION_parent;
}

function getRegionListName($db,$id=''){
	
	$sql = "SELECT * FROM region WHERE id=$id";
	$db->query($sql);
  $row=$db->fetch_assoc();
  $EMP_REGION_NAME= $row['name'];
      
  return  $EMP_REGION_NAME;
} 
function getWardlist($db,$id=''){
	
	$sql = "SELECT * FROM wards WHERE 1";
	$db->query($sql);
  while($row=$db->fetch_assoc())
  {  
       if($row['id']==$id)
         $WARD_LIST .="<option value=\"$row[id]\" selected>$row[ward_name]</option>";
      else
        $WARD_LIST .="<option value=\"$row[id]\">$row[ward_name]</option>"; 
            
  }
  return  $WARD_LIST;
}

function getWradListName($db,$id=''){
	
	$sql = "SELECT * FROM wards WHERE id=$id";
	$db->query($sql);
  $row=$db->fetch_assoc();
  $WARD_NAME= $row['ward_name'];
      
  return  $WARD_NAME;
} 
function getWardcategory($db,$id=''){
	
	//$sql = "SELECT * FROM ward_category WHERE 1"; MG 16/06/17 fix to order by ASC
	$sql = "SELECT * FROM ward_category ORDER BY category_name";

	$db->query($sql);
  while($row=$db->fetch_assoc())
  {  
       if($row['id']==$id)
         $CATE_LIST .="<option value=\"$row[id]\" selected>$row[category_name]</option>";
      else
        $CATE_LIST .="<option value=\"$row[id]\">$row[category_name]</option>"; 
            
  }
  return  $CATE_LIST;
}

function getWradCategoryName($db,$id=''){
	
	$sql = "SELECT * FROM ward_category WHERE id=$id";
	$db->query($sql);
  $row=$db->fetch_assoc();
  $CATEGORY_NAME= $row['category_name'];
      
  return  $CATEGORY_NAME;
} 

?>