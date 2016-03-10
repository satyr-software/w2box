<?php
function parseHttpAcceptLanguage($str=NULL) { 
	// getting http instruction if not provided 
	$str=$str?$str:$_SERVER['HTTP_ACCEPT_LANGUAGE']; 
	// exploding accepted languages 
	$langs=explode(',',$str); 
	// creating output list 
	$accepted=array(); 
	foreach ($langs as $lang) { 
	// parsing language preference instructions 
	// 2_digit_code[-longer_code][;q=coefficient] 
	preg_match('/([a-z]{1,2})(-([a-z0-9]+))?(;q=([0-9\.]+))?/',$lang,$found);
	// 2 digit lang code
	$code=$found[1];
	// lang code complement
	if (isset($found[3])) { $morecode=$found[3];  }
	// full lang code
	$fullcode=(isset($morecode)?$code.'-'.$morecode:$code);
	// coefficient
	$coef=sprintf('%3.1f',(isset($found[5])?$found[5]:'1'));
	// for sorting by coefficient
	$key=$coef.'-'.$code;
	// adding
	$accepted[$key]=array('code'=>$code,'coef'=>$coef,'morecode'=>(isset($morecode)?$morecode:""),'fullcode'=>$fullcode); }
	// sorting the list by coefficient desc 
	krsort($accepted); 
	return $accepted; 
}

$lang = Array();
require("en.php");

foreach (parseHttpAcceptLanguage() as $l) {
	$langfile = "lang/".$l['code'].".php";
	if ($l['code'] == "en") {
		break;
	}
	if (file_exists($langfile) && $l['code'] != "en") {
		include ($langfile);
		break;
	}
}
?>
