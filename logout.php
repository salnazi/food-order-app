<?php
/**
 * FILENAME : logout.php
 * Logic : Clear sessions and redirect to login.
 */
session_start();
session_destroy();
header("Location: login.php");
exit();
?>