<?php
//获取IP对应的实际地址
function getIpLocation($ip){//首先使用淘宝接口
    $address = ''; 
    $location = json_decode(file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.$ip),true);
    if ($location['code'] === 0) {
        if (isset($location['data'])) {
            if (isset($location['data']['country']))
                $address .= $location['data']['country'];
            if (isset($location['data']['region']))
                $address .= $location['data']['region'];
            if (isset($location['data']['city']))
                $address .= $location['data']['city'];
            if (isset($location['data']['isp']))
                $address .= $location['data']['isp'];
        }
    } else {
        $location = json_decode(file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$ip));
        if (isset($location->desc)) {
            if (empty($location->desc)) {
                if (isset($location->province))
                    $address .= $location->province;
                if (isset($location->city))
                    $address .= $location->city;
                if (isset($location->district))
                    $address .= $location->district;
                if (isset($location->isp))
                    $address .= $location->isp;
            } else {
                $address = $location->desc; 
            }
        }
    }
    return $address;
}
//将'-'转换成''
function barsToEmpty(&$value) {
	if ($value === '-')
	    $value = '';
}
//字符串截取
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
	if(function_exists("mb_substr"))
		$slice = mb_substr($str, $start, $length, $charset);
	elseif(function_exists('iconv_substr')) {
		$slice = iconv_substr($str,$start,$length,$charset);
	}else{
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
	}
	return $slice!== $str ? $slice.'...' : $slice;
}

//图片列表取第一张
function cropimage($images, $size='') {
	if (strpos($images, ",") !== false) {
	    $images = substr($images, 0, strpos($images, ','));
	} 
	if (!empty($images) && !empty($size)) {
        $images = $images.'_'.$size.substr($images, strrpos($images, '.'));
	}
	return $images;
}
//返回like字符串数组
function get_like($cateid) {
	return array("like", array($cateid, $cateid.',%', '%,'.$cateid.',%', '%,'.$cateid), "OR");
}
// 获取文件夹大小
function getDirSize($dir) {
    $sizeResult = 0;
    $handle = opendir($dir);
    while (false !== ($FolderOrFile = readdir($handle)))
    {
        if($FolderOrFile != "." && $FolderOrFile != "..")
        {
            if(is_dir("$dir/$FolderOrFile"))
            {
                $sizeResult += getDirSize("$dir/$FolderOrFile");
            }
            else
            {
                $sizeResult += filesize("$dir/$FolderOrFile");
            }
        }   
    }
    closedir($handle);
    return $sizeResult;
}

// 单位自动转换函数
function getRealSize($size) {
    $kb = 1024;         // Kilobyte
    $mb = 1024 * $kb;   // Megabyte
    $gb = 1024 * $mb;   // Gigabyte
    $tb = 1024 * $gb;   // Terabyte
   
    if($size < $kb)
    {
        return $size." B";
    }
    else if($size < $mb)
    {
        return round($size/$kb,2)." KB";
    }
    else if($size < $gb)
    {
        return round($size/$mb,2)." MB";
    }
    else if($size < $tb)
    {
        return round($size/$gb,2)." GB";
    }
    else
    {
        return round($size/$tb,2)." TB";
    }
}