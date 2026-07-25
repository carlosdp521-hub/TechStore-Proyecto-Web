<?php

session_start();

unset($_SESSION["admin_id"]);
unset($_SESSION["admin_nombre"]);

header("Location: loginAdmin.php");
exit();

?>