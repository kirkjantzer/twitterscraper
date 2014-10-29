<?php
 $mysqli = new mysqli('localhost','dbuser_tweets','tweets','tweets');
    $myArray = array();
    if ($result = $mysqli->query("SELECT * FROM tweets")) {
        $tempArray = array();
        while($row = $result->fetch_object()) {
                $tempArray = $row;
                array_push($myArray, $tempArray);
            }
        echo json_encode($myArray);
    }

    $result->close();
    $mysqli->close();
?>
