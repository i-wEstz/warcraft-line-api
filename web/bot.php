<?php
require('connection.php');
$collection_item = $database->selectCollection('item_list');
$exist = $collection_item->findOne(array('item' => 720));
if(!empty($exist)){
    echo 'Data Already Exist';
}
else{
    echo 'Data Not Already Exist';
}
// foreach ($exist as $doc) {
// print_r($doc['name'].$doc['item']);
// }
// print_r(iterator_to_array($exist));
?>