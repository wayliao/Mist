<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    /*
        Redirects to the search page if they are not logged in.
    */

    if(!isset($_SESSION["userID"])) {
        header("location: ../search");
        exit();
    }

    $steps = 2;
    require(dirname(__DIR__, $steps)."/database.php");
    require(dirname(__DIR__, $steps)."/functions.php");

    $title = "Create Post";
    require(dirname(__DIR__, $steps)."/header/index.php");
?>

<div>
    <form action="create.php" method="post">
        <h1>Create Post</h1>

        <input type="text" name="postName" placeholder="Name (64 characters maximum)">
        <textarea name="postDescription" placeholder="Description (1028 characters maximum)" rows="8"></textarea>
        
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
                case "largeName":
                    echo "Please enter a name with less than 64 characters.";
                    break;
                case "largeDescription":
                    echo "Please enter a description with less than 1028 characters.";
                    break;
                case "none":
                    echo "Post created!";
                    break;
            }
            
            echo "</p>";
        ?>
    </form>
</div>

<?php
    require(dirname(__DIR__, $steps)."/footer/index.php")
?>