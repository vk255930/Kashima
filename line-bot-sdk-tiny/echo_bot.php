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
                    if($message['text'] == "功能"){
                        $msg = "提督先生, 您好!\n目前鹿島的能幫忙做的事情有\n[郵遞區號]查詢";
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
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'text',
                                    'text' => '提督先生 , '.$message['text']
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
