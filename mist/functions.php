<?php
    // Generates the search query seen in the url.

    function formatSearchQuery($parameters) {
        if(checkEmptyStrings($parameters["search"])) {
            unset($parameters["search"]);
        }

        foreach ($parameters as $index => $parameter) {
            if($parameter == "none") {
                unset($parameters[$index]);
            }
        }
        return http_build_query($parameters);
    }

    // Gets a serach query in the url.

    function getSearchQuery($parameter) {
        if(isset($_GET[$parameter])) {
            return $_GET[$parameter];
        }

        return "";
    }

    // Checks if any of the strings are empty.

    function checkEmptyStrings(...$parameters) {
        foreach($parameters as $parameter) {
            if(empty($parameter)) {
                return true;
            }
        }

        return false;
    }

    // Checks if a string exceeds a defined length.

    function checkLargeString($string, $length) {
        return strlen($string) > $length;
    }

    // Checks is all booleans are false.

    function checkEmptyBooleans(...$parameters) {
        foreach($parameters as $parameter) {
            if($parameter == 1) {
                return false;
            }
        }

        return true;
    }

    // Checks that all strings match.

    function checkDifferentStrings(...$parameters) {
        return $parameters[0] != $parameters[1];
    }

    // Checks that an email is in proper format.

    function checkInvalidEmail($userEmail) {
        return !filter_var($userEmail, FILTER_VALIDATE_EMAIL);
    }

    // Checks that a password is greater than 8 characters.

    function checkInvalidPassword($userPassword) {
        return strlen($userPassword);
    }

    // Checks that an image is 1200x600 or less in dimensional size.

    function checkLargePicture($picture) {
        $pictureDimensions = getimagesize($picture);
        $pictureWidth = $pictureDimensions[0];
        $pictureHeight = $pictureDimensions[1];

        if ($pictureWidth > 1200) {
            return true;
        }

        if ($pictureHeight > 600) {
            return true;
        }

        return false;
    }

    // Gets user info from the database based on email.

    function getUserFromEmail($userEmail) {
        return callProcedure("spGetUserFromEmail", $userEmail)[0];
    }

    // Checks that the hashed password matchs the user-filled password.

    function checkPasswordMatchesEmail($userEmail, $userPassword) {
        $user = getUserFromEmail($userEmail);

        return !password_verify($userPassword, $user["userPassword"]);
    }

    // Creates a new session with userID and userRole saved.

    function loginUser($userEmail) {
        $user = getUserFromEmail($userEmail);
        
        session_start();

        $_SESSION["userID"] = $user["userID"];
        $_SESSION["userRole"] = $user["userRole"];
    }

    // Returns the url with the error tage attached.
    
    function returnError($error) {
        header("location: index.php?error=".$error);
        exit();
    }
?>