<?php
####################### connect database ##########################
#### Connect Mysqli ######
function bconnect(){
	
	$server="localhost";
	$user="c1itglobal";
	$pw='^_^Itg46*_*';
	$db="c1itglobal";
	$conn = new mysqli($server, $user, $pw, $db);

/*
 * This is the "official" OO way to do it,
 * BUT $connect_error was broken until PHP 5.2.9 and 5.3.0.
 */
if ($conn->connect_error) {
    die('Connect Error (' . $conn->connect_errno . ') '. $conn->connect_error);
}
	return $conn;
}

###################### Function Query ลดขั้นตอนการเขียนโค้ด  ##############
function brsQuery($sql){ 
	$con=bconnect();
	if($sql==""){
		return false;
	}else{
		$con->set_charset("utf8");
	
		$rs=$con->query($sql);
		if($rs !== false){
		//	$error=$con->error;
			return $rs;
		}else{
			$error=$con->error;
			return $error;
		}
	}
	$rs->free();
	$con->close();
}

?>