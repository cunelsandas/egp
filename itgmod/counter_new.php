<?php
	
	$strSQL = " SELECT DATE FROM new_counter LIMIT 0,1";
	$objQuery = rsQuery($strSQL);

	$objResult =mysqli_fetch_assoc($objQuery);
	if($objResult["DATE"] != date("Y-m-d"))
	{
		//*** บันทึกข้อมูลของเมื่อวาน ***//
		$strSQL = " INSERT INTO new_daily (DATE,NUM) SELECT '".date('Y-m-d',strtotime("-1 day"))."',COUNT(*) AS intYesterday FROM  new_counter WHERE 1 AND DATE = '".date('Y-m-d',strtotime("-1 day"))."'";
		rsQuery($strSQL);

		//*** ลบข้อมูลของเมื่อวาน ***//
		$strSQL = " DELETE FROM new_counter WHERE DATE != '".date("Y-m-d")."' ";
		rsQuery($strSQL);
	}

	//*** Insert new_counter ปัจจุบัน ***//
	$strSQL = " INSERT INTO new_counter (DATE,IP) VALUES ('".date("Y-m-d")."','".$_SERVER["REMOTE_ADDR"]."') ";
	rsQuery($strSQL);

	//******************** Get Counter ************************//

	// Today //
	$strSQL = " SELECT COUNT(DATE) AS CounterToday FROM new_counter WHERE DATE = '".date("Y-m-d")."' ";
	$objQuery = rsQuery($strSQL);
	$objResult =mysqli_fetch_assoc($objQuery);
	$strToday = $objResult["CounterToday"];

	// Yesterday //
	$strSQL = " SELECT NUM FROM new_daily WHERE DATE = '".date('Y-m-d',strtotime("-1 day"))."' ";
	$objQuery = rsQuery($strSQL);
	$objResult =mysqli_fetch_assoc($objQuery);
	$strYesterday = $objResult["NUM"];

	// This Month //
	$strSQL = " SELECT SUM(NUM) AS CountMonth FROM new_daily WHERE DATE_FORMAT(DATE,'%Y-%m')  = '".date('Y-m')."' ";
	$objQuery = rsQuery($strSQL);
	$objResult =mysqli_fetch_assoc($objQuery);
	$strThisMonth = $objResult["CountMonth"];

	// Last Month //
	$strSQL = " SELECT SUM(NUM) AS CountMonth FROM new_daily WHERE DATE_FORMAT(DATE,'%Y-%m')  = '".date('Y-m',strtotime("-1 month"))."' ";
	$objQuery = rsQuery($strSQL);
	$objResult =mysqli_fetch_assoc($objQuery);
	$strLastMonth = $objResult["CountMonth"];

	// This Year //
	$strSQL = " SELECT SUM(NUM) AS CountYear FROM new_daily WHERE DATE_FORMAT(DATE,'%Y')  = '".date('Y')."' ";
	$objQuery = rsQuery($strSQL);
	$objResult =mysqli_fetch_assoc($objQuery);
	$strThisYear = $objResult["CountYear"];

	// Last Year //
	$strSQL = " SELECT SUM(NUM) AS CountYear FROM new_daily WHERE DATE_FORMAT(DATE,'%Y')  = '".date('Y',strtotime("-1 year"))."' ";
	$objQuery = rsQuery($strSQL);
	$objResult =mysqli_fetch_assoc($objQuery);
	$strLastYear = $objResult["CountYear"];

	// Total //
	$strSQL = " SELECT SUM(NUM) AS CountTotal FROM new_daily";
	$objQuery = rsQuery($strSQL);
	$objResult =mysqli_fetch_assoc($objQuery);
	$strTotal = $objResult["CountTotal"];

	//*** Close MySQL ***//
	
?>

<table>
  <tr>
    <td colspan="2"><div align="center">สถิติการเข้าชม เริ่มวันที่ <?php echo $startdate;?></div></td>
  </tr>
  <tr>
    <td width="60%">วันนี้</td>
    <td width="40%"><div align="center"><?php echo number_format($strToday,0);?></div></td>
  </tr>
  <tr>
    <td>เมื่อวานนี้</td>
    <td><div align="center"><?php echo number_format($strYesterday,0);?></div></td>
  </tr>
  <tr>
    <td>เดือนนี้ </td>
    <td><div align="center"><?php echo number_format($strThisMonth,0);?></div></td>
  </tr>
  <tr>
    <td>เดือนที่แล้ว </td>
    <td><div align="center"><?php echo number_format($strLastMonth,0);?></div></td>
  </tr>
  <tr>
    <td>ปีนี้ </td>
    <td><div align="center"><?php echo number_format($strThisYear,0);?></div></td>
  </tr>
  <tr>
    <td>ปีที่แล้ว </td>
    <td><div align="center"><?php echo number_format($strLastYear,0);?></div></td>
  </tr>
   <tr>
    <td>ทั้งหมด </td>
    <td><div align="center"><?php echo number_format($strTotal,0);?></div></td>
  </tr>
   <tr>
    <td>ไอพี ของคุณ </td>
    <td><div align="center"><?php echo$_SERVER['REMOTE_ADDR'];?></div></td>
  </tr>
</table>
