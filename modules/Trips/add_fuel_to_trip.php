<?php

require("../core/database/db_config.php");

$ltr = $_POST['fuel_ltr'];
$money = $_POST['fuel_money'];
$tid = $_POST['trip_id'];

$pdo = new PDO($dsn, $user, $pass, $options);
$sql = $pdo->prepare("UPDATE `trips` SET `fuel_ltr`=`fuel_ltr`+?, `fuel_money`=`fuel_money`+? WHERE `trip_id` = ?");
$sql->execute([$ltr,$money,$tid]);

echo json_encode(['result' => 'Successfully Updated']);

?>