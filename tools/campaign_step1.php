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

?>



<h1>Create New Campaign - Step 1. Basic Information</h1>



    <div id="fields">
        <form action="campaign_step2.php" method="post">

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


<div id="map">

</div>


            <div class="clear"></div>
            <input type="submit" class="contact_btn" value="Submit" />
            <div class="clear"></div>
        </form>
    </div>

<?php include('_footer.html');?>
