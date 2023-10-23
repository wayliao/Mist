<?php
    $steps = 2;
    require(dirname(__DIR__, $steps)."/database.php");
    require(dirname(__DIR__, $steps)."/functions.php");

    /*
        Redirects to the sign up page if they did not access this script through the submit button.
    */

    if(!isset($_POST["submit"])) {
        header("location: ../signup");
        exit();
    }

    /*
        Grabs data from the user-filled form.
    */
    
    $userFirstName = $_POST["userFirstName"];
    $userLastName = $_POST["userLastName"];
    $userEmail = $_POST["userEmail"];
    $userEmailVerify = $_POST["userEmailVerify"];
    $userPassword = $_POST["userPassword"];
    $userPasswordVerify = $_POST["userPasswordVerify"];

    /*
        Checks if all fields in the form are filled out.
    */

    if(checkEmptyStrings($userFirstName, $userLastName, $userEmail, $userPassword)) {
        returnError("emptyFields");
    }

    /*
        Checks if the required fields can be stored in the database based on their character count.
    */

    if(checkLargeString($userFirstName, 16)) {
        returnError("largeFirstName");
    }

    if(checkLargeString($userLastName, 16)) {
        returnError("largeLastName");
    }

    if(checkLargeString($userEmail, 64)) {
        returnError("largeEmail");
    }

    /*
        Checks if the email already exists on the database.
    */

    if(getUserFromEmail($userEmail)) {
        returnError("emailTaken");
    }

    /*
        Checks if the email is a proper email and the password is a least 8 characters long.
        Note: we don't have a max character count for the password since it is hashed.
    */

    if(checkInvalidEmail($userEmail)) {
        returnError("invalidEmailFormat");
    }

    if(checkInvalidPassword($userPassword)) {
        returnError("invalidPasswordFormat");
    }

    /*
        Checks if the two emails and the two passwords match to prevent typos and misinputs.
    */

    if(checkDifferentStrings($userEmail, $userEmailVerify)) {
        returnError("differentEmails");
    }

    if(checkDifferentStrings($userPassword, $userPasswordVerify)) {
        returnError("differentPasswords");
    }

    /*
        Hashes the user password to ensure user safety.
    */

    $userPasswordHash = password_hash($userPassword, PASSWORD_DEFAULT);

    /*
        Call the databse to create a new user.
    */

    callProcedure("spCreateUser", $userFirstName, $userLastName, $userEmail, $userPasswordHash);

    returnError("none");
?>