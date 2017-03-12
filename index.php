<?php
/*
	ini_set('memory_limit', '256M');

	$url = "http://download.post.gov.tw/post/download/Xml_10510.xml";
   	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents

	$data = curl_exec($ch); // execute curl request
	curl_close($ch);

	$xml = simplexml_load_string($data);
	$json = json_encode($xml);
	$xmlArr = json_decode($json,TRUE);
	unset($xmlArr['@attributes']);
	foreach ($xmlArr as $arrVal) {
		foreach ($arrVal as $val) {
			if($val['欄位4'] == "高雄市橋頭區"){
				echo("<pre>");print_r($val);echo("</pre>");
			}
		}
	}
	$array = array(
		array(
		    "欄位1" => "82547",
		    "欄位4" => "高雄市橋頭區",
		    "欄位2" => "三仙路",
		    "欄位3" => "全",
		),
		array(
		    "欄位1" => "82541",
		    "欄位4" => "高雄市橋頭區",
		    "欄位2" => "鐵道北路",
		    "欄位3" => "全",
		),
		array(
		    "欄位1" => "82548",
		    "欄位4" => "高雄市橋頭區",
		    "欄位2" => "仕隆路博愛巷",
		    "欄位3" => "全",
		),
		array(
		    "欄位1" => "24266",
		    "欄位4" => "新北市新莊區",
		    "欄位2" => "西盛街",
		    "欄位3" => "單 407號以下",
		),
		array(
		    "欄位1" => "24264",
		    "欄位4" => "新北市新莊區",
		    "欄位2" => "西盛街",
		    "欄位3" => "單 409號以上",
		)
	);
	$keyWord = array(
		"zipcode" => "郵遞",
        "function"  => "功能",
        "usage"		=> "用法",
	);
	$str = "郵遞區號 用法";
	$key = " ".$str;
	$keyPosition = strpos($key, $keyWord["zipcode"]);
	if(strpos($key, $keyWord["usage"])){
        $fun = explode(" ", $str)['0'];
        $msg = "";
        switch ($fun) {
            case '郵遞區號':
                $msg.= "[郵遞區號]的查詢用法為：\n郵遞區號查詢 {地址}\nex：高雄市橋頭區鐵道北路";
                break;
            
            default:
                # code...
                break;
        }
		echo("<pre>");
		print_r(htmlspecialchars($msg));
		echo("</pre>");
	}
	foreach ($array as $value) {
		echo("<pre>");print_r($value);echo("</pre>");
	}
	$str1 = "高雄市橋頭區鐵道北路";
	$str2 = "新北市新莊區西盛街";
	$area = "";
	foreach ($array as $val) {
		$cityAddress = $val['欄位4'].$val['欄位2'];
		if($str2 == $cityAddress){
			$area.= $val['欄位3']."：".$val['欄位1']."<br/>";
		}
	}
	$msg = "您查詢的郵遞區號地址為：\n".$str1."\n"."該地段的郵遞區號為：\n".$area;
	echo("<pre>");
	print_r(htmlspecialchars($msg));
	echo("</pre>");
*/
	$keyWord = array(
		"zipcode" 	=> "郵遞區號查詢",
        "function"  => "功能",
        "usage"		=> "用法",
	);

	$str = "郵遞區號查詢 新北市新莊區西盛街";
	$key = " ".$str;

	if(strpos($key, $keyWord["zipcode"])){
	    $address = explode(" ", $str)['1'];
	    ini_set('memory_limit', '256M');

	    $url = "http://download.post.gov.tw/post/download/Xml_10510.xml";
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents

	    $data = curl_exec($ch); // execute curl request
	    curl_close($ch);

	    $xml = simplexml_load_string($data);
	    $json = json_encode($xml);
	    $xmlArr = json_decode($json,TRUE);
	    unset($xmlArr['@attributes']);
	    $zipcode = "";
	    foreach ($xmlArr as $arrVal) {
	        foreach ($arrVal as $val) {
	            $cityAddress = $val['欄位4'].$val['欄位2'];
	            if($address == $cityAddress){
	            	if($val['欄位3'] == "全"){
	            		$zipcode.= $val['欄位1']."\n";
	            	}else{
	                	$zipcode.= "\n".$val['欄位3']."：".$val['欄位1'];
	            	}
	            }
	        }
	    }
	    $msg = "您查詢的郵遞區號地址為：\n".$address."\n"."該地段的郵遞區號為：".$zipcode;
				echo("<pre>");
				print_r($msg);
				echo("</pre>");
	 }
?>