<?php
$servername = "localhost";
$username = "dbuser_tweets";
$password = "tweets";
$dbname = "tweets";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql_checkfortweet = "SELECT * FROM tweets where tweet_sentiment IS NULL limit 30";
$dbresult = mysqli_query($conn, $sql_checkfortweet);
$num_dbresult = mysqli_num_rows($dbresult);

if (mysqli_num_rows($dbresult) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($dbresult)) 
    {
      $text = $row['tweet'];
      $id = $row['id'];
      echo $id . ": " . $text ."\n"; 
      $key = "932701ee8404e52ba67d3cf99478cbb08550263c";
      $url = "https://www.tweetsentimentapi.com/api/?key=$key&text=$text";
      $response = file_get_contents($url);
      $jsonoutput = json_decode($response);
      if (!empty($jsonoutput->sentiment)) {$tweet_sentiment = $jsonoutput->sentiment;} else {$tweet_sentiment = NULL;}
      if (!empty($jsonoutput->score)) {$tweet_sentimentscore = $jsonoutput->score;} else {$tweet_sentiment = NULL;}
      echo $tweet_sentiment . ": " . $tweet_sentimentscore ."\n\n";
      //$sql_insert = "INSERT INTO tweets (tweet_sentiment, tweet_sentimentscore) VALUES ('$tweet_sentiment', '$tweet_sentimentscore') WHERE ID = $id";
      //mysqli_query($conn, $sql_insert) or die(mysqli_error($conn));
    }
} else {
    echo "0 results";
}

// Close connection
mysqli_close($conn);
?>
