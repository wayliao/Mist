<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $steps = 2;
    require(dirname(__DIR__, $steps)."/database.php");
    require(dirname(__DIR__, $steps)."/functions.php");

    $title = "Mist Store";
    require(dirname(__DIR__, $steps)."/header/index.php");
?>

<div class="search">
    <div class="game-list">
        <?php
            /*
                Grabs gameIDs what are pending from the database.
            */

            $games = callProcedure("spGetGamesFromSearch", "", "", "", "pending");

            foreach($games as $game) {
                $gameID = $game["gameID"];

                /*
                    Grabs data about the post from the database based on gameID.
                */

                $game = callProcedure("spGetGameFromID", $gameID)[0];

                $gameName = $game["gameName"];
                $gameDescription = $game["gameDescription"];
                $gameGenre = $game["gameGenre"];
                $gameDate = $game["gameDate"];
                $gamePicture = base64_encode($game["gamePicture"]);
                $compatibleWindows = $game["compatibleWindows"];
                $compatibleWindows = ($game["compatibleWindows"] == 1) ? "<img src=\"../../images/os/windows.svg\">" : "";
                $compatibleMacOS = ($game["compatibleMacOS"] == 1) ? "<img src=\"../../images/os/macos.svg\">" : "";
                $compatibleLinux = ($game["compatibleLinux"] == 1) ? "<img src=\"../../images/os/linux.svg\">" : "";

                echo "
                    <a class=\"game\" href=\"../game/index.php?gameID=$gameID\">
                        <div class=\"game-info\">
                            <h1>$gameName</h1>
                            <h2>$gameGenre $compatibleWindows $compatibleMacOS $compatibleLinux</h2>
                        </div>

                        <img class=\"game-picture\" src = \"data:image/png;base64,$gamePicture\">
                    </a>
                ";
            }
        ?>
    </div>
</div>

<?php
    require(dirname(__DIR__, $steps)."/footer/index.php")
?>