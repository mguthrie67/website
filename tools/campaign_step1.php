<?php
// security stuff
require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();

if ($login->isUserLoggedIn() !== true) {
    include('_header.html');
    include("views/not_logged_in.php");
    include('_footer.html');
    die();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Tools | 17 Ways</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">


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
                <div class="fleft"><a href="../index.html"><img id="TopLogo" src="../images/logo_slogan.png" alt="17 Ways" /></a></div>
                <div class="navbar fright">
                    <nav id="main_menu">
                        <div class="menu_wrap">
                            <ul class="nav sf-menu">

                                <li class="first"><a href="../index.html">Home</a></li>
                                <li class="sub-menu first"><a href="javascript:{}">Services</a>
                                    <ul>
                                        <li><a href="../hardware.html"><span>-</span>Hardware</a></li>
                                        <li><a href="../software.html"><span>-</span>Software</a></li>
                                        <li><a href="../advice.html"><span>-</span>Advice</a></li>
                                    </ul>
                                </li>

                                <li class="sub-menu first"><a href="javascript:{}">Partners</a>
                                    <ul>
                                        <li><a href="../Simplivity"><span>-</span>SimpliVity</a></li>
                                        <li><a href="../VCE"><span>-</span>VCE</a></li>
                                        <li><a href="../Avaya"><span>-</span>Avaya</a></li>
                                        <li><a href="../ScienceLogic"><span>-</span>ScienceLogic</a></li>
                                    </ul>
                                </li>
                                <li class="sub-menu first"><a href="Library/index.html">Library</a>
                                    <ul>
                                        <li><a href="../Library/CEO"><span>-</span>CEO Series</a></li>
                                        <li><a href="../Library/CTO"><span>-</span>CTO Series</a></li>
                                        <li><a href="../Library/TECH"><span>-</span>Technical Series</a></li>
                                        <li><a href="../search.html"><span>-</span>Search</a></li>
                                    </ul>
                                </li>

                                <li class="last"><a href="../about.html">About</a></li>
                                <li class="last"><a href="../contacts.html">Contact</a></li>
                            </ul>



                        </div>
                    </nav>
                </div>
                <div class="clear"></div>
            </div>

        </div>
    </div>
    <!--page_container-->

    <!--page_container-->
    <div class="page_container">
        <div class="wrap">
            <div class="breadcrumb">
                <div>
                    <a href="../index.html">Home</a><span>/</span>Tools

                    <span style="text-align: right;">
                        <a href="index.php?logout">[Logout]</a>
                    </span>
                </div>
            </div>
            <div class="container">



  <script type="text/javascript" src="js/custom.js"></script>
  <script type="text/javascript" src="js/tools.js"></script>

  <script type="text/javascript" src="js/bower_components/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript" src="js/bower_components/moment/min/moment.min.js"></script>
  <script type="text/javascript" src="js/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>


  <link rel="stylesheet" href="js/bower_components/bootstrap/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="js/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
<!-- Stylesheets -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/site.css" rel="stylesheet">


<h1>Create New Campaign:<br>Step 1. Basic Information</h1>



    <div id="fields">
        <form action="campaign_step2.php" method="post">

        <script type="text/javascript">
            $(function () {
                $('#datetimepickerstart').datetimepicker({
                  format: 'YYYY-MM-DD HH:mm'
                });
            });
            $(function () {
                $('#datetimepickerfinish').datetimepicker({
                format: 'YYYY-MM-DD HH:mm'
                });
            });
            $("#datetimepickerstart").on("dp.change", function (e) {
                $('#datetimepickerfinish').data("DateTimePicker").minDate(e.date);
            });
            $("#datetimepickerfinish").on("dp.change", function (e) {
                $('#datetimepickerstart').data("DateTimePicker").maxDate(e.date);
            });
        </script>




<table>
    <tr>
        <td>Title
        <td><input class="span7" type="text" name="title" value="" maxlength="80" required/>
    </tr>
    <tr>
        <td>Description&nbsp;
        <td><textarea input class="span7" name="description" value="" maxlength="400" rows="6" cols="30" required/></textarea>
    </tr>
    <tr>
    <tr>
        <td>Start
        <td>

                        <div class='input-group date' id='datetimepickerstart'>
                            <input type='text' class="form-control" name="start"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>



    </tr>
    <tr>
        <td>Finish
        <td>

                        <div class='input-group date' id='datetimepickerfinish'>
                            <input type='text' class="form-control" name="finish"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>


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



</body>

</html>