<?php

###############
#
# grab requests for urls (usually from emails) and log the access, then redirect
#
###############

####################
# get parameters   #
####################
$token=$_GET["token"];
$url=$_GET["url"];
$campaign=$_GET["code"];

#################################
# redirect function             #
#################################
function offyougo($url) {
    header("Location: " . $url);
    die();
}

#################################
# check fully formed parameters #
#################################
if (isset($token) and isset($url) and isset($campaign)) {

##################
# look for match #
##################

#########################
# Check campaign exists #
#########################
    if (file_exists("mailout/" . $campaign)) {      # file_exists also checks for directory

#########################
# ok, write to log      #
#########################
        $pattern = "/" . $token . "/";
        $nameArray = preg_grep($pattern, file('mailout/' . $campaign . '/users.txt'));
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
        $line = $date . ":" . $name  . ":" . $token . ":" . $url . ":" . $_SERVER['REMOTE_ADDR']  . "\n";
        file_put_contents('mailout/' . $campaign . "/redir-logfile.txt", $line, FILE_APPEND);
    }
}

############################################################################################
# regardless of what happened redirect them to the url and let the website take care of it #
############################################################################################
offyougo($url);
?>
