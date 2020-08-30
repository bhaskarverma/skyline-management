<?php

include("../core/database/db_config.php");

$pdo = new PDO($dsn, $user, $pass, $options);
$sql = $pdo->prepare("INSERT INTO `vehicles`(`vehicle_no`, `breakdown`, `last_known_location`, `financer`, `make`, `tot_wheels`, `unladen_weight`, `vehicle_gvw`, `net_weight`, `emi_amount`, `emi_date`, `bank_name`, `bank_ac_no`, `emi_remaining`) VALUES (?,0,'Raipur Office',?,?,?,?,?,?,?,?,?,?,?)");
$sql->execute(array_values($_POST));

echo json_encode(['res' => 'Vehicle Successfully Added']);

?>