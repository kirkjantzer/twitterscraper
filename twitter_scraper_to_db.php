<?php
require_once('twitteroauth.php');
 
define('CONSUMER_KEY', 'NrVCX0rkiQmcMs1CLo96aVEXG');
define('CONSUMER_SECRET', 'JY0PSdBfSYUeKRjF4RchDgU3GfIMTLwK2f9YHU6rc5WgCf4eIc');
define('ACCESS_TOKEN', '69743234-uq5IzUYVl5ClIQkIltmsN0lIW4hbwjCyMJsirN8x5');
define('ACCESS_TOKEN_SECRET', 'QEvEdMdEt0p1bpP7kWJ3HQprXqfSMJaTTq4QmvEVZYb8R');
 
function search(array $query)
{
  $toa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
  return $toa->get('search/tweets', $query);
}
$q = $argv[1];
 
$query = array(
  "q" => $q,
  "count" => "100",
  "result_type" => "recent",
);
  
$results = search($query);

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

foreach ($results->statuses as $result) 
{ 
  $sql = "SELECT tweetid FROM tweets where `tweetid` = `$result->id`";
  $dbresult = mysqli_query($conn, $sql);
  if (empty($dbresult)) 
  {
    $tweet = preg_replace(array('/\r/', '/\n/'), '', $result->text);
    preg_match("/>([^<]*)</", $result->source, $source_output_array);
    if (!empty($result->geo->coordinates)) {$geo = $result->geo->coordinates[0] . "," . $result->geo->coordinates[1];} else {$geo = "";}
    if (!empty($result->place->bounding_box)) {$geo_bounding_box = $result->place->bounding_box->coordinates[0][0][0] . "," . $result->place->bounding_box->coordinates[0][0][1] . "," . $result->place->bounding_box->coordinates[0][1][0] . "," . $result->place->bounding_box->coordinates[0][1][1] . "," . $result->place->bounding_box->coordinates[0][2][0] . "," . $result->place->bounding_box->coordinates[0][2][1] . "," . $result->place->bounding_box->coordinates[0][3][0] . "," . $result->place->bounding_box->coordinates[0][3][1];} else {$geo_bounding_box = "";}
    echo $result->id . ": " . $geo . "\n";
    //$sql = "INSERT INTO tweets (tweetid, userid, screenname, name, tweet_geo_coordinates, tweet_place_name, tweet_place_fullname, tweet_place_countrycode, tweet_place_boundingbox_coordinates, tweet_retweets, tweet_favorites, source, location, description, tweet, tweetdate, followers_count, friends_count, statuses_count, favourites_count, listed_count, time_zone, lang) VALUES ('$result->id', '$result->user->id', '$result->user->screen_name', '$result->user->name', '', '$result->place->name', '$result->place->full_name', '$result->place->country_code', '', '$result->retweet_count', '$result->favorite_count', '', '$result->user->location', '$result->user->description', '', '$result->created_at', '$result->user->followers_count', '$result->user->friends_count', '$result->user->statuses_count', '$result->user->favourites_count', '$result->user->listed_count', '$result->user->time_zone', '$result->user->lang')";
    $sql = "INSERT INTO tweets (tweetid, userid, screenname, name, tweet_geo_coordinates, tweet_place_name, tweet_place_fullname, tweet_place_countrycode, tweet_place_boundingbox_coordinates, tweet_retweets, tweet_favorites, source, location, description, tweet, tweetdate, followers_count, friends_count, statuses_count, favourites_count, listed_count, time_zone, lang) VALUES (serialize($result->id), serialize($result->user->id), serialize($result->user->screen_name), serialize($result->user->name), $geo, serialize($result->place->name), serialize($result->place->full_name), serialize($result->place->country_code), '$geo_bounding_box', serialize($result->retweet_count), serialize($result->favorite_count), serialize($source_output_array[1]), serialize($result->user->location), serialize($result->user->description), $tweet, serialize($result->created_at), serialize($result->user->followers_count), serialize($result->user->friends_count), serialize($result->user->statuses_count), serialize($result->user->favourites_count), serialize($result->user->listed_count), serialize($result->user->time_zone), serialize($result->user->lang))";
    echo $sql;
    //mysqli_query($conn, $sql);
  }
}
// var_dump($results);
// Close connection
mysqli_close($conn);
?>
