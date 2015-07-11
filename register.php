<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<title>Registration | 17 Ways</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="17 Ways">

<!-- Stylesheets -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link href="css/site.css" rel="stylesheet">


<!-- Font -->
<link href='http://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>


<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->    
</head>
<body >
<!-- Top menu bar -->
<div class="container box_shadow">

    <!--header-->
    <div class="header">
        <div class="wrap">
            <div class="container">
                <div class="fleft"><a href="index.html"><img id="TopLogo" src="images/logo_slogan.png" alt="17 Ways" width="280" /></a></div>
                <div class="navbar fright">
                    <nav id="main_menu">
                        <div class="menu_wrap">
                            <ul class="nav sf-menu">

                                <li class="first"><a href="index.html">Home</a></li>
                                <li class="sub-menu first"><a href="javascript:{}">Services</a>
                                    <ul>
                                        <li><a href="hardware.html"><span>-</span>Hardware</a></li>
                                        <li><a href="software.html"><span>-</span>Software</a></li>
                                        <li><a href="advice.html"><span>-</span>Advice</a></li>
                                    </ul>
                                </li>

                                <li class="sub-menu first"><a href="javascript:{}">Partners</a>
                                    <ul>
                                        <li><a href="Simplivity"><span>-</span>SimpliVity</a></li>
                                        <li><a href="VCE"><span>-</span>VCE</a></li>
                                        <li><a href="Avaya"><span>-</span>Avaya</a></li>
                                        <li><a href="ScienceLogic"><span>-</span>ScienceLogic</a></li>
                                    </ul>
                                </li>
                                <li class="sub-menu first"><a href="Library/index.html">Library</a>
                                    <ul>
                                        <li><a href="Library/CEO"><span>-</span>CEO Series</a></li>
                                        <li><a href="Library/CTO"><span>-</span>CTO Series</a></li>
                                        <li><a href="Library/TECH"><span>-</span>Technical Series</a></li>
                                        <li><a href="search.html"><span>-</span>Search</a></li>
                                    </ul>
                                </li>

                                <li class="last"><a href="about.html">About</a></li>
                                <li class="last"><a href="contacts.html">Contact</a></li>
                            </ul>



                        </div>
                    </nav>
                </div>
                <div class="clear"></div>
            </div>

        </div>
    </div>

    <!--page_container-->
    <div class="page_container">
    	<div class="wrap">
        	<div class="breadcrumb">
				<div>
					<a href="index.html">Home</a><span>/</span>Event Registration
				</div>
			</div>
			<div class="container">


<?php

###########################################
# PHP Code
###########################################
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

# we are either linked to with a get register.php?event=23, or we are calling ourselves with a post (hidden parameters)
# for the first we show a form, for the second we just register

