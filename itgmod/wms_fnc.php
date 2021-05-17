	<?php
	$gloUploadFileType=array('jpeg','jpg','gif','png','bmp','pdf');

	function CheckFileUpload($strFileName,$strFileSize,$strLimitSize,$strSizeInMb,$str_x){
		$gloUploadFileType=array('jpeg','jpg','gif','png','bmp','pdf');
	//วนเช็ค file type
		$allowed=$gloUploadFileType;
		$filename=strtolower($strFileName);
		$x=$str_x;
		$SizeInMb=$strSizeInMb;
		$limitsize=$strLimitSize;
		$size=$strFileSize;
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		if($filename<>""){
			if( ! in_array( $ext, $allowed ) ) {
				$strHtml="<p>ไฟล์ที่ ".$x." ". $filename." ไม่ตรงกับไฟล์ ";
				for ($i = 0; $i < count($allowed); $i++){
					  $strHtml.= $allowed[$i] . ",";
				}
				$strHtml.= " ไม่สามารถอัพโหลดได้</p>";
				$strHtml.="<a href=\"javascript:window.history.back();\">ย้อนกลับ</a>";
				
				return array("no",$strHtml);
				exit();
			}
			if($size>$limitsize){
				$strHtml="<p>ไฟล์ที่ ".$x." มีขนาดใหญ่เกินกว่า". $SizeInMb." Mb</p>";
				$strHtml.="<a href=\"javascript:window.history.back();\">ย้อนกลับ</a>";
				
				return array("no",$strHtml);
				exit();
			}
		}
			
	}
function ShowAllowedFileUpload($strFileAllowed){
			for ($i = 0; $i < count($strFileAllowed); $i++){
			   $strFileType.= $strFileAllowed[$i] . ",";
			}
			return $strFileType;
}


	?>