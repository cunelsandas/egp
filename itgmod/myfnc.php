<?php
####################### connect database ##########################
#### Connect Mysqli ######
function connect(){
	global $g_user;
	global $g_pw;
	global $g_db;

	$server="localhost";
	$user=$g_user;
	$pw=$g_pw;
	$db=$g_db;
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

function EscapeValue($string){
	$con=connect();
	//$value=$con->real_escape_string(strip_tags($string));
	$string1=strip_tags($string);
	$value=mysqli_real_escape_string($con,$string1);
	$con->close();
	return $value;
	

}

###################### Function Query ลดขั้นตอนการเขียนโค้ด  ##############
function rsQuery($sql){ 
	$con=connect();
	
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

###################### Find Data
function FindRS($sql,$FieldName)
{
	$con=connect();
	
	$con->set_charset("utf8");
	$rs=$con->query($sql);
	
	
	if ($rs) {
	
		$row = $rs->fetch_assoc();
		$findvalue=$row[$FieldName];
		return $findvalue;
	}
		else {
		return "Not Found";
	}
	$rs->free();
	$con->close();
}

###################### Search Filename 
//SearchFileName ค้นหาไฟล์ในตาราง tb_filename ส่งค่ากลับมาเป็นชื่อไฟล์
function SearchFileName($tablename,$masterid){
	$con=connect();

	$strsql="select * from filename where tablename='".$tablename."' And masterid='".$masterid."' Order by Rand() Limit 1";
	$dbquery=$con->query($strsql);
	//$rs_row=$dbquery->num_rows;
	if($dbquery){
		$rs=mysqli_fetch_assoc($dbquery);
		$filename=$rs['filename'];
		return $filename;
	}else{
		return "ไม่พบเอกสาร";
	}

	$con->close();

}

####################### แสดงรูปภาพแบบ สุ่ม
function SearchImage($tablename,$masterid,$foldername,$default_image){
	//สำหรับค้นหาภาพ ถ้าเป็น pdf ให้แสดงไฟล์ pdf.jpg ถ้าไม่พบไฟล์ให้แสดงไฟล์ notfound.jpg
	// ค่า default_image ถ้าส่งมาเป็น 0 แสดงว่าให้ใช้ค่าdefault หรือสามารถกำหนดชื่อไฟล์ที่ต้องการให้แสดงได้เอง
					$con=connect();
					$strSql="select * from filename where tablename='".$tablename."' AND masterid='".$masterid."' Order by Rand() limit 1";
					$rs2=$con->query($strSql);
					$rs_filename=mysqli_fetch_assoc($rs2);
					$type=strtolower(substr($rs_filename['filename'],-3));
					$cpic=file_exists($foldername.$rs_filename['filename']);
				if(!empty($rs_filename)){
					if($cpic){
						if($type=='pdf'){
							$filepath="images/pdf.gif";
						}else{
							$filepath=$foldername.$rs_filename['filename'];
						}
					}else{
						if($default_image=="0"){
						$filepath="images/notfound.jpg";
						}else{
							$filepath="images/".$default_image;
						}
					}
				}else{
					$filepath="images/notfound.jpg";
				}

					return $filepath;
					$con->close();
}

####################### แสดงรูปภาพ ภาพแรกที่บันทึก
function ShowImage($tablename,$masterid,$foldername,$default_image,$filter){
	//สำหรับค้นหาภาพ ถ้าเป็น pdf ให้แสดงไฟล์ pdf.jpg ถ้าไม่พบไฟล์ให้แสดงไฟล์ notfound.jpg
	// ค่า default_image ถ้าส่งมาเป็น 0 แสดงว่าให้ใช้ค่าdefault หรือสามารถกำหนดชื่อไฟล์ที่ต้องการให้แสดงได้เอง
					$con=connect();
					$strSql="select * from filename Where id='".$masterid."'";
					$rs2=$con->query($strSql);
					$rs_filename=$rs2->fetch_assoc();
					$type=strtolower(substr($rs_filename['filename'],-3));
					$cpic=file_exists($foldername.$rs_filename['filename']);
					
					if($cpic){
						if($type=='pdf'){
							$filepath="<a href=\"".$foldername.$rs_filename['filename']."\" target=\"_blank\"><img src=\"images/pdf.gif\"></a>";
						}else{
							$filepath="<a href=\"".$foldername.$rs_filename['filename']."\" target=\"_blank\"><img src=\"".$foldername.$rs_filename['filename']."\" class='$filter'></a>";
						}
					}else{
						if($default_image=="0"){
						$filepath="<img src=\"images/notfound.jpg\">";
						}else{
							$filepath="<img src=\"images/".$default_image."\">";
						}
					}

					return $filepath;
					$con->close();
}
########################## 
function chkPath(){
	$configpath=$_SERVER['DOCUMENT_ROOT'].""; // ตัวกำหนด PATH ของ ที่เก็บไฟล์
	$dochkpath=file_exists($configpath);
	if(!$dochkpath){
	//echo"<p style=\"font-size:14px;font-weight:bold;color:red;\">กรุณาตรวจสอบ ตัวแปร \$configpath ใน connect.inc.php ด้วยค่ะ มีการกำหนดไม่ถูกต้อง</p>";
	//echo"<p style=\"font-size:14px;font-weight:bold;color:red;\">ตัวแปร \$configpath คือ $configpath </p>";
	//exit();
		return "ไม่พบ".$configpath;
		exit();
	}else{
		return $configpath;
	}
}



########################## จบ Function #############################################################

function thaidate($vardate="") { 
	$_month_name = array("01"=>"มกราคม",  "02"=>"กุมภาพันธ์",  "03"=>"มีนาคม",  
    "04"=>"เมษายน",  "05"=>"พฤษภาคม",  "06"=>"มิถุนายน",  
    "07"=>"กรกฎาคม",  "08"=>"สิงหาคม",  "09"=>"กันยายน",  
    "10"=>"ตุลาคม", "11"=>"พฤศจิกายน",  "12"=>"ธันวาคม");
	
	/*
	$yy =substr($vardate,0,4);
	$mm =substr($vardate,5,2);
	$dd =substr($vardate,8,2);
	*/
	$yy=substr($vardate,0,4);
	$mm=substr($vardate,5,2);
	$dd=substr($vardate,8,2);

	$yy += 543;
	if ($yy==543){
		$dateT = "-";
	}else{
		$dateT=$dd ." ".$_month_name[$mm]."  ".$yy;
	 }
  return $dateT;
} 
$timedate=date("m-d-Y");

function DateTimeThai($strDate)
{
$strYear = date("Y",strtotime($strDate))+543;
$strMonth= date("n",strtotime($strDate));
$strDay= date("j",strtotime($strDate));
$strHour= date("H",strtotime($strDate));
$strMinute= date("i",strtotime($strDate));
$strSeconds= date("s",strtotime($strDate));
$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
$strMonthThai=$strMonthCut[$strMonth];
return "$strDay $strMonthThai $strYear เวลา : $strHour:$strMinute:$strSeconds";
} 
function DateThai($strDate)
{
$strYear = date("Y",strtotime($strDate))+543;
$strMonth= date("n",strtotime($strDate));
$strDay= date("j",strtotime($strDate));
$strHour= date("H",strtotime($strDate));
$strMinute= date("i",strtotime($strDate));
$strSeconds= date("s",strtotime($strDate));
$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
$strMonthThai=$strMonthCut[$strMonth];
return "$strDay $strMonthThai $strYear"; //, $strHour:$strMinute";
} 
function MyTime($strDate)
{
$strYear = date("Y",strtotime($strDate))+543;
$strMonth= date("n",strtotime($strDate));
$strDay= date("j",strtotime($strDate));
$strHour= date("H",strtotime($strDate));
$strMinute= date("i",strtotime($strDate));
$strSeconds= date("s",strtotime($strDate));
$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
$strMonthThai=$strMonthCut[$strMonth];
return "$strHour:$strMinute:$strSeconds";
} 




function CheckRude($temp){

$con=connect();
$wordchange = ("<font color=red>*</font>") ; // ข้อความที่ต้องการแทนที่คำหยาบ

$sql = "select * from rude";
$dbquery = $con->query($sql);
if($dbquery){
	$num_rows = $dbquery->num_rows($dbquery);
}else{
	$num_rows="0";
}
$i=0;
while ($i < $num_rows)
{
$result= mysqli_fetch_array($dbquery);
$temp = eregi_replace ("$result[rude_name]" ,$wordchange ,$temp);
$i++;
}

return ( $temp ) ;
$con->close();
}


function UpdateTrans($tablename,$action,$username,$detail){
	$con=connect();
	$sql="INSERT INTO tb_trans(tablename,action,username,detail) VALUES('".$tablename."','".$action."','".$username."','".$detail."')";
	$rs=$con->query($sql);
	$con->close();
}

   
//หาวันแรกของเดือน
function firstOfMonth() {
return date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00'));
}
######################

//หาวันสุดท้ายของเดือน
function lastOfMonth() {
return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));
}

