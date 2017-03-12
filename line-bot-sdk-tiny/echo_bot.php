<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once('./LINEBotTiny.php');

$channelAccessToken = 'ZrZRpC88fqppGShQTEPHcuukfsk54FX+HlOpWgzuO7q2RoZANqTMoIowoSVtK+Lax/fD5qGqY2O1L4b7Uocx9+OH35+3jKDbRikPysj+brtOgJkZHQH312DiIbUYddgCwD/5FIgebAR6rCdtublxngdB04t89/1O/w1cDnyilFU=';
$channelSecret = '2dac00819f286422938ce905f3ceafbe';

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    $keyWord = array(
                        "zipcode"   => "郵遞區號查詢",
                        "function"  => "功能",
                        "usage"     => "用法",
                    );
                    $key = " ".$message['text'];
                    if(strpos($key, $keyWord["function"])){
                        $msg = "提督先生, 您好!\n目前鹿島的能幫忙做的事情有\n[郵遞區號]查詢\n以上的功能喔!\n\n";
                        $usage = "若要查詢用法請輸入\n[功能名稱 用法]\nex：郵遞區號 用法";
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => $msg.$usage
                                )
                            )
                        ));
                    }elseif(strpos($key, $keyWord["usage"])){
                        $fun = explode(" ", $message['text'])['0'];
                        $msg = "";
                        switch ($fun) {
                            case '郵遞區號':
                                $msg.= "[郵遞區號]的查詢用法為：\n郵遞區號查詢 縣市+鄉鎮[市]區+路(街)名或鄉里名稱\nex：郵遞區號查詢 高雄市橋頭區鐵道北路";
                                break;
                            
                            default:
                                $msg.= "提督先生抱歉!\n目前鹿島尚未學會此功能!";
                                break;
                        }
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => $msg
                                )
                            )
                        ));
                    }elseif(strpos($key, $keyWord["zipcode"]){
                        $address = explode(" ", $message['text'])['1'];
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
                                        $zipcode.= "\n".$val['欄位3']."：".$val['欄位1']."\n";
                                    }
                                }
                            }
                        }
                        $msg = "您查詢的郵遞區號地址為：\n".$address."\n"."該地段的郵遞區號為：".$zipcode;
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => $msg
                                )
                            )
                        ));
                    }else{
                        $msg = "提督先生, 您好\n可以輸入[功能查詢]\n來查查看鹿島會什麼喔!";
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => $msg
                                )
                            )
                        ));
                    }
                    break;
                default:
                    error_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};
