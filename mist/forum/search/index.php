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
    <form class="search-query" action="search.php" method="post">
        <div class="search-bar">
            <input type="text" name="search" placeholder="Search">
            <button name="submit"><img src="../../images/search.svg"></button>
        </div>

        <div class="search-options">
            <select name="sort">
                <option value="none">Sort By</option>
                <option value="date">Date</option>
                <option value="likes">Likes</option>
            </select>
        </div>

        <!--
            Displays the create post button if the user is logged in.
        -->

        <div class="post-create">
        <?php
                if(isset($_SESSION["userID"])) {
                    echo "<a href=\"../create/\">Create Post</a>";
                }
            ?>
        </div>
    </form>

    <div class="post-list">
        <?php
            /*
                Grabs search queries from the page url.
            */

            $search = getSearchQuery("search");
            $sort = getSearchQuery("sort");

            /*
                Grabs postIDs from the database based on search queries.
            */

            $posts = callProcedure("spGetPostsFromSearch", $search, $sort);

            foreach($posts as $post) {
                $postID = $post["postID"];

                /*
                    Grabs data about the post from the database based on postID.
                */
                
                $post = callProcedure("spGetPostFromID", $postID)[0];
                
                $userID = $post["userID"];

                $postName = $post["postName"];
                $postAuthor = $post["postAuthor"];
                $postDescription = $post["postDescription"];
                $postLikes = $post["postLikes"];
                $postDate = $post["postDate"];

                echo "
                    <div class=\"post\" onclick=\"location.href='../post/index.php?postID=$postID';\" style=\"cursor: pointer;\">
                        <div class=\"post-info\">
                            <h1><img src=\"https://robohash.org/$postAuthor?set=set4\">$postAuthor</h1>
                            <h2>$postDate</h2>
                        </div>

                        <div class=\"post-content\">
                            <h3>$postName</h3>
                            <h4>$postDescription</h4>
                            <h5>$postLikes likes</h5>
                        </div>
                    </div>
                ";
            }
        ?>
    </div>
</div>

<?php
    require(dirname(__DIR__, $steps)."/footer/index.php")
?>