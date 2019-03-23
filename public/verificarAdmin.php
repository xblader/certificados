<?php
 session_start();
if ($_SESSION['perfil'] != 'ADMIN') {
 
    header("HTTP/1.1 401 Unauthorized");
    exit;
}
?>