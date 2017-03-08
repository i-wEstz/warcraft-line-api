<?php
$str = file_get_contents('https://us.api.battle.net/wow/auction/data/dreadmaul?locale=en_US&apikey=4vz7v2gtujvqtkprvy85f6mj9fwaanb8');
$json = json_decode($str, true); // decode the JSON into an associative array
console.log($json);
?>
