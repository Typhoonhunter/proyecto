<?php
    function connect(){
        $host       = "localhost";
        $root       = "root";
        $passwdroot = "";
        $dbname     = "lab11";
        // Create connection.
        $conn = new mysqli($host, $root, $passwdroot, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            // echo "Connection successful.<br>";
        }
        return $conn;
    }
    function disconnect($conn){
        mysqli_close($conn);
    }
?>