function DateDiff($strDate1,$strDate2)
	{
		return (strtotime($strDate2) - strtotime($strDate1))/ ( 60 * 60 * 24 ); // 1 day = 60*60*24
	}

function TimeDiff($strTime1,$strTime2)
{
return (strtotime($strTime2) - strtotime($strTime1))/ ( 60 * 60 ); // 1 Hour = 60*60
}

function DateTimeDiff($strDateTime1,$strDateTime2)
{
return (strtotime($strDateTime2) - strtotime($strDateTime1))/ ( 60 * 60 ); // 1 Hour = 60*60
}




//เข้ารหัสและถอดรหัส url

function base64url_encode($data) {
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}
//แก้ขื่อฟังชั่นให้สั้นลง
function encode64($data) {
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function decode64($data) {
  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

function msgLastUpdate($edittime,$edituser){
	$editday=DateThai($edittime);
	$edittime=date('H:i:s',strtotime($edittime));
	
	return "<b>แก้ไขล่าสุดเมื่อ :</b> &nbsp;".$editday."&nbsp;&nbsp;<b>เวลา :</b>$edittime&nbsp;&nbsp;<b>โดย :</b>&nbsp;".$edituser;
}

#check file type 3 ตัวขวาสุด
function checkfiletype($filename){
	if($filename<>""){
		$type=substr($filename,-3,3);
	}
	return $type;
}

function resizeimage($tmp_images,$tmp_newfile,$tmp_foldername,$tmp_text,$tmp_width,$tmp_showwatermark){  // $images ไฟล์ต้นฉบับ , $newfile ชื่อไฟล์ใหม่ , $foldername ที่เก็บไฟล์ , $tmp_text ลายน้ำ , $tmp_width ผู้ใช้กำหนดความกว้างเอง ค่าdefault=0ระบบกำหนดให้,$tmp_showwatermark ให้แสดง่ลายน้ำหรือไม่ true แสดง false ไม่แสดง default=true
				$images=$tmp_images;
				$newfile=$tmp_newfile;
				$foldername=$tmp_foldername;
				if($tmp_showwatermark=='false'){
					$watermark="";
				}else{
					$watermark=$tmp_text;
				}
				if($tmp_width=='0'){
					$width=800;   // กำหนดความกว้างของรูปใหม่
				}else{
					$width=$tmp_width;  // ถ้ามีการส่งค่ามาให้ใช้ค่าที่ส่งมา
				}
				$size=getimagesize($images);  // $size[0]=width , [1]=height ,[2]=imagetype ค่า 1=gif ,2=jpg ,3=png ,4=swf,5=psd,6=bmp
				//$images_orig = ImageCreateFromJPEG($images);
				if($size[0]<$width){  // ถ้าขนาดรูปเล็กกว่าค่าที่กำหนด ให้ใช้ขนาดรูปแทนค่าที่กำหนด
						$width=$size[0];
				}
				$height=round($width*$size[1]/$size[0]);  // [1]=height , [0]=width
				switch($size["mime"]){
					case "image/jpeg":
						$images_orig = imagecreatefromjpeg($images); //jpeg file
						break;
					 case "image/gif":
						$images_orig = imagecreatefromgif($images); //gif file
						break;
					case "image/png":
						$images_orig = imagecreatefrompng($images); //png file
						break;	
				}

				//พิมพ์ลายน้ำ
				$scale=$size[0]/$width; //หาขนาดอัตราส่วนความยาวfont ให้เต็มขนาดความกว้างของรูป
				$fontsize=60*$scale;  //กำหนดขนาด font
				$textposition=$fontsize*1.15;  //กำหนดตำแหน่ง x,y
				$font = 'font/angsaz.ttf';  //ให้วางไว้ใน wms
				//$textcolor = imagecolorallocate($images_orig,245,245,245); // สีตัวอักษรwhite smoke  light blue(173,216,230)   gray(190,190,190)  snow3(205,201,201)
				$textcolor = imagecolorallocatealpha($images_orig,245,245,245,75);
				imagettftext($images_orig, $fontsize, 0, $textposition,$textposition, $textcolor, $font, $watermark);
				//จบ พิมพ์ลายน้ำ

				$photoX = ImagesX($images_orig);
				$photoY = ImagesY($images_orig);
				$images_fin = ImageCreateTrueColor($width, $height);

				ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
				switch($size["mime"]){
					case "image/jpeg":
						ImageJPEG($images_fin,$_SERVER['DOCUMENT_ROOT'].$foldername.$newfile);
						break;
					case "image/gif":
						ImageGIF($images_fin,$_SERVER['DOCUMENT_ROOT'].$foldername.$newfile);
						break;
					case "image/png":
						ImagePNG($images_fin,$_SERVER['DOCUMENT_ROOT'].$foldername.$newfile);
						break;
				}
				ImageDestroy($images_orig);
				ImageDestroy($images_fin);
				return "Upload Image Complete";
	}		

	



	function ThaiNum($num){  
    return str_replace(array( '0' , '1' , '2' , '3' , '4' , '5' , '6' ,'7' , '8' , '9' ),  
    array( "o" , "๑" , "๒" , "๓" , "๔" , "๕" , "๖" , "๗" , "๘" , "๙" ),  
    $num);  
}  

/*function isImage($filename){
	$imagetype=exif_imagetype($filename);
	switch($imagetype){
		case 'IMAGETYPE_GIF':
			return 'image';
			break;
		case 'IMAGETYPE_JPEG':
			return 'image';
			break;
		case 'IMAGETYPE_PNG':
			return 'image';
			break;
		case 'IMAGETYPE_BMP':
			return 'image';
			break;
		default:
			return 'no';
			break;

	}

} */
function isImage($filename){
	$img = getimagesize($filename);
	if($img!== false){
			return "image";
	}else{
		return "no";
	}
}

// ทำfunction แทน mysql_result  ใน mysqli
	function mysqli_result($result, $row, $field = 0) {
    // Adjust the result pointer to that specific row
    $result->data_seek($row);
    // Fetch result array
    $data = $result->fetch_assoc();
 
    return $data[$field];
	$result->free();
}

function ChangeYear($strDate,$strType){
	// $strType คือประเภท en คศ , th พศ
	if ($strDate==""){
		return "";
	}

	if($strDate=="0000-00-00"){
		return "0000-00-00";
	}
	else {
		
		$tmpdate=explode("-",$strDate);
			list($tmpYear,$tmpMonth,$tmpDate)=$tmpdate;
		if($strType=="en"){
			$engYear=$tmpYear-543;
		}elseif($strType=="th"){
			$engYear=$tmpYear+543;
		}else{
			$engYear="0000";
		}
	return "$engYear-$tmpMonth-$tmpDate";
	
	}
}

function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
	$device="";
	
	if (preg_match('/mobile/i', $u_agent)) {
        $device = 'Mobile';

    }else{
		$device="Computer";
	}
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'Linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    }
	elseif(preg_match('/Android/i',$u_agent)){
		$platform='Android';
	}
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
   
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern,
		'device'		=> $device
    );
}


