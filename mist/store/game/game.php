<?php
    $steps = 2;
    require(dirname(__DIR__, $steps)."/database.php");
    require(dirname(__DIR__, $steps)."/functions.php");

    /*
        Grabs the requestID.
    */

    $requestID = $_GET["requestID"];

    /*
        Call the database to accept or deny a game.
    */

    if(isset($_POST["accept"])) {
        $requestReason = $_POST["requestReason"];
        callProcedure("spUpdateRequest", $requestID, "accepted", $requestReason);
    } else if (isset($_POST["deny"])) {
        $requestReason = $_POST["requestReason"];
        callProcedure("spUpdateRequest", $requestID, "denied", $requestReason);
    }

    header("location: ../search");
    exit();
?>