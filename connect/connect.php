<?php
    $servername = "localhost";
    $username = "cnstudios";
    $password = "Chinguyen5161#";
    $dbname = "cnstudios";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>