function GraphHorizon($ulWidth,$totalvote,$votecount,$voteid,$votename,$spanstyle,$graphcolor){
	// ulWidth ความยาวส่วนแสดงผลทั้งหมด , liWidth ความยาวของกราฟแต่ละแท่ง , totalvote จำนวนคะแนนทั้งหมด  
	// votecount คะแนนแต่ละหัวข้อ , voteid ไอดีของหัวข้อ , votename ชื่อแต่ละหัวข้อ
	// spanstyle กำหนดค่าตัวอักษร default= 0 
	// graphcolor = สีแท่ง  default =0
	if(empty($spanstyle)){
		$span_style="width:100%;text-align:right;z-index:10;position:absolute;top:30%;padding-left:0px;text-shadow:2px 2px 1px #fff;";
	}else{
		$span_style=$spanstyle;
	}
	if(empty($graphcolor)){
		$color = sprintf("#%06x",rand(0,16777215));
	}else{
		$color=$graphcolor;
	}
	$votecount=empty($votecount)?0:$votecount;
	$percent=round(((($votecount/$totalvote)*100)),2);   //percent ความยาว
	$liwidth=round(($percent/100)*$ulWidth,2);  // ความยาว
	$graph= '<style>
	.progress'.$voteid.' {
			width:500px;
			height:40px;
			text-align:left;
		    margin-bottom:10px;
			position:relative;
			}
	.progress'.$voteid.':after {
		content:"";
		position:absolute;
		background:'.$color.';
		top:0; bottom:0;
		left:0; 
		opacity:0.9;
		z-index:1;
		border-top-right-radius:100px;
		border-bottom-right-radius:100px;
		width:'.$liwidth.'px; 
		}
	</style>
		<li class="progress'.$voteid.'">
			<span style="'.$span_style.'">'.$votename.'&nbsp;&nbsp;=&nbsp;'.$votecount.'&nbsp;คะแนน&nbsp;'.$percent.'%</span>
		</li>';
	return $graph;
}

