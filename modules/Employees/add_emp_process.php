<?php

include("../core/database/db_config.php");

$full_name = $_POST['emp_full_name'];
$brought_by = $_POST['brought_by'];
$aadhaar_no = $_POST['aadhaar_no'];
$license_no = $_POST['license_no'];
$role = $_POST['role'];

$pdo = new PDO($dsn, $user, $pass, $options);
$sql = $pdo->prepare("INSERT INTO `employees`(`emp_name`, `date_of_joining`, `license_no`, `aadhaar_no`, `brought_by`, `on_vehicle`, `role`) VALUES (?,NOW(),?,?,?,false,?)");
$sql->execute([$full_name, $license_no, $aadhaar_no, $brought_by, $role]);

header("Location: /?module=Employees&page=Add%20Employee");

?>