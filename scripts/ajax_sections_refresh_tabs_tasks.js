// load this only when under the Tasks tab


//DIV UNDONE
function refreshdiv_2(){
// Customise those settings

var divid="div_2";
var url ="ajax_indicator_undone.php";

var xmlHttp_one = AjaxCalls();
// No cache

var timestamp = fetch_unix_timestamp();
var nocacheurl = url+"?t="+timestamp;

xmlHttp_one.onreadystatechange=function()
	{
	if(xmlHttp_one.readyState==4)
		{
		document.getElementById(divid).innerHTML=xmlHttp_one.responseText;
		setTimeout('refreshdiv_2()',seconds*6000);
		}
	}
xmlHttp_one.open("GET",nocacheurl,true);
xmlHttp_one.send(null);
}

// Start the refreshing process
window.onload = function startrefresh(){
setTimeout('refreshdiv_2()',seconds*6000);
}



//DIV INPROGRESS
function refreshdiv_3(){
// Customise those settings

var divid="div_3";
var url ="ajax_indicator_inprog.php";

var xmlHttp_one = AjaxCalls();
// No cache

var timestamp = fetch_unix_timestamp();
var nocacheurl = url+"?t="+timestamp;

xmlHttp_one.onreadystatechange=function()
	{
	if(xmlHttp_one.readyState==4)
		{
		document.getElementById(divid).innerHTML=xmlHttp_one.responseText;
		setTimeout('refreshdiv_3()',seconds*6000);
		}
	}
xmlHttp_one.open("GET",nocacheurl,true);
xmlHttp_one.send(null);
}

// Start the refreshing process
window.onload = function startrefresh(){
setTimeout('refreshdiv_3()',seconds*6000);
}


//DIV OVERDUE
function refreshdiv_4(){
// Customise those settings

var divid="div_4";
var url ="ajax_indicator_overdue.php";

var xmlHttp_one = AjaxCalls();
// No cache

var timestamp = fetch_unix_timestamp();
var nocacheurl = url+"?t="+timestamp;

xmlHttp_one.onreadystatechange=function()
	{
	if(xmlHttp_one.readyState==4)
		{
		document.getElementById(divid).innerHTML=xmlHttp_one.responseText;
		setTimeout('refreshdiv_4()',seconds*6000);
		}
	}
xmlHttp_one.open("GET",nocacheurl,true);
xmlHttp_one.send(null);
}

// Start the refreshing process
window.onload = function startrefresh(){
setTimeout('refreshdiv_4()',seconds*6000);
}



//DIV 1 1
function refreshdiv_1_1(){
// Customise those settings

var divid="div_1_1";
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
		setTimeout('refreshdiv_1_1()',seconds*6000);
		}
	}
xmlHttp_one.open("GET",nocacheurl,true);
xmlHttp_one.send(null);
}

// Start the refreshing process
window.onload = function startrefresh(){
setTimeout('refreshdiv_1_1()',seconds*6000);
}