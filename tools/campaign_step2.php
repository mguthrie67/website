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

date_default_timezone_set("Australia/Sydney");

# Get parameters from first screen and create campaign. First screen handles validation.

$title= $_POST["title"];
$description= $_POST["description"];
$start=$_POST["start"] . ":00";           # add seconds on
$finish=$_POST["finish"] . ":00";
$location=$_POST["location"];

# connect to DB
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_CAMPAIGN);

$title_esc=mysqli_real_escape_string($db, $title);
$description_esc=mysqli_real_escape_string($db, $description);

$campaign_ref=substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1,10))),1,10);

$sql = "INSERT INTO campaign (campaign_ref, title, description,start,finish,location)
VALUES ('$campaign_ref', '$title_esc','$description_esc','$start','$finish','$location')";

if (mysqli_query($db, $sql)) {
    echo " ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($db);
    die();
}

#$row=mysqli_fetch_row($results);
#$campaign_id=$row[0];

$nicestart=date("l jS \o\\f F Y \a\\t g:ia",strtotime($start));
$nicefinish=date("l jS \o\\f F Y \a\\t g:ia",strtotime($finish));


# use a relative path for the links but we need to print an absolute path
# i.e. we want to print http://17ways.com.au/register.php for prod but http://17ways.com.au/UAT/register.php for UAT
# So we strip of the /tools/campaign_step2.php part from the end.

$ME="/tools/" . basename(__FILE__);

$MYPATH=substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], $ME));

$URL="http://".$_SERVER['HTTP_HOST'].$MYPATH;

echo "<h2>Congratulations! Your Campaign has been created.</h2>\n";
echo "<p>Use this URL to register users : <a href='";
echo $URL;
echo "/register.php?event=$campaign_ref'>";
echo $URL;
echo "/register.php?event=$campaign_ref</a><br><br>Or add attendees below to send them automated emails.<p>\n";
echo "<h3>Details</h3>\n";
echo "<table><tr><td>Title<td>$title</tr>\n";
echo "<tr><td>Description<td>$description</tr>\n";
echo "<tr><td>Start<td>$nicestart</tr>\n";
echo "<tr><td>Finish<td>$nicefinish</tr>\n";
echo "<tr><td>Location<td>$location</tr>\n";
echo "</table>\n";

echo "<br><br>";
echo "<input type='button' id='atButton' value='Send Email to Attendees' onclick=\"document.getElementById('addattendees').style.display = 'block'; document.getElementById('atButton').style.display = 'none';\">";

# get new id
$sql="select max(campaign_id) from campaign";

if(!$results = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

$row=mysqli_fetch_row($results);
$id=$row[0];

echo "<div id='addattendees' style='display: none;'>";
echo "<h2>Compose Campaign Email</h2>";
echo "<table>";
echo "    <tr>";
echo "        <td width='40\%'>";
echo "            <input type='button' value='Easy' onclick=\"parent.location='campaign_step3_easy.php?id=" . $id . "'\">";
echo "        <td width='20\%'>";
echo "        <td width='40\%'>";
echo "            <input type='button' value='Advanced' onclick=\"parent.location='campaign_step3_advanced.php?id=" . $id . "'\">";
echo "    </tr>";
echo "    <tr>";
echo "                <td>The Easy option has all of the basic HTML already defined. You just need to add your text and any pictures";
echo "                    that you want to include.";
echo "<td>";
echo "        <td>The advanced option allows you to write your own HTML.";
echo "    </tr>";
echo "</table>";


echo "</div>";


include('_footer.html');
?>