<?php

###############
#
# grab requests for images (usually from emails) and log the access
#
###############

# function to sniff out baddies
function isValid($str) {
    return !preg_match('/[^A-Za-z0-9.#_-]/', $str);
}

# error function
function nogood($filename) {
    header("HTTP/1.0 404 Not Found");
    date_default_timezone_set('Australia/Sydney');
    $date = date('d/m/Y H-i-s', time());
    $line = $date . ":INVALID REQUEST:" . $filename . ":" . $_SERVER['REMOTE_ADDR']  . "\n";
    file_put_contents("/tmp/logfile.txt", $line, FILE_APPEND);
}

####################
# get parameters   #
####################
$token=$_GET["token"];
$filename=$_GET["file"];

#################################
# check fully formed parameters #
#################################
if (isset($token) and isset($filename)) {

#####################
# check for rascals #
#####################
    if (isValid($filename) and file_exists($filename)) {

#####################
# ok. get the image #
#####################
        $size = getimagesize($filename);
        $fp = fopen($filename, "rb");

##########################
# last check...          #
##########################
        if ($size && $fp) {

################
# log it       #
################
            date_default_timezone_set('Australia/Sydney');
            $date = date('d/m/Y H-i-s', time());
            $line = $date . ":" . $token . ":" . $filename . ":" . $_SERVER['REMOTE_ADDR']  . "\n";
            file_put_contents("/tmp/logfile.txt", $line, FILE_APPEND);

###########
# send it #
###########
            header("Content-type: {$size['mime']}");
            readfile($filename);
        } else {
            nogood($filename);
        }

#########################
# rascal alert          #
#########################
    } else {

        nogood($filename);
    }


} else {

    nogood($filename);

}


?>