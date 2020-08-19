<?php
try
{
    require '../vendor/autoload.php'; // include Composer's autoloader
//    $connection = new Mongo('mongodb://iwestz:lakious1@ds145389.mlab.com:45389/wow-api-ah');
//    $connection = new MongoClient('mongodb://iwestz:lakious1@ds145389.mlab.com:45389/wow-api-ah');
//    $connection = new Mongodb('mongodb://iwestz:lakious1@ds145389.mlab.com:45389/wow-api-ah');
    $connection = new MongoDB\Client('mongodb://iwestz:lakious1@ds145389.mlab.com:45389/wow-api-ah');
//    $connection = new MongoDB('mongodb://iwestz:lakious1@ds145389.mlab.com:45389/wow-api-ah');

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
