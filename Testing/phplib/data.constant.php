<?php
// SYSTEM CONSTANTS FILE. NOT TO BE EDITED
define("LINEFEED", "\r\n");
define("LINK_DIV", "<IMG SRC = \"$IMG_DIR/black_array.gif\" HEIGHT = 7 WIDTH = 4 HSPACe = 3>");

//Type of Admin Users
define("USER_WEBMASTER", "W");
define("USER_EXECUTIVE", "E");
define("USER_BUYER", "B");
define("USER_GUEST", "G");
define("USER_SELLER", "S");

$ARR_USERS = array(
	USER_BUYER		=> 'Member',
	USER_GUEST		=> 'Guest',
	USER_WEBMASTER	=> 'Webmaster',
	USER_EXECUTIVE	=> 'Executive'
);

define("USER_FREE", "F");
define("USER_PLATINUM", "P");
define("USER_OPTIMIZED", "O");

$ARR_LISTING_TYPE = array(
	'B'		=> 'Basic',
	'P'		=> 'Premium',
	'F'		=> 'Featured'
);

// Products Detail
define("TRADE_CURRENCY",'NOK');
define("AREA_UNIT",'M<sup>2</sup>');
//Application Modules
define("MODULE_ENQUIRY_FORM", "EF");
define("MODULE_SITE_GLOBAL", "SG");
define("MODULE_GALLERY", "CL");
define("MODULE_NEWS", "NW");
define("MODULE_CASESTUDY", "CS");
define("MODULE_USEFUL_LINK", "UL");

$ARR_APP_MODULES = array(
MODULE_ENQUIRY_FORM		=> "Enquiry Mailbox",
MODULE_NEWS				=> "Product Updates",
MODULE_CASESTUDY		=> "Case Studies",
MODULE_USEFUL_LINK		=> "Useful Links"
);


//Global Status
define ("STATUS_DRAFT", "D");
define ("STATUS_LIVE", "L");
define ("STATUS_SUSPENDED", "S");
define ("STATUS_UNVERIFIED", "U");
define ("STATUS_NEW", "N");
define ("STATUS_OLD", "O");
define ("STATUS_REPLIED", "R");

/*
$ARR_GLOBAL_STATUS = array(
	STATUS_DRAFT		=> "Draft",
	STATUS_LIVE			=> "Live",
	STATUS_SUSPENDED	=> "Suspended",
	STATUS_NEW			=> "New",
	STATUS_OLD			=> "Read",
	STATUS_REPLIED		=> "Replied",
	STATUS_ACTIVE		=> "Active",
	STATUS_INACTIVE		=> "In-active"
);*/

$ARR_GLOBAL_STATUS = array(
	'L'		=> "Live",	
	'S'		=> "Suspended"
);

define ("STATUS_DELETED", "D");

define ("STATUS_ACTIVE", "Y");
define ("STATUS_INACTIVE", "N");


$ARR_GLOBAL_STATUS1 = array(
	STATUS_DELETED		=> "Deleted",
	STATUS_LIVE			=> "Live",
	STATUS_SUSPENDED	=> "Suspended",
	STATUS_NEW			=> "New",
	STATUS_OLD			=> "Read",
	STATUS_REPLIED		=> "Replied"
);


define ("STATUS_PROCESS", "P");
define ("STATUS_CLOSED", "C");

$ARR_GLOBAL_ORDER_STATUS = array(
	STATUS_NEW			=> "New",
	STATUS_PROCESS		=> " On process",
	STATUS_CLOSED		=> "Closed",
	STATUS_DELETED			=> "Deleted"
	
);
//Newsletter Status
define ("NL_DRAFT", "D");
define ("NL_READY", "R");
define ("NL_PROCESSING", "P");
define ("NL_SENT", "S");

$ARR_NL_STATUS = array(
	NL_DRAFT		=> "Draft",
	NL_READY		=> "Ready",
	NL_PROCESSING => "Processing",
	NL_SENT		=> "Sent"
);

//Global Toggles
define ("TAG_YES", "Y");
define ("TAG_NO",  "N");
   


//Mailing List Subscription Status
define("SUBXN_VERIFIED", "V");
define("SUBXN_UNVERIFIED", "U");
define("SUBXN_SUSPENDED", "S");
//define("SUBXN_CANCELLED", "X");

$ARR_SUBXN_STATUS = array(
	SUBXN_VERIFIED		=> 'Verified',
	SUBXN_UNVERIFIED	=> 'Un-verified',
	SUBXN_SUSPENDED		=> 'Suspended'
	//SUBXN_CANCELLED		=> 'Cancelled'
);

