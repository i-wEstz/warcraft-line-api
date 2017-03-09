<?php
require('connection.php');
$collection_item = $database->selectCollection('item_list');
$exist = $collection_item->find(array('item' => 720),array('limit' => 1));
print_r(iterator_to_array($exist));
?>