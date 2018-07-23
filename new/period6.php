<?php session_start(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<!--- header and style definitions --> 
<title>ThatShouldBeAComic.com</title>
<head>
<!--[if lt IE 9]>
    <script src="http://www.dreamreign.com/include/excanvas.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="thatshouldbeacomic.css" />
<script src="getimagesdb.php?displayName=smithyisspiffy&desc=@p6"></script>
</head>
<?php
session_start();
$email = $_SESSION['email'];
$curimage=$_GET['image'];
if (!$curimage)
{
	$_SESSION['lastPage'] = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}
else
{
	$_SESSION['lastPage'] = "http://www.thatshouldbeacomic.com/new/journal.php";
}
?>
<!--- Content-->
<body>
<a href="http://www.thatshouldbeacomic.com/new/">
  <img src="tsbacbanner.png" style="margin-left:10%; margin-right:10%; width:80%; opacity:.8;" />
  </a>
<div align="center">
<table style="padding:5px">
	<tr>
		<td><button type="button" id="btnTSBAC" onclick="location.href='addcomment.php?fileName=thatshouldbeacomic'" title="I have an idea!" alt="I have an idea!">That Should Be a Comic!!!</button></td>
		<td><button type="button" id="btnUpdate" onclick="location.href='updates.html'" title="View updates" alt="View updates">Updates</button></td>
		<td><button type="button" id="gotoBugs" alt="report a bug" onclick="location.href='bugs.html'">Bugs</button></td>
		<?php if ($email) echo "<!--";?>
			<td><button type="button" id="btnAddUser" onclick="location.href='adduser.php'" title="Sign me up!" alt="Sign me up!">Register</button></td>
		<?php if ($email) echo "-->";?>
		<?php if ($email) echo "<!--";?>
			<td><button type="button" id="btnLogin" onclick="location.href='login.php'" title="Log me in!" alt="Log me in!">Login</button></td>
		<?php if ($email) echo "-->";?>
		<?php if (!($email)) echo "<!--";?>
			<td><button type="button" id="btnLogout" onclick="location.href='logout.php'" title="Log out" alt="Log out">Logout</button></td>
		<?php if (!($email)) echo "-->";?>
		<?php if (!($email)) echo "<!--";?>
			<td><button type="button" id="btnUpload" onclick="location.href='fileselect.php'" title="Upload" alt="Upload">Upload</button></td>
		<?php if (!($email)) echo "-->";?>
	</tr>
</table>
<br />
<canvas id="canvas" name="canvas"> </canvas>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script type="text/javascript"> 
//Script found @ http://www.javascriptkit.com/javatutors/externalphp2.shtml
var cview;                           //Canvas Viewer
var canvas;                          //Canvas
var curimg = 0;                      //Initialize counter for array.
var picRedSize = 80;                 //Percent of reduced size as whole number for %
var startx=0;
var starty=0;
var cwidth=630;
var cheight=460;
var resizeTimeoutId;
var timoutId;
var imageWidth=0;
var imageHeight=0;
var cscale = 1.0;
var fullsize = false;
var loadct=0;
var rooturl = "http://www.thatshouldbeacomic.com/new/";
 
 
//Function to go to next image in picture viewer (basically a go to next image function) this is looped by the setInterval later.
//Author: unknown (JavaScriptKit)
//Edited: Ben Parker - rewrote if statement.  I think it's easier to read without the short-hand method.
 
//go to newer (first is newest)
function nextimage(){
	if (curimg < galleryarray.length-1)   //check to see if we are at the end of the array
	{   
		curimg++; 
		document.getElementById("btnFirst").disabled=false;
		document.getElementById("btnPrev").disabled=false;
	} 
	prepareImage();
	if (curimg == galleryarray.length-1)
	{
		document.getElementById("btnLast").disabled=true;
		document.getElementById("btnNext").disabled=true;
	}
	
}
 
function loadimage(cimg)
{
	if (cimg > 0)
	{   		
		document.getElementById("btnLast").disabled=false;
		document.getElementById("btnNext").disabled=false;
	}
	prepareImage();
	if (cimg == 0)
	{
		document.getElementById("btnFirst").disabled=true;
		document.getElementById("btnPrev").disabled=true;
	}
}

//go to older (last is oldest)
function previmage() {	
	if (curimg > 0)
	{   
		curimg--; 
		document.getElementById("btnLast").disabled=false;
		document.getElementById("btnNext").disabled=false;
	}
	prepareImage();
	if (curimg == 0)
	{
		document.getElementById("btnFirst").disabled=true;
		document.getElementById("btnPrev").disabled=true;
	}
}
 
//Most recent image
function firstimage() {
	curimg = 0;
	prepareImage();	
	document.getElementById("btnFirst").disabled=true;
	document.getElementById("btnPrev").disabled=true;
	document.getElementById("btnLast").disabled=false;
	document.getElementById("btnNext").disabled=false;
}


//Get Comments 
function getComments()
{
	var XMLHTTP= XMLHttpRequest || ActiveXObject("Microsoft.XMLHTTP");
	if (typeof XMLHTTP!= "undefined" ) 
	{
		var xmlhttp = new XMLHTTP;
		xmlhttp.onreadystatechange= function() {
			if(xmlhttp.readyState== 4) //4 is recv'd all responses
			{
				var resp = xmlhttp.responseText;
				document.getElementById("comments").innerHTML= resp;
			}			
		}
		xmlhttp.open("GET", "http://www.thatshouldbeacomic.com/new/getcomments.php?fileName=" + galleryarray[curimg].id + ".jpg", true);
		xmlhttp.send(null);
	}
	else
		alert("Your browser doesn't seem to support ajax");
}

function prepareImage()
{
	imageWidth= galleryarray[curimg].width ;
	imageHeight= galleryarray[curimg].height ;
	
	var hscale = cheight / imageHeight;
	var wscale = cwidth / imageWidth;
	if (hscale < wscale)
	{
		cscale = hscale;
	}
	else
	{
		cscale = wscale;
	}
	
	imageHeight = Math.floor(imageHeight * cscale);
	imageWidth = Math.floor(imageWidth * cscale);
	cview.clearRect(startx,starty,cwidth,cheight);
	startx=(cwidth/2)-(imageWidth/2);
	cview.drawImage(galleryarray[curimg],startx,starty,imageWidth,imageHeight);	
	if ((loadct<5)&&(!fullsize))
	{
		timeoutID = window.setTimeout(prepareImage, (1000+loadct*200));
		loadct++;
	}
	
	 
	
	
	//Change link for Comment button.
	document.getElementById("commentClick").setAttribute("onclick","location.href='addcomment.php?fileName=" + galleryarray[curimg].id + ".jpg&image=" + curimg +"'");
	getComments();	
}
 
//Oldest image
function lastimage() {
	curimg = galleryarray.length-1;	
	prepareImage();
	document.getElementById("btnLast").disabled=true;
	document.getElementById("btnNext").disabled=true;
	document.getElementById("btnFirst").disabled=false;
	document.getElementById("btnPrev").disabled=false;
}	
 
function setsize()
{
	//Info can be found at: http://www.javascripter.net/faq/browserw.htm
	if (document.body && document.body.offsetWidth) {
	 cwidth = document.body.offsetWidth;
	 cheight = document.body.offsetHeight;
	}
	if (document.compatMode=='CSS1Compat' &&
	    document.documentElement &&
	    document.documentElement.offsetWidth ) {
	 cwidth = document.documentElement.offsetWidth;
	 cheight = document.documentElement.offsetHeight;
	}
	if (window.innerWidth && window.innerHeight) {
	 cwidth = window.innerWidth  ;
	 cheight = window.innerHeight ;
	}
	cwidth = Math.floor(cwidth *(picRedSize/100.0));
	cheight = Math.floor(cheight * (picRedSize/100.0)) ;
	canvas.setAttribute('height', cheight);
	canvas.setAttribute('width', cwidth);
}
 
function canvasonclick(e)
{
	
	if (!(fullsize))
	{
		//Find out where the click happened
		var x;
		var y;
		if (e.pageX != undefined && e.pageY != undefined) 
		{
			x = e.pageX;
			y = e.pageY;
		}
		else 
		{
			x = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
			y = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
    	}
    	x -= canvas.offsetLeft;
    	y -= canvas.offsetTop;
    	x -= startx;
    	if ((x>=0)&&(x<=imageWidth)&&(y<=imageHeight)&&(y>=0))
    	{   
			// make fullsize
			fullsize= !fullsize;
			canvas.setAttribute('height', galleryarray[curimg].height );
			canvas.setAttribute('width', galleryarray[curimg].width );
			cview.clearRect(startx,starty,galleryarray[curimg].width,galleryarray[curimg].height);		
			cview.drawImage(galleryarray[curimg],0,0,galleryarray[curimg].width,galleryarray[curimg].height);
		}
	}
	else
	{
		//make small
		fullsize = !fullsize;   
			
		cview.clearRect(startx,starty,galleryarray[curimg].width,galleryarray[curimg].height);		
		canvas.setAttribute('height', cheight);
		canvas.setAttribute('width', cwidth);
		cview.drawImage(galleryarray[curimg],startx,starty,imageWidth,imageHeight);			
	}
}

function canvasonkey(e)
{
	if (!e) e=event;
	if (!fullsize)
	{
		if (e.keyCode==37)
		{
			previmage();
		}
		else if (e.keyCode==39)
		{
			nextimage();
		}
	}
}


function init()
{	
	//Create canvas to draw image to:
	canvas = document.getElementById('canvas');
	setsize();
	if (canvas.addEventListener)
	{	canvas.addEventListener("click", canvasonclick, false); }
	else
	{	canvas.attachEvent("onclick", canvasonclick ); }	
	
	if (document.addEventListener)
	{	document.addEventListener("keydown", canvasonkey, false); }
	else
	{	document.attachEvent("onkeydown", canvasonkey); }
	cview = canvas.getContext("2d");
	<?php if ($curimage) echo 'curimg = ' .$curimage ?>;
	
	//Draw initial image:
	if (curimg==0)
	{
		firstimage();
	}
	else
	{
		loadimage(curimg);
	}
}
 
window.onload=function(){
	init();
} 

</script> 
<form>
<div align="center">
<br />
<button type="button" id="btnFirst" onclick="firstimage()">|<<</button>
<button type="button" id="btnPrev" onclick="previmage()"><<</button>
<button type="button" id="btnNext" onclick="nextimage()">>></button>
<button type="button" id="btnLast" onclick="lastimage()">>>|</button>
<br />
<button type="button" id="gotoBugs2" alt="report a bug" onclick="location.href='bugs.html'">Bugs</button>
<button type="button" id="commentClick" onclick="location.href='addcomment.php'">Comment</button>
</form>
</div>
<div align="center" id="comments">
</div>
<br>
<br>
<div align="center">
[ <a href="credits.html">Programming Credits</a> | <a href="showall.html">Show All</a> ]
</div>
<br>
<br>
</body>
</html>