<?php

    /*
        Closes and destroys the window session, which contains data for the user when transferrign through webpages.
    */

    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    session_unset();
    session_destroy();

    header("location: ../login");
    exit();
?>