<?php session_start(); 
 
include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions_client.library.php");
//include("$LIB_DIR/include.inc.php");

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$db1=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db1->open() or die($db1->error());
 
$db2=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db2->open() or die($db2->error());

$db3=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db3->open() or die($db3->error());

$MEMBER_NAME=$_SESSION["SESS_CLIENT_NAME"];


checkClientLogin();
$proj_id =$_REQUEST['project'];


$select ="select chk_list_name,hours from project_prod_chkList where id='".$_REQUEST['id']."' and project_id='".$_REQUEST['project']."'";
$db->query($select);
$row=$db->fetch_assoc();
$chk_list_name =  $row['chk_list_name'];
$hours =  $row['hours'];




$query ="select id,heading,complete from proj_prod_chkList_Head where chk_list_id='".$_REQUEST['id']."' and project_id='".$_REQUEST['project']."'";
$db->query($query);
if($db->num_rows())
{   
    while($row1=$db->fetch_assoc())
    {   $h_id =$row1['id'];
        if($row1['complete']=='1')
        {/*onclick=\"checked_head($row1[id]);\"*/
        $Heading .="<p class='main_title'><input type=\"checkbox\" name=\"head$h_id\" class=\"head head_".$row1['id']."\" value=\"$row1[id]\" id=\"".$row1['id']."\" checked=\"checked\"/><input type=\"hidden\" name=\"head_hid$h_id\" value=\"$row1[id]\"> $row1[heading]</p>";
        }
        else{
        $Heading .="<p class='main_title'><input type=\"checkbox\" name=\"head$h_id\" class=\"head head_".$row1['id']."\" id=\"".$row1['id']."\" value=\"$row1[id]\" /><input type=\"hidden\" name=\"head_hid$h_id\" value=\"$row1[id]\"> $row1[heading]</p>";
        }
        $sql ="select id,subheading,complete from proj_prod_chkList_subhead where head_id=$row1[id] and project_id='".$_REQUEST['project']."'";
        $db1->query($sql);
        if($db1->num_rows())
        {  
            while($row2=$db1->fetch_assoc())
            {
            $s_id=$row2['id'];
            $row2['subheading'];
             if($row2['subheading']!="")
                 {
                  if($row2['complete']=='1')
                  {
                  $Heading .="<p><input type=\"checkbox\" name=\"subhead$s_id\" class=\"sub_chkbox sub_".$row1['id']."\" value=\"$row2[id]\"   id=\"".$row1['id']."\" checked=\"checked\" /><input type=\"hidden\" name=\"sub_hid$s_id\" value=\"$row2[id]\"> $row2[subheading]</p>";
                  }
                  else
                  {
                  $Heading .="<p><input type=\"checkbox\" name=\"subhead$s_id\" class=\"sub_chkbox sub_".$row1['id']."\" value=\"$row2[id]\"  id=\"".$row1['id']."\" /><input type=\"hidden\" name=\"sub_hid$s_id\" value=\"$row2[id]\"> $row2[subheading]</p>";
                  }
                   
                 }
              else
                 {
                 $Heading .="";
                 }
                 
            }
        } 
        
    }
}

?>
<html>
  <head>
 
 
  <link href="<?php echo $SITE_URL; ?>/css/style.css" rel="stylesheet" type="text/css" />
  </head>  
  <body style='background:#F9F9F9;' class="project_checkleft">
  <form action="" method="post">
 <div style="border-bottom:1px solid #609A12;">
 <table cellspadding="0" cellspacing="0">
    <tr>
      <td width="150"><b>Checklist Name:</b></td><td><?php echo $chk_list_name;?></td>
    </tr>
     <tr>
      <td><b>Hours Allocated:</b></td><td><?php echo $hours;?></td>
    </tr>
 </table>
    <?php echo $Heading;?>
 
 </div>

 </form>
 
 </body>
</html>