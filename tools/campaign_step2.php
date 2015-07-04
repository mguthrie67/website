<?php
include('_header.html');
// security stuff
require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
if ($login->isUserLoggedIn() !== true) {
    include("views/not_logged_in.php");
    include('_footer.html');
    die();
}

# Get parameters from first screen and create campaign. First screen handles validation.

$title=$_POST["title"];
$description=$_POST["description"];
$start=$_POST["start"];
$finish=$_POST["finish"];
$location=$_POST["location"];

# connect to DB
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_CAMPAIGN);

$sql = "INSERT INTO campaign (title, description,start,finish,location)
VALUES ('$title','$description','$start','$finish','$location')";

if (mysqli_query($db, $sql)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($db);
    die();
}

# get new id
$sql="select max(campaign_id) from campaign";

if(!$results = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

$row=mysqli_fetch_row($results);
$campaign_id=$row[0];

echo "<h2>Congratulations! Your Campaign has been created.</h2>\n";
echo "<p>Use this URL to register: <a href='http://17ways.com.au/register.php?event=$campaign_id'>http://17ways.com.au/register?event=$campaign_id</a><p>\n";
echo "<h3>Details</h3>\n";
echo "<table><tr><td>Title<td>$title</tr>\n";
echo "<tr><td>Description<td>$description</tr>\n";
echo "<tr><td>Start<td>$start</tr>\n";
echo "<tr><td>Finish<td>$finish</tr>\n";
echo "<tr><td>Location<td>$location</tr>\n";
echo "</table>\n";

?>

<br><br>
<input type="button" value="Add Attendees">



<?php include('_footer.html');?>