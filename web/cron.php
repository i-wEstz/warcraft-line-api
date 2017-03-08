<?php
require('connection.php');
$date_db = $database->selectCollection('flag');
ini_set('max_execution_time', 300);

$str = file_get_contents('https://us.api.battle.net/wow/auction/data/dreadmaul?locale=en_US&apikey=4vz7v2gtujvqtkprvy85f6mj9fwaanb8');
$json = json_decode($str, true); // decode the JSON into an associative array
$data_url = $json['files'][0]['url'];
$lastModi = $json['files'][0]['lastModified'];

$json_data = json_decode(file_get_contents($data_url),true);
$lastModi = json_encode($lastModi);
$auction_data = $json_data['auctions'];



// print "<pre>";
// print_r($details);
// print "</pre>";
$collection->remove(array(),array('safe' => true));
foreach ($json_data['auctions'] as $key=>$value) {
    // print_r($value['item']);
    // $item_no = $value['item']; 
    // $item_data = json_decode(file_get_contents('https://us.api.battle.net/wow/item/'.$item_no.'?locale=en_US&apikey=4vz7v2gtujvqtkprvy85f6mj9fwaanb8'),true);
    // $item_name = $item_data['name'];
    $collection->insert($value);
}
// print "<pre>";
// print_r($json_data['auctions']);
// print "</pre>";

$item_list = unique_multidim_array($auction_data,'item');

function unique_multidim_array($array, $key) { 
    $temp_array = array(); 
    $i = 0; 
    $key_array = array(); 
    
    foreach($array as $val) { 
        if (!in_array($val[$key], $key_array)) { 
            $key_array[$i] = $val[$key]; 
            $temp_array[$i] = $val; 
        } 
        $i++; 
    } 
    return $temp_array; 
} 
?>