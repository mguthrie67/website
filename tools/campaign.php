<?php
// security stuff


// Test module for crm class

require_once("config/db.php");
require_once("classes/Login.php");
require_once("classes/crm.php");
#$login = new Login();
include('_header.html');
#if ($login->isUserLoggedIn() !== true) {
#    include("views/not_logged_in.php");
#    include('_footer.html');
#    die();
#}

#echo "<h3>Security is off</h3>";

$crm = new crm();

####
# Get a single contact
#
# Works ok
###
#$dets = $crm->getContactbyId(124650965);
#var_dump($dets);
#die();

####
# Get a list of contacts by project
#
# Not working yet - project call returns id of company not name. Need a fast way to get this.
####
#$dets = $crm->getContactsbyProject(2378067);
#var_dump($dets);

#####
#
# Get contact by tag
#
# Works ok
#####
$dets = $crm->getContactsbyTag("Type-Provider");

foreach ($dets as $d) {
    echo "<h3>" . $d["name"] . "</h3>";
    echo "<p>ID: " . $d["id"] . "</p>";
    echo "<p>Email: " . $d["email"] . "</p>";
    echo "<p>Organisation: " . $d["organisation"] . "</p>";
}


?>

<?php include('_footer.html');?>