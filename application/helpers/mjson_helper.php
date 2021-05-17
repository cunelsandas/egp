<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 11/8/2561
 * Time: 9:06
 */
function m_json_encode($data = array())
{
    $ci =& get_instance();
    $ci->output->set_content_type('application/json')->set_output(json_encode($data));
}

function date_thai($strDate)
{
    if ($strDate != '') {
        $strMonthCut = Array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        list ($y, $m, $d) = explode('-', $strDate);
        $strMonthThai = $strMonthCut[number_format($m)];
        $dob = sprintf("%02d $strMonthThai %04d", $d, $y + 543);

        return $dob;
    } else {
        return $strDate;
    }
}

function date_pick2php_date($strDate)
{
    if ($strDate != '') {
        $Date = explode('/', $strDate);
        return $Date[2] - 543 . '-' . $Date[1] . '-' . $Date[0];
    }
    return '0000-00-00';
}

function php_date2date_pick($strDate)
{
    if ($strDate != '') {
        list ($y, $m, $d) = explode('-', $strDate);
        $dob = sprintf("%02d/%02d/%04d", $d, $m, $y + 543);
        return $dob;
    }
}

function set_no_array($array, $no)
{
    $data = array();
    foreach ($array as $key => $item) {
        if (!in_array($key, $no)) {
            $data[$key] = $item;
        }
    }
    return $data;
}