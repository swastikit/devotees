function isLegal(txt) {
	var invalids = "!@#$%^&*()-~,'<.>/?;:\|"
	for(i=0; i<invalids.length; i++) {
		if(txt.indexOf(invalids.charAt(i)) >= 0 ) {
			alert("!@#$%^&*()-~,'<.>/?;:\| characters are not allowed!!!")
			return false;
		}
	}
	return true;
}
function openWindowYatraRegDevProfile(regId){
	var url="http://rgmdatabase.com:8080/yatra.registerdList.viewProfile.action?redId="+regId;
	window.showModalDialog(url,"","width=400; height=200; resizable=yes; scrollbars=yes; top=300;left=300")
} 


/**********************************************************************
	IN: NUM - the number to format
		decimalNum - the number of decimal places to format the number to
		bolLeadingZero - true / false - display a leading zero for
										numbers between -1 and 1
		bolParens - true / false - use parenthesis around negative numbers
		bolCommas - put commas as number separators.
 	RETVAL:
		The formatted number!
 **********************************************************************/
function FormatNumber(num,decimalNum,bolLeadingZero,bolParens,bolCommas)
{ 
	if (isNaN(parseInt(num))) return "NaN";
	var tmpNum = num;
	var iSign = num < 0 ? -1 : 1;		// Get sign of number
	// Adjust number so only the specified number of numbers after
	// the decimal point are shown.
	tmpNum *= Math.pow(10,decimalNum);
	tmpNum = Math.round(Math.abs(tmpNum))
	tmpNum /= Math.pow(10,decimalNum);
	tmpNum *= iSign;					// Readjust for sign
	// Create a string object to do our formatting on
	var tmpNumStr = new String(tmpNum);
	// See if we need to strip out the leading zero or not.
	if (!bolLeadingZero && num < 1 && num > -1 && num != 0)
		if (num > 0)
			tmpNumStr = tmpNumStr.substring(1,tmpNumStr.length);
		else
			tmpNumStr = "-" + tmpNumStr.substring(2,tmpNumStr.length);
	// See if we need to put in the commas
	if (bolCommas && (num >= 1000 || num <= -1000)) {
		var iStart = tmpNumStr.indexOf(".");
		if (iStart < 0)
			iStart = tmpNumStr.length;

		iStart -= 3;
		while (iStart >= 1) {
			tmpNumStr = tmpNumStr.substring(0,iStart) + "," + tmpNumStr.substring(iStart,tmpNumStr.length)
			iStart -= 3;
		}		
	}
	// See if we need to use parenthesis
	if (bolParens && num < 0)
		tmpNumStr = "(" + tmpNumStr.substring(1,tmpNumStr.length) + ")";
	return tmpNumStr;		// Return our formatted string!
}
function FormatNumber(num,decPos)
{
	var rt="";
	var tmpNumStr = new String(num);
	var decIndex=tmpNumStr.indexOf(".");
	var startIndex=0;
	if(decIndex==-1)
		startIndex=tmpNumStr.length-1;
	else
		startIndex=tmpNumStr.length<decIndex+decPos?tmpNumStr.length:decIndex+decPos;
	if(decPos==0 && decIndex>-1) startIndex--;
	for(i=startIndex;i>=0;i--)
	{
		if(startIndex-i==3||startIndex-i==5||startIndex-i==7)
			rt=tmpNumStr.charAt(i) + "," + rt;
		else
			rt=tmpNumStr.charAt(i) + rt;
	}
	return rt;
}
function IsNumeric(sText)
{
	if(sText.length==0) return false;
   	var ValidChars = "0123456789.";
   	var IsNumber=true;
   	var Char;
   	for (i = 0; i < sText.length && IsNumber == true; i++) 
   	{ 
      	Char = sText.charAt(i); 
      	if (ValidChars.indexOf(Char) == -1) 
      	{
         	IsNumber = false;
      	}
   	}
   	return IsNumber;
}			

/*
 * ------------------------------CHANGE DB STARTS-------------------------------
 */
