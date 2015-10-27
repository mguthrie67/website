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
$sql = "select subject, body, sender from event_email where campaign_id=" . $id;

if(!$results = $db->query($sql)){
    echo "<h1>Oops!! Error accessing database.</h1>";
    echo "Please return to the main menu and try again.";
    include('_footer.html');
    die();
}

if ($results->num_rows==0){
// no match so set up defaults from campaign information

    $body = "Hi [name],\n\nPlease join us on " . $nicestart . " at " . $location . " for our latest event.\n\nTo register click on this link <a href='http://17ways.com.au/register.php?event=$campaign_ref'>http://17ways.com.au/register.php?event=$campaign_ref</a>"
     . "\n\n" . $description . "\n\n regards\n\n[me]";
    $subject = $title;

} else {

    $row=mysqli_fetch_row($results);

    $subject=$row[0];
    $body=$row[1];
    $sender=$row[2];

}

echo "<p>Use this URL to register users : <a href='http://17ways.com.au/register.php?event=$campaign_ref'>http://17ways.com.au/register.php?event=$campaign_ref</a><br><br>Or add attendees below to send them automated emails.<p>\n";
echo "<h3>Your Campaign</h3>\n";
echo "<table><tr><td>Title<td>$title</tr>\n";
echo "<tr><td>Start<td>$nicestart</tr>\n";
echo "<tr><td>Finish<td>$nicefinish</tr>\n";
echo "<tr><td>Location&nbsp;&nbsp;<td>$location</tr>\n";
echo "</table>\n";

echo "<br><br>\n";

echo "<h2>Compose Campaign Email</h2>\n";
echo "<p>You can use the following tags in your message and they will be substituted when the message is sent:<br>[name] = first name of";
echo " the person receiving the email.<br>[me] = your name.<br><br>Any HTML can also be used inside the text. If you want to include pictures";
echo " then upload them first and use the url that is provided. </p><p>";
echo "Tip: you can use the easy tool to build a basic message and then use the advanced tool to tweak it.</>";
echo "    <div id='fields'>\n";
echo "        <form action='campaign_step3.php' id='file-form' method='post'>\n";


echo "<table>\n";

echo "<tr><td>Sender<td>";

if ($sender=="You"){
    echo "<select id='sender' style='font-size: 11px;'> <option value='You'>You";
    echo "<option value='Events@17ways.com.au'>Events@17ways.com.au</select></tr>";
} else {
    echo "<select id='from' style='font-size: 11px;'>" ;
    echo "<option value='Events@17ways.com.au'>Events@17ways.com.au<option value='You'>You</select></tr>";
}

echo "    <tr>\n";
echo "        <td>Subject\n";
echo "        <td><input class='span7' type='text' id='subject' name='subject' value='" . $subject . "' maxlength='80' required/>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td style='vertical-align: top;'>Text\n";
echo "        <td><textarea input class='span7' id='body' name='body' maxlength='4000' rows='20' cols='30' required onChange='step3_off()' onkeyup='step3_off()'/>" . $body . "</textarea>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td>Upload Pictures\n";
echo "        <td>\n";

echo "    <input type='file' name='fileToUpload' id='fileToUpload'>\n";
echo "    <input type='submit' value='Upload Image' onclick='uploadFile(); return false;' name='submit'>\n";

echo "    </tr>\n";
echo "</table><br><br>\n";

echo "    <div class='clear'></div>\n";
echo "    <input type='submit' class='contact_btn' id='testWeb' value='Preview in Browser'  onclick='testWebMail(" . $id . "); return false;'/>\n";
echo "    <input type='submit' class='contact_btn' id='testMail' value='Preview in Email' onclick='sendTestMail(); return false;'/>\n";
echo "    <input type='submit' class='contact_btn' id='crap' value='Crap' onclick='var x=Document.getElementById(\"Save\").value; alert(x); return false;'/>\n";
echo "    <input type='submit' class='contact_btn' id='crap' value='Crap2' onclick='alert(\"ff\"); return false;'/>\n";
echo "    <input type='submit' class='contact_btn' id='Save' disabled value='Save' onclick='saveMail(" . $id . "); return false;'/>\n";
echo "    <input type='submit' class='contact_btn' id='Next' value='Next' onclick='sendTestMail(); return false;'/>\n";
echo "    <div class='clear'></div>\n";

echo "<div id='StatusArea'></div>\n";


echo "</div>\n";

echo "<script>\n";
echo "window.onbeforeunload = function (e) {\n";
echo "alert(Document.getElementById('Save').enabled); \n";
echo "if (Document.getElementById('Save').enabled == true) {\n";
echo "              return 'You have unsaved changes.';\n";
echo "      };\n";
echo "              return 'Baaa.';\n";
echo "};\n";
echo "</script>\n";

include('_footer.html');

?>