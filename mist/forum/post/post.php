<?php
    $steps = 2;
    require(dirname(__DIR__, $steps)."/database.php");
    require(dirname(__DIR__, $steps)."/functions.php");

    /*
        Grabs the postID.
    */

    $postID = $_GET["postID"];

    /*
        Calls the database to delete or like a post.
    */

    if(isset($_POST["delete"])) {
        callProcedure("spDeletePost", $postID);
    }
    
    if(isset($_POST["like"])) {
        callProcedure("spAddLike", $postID);
    }

    header("location: ../post/index.php?postID=$postID");
?>