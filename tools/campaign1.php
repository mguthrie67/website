<?php
$name = $_POST["name"];
if ($name<>"") {
#######################################################################
# name provided so generate entry in users.txt file and download file #
#######################################################################

# from the internet. random string
    $code=substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1,10))),1,10);

############################
# write to file.           #
############################
    $line = $code . ":" . $name . "\n";
    file_put_contents("../mailout/001/users.txt", $line, FILE_APPEND);

############################
# download                 #
############################
    header('Content-Type: application/download');
    $n = $name . ' mailout.html';
    header('Content-Disposition: attachment; filename=\"' . $n .'\"');
    header("Content-Length: " . filesize("../mailout/001/mailout.html"));

# load file
    $mailout=file_get_contents("../mailout/001/mailout.html");

# change tag
    $mailout=str_replace("CHANGEME", $code, $mailout);

# send it
    echo $mailout;

}

#onsubmit="setTimeout(function () { window.location.reload(); }, 1000)"

?>
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
<h1>Enter Names for Campaign 001</h1>
<br><br>
<form method="post"  action="<?php echo $PHP_SELF;?>">
<center>
Name:&nbsp;&nbsp;<input type="text" size="20" name="name"><br />
<input type="submit" value="Generate" name="submit">
</form>
</center>

<h2>Previously Entered Names</h2>

<table border=1>
<tr><th width=30%>Name<th width=20%>Id</tr>

<?php

$handle = fopen("../mailout/001/users.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
       echo "<tr><td>";
       $pieces = explode(":", $line);
       echo $pieces[1];
       echo "<td>";
       echo $pieces[0];
       echo "</tr>";

    }

    fclose($handle);
}
?>

</table>

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