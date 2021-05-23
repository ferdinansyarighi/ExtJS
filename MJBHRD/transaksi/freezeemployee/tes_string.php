<?php
echo strpos("I love php, I love php too!","IX");

$dt = '2019-04-19';
$ndt = date('N', strtotime($dt));
echo $ndt;
echo "<br>";
$ndt = date('Y-m', strtotime($dt));
echo $ndt;
echo "<br>";
?>