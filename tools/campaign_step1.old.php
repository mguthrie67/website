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
        <td><input id="location" class="span7" type="text" name="location" value="" maxlength="100"  onkeyup="updateMap()" required/>
    </tr>
</table>


<div id="map">

</div>


            <div class="clear"></div>
            <input type="submit" class="contact_btn" value="Submit" />
            <div class="clear"></div>
        </form>
    </div>

</div>
</div>
</div>
<!--//page_container-->

<!--//page_container-->?		<!--footer-->
<div id="footer">
    <div class="wrap">
        <div>
            <div class="row">
                <div class="container">
                    <h2 class="title">17 Ways</h2>
                    <p>We are an Australian technology company providing local and international services to
                        medium and large scale Australian companies.  </p>
                    <p>Our offices are located at 167 Phillip Street, Sydney 2000.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer_bottom">
    <div class="wrap">
        <div class="container">
            <div class="fleft copyright">17 Ways Pty Ltd &copy; 2015 | Registered in Australia ACN 603890179 | <a href="privacy.html" style="color: #bbb;">Privacy Policy</a> | <a href="terms.html" style="color: #bbb;">Terms of Use</a></div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</div>
<!--//footer-->




</body>

</html>
