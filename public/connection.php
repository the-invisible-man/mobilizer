<?php

// Here we create the database connection and then share it among
// all the other files that need a database connection.
$connection = mysqli_connect('192.168.10.10', 'homestead', 'secret', 'homestead');

// If connection returns false then the connection was not successful.
if (!$connection) {
    die("There was an error connecting to the database");
}
