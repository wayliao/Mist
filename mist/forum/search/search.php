<?php
    $steps = 2;
    require(dirname(__DIR__, $steps)."/database.php");
    require(dirname(__DIR__, $steps)."/functions.php");

    /*
        Redirects to the search page if they did not access this script through the submit button.
    */

    if(!isset($_POST["submit"])) {
        header("location: ../search");
        exit();
    }

    /*
        Grabs data from the user-filled form.
    */

    $searchQuery = [
        "search" =>  $_POST["search"],
        "sort" =>  $_POST["sort"]
    ];

    /*
        Generates the query you see in the url.
    */

    $searchQuery = formatSearchQuery($searchQuery);

    /*
        Redirects to the search page based on user-filled input.
    */

    if(!empty($searchQuery)) {
        header("location: index.php?".$searchQuery);
    } else {
        header("location: ../search");
    }

    exit();
?>