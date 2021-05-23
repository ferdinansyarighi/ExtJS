<?php
include 'phpqrcode/qrlib.php';
session_start();

QRcode::png("asdfsdfsdf123132123", 'barcodelist/aaa.png', "L", 10, 10);
echo "<img src = 'barcodelist/aaa.png'/>";
?>