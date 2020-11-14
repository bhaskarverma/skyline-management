<?php

require("../core/database/db_config.php");

$name = $_POST['name'];
$cp = $_POST['cp'];
$cn = $_POST['cn'];
$city = $_POST['city'];

$pdo = new PDO($dsn, $user, $pass, $options);

$sql = $pdo->prepare("INSERT INTO `party_details` (`party_name`,`party_contact_person`,`party_contact_no`,`party_city`) VALUES (?,?,?,?)");
$sql->execute([$name,$cp,$cn,$city]);
$party_id = $pdo->lastInsertId();

$sql = $pdo->prepare("INSERT INTO `party_ledger` (`party_id`,`balance`) VALUES (?,0)");
$sql->execute([$party_id]);

echo json_encode(['result' => 'Successfully Inserted']);

?>