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
$start=$_POST["start"];
$finish=$_POST["finish"];
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

# get new id
#$sql="select max(campaign_id) from campaign";
#
#if(!$results = $db->query($sql)){
#    die('There was an error running the query [' . $db->error . ']');
#}

#$row=mysqli_fetch_row($results);
#$campaign_id=$row[0];

$nicestart=date("l jS \o\\f F Y \a\\t ga",strtotime($start));
$nicefinish=date("l jS \o\\f F Y \a\\t ga",strtotime($finish));


echo "<h2>Congratulations! Your Campaign has been created.</h2>\n";
echo "<p>Use this URL to register users : <a href='http://17ways.com.au/register.php?event=$campaign_ref'>http://17ways.com.au/register.php?event=$campaign_ref</a><br><br>Or add attendees below to send them automated emails.<p>\n";
echo "<h3>Details</h3>\n";
echo "<table><tr><td>Title<td>$title</tr>\n";
echo "<tr><td>Description<td>$description</tr>\n";
echo "<tr><td>Start<td>$nicestart</tr>\n";
echo "<tr><td>Finish<td>$nicefinish</tr>\n";
echo "<tr><td>Location<td>$location</tr>\n";
echo "</table>\n";

?>

<br><br>
<input type="button" id="atButton" value="Send Email to Attendees" onclick="document.getElementById('addattendees').style.display = 'block'; document.getElementById('atButton').style.display = 'none';">

<div id="addattendees" style="display: none;">
<br><br>

    <div id="fields">
        <form action="campaign_step3.php" method="post">

<table>
    <tr>
        <td>Title
        <td><input class="span7" type="text" name="title" value="" maxlength="80" required/>
    </tr>
    <tr>
        <td>Description
        <td><textarea input class="span7" name="description" value="" maxlength="400" rows="6" cols="30" required/></textarea>
    </tr>
    <tr>
    <tr>
        <td>Start
        <td><input class="span7" type="text" id ="datepicker" name="start" value="" placeholder="YYYY-MM-DD HH:MM:SS" required/>
    </tr>
    <tr>
        <td>Finish
        <td><input class="span7" type="text" id="datepicker2" name="finish" value="" placeholder="YYYY-MM-DD HH:MM:SS" required/>
    </tr>
        <td>Location
        <td><input id="location" class="span7" type="text" name="location" value="" maxlength="100"  onkeydown="updateMap()" required/>
    </tr>
</table>

</div>
</div>

<?php include('_footer.html');?>