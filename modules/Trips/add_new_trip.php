<?php

require("../core/database/db_config.php");

$round_trip_id = $_POST['round_trip_id'];

if($round_trip_id == "new")
{
    $pdo = new PDO($dsn, $user, $pass, $options);

    //Preparing Data to Insert in Trips Table
    $data_for_trip = [
    	$_POST['trip_from'],
    	$_POST['trip_to'],
    	$_POST['vehicle'],
    	$_POST['driver'],
    	$_POST['material'],
    	$_POST['quantity'],
    	$_POST['rate'],
    	$_POST['fuel_ltr'],
    	$_POST['fuel_money'],
    	$_POST['freight'],
        $_POST['trip_type'],
        $_POST['booking_party'],
        $_POST['paying_party'],
        $_POST['trip_advance']
    ];

    //Inserting Data into Trips Table
    $sql = $pdo->prepare("INSERT INTO `trips` (`trip_from`, `trip_to`, `trip_start`,`current_status`, `last_updated`, `vehicle`, `driver`, `material`, `quantity`, `rate`, `fuel_ltr`, `fuel_money`, `freight`, `trip_type`, `booking_party`, `paying_party`, `trip_advance`) VALUES (?, ?, now(), 'Ready', now(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->execute($data_for_trip);
    $trip_id = $pdo->lastInsertId();

    //Creating a new Round Trip Entry
    $sql = $pdo->prepare("INSERT INTO `round_trip`(`km_start`, `km_end`, `on_road`) VALUES (?, 0, true)");
    $sql->execute([$_POST['km_start']]);
    $round_trip_id = $pdo->lastInsertId();

    //Pairing the new Trip with this Round Trip
    $sql = $pdo->prepare("INSERT INTO `trip_round_trip_xref`(`round_trip_id`, `trip_id`) VALUES (?, ?)");
    $sql->execute([$round_trip_id, $trip_id]);

    //Inserting into Ledger History
    $comments = "Trip Freight for Trip ID ".$trip_id;
    $sql = $pdo->prepare("INSERT INTO `party_ledger_history`(`party_id`,`transaction_amount`,`transaction_comments`,`transaction_datetime`) VALUES (?,?,?,NOW())");
    $sql->execute([$_POST['paying_party'],$_POST['freight'],$comments]);

    if($_POST['advance_by'] == "party")
    {  
        $comments = "Trip Advance for Trip ID ".$trip_id;
        $sql = $pdo->prepare("INSERT INTO `party_ledger_history`(`party_id`,`transaction_amount`,`transaction_comments`,`transaction_datetime`) VALUES (?,?,?,NOW())");
        $sql->execute([$_POST['paying_party'],$_POST['trip_advance'],$comments]);
    }

    if($_POST['fuel_filled_by'] == "party")
    {
        $comments = "Fuel Advance for Trip ID ".$trip_id;
        $sql = $pdo->prepare("INSERT INTO `party_ledger_history`(`party_id`,`transaction_amount`,`transaction_comments`,`transaction_datetime`) VALUES (?,?,?,NOW())");
        $sql->execute([$_POST['paying_party'],$_POST['fuel_money'],$comments]);
    }

    //Updating Ledger for Party
    $tot_money = $_POST['freight'];

    if($_POST['advance_by'] == "party")
    {
        $tot_money -= $_POST['trip_advance'];
    }

    if($_POST['fuel_filled_by'] == "party")
    {
        $tot_money -= $_POST['fuel_money'];
    }

    $sql = $pdo->prepare("UPDATE `party_ledger` SET `balance` = `balance` + ? WHERE `party_id` = ?");
    $sql->execute([$tot_money, $_POST['paying_party']]);

 }
 else
 {
    $pdo = new PDO($dsn, $user, $pass, $options);

    //Preparing Data to Insert in Trips Table
    $data_for_trip = [
    	$_POST['trip_from'],
    	$_POST['trip_to'],
    	$_POST['vehicle'],
    	$_POST['driver'],
    	$_POST['material'],
    	$_POST['quantity'],
    	$_POST['rate'],
    	$_POST['fuel_ltr'],
    	$_POST['fuel_money'],
    	$_POST['freight'],
        $_POST['trip_type'],
        $_POST['booking_party'],
        $_POST['paying_party'],
        $_POST['trip_advance']
    ];

    //Inserting Data into Trips Table
    $sql = $pdo->prepare("INSERT INTO `trips` (`trip_from`, `trip_to`, `trip_start`,`current_status`, `last_updated`, `vehicle`, `driver`, `material`, `quantity`, `rate`, `fuel_ltr`, `fuel_money`, `freight`, `trip_type`, `booking_party`, `paying_party`, `trip_advance`) VALUES (?, ?, now(), 'Ready', now(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->execute($data_for_trip);
    $trip_id = $pdo->lastInsertId();

    //Pairing the new Trip with this Round Trip
    $sql = $pdo->prepare("INSERT INTO `trip_round_trip_xref`(`round_trip_id`, `trip_id`) VALUES (?, ?)");
    $sql->execute([$round_trip_id, $trip_id]);

    //Inserting into Ledger History
    $comments = "Trip Freight for Trip ID ".$trip_id;
    $sql = $pdo->prepare("INSERT INTO `party_ledger_history`(`party_id`,`transaction_amount`,`transaction_comments`,`transaction_datetime`) VALUES (?,?,?,NOW())");
    $sql->execute([$_POST['paying_party'],$_POST['freight'],$comments]);

    if($_POST['advance_by'] == "party")
    {  
        $comments = "Trip Advance for Trip ID ".$trip_id;
        $sql = $pdo->prepare("INSERT INTO `party_ledger_history`(`party_id`,`transaction_amount`,`transaction_comments`,`transaction_datetime`) VALUES (?,?,?,NOW())");
        $sql->execute([$_POST['paying_party'],$_POST['trip_advance'],$comments]);
    }

    if($_POST['fuel_filled_by'] == "party")
    {
        $comments = "Fuel Advance for Trip ID ".$trip_id;
        $sql = $pdo->prepare("INSERT INTO `party_ledger_history`(`party_id`,`transaction_amount`,`transaction_comments`,`transaction_datetime`) VALUES (?,?,?,NOW())");
        $sql->execute([$_POST['paying_party'],$_POST['fuel_money'],$comments]);
    }

    //Updating Ledger for Party
    $tot_money = $_POST['freight'];

    if($_POST['advance_by'] == "party")
    {
        $tot_money -= $_POST['trip_advance'];
    }

    if($_POST['fuel_filled_by'] == "party")
    {
        $tot_money -= $_POST['fuel_money'];
    }

    $sql = $pdo->prepare("UPDATE `party_ledger` SET `balance` = `balance` + ? WHERE `party_id` = ?");
    $sql->execute([$tot_money, $_POST['paying_party']]);
 }

 header("Location: /?module=Trips&page=Trips");
?>