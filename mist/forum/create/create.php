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

    $postName = $_POST["postName"];
    $postDescription = $_POST["postDescription"];

    /*
        Checks if all fields in the form are filled out.
    */

    if(checkEmptyStrings($postName, $postDescription)) {
        returnError("emptyFields");
    }

    /*
        Checks if the required fields can be stored in the database based on their character count.
    */

    if(checkLargeString($postName, 64)) {
        returnError("largeName");
    }

    if(checkLargeString($postDescription, 1028)) {
        returnError("largeDescription");
    }

    /*
        Call the databse to create a new post.
    */

    callProcedure("spCreatePost", $userID, $postName, $postDescription);

    returnError("none");
?>