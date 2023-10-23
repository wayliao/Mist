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

    $title = "Log In";
    require(dirname(__DIR__, $steps)."/header/index.php");
?>

<form action="login.php" method="post">
    <h1>Log In</h1>

    <!--
        Made a function to create text inputs so there isn't long lines of code.
    -->

    <?php 
        function createInput($type, $name, $placeholder) {
            echo "<input type=\"$type\" name=\"$name\" placeholder=\"$placeholder\">";
        }

        createInput("text", "userEmail", "Email");
        createInput("password", "userPassword", "Password");
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
            case "emailNonexistent":
                echo "The email that you entered does not match our records.";
                break;
            case "incorrectPassword":
                echo "The password that you entered is incorrect.";
                break;
            case "none":
                echo("You are logged in!");
                break;
        }
        
        echo "</p>";
    ?>
</form>
    
<?php
    require(dirname(__DIR__, $steps)."/footer/index.php")
?>