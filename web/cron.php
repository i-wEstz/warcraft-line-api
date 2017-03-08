<?php
require('connection.php');
ini_set('max_execution_time', 300);
$collection = $database->selectCollection('AH');
$collection_item = $database->selectCollection('item_list');

$list = array();
$str = file_get_contents('https://us.api.battle.net/wow/auction/data/dreadmaul?locale=en_US&apikey=4vz7v2gtujvqtkprvy85f6mj9fwaanb8');
$json = json_decode($str, true); // decode the JSON into an associative array
$data_url = $json['files'][0]['url'];

$json_data = json_decode(file_get_contents($data_url),true);
$ah_data = $json_data['auctions'];

$collection->remove(array(),array('w' => true));
foreach ($ah_data as $key=>$value) {


    $collection->insert($value);
    array_push($list,$value['item']);


}
print_r('Successfully Collect Data with '.count($ah_data).' Records');

$item_list = array_unique($list);
sort($item_list);

$collection_item->insert($item_list);
print_r('Successfully insert unique with '.count($item_list).' Records');



?>