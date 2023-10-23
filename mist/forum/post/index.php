<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    /*
        Redirects to the search page if there is no postID in the url.
    */

    if(!isset($_GET["postID"])) {
        header("location: ../search");
        return;
    }

    $steps = 2;
    require(dirname(__DIR__, $steps)."/database.php");
    require(dirname(__DIR__, $steps)."/functions.php");

    $postID = $_GET["postID"];
    
    /*
        Grabs data about the post from the database based on postID.
    */

    $post = callProcedure("spGetPostFromID", $postID)[0];
    
    $postAuthor = $post["postAuthor"];
    $postName = $post["postName"];
    $postDescription = $post["postDescription"];
    $postLikes = $post["postLikes"];
    $postDate = $post["postDate"];
    $postDeleted = $post["postDeleted"];

    $postName = ($postDeleted == 1 ? "[DELETED] " . $postName: $postName);

    $title = $postName;
    require(dirname(__DIR__, $steps)."/header/index.php");
?>

<div class="post">
    <form action="post.php? <?php echo "postID=$postID" ?>" method="post">
        <?php
            /*
                Profile picture that is hash-generated from RoboHash.
            */

            echo "
                <div class=\"post-info\">
                    <h1><img src=\"https://robohash.org/$postAuthor?set=set4\">$postAuthor</h1>
                    <h2>$postDate</h2>
                </div>

                <div class=\"post-content\">
                    <h3>$postName</h3>
                    <h4>$postDescription</h4>
                    <h5>$postLikes likes</h5>
                </div>
            ";

            /*
                Displays the like button if the user is logged in.
                Displats the delete button if the user is an admin or mod.
            */

            if(isset($_SESSION["userID"])) {
                echo "
                    <button class=\"like\" name=\"like\"><img src=\"".str_repeat("../", $steps)."images/like.svg\"></button>
                ";
                if($_SESSION["userRole"] == "admin" || $_SESSION["userRole"] == "mod") {
                    if($postDeleted == 0) {
                        echo "
                            <button class=\"delete\" name=\"delete\"><img src=\"".str_repeat("../", $steps)."images/delete.svg\"></button>
                        ";
                    }
                }
            }
        ?>
    </form>
</div>

<?php
    require(dirname(__DIR__, $steps)."/footer/index.php")
?>