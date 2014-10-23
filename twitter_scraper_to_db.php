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
  $sql_checkfortweet = "SELECT tweetid FROM tweets where `tweetid` = `$result->id`";
  $dbresult = mysqli_query($conn, $sql_checkfortweet);
  if (empty($dbresult)) 
  {
    $tweet = preg_replace(array('/\r/', '/\n/'), '', $result->text);
    preg_match("/>([^<]*)</", $result->source, $source_output_array);
    if (!empty($result->geo->coordinates)) {$geo = $result->geo->coordinates[0] . "," . $result->geo->coordinates[1];} else {$geo = "NULL";}
    if (!empty($result->place->bounding_box)) {$geo_bounding_box = $result->place->bounding_box->coordinates[0][0][0] . "," . $result->place->bounding_box->coordinates[0][0][1] . "," . $result->place->bounding_box->coordinates[0][1][0] . "," . $result->place->bounding_box->coordinates[0][1][1] . "," . $result->place->bounding_box->coordinates[0][2][0] . "," . $result->place->bounding_box->coordinates[0][2][1] . "," . $result->place->bounding_box->coordinates[0][3][0] . "," . $result->place->bounding_box->coordinates[0][3][1];} else {$geo_bounding_box = "NULL";}
    
    $result_tweetid = mysqli_real_escape_string($conn, $result->id); 
    $result_user_id = mysqli_real_escape_string($conn, $result->user->id); 
    $result_user_screenname = mysqli_real_escape_string($conn, $result->user->screen_name); 
    $result_user_name = mysqli_real_escape_string($conn, $result->user->name); 
    $result_geo = mysqli_real_escape_string($conn, $geo); 
    if (!empty($result->place->name)) {$result_place_name = mysqli_real_escape_string($conn, $result->place->name);} else {$result_place_name = "NULL";}
    if (!empty($result->place->full_name)) {$result_place_full_name = mysqli_real_escape_string($conn, $result->place->full_name);} else {$result_place_full_name = "NULL";}
    if (!empty($result->place->country_code)) {$result_place_country_code = mysqli_real_escape_string($conn, $result->place->country_code);} else {$result_place_country_code = "NULL";}
    if (!empty($result->place->country)) {$result_place_country = mysqli_real_escape_string($conn, $result->place->country);} else {$result_place_country = "NULL";}
    $result_geo_bounding_box = mysqli_real_escape_string($conn, $geo_bounding_box); 
    $result_retweet_count = mysqli_real_escape_string($conn, $result->retweet_count); 
    $result_favorite_count = mysqli_real_escape_string($conn, $result->favorite_count); 
    $result_source_output_array = mysqli_real_escape_string($conn, $source_output_array[1]); 
    $result_user_location = mysqli_real_escape_string($conn, $result->user->location); 
    $result_user_description = mysqli_real_escape_string($conn, $result->user->description); 
    $result_tweet = mysqli_real_escape_string($conn, $tweet); 
    $result_created_at = mysqli_real_escape_string($conn, $result->created_at); 
    $result_user_followers_count = mysqli_real_escape_string($conn, $result->user->followers_count); 
    $result_user_friends_count = mysqli_real_escape_string($conn, $result->user->friends_count); 
    $result_user_statuses_count = mysqli_real_escape_string($conn, $result->user->statuses_count); 
    $result_user_favourites_count = mysqli_real_escape_string($conn, $result->user->favourites_count); 
    $result_user_listed_count = mysqli_real_escape_string($conn, $result->user->listed_count); 
    $result_user_time_zone = mysqli_real_escape_string($conn, $result->user->time_zone); 
    $result_user_lang = mysqli_real_escape_string($conn, $result->user->lang);
    $sql_insert = "INSERT INTO tweets (tweetid, userid, screenname, name, tweet_geo_coordinates, tweet_place_name, tweet_place_fullname, tweet_place_countrycode, tweet_place_country, tweet_place_boundingbox_coordinates, tweet_retweets, tweet_favorites, source, location, description, tweet, tweetdate, followers_count, friends_count, statuses_count, favourites_count, listed_count, time_zone, lang) VALUES ('$result_tweetid', '$result_user_id', '$result_user_screenname', '$result_user_name', '$result_geo', '$result_place_name', '$result_place_full_name', '$result_place_country_code', '$result_place_country', '$result_geo_bounding_box', '$result_retweet_count', '$result_favorite_count', '$result_source_output_array', '$result_user_location', '$result_user_description', '$result_tweet', '$result_created_at', '$result_user_followers_count', '$result_user_friends_count', '$result_user_statuses_count', '$result_user_favourites_count', '$result_user_listed_count', '$result_user_time_zone', '$result_user_lang')";
    mysqli_query($conn, $sql_insert) or die(mysqli_error($conn));
  }
}
// Close connection
mysqli_close($conn);
?>
