<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    /*
        Made functions to create links so there isn't long lines of code.
    */

    function createLink($href, $text) {
        global $steps;
        echo "<a href=\"".str_repeat("../", $steps)."$href\">$text</a>";
    }
        
    function createButton($class, $text) {
        return "<button class=\"$class\">$text</button>";
    }
?>

<html>
    <head>
        <meta charset="utf-8">

        <link rel="icon" href="<?php echo str_repeat("../", $steps) ?>images/misticon.png">

        <title><?php echo $title ?></title>
    </head>

    <body>
        <noscript>You need to enable JavaScript to access this page.</noscript>

        <link rel="stylesheet" href="<?php echo str_repeat("../", $steps) ?>header/styles.css">

        <header>
            <nav>
                <?php
                    /*
                        Creates text with hyperlinks.
                    */

                    createLink("store/search", "<img src=\"".str_repeat("../", $steps)."images/mistlogo.png\">");
                    createLink("store/search", "STORE");
                    createLink("forum/search", "FORUM");
                    createLink("about", "ABOUT");
                ?>
            </nav>

            <nav>
                <?php
                    /*
                        Creates buttons with hyperlinks.
                        Shows the appropriate buttons based on whether or not the user is logged in or not.
                    */

                    if(isset($_SESSION["userID"])) {
                        createLink("account/profile/index.php?userID=".$_SESSION["userID"], createButton("profile", "Profile"));
                        createLink("account/logout", createButton("logout", "Log Out"));
                    } else {
                        createLink("account/login", createButton("login", "Log In"));
                        createLink("account/signup", createButton("signup", "Sign Up"));
                    }
                ?>
            </nav>
        </header>

        <link rel="stylesheet" href="styles.css">