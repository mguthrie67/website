<?php

###############
#
# grab requests for urls (usually from emails) and log the access
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
    file_put_contents("logfile.txt", $line, FILE_APPEND);
}

####################
# get parameters   #
####################
$token=$_GET["token"];
$url=$_GET["url"];
$campaign=$_GET["code"];

#################################
# check fully formed parameters #
#################################
if (isset($token) and isset($url) and isset($campaign)) {

#####################
# check for rascals #
#####################
    if (isValid($url) and file_exists($filename)) {

#####################
# ok. get the image #
#####################
        $size = getimagesize($filename);
        $fp = fopen($filename, "rb");

##########################
# last check...          #
##########################
        if ($size && $fp) {

##################
# look for match #
##################
            $pattern = "/" . $token . "/";
            $nameArray = preg_grep($pattern, file('users.txt'));
            $line = array_values($nameArray)[0];
            $pieces=explode(":",$line);
            $name=$pieces[1];
            $name = str_replace(array("\n", "\r"), '', $name);
            if ($name=="") {
               $name="UNKNOWN";
            }

################
# log it       #
################
            date_default_timezone_set('Australia/Sydney');
            $date = date('d/m/Y H-i-s', time());
            $line = $date . ":" . $name  . ":" . $token . ":" . $filename . ":" . $_SERVER['REMOTE_ADDR']  . "\n";
            file_put_contents("logfile.txt", $line, FILE_APPEND);

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