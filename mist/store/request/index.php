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

    $title = "Request Game";
    require(dirname(__DIR__, $steps)."/header/index.php");
?>

<div>
    <form action="request.php" method="post" enctype="multipart/form-data">
        <h1>Request Game</h1>

        <input type="text" name="gameName" placeholder="Name (64 characters maximum)">
        <textarea name="gameDescription" placeholder="Description (1028 characters maximum)" rows="5"></textarea>
        <input type="text" name="gameGenre" placeholder="Genre (16 characters maximum)">

        <div class="compatibility">
            <input type="checkbox" name="compatibleWindows">
            <label for="compatibleWindows">Windows</label>
            <input type="checkbox" name="compatibleMacOS">
            <label for="compatibleMacOS">MacOS</label>
            <input type="checkbox" name="compatibleLinux">
            <label for="compatibleLinux">Linux</label>
        </div>

        <div class="thumbnail_upload">
            <label for="gamePicture">Thumbnail</label>
            <input type="file" name="gamePicture" accept=".jpg, .jpeg, .png">
        </div>

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
                    echo "Please fill in all fields!";
                    break;
                case "emptyChecks":
                    echo "Please make sure there is a compatible operating system!";
                    break;
                case "emptyPicture":
                    echo "Please make sure you upload a picture!";
                    break;
                case "largeName":
                    echo "Please enter a name with less than 64 characters.";
                    break;
                case "largeDescription":
                    echo "Please enter a description with less than 1028 characters.";
                    break;
                case "largeGenre":
                    echo "Please enter a genre with less than 16 characters.";
                    break;
                case "largePicture":
                    echo "Please make sure the picture is less than 1200x600 pixels!";
                    break;
                case "none":
                    $gameID = $_GET["gameID"];
                    echo "Game requested! Check <a href=\"../game/index.php?gameID=$gameID\">here</a> often to see the status of your game request.";
                    break;
            }
            
            echo "</p>";
        ?>
    </form>
</div>

<?php
    require(dirname(__DIR__, $steps)."/footer/index.php")
?>