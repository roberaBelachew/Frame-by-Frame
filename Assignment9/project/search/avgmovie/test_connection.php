<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing connection...<br>";

require_once("../../db_connect.php");

echo "Connection file loaded...<br>";

if ($conn) {
    echo "Connection object exists!<br>";
    
  
    $result = $conn->query("SELECT * FROM Movies LIMIT 5");
    
    if ($result) {
        echo "Query successful! Found " . $result->num_rows . " rows<br><br>";
        
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row['movieID'] . " - Title: " . $row['title'] . "<br>";
        }
    } else {
        echo "Query failed: " . $conn->error;
    }
} else {
    echo "Connection failed!";
}
?>
