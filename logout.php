<?php
session_start();
session_destroy();
header("Location: loginstaff.php");
exit();
?> 