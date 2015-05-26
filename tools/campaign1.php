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
    file_put_contents("/tmp/users.txt", $line, FILE_APPEND);

############################
# download                 #
############################
    header('Content-Type: application/download');
    header('Content-Disposition: attachment; filename="mailout.html"');
    header("Content-Length: " . filesize("/tmp/mailout.html"));

# load file
    $mailout=file_get_contents("/tmp/mailout.html");

# change tag
    $mailout=str_replace("CHANGEME", $code, $mailout);

# send it
    echo $mailout;

}
?>
<html>
<head>
<title>Campaign Manager</title>
</head>
<body>
<form method="post" action="<?php echo $PHP_SELF;?>">
Name:<input type="text" size="20" name="name"><br />
<input type="submit" value="Generate" name="submit">
</form>
</body>
</html>