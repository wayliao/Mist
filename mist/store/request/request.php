<?php
    $steps = 2;
    require(dirname(__DIR__, $steps)."/database.php");
    require(dirname(__DIR__, $steps)."/functions.php");
    
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    /*
        Redirects to the search page if they did not access this script through the submit button.
    */

    if(!isset($_POST["submit"])) {
        header("location: ../search");
        exit();
    }

     /*
        Grabs data from the user-filled form.
    */
    
    $userID = $_SESSION["userID"];

    $gameName = $_POST["gameName"];
    $gameDescription = $_POST["gameDescription"];
    $gameGenre = $_POST["gameGenre"];
    $compatibleWindows = isset($_POST["compatibleWindows"]) ? 1 : 0;
    $compatibleMacOS = isset($_POST["compatibleMacOS"]) ? 1 : 0;
    $compatibleLinux = isset($_POST["compatibleLinux"]) ? 1 : 0;
    $gamePicture = $_FILES["gamePicture"]["tmp_name"];
    $gamePictureBLOB = file_get_contents($gamePicture);

    /*
        Checks if all fields in the form are filled out.
    */

    if(checkEmptyStrings($gameName, $gameDescription, $gameGenre)) {
        returnError("emptyFields");
    }

    if(checkEmptyBooleans($compatibleWindows, $compatibleMacOS, $compatibleLinux)) {
        returnError("emptyChecks");
    }

    if(checkEmptyStrings($gamePictureBLOB)) {
        returnError("emptyPicture");
    }

    /*
        Checks if the required fields can be stored in the database based on their character count.
    */

    if(checkLargeString($gameName, 64)) {
        returnError("largeName");
    }

    if(checkLargeString($gameDescription, 1028)) {
        returnError("largeDescription");
    }

    if(checkLargeString($gameGenre, 16)) {
        returnError("largeGenre");
    }

    if(checkLargePicture($gamePicture)) {
        returnError("largePicture");
    }

    /*
        Call the databse to create a new game.
    */

    $gameID = callProcedure("spCreateRequest", $userID, $gameName, $gameDescription, $gameGenre, $gamePictureBLOB, $compatibleWindows, $compatibleMacOS, $compatibleLinux)[0]["gameID"];

    returnError("none&gameID=$gameID");
?>