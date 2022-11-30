<?php
// include database and object files
include_once 'tweet_database.php';
include_once 'database.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare tweet object
$tweet = new Tweet($db);

// query tweet
$stmt = $tweet->read();
$num = $stmt->rowCount();
// check if more than 0 record found
if($num>0){

    // tweets array
    $tweets_arr=array();
    $tweets_arr["tweets"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $tweet_item=array(
            "id" => $tweet_id,
            "text" => $text,
            "time" => $time,
            "url" => $profile_url
        );
        array_push($tweets_arr["tweets"], $tweet_item);
    }

    echo json_encode($tweets_arr["tweets"]);
}
else{
    echo json_encode(array());
}
?>