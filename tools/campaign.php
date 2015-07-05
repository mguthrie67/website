<?php
// security stuff
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

echo "<h3>Security is off</h3>";

$crm = new crm();

$dets = $crm->getContactsbyProject(2378067);

var_dump($dets);


die();

$dets = $crm->getContactsbyTag("Type-Provider");

foreach ($dets as $d) {
    echo "<h3>" . $d["name"] . "</h3>";
    echo "<p>ID: " . $d["id"] . "</p>";
    echo "<p>Email: " . $d["email"] . "</p>";
    echo "<p>Organisation: " . $d["organisation"] . "</p>";
}


// var_dump($contacts);

?>

<?php include('_footer.html');?>