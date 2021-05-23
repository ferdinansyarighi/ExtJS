<!doctype html>
<html>
    <head>
        <title>barcode - harviacode.com</title>
        <style>
            @font-face {
                font-family: code39;
                src: url('IDAutomationHC39M Code 39 Barcode.ttf');
            }
            div{text-align: center}
        </style>
<?php
include '../../main/koneksi.php';
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}
$data     = "gagal";
$msgError = "";
$userid   = "";
$tglskr   = date('Y-m-d');
if (isset($_POST['asset'])) {
    $plat     = $_POST['plat'];
    $barcode  = $_POST['barcode'];
    $orid     = $_POST['orid'];
    $lambung  = $_POST['lambung'];
}
if($person_id == "" || $person_id == null){
    $person_id = 0;
}
?>
    </head>
    <body>
        <div><font face="code39" size="6em"><?php echo "$barcode" ?>/font></div>
    </body>
</html>