<?php

require("../core/database/db_config.php");

$pid = $_GET['party'];
$amount = $_GET['amount'];

$pdo = new PDO($dsn, $user, $pass, $options);

$sql = $pdo->prepare("INSERT INTO `party_ledger_history` (`party_id`,`transaction_amount`,`transaction_comments`,`transaction_datetime`) VALUES (?,?,'Payment Recorded',NOW())");
$sql->execute([$pid,$amount]);

$sql = $pdo->prepare("UPDATE `party_ledger` SET `balance` = `balance` - ? WHERE `party_id` = ?");
$sql->execute([$amount,$pid]);

echo json_encode(['status' => 'Successfully Recorded']);

?>