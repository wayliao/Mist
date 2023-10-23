<?php
    $steps = 2;
    require(dirname(__DIR__, $steps)."/database.php");
    require(dirname(__DIR__, $steps)."/functions.php");

    /*
        Redirects to the log in page if they did not access this script through the submit button.
    */

    if(!isset($_POST["submit"])) {
        header("location: ../login");
        exit();
    }

    /*
        Grabs data from the user-filled form.
    */
    
    $userEmail = $_POST["userEmail"];
    $userPassword = $_POST["userPassword"];

    /*
        Checks if all fields in the form are filled out.
    */

    if(checkEmptyStrings($userEmail, $userPassword)) {
        returnError("emptyFields");
    }

    /*
        Checks if the email entered exists in the database.
    */

    if(!getUserFromEmail($userEmail)) {
        returnError("emailNonexistent");
    }

    /*
        Checks if the password matches the email.
    */

    if(checkPasswordMatchesEmail($userEmail, $userPassword)) {
        returnError("incorrectPassword");
    }

    /*
        Logs the user in (see functions.php in the main directory).
    */

    loginUser($userEmail);

    returnError("none");
?>