<?php
require('connection.php');
$collection_item = $database->selectCollection('item_list');
$exist = $collection_item->find(array('item' => 720))->limit(1);
foreach ($exist as $doc) {
print_r($doc['name'].$doc['item']);
}
// print_r(iterator_to_array($exist));
?>