/*
$ARR_JOKE_TYPE = array(
	'1'		=> 'Animal',
	'2'		=> 'Bar',
	'3'		=> 'Blonde',
	'4'		=> 'Man And Woman',
	'5'		=> 'Workplace',
	'6'		=> 'Political',
	'7'		=> 'Religious',
	'8'		=> 'Knock-Knock',
	'9'		=> 'Dirty',
	'10'	=> 'Ethnic',
	'11'	=> 'Sexual Orientation',
	'12'	=> 'Marriage',
	'13'	=> 'Yo Mamma',
	'14'	=> 'Golf Jokes',
	'15'	=> 'Other'
);

*/


$ARR_CATEGORY = array(
	'1'		=> 'Rider',
	'2'		=> 'Instructor',
	'3'		=> 'Youth Parent',
	'4'		=> 'Organization',
	'5'		=> 'Other'
);

$ARR_STATE = array(
	'1'		=> 'Alabama',
	'2'		=> 'Alaska',
	'3'		=> 'Arizona',
	'4'		=> 'State-04',
	'5'		=> 'State-05',
	'6'		=> 'State-06',
	'7'		=> 'State-07',
	'8'		=> 'State-08',
	'9'		=> 'State-09',
	'10'	=> 'State-10'
);

$ARR_RIDING_DISCIPLINE  = array(
	'1'			=> 'Hunter',
	'2'			=> 'Jumper',
	'3'			=> 'Dressage',
	'4'			=> 'Western Pleasure',
	'5'			=> 'Eventing',
	'6'			=> 'Barrel racing',
	'7'			=> 'Cutting',
	'8'			=> 'Driving',
	'9'			=> 'Endurance',
	'10'		=> 'English Pleasure',
	'11'		=> 'Gaited Horses',
	'12'		=> 'ParaEquestrian',
	'13'		=> 'Park',
	'14'		=> 'Reining',
	'15'		=> 'Saddleseat Equitation',
	'16'		=> 'Trail',
	'17'		=> 'Vaulting',
	'18'		=> 'Other'
);

$ARR_SHOW_CIRCUIT  = array(
	'1'			=> 'Local Shows',
	'2'			=> 'Pony Club',
	'3'			=> 'Hunter/Jumper',
	'4'			=> 'Dressage/CT',
	'5'			=> 'Quarter Horse',
	'6'			=> 'Appaloosa',
	'7'			=> 'Arabian',
	'8'			=> 'Collegiate',
	'9'			=> 'Driving',
	'10'		=> 'Endurance',
	'11'		=> 'Eventing',
	'12'		=> 'Morgan',
	'13'		=> 'National Show Horse',
	'14'		=> 'Paint',
	'15'		=> 'Pinto',
	'16'		=> 'Saddlebred',
	'17'		=> 'Scholastic',
	'18'		=> 'Tennessee Walker',
	'19'		=> 'UPHA',
	'20'		=> 'Welch Pony',
	'21'		=> 'Other'

);

$ARR_COMPETITION_LEVEL   = array(
	'1'		=> 'Local',
	'2'		=> 'Regional',
	'3'		=> 'National',
	'4'		=> 'Professional',
	'5'		=> 'Other',
);

$ARR_SKILL_LEVEL = array(
	'1'		=> 'Beginner',
	'2'		=> 'Intermediate',
	'3'		=> 'Advanced',
	'4'		=> 'Professional',
	'5'		=> 'Other'
);

$ARR_SPEND_DROPDOWN = array(
	0		=> '0',
	100		=> '100',
	200		=> '200',
	300		=> '300',
  	400		=> '400',
	500		=> '500',
	1000	=> '1000',
  	2000	=> '2000',
  	3000	=> '3000',
  	4000	=> '4000',
  	5000	=> '5000', 
  	10000	=> '10000',
  	20000	=> '20000',
  	30000	=> '30000',
  40000	=> '40000',
  50000	=> '50000',
);

$GST_VAL = 10;


