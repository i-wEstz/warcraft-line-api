<?php
require('connection.php');
$collection_item = $database->selectCollection('item_list');
$collection = $database->selectCollection('AH');
$cursor = $collection_item->findOne(array('name' => 'Bent Staff'));
$ah = $collection->find(array('item' => $cursor['item']))->sort(array('buyout' => 1))->limit(1);

print_r($ah);


?>