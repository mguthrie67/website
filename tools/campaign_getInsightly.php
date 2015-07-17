<?php
// security stuff

// Ajax module for CRM

require_once("config/db.php");
require_once("classes/Login.php");
require_once("classes/crm.php");
$login = new Login();
if ($login->isUserLoggedIn() !== true) {
    echo "Not logged in";
    die();
}

$crm = new crm();

$type = $_POST["type"];
$search = $_POST["search"];

echo $type;
echo $search;

if ($type == "Project ID") {
    $dets = $crm->getContactsbyProject($search);
} elseif ($type == "tag") {
    $dets = $crm->getContactsbyTag($search);
}

foreach ($dets as $d) {
    echo "<h3>" . $d["name"] . "</h3>";
    echo "<p>ID: " . $d["id"] . "</p>";
    echo "<p>Email: " . $d["email"] . "</p>";
    echo "<p>Organisation: " . $d["org"] . "</p>";
}

?>