$PROMPTMESSAGE['cmsmsg']['1']='Authentication failed';
$PROMPTMESSAGE['cmsmsg']['2']='Password has been send to your email.';
$PROMPTMESSAGE['cmsmsg']['3']='Username not found. Try again.';
$PROMPTMESSAGE['cmsmsg']['4']='Your Account Password has been changed with the given new password.';
$PROMPTMESSAGE['cmsmsg']['5']='Authentication failed. Try again!';
$PROMPTMESSAGE['cmsmsg']['6']='Login details send to ';
$PROMPTMESSAGE['cmsmsg']['7']='Error encountered in delivering login details by mail.';
$PROMPTMESSAGE['cmsmsg']['8']='Username <USERNAME> could not be added. Try again.';
$PROMPTMESSAGE['cmsmsg']['9']='Username <USERNAME> has been added.';
$PROMPTMESSAGE['cmsmsg']['10']='Username <USERNAME> already exists in the database. Try another.';
$PROMPTMESSAGE['cmsmsg']['11']='Status has been changed to <TOTAL> user.';
$PROMPTMESSAGE['cmsmsg']['12']='Status has been changed.';
$PROMPTMESSAGE['cmsmsg']['13']='Member has been deleted.';
$PROMPTMESSAGE['cmsmsg']['14']='Status has been changed to <TOTAL> members.';
$PROMPTMESSAGE['cmsmsg']['15']='<TOTAL> members has been deleted.';
$PROMPTMESSAGE['cmsmsg']['16']='Member has been created successfully.';
$PROMPTMESSAGE['cmsmsg']['17']='Member has been updated successfully.';
$PROMPTMESSAGE['cmsmsg']['18']='Job has been added successfully.';
$PROMPTMESSAGE['cmsmsg']['19']='Job has been deleted successfully.';
$PROMPTMESSAGE['cmsmsg']['20']='Job has been updated successfully.';
$PROMPTMESSAGE['cmsmsg']['21']='<TOTAL> jobs has been deleted.';
$PROMPTMESSAGE['cmsmsg']['22']='Status has been changed to <TOTAL> jobs.';
$PROMPTMESSAGE['cmsmsg']['23']='<TOTAL> mails deleted successfully.';
$PROMPTMESSAGE['cmsmsg']['24']='Static page added successfully.';
$PROMPTMESSAGE['cmsmsg']['25']='Static page updated successfully.';
$PROMPTMESSAGE['cmsmsg']['26']='Profile has been updated successfully.';
$PROMPTMESSAGE['cmsmsg']['27']='Old password is wrong.';
$PROMPTMESSAGE['cmsmsg']['28']='Password has been changed successfully.';
$PROMPTMESSAGE['cmsmsg']['29']='Your account is deactivated.';
$PROMPTMESSAGE['cmsmsg']['30']='Your account is activated.';
$PROMPTMESSAGE['cmsmsg']['31']='Invalide cms user.';
$PROMPTMESSAGE['cmsmsg']['32']='CMS user updated successfully.';
$PROMPTMESSAGE['cmsmsg']['33']='Invalide member.';
$PROMPTMESSAGE['cmsmsg']['34']='Status has been changed to <TOTAL> videos.';
$PROMPTMESSAGE['cmsmsg']['35']='<TOTAL> videos has been deleted.';
$PROMPTMESSAGE['cmsmsg']['36']='<TOTAL> cms user has been deleted.';
$PROMPTMESSAGE['cmsmsg']['37']='Changes have been saved to Application Parameters.';
$PROMPTMESSAGE['cmsmsg']['38']='No changes have been saved.';
$PROMPTMESSAGE['cmsmsg']['39']='Username is not available.';
$PROMPTMESSAGE['cmsmsg']['40']='New password has been send to your email address.';
$PROMPTMESSAGE['cmsmsg']['41']='Mail has been send to your friend.';
$PROMPTMESSAGE['cmsmsg']['42']='An error occur due to sending mail.';
$PROMPTMESSAGE['cmsmsg']['43']='Please enter news story.';
$PROMPTMESSAGE['cmsmsg']['44']='You don\'t have to access this page.';

$PROMPTMESSAGE['sitemsg']['1']='Username already exists.';
$PROMPTMESSAGE['sitemsg']['2']='You have successfully registered with TheReelComic.com.';
$PROMPTMESSAGE['sitemsg']['3']='Picture uploaded successfully.';
$PROMPTMESSAGE['sitemsg']['4']='Thank you for contacting us. We will get back to you soon.';
$PROMPTMESSAGE['sitemsg']['5']='Video file uploaded succsessfully.';
$PROMPTMESSAGE['sitemsg']['6']='Please upload file less than 20MB.';
$PROMPTMESSAGE['sitemsg']['7']='Please upload a MOV, AVI, WMV or MPG file.';
$PROMPTMESSAGE['sitemsg']['8']='No gig available.';
$PROMPTMESSAGE['sitemsg']['9']='No gig available.';
$PROMPTMESSAGE['sitemsg']['10']='Your vote has been saved for this joke.';
$PROMPTMESSAGE['sitemsg']['11']='You have already voted on this video.';
$PROMPTMESSAGE['sitemsg']['12']='Video file delete succsessfully.';
$PROMPTMESSAGE['sitemsg']['13']='Video is not available.';
$PROMPTMESSAGE['sitemsg']['14']='Your comment has been added.';
$PROMPTMESSAGE['sitemsg']['15']='Username/Email is not available.';




// $EMP_REGION check function.library.php function getRegionList();

////// setting timezone
date_default_timezone_set("Australia/Canberra");
$CURR_YEAR_COPY = date('Y');