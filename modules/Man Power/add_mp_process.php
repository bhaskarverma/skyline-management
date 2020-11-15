<?php

include("../core/database/db_config.php");

$full_name = $_POST['mp_full_name'];
$brought_by = $_POST['brought_by'];
$aadhaar_no = $_POST['aadhaar_no'];
$license_no = $_POST['license_no'];
$role = $_POST['role'];

$pdo = new PDO($dsn, $user, $pass, $options);
$sql = $pdo->prepare("INSERT INTO `manpower`(`mp_name`, `date_of_joining`, `license_no`, `aadhaar_no`, `brought_by`, `on_vehicle`, `role`) VALUES (?,NOW(),?,?,?,false,?)");
$sql->execute([$full_name, $license_no, $aadhaar_no, $brought_by, $role]);

header("Location: /?module=Man%20Power&page=Add%20Man%20Power");

?>