if (isset($_POST['event'])) {
    $event=$_POST['event'];
    $name=$_POST['name'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];


    if (NotSafe($event) || NotSafe($name) || NotSafe($email) || NotSafe($phone)) {
        echo "Invalid characters in input. Please remove any special characters and try again.";
        die();
    }

# update entry
    date_default_timezone_set("Australia/Sydney");
    $now=date("Y-m-d H:i:s");
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_CAMPAIGN);
    $sql = "INSERT INTO event_registration (campaign_id, name, email, phone, date_of_registration)
            VALUES($event, '$name', '$email', '$phone', '$now')";

    if (mysqli_query($db, $sql)) {

        $sql = "select title, description,start,finish,location
                 from campaign
                 where campaign_id=$event";

        $results = $db->query($sql);
        $row=mysqli_fetch_row($results);
        $title=$row[0];
        $description=str_replace("\n", "<br>", $row[1]);
        $start=$row[2];
        $finish=$row[3];
        $location=$row[4];

        $first=trim(explode(' ',$name)[0]);

        $nicestart=date("l jS \o\\f F Y \a\\t ga",strtotime($start));

    # to them
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= "From: 17 Ways Events <events@17ways.com.au>\r\n";
        $message = "<p style='font-family:Verdana;font-size:13px;'>";
        $message .= "Dear " . $first . ",<br><br>Confirming your attendance at the 17 Ways event on " . $nicestart . ". <br><br>Looking forward to seeing you there!<br><br>The 17 Ways Team";
 #       @mail($email, "17 Ways Event Confirmation: " . $title , $message,$headers);
        @mail("mark.guthrie@17ways.com.au", "17 Ways Event Confirmation: " . $title , $message,$headers);

    # to us
        $message = "<p style='font-family:Verdana;font-size:13px;'>";
        $message .= "New attendee: $name ($email : $phone)";
#        @mail('"' . MAIL_GROUP . '"', "17 Ways Event Confirmation: " . $title , $message,$headers);
        @mail("mark.guthrie@17ways.com.au", "17 Ways Event Confirmation: " . $title , $message,$headers);


        echo "Thank you for registering, " . $first . ".";
        echo "<br><br>A confirmation email has been sent to you.";
        echo "<br><br><a href='calendar.php?event=" . $event . "'>Click here to download a calendar invite as a reminder.<br><br><img src='images/tools/ics.png'></a>";

    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($db);
        echo "<br><br>Sorry. An error has occurred. Please call us on 1300 17WAYS or email us at <a href='mailto:events@17ways.com.au'>events@17ways.com.au</a> to confirm your registration.";
    }

} else {

###########################################################
# Get event details from database                         #
###########################################################

    if(isset($_GET['event'])) {
        $ref=$_GET['event'];
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_CAMPAIGN);

        $sql = "select campaign_id,title, description,start,finish,location
                 from campaign
                 where campaign_ref='$ref'";

        if(!$results = $db->query($sql)){
            echo "<h1>Oops!! No matching event found</h1>";
            echo "Please contact us to find out about upcoming events.";
        } else {

            if ($results->num_rows==0){
            echo "<h1>Oops! No matching event found</h1>";
            echo "Please contact us to find out about upcoming events.";
            } else {
                $row=mysqli_fetch_row($results);
                $id=$row[0];
                $title=$row[1];
                $description=str_replace("\n", "<br>", $row[2]);
                $start=$row[3];
                $finish=$row[4];
                $location=$row[5];

                $nicestart=date("l jS \o\\f F Y \a\\t ga",strtotime($start));
                $nicefinish=date("l jS \o\\f F Y \a\\t ga",strtotime($finish));

                echo "<h1>$title</h1>";
                echo "<p>Starts: " . $nicestart . "</p>";
                echo "<p>Ends: " . $nicefinish . "</p>";
                echo "<p>$description</p>";

                echo "<h3>Register Now!</h3>";
                echo '<div id="fields">';
                echo '<form method="post">';
                echo '<input type="hidden" name="event" value="' . $id . '">';
                echo '<input class="span7" type="text" name="name" value="" placeholder="Name (required)" required />';
                echo '<input class="span7" type="text" name="email" value="" placeholder="Email (required)" required/>';
                echo '<input class="span7" type="text" name="phone" value="" placeholder="Phone number (required)" required/>';
                echo '<div class="clear"></div>';
                echo '<input type="submit" class="contact_btn" value="Register" />';
                echo '<div class="clear"></div>';
                echo '</form>';
                echo '</div>';

                echo '<h2>Location</h2>';
                echo "<h3>$location</h3>";
                $location=str_replace(" ", "+", $location);

                echo '<div id="map"><iframe width="100%" height="310" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"';
                echo 'src="https://www.google.com/maps/embed/v1/search?key=AIzaSyBimDKYQtLr5Us6EbldvgtMqROoYrXAn9U&q=' . $location . '"></iframe><br />';
                echo '<small><a href="https://www.google.com.au/maps/place/' . $location . '">View Larger Map</a></small></div>';
                #echo '</div>';
            }
        }
    } else {
        echo "<h1>Oops! No event code provided</h1>";
        echo "Please contact us to find out about upcoming events.";
    }
}
?>

        </div>
    </div>
    <!--//page_container-->
</div>
        <!--//page_container-->﻿		<!--footer-->
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
    </div>

    <script src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="js/jquery.mobile.customized.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/superfish.js"></script>
    <script type="text/javascript" src="js/jquery.prettyPhoto.js"></script>
    <script src="js/htweet.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/custom.js"></script>

</body>

</html>

