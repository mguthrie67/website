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

<?php

require("insightly.php");

$i = new Insightly('1b59c7a6-98cc-4788-b4ae-d063453e04ab');

$contacts = $i->getContacts(array("filters" => array('FIRST_NAME=\'Brian\'')));

print_r($contacts);

// var_dump($contacts);

?>

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