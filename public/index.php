<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once "../libraries/autoload.php";

App::process();
?>
<h1>COUCOU !</h1>
<?php echo "bienvenue"; ?>