function ImageSlide($slidename,$width,$height,$axis,$border){
		//$slidename ชื่อ , axis สไลด์แนวนอน x  เลื่อนขึ้นy
		if(empty($axis)){
			$axis=x;  // default แนวนอน
		}
		if(empty($border)){
			$border=0;
		}
		$li_width=$width-4;
		$li_height=$height-4;
		$style='<script type="text/javascript">
		$(document).ready(function()
		{
			$("#'.$slidename.'").tinycarousel({ interval: true , axis:"'.$axis.'" });
		});
	</script>
	<style>
						#'.$slidename.' { height: 1%; overflow: hidden; padding: 0 0 10px;}
						#'.$slidename.' .viewport { float: left; width: '.$width.'px; height: '.$height.'px; overflow: hidden; position: relative; }
						#'.$slidename.' .disable { visibility: hidden; }
						#'.$slidename.' .overview { list-style: none; position: absolute; padding: 0; margin: 0; width: 240px; left: 0; top: 0; }
						#'.$slidename.' .overview li { float: left; margin: 0 20px 0 0; padding: 1px; height: '.$li_height.'px; border: '.$border.'px solid #dcdcdc; width: '.$li_width.'px; }
						#'.$slidename.' .overview li:before {content: "";}
					</style>';
		return $style;
}