var isIE_DB_ = false;
var el_DB_;
var btn_DB_;
var req_DB_;
function loadXMLDocDB_(url) {
    if (window.XMLHttpRequest) {
        req_DB_ = new XMLHttpRequest();
        req_DB_.onreadystatechange = dbChanged_;
        req_DB_.open("GET", url, true);
        req_DB_.send(null);
    } else if (window.ActiveXObject) {
        isIE_DB_ = true;
        req_DB_ = new ActiveXObject("Microsoft.XMLHTTP");
        if (req_DB_) {
            req_DB_.onreadystatechange = dbChanged_;
            req_DB_.open("GET", url, true);
            req_DB_.send();
        }
    }
}
function dbChanged_() {
    if (req_DB_.readyState == 4) {
        if (req_DB_.status == 200) {
        	var objJson = eval('(' + req_DB_.responseText + ')');        	
    		var btnDb_ = btn_DB_;		
    		var el = el_DB_;
    		if(el.innerHTML=="Congregation Database")
    		{
    			el.innerHTML="IYS Database"
    			btnDb_.innerHTML="Congregation Database"
    		}
    		else if(el.innerHTML=="IYS Database")
    		{
    			el.innerHTML="Congregation Database"
    			btnDb_.innerHTML="IYS Database"
    		}
    		hideWait();
    		alert(objJson.msg + "\n Please refresh the page to make it effect.")
        } 
        else 
        {
        	hideWait();
        	alert("There was a problem while changing database\n Error:" + req_DB_.statusText);
        }
    }
    hideWait();
}
function changeDb_(el)
{
	var newDB=""
	el_DB_ = el;
	btn_DB_ = document.getElementById('btnDb_');
	if(el.innerHTML=="Congregation Database")
		newDB="CONG";
	else if(el.innerHTML=="IYS Database")
	{
		newDB="IYS";
	}
	var sUrl=document.URL;
	sUrl=sUrl.substring(0,sUrl.lastIndexOf("/"));
	sUrl = sUrl + "/common.changeDB.action?newDB=" + newDB;
	showWait();	
	loadXMLDocDB_(sUrl);
	hideCurrentPopup();
}
/*
 * ------------------------------CHANGE DB ENDS-------------------------------
 */

function searchInArrary(originalArray, itemToDetect) {
	var j = 0;
	while (j < originalArray.length) {
		if (originalArray[j] == itemToDetect) {
			return true;
		} else { j++; }
	}
	return false;
}
function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}
function ltrim(stringToTrim) {
	return stringToTrim.replace(/^\s+/,"");
}
function rtrim(stringToTrim) {
	return stringToTrim.replace(/\s+$/,"");
}

function replaceAll (searchHere, replaceIt, replaceWith)
{ 
	var st = streng;
	if (soeg.length == 0)
    	return st;
	var idx = st.indexOf(soeg);
	while (idx >= 0)        
  	{
  		st = st.substring(0,idx) + erstat + st.substr(idx+soeg.length);
     	idx = st.indexOf(soeg);
	}
  	return st;
}	


/*
These two functions are used to show and hide the div which shows wait...indication
to user
*/
/*-----------------------------------START---------------------------------------*/
function showWait(){
	if(this.divBussy==null){
		var divBussy = document.createElement('div')
		divBussy.id = "divBussy";
		divBussy.align='center';
		divBussy.style.backgroundColor="#CCD5FF";
		divBussy.style.display="none";
		divBussy.style.zIndex="2000";
		divBussy.style.position="absolute";
		divBussy.style.width="300px";
		/*divBussy.style.height="100px";*/	
		divBussy.style.zIndex=1000;	
		var innerHtml = '<table style="border-width:20px;border-color: #CCD5FF;border-style:solid;background-color:#CCD5FF;">';
		innerHtml = innerHtml + '<tr>';
		innerHtml = innerHtml + '<td align="right">';
		innerHtml = innerHtml + 'Please wait......';
		innerHtml = innerHtml + '</td>';
		innerHtml = innerHtml + '<td>';
		innerHtml = innerHtml + '<img src="css/images/ajax-loader.gif"/>';
		innerHtml = innerHtml + '</td>';
		innerHtml = innerHtml + '</tr>';
		innerHtml = innerHtml + '</table>';
		divBussy.innerHTML=innerHtml;
		document.body.appendChild(divBussy);			
		this.divBussy=divBussy;
	}
	if (window.createPopup && !window.XmlHttpRequest){
		if(this.shimBussy==null){
			var viframe = document.createElement('iframe')
			viframe.id = "iframeshimBussy";
			viframe.src = "";
			viframe.style.backgroundColor="#CCD5FF";
			viframe.style.display="none";
			viframe.style.left="0";
			viframe.style.top="0";
			viframe.style.zIndex="999";
			viframe.style.position="absolute";			
			//viframe.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)";
			viframe.frameBorder="0"; 
			viframe.scrolling="no";
			document.body.appendChild(viframe);			
			this.shimBussy=viframe;
		}
	}
	this.divBussy.style.display="block";
	if (window.createPopup && !window.XmlHttpRequest){	
		if(this.shimBussy!=null){
			var ifrRef = this.shimBussy;
		    ifrRef.style.width = this.divBussy.style.width;
		    ifrRef.style.height = this.divBussy.style.height
		    ifrRef.style.top = this.divBussy.style.top;
		    ifrRef.style.left = this.divBussy.style.left;
		    ifrRef.style.zIndex = this.divBussy.style.zIndex - 1;
		    ifrRef.style.display = this.divBussy.style.display;
		}
	}
	centerWait();
}
function centerWait() {
	var loading=this.divBussy;
	if(loading!== undefined)
	{
		width = 400;
		height = 104;
		var theBody = document.getElementsByTagName("BODY")[0];
		var scTop = parseInt(getScrollTop(),10);
		var scLeft = parseInt(theBody.scrollLeft,10);
		var fullHeight = getViewportHeight();
		var fullWidth = getViewportWidth();
		loading.style.top = (scTop + ((fullHeight - (height)) / 2) + 10) + "px";
		loading.style.left =  (scLeft + ((fullWidth - width) / 2)) + "px";
	}
}
addEvent(window, "resize", centerWait);
addEvent(window, "scroll", centerWait);
window.onscroll = centerWait;
function hideWait()
{
	var loading=this.divBussy;
	loading.style.display="none"
	if (window.createPopup && !window.XmlHttpRequest)
	{
		if(this.shimBussy!=null)
		{
			var ifrRef = this.shimBussy;
		    ifrRef.style.zIndex = loading.style.zIndex - 1;
		    ifrRef.style.display = loading.style.display;
		}
	}

}
/*-----------------------------------ENDS---------------------------------------*/

