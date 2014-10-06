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
$q = $_POST["q"];
 
$query = array(
  "q" => $q,
  "count" => "100",
  "result_type" => "recent",
);
$jsonarray = array();  
$results = search($query);

echo "<html><head><title>My Scraper</title></head><body><table border='1'>";
echo "<form action=". $_SERVER[PHP_SELF] ." method=post>";
echo "<input type='text' name='q'>";
echo "<input value='Search' type='submit'>";
echo "</form>";
echo "<tr>";
echo "<td>Created at</td>";
echo "<td>Screen name</td>";
echo "<td>Tweet</td>";
echo "<td>User location</td>";
echo "<td>Tweet ID</td>";
echo "<td>User name</td>";
echo "<td>User description</td>";
echo "<td>User followers</td>";
echo "<td>User friends</td>";
echo "<td>User timezone</td>";
echo "<td>User language</td>";
echo "<td>User statuses count</td>";
echo "<td>Tweet source</td>";
echo "<td>Tweet coordinates</td>";
echo "</tr>";
foreach ($results->statuses as $result) 
{
	$tweet = preg_replace(array('/\r/', '/\n/'), '', $result->text);
	if (!empty($result->user->location)) 
	{
		$tweet = preg_replace(array('/\r/', '/\n/'), '', $result->text);

		echo "<tr>";
                echo "<td>" . $result->created_at . "</td>";
                echo "<td>" . $result->user->screen_name . "</td>";
                echo "<td>" . $tweet . "</td>";
                echo "<td>" . $result->user->location . "</td>";
                echo "<td>" . $result->id . "</td>";
                echo "<td>" . $result->user->name . "</td>";
                echo "<td>" . $result->user->description . "</td>";
                echo "<td>" . $result->user->followers_count . "</td>";
                echo "<td>" . $result->user->friends_count . "</td>";
                echo "<td>" . $result->user->time_zone . "</td>";
                echo "<td>" . $result->user->lang . "</td>";
                echo "<td>" . $result->user->statuses_count . "</td>";
                preg_match("/>([^<]*)</", $result->source, $output_array);
                echo "<td>" . $output_array[1] . "</td>";
		echo "<td>";
                if (!empty($result->geo->coordinates)) {echo $result->geo->coordinates[0] . "," . $result->geo->coordinates[1];}
		echo "</td>";
		echo "</tr>";

                preg_match("/>([^<]*)</", $result->source, $source_output_array);
		$coordinates = "";
		if (!empty($result->geo->coordinates)) {$coordinates = $result->geo->coordinates[0] . "," . $result->geo->coordinates[1];}
		
		$jsonsubarray = array('created_at' => $result->created_at, 'screen_name' => $result->user->screen_name, 'tweet' => $tweet, 'user_location' => $result->user->location, 'tweet_id' => $result->id, 'user_name' => $result->user->name, 'user_description' => $result->user->description, 'user_followers' => $result->user->followers_count, 'user_friends' => $result->user->friends_count, 'user_timezone' => $result->user->time_zone, 'user_language' => $result->user->lang, 'user_statuses' => $result->user->statuses_count, 'tweet_source' => $tweetsource_output_array[1], 'tweet_coordinates' => $coordinates);
		array_push($jsonarray,$jsonsubarray); 
	}
}

echo "</table>";
echo json_encode($jsonarray);
echo "</body></html>";
?>
