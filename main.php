<?php
	require_once('TwitterAPIExchange.php');
    include_once 'database.php';
    include_once 'tweet_database.php';

    $matchFound = (isset($_GET["q"]));
    $query_param = $matchFound ? trim($_GET["q"]) : 'india';

$settings = array(
'oauth_access_token' => "",
'oauth_access_token_secret' => "",
'consumer_key' => "",
'consumer_secret' => ""
);


$url = "https://api.twitter.com/1.1/search/tweets.json";
$requestMethod = "GET";
$getfield = "?q=' +$query_param+'&count=100";
$twitter = new TwitterAPIExchange($settings);
$string = json_decode($twitter->setGetfield($getfield)
->buildOauth($url, $requestMethod)
->performRequest(),$assoc = TRUE);
if(array_key_exists("errors", $string)) {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}
echo "<pre>";

// include database and object files

// get database connection
$database = new Database();
$db = $database->getConnection();
// prepare tweet object
$tweet = new Tweet($db);

// iterate each tweet and store in database table.
foreach ($string['statuses'] as $value) {

    // converting string to date
    $time = strtotime($value['created_at']);
    $newformat = date('M d, Y h:i:s',$time);

    // set tweet property values
    $tweet->tweet_id = $value['user']['screen_name'];
    $tweet->text = $value['text'];
    $tweet->time = $newformat;
    $tweet->profile_url = $value['user']['profile_image_url_https'];


    // create the tweet
    if($tweet->create()){

    }
}

echo "</pre>";
?>

<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <style>
#tweets {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#tweets td, #tweets th {
  border: 1px solid #ddd;
  padding: 8px;
}

#tweets tr:nth-child(even){background-color: #f2f2f2;}

#tweets tr:hover {background-color: #ddd;}

#tweets th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
        .image-cropper {
  width: 50px;
  height: 50px;
  position: relative;
  overflow: hidden;
  border-radius: 50%;
}

img {
  display: inline;
  margin: 0 auto;
  height: 100%;
  width: auto;
}
    </style>
</head>


<body>

    <h1>Tweets</h1>

    <table id="tweets">
        <tr>
            <th>Profile Image</th>
            <th>Id</th>
            <th>Text</th>
            <th>Tweet Time</th>
        </tr>
        
    </table>
</body>

</html>

<!-- page script -->
<script>


  $(document).ready(function(){
    $.ajax({
        type: "GET",
        url: "read_tweet.php",
        dataType: 'json',
        success: function(data) {
            var response="";
            for(var user in data){
                response += "<tr>" +
                "<td><div class='image-cropper'><img src="+data[user].url+" class='rounded'/></div></td>"+
                "<td>"+data[user].id+"</td>"+
                "<td>"+data[user].text+"</td>"+
                "<td>"+data[user].time+"</td>"+
                "</tr>";
            }
            $(response).appendTo($("#tweets"));
        }
    });
  });
</script>
