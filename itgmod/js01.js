/* เปลี่ยนภาพ background */
function ChangeCssBg(CssId,Cssvalue){
		document.getElementById(CssId).style.background= "url('images/"+Cssvalue+"') no-repeat top center";
		
		}

function ajaxLoad(method,URL,data,displayid){
	var ajax=null;
	if(window.ActiveXObject){
		ajax=new ActiveXObject("Microsoft.XMLHTTP");
	}else if(window.XMLHttpRequest){
		ajax=new XMLHttpRequest();
	}else{
		alert("browser not support");
		return;
	}
	method=method.toLowerCase();
	URL+="?dummy="+(new Date()).getTime();
	if(method.toLowerCase()=="get"){
		URL+="&"+data;
		data=null;
	}
	ajax.open(method,URL);
	
	if(method.toLowerCase()=="post"){
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
	}

	ajax.onreadystatechange = function(){
		if(ajax.readyState==4 && ajax.status==200){
			var ctype=ajax.getResponseHeader("Content-Type");
			ctype= ctype.toLowerCase();
			ajaxCallback(ctype,displayid,ajax.responseText);

			delete ajax;
			ajax=null;
		}
	}
	ajax.send(data);

}

function ajaxCallback(contentType,displayid,responseText){
	if(contentType.match("text/javascript")){
		eval(responseText);
	}
	else{
		var el=document.getElementById(displayid);
		el.innerHTML = responseText;
	}
}