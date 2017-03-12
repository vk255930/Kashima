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
                        "zipcode"   => "郵遞",
                        "function"  => "功能",
                        "usage"     => "用法",
                    );
                    $key = " ".$message['text'];
                    if(strpos($key, $keyWord["function"])){
                        $msg = "提督先生, 您好!\n目前鹿島的能幫忙做的事情有\n[郵遞區號]查詢\n以上的功能喔!\n";
                        $usage = "若要查詢用法請輸入'功能名稱 用法'\nex：郵遞區號 用法";
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
                        $fun = explode(" ", $message['type'])['1'];
                        $msg = "";
                        switch ($fun) {
                            case '郵遞區號':
                                $msg.= "[郵遞區號]的查詢用法為：\n郵遞區號 查詢 {地址}\nex：高雄市橋頭區鐵道北路";
                                break;
                            
                            default:
                                # code...
                                break;
                        }

                    }else{
                        $msg = "提督先生, 您好!\n可以輸入[功能查詢]\n來查查看鹿島會什麼喔!";
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
