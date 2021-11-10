<?php
if (session_status() <> PHP_SESSION_ACTIVE) session_start();
function accessCheck($requiredRole) {
    if ( isset($_SESSION['role']) ) {
        $role = (int)$_SESSION['role'];
        if ( $role < $requiredRole ) {
            header("Location: ../index.php?err=unauth");
        }
    }
    else {
        header("Location: ../index.php?err=notLoggedIn");
    }
}
?>