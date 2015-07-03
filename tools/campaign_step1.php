<html>
<head>
<title>Campaign Manager</title>
    <!-- Stylesheets -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/site.css" rel="stylesheet">
    <link href="../css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Font -->
    <link href='http://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>


</head>
<body>

<h1>Create New Campaign</h1>



    <div id="fields">
        <form action="campaign_step2.php" method="post">

<?php

// Get next campaign number by looking in the file system

// items in the mailout directory
$files=array_diff(scandir("../mailout"), array('..','.','dev'));
$top=intval(max($files));
$top=$top+1;
$next=sprintf("%'.03d", $top);

echo '<input type="hidden" name="campaign" value="' . $next . '">';
?>

<table>
    <tr>
        <td>Title
        <td><input class="span7" type="text" name="title" value=""  required/>
    </tr>
    <tr>
        <td>Where
        <td><input class="span7" type="text" name="where" value=""  required/>
    </tr>
    <tr>
        <td>When
        <td><input class="span7" type="text" name="when" value="" placeholder="Change me to a date and time  picker" required/>
    </tr>
    <tr>
        <td>Description
        <td><textarea input class="span7" name="description" value=""  rows="6" cols="30" required/></textarea>
    </tr>
</table>

            <div class="clear"></div>
            <input type="submit" class="contact_btn" value="Submit" />
            <div class="clear"></div>
        </form>
    </div>


<script src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="../js/jquery.mobile.customized.min.js"></script>
<script type="text/javascript" src="../js/camera.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/superfish.js"></script>
        <script type="text/javascript" src="../js/jquery.prettyPhoto.js"></script>
<script src="../js/htweet.js" type="text/javascript"></script>
        <script type="text/javascript" src="../js/custom.js"></script>
</body>
</html>