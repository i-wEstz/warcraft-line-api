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
$teach = $database->selectCollection('teach');

$channelAccessToken = getenv('LINE_ACCESS_TOKEN');
$channelSecret = getenv('LINE_CHANNEL_SECRET');

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {

    $userId = $event['source']['groupId'];

    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                if(substr($message['text'],0,2) === 'AH'){
                    $output = Message($message['text'],$collection,$collection_item,$userId);
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

function Message($message_in,$collection,$collection_item,$userId,$teach){

    $sim_api = getenv('SIMSISMI');
    // $line_acc_token = getenv('LINE_ACCESS_TOKEN');
    // $line_ch_secret = getenv('LINE_CHANNEL_SECRET');

    // $collection = $database->selectCollection('AH');
    // $collection_item = $database->selectCollection('item_list');

    $str = trim(substr($message_in,2,strlen($message_in)));
    if($str != ''){
     if(strtoupper($str) === 'WOWTOKEN' || strtoupper($str) === 'WOW TOKEN'){

        $strn = file_get_contents('https://wowtoken.info/snapshot.json');
        $json = json_decode($strn, true); // decode the JSON into an associative array
        $json_na = $json['NA']['formatted'];
        $text_result = "< ".$str." >"."\n--------------\n"."ราคาขาย: ".$json_na['buy']."\nต่ำสุด(24hrs): ".$json_na['24min']."\nสูงสุด(24hrs): ".$json_na['24max']."\nอัพเดทล่าสุด: ".$json_na['updated']." (+12 in Bangkok)";

                    }
                    elseif(strtoupper($str) === 'TEACH'){

                            $text_result = $userId;
                        }
                        else{

    $search = strtoupper($str);                    
    $where = array('name' => array('$regex' => new MongoRegex("/^$search/")));               
    // $cursor = $collection_item->findOne(array('name' => strtoupper($str)));
    $cursor = $collection_item->findOne($where);
    if(!empty($cursor)){

        $ah = $collection->find(array('item' => $cursor['item'],'buyout' => array('$gt' => 0)))->sort(array('buyout' => 1))->limit(1);
    foreach($ah as $data){
        $length = strlen($data['buyout']);
        $bronze = substr($data['buyout'],-2);
        $silver = substr($data['buyout'],-4,2);
        $gold = substr($data['buyout'],0,($length-4));
        // $text_1 = "< ".$cursor['name']." >"."\n--------------\n"."1.) ราคาถูกที่สุด\nBuyout: ".$gold.'g'.$silver.'s'.$bronze."b\n".'จำนวน: '.$data['quantity']."\nตั้งโดย: ".$data['owner']."\n========\n";
        $text_1 = "< ".$cursor['name']." >"."\n--------------\n"."1.) ราคาถูกที่สุด\nBuyout: ".$gold.'.'.$silver."g\n".'จำนวน: '.$data['quantity']."\nตั้งโดย: ".$data['owner']."\n========\n";

    } 
        $ah = $collection->find(array('item' => $cursor['item'],'buyout' => array('$gt' => 0)))->sort(array('quantity' => -1,'buyout'=> 1))->limit(1);

        foreach($ah as $data){
        $length = strlen($data['buyout']);
        $bronze = substr($data['buyout'],-2);
        $silver = substr($data['buyout'],-4,2);
        $gold = substr($data['buyout'],0,($length-4));
        // Viable
        $len = strlen($data['viable']);
        $br = substr($data['viable'],-2);
        $si = substr($data['viable'],-4,2);
        $go = substr($data['viable'],0,($len-4));
        $text_2 = "2.) ถูกที่สุดในปริมาณมากที่สุด"."\nBuyout: ".$gold.'.'.$silver."g\n".'จำนวน: '.$data['quantity']."\nตั้งโดย: ".$data['owner']."\nราคาต่อชิ้น: ".$go.".".$si."g\n========\n";
    } 
    $ah = $collection->find(array('item' => $cursor['item'],'buyout' => array('$gt' => 0)))->sort(array('viable' => 1))->limit(1);

        foreach($ah as $data){
        $length = strlen($data['buyout']);
        $bronze = substr($data['buyout'],-2);
        $silver = substr($data['buyout'],-4,2);
        $gold = substr($data['buyout'],0,($length-4));
        // Viable
        $len = strlen($data['viable']);
        $br = substr($data['viable'],-2);
        $si = substr($data['viable'],-4,2);
        $go = substr($data['viable'],0,($len-4));
        $text_3 = "3.) คุ้มค่าที่สุด(ต่อชิ้น)"."\nBuyout: ".$gold.'.'.$silver."g\n".'จำนวน: '.$data['quantity']."\nตั้งโดย: ".$data['owner']."\nราคาต่อชิ้น: ".$go.".".$si."g\n========\n";
    } 
    $text_result = $text_1.$text_2.$text_3; 
    }
        else{
 
            
    //    $text_result = $str.' นี่มันอะไรไม่รู้จักเฟ้ย ไปพิมพ์มาใหม่ !';
    $simisimi = file_get_contents('http://api.simsimi.com/request.p?key='.$sim_api.'&lc=th&ft=1.0&text='.urlencode($str));
    $res = json_decode($simisimi, true); // decode the JSON into an associative array
    if($res['result'] == '100'){
    $text_result = $res['response'];
    } else{
        $random_message = array("จ้า","คือไยหยอ","พูดอะไรไม่เห็นจะเข้าใจเลย","จะให้โอกาสพูดอีกที","อีตาปลาบนตีนตะกวด","อีกิ้งกือตัดต่อพันธุกรรม","อีลบเข็บของไส้เดือน","ไม่มีปัญญาทำให้ผู้ชายมารัก","เธอๆ ทำยังไงให้อ้วนอ่ะ","ถ้า นีล อาร์มสตอรง เค้าเจอเธอก่อนเค้าคงไม่ต้องไปดวงจันทร์"
        ,"หมูป่าปากีสถาน","อิไม่มีดอก","หน้าหนังฮี๋ สังกะสีบาดแตด","นังมิติลี้ลับ!","ห่ากินหัว","ปอบถั่งมึง","สี่แม่ง","บ่ค่อยฮู้เรื่อง","เรื่องดีๆเธอคงไม่ถนัด แต่ถ้าเรื่องสัตว์สัตว์เธอถนัดดี๊ดี","เธอๆนี่โลกมนุษย์ ผุดลงไปใต้ดินได้แล้วค่ะ","สมองใหญ่เท่านมคงจะดี","หัดใช้ฟังก์ชั่นหลักของตูบ้างสิวะ");
        $rand_keys = array_rand($random_message);
        $text_result = $random_message[$rand_keys];

        $teach->insert(array("user" => $userId, "question" => $str , "answer" => $text_result));

    }
    // print_r($json['response']);
    }

     }
     }
     else{
         $text_result = "มีไรว่ามา";
    // $simisimi = file_get_contents('http://sandbox.api.simsimi.com/request.p?key=key&lc=th&text='.urlencode("สวัสดี"));
    // $res = json_decode($simisimi, true); // decode the JSON into an associative array
    // $text_result = $res['response'];
     }
    return $text_result;

}
