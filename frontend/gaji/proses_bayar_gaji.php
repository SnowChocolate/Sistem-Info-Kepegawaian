<?php

require_once __DIR__ . '/../backend/config/koneksi.php';

$db = new Database();
$conn = $db->conn;


$id = intval($_GET['id']);


mysqli_query($conn,"
UPDATE gaji
SET status_bayar='dibayar'
WHERE id='$id'
");


header("location:index.php?page=gaji");

exit;

?>