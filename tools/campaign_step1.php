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

?>



<h1>Create New Campaign - Step 1. Basic Information</h1>



    <div id="fields">
        <form action="campaign_step2.php" method="post">

<table>
    <tr>
        <td>Title
        <td><input class="span7" type="text" name="title" value=""  required/>
    </tr>
    <tr>
        <td>Description
        <td><textarea input class="span7" name="description" value=""  rows="6" cols="30" required/></textarea>
    </tr>
    <tr>
    <tr>
        <td>Start
        <td><input class="span7" type="text" name="start" value="" placeholder="Change me to a date and time  picker" required/>
    </tr>
    <tr>
        <td>Finish
        <td><input class="span7" type="text" name="finish" value="" placeholder="Change me to a date and time  picker" required/>
    </tr>
        <td>Location
        <td><input class="span7" type="text" name="location" value=""  required/>
    </tr>
</table>

<div id="map"><iframe width="100%" height="310" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                      src="https://www.google.com.au/maps/place/The+Royal+George,+320+George+St,+Sydney+NSW+2000"></iframe><br />
</div>


            <div class="clear"></div>
            <input type="submit" class="contact_btn" value="Submit" />
            <div class="clear"></div>
        </form>
    </div>

<?php include('_footer.html');?>