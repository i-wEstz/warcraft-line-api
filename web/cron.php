<?php
require('connection.php');
ini_set('max_execution_time', 300);
$collection = $database->selectCollection('AH');


$str = file_get_contents('https://us.api.battle.net/wow/auction/data/dreadmaul?locale=en_US&apikey=4vz7v2gtujvqtkprvy85f6mj9fwaanb8');
$json = json_decode($str, true); // decode the JSON into an associative array
$data_url = $json['files'][0]['url'];

$json_data = json_decode(file_get_contents($data_url),true);

$collection->remove(array(),array('safe' => true));
foreach ($json_data['auctions'] as $key=>$value) {


    $collection->insert($value);



}

?>