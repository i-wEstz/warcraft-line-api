<?php
try
{
    $connection = new MongoClient('mongodb://iwestz:lakious1@ds145389.mlab.com:45389/');
    $database   = $connection->selectDB('wow-api-ah');
    // $db_name = 'wow-api-ah';
    // $database =  $connection->wow-api-ah;
    // $collection = $database->selectCollection('AH');
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

// $cursor = $collection->find();

?>