<?php
// security stuff
require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
include('_header.html');
if ($login->isUserLoggedIn() !== true) {
    include("views/not_logged_in.php");
    include('_footer.html');
    die();
}

require_once("classes/crm.php");

date_default_timezone_set("Australia/Sydney");

// Get parameter. We are passed the ID to use.

$id= $_GET["id"];


// Connect to Database and get details of the campaign

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_CAMPAIGN);

$sql = "select campaign_ref, title, description,start,finish,location from campaign where campaign_id=" . $id;

if(!$results = $db->query($sql)){
    echo "<h1>Oops!! Error accessing database.</h1>";
    echo "Please return to the main menu and try again.";
    include('_footer.html');
    die();
}

if ($results->num_rows==0){

    echo "<h1>Oops! No matching event found</h1>";
    echo "Please return to the main menu and try again.";
    include('_footer.html');
    die();
}

$row=mysqli_fetch_row($results);

$campaign_ref=$row[0];
$title=$row[1];
#$description=str_replace("\n", "<br>", $row[2]);
$description=$row[2];
$start=$row[3];
$finish=$row[4];
$location=$row[5];

$nicestart=date("l jS \o\\f F Y \a\\t ga",strtotime($start));
$nicefinish=date("l jS \o\\f F Y \a\\t ga",strtotime($finish));

// Connect to database and get any saved information
$sql = "select subject, body from event_email where campaign_id=" . $id;

if(!$results = $db->query($sql)){
    echo "<h1>Oops!! Error accessing database.</h1>";
    echo "Please return to the main menu and try again.";
    include('_footer.html');
    die();
}

if ($results->num_rows==0){
// no match so set up defaults from campaign information

    echo "<h1>No email found for this campaign!</h1>";
    echo "Please return to the main menu and try again.";
    include('_footer.html');
    die();

} else {

    $row=mysqli_fetch_row($results);

    $subject=$row[0];
    $body=$row[1];

}

echo "<p>Use this URL to register users if you don't have their email addresses : <a href='http://17ways.com.au/register.php?event=$campaign_ref'>http://17ways.com.au/register.php?event=$campaign_ref</a><br><br>Or add attendees below to send them automated emails.<p>\n";
echo "<h3>Your Campaign</h3>\n";
echo "<table><tr><td>Title<td>$title</tr>\n";
echo "<tr><td>Start<td>$nicestart</tr>\n";
echo "<tr><td>Finish<td>$nicefinish</tr>\n";
echo "<tr><td>Location&nbsp;&nbsp;<td>$location</tr>\n";
echo "</table>\n";

echo "<br><br>\n";

echo "        <form action='campaign_step5.php' id='file-form' method='post'>\n";


echo "<table>\n";

echo "    <tr>\n";
echo "        <td>Search by:\n";

echo "<select id='type' style='font-size: 11px;'> <option value='Tag'>Tag";
echo "<option value='Project ID'>Project ID</select></tr>";

echo "        <td><input class='span7' type='text' id='search' name='search' value='' maxlength='80'/>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td>Project ID\n";
echo "        <td><input class='span7' type='text' id='tag' name='tag' value='' maxlength='80'/>\n";
echo "    </tr>\n";
echo "</table><br><br>\n";

echo "    <div class='clear'></div>\n";
echo "    <input type='submit' class='contact_btn' value='Load' onclick='getInsightly(); return false;'/>\n";
echo "    <input type='submit' class='contact_btn' value='Send' onclick='sendTestMail(); return false;'/>\n";
echo "    <div class='clear'></div>\n";

echo "<div id='StatusArea'></div>\n";

echo "</div>\n";


include('_footer.html');

?>