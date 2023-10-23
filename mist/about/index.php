<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $steps = 1;
    require(dirname(__DIR__, $steps)."/database.php");
    require(dirname(__DIR__, $steps)."/functions.php");

    $title = "About Mist";
    require(dirname(__DIR__, $steps)."/header/index.php");
?>

<div class="about">
    <div class="about-info">
        <h1>About Us</h1>

        <!--
            Our purpose, which was taken from our project overview in our docs branch.
        -->
        <ul>
            <li>We serve to allow users to search and browse a variety of games in our game library through a web browser.</li>
            <li>Users can chat and create discussions through our online community forums.</li>
            <li>Publishers can also publish their own games alongside our expanding library.</li>
        </ul>

        <h1>Our Team</h1>

        <!--
            Our team, which includes our names and pictures.
        -->

        <div class="members">
            <div class="member">
                <div class="border">
                    <img src="../images/profile/joey.jpg" />
                </div>

                <h2>Joey Luong</h2>
                <h3>Project Manager</h3>
            </div>

            <div class="member">
                <div class="border">
                    <img src="../images/profile/harrison.jpg" />
                </div>

                <h2>Harrison Baker</h2>
                <h3>Technical Manager</h3>
            </div>

            <div class="member">
                <div class="border">
                    <img src="../images/profile/huy.jpg" />
                </div>

                <h2>Huy Nguyen</h2>
                <h3>Front-End Programmer</h3>
            </div>
            
            <div class="member">
                <div class="border">
                    <img src="../images/profile/eric.jpg" />
                </div>

                <h2>Hongwei(Eric) Liao</h2>
                <h3>Back-End Programmer</h3>
            </div>
            
            <div class="member">
                <div class="border">
                    <img src="../images/profile/jon.jpg" />
                </div>

                <h2>Jon Kraft</h2>
                <h3>Back-End Programmer</h3>
            </div>
        </div>
    </div>
</div>

<?php
    require(dirname(__DIR__, $steps)."/footer/index.php")
?>