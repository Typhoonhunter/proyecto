<?php
    session_start();
    if (isset($_SESSION["Session"])) {
        unset($_SESSION["Session"]);
        session_unset(); 
        session_destroy();
        header('Location: ../index.html');
    } else {   
        header("HTTP/1.0 404 Not Found");
        echo "<br>";
        echo "<h1>Error 404: Not Found</h1>";
        echo "<br>";
        echo "<i>The requested URL was not found on this server. </i> <br>";
    }
?>