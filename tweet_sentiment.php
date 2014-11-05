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

$sql_checkfortweet = "SELECT * FROM tweets where tweet_sentiment IS NULL";
$dbresult = mysqli_query($conn, $sql_checkfortweet);
$num_dbresult = mysqli_num_rows($dbresult);
if ($num_dbresult > 0) 
{
  $i = 0;
  while ($i < 31)
  {
    foreach ($dbresult as $row)
    {
      $text = $row["tweet"];
      $id = $row["id"];
      echo $id . ": " . $text; 
      $key = "932701ee8404e52ba67d3cf99478cbb08550263c";
      $url = "https://www.tweetsentimentapi.com/api/?key=$key&text=$text";
      $response = file_get_contents($url);
      var_dump($response);
      $tweet_sentiment = $response->sentiment;
      $tweet_sentimentscore = $response->score;
      // $sql_insert = "INSERT INTO tweets (tweet_sentiment, tweet_sentimentscore) VALUES ('$tweet_sentiment', '$tweet_sentimentscore') WHERE ID = $id";
      mysqli_query($conn, $sql_insert) or die(mysqli_error($conn));
    }
    $i++;
  }
}
// Close connection
mysqli_close($conn);
?>
