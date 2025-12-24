<?php
session_start();
session_destroy();
header("Location: $URL/admin/login.php");
exit();
?>