/*---------------------------FOR YAHOOMAIL STYLE BUTTON MENU------------*/
function initButtonMenu(DivMenu) 		
{
	if (window.createPopup && !window.XmlHttpRequest){ //if IE5.5 to IE6, create iframe for iframe shim technique
		if(this.shimobject==null)
		{
			document.write('<IFRAME id="iframeshim1"  src="" style="background-color:#3b73b9;display: none; left: 0; top: 0; z-index: 90; position: absolute; filter: progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)" frameBorder="0" scrolling="no" ></IFRAME>')
			this.shimobject=document.getElementById("iframeshim1") //reference iframe object
		}
	}
	if (document.getElementById) 
	{
		/* For NON-IE*/
		document.getElementById(DivMenu).onclick=function(e)
		{
			if (!e) var e = window.event;
				e.cancelBubble = true;
			if (e.stopPropagation) e.stopPropagation();
		}
	}
	if (document.all&&document.getElementById) 
	{
		var divNode = document.getElementById(DivMenu);
		/* For IE*/
		divNode.onclick=function()
		{
			var e = window.event;
			e.cancelBubble = true;
		}
	 	for (i=0; i<divNode.childNodes.length; i++) 
		{
			var ulNode = divNode.childNodes[i];
			if (typeof(ulNode.nodeName)!="undefined" || ulNode.nodeName!=null)
			{
				if (ulNode.nodeName=="UL") 
				{
					for (j=0; i<ulNode.childNodes.length; j++) 
					{
						var liNode = ulNode.childNodes[j];
						if (typeof(liNode.nodeName)!="undefined" || liNode.nodeName!=null)
						{	
							if (liNode.nodeName=="LI") 
							{
								liNode.onmouseover=function() 
								{
									this.className+=" hover";
								}
								liNode.onmouseout=function() 
								{
									this.className=this.className.replace(" hover", "");
							   	}
				   			}
						}
					}
			  		break;
	   			}
			}
  		}
	}
}
document.onclick=Document_Click;
window.BolShowMenu = true;
function Document_Click()
{
	if(!window.BolShowMenu)
		hideCurrentPopup();
	window.BolShowMenu = false;						
}
function showButtonMenu(btn,el,offsettype,inPopUpWindow){
	var offsettype = (offsettype == null) ? "buttom-buttom" : offsettype;
	var xpos=0;
	var ypos=0;
	hideCurrentPopup();
	window.BolShowMenu = true;
	window.currentlyVisiblePopup = el;
	if (document.getElementById){
		var mel=document.getElementById(el)
		mel.style.display = "block";
		if (offsettype=="buttom-buttom")
		{
			xpos=getposOffset(btn, "left");
			ypos=getposOffset(btn, "top")+btn.offsetHeight;
		}
		else if (offsettype=="left-top")
		{
			xpos=getposOffset(btn, "left")-mel.offsetWidth+btn.offsetWidth;
			ypos=getposOffset(btn, "top")+btn.offsetHeight;
		}
		else if (offsettype=="left-up")
		{
			xpos=getposOffset(btn, "left")-mel.offsetWidth+btn.offsetWidth;
			ypos=getposOffset(btn, "top")-mel.offsetHeight;
		}
		else if (offsettype=="right-up")
		{
			xpos=getposOffset(btn, "left");
			ypos=getposOffset(btn, "top")-mel.offsetHeight;
		}
		else if (offsettype=="center")
		{
			var fullHeight = getViewportHeight();
			var fullWidth = getViewportWidth();
			ypos= fullHeight/2 - mel.offsetHeight/2 + getScrollTop();
			xpos= fullWidth/2 - mel.offsetWidth/2;
		}
		/*------------To Adjust with Rounded Contented Div START---------------------*/
		/*---------In pupup window it is not applicable---------*/
		if(inPopUpWindow==undefined){
			if (/msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent)){ //Ie
				xpos=xpos-192;
				ypos=ypos-112;
			}else{//Non Ie
				xpos=xpos-192;
				ypos=ypos-110;
			}
		}
		/*------------To Adjust with Rounded Contented Div END---------------------*/
		mel.style.left=xpos+"px"
		mel.style.top=ypos+"px"
		mel.style.visibility="visible";
		if (window.createPopup && !window.XmlHttpRequest)
		{
			this.shimobject.style.width = mel.offsetWidth+"px";
			this.shimobject.style.height = mel.offsetHeight+"px";
			this.shimobject.style.top = mel.style.top;
			this.shimobject.style.left = mel.style.left;
			mel.style.zIndex=1000;
		    this.shimobject.style.zIndex = mel.style.zIndex - 1;
			this.shimobject.style.display = "block";
			this.shimobject.style.visibility="visible";
		}
		return false;
	}
	else
		return true
}
function getposOffset(overlay, offsettype){
	var totaloffset=(offsettype=="left")? overlay.offsetLeft : overlay.offsetTop;
	var parentEl=overlay.offsetParent;
	while (parentEl!=null){
		totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
		parentEl=parentEl.offsetParent;
	}
	return totaloffset;
}
function hideCurrentPopup() 
{
    if(window.currentlyVisiblePopup) 
    {
    	var el=window.currentlyVisiblePopup;
		if (document.getElementById){
			var mel=document.getElementById(el)
			mel.style.visibility="hidden";
			mel.style.display="none";
			if (window.createPopup && !window.XmlHttpRequest)
				if(this.shimobject!=null)
				{
					this.shimobject.style.display = "none";
					this.shimobject.style.visibility="hidden";
				}
		}
		window.currentlyVisiblePopup = false;
    }
}
 
