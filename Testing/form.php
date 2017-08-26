<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
</head>
<body>

<?php
if(isset($_POST['submit']))
{
	$to="neha@brainworkindia.net";
	$subject="Registration From";
	
	$name=$_POST['name'];
	$email=$_POST['email'];
	$phone=$_POST['phone'];
	$address=$_POST['address'];
	
	$message="<table cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td width=\"150\">Name:</td>
					<td>$name</td>
				</tr>
				<tr>
					<td width=\"150\">Email:</td>
					<td>$email</td>
				</tr>
				<tr>
					<td width=\"150\">Phone:</td>
					<td>$phone</td>
				</tr>
				<tr>
					<td width=\"150\">Address:</td>
					<td>$address</td>
				</tr>
				</table>";
				
    // Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= "From: <$email>" . "\r\n";

	mail($to,$subject,$message,$headers);
}

?>

<form name="registration" id="registration" action="" method="post" onsubmit="return validate();">
<div>
<label>NAME:</label>
<input name="name" type="text" /><span id="name_error" ></span>
</div>
<div>&nbsp;</div>
<div>
<label>EMAIL:</label>
<input name="email" type="text" /><span id="email_error" ></span>
</div>
<div>&nbsp;</div>

<div>
<label>PHONE:</label>
<input name="phone" type="text" />
</div>
<div>&nbsp;</div>

<div>
<label>ADDRESS:</label>
<textarea name="address" type="text" cols="3" rows="2"></textarea>
</div>
<div>&nbsp;</div>
<div>
<label>&nbsp;</label>
<input type="submit" name="submit" value="Submit" />
</div>	
	
</form>

</body>
<script>
function validate()
{ 
with(window.document.registration)
{

 $('#name_error').html('');
 $('#email_error').html('');
if((name.value==""))
{
   $('#name_error').html('please enter name');
   return false;
}
if((email.value==""))
{
   $('#email_error').html('please enter email');
   return false;
}

if(email.value!="")
{ apos=email.value.indexOf("@");
dotpos=email.value.lastIndexOf(".");
if (apos<1||dotpos-apos<2)
{
 $('#email_error').html('please enter valid email');
   return false;
}
}

}
}
</script>

</html>
