<?php

require("../core/database/db_config.php");

$ltr = $_POST['status'];
$tid = $_POST['trip_id'];

$pdo = new PDO($dsn, $user, $pass, $options);
$sql = $pdo->prepare("UPDATE `trips` SET `current_status`=? WHERE `trip_id` = ?");
$sql->execute([$ltr, $tid]);

echo json_encode(['result' => 'Successfully Updated']);

?>