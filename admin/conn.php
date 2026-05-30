<?php
$conn = new mysqli("localhost", "root", "", "online_portal");
if ($conn->connect_error) {
    die("Error: Connection failed - ".$conn->connect_error);
}
?>