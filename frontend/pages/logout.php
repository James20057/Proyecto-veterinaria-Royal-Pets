<?php
require_once __DIR__ . '/../../backend/core/session.php';

session_destroy();
header('Location: ../../public/index.php');  
exit;
?>
