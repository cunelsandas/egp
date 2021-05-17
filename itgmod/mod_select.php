<?php
!empty($_GET['_mod'])?$mod1=$_GET['_mod']:null;

$mod=EscapeValue(decode64($mod1));
$pathid=FindRS("select * from tb_mod where modtype='$mod'",modpath);
$web_path=FindRS("select * from tb_modpath where id=".$pathid,web_path);
$server_path=FindRS("select * from tb_modpath where id=".$pathid,server_path);  //remark on localhost
$full_path=$server_path.$web_path;
if(!empty($web_path) and $web_path<>"Not Found"){
	if(file_exists($full_path)){	
	
		echo '<script type="text/javascript" src="http://www.itglobal.co.th/js/js01.js"></script>';	
		include"$full_path";
	}else{
		echo "ไม่พบตำแหน่งไฟล์";
	}
}else{
	echo '<script type="text/javascript" src="http://www.itglobal.co.th/js/js01.js"></script>';	
	include "modules/default/default.php";
	
}

?>
