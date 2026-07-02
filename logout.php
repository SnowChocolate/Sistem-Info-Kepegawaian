<?php
// logout.php
session_start();
session_unset();
session_destroy();

// Setelah logout, alihkan ke halaman login
header("Location: view_login.php?pesan=logout_sukses");
exit();
?>