<?php

require("../core/database/db_config.php");

$trip_id = $_POST['trip_id'];
$trip_expense = $_POST['trip_expense'];
$receiving_quantity = $_POST['receiving_quantity'];
$penalty = $_POST['penalty'];

$pdo = new PDO($dsn, $user, $pass, $options);

$sql = $pdo->prepare("UPDATE `trips` SET `trip_end`=NOW(), `current_status` = 'Ready', `trip_expense` = ?, `receiving_quantity` = ?, `penalty` = ? WHERE `trip_id`=?");
$sql->execute([$trip_expense,$receiving_quantity,$penalty,$trip_id]);

$sql = $pdo->prepare("SELECT `trip_to` FROM `trips` WHERE `trip_id`=?");
$sql->execute([$trip_id]);
$dest = $sql->fetch()['trip_to'];

if(strtoupper($dest) == "RAIPUR")
{
	$sql = $pdo->prepare("SELECT `round_trip_id` FROM `trip_round_trip_xref` WHERE trip_id = ?");
	$sql->execute([$trip_id]);
	$rid = $sql->fetch()['round_trip_id'];

	$sql = $pdo->prepare("UPDATE `round_trip` SET `km_end` = ?, `on_road` = false WHERE `round_trip_id` = ?");
	$sql->execute([$km_end,$rid]);

	$sql = $pdo->prepare("UPDATE `trips` SET `current_status` = 'Ready' WHERE trip_id = ?");
	$sql->execute();
}

echo json_encode(["result" => "Trip Successfully Ended"]);

?>