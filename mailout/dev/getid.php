<?php
$name = $_GET["name"];
if ($name<>"") {
#######################################################################
# name provided so generate entry in users.txt file and return id     #
#######################################################################

    header('Content-Type: application/json');

# from the internet. random string
    $code=substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1,10))),1,10);

############################
# write to file.           #
############################
    $line = $code . ":" . $name . "\n";
    file_put_contents("users.txt", $line, FILE_APPEND);

############################
# return id                #
############################
    echo $code;

}
?>

