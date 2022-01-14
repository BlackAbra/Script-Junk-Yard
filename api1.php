<?php
error_reporting(E_ALL);
setlocale(LC_TIME, 'tr_TR');
 
//Orhan Eryiğit 20160601139	

function bol($veri,$basla,$bitir,$no,$no2)
	{
		$return = explode($basla,$veri);
		$return = @explode($bitir,$return[$no]);
		return $return[$no2];
	}
	
function getir($url,$ref=false){
	if(!$ref){
		$ref = $url;
	}
	$ch = curl_init();
	$timeout = 0;
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_REFERER,$ref);
	curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt'); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
	$veri= curl_exec($ch);
	curl_close($ch);
	return $veri;
}

function select_data($data){
	$data = explode("-", $data);
	$data["name"] = $data[0];
	$data["id"] = $data[1];
	
	return $data;
}


 
$inc_data = select_data($_GET["data"]);



 
$aytemiz_data = bol(file_get_contents('http://www.aytemiz.com.tr/faaliyet-alanlari-hizmetler/istasyonlar-hakkinda/akaryakit-ve-pompa-fiyatlari'), '<tbody>', '</tbody>>', 1, 0);
$aytemiz_data = explode('</table>', $aytemiz_data);
$aytemiz_data = trim(preg_replace('/\s+/', ' ', $aytemiz_data[0]));
preg_match_all('#<tr> <td style="padding: 14px 7px;"> <div style="width: 95px">(.*?)</div> </td> <td>(.*?)</td> <td>(.*?)</td> <td>(.*?)</td> <td>(.*?)</td> <td>(.*?)</td> </tr>#si',$aytemiz_data,$aytemiz_data);

$aytemiz_data["benzin"] = $aytemiz_data[2];
$aytemiz_data["motorin"] = $aytemiz_data[3];
$aytemiz_data["fueloil"] = $aytemiz_data[6];

 
$enerji_data = bol(getir('http://www.enerjipetrol.com/pompafiyatlari.asp'), '</thead>', '</table>', 1, 0);
$enerji_data = trim(preg_replace('/\s+/', ' ', $enerji_data));
preg_match_all('#<tr> <td>(.*?)</td> <td>(.*?)</td> <td>(.*?)</td> <td>(.*?)</td> <td>(.*?)</td> <td>(.*?)</td> <td>(.*?)</td> </tr>#si',$enerji_data,$enerji_data);

$enerji_data["benzin"] = $enerji_data[2];
$enerji_data["motorin"] = $enerji_data[3];
$enerji_data["fueloil"] = $enerji_data[6];


 
$sunpet_data = getir('http://sunpettr.com.tr/yakit-fiyatlari-'.$inc_data["name"]);
$sunpet_data = trim(preg_replace('/\s+/', ' ', $sunpet_data));
$sunpet_data = bol($sunpet_data, '<tr> <td> MERKEZ </td>', ' </tr>', 1, 0);
preg_match_all('#<td> (.*?) </td>#si',$sunpet_data,$sunpet_data);


$sunpet_data["benzin"] = $sunpet_data[1][1];
$sunpet_data["motorin"] = $sunpet_data[1][2];
$sunpet_data["fueloil"] = $sunpet_data[1][5];
?>