<?php
require('connection.php');
$collection_item = $database->selectCollection('item_list');
$cursor = $collection_item->findOne(array('name' => 'Bent Staff'));

print_r($cursor['item']);


?>