<?php session_start();

include("../config/data.config.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/functions_client.library.php");
//include("$LIB_DIR/include.inc.php");


$MEMBER_NAME=$_SESSION["SESS_CLIENT_NAME"];

$MEMBER_ID   = $_SESSION["SESS_CLIENT_ID"];
checkClientLogin();
 display_message();

 $TOPBAR			    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/topbar.html");
   
 $PAGE_CONTENTS	  = ReadTemplate("$TEMPLATE_DIR_CLIENT/sales.html");
 $RIGHT_BAR		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/rightbar.html");
 $BOTTOMBAR		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/bottombar.html");
 $SUB_TEMPLATE	  = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/sub_template.html");
 $TEMPLATE		    = ReadTemplate("$TEMPLATE_DIR_CLIENT/common/template.html");

ReplaceContent(Array("TOPBAR", "BOTTOMBAR", "PAGE_CONTENTS","RIGHT_BAR", "SUB_TEMPLATE", "TEMPLATE"));

print $TEMPLATE;
flush();



?>