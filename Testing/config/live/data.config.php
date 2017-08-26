<?php
error_reporting (0); 
//--------------------------------------------------------------
// Global Configuration File
//--------------------------------------------------------------
//date_default_timezone_set('America/New_York');
// SOME GUIDELINES:
// Make sure that all files/dirs have a valid permissions.
// Right permissions should be for dirs 0755 and for files 0644.
// Please mention in the below line which dirs/files must have writeable by webserver.
// You should also write some description here about dirs/files.
// This may be require to other developers who will might have to work on this project.

// <MENTION HERE DIRS/FILES NAME WHICH MUST HAVE WRITEABLE BY WEBSERVER>

// Host/Server Name
$HOST_NAME                        = $_SERVER['SERVER_NAME'];
$DOCUMENT_ROOT                    = $_SERVER['DOCUMENT_ROOT'];

//$HOST_NAME                  = "reel-comic.di.com";
//$HOST_NAME                  = "demoN.dimensioni.net";
//$HOST_NAME                  = "demoN.dimensionindia.com";

if($HOST_NAME == "rawcrm.bwi.com")
{
       // $DOCUMENT_ROOT                  = "/var/www/vhosts/reel-comic/httpdocs";

        //--------------------------------------------------------------
        // DATABASE PARAMETERS
        //--------------------------------------------------------------

        $DB_HOST                        = "localhost";                  // Database Host Server
        $DB_USERNAME                    = "rawcrm";              // Database Username
        $DB_PASSWORD                    = 'rawcrm';              // Password for the Db User
        $DB_NAME                        = "rawcrm";              // Database name
        $DB_REPORT_ERROR                = false;                        // To Report Error
        $DB_PERSISTENT_CONN             = false;                        // If Db Connection to be persistent
		//Application directory Path of the app from Virtual root
		$MAP_VROOT_PATH                 = "";
		$SITE_URL_PAYEMNT = "http://rawcrm.bwi.com";	
}else{
 
        //--------------------------------------------------------------
        // DATABASE PARAMETERS
        //--------------------------------------------------------------

        $DB_HOST                        = "localhost";                  // Database Host Server
        $DB_USERNAME                    = "rawonlin_crm";                     // Database Username
        $DB_PASSWORD                    = 'crm123$%^';                 // Password for the Db User
        $DB_NAME                        = "rawonlin_crm";                      // Database name
        $DB_REPORT_ERROR                = false;                        // To Report Error
        $DB_PERSISTENT_CONN             = false;                        // If Db Connection to be persistent
		//Application directory Path of the app from Virtual root
		$MAP_VROOT_PATH                 = "";
		$SITE_URL_PAYEMNT = "https://exhibitors.ccmcme.com";
}


//Website URL
$SITE_URL                       = "http://$HOST_NAME$MAP_VROOT_PATH";

//HTML Global Templates Location
$TEMPLATE_DIR                   = "$DOCUMENT_ROOT$MAP_VROOT_PATH/templates";


$FCK_ROOT_DIR					          = "$DOCUMENT_ROOT$MAP_VROOT_PATH";

//Image Directory from Virtual Root
$IMG_DIR                        = "$MAP_VROOT_PATH/images";

$PIC_DIR                        = "$MAP_VROOT_PATH/pics";



//PHP Global Library Location
$FLICKER_DIR                    = "$DOCUMENT_ROOT$MAP_VROOT_PATH/flickr";
$LIB_DIR                        = "$DOCUMENT_ROOT$MAP_VROOT_PATH/phplib";
$PAYPAL_DIR                     = "$DOCUMENT_ROOT$MAP_VROOT_PATH/paypal";
$UPLOAD_LIB_DIR                 = "$DOCUMENT_ROOT$MAP_VROOT_PATH/prog";
$JS_DIR                         = "$SITE_URL/jslib";

 
$DIR_UPLOAD_EMPLOYEE_FILE        = "$DOCUMENT_ROOT$MAP_VROOT_PATH/uploadedFiles/employee";
$DIR_UPLOAD_CUSTOMER_FILE        = "$DOCUMENT_ROOT$MAP_VROOT_PATH/uploadedFiles/customer";  
$GALLERY_IMG                    = "$DOCUMENT_ROOT$MAP_VROOT_PATH/uploadedFiles/gallery";
//$DIR_UPLOAD_NEWS                = "$DOCUMENT_ROOT$MAP_VROOT_PATH/uploadedFiles/news"; 
//$DIR_UPLOAD_FLASH               = "$DOCUMENT_ROOT$MAP_VROOT_PATH/uploadedFiles/flash"; 

$GALLERY_IMG_DIR                = "$MAP_VROOT_PATH/uploadedFiles/gallery";
$DIR_DISPLAY_EMPLOYEE_FILE      = "$MAP_VROOT_PATH/uploadedFiles/employee";
$DIR_DISPLAY_CUSTOMER_FILE      = "$MAP_VROOT_PATH/uploadedFiles/customer";
$TEMP_DIR                       = "/tmp";

 
//$DIR_DISPLAY_PAGE_FILE          = "$MAP_VROOT_PATH/uploadedFiles/page";
//$DIR_DISPLAY_NEWS               = "$MAP_VROOT_PATH/uploadedFiles/news";
//$DIR_DISPLAY_FLASH              = "$MAP_VROOT_PATH/uploadedFiles/flash";
//$DIR_DISPLAY_PET_FILE           = "$MAP_VROOT_PATH/uploadedFiles/pets";

$APPL_NAME                      = "";
$COMPANY_NAME                   = "";
//$COPYRIGHT_YEAR                 = date("Y");

//$CURRENT_TOP_LINK_COLOR       = "red";
//$CURRENT_LEFT_LINK_COLOR      = "navy";

//--------------------------------------------------------------
// MAIL SERVER TYPE
//--------------------------------------------------------------

$MAIL_SERVER_TYPE               = "qmail";                       //Value Pool: sendmail, mail, qmail, smtp

$USER_IP = "Unknown";

if (isset($_SERVER['REMOTE_ADDR'])) {
    $USER_IP = $_SERVER['REMOTE_ADDR'];
}

if (isset($_SERVER['HTTP_VIA']) || isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    if ($_SERVER['HTTP_X_FORWARDED_FOR'] == "")
        $_SERVER['HTTP_X_FORWARDED_FOR'] = "Unknown";
    $USER_IP .= " (Proxying for ".$_SERVER['HTTP_X_FORWARDED_FOR'].")";
}

?>