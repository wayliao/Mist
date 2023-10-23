<?php
    function getConnection() {
        $server = "23.236.61.136";
        $username = "root";
        $password = "outlet";
        $name = "mist";

        /*
            Use this for your phpMyAdmin.

            $server = "localhost";
            $username = "root";
            $password = "";
            $name = "mist";
        */

        return mysqli_connect($server, $username, $password, $name);
    }

    function callProcedure($procedure, ...$parameters) {                    // Usage example: callProcedure("spExample", parameter1, parameter2, parameter3);.
        $connection = getConnection();

        $questions = rtrim(str_repeat("?,", count($parameters)), ", ");     // Creates a string like: "?,?,?".

        $query = "CALL $procedure($questions)";                             // Creates a string like: "CALL spExample(?,?,?)".
        
        $statement = $connection->prepare($query);                          // Prepares SQL query.

        $strings = str_repeat("s", count($parameters));                     // Creates a string like: "sss".

        $statement->bind_param($strings, ...$parameters);                   // Assigns each parameter as a string variable.

        $statement->execute();

        $table = $statement->get_result();                                  // Fetches the output table.
        
        if($table) {
            while($records[] = $table->fetch_array()) {                     // Creates a 2D array from the table.
                continue;
            }
    
            array_pop($records);                                            // Removes last element because it's null.
    
            return $records;
        }
    }
?>