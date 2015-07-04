<?php

require_once("tools/config/db.php");

date_default_timezone_set("Australia/Sydney");

function NotSafe($string)
{
    if(preg_match('/[^a-zA-Z0-9. +@_]/', $string) == 0)
    {
        return false;
    }
    else
    {
        return true;
    }
}

function dateToCal($timestamp) {
  return "TZID=Australia/Sydney:" . date('Ymd\THis', $timestamp);
}

// Escapes a string of characters
function escapeString($string) {
  return preg_replace('/([\,;])/','\\\$1', $string);
}


if (isset($_GET['event'])) {
    $event=$_GET['event'];

    if (NotSafe($event)) {
        echo "Invalid characters in input. Please remove any special characters and try again.";
        die();
    }


    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_CAMPAIGN);

    $sql = "select title, description,start,finish,location
             from campaign
             where campaign_id=$event";

    if(!$results = $db->query($sql)){
        echo "<h1>Oops! No matching event found</h1>";
        echo "Please contact us to find out about upcoming events.";
    } else {

        if ($results->num_rows==0){
            echo "<h1>Oops! No matching event found</h1>";
            echo "Please contact us to find out about upcoming events.";
        } else {
            $row=mysqli_fetch_row($results);
            $title=$row[0];
            $description="A 17 Ways Event. For more details visit http://17ways.com.au/register.php?event=" . $event . " Or call us on 1300 17WAYS";
            $start=strtotime($row[2]);
            $finish=strtotime($row[3]);
            $location=$row[4];

            $uri="http://17ways.com.au";

            header('Content-type: text/calendar; charset=utf-8');
            header('Content-Disposition: attachment; filename="17ways.ics');

            echo "BEGIN:VCALENDAR\r\n";
            echo "VERSION:2.0\r\n";
            echo "PRODID:17Ways\r\n";
            echo "CALSCALE:GREGORIAN\r\n";
            echo "TZID:AUS Eastern Standard Time\r\n";
            echo "BEGIN:VEVENT\r\n";
            echo "DTEND:" . dateToCal($finish) . "\r\n";
            echo "UID:" . uniqid() . "\r\n";
            echo "DTSTAMP:" . dateToCal(time()) . "\r\n";
            echo "LOCATION:" . escapeString($location). "\r\n";
            echo "DESCRIPTION:" . escapeString($description) . "\r\n";
            echo "URL;VALUE=URI:" . escapeString($uri) . "\r\n";
            echo "SUMMARY:" . escapeString($title). "\r\n";
            echo "DTSTART:" . dateToCal($start) . "\r\n";
            echo "END:VEVENT\r\n";
            echo "END:VCALENDAR\r\n";
       }
    }
}
?>
