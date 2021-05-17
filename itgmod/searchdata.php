<?php 
	
include_once"connect.inc.php";
$tablename=$_GET['tablename'];
$select_id=$_GET['select_id'];


switch($tablename){
	case "tb_officer_workgroup":
		$rstTemp=rsQuery("select * from $tablename Where offid=$select_id Order by listno");
		echo "<select name='workgroup' id='workgroup'><option value=\"0\">หัวหน้าส่วน/รอง</option>";
		while($arr_2=mysqli_fetch_array($rstTemp)){
			echo "<option value='". $arr_2['id']."'>".$arr_2['name']."</option>";
		}
		echo "</select>";
		break;

	case "tb_officer_subworkgroup":
	
		$rstTemp=rsQuery("select * from $tablename Where workgroupid=$select_id Order by listno");
		echo "<select name='subworkgroup' id='subworkgroup'><option value=\"0\">หัวหน้าฝ่าย/เจ้าหน้าที่</option>";
	while($arr_1=mysqli_fetch_array($rstTemp)){
		echo "<option value='". $arr_1['id']."'>".$arr_1['name']."</option>";
	}
		echo "</select>";
		break;
	
	case "showworkgroup":
		$modid=$_GET['modid'];
		$modname=FindRS("select modtype from tb_mod where modid='$modid'",modtype);
		$strTmp="select * from tb_officer_workgroup where offid='$select_id' order by listno";
		$rs=rsQuery($strTmp);
		
				echo "<div id='showdata'>";
				
				echo "<table width='90%' class='content-table'>";
				echo "<tr><th>ลำดับ</th><th>ชื่อสายงานหลัก</th><th>จัดการ</th></tr>";
			while($data=mysqli_fetch_assoc($rs)){
				$id=$data['id'];
				$name=$data['name'];
				$listno=$data['listno'];
				echo "<tr><td>$listno</td><td>$name</td><td><a href='main.php?_mod=".$modname."&_modid=".$modid."&id=".$id."' title='แก้ไขข้อมูล'>แก้ไข</a>&nbsp;&nbsp;<a href='main.php?_mod=".$modname."&_modid=".$modid."&del=".$id."' title='ลบข้อมูล' onclick=\"return confirm('ยืนยันการลบ')\">ลบ</a></td></tr>";

			}
			echo "</table>";
			echo "</div>";
			
			
		break;
	
	case "showsubworkgroup":
		$modid=$_GET['modid'];
		$modname=FindRS("select modtype from tb_mod where modid=$modid",modtype);
		$strTmp="select * from tb_officer_subworkgroup where workgroupid=$select_id order by listno";
			$rs=rsQuery($strTmp);
		
				echo "<div id='showdata'>";
				
				echo "<table width='90%' class='content-table'>";
				echo "<tr><th>ลำดับ</th><th>ชื่อสายงานรอง</th><th>จัดการ</th></tr>";
			while($data=mysqli_fetch_assoc($rs)){
				$id=$data['id'];
				$name=$data['name'];
				$listno=$data['listno'];
				echo "<tr><td>$listno</td><td>$name</td><td><a href='main.php?_mod=".$modname."&_modid=".$modid."&id=".$id."' title='แก้ไขข้อมูล'>แก้ไข</a>&nbsp;&nbsp;<a href='main.php?_mod=".$modname."&_modid=".$modid."&del=".$id."' title='ลบข้อมูล' onclick=\"return confirm('ยืนยันการลบ')\">ลบ</a></td></tr>";

			}
			echo "</table>";
			echo "</div>";
			break;
		
		
	} // end switch


?>