function CheckIdCard($personID){
	if (strlen($personID) != 13) {
	 return false;
	}

	$rev = strrev($personID); 
	$total = 0;
	for($i=1;$i<13;$i++) {
	$mul = $i +1;
	$count = $rev[$i]*$mul; 
	$total = $total + $count; 
}
$mod = $total % 11; 
$sub = 11 - $mod; 
$check_digit = $sub % 10;

 if($rev[0] == $check_digit){ 
		return true;
	 }else{
		 return false;
	}
}

function SendMail($mailto,$mailsubject,$mailmesssage,$mailfrom){
		// Send Email
				
				$MailTo = $mailto;
				$MailFrom = $mailfrom;
				$subject = $mailsubject;
				$MailSubject = '=?UTF-8?B?'.base64_encode($subject).'?=' ;
				$MailMessage = $mailmessage;
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n" ;
				$headers .= "Reply-To: The Sender <".$MailFrom.">\r\n"; 
				$headers .= "Return-Path: The Sender <".$MailFrom.">\r\n"; 
				$headers .= "From: System mail <".$MailFrom.">\r\n";  
				 $headers .= "Organization: I.T.Global\r\n";
				$headers .= "X-Priority: 3\r\n";
				$headers .= "X-Mailer: PHP". phpversion() ."\r\n" ;
				 if(mail($MailTo, $MailSubject , $MailMessage, $headers, $MailFrom)) {
					 $msg= "true" ;  //ส่งเรียบร้อย
				}else{
					$msg= "false" ; //ไม่สามารถส่งเมล์ได้
				 }
				 return $msg;
				//End Send Email

				
}

