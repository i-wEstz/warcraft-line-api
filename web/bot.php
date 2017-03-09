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

require_once('LineBotTiny.php');
require('connection.php');

$collection = $database->selectCollection('AH');
$collection_item = $database->selectCollection('item_list');

$channelAccessToken = 'INlnJAtPlk6fbtEdrKwJs2Qvb9g4sRN7CWhm9GNWVbjYalLUXPf4rrqu6yVb+Chs1kePBrggCJ5NQgRGRSx/cnSg6E7ZLnU0Rj/Uf8C2cCWqFSaJDQbnfjffjW2R2iohgepVVIbgnRYm113ZEGTJOQdB04t89/1O/w1cDnyilFU=';
$channelSecret = 'b600e84645566513de2c79423dcfa139';

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                if(substr($message['text'],0,2) === 'AH'){
                    $output = Message($message['text'],$collection,$collection_item);
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $output
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

function Message($message_in,$collection,$collection_item){

    // $collection = $database->selectCollection('AH');
    // $collection_item = $database->selectCollection('item_list');

    $str = trim(substr($message_in,2,strlen($message_in)));
    $cursor = $collection_item->findOne(array('name' => strtoupper($str)));
    if(!empty($cursor)){

        $ah = $collection->find(array('item' => $cursor['item']))->sort(array('buyout' => 1))->limit(1);
    foreach($ah as $data){
        $length = strlen($data['buyout']);
        $bronze = substr($data['buyout'],-2);
        $silver = substr($data['buyout'],($length-2),-4);
        $gold = substr($data['buyout'],($length-4),($length*-1));
        $text = $str."\n ราคาถูกที่สุด Buyout: ".$gold.' G '.$silver.' S '.$bronze.' B \n จำนวน: '.$data['quantity'];
    } 
    }
        else{
       $text = 'Item Not Found 0x100083';
    }

     
    
    return $text;

}