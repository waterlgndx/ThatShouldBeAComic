<?php session_start(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<!--- header and style definitions --> 
<title>ThatShouldBeAComic.com</title>
<head>
<!--[if lt IE 9]>
    <script src="http://www.dreamreign.com/include/excanvas.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="thatshouldbeacomic.css" />
</head>
<?php
session_start();
$email = $_SESSION['email'];
$_SESSION['lastPage'] = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];



?>
<!--- Content-->
<img src="giraffes.jpg" usemap="#giraffemap" width="800" height="800" />

<map name="giraffemap">
     <area shape="rect" coords="9,380,120,630" href="journal.php">
     <area shape="rect" coords="295,398,478,588" href="period6.php">
    <!-- <area shape="rect" coords="470,190,686,310" href="dinogirl/">
     <area shape="rect" coords="624,377,770,495" href="ally/">-->
</map>

<script type="text/javascript"> 
//Get Comments 
function fillContent(div, url)
{
	var XMLHTTP= XMLHttpRequest || ActiveXObject("Microsoft.XMLHTTP");
	if (typeof XMLHTTP!= "undefined" ) 
	{
		var xmlhttp = new XMLHTTP;
		xmlhttp.onreadystatechange= function() {
			if(xmlhttp.readyState== 4) //4 is recv'd all responses
			{
				var resp = xmlhttp.responseText;
				document.getElementById(div).innerHTML= resp;
			}			
		}
		xmlhttp.open("GET", url, true);
		xmlhttp.send(null);
	}
	else
		alert("Your browser doesn't seem to support ajax");
}

function getComments()
{
	fillContent("comments", "http://www.thatshouldbeacomic.com/new/getcomments.php?fileName=thatshouldbeacomic");
}

function init()
{	
	getComments();
}
 
window.onload=function(){
	init();
}
</script>
<br />
<br />

<!-- buttons-->
<button type="button" id="btnTSBAC" onclick="location.href='addcomment.php?fileName=thatshouldbeacomic'" title="I have an idea!" alt="I have an idea!">That Should Be a Comic!!!</button>
<button type="button" id="btnUpdate" onclick="location.href='updates.html'" title="View updates" alt="View updates">Updates</button>
<button type="button" id="gotoBugs" alt="report a bug" onclick="location.href='bugs.html'">Bugs</button>
<?php if ($email) echo "<!--";?><button type="button" id="btnAddUser" onclick="location.href='adduser.php'" title="Sign me up!" alt="Sign me up!">Register</button><?php if ($email) echo "-->";?>
<?php if ($email) echo "<!--";?><button type="button" id="btnLogin" onclick="location.href='login.php'" title="Log me in!" alt="Log me in!">Login</button><?php if ($email) echo "-->";?>
<?php if (!($email)) echo "<!--";?><button type="button" id="btnLogout" onclick="location.href='logout.php'" title="Log out" alt="Log out">Logout</button><?php if (!($email)) echo "-->";?>
<?php if (!($email)) echo "<!--";?><button type="button" id="btnUpload" onclick="location.href='fileselect.php'" title="Upload" alt="Upload">Upload</button><?php if (!($email)) echo "-->";?>

<div id="temp">
</div>
<div id="comments">
</div>