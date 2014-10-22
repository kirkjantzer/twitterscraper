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
$q = $_GET["q"];
 
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
if (!$conn) 
{
  die("Database connection failed: " . mysqli_connect_error());
}
if (!$mysqli->query("SET @a:='this will not work'")) 
{
  printf("Error: %s\n", $mysqli->error);
}

foreach ($results->statuses as $result) 
{
  if (!empty($dbresult = $mysqli->query("SELECT tweetid FROM tweets where tweetid=$result->id", MYSQLI_USE_RESULT))) 
  {
    $tweet = preg_replace(array('/\r/', '/\n/'), '', $result->text);
    preg_match("/>([^<]*)</", $result->source, $source_output_array);
    $geo = $result->geo->coordinates[0] . "," . $result->geo->coordinates[1];
    $geo_bounding_box = $result->place->bounding_box[0] . "," . $result->place->bounding_box[1] . "," . $result->place->bounding_box[2] . "," . $result->place->bounding_box[3];

    INSERT INTO table_name (tweetid, userid, screenname, name, tweet_geo_coordinates, tweet_place_name, tweet_place_fullname, tweet_place_countrycode, tweet_place_boundingbox_coordinates, tweet_retweets, tweet_favorites, source, location, description, tweet, tweetdate, followers_count, friends_count, statuses_count, favourites_count, listed_count, time_zone, lang) 
    VALUES($result->id, $result->user->id, $result->user->screen_name, $result->user->name, $geo, $result->place->name, $result->place->full_name, $result->place->country_code, $geo_bounding_box, $result->retweet_count, $result->favorite_count, $source_output_array[1], $result->user->location, $result->user->description, $tweet, $result->created_at, $result->user->followers_count, $result->user->friends_count, $result->user->statuses_count, $result->user->favourites_count, $result->user->listed_count, $result->user->time_zone, $result->user->lang)         
  }

  // Close result
  $result->close();
}

// Close connection
$mysqli->close();
?>
