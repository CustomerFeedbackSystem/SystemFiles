// JavaScript Document
function AjaxCalls(){
try{
xmlHttp=new XMLHttpRequest(); // Firefox, Opera 8.0+, Safari
return xmlHttp;
}
catch (e){
try{
xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
return xmlHttp;
}
catch (e){
try{
xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
return xmlHttp;
}
catch (e){
alert("Your browser does not support AjaxCalls.");
return false;
}
}
}
}

// Timestamp for preventing IE caching the GET request (common function)

function fetch_unix_timestamp()
{
 return parseInt(new Date().getTime().toString().substring(0, 10))
}

var seconds=60;

//DIV 1
function refreshdiv_1(){
// Customise those settings

var divid="div_1";
var url ="ajax_indicator_allin.php";

var xmlHttp_one = AjaxCalls();
// No cache

var timestamp = fetch_unix_timestamp();
var nocacheurl = url+"?t="+timestamp;

xmlHttp_one.onreadystatechange=function()
	{
	if(xmlHttp_one.readyState==4)
		{
		document.getElementById(divid).innerHTML=xmlHttp_one.responseText;
		setTimeout('refreshdiv_1()',seconds*6000);
		}
	}
xmlHttp_one.open("GET",nocacheurl,true);
xmlHttp_one.send(null);
}

// Start the refreshing process
window.onload = function startrefresh(){
setTimeout('refreshdiv_1()',seconds*6000);
}


//DIV 1
function refreshdiv_footer(){
// Customise those settings

var divid="div_footer";
var url ="ajax_footer.php";

var xmlHttp_one = AjaxCalls();
// No cache

var timestamp = fetch_unix_timestamp();
var nocacheurl = url+"?t="+timestamp;

xmlHttp_one.onreadystatechange=function()
	{
	if(xmlHttp_one.readyState==4)
		{
		document.getElementById(divid).innerHTML=xmlHttp_one.responseText;
		setTimeout('refreshdiv_footer()',seconds*6000);
		}
	}
xmlHttp_one.open("GET",nocacheurl,true);
xmlHttp_one.send(null);
}

// Start the refreshing process
window.onload = function startrefresh(){
setTimeout('refreshdiv_footer()',seconds*6000);
}



