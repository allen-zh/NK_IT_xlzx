<?php

//检测字符串s是否都是数字
function check_is_num($s)
{
	if(preg_match("/^\d*$/",$s)){return true;}
	return false;
}

//message内容的加密
function encrypt_msg($s)
{
	return "ls".base64_encode($s)."paradise";
}

//message内容的解密
function decrypt_msg($s)
{
	$s=substr($s,2,-8);
	return base64_decode($s);
}

?>