/**
*
*  UTF-8 data encode / decode
*  http://www.webtoolkit.info/
*
**/

var Utf8 = {

	// public method for url encoding
	encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},

	// public method for url decoding
	decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;

		while ( i < utftext.length ) {

			c = utftext.charCodeAt(i);

			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}
}
/*
 * ------------------------------------END-------------------------------------------
 */


/*
 * FOLLWING SECTION OF CODE IS USED FOR DHTML MODAL POPUP WINDOW
 */
/*-----------------------------------------SATRTS----------------------------------*/
/**
 * COMMON DHTML FUNCTIONS
 * These are handy functions I use all the time.
 *
 * By Seth Banks (webmaster at subimage dot com)
 * http://www.subimage.com/
 *
 * Up to date code can be found at http://www.subimage.com/dhtml/
 *
 * This code is free for you to use anywhere, just keep this comment block.
 */

/**
 * X-browser event handler attachment and detachment
 * TH: Switched first true to false per http://www.onlinetools.org/articles/unobtrusivejavascript/chapter4.html
 *
 * @argument obj - the object to attach event to
 * @argument evType - name of the event - DONT ADD "on", pass only "mouseover", etc
 * @argument fn - function to call
 */
function addEvent(obj, evType, fn){
 if (obj.addEventListener){
    obj.addEventListener(evType, fn, false);
    return true;
 } else if (obj.attachEvent){
    var r = obj.attachEvent("on"+evType, fn);
    return r;
 } else {
    return false;
 }
}
function removeEvent(obj, evType, fn, useCapture){
  if (obj.removeEventListener){
    obj.removeEventListener(evType, fn, useCapture);
    return true;
  } else if (obj.detachEvent){
    var r = obj.detachEvent("on"+evType, fn);
    return r;
  } else {
    alert("Handler could not be removed");
  }
}

/**
 * Code below taken from - http://www.evolt.org/article/document_body_doctype_switching_and_more/17/30655/
 *
 * Modified 4/22/04 to work with Opera/Moz (by webmaster at subimage dot com)
 *
 * Gets the full width/height because it's different for most browsers.
 */
function getViewportHeight() {
	if (window.innerHeight!=window.undefined) return window.innerHeight;
	if (document.compatMode=='CSS1Compat') return document.documentElement.clientHeight;
	if (document.body) return document.body.clientHeight; 

	return window.undefined; 
}
function getViewportWidth() {
	var offset = 17;
	var width = null;
	if (window.innerWidth!=window.undefined) return window.innerWidth; 
	if (document.compatMode=='CSS1Compat') return document.documentElement.clientWidth; 
	if (document.body) return document.body.clientWidth; 
}

/**
 * Gets the real scroll top
 */
function getScrollTop() {
	if (self.pageYOffset) // all except Explorer
	{
		return self.pageYOffset;
	}
	else if (document.documentElement && document.documentElement.scrollTop)
		// Explorer 6 Strict
	{
		return document.documentElement.scrollTop;
	}
	else if (document.body) // all other Explorers
	{
		return document.body.scrollTop;
	}
}
function getScrollLeft() {
	if (self.pageXOffset) // all except Explorer
	{
		return self.pageXOffset;
	}
	else if (document.documentElement && document.documentElement.scrollLeft)
		// Explorer 6 Strict
	{
		return document.documentElement.scrollLeft;
	}
	else if (document.body) // all other Explorers
	{
		return document.body.scrollLeft;
	}
}
/*
**  Returns the caret (cursor) position of the specified text field.
**  Return value range is 0-oField.length.
*/
function getCursorPosition(oField){
	//Initialize
	var iCaretPos = 0;
	//IE Support
	if(document.selection){ 
		//Set focus on the element
		oField.focus();
		//To get cursor position, get empty selection range
		var oSel = document.selection.createRange ();
		//Move selection start to 0 position
		oSel.moveStart ('character', -oField.value.length);
		//The caret position is selection length
		iCaretPos = oSel.text.length;
	}
	//Firefox support
	else if (oField.selectionStart || oField.selectionStart == '0')
		iCaretPos = oField.selectionStart;
   	//Return results
	return (iCaretPos);
}
/*
**  Sets the caret (cursor) position of the specified text field.
**  Valid positions are 0-oField.length.
*/
function setCursorPosition (oField, iCaretPos) {
   // IE Support
   if (document.selection) { 
     // Set focus on the element
     oField.focus ();
     // Create empty selection range
     var oSel = document.selection.createRange ();
     // Move selection start and end to 0 position
     oSel.moveStart ('character', -oField.value.length);
     // Move selection start and end to desired position
     oSel.moveStart ('character', iCaretPos);
     oSel.moveEnd ('character', 0);
     oSel.select ();
   }
   // Firefox support
   else if (oField.selectionStart || oField.selectionStart == '0') {
     oField.selectionStart = iCaretPos;
     oField.selectionEnd = iCaretPos;
     oField.focus ();
   }
}
//-------------------------------Index Of In Arrary---------------------------
[].indexOf || (Array.prototype.indexOf = function(v){
    for(var i = this.length; i-- && this[i] !== v;);
    return i;
});	
//--------------------------------Trim Funtion for String--------------------
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ''); };
//-----------------------IsNumeric Prototye------------------------------------
String.prototype.isNumeric = function(){
	if(this.length==0) return false;
   	var ValidChars = "0123456789.";
   	var IsNumber=true;
   	var Char;
   	for (i = 0; i < this.length && IsNumber == true; i++){ 
      	Char = this.charAt(i); 
      	if (ValidChars.indexOf(Char) == -1){
         	IsNumber = false;
      	}
   	}
   	return IsNumber;
};	

function allowNumericOnly(e,t){
	var keyCode = document.all? window.event.keyCode:e.which;
	if((keyCode == 8 )||(keyCode == 9) || (keyCode == 12) || (keyCode == 27) || (keyCode == 37) || (keyCode == 39) || (keyCode == 46) || (keyCode == 110)){
		return true;
	}
	if((keyCode >= 48 && keyCode <= 57)||(keyCode >= 96 && keyCode <= 105)){
		return true;
	}else{
		return false;
	}
}

/*Given a select box and selected value*/

/*------------------------------------ENDS----------------------------------------*/