function format_personid($id)
{
    $id = preg_replace("/^\d/", "", $id);

    if(strlen($id) == 13){
        return preg_replace("/(\d{1})(\d{4})(\d{5})(\d{2})(\d{1})/", "$1-$2-$3-$4-$5", $id);
	}else{
    
        return $id;
	}
}

function CheckPersonId($personID)
{

//if (strlen($personID) != 13) {
// return false;
//} ctype_digit

$rev = strrev($personID); // reverse string ¢Ñé¹·Õè 0 àµÃÕÂÁµÑÇ
$total = 0;
for($i=1;$i<13;$i++) // 
{
 $mul = $i +1;
 $count = $rev[$i]*$mul; //
 $total = $total + $count; // 
}
$mod = $total % 11; //
$sub = 11 - $mod; //
$check_digit = $sub % 10; //Check Digit

 if($rev[0] == $check_digit) { // 
     return "true"; ///
 }else{
     return "false"; // 
 }
}

//คำนวณอายุ วัน เวลา
function timespan($seconds = 1, $time = '')
{
	if ( ! is_numeric($seconds))
	{
		$seconds = 1;
	}
 
	if ( ! is_numeric($time))
	{
		$time = time();
	}
 
	if ($time <= $seconds)
	{
		$seconds = 1;
	}
	else
	{
		$seconds = $time - $seconds;
	}
 
	$str = '';
	$years = floor($seconds / 31536000);
 
	if ($years > 0)
	{	
		$str .= $years.' ปี, ';
	}	
 
	$seconds -= $years * 31536000;
	$months = floor($seconds / 2628000);
 
	if ($years > 0 OR $months > 0)
	{
		if ($months > 0)
		{	
			$str .= $months.' เดือน, ';
		}	
 
		$seconds -= $months * 2628000;
	}
 
	$weeks = floor($seconds / 604800);
 
	if ($years > 0 OR $months > 0 OR $weeks > 0)
	{
		if ($weeks > 0)
		{	
			$str .= $weeks.' สัปดาห์, ';
		}
 
		$seconds -= $weeks * 604800;
	}			
 
	$days = floor($seconds / 86400);
 
	if ($months > 0 OR $weeks > 0 OR $days > 0)
	{
		if ($days > 0)
		{	
			$str .= $days.' วัน, ';
		}
 
		$seconds -= $days * 86400;
	}
 
	$hours = floor($seconds / 3600);
 
	if ($days > 0 OR $hours > 0)
	{
		if ($hours > 0)
		{
			$str .= $hours.' ชั่วโมง, ';
		}
 
		$seconds -= $hours * 3600;
	}
 
	$minutes = floor($seconds / 60);
 
	if ($days > 0 OR $hours > 0 OR $minutes > 0)
	{
		if ($minutes > 0)
		{	
			$str .= $minutes.' นาที, ';
		}
 
		$seconds -= $minutes * 60;
	}
 
	if ($str == '')
	{
		$str .= $seconds.' วินาที';
	}
 
	return substr(trim($str), 0, -1);
}
// ตัวอย่างการใช้งาน
// $birthdate = strtotime( '1973-11-13' );
// $today = time();
 
//  echo timespan( $birthdate , $today );
//36 ปี, 2 เดือน, 3 สัปดาห์, 2 วัน, 4 ชั่วโมง, 51 นาที


function calage($seconds = 1, $time = '')
{
	if ( ! is_numeric($seconds))
	{
		$seconds = 1;
	}
 
	if ( ! is_numeric($time))
	{
		$time = time();
	}
 
	if ($time <= $seconds)
	{
		$seconds = 1;
	}
	else
	{
		$seconds = $time - $seconds;
	}
 
	$str = '';
	$years = floor($seconds / 31536000);
 
	if ($years > 0)
	{	
		$str .= $years.' ปี, ';
	}	
 
	$seconds -= $years * 31536000;
	$months = floor($seconds / 2628000);
 
	if ($years > 0 OR $months > 0)
	{
		if ($months > 0)
		{	
			$str .= $months.' เดือน, ';
		}	
 
		$seconds -= $months * 2628000;
	}
 
	$age=$years;
 
	return $age;
}

function getAge($birthday) {
$then = strtotime($birthday);
$check=strtotime(date('Y-09-30'));
//return(floor((time()-$then)/31556926));
return(floor(($check-$then)/31556926));
}
include_once("wms_fnc.php");
?>