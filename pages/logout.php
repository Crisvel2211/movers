<?php
session_start();
session_destroy();
header("Location: https://logistic2.moverstaxi.com"); // Redirect to login page after logout
exit();
?>