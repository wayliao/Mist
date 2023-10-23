<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    /*
        Redirects to the user profile if they are already logged in.
    */

    if(isset($_SESSION["userID"])) {
        header("location: ../profile/index.php?userID=".$_SESSION["userID"]);
        exit();
    }

    $steps = 2;
    require(dirname(__DIR__, $steps)."/database.php");
    require(dirname(__DIR__, $steps)."/functions.php");

    $title = "Sign Up";
    require(dirname(__DIR__, $steps)."/header/index.php");
?>

<form action="signup.php" method="post">
    <h1>Sign Up</h1>

    <!--
        Made a function to create text inputs so there isn't long lines of code.
    -->

    <?php 
        function createInput($type, $name, $placeholder) {
            echo "<input type=\"$type\" name=\"$name\" placeholder=\"$placeholder\">";
        }

        createInput("text", "userFirstName", "First Name (16 characters maximum)");
        createInput("text", "userLastName", "Last Name (16 characters maximum)");
        createInput("text", "userEmail", "Email (64 characters maximum)");
        createInput("text", "userEmailVerify", "Verify Email");
        createInput("password", "userPassword", "Password (8 characters minimum)");
        createInput("password", "userPasswordVerify", "Verify Password");
    ?>

    <button name="submit">Submit</button>

    <!--
        Displays an error message based on the error attached in the url.
    -->

    <?php
        if(!isset($_GET["error"])) {
            return;
        }
        
        $error = $_GET["error"];
        
        echo "<p>";

        switch($_GET["error"]) {
            case "emptyFields":
                echo "Please fill in all fields.";
                break;
            case "largeFirstName":
                echo "Please enter a first name with less than 16 characters.";
                break;
            case "largeLastName":
                echo "Please enter a last name with less than 16 characters.";
                break;
            case "largeEmail":
                echo "Please enter an email with less than 64 characters.";
                break;
            case "emailTaken":
                echo "This email is taken. <a href=\"../login\">Log In?</button>";
                break;
            case "invalidEmailFormat":
                echo "Please enter a valid email address.";
                break;
            case "invalidPasswordFormat":
                echo "Please enter a valid password (minimum 8 characters).";
                break;
            case "differentEmails":
                echo "Please enter the same email address in both email address fields.";
                break;
            case "differentPasswords":
                echo "Please enter the same password in both password fields.";
                break;
            case "none":
                echo "You are signed up! <a href=\"../login\">Log In?</button>";
                break;
        }
        
        echo "</p>";
    ?>
</form>
    
<?php
    require(dirname(__DIR__, $steps)."/footer/index.php")
?>