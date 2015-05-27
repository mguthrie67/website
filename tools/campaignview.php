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

$excludeip="122.109.192.180";
$excludeip="122.109.192.181";   # fake


$campaign = $_GET["campaign"];

$purpose=file_get_contents("../mailout/" . $campaign . "/purpose.txt");

echo "<h1>Campaign " . $campaign . " - " . $purpose . "</h1>";

# Load Data

$users=file("../mailout/" . $campaign . "/users.txt");
$log=file("../mailout/" . $campaign . "/logfile.txt");
array_shift($log);

# Clean log data - remove our entries - excludeip

$newlog=array();

foreach ($log as &$l) {
    $pos = strpos($l, $excludeip);
    if ($pos === false) {
        array_push($newlog, $l);
    }
}

$log=$newlog;

# pre-process data

   # hits per user

      # initialise empty array with names

$hits=array();
$hits["UNKNOWN"]=0;

foreach ($users as &$u) {
   $pieces=explode(":", $u);
   $name=$pieces[1];
   $name=str_replace(array("\n","\r"), '', $name);

   $hits[$name]=0;
}

      # enter hits

foreach ($log as &$i) {
   $pieces=explode(":", $i);
   $name=$pieces[1];
   $hits[$name]=$hits[$name]+1;
}

   # count percentage hits

$hit=0;
$miss=0;

foreach ($hits as $k => $v) {
    if ($v==0) {
       $miss=$miss+1;
    } else {
       $hit=$hit+1;
    }

}

    # count multiple hits

$mhit=0;
$mmiss=0;

foreach ($hits as $k => $v) {
    if ($v>1) {
       $mmiss=$mmiss+1;
    } else {
       $mhit=$mhit+1;
    }

}



# Summary

echo "<table border=1>";

# all users

$totusers = count($users);

# get hits

$percentread = $hit * 100 / $totusers;
$mpercentread = $mhit * 100 / $totusers;

echo "<tr><td>Total Targets<td>" . $totusers . "</tr>";
echo "<tr><td>Percentage Read<td>" . $percentread . "%</tr>";
echo "<tr><td>Percentage Multiple Read<td>" . $mpercentread . "%</tr>";

echo "</table>";


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