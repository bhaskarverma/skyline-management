<?php

require("../database/db_config.php");

$mark_complete_id = $_POST['mark'];

$mark_complete_id = explode('-',$mark_complete_id)[2];

$pdo = new PDO($dsn, $user, $pass, $options);
$sql = $pdo->prepare("UPDATE `global_todo` SET `completed_on` = NOW(), `is_completed` = true  WHERE `item_id` = ?");
$sql->execute([$mark_complete_id]);

$res = ['res' => 'TODO Successfully Completed'];

echo json_encode($